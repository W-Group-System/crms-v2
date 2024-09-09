<?php

namespace App\Http\Controllers;
use App\Client;
use App\Address;
use App\Country;
use App\User;
use App\PaymentTerms;
use App\Region;
use App\Area;
use App\BusinessType;
use App\SalesApprovers;
use App\Contact;
use App\FileClient;
use App\Industry;
use App\Exports\CurrentClientExport;
use App\Exports\ProspectClientExport;
use App\Exports\ArchivedClientExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Collective\Html\FormFacade as Form;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    // Current List
    public function index(Request $request)
    {
        $request->session()->put('last_client_page', url()->full());
        $search = $request->input('search');
        $sort = $request->get('sort', 'Name'); // Default to 'Name' if no sort is specified
        $direction = $request->get('direction', 'asc'); // Default to ascending order
        $role = auth()->user()->role;
        $fetchAll = $request->input('fetch_all', false); // Get the fetch_all parameter
        $entries = $request->input('number_of_entries', 10); // Default to 10 entries per page

        // Validate sort and direction parameters
        $validSorts = ['Name', 'BuyerCode', 'Type', 'ClientIndustryId', 'PrimaryAccountManagerId'];
        if (!in_array($sort, $validSorts)) {
            $sort = 'Name';
        }
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'desc';
        }

        // Map search terms to type values
        $typeMap = [
            'Local' => '1',
            'local' => '1',
            'International' => '2',
            'international' => '2',
        ];
        
        // Default to all types if no specific type is searched
        $typeSearch = $typeMap[$search] ?? null;

        // Build the query with relationships
        $clients = Client::with(['industry', 'userById', 'userByUserId', 'userByUserId2'])
            ->where('Status', '2')  // Filter by status
            ->where(function ($query) use ($search, $typeSearch) {
                // Search by type
                if ($typeSearch) {
                    $query->where('Type', $typeSearch);
                } else {
                    $query->whereRaw('CAST(Type AS CHAR) LIKE ?', ['%' . $search . '%'])  // Search in Type field
                        ->orWhere('BuyerCode', 'LIKE', '%' . $search . '%')  // Search in BuyerCode field
                        ->orWhere('Name', 'LIKE', '%' . $search . '%')  // Search in Name field
                        ->orWhereHas('userById', function ($q) use ($search) {
                            $q->where('full_name', 'LIKE', '%' . $search . '%');  // Search in related userById
                        })
                        ->orWhereHas('userByUserId', function ($q) use ($search) {
                            $q->where('full_name', 'LIKE', '%' . $search . '%');  // Search in related userByUserId
                        })
                        ->orWhereHas('userByUserId2', function ($q) use ($search) {
                            $q->where('full_name', 'LIKE', '%' . $search . '%');  // Search in related userByUserId2
                        })
                        ->orWhereHas('industry', function ($q) use ($search) {
                            $q->where('Name', 'LIKE', '%' . $search . '%');  // Search in related industry
                        });
                }
            })
            ->when(optional($role)->type, function($q) use ($role) {
                if ($role->type == "IS") {
                    $q->where('Type', '2');
                } elseif ($role->type == "LS") {
                    $q->where('Type', '1');
                }
            })
            ->orderBy($sort, $direction);

        if ($fetchAll) {
            $currentClient = $clients->get(); // Fetch all results
            return response()->json($currentClient); // Return JSON response for copying
        } else {
            $currentClient = $clients->paginate($entries); // Default pagination
            return view('clients.index', [
                'search' => $search,
                'currentClient' => $currentClient,
                'fetchAll' => $fetchAll,
                'entries' => $entries
            ]);
        }
    }

    // Prospect List
    public function prospect(Request $request)
    {   
        
        $request->session()->put('last_client_page', url()->full());
        $search = $request->input('search');
        $sort = $request->get('sort', 'Name'); // Default to 'Name' if no sort is specified
        $direction = $request->get('direction', 'asc'); // Default to ascending order
        $role = auth()->user()->role;
        $fetchAll = $request->input('fetch_all', false); // Get the fetch_all parameter
        $entries = $request->input('number_of_entries', 10); // Default to 10 entries per page

        // Validate sort and direction parameters
        $validSorts = ['Name', 'BuyerCode', 'Type', 'ClientIndustryId', 'PrimaryAccountManagerId'];
        if (!in_array($sort, $validSorts)) {
            $sort = 'BuyerCode';
        }
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'desc';
        }
    
        // Map search terms to type values
        $typeMap = [
            'Local' => '1',
            'local' => '1',
            'International' => '2',
            'international' => '2',
        ];
        
        // Default to all types if no specific type is searched
        $typeSearch = $typeMap[$search] ?? null;

        $clients = Client::with(['industry', 'userById', 'userByUserId', 'userByUserId2'])
            ->where('Status', '1')  // Filter by status
            ->where(function ($query) use ($search, $typeSearch) {
                // Search by type
                if ($typeSearch) {
                    $query->where('Type', $typeSearch);
                } else {
                    $query->whereRaw('CAST(Type AS CHAR) LIKE ?', ['%' . $search . '%'])  // Search in Type field
                        ->orWhere('BuyerCode', 'LIKE', '%' . $search . '%')  // Search in BuyerCode field
                        ->orWhere('Name', 'LIKE', '%' . $search . '%')  // Search in Name field
                        ->orWhereHas('userById', function ($q) use ($search) {
                            $q->where('full_name', 'LIKE', '%' . $search . '%');  // Search in related userById
                        })
                        ->orWhereHas('userByUserId', function ($q) use ($search) {
                            $q->where('full_name', 'LIKE', '%' . $search . '%');  // Search in related userByUserId
                        })
                        ->orWhereHas('userByUserId2', function ($q) use ($search) {
                            $q->where('full_name', 'LIKE', '%' . $search . '%');  // Search in related userByUserId2
                        })
                        ->orWhereHas('industry', function ($q) use ($search) {
                            $q->where('Name', 'LIKE', '%' . $search . '%');  // Search in related industry
                        });
                }
            })
            ->when(optional($role)->type, function($q) use ($role) {
                if ($role->type == "IS") {
                    $q->where('Type', '2');
                } elseif ($role->type == "LS") {
                    $q->where('Type', '1');
                }
            })
            ->orderBy($sort, $direction);

        if ($fetchAll) {
            $prospectClient = $clients->get(); // Fetch all results
            return response()->json($prospectClient); // Return JSON response for copying
        } else {
            $prospectClient = $clients->paginate($entries); // Default pagination
            return view('clients.prospect', [
                'search' => $search,
                'prospectClient' => $prospectClient,
                'fetchAll' => $fetchAll,
                'entries' => $entries
            ]);
        }
    }

    // Archived List
    public function archived(Request $request)
    {   
        // Save the current URL in session (if needed for page tracking)
        $request->session()->put('last_client_page', url()->full());
        $search = $request->input('search');
        $sort = $request->get('sort', 'Name'); // Default to 'Name' if no sort is specified
        $direction = $request->get('direction', 'asc'); // Default to ascending order
        $role = auth()->user()->role;
        $fetchAll = $request->input('fetch_all', false); // Get the fetch_all parameter
        $entries = $request->input('number_of_entries', 10); // Default to 10 entries per page

        $validSorts = ['Name', 'BuyerCode', 'Type', 'ClientIndustryId', 'PrimaryAccountManagerId'];
        if (!in_array($sort, $validSorts)) {
            $sort = 'Name';
        }
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'desc';
        }
    
        // Map search terms to type values
        $typeMap = [
            'Local' => '1',
            'local' => '1',
            'International' => '2',
            'international' => '2',
        ];
        
        // Default to all types if no specific type is searched
        $typeSearch = $typeMap[$search] ?? null;

        $clients = Client::with(['industry', 'userById', 'userByUserId', 'userByUserId2'])
            ->where('Status', '5')  // Filter by status
            ->where(function ($query) use ($search, $typeSearch) {
                // Search by type
                if ($typeSearch) {
                    $query->where('Type', $typeSearch);
                } else {
                    $query->whereRaw('CAST(Type AS CHAR) LIKE ?', ['%' . $search . '%'])  // Search in Type field
                        ->orWhere('BuyerCode', 'LIKE', '%' . $search . '%')  // Search in BuyerCode field
                        ->orWhere('Name', 'LIKE', '%' . $search . '%')  // Search in Name field
                        ->orWhereHas('userById', function ($q) use ($search) {
                            $q->where('full_name', 'LIKE', '%' . $search . '%');  // Search in related userById
                        })
                        ->orWhereHas('userByUserId', function ($q) use ($search) {
                            $q->where('full_name', 'LIKE', '%' . $search . '%');  // Search in related userByUserId
                        })
                        ->orWhereHas('userByUserId2', function ($q) use ($search) {
                            $q->where('full_name', 'LIKE', '%' . $search . '%');  // Search in related userByUserId2
                        })
                        ->orWhereHas('industry', function ($q) use ($search) {
                            $q->where('Name', 'LIKE', '%' . $search . '%');  // Search in related industry
                        });
                }
            })
            ->when(optional($role)->type, function($q) use ($role) {
                if ($role->type == "IS") {
                    $q->where('Type', '2');
                } elseif ($role->type == "LS") {
                    $q->where('Type', '1');
                }
            })
            ->orderBy($sort, $direction);
        
        if ($fetchAll) {
            $archivedClient = $clients->get(); // Fetch all results
            return response()->json($archivedClient); // Return JSON response for copying
        } else {
            $archivedClient = $clients->paginate($entries); // Default pagination
            return view('clients.archived', [
                'search' => $search,
                'archivedClient' => $archivedClient,
                'fetchAll' => $fetchAll,
                'entries' => $entries
            ]);
        }
    }

    // Create
    public function create()
    {
        $data = [
            'clients'           => Client::all(),
            'users'             => User::all(),
            'payment_terms'     => PaymentTerms::all(),
            'regions'           => Region::all(),
            'countries'         => Country::all(),
            'areas'             => Area::all(),
            'business_types'    => BusinessType::all(),
            'industries'        => Industry::all(),
            'buyerCode'         => 'BCODE-' . now()->format('Ymd-His'),
        ];
        
        $loggedInUser = Auth::user();
        $role = $loggedInUser->role;
        $withRelation = optional($role)->type == 'LS' ? 'localSalesApprovers' : 'internationalSalesApprovers';
        
        if (optional($role)->name == 'Staff L2' || optional($role)->name == 'Department Admin') {
            $salesApprovers = SalesApprovers::where('SalesApproverId', $loggedInUser->id)->pluck('UserId');
            $primarySalesPersons = User::whereIn('id', $salesApprovers)->orWhere('id', $loggedInUser->id)->get();
            $secondarySalesPersons = User::where('id', $loggedInUser->salesApproverById->pluck('SalesApproverId'))->get();
        } else {
            $primarySalesPersons = User::where('id', $loggedInUser->id)->with($withRelation)->get();
            $secondarySalesPersons = User::whereIn('id', $loggedInUser->salesApproverById->pluck('SalesApproverId'))->get();
        }

        // Pass the data, primarySalesPersons, and secondarySalesPersons to the view
        return view('clients.create', array_merge($data, [
            'primarySalesPersons' => $primarySalesPersons,
            'secondarySalesPersons' => $secondarySalesPersons,
            'role' => $role
        ]));
    }

    public function getRegions(Request $request)
    {
        $type = $request->input('type');
        $regions = Region::where('Type', $type)->get(['id', 'name']); // Adjust field names if needed
        return response()->json($regions);
    }

    public function getAreas(Request $request)
    {
        $regionId = $request->input('regionId');
        $areas = Area::where('RegionId', $regionId)->get(['id', 'name']); // Adjust field names if needed
        return response()->json($areas);
    }

    // Store
    public function store(Request $request)
    {
        $rules = [
            'BuyerCode'                 => 'required|string|max:255',
            'PrimaryAccountManagerId'   => 'required|string',
            'Name'                      => 'required|string|max:255',
            'Type'                      => 'required|string|max:255',
            'ClientRegionId'            => 'required|string',
            'ClientAreaId'              => 'required|string',
            'ClientCountryId'           => 'required|string',        
            'ClientAreaId'              => 'required|string|max:255',
            'BusinessTypeId'            => 'required|string|max:255',
            'AddressType'               => 'required|array',
            'AddressType.*'             => 'required|string|max:255',
            'Address'                   => 'required|array',
            'Address.*'                 => 'required|string|max:255',
            'ContactName.*'             => 'required|string|max:255',
        ];  

        $customMessages = [
            'PrimaryAccountManagerId.required'  => 'The primary account manager field is required.',
            'Name.required'                     => 'The company name is required.',
            'ClientRegionId.required'           => 'The region field is required.',
            'ClientCountryId.required'          => 'The country field is required.',
            'ClientAreaId.required'             => 'The area field is required.',
            'BusinessTypeId.required'           => 'The business type is required.',
            'ClientIndustryId.required'         => 'The industry is required.',
            'AddressType.*.required'            => 'Each address type is required.',
            'Address.*.required'                => 'Each address is required.',
            'ContactName.*.required'            => 'Contact Name is required.'
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->toArray()]);
        }
        // Create client
        $client = Client::create($request->only([
            'BuyerCode', 'PrimaryAccountManagerId', 'SapCode', 'SecondaryAccountManagerId',
            'Name', 'TradeName', 'TaxIdentificationNumber', 'TelephoneNumber', 'PaymentTermId',
            'FaxNumber', 'Type', 'Website', 'ClientRegionId', 'Email', 'ClientCountryId',
            'Source', 'ClientAreaId', 'BusinessTypeId', 'ClientIndustryId', 'Status'
        ]));
        
        // Handle addresses if provided
        if ($request->has('AddressType') && $request->has('Address')) {
            foreach ($request->AddressType as $key => $addressType) {
                if (!empty($addressType) && !empty($request->Address[$key])) {
                    Address::create([
                        'CompanyId' => $client->id,
                        'AddressType' => $addressType,
                        'Address' => $request->Address[$key]
                    ]);
                }
            }
        }

        if ($request->has('ContactName') && is_array($request->ContactName)) {
            foreach ($request->ContactName as $key => $contactName) {
                if (!empty($contactName)) { // Corrected to use $contactName
                    Contact::create([
                        'CompanyId' => $client->id,
                        'ContactName' => $contactName,
                    ]);
                }
            }
        }        
       
        // Return success message
        return response()->json(['success' => 'Data Added Successfully.']);
    }

    // Edit
    public function edit($id)
    {
        $data = Client::findOrFail($id);
        $addresses = Address::where('CompanyId', $id)->get();
        $contacts = Contact::where('CompanyId', $id)->get();
        $files = FileClient::where('ClientId', $id)->get();
        $users = User::where('is_active', '1')->whereNull('deleted_at')->get();
       
        // dd($addresses);
        $collections = [
            'business_types' => BusinessType::all(),
            'payment_terms' => PaymentTerms::all(),
            'regions' => Region::all(),
            'countries' => Country::all(),
            'areas' => Area::all(),
            'industries' => Industry::all()
        ];
        
        return view('clients.edit', array_merge([
            'data' => $data,
            'users' => $users,
            'addresses' => $addresses,
            'contacts' => $contacts,
            'files' => $files
        ], $collections));
    }

    // Update
    public function update(Request $request, $id)
    {
        $rules = [
            'BuyerCode'                 => 'required|string|max:255',
            'PrimaryAccountManagerId'   => 'required|string',
            'Name'                      => 'required|string|max:255',
            'Type'                      => 'required|string|max:255',
            'ClientRegionId'            => 'required|string',
            'ClientAreaId'              => 'required|string',
            'ClientCountryId'           => 'required|string',
            'BusinessTypeId'            => 'required|string|max:255',
            'AddressIds'                => 'array', // Validate address IDs
            'AddressIds.*'              => 'nullable|integer|exists:clientcompanyaddresses,id',
            'AddressType'               => 'array|required',
            'AddressType.*'             => 'required|string|max:255',
            'Address'                   => 'array|required',
            'Address.*'                 => 'required|string|max:255',
        ];

        $customMessages = [
            'PrimaryAccountManagerId.required'  => 'The primary account manager field is required.',
            'Name.required'                     => 'The company name is required.',
            'ClientRegionId.required'           => 'The region field is required.',
            'ClientCountryId.required'          => 'The country field is required.',
            'ClientAreaId.required'             => 'The area field is required.',
            'BusinessTypeId.required'           => 'The business type is required.',
            'ClientIndustryId.required'         => 'The industry is required.',
            'AddressIds.*.exists'               => 'The address ID is invalid.',
            'AddressType.*.required'            => 'Each address type is required.',
            'Address.*.required'                => 'Each address is required.'
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->toArray()]);
        }

        // Update client details
        $client = Client::findOrFail($id);
        $client->update($request->only([
            'BuyerCode', 'PrimaryAccountManagerId', 'SapCode', 'SecondaryAccountManagerId',
            'Name', 'TradeName', 'TaxIdentificationNumber', 'TelephoneNumber', 'PaymentTermId',
            'FaxNumber', 'Type', 'Website', 'ClientRegionId', 'Email', 'ClientCountryId',
            'Source', 'ClientAreaId', 'BusinessTypeId', 'ClientIndustryId'
        ]));

        // Handle addresses
        if ($request->has('AddressType') && $request->has('Address')) {
            foreach ($request->AddressType as $key => $addressType) {
                if (!empty($addressType) && !empty($request->Address[$key])) {
                    $addressId = $request->AddressIds[$key] ?? null;
                    if ($addressId) {
                        // Update existing address
                        $address = Address::findOrFail($addressId);
                        $address->update([
                            'AddressType' => $addressType,
                            'Address' => $request->Address[$key]
                        ]);
                    } else {
                        // Create new address
                        Address::create([
                            'CompanyId' => $client->id,
                            'AddressType' => $addressType,
                            'Address' => $request->Address[$key]
                        ]);
                    }
                }
            }
        }

        // Return success message
        return response()->json(['success' => 'Data Updated Successfully.']);
    }

    // View
    public function view($id) 
    {
        $data = Client::with([
                'activities', 
                'srfClients', 
                'crrClients', 
                'rpeClients', 
                'srfClientFiles', 
                'sampleRequests',
                'crrClientFiles',
                'customerRequirements',
                'rpeClientFiles',
                'productEvaluations',
                'productFiles',
                'productFiles.product' 
        ])->findOrFail($id);
        // dd($data)->take(10);
        $addresses = Address::where('CompanyId', $id)->get();
        $users = User::all();
        $payment_terms = PaymentTerms::find($data->PaymentTermId);
        $regions = Region::find($data->ClientRegionId);
        $countries = Country::find($data->ClientCountryId);
        $areas = Area::find($data->ClientAreaId);
        $business_types = BusinessType::find($data->BusinessTypeId);
        $industries = Industry::find($data->ClientIndustryId);

        $primaryAccountManager = $users->firstWhere('user_id', $data->PrimaryAccountManagerId) ?? $users->firstWhere('id', $data->PrimaryAccountManagerId);
        $secondaryAccountManager = $users->firstWhere('user_id', $data->SecondaryAccountManagerId) ?? $users->firstWhere('id', $data->SecondaryAccountManagerId);

        // Ensure secondaryAccountManager is null if not found
        if (is_null($data->SecondaryAccountManagerId)) {
            $secondaryAccountManager = null;
        }
        
        $activities = $data->activities;
        $srfClients = $data->srfClients;
        $crrClients = $data->crrClients;
        $rpeClients = $data->rpeClients;
        $srfClientFiles = $data->srfClientFiles;
        $sampleRequests = $data->sampleRequests;
        $crrClientFiles = $data->crrClientFiles;
        $customerRequirements = $data->customerRequirements;
        $rpeClientFiles = $data->rpeClientFiles;
        $productEvaluations = $data->productEvaluations;
        $productFiles = $data->productFiles;

        return view('clients.view', compact(
            'data', 'primaryAccountManager', 'secondaryAccountManager', 'payment_terms', 
            'regions', 'countries', 'areas', 'business_types', 'industries', 'addresses', 'activities', 'srfClients', 'crrClients', 'rpeClients', 'srfClientFiles', 'sampleRequests', 'crrClientFiles', 'customerRequirements', 'rpeClientFiles', 'productEvaluations', 'productFiles'
        ));
    }

    // Activate Client
    public function activateClient($id) 
    {
        $client = Client::findOrFail($id);

        $client->Status = '2';
        $client->save();

        return response()->json(['message' => 'Client status updated to current successfully!']);
    }
    
    // Prospect Client
    public function prospectClient(Request $request, $id)
    {
        $client = Client::findOrFail($id);

        $client->Status = '1';
        $client->save();

        return response()->json(['message' => 'Client status updated to prospect successfully!']);
    }

    // Delete Client
    public function delete($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();

        return response()->json(['message' => 'Client deleted successfully!']);
    }

    // Archived Client
    public function archivedClient($id)
    {
        $client = Client::findOrFail($id);

        $client->Status = '5';
        $client->save();

        return response()->json(['message' => 'Client status updated to archived successfully!']);
    }

    // Add File
    public function addFiles(Request $request)
    {
        $clientId = $request->ClientId;
        $fileNames = $request->FileName;
        $files = $request->file('Path');

        // Check if file names and files are both arrays and not empty
        if (!is_array($fileNames) || !is_array($files) || empty($fileNames) || empty($files)) {
            return response()->json(['error' => 'No files or file names provided'], 400);
        }

        try {
            foreach ($files as $index => $file) {
                // Check if the file is valid
                if ($file && $file->isValid()) {
                    $fileClient = new FileClient;
                    $fileClient->ClientId = $clientId;
                    $fileClient->FileName = $fileNames[$index];

                    // Generate a unique file name and move the file
                    $fileName = time().'_'.$file->getClientOriginalName();
                    $file->move(public_path('attachments'), $fileName);

                    // Save the file path to the database
                    $fileClient->Path = "/attachments/" . $fileName;
                    $fileClient->save();
                }
            }

            return response()->json(['success' => 'Files successfully uploaded']);
        } catch (\Exception $e) {
            // Return a JSON response with an error message
            return response()->json(['error' => 'Error uploading files', 'message' => $e->getMessage()], 500);
        }
    }

    // Edit File
    public function editFile(Request $request, $id)
    {
        // Find the file client by ID
        $fileClient = FileClient::findOrFail($id);

        // Update the file client details
        $fileClient->FileName = $request->FileName;

        // Check if a new file is uploaded
        if ($request->hasFile('Path')) {
            $file = $request->file('Path');
            if ($file && $file->isValid()) {
                // Generate a unique file name and move the file
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('attachments'), $fileName);

                // Update the file path
                $fileClient->Path = "/attachments/" . $fileName;
            }
        }

        // Save the updated file client
        $fileClient->save();

        return response()->json(['success' => 'File updated successfully']);
    }
    // Delete File
    public function deleteFile($id)
    {
        $contact = FileClient::findOrFail($id);
        $contact->delete();

        return response()->json(['message' => 'File deleted successfully!']);
    }

    public function exportCurrentClient()
    {
        return Excel::download(new CurrentClientExport, 'Current Client.xlsx');
    }

    public function exportProspectClient()
    {
        return Excel::download(new ProspectClientExport, 'Prospect Client.xlsx');
    }

    public function exportArchivedClient()
    {
        return Excel::download(new ArchivedClientExport, 'Archived Client.xlsx');
    }

}

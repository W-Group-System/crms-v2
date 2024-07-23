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
use App\Contact;
use App\FileClient;
use App\Industry;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    // Current List
    public function index(Request $request)
    {   
        // $clients = Client::with(['industry'])->where('Status', '2')->orderBy('Id', 'desc')->get();
        // if(request()->ajax())   
        // {
        //     return datatables()->of($clients)
        //             ->addColumn('action', function ($data) {
        //                 $viewButton = '<a type="button" href="' . route("client.view", ["id" => $data->id]) . '" name="view" id="' . $data->id . '" class="edit btn btn-success">View</a>';
        //                 $editButton = '<a type="button" href="' . route("client.edit", ["id" => $data->id]) . '" name="edit" id="' . $data->id . '" class="edit btn btn-primary">Edit</a>';
        //                 return $viewButton . '&nbsp;&nbsp;' . $editButton;
        //             })
        //             ->rawColumns(['action'])
        //             ->make(true);
        // }
        // return view('clients.index', compact('clients'));
        
        $search = $request->input('search');
        $clients = Client::with(['industry', 'userById', 'userByUserId', 'userByUserId2'])
                        ->where('Status', '2')
                        ->where(function ($query) use ($search) {
                            $query->where('Type', 'LIKE', '%' . $search . '%')
                                ->orWhere('BuyerCode', 'LIKE', '%' . $search . '%')
                                ->orWhere('Name', 'LIKE', '%' . $search . '%')
                                ->orWhere('PrimaryAccountManagerId', 'LIKE', '%' . $search . '%')
                                ->orWhereHas('industry', function ($q) use ($search) {
                                    $q->where('Type', 'LIKE', '%' . $search . '%');
                                });
                        })
                        ->orderBy('id', 'desc')
                        ->paginate(10);
        // dd($clients);
        return view('clients.index', [
            'search' => $search,
            'clients' => $clients,
        ]);
    }

    // Prospect List
    // public function prospect()
    // {   
    //     $clients = Client::with(['industry'])->where('Status', '1')->orderBy('Id', 'desc')->get();
    //     if(request()->ajax())   
    //     {
    //         return datatables()->of($clients)
    //                 ->addColumn('action', function ($data) {
    //                     $viewButton = '<a type="button" href="' . route("client.view", ["id" => $data->id]) . '" name="view" id="' . $data->id . '" class="edit btn btn-success">View</a>';
    //                     $editButton = '<a type="button" href="' . route("client.edit", ["id" => $data->id]) . '" name="edit" id="' . $data->id . '" class="edit btn btn-primary">Edit</a>';
    //                     return $viewButton . '&nbsp;&nbsp;' . $editButton;
    //                 })
    //                 ->rawColumns(['action'])
    //                 ->make(true);
    //     }
    //     return view('clients.prospect', compact('clients')); 
    // }

    // Archived List
    // public function archived()
    // {   
    //     $clients = Client::with(['industry'])->where('Status', '5')->orderBy('Id', 'desc')->get();
    //     if(request()->ajax())   
    //     {
    //         return datatables()->of($clients)
    //                 ->addColumn('action', function ($data) {
    //                     $viewButton = '<a type="button" href="' . route("client.view", ["id" => $data->id]) . '" name="view" id="' . $data->id . '" class="edit btn btn-success">View</a>';
    //                     $editButton = '<a type="button" href="' . route("client.edit", ["id" => $data->id]) . '" name="edit" id="' . $data->id . '" class="edit btn btn-primary">Edit</a>';
    //                     return $viewButton . '&nbsp;&nbsp;' . $editButton;
    //                 })
    //                 ->rawColumns(['action'])
    //                 ->make(true);
    //     }
    //     return view('clients.archived', compact('clients')); 
    // }
    
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
        
        return view('clients.create', $data);
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
            'BusinessTypeId'            => 'required|string|max:255'
        ];  

        $customMessages = [
            'PrimaryAccountManagerId.required'  => 'The primary account manager field is required.',
            'Name.required'                     => 'The company name is required.',
            'ClientRegionId.required'           => 'The region field is required.',
            'ClientCountryId.required'          => 'The country field is required.',
            'ClientAreaId.required'             => 'The area field is required.',
            'BusinessTypeId.required'           => 'The business type is required.',
            'ClientIndustryId.required'         => 'The industry is required.'
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
       
        // Return success message
        return response()->json(['success' => 'Data Added Successfully.']);
    }

    // Edit
    public function edit($id)
    {
        $data = Client::find($id);
        $addresses = Address::where('CompanyId', $id)->get();
        $contacts = Contact::where('CompanyId', $id)->get();
        $files = FileClient::where('ClientId', $id)->get();

        $collections = [
            'business_types' => BusinessType::all(),
            'users' => User::all(),
            'payment_terms' => PaymentTerms::all(),
            'regions' => Region::all(),
            'countries' => Country::all(),
            'areas' => Area::all(),
            'industries' => Industry::all()
        ];

        return view('clients.edit', array_merge(['data' => $data, 'addresses' => $addresses, 'contacts' => $contacts, 'files' => $files], $collections));
    }

    // Update
    public function update(Request $request, $id)
    {
        $rules = [
            'BuyerCode'                 => 'required|string|max:255',
            'PrimaryAccountManagerId'   => 'required|string|max:255',
            'Name'                      => 'required|string|max:255',
            'ContactName.*'             => 'required|string|max:255',
            'Designation.*'             => 'nullable|string|max:255',
            'Birthday.*'                => 'nullable|date',
            'EmailAddress.*'            => 'nullable|email|max:255',
            'PrimaryTelephone.*'        => 'nullable|string|max:255',
            'SecondaryTelephone.*'      => 'nullable|string|max:255',
            'PrimaryMobile.*'           => 'nullable|string|max:255',
            'SecondaryMobile.*'         => 'nullable|string|max:255',
            'Skype.*'                   => 'nullable|string|max:255',
            'Viber.*'                   => 'nullable|string|max:255',
            'WhatsApp.*'                => 'nullable|string|max:255',
            'Facebook.*'                => 'nullable|string|max:255',
            'LinkedIn.*'                => 'nullable|string|max:255',
            'PaymentTermId'             => 'required|string|max:255',
            'Type'                      => 'required|string|max:255',
            'ClientRegionId'            => 'required|string|max:255',
            'ClientCountryId'           => 'required|string|max:255',
            'ClientAreaId'              => 'required|string|max:255',
            'BusinessTypeId'            => 'required|string|max:255',
            'ClientIndustryId'          => 'required|string|max:255',
            'Status'                    => 'required|string|max:255',
            'FileName.*'                => 'nullable|string|max:255',
            'Path.*'                    => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048'
        ];

        $customMessages = [
            'PrimaryAccountManagerId.required'  => 'The primary account manager field is required.',
            'ContactName.*.required'            => 'The contact name is required.',
            'Name.required'                     => 'The company name is required.',
            'ClientRegionId.required'           => 'The region field is required.',
            'ClientCountryId.required'          => 'The country field is required.',
            'ClientAreaId.required'             => 'The area field is required.',
            'BusinessTypeId.required'           => 'The business type is required.',
            'ClientIndustryId.required'         => 'The industry is required.',
            'Path.*.mimes'                      => 'The file must be a type of: jpg, jpeg, png, pdf, doc, docx.'
        ];

        $customAttributes = [
            'Path.*' => 'file',
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages, $customAttributes);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            // Customize the error message for file validation
            foreach ($errors as $key => $error) {
                $errors[$key] = preg_replace_callback('/Path\.(\d+)\.mimes/', function ($matches) {
                    // return 'The file at index ' . $matches[1] . ' must be: jpg, jpeg, png, pdf, doc, docx.';
                }, $error);
            }
            return response()->json(['errors' => $errors]);
        }

        Client::whereId($id)->update($request->only([
            'BuyerCode',
            'PrimaryAccountManagerId',
            'SapCode',
            'SecondaryAccountManagerId',
            'Name',
            'TradeName',
            'TaxIdentificationNumber',
            'TelephoneNumber',
            'PaymentTermId',
            'FaxNumber',
            'Type',
            'Website',
            'ClientRegionId',
            'Email',
            'ClientCountryId',
            'Source',
            'ClientAreaId',
            'BusinessTypeId',
            'ClientIndustryId',
            'Status'
        ]));

        $client = Client::find($id);  // Make sure to get the updated client

        if ($request->has('AddressType') && $request->has('Address')) {
            foreach ($request->AddressType as $key => $addressType) {
                if (!empty($addressType) && !empty($request->Address[$key])) {
                    $addressId = $request->AddressId[$key] ?? null;  // Get the address ID if available
        
                    if ($addressId) {
                        $address = Address::where('CompanyId', $client->id)
                                        ->where('id', $addressId)
                                        ->first();
                        if ($address) {
                            // Update the existing address record
                            $address->update([
                                'AddressType' => $addressType,
                                'Address' => $request->Address[$key]
                            ]);
                        }
                    } else {
                        // Create a new address record
                        Address::create([
                            'CompanyId' => $client->id,
                            'AddressType' => $addressType,
                            'Address' => $request->Address[$key]
                        ]);
                    }
                }
            }
        }

        if ($request->has('ContactName')) {
            foreach ($request->ContactName as $key => $contact_name) {
                if (!empty($contact_name)) {
                    $contactId = $request->ContactId[$key] ?? null;  // Get the contact ID if available
        
                    if ($contactId) {
                        $contact = Contact::where('CompanyId', $client->id)
                                          ->where('id', $contactId)
                                          ->first();
                        if ($contact) {
                            // Update the existing contact record
                            $contact->update([
                                'ContactName' => $contact_name,
                                'Designation' => $request->Designation[$key] ?? null,
                                'PrimaryTelephone' => $request->PrimaryTelephone[$key] ?? null,
                                'SecondaryTelephone' => $request->SecondaryTelephone[$key] ?? null,
                                'PrimaryMobile' => $request->PrimaryMobile[$key] ?? null,
                                'SecondaryMobile' => $request->SecondaryMobile[$key] ?? null,
                                'EmailAddress' => $request->EmailAddress[$key] ?? null,
                                'Skype' => $request->Skype[$key] ?? null,
                                'Viber' => $request->Viber[$key] ?? null,
                                'Facebook' => $request->Facebook[$key] ?? null,
                                'WhatsApp' => $request->WhatsApp[$key] ?? null,
                                'LinkedIn' => $request->LinkedIn[$key] ?? null,
                                'Birthday' => $request->Birthday[$key] ?? null
                            ]);
                        }
                    } else {
                        // Create a new contact record
                        Contact::create([
                            'CompanyId' => $client->id,
                            'ContactName' => $contact_name,
                            'Designation' => $request->Designation[$key] ?? null,
                            'PrimaryTelephone' => $request->PrimaryTelephone[$key] ?? null,
                            'SecondaryTelephone' => $request->SecondaryTelephone[$key] ?? null,
                            'PrimaryMobile' => $request->PrimaryMobile[$key] ?? null,
                            'SecondaryMobile' => $request->SecondaryMobile[$key] ?? null,
                            'EmailAddress' => $request->EmailAddress[$key] ?? null,
                            'Skype' => $request->Skype[$key] ?? null,
                            'Viber' => $request->Viber[$key] ?? null,
                            'Facebook' => $request->Facebook[$key] ?? null,
                            'WhatsApp' => $request->WhatsApp[$key] ?? null,
                            'LinkedIn' => $request->LinkedIn[$key] ?? null,
                            'Birthday' => $request->Birthday[$key] ?? null
                        ]);
                    }
                }
            }
        }
        
        if ($request->hasFile('Path')) {
            foreach ($request->file('Path') as $key => $file) {
                // Generate a unique filename
                $fileName = time() . '_' . $file->getClientOriginalName();
                
                // Store the file
                $storedPath = $file->storeAs('uploads', $fileName, 'public');
                
                // Get the corresponding file ID
                $fileId = $request->input('fileId')[$key]; // Ensure the key matches the input name in the HTML
                
                // Check if the file already exists for the client
                $existingFile = FileClient::where('ClientId', $client->id)
                                          ->where('id', $fileId)
                                          ->first();
                
                if ($existingFile) {
                    // Update the existing file's path
                    $existingFile->update([
                        'Path' => $storedPath,
                        'FileName' => $request->input('FileName')[$key] // Update the file name as well
                    ]);
                } else {
                    // Create a new file record
                    FileClient::create([
                        'ClientId' => $client->id,
                        'FileName' => $request->input('FileName')[$key],
                        'Path' => $storedPath
                    ]);
                }
            }
        }                  

        return response()->json(['success' => 'Data is Successfully Updated.']);
    }

    // View
    public function view($id) 
    {
        $data = Client::find($id);
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

        return view('clients.view', compact('data', 'primaryAccountManager', 'secondaryAccountManager', 'payment_terms', 'regions', 'countries', 'areas', 'business_types', 'industries', 'addresses'));
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PaymentTerms;
use App\Supplier;
use App\SupplierContact;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{
    // List
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->get('sort', 'Name'); // Default to 'Name' if no sort is specified
        $direction = $request->get('direction', 'asc'); // Default to ascending order
        $fetchAll = $request->input('fetch_all', false); // Get the fetch_all parameter
        $entries = $request->input('number_of_entries', 10); // Default to 10 entries per page

        $payment_terms = PaymentTerms::where('Type', 2)->get();
      
        // Validate sort and direction parameters
        $validSorts = ['Name', 'Products', 'MobileNo', 'Terms'];
        if (!in_array($sort, $validSorts)) {
            $sort = 'Name';
        }
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'desc';
        }

        $suppliers = Supplier::with(['payment_terms', 'supplier_contacts'])
            ->where(function ($query) use ($search) {
                $query->where('Name', 'LIKE', '%' . $search . '%')  
                    ->orWhere('Products', 'LIKE', '%' . $search . '%')
                    ->orWhere('MobileNo', 'LIKE', '%' . $search . '%')
                    ->orWhere('Terms', 'LIKE', '%' . $search . '%');
            })
            ->orderBy($sort, $direction);
        // dd($suppliers->take(10));
        if ($fetchAll) {
            $data = $suppliers->get(); // Fetch all results
            return response()->json($data); // Return JSON response for copying
        } else {
            $data = $suppliers->paginate($entries); // Default pagination
            // dd($data);
            return view('supplier.index', [
                'search' => $search,
                'data' => $data,
                'fetchAll' => $fetchAll,
                'entries' => $entries,
                'payment_terms' => $payment_terms
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    
    public function store(Request $request)
    {
        // Validation rules
        $rules = [
            'Name' => 'required|string',
            'Products' => 'required|string',
            'Distributor' => 'required|string',
            'Origin' => 'required|string',
            'ContactPerson' => 'required|array',
            'ContactPerson.*' => 'required|string|max:255',
            'Email' => 'required|email',
            'TelNo' => 'nullable|string',
            'FaxNo' => 'nullable|string',
            'MobileNo' => 'nullable|string',
            'Email2' => 'nullable|email',
        ];

        $customMessages = [
            'Name.required' => 'The supplier name is required.',
            'ContactPerson.required' => 'The contact person is required.',
            'ContactPerson.*.required' => 'Each contact person is required.',
            'Email.required' => 'The email is required.',
            'Email.email' => 'Please enter a valid email address.'
        ];

        // Validate request
        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        // If validation passes
        $supplier = new Supplier();
        $supplier->Name = $request->Name;
        $supplier->Products = $request->Products;
        $supplier->Distributor = $request->Distributor;
        $supplier->Origin = $request->Origin;
        $supplier->Email = $request->Email;
        $supplier->TelNo = $request->TelNo;
        $supplier->FaxNo = $request->FaxNo;
        $supplier->MobileNo = $request->MobileNo;
        $supplier->Email2 = $request->Email2;
        $supplier->Terms = $request->Terms; 
        $supplier->Status = 1;
        $supplier->save();

        // Handle supplier contacts
        foreach ($request->ContactPerson as $contact) {
            $supplierContact = new SupplierContact();
            $supplierContact->SupplierId = $supplier->id;
            $supplierContact->ContactPerson = $contact;
            $supplierContact->save();
        }

        return response()->json(['success' => 'Supplier added successfully!']);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }


    public function edit($id)
    {
        if(request()->ajax())
        {
            $data = Supplier::with('supplier_contacts')->findOrFail($id);
            return response()->json(['data' => $data]);
        }
    }

    // Update
    public function update(Request $request, $id)
    {
        // Validation rules
        $rules = [
            'Name' => 'required|string',
            'Products' => 'required|string',
            'Distributor' => 'required|string',
            'Origin' => 'required|string',
            'ContactPerson' => 'required|array',
            'ContactPerson.*' => 'required|string|max:255',
            'Email' => 'required|email',
            'TelNo' => 'nullable|string',
            'FaxNo' => 'nullable|string',
            'MobileNo' => 'nullable|string',
            'Email2' => 'nullable|email',
        ];

        $customMessages = [
            'Name.required' => 'The supplier name is required.',
            'ContactPerson.required' => 'The contact person is required.',
            'ContactPerson.*.required' => 'Each contact person is required.',
            'Email.required' => 'The email is required.',
            'Email.email' => 'Please enter a valid email address.',
        ];

        // Validate request
        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        // Prepare data for update
        $form_data = [
            'Name'          => $request->Name,
            'Products'      => $request->Products,
            'Origin'        => $request->Origin,
            'Distributor'   => $request->Distributor,
            'Address'       => $request->Address,
            'TelNo'         => $request->TelNo,
            'FaxNo'         => $request->FaxNo,
            'MobileNo'      => $request->MobileNo,
            'Email'         => $request->Email,
            'Email2'        => $request->Email2,
            'Terms'         => $request->Terms,
        ];

        // Update supplier
        Supplier::whereId($id)->update($form_data);
        $supplier = Supplier::findOrFail($id);

        // Delete contacts that are not present in the request
        SupplierContact::where('SupplierId', $supplier->Id)
            ->whereNotIn('ContactPerson', $request->ContactPerson)
            ->delete();

        // Add or update contact persons
        foreach ($request->ContactPerson as $contactPerson) {
            if (!empty($contactPerson)) {
                // Check if the contact already exists
                SupplierContact::updateOrCreate(
                    ['SupplierId' => $supplier->Id, 'ContactPerson' => $contactPerson],
                    ['ContactPerson' => $contactPerson]
                );
            }
        }

        return response()->json(['success' => 'Data is Successfully Updated.']);
    }
    

}

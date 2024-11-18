<?php

namespace App\Http\Controllers;

use App\Supplier;
use App\SupplierProduct;
use App\SpeFiles;
use App\SpeInstructions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class SupplierProductController extends Controller
{
    // List
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->get('sort', 'Name'); // Default to 'Name' if no sort is specified
        $direction = $request->get('direction', 'asc'); // Default to ascending order
        $fetchAll = $request->input('fetch_all', false); // Get the fetch_all parameter
        $entries = $request->input('number_of_entries', 10); // Default to 10 entries per page
        $refCode = $this->refCode();
    
        $suppliers = Supplier::all();

        // Get the current year (last two digits)
        $year = date('y'); // For example, '24' for 2024

        // Fetch the latest SpeNo from the database and extract the series part
        $latestSpe = SupplierProduct::whereYear('created_at', date('Y'))
                        ->orderBy('SpeNumber', 'desc')
                        ->first();

        if ($latestSpe) {
            // Extract and increment the numeric part of the latest series (e.g., 0001 -> 0002)
            $latestSeries = (int) substr($latestSpe->SpeNumber, -4);
            $newSeries = str_pad($latestSeries + 1, 4, '0', STR_PAD_LEFT);
        } else {
            // If no previous records exist, start with '0001'
            $newSeries = '0001';
        }

        // Combine to create the new SpeNo (e.g., SPE-24-0001)
        $newSpeNo = 'SPE-' . $year . '-' . $newSeries;

        $validSorts = ['ProductName'];
        if (!in_array($sort, $validSorts)) {
            $sort = 'ProductName';
        }
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'desc';
        }

        $supplierProducts = SupplierProduct::with(['suppliers', 'progress'])
                ->where(function ($query) use ($search) {
                $query->where('ProductName', 'LIKE', '%' . $search . '%')  
                    ->orWhere('Supplier', 'LIKE', '%' . $search . '%')
                    ->orWhere('AttentionTo', 'LIKE', '%' . $search . '%')
                    ->orWhere('DateRequested', 'LIKE', '%' . $search . '%');
            })
            ->orderBy($sort, $direction);
        if ($fetchAll) {
            $data = $supplierProducts->get(); // Fetch all results
            return response()->json($data); // Return JSON response for copying
        } else {
            $data = $supplierProducts->paginate($entries); // Default pagination
            // dd($data);
            return view('spe.index', [
                'search' => $search,
                'data' => $data,
                'fetchAll' => $fetchAll,
                'entries' => $entries,
                'refCode' => $refCode, 
                'suppliers' => $suppliers,
                'newSpeNo' => $newSpeNo
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

    // Store
    public function store(Request $request)
    {
        // Validation rules
        $rules = [
            'DateRequested' => 'nullable|date',
            'Deadline' => 'required|date',
            'ProductName' => 'required|string|max:255',
            'Supplier' => 'required|exists:suppliers,id', 
            'LotNo' => 'required|string|max:255', 
            'Manufacturer' => 'nullable|string|max:255',
            'Quantity' => 'nullable|numeric',
            'ProductApplication' => 'nullable|string|max:255',
            'Origin' => 'nullable|string|max:255',  
            'Price' => 'nullable|numeric',  
            'LotNo' => 'nullable|string|max:255',
        ];

        $customMessages = [
            'ProductName.required' => 'The product name is required.',
            'Deadline.required' => 'The deadline is required.',
            'Supplier.required' => 'The supplier/ trader name is required.',
            'LotNo.required' => 'The lot no./ batch no. is required.'            
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $supplier_product = new SupplierProduct();
        $supplier_product->SpeNumber = $request->SpeNumber;
        $supplier_product->DateRequested = $request->DateRequested;
        $supplier_product->Deadline = $request->Deadline;
        $supplier_product->AttentionTo = $request->AttentionTo;
        $supplier_product->ProductName = $request->ProductName;
        $supplier_product->Manufacturer = $request->Manufacturer;
        $supplier_product->Supplier = $request->Supplier;
        $supplier_product->Quantity = $request->Quantity;
        $supplier_product->Origin = $request->Origin;
        $supplier_product->ProductApplication = $request->ProductApplication;
        $supplier_product->Price = $request->Price;
        $supplier_product->LotNo = $request->LotNo;
        $supplier_product->Status = 10;
        $supplier_product->Progress = 10;
        $supplier_product->PreparedBy = auth()->user()->id;
        $supplier_product->save();

        // Handle instructions
        if ($request->has('Instruction') && is_array($request->Instruction)) {
            SpeInstructions::where('SpeId', $supplier_product->id)->delete();
    
            // Then, save each new instruction
            foreach ($request->Instruction as $supplier_instruction) {
                $supplierContact = new SpeInstructions();
                $supplierContact->SpeId = $supplier_product->id;
                $supplierContact->Instruction = $supplier_instruction;
                $supplierContact->save();
            }
        } 

        // Handle file attachments
        if ($request->has('Name') && is_array($request->Name)) {
            foreach ($request->Name as $index => $name) {
                // Ensure the file exists before saving
                if ($request->hasFile('Path.' . $index)) {
                    $supplierFiles = new SpeFiles();
                    $supplierFiles->SpeId = $supplier_product->id;
                    $supplierFiles->Name = $name; // Save the selected attachment name

                    // Handle file upload
                    $file = $request->file('Path.' . $index);
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('uploads', $fileName, 'public'); // Store file in 'public' disk
                    $supplierFiles->Path = $filePath; // Save the file path

                    $supplierFiles->save();
                }
            }
        }

        return response()->json(['success' => 'Supplier Product added successfully!']);
    }

    public function view($id)
    {
        $data = SupplierProduct::with('supplier_instruction', 'attachments', 'suppliers')->findOrFail($id);
        return view('spe.view', compact('data'));
    }

    public function edit($id)
    {
        if(request()->ajax()) {
            $data = SupplierProduct::with('supplier_instruction', 'attachments')->findOrFail($id);
            
            $attachments = $data->attachments->map(function($attachment) {
                return [
                    'id' => $attachment->id, 
                    'name' => $attachment->Name,
                    'path' => $attachment->Path,
                ];
            });
            
            return response()->json([
                'data' => $data,
                'instructions' => $data->supplier_instruction->pluck('Instruction')->toArray(), 
                'attachments' => $attachments,
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        // Validation rules
        $rules = [
            'DateRequested' => 'nullable|date',
            'Deadline' => 'required|date',
            'ProductName' => 'required|string|max:255',
            'Supplier' => 'required|exists:suppliers,id',
            'LotNo' => 'required|string|max:255',
            'Manufacturer' => 'nullable|string|max:255',
            'Quantity' => 'nullable|numeric',
            'ProductApplication' => 'nullable|string|max:255',
            'Origin' => 'nullable|string|max:255',
            'Price' => 'nullable|numeric',
        ];

        // Custom error messages
        $customMessages = [
            'ProductName.required' => 'The product name is required.',
            'Deadline.required' => 'The deadline is required.',
            'Supplier.required' => 'The supplier/trader name is required.',
            'LotNo.required' => 'The lot no./batch no. is required.',
        ];

        // Validate the request
        $validator = Validator::make($request->all(), $rules, $customMessages);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        // Fetch SupplierProduct by ID, or fail if not found
        $supplierProduct = SupplierProduct::findOrFail($id);
        
        // Update SupplierProduct with validated fields
        $supplierProduct->update($request->only([
            'ProductName',
            'DateRequested',
            'AttentionTo',
            'Deadline',
            'Manufacturer',
            'Quantity',
            'Supplier',
            'ProductApplication',
            'Origin',
            'LotNo',
            'Price',
        ]));

        // Handle file updates
        if ($request->has('Name') && is_array($request->Name)) {
            foreach ($request->Name as $index => $name) {
                if ($request->hasFile('Path.' . $index)) {
                    // Delete old file if a new one is uploaded
                    $existingFile = SpeFiles::where('SpeId', $supplierProduct->id)
                                            ->where('Name', $name)
                                            ->first();
                    if ($existingFile && Storage::exists('public/' . $existingFile->Path)) {
                        Storage::delete('public/' . $existingFile->Path);
                        $existingFile->delete(); // Remove record from database
                    }
                    // Upload and save new file
                    $file = $request->file('Path.' . $index);
                    if ($file->isValid()) {
                        $fileName = time() . '_' . $file->getClientOriginalName();
                        $filePath = $file->storeAs('uploads', $fileName, 'public');
        
                        SpeFiles::updateOrCreate([
                            'SpeId' => $supplierProduct->id,
                            'Name' => $name,
                            'Path' => $filePath,
                        ]);
                    }
                }
            }
        }

        // Handle file deletion if requested
        if ($request->has('deletedFiles')) {
            $deletedFilesArray = explode(',', $request->deletedFiles);
            foreach ($deletedFilesArray as $deletedFileId) {
                $file = SpeFiles::find($deletedFileId);
                if ($file && Storage::exists('public/' . $file->Path)) {
                    Storage::delete('public/' . $file->Path); // Delete file from storage
                    $file->delete(); // Remove from database
                }
            }
        }
        
        // Handle Instructions (New Code Integration)
        if ($request->has('Instruction') && is_array($request->Instruction)) {
            // First, delete any existing instructions for the supplier product to avoid duplicates
            SpeInstructions::where('SpeId', $supplierProduct->id)->delete();

            // Then, save each new instruction
            foreach ($request->Instruction as $supplier_instruction) {
                $supplierInstruction = new SpeInstructions();
                $supplierInstruction->SpeId = $supplierProduct->id;
                $supplierInstruction->Instruction = $supplier_instruction;
                $supplierInstruction->save();
            }
        }
        dd($request);
        // Return a success response
        return response()->json(['success' => 'Data is successfully updated.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function refCode()
    {
        return array(
            'RND' => 'RND',
            'QCD-WHI' => 'QCD-WHI',
            'QCD-PBI' => 'QCD-PBI',
            'QCD-MRDC' => 'QCD-MRDC',
            'QCD-CCC' => 'QCD-CCC'
        );
    }
}

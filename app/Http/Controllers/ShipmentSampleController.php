<?php

namespace App\Http\Controllers;

use App\ShipmentSample;
use App\SseAttachments;
use App\SsePersonnel;
use App\SseFiles;
use App\SsePacks;
use App\SseWork;
use App\SseDisposition;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ShipmentSampleController extends Controller
{
    // List 
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->get('sort', 'SseNumber'); // Default to 'Name' if no sort is specified
        $direction = $request->get('direction', 'asc'); // Default to ascending order
        $fetchAll = $request->input('fetch_all', false); // Get the fetch_all parameter
        $entries = $request->input('number_of_entries', 10); // Default to 10 entries per page
        $refCode = $this->refCode();

        $year = date('y'); // For example, '24' for 2024

        // Fetch the latest SpeNo from the database and extract the series part
        $latestSse = ShipmentSample::whereYear('created_at', date('Y'))
                        ->orderBy('SseNumber', 'desc')
                        ->first();

        if ($latestSse) {
            // Extract and increment the numeric part of the latest series (e.g., 0001 -> 0002)
            $latestSeries = (int) substr($latestSse->SseNumber, -4);
            $newSeries = str_pad($latestSeries + 1, 4, '0', STR_PAD_LEFT);
        } else {
            // If no previous records exist, start with '0001'
            $newSeries = '0001';
        }

        // Combine to create the new SpeNo (e.g., SPE-24-0001)
        $newSseNo = 'SSE-' . $year . '-' . $newSeries;

        $userId = Auth::id();

        $shipmentSample = ShipmentSample::with(['progress'])
            ->when($request->input('status'), function($query) use ($request, $userId) {
                $status = $request->input('status');
                $role = auth()->user()->role;
                $userType = $role->type;  
                $userName = $role->name;
            
                if ($status == '10') {
                    if ($userType == 'RND' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
                        $query->where('Status', '10')
                              ->where('AttentionTo', 'RND');
                    } elseif ($userType == 'QCD-WHI' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
                        $query->where('Status', '10')
                              ->where('AttentionTo', 'QCD-WHI');
                    } elseif ($userType == 'QCD-PBI' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
                        $query->where('Status', '10')
                                ->where('AttentionTo', 'QCD-PBI');
                    } elseif ($userType == 'QCD-MRDC' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
                        $query->where('Status', '10')
                                ->where('AttentionTo', 'QCD-MRDC');
                    } elseif ($userType == 'QCD-CCC' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
                        $query->where('Status', '10')
                                ->where('AttentionTo', 'QCD-CCC');
                    } else {
                        // Default logic for other users
                        $query->where('Status', '10')
                                ->where(function($query) use ($userId) {
                                    $query->where('status', '10')
                                    ->where(function($query) use ($userId) {
                                        $query->where('PreparedBy', $userId);
                                    });
        
                                  // Check for related 'crr_personnels' entries
                                  $query->orWhereHas('ssePersonnel', function($query) use ($userId) {
                                      $query->where('SsePersonnel', $userId);
                                  });
                              });
                    }
                } else {
                    // Apply other status filters if status is not '10'
                    $query->where('Status', $status);
                }
            })
            ->when($request->input('progress'), function($query) use ($request) {
                $progress = $request->input('progress');
                $role = auth()->user()->role;
                $userType = $role->type;  
                $userName = $role->name; 

                if ($userType == 'PRD' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
                    $query->where('Progress', '10');
                } else {
                    $query->where('Progress', $progress);
                }
            })
            ->when($request->input('progress'), function($query) use ($request, $userId) {
                $progress = $request->input('progress');
                $role = auth()->user()->role;
                $userType = $role->type;  
                $userName = $role->name; 
                
                if ($progress == '20') {
                    if ($userType == 'RND' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
                        $query->where('Progress', '20')
                              ->where('AttentionTo', 'RND');
                    } elseif ($userType == 'QCD-WHI' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
                        $query->where('Progress', '20')
                              ->where('AttentionTo', 'QCD-WHI');
                    } elseif ($userType == 'QCD-PBI' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
                        $query->where('Progress', '20')
                                ->where('AttentionTo', 'QCD-PBI');
                    } elseif ($userType == 'QCD-MRDC' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
                        $query->where('Progress', '20')
                                ->where('AttentionTo', 'QCD-MRDC');
                    } elseif ($userType == 'QCD-CCC' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
                        $query->where('Progress', '20')
                                ->where('AttentionTo', 'QCD-CCC');
                    } else {
                        $query->where('Progress', '20')
                            ->where(function($query) use ($userId) {
                                $query->orWhereHas('ssePersonnel', function($query) use ($userId) {
                                    $query->where('SsePersonnel', $userId);
                                });
                            });
                    }
                } else {
                    $query->where('Progress', $progress);
                }
            })
            ->where(function ($query) use ($search) {
                $query->where('SseNumber', 'LIKE', '%' . $search . '%')  
                    ->orWhere('AttentionTo', 'LIKE', '%' . $search . '%')
                    ->orWhere('RmType', 'LIKE', '%' . $search . '%')
                    ->orWhere('SseResult', 'LIKE', '%' . $search . '%');
            })
            ->orderBy($sort, $direction);
        if ($fetchAll) {
            $data = $shipmentSample->get(); // Fetch all results
            return response()->json($data); // Return JSON response for copying
        } else {
            $data = $shipmentSample->paginate($entries); // Default pagination
            // dd($data);
            return view('sse.index', [
                'search' => $search,
                'data' => $data,
                'fetchAll' => $fetchAll,
                'entries' => $entries,
                'refCode' => $refCode, 
                'newSseNo' => $newSseNo
            ]);
        }

    }

    // Store 
    public function store(Request $request) 
    {
        $rules = [
            'RmType' => 'required|string|max:255',
            'Supplier' => 'required|string|max:255',
            'SseResult' => 'required|string|max:255',
            'AttentionTo' => 'nullable|string|max:255',
            'ProductCode' => 'nullable|string|max:255',
            'Grade' => 'nullable|string|max:255',
            'Origin' => 'nullable|string|max:255',  
        ];

        $customMessages = [
            'RmType.required' => 'The raw material type is required.',
            'SseResult.required' => 'The result is required.',
            'Supplier.required' => 'The supplier is required.',           
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $shipment_sample = new ShipmentSample();
        $shipment_sample->SseNumber = $request->SseNumber;
        $shipment_sample->DateSubmitted = $request->DateSubmitted;
        $shipment_sample->AttentionTo = $request->AttentionTo;
        $shipment_sample->RmType = $request->RmType;
        $shipment_sample->Grade = $request->Grade;
        $shipment_sample->ProductCode = $request->ProductCode;
        $shipment_sample->Manufacturer = $request->Manufacturer;        
        $shipment_sample->Origin = $request->Origin;
        $shipment_sample->Supplier = $request->Supplier;
        $shipment_sample->SseResult = $request->SseResult;
        $shipment_sample->Origin = $request->Origin;
        $shipment_sample->ResultSpeNo = $request->ResultSpeNo;
        $shipment_sample->PoNumber = $request->PoNumber;
        $shipment_sample->Quantity = $request->Quantity;
        $shipment_sample->ProductOrdered = $request->ProductOrdered;
        $shipment_sample->Ordered = $request->Ordered;
        $shipment_sample->SampleType = $request->SampleType;
        $shipment_sample->Status = 10;
        $shipment_sample->Buyer = $request->Buyer;
        $shipment_sample->BuyersPo = $request->BuyersPo;
        $shipment_sample->SalesAgreement = $request->SalesAgreement;
        $shipment_sample->ProductDeclared = $request->ProductDeclared;
        $shipment_sample->LnBags = $request->LnBags;
        $shipment_sample->Instruction = $request->Instruction;
        $shipment_sample->OtherProduct = $request->OtherProduct;
        $shipment_sample->Progress = 10;
        $shipment_sample->PreparedBy = auth()->user()->id;
        $shipment_sample->save();

        if ($request->has('LotNumber') && is_array($request->LotNumber)) {
            SsePacks::where('SseId', $shipment_sample->id)->delete();
        
            foreach ($request->LotNumber as $index => $shipment_lotnumber) {
                if (!empty($shipment_lotnumber) && !empty($request->QtyRepresented[$index])) {
                    $shipmentSample = new SsePacks();
                    $shipmentSample->SseId = $shipment_sample->id;
                    $shipmentSample->LotNumber = $shipment_lotnumber;
                    $shipmentSample->QtyRepresented = $request->QtyRepresented[$index]; 
                    $shipmentSample->save();
                }
            }
        } 

        if ($request->has('Name') && is_array($request->Name)) {
            foreach ($request->Name as $index => $name) {
                // Ensure the file exists before saving
                if ($request->hasFile('Path.' . $index)) {
                    $shipmentFiles = new SseFiles();
                    $shipmentFiles->SseId = $shipment_sample->id;
                    $shipmentFiles->Name = $name; // Save the selected attachment name

                    // Handle file upload
                    $file = $request->file('Path.' . $index);
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('uploads', $fileName, 'public'); // Store file in 'public' disk
                    $shipmentFiles->Path = $filePath; // Save the file path

                    $shipmentFiles->save();
                }
            }
        }
        
        if ($request->has('Work') && is_array($request->Work)) {
            SseWork::where('SseId', $shipment_sample->id)->delete();

            foreach ($request->Work as $shipment_work) {
                $shipmentWork = new SseWork();
                $shipmentWork->SseId = $shipment_sample->id;
                $shipmentWork->Work = $shipment_work;
                $shipmentWork->save();
            }
        }

        sseHistoryLogs("create", $shipment_sample->id);

        return response()->json(['success' => 'Shipment Sample added successfully!']);
    }

    public function edit($id)
    {
        if (request()->ajax()) {
            $data = ShipmentSample::with('shipment_pack', 'shipment_attachments', 'shipment_work')->findOrFail($id);
            // dd($data);
            $shipment_pack = $data->shipment_pack->map(function($pack) {
                return [
                    'LotNumber' => $pack->LotNumber,
                    'QtyRepresented' => $pack->QtyRepresented,
                ];
            });

            $shipment_attachments = $data->shipment_attachments->map(function($attachment) {
                return [
                    'id' => $attachment->id, 
                    'name' => $attachment->Name,
                    'path' => $attachment->Path,
                ];
            });
    
            return response()->json([
                'data' => $data,
                'shipment_pack' => $shipment_pack,
                'shipment_attachments' => $shipment_attachments,
                'work' => $data->shipment_work->pluck('Work')->toArray(), 
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'RmType' => 'required|string|max:255',
            'Supplier' => 'required|string|max:255',
            'SseResult' => 'required|string|max:255',
            'AttentionTo' => 'nullable|string|max:255',
            'ProductCode' => 'nullable|string|max:255',
            'Grade' => 'nullable|string|max:255',
            'Origin' => 'nullable|string|max:255',  
        ];

        $customMessages = [
            'RmType.required' => 'The raw material type is required.',
            'SseResult.required' => 'The result is required.',
            'Supplier.required' => 'The supplier is required.',        
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $shipmentSample = ShipmentSample::findOrFail($id);
        $shipmentSample->update($request->only([
            'DateSubmitted',
            'AttentionTo',
            'RmType',
            'Grade',
            'ProductCode',
            'Origin',
            'Supplier',
            'SseResult',
            'ResultSpeNo',
            'PoNumber',
            'Quantity',
            'Ordered',
            'ProductOrdered',
            'OtherProduct',
            'SampleType',
            'Instruction',
            'Buyer',
            'BuyersPo',
            'SalesAgreement',
            'ProductDeclared',
            'LnBags'
        ]));

        if ($request->has('Name') && is_array($request->Name)) {
            foreach ($request->Name as $index => $name) {
                if ($request->hasFile('Path.' . $index)) {
                    // Delete old file if a new one is uploaded
                    $existingFile = SseFiles::where('SseId', $shipmentSample->id)
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
        
                        SseFiles::updateOrCreate([
                            'SseId' => $shipmentSample->id,
                            'Name' => $name,
                            'Path' => $filePath,
                        ]);
                    }
                }
            }
        }

        if ($request->has('deletedFiles')) {
            $deletedFilesArray = explode(',', $request->deletedFiles);
            foreach ($deletedFilesArray as $deletedFileId) {
                $file = SseFiles::find($deletedFileId);
                if ($file && Storage::exists('public/' . $file->Path)) {
                    Storage::delete('public/' . $file->Path); // Delete file from storage
                    $file->delete(); // Remove from database
                }
            }
        }

        if ($request->has('LotNumber') && is_array($request->LotNumber)) {
            foreach ($request->LotNumber as $index => $lotNumber) {
                $packId = $request->PackId[$index] ?? null; // Fetch PackId if it exists
                
                if ($packId) {
                    // Update existing record using PackId
                    SsePacks::updateOrCreate(
                        ['id' => $packId], // Find by PackId for updating
                        [
                            'SseId' => $shipmentSample->id,
                            'LotNumber' => $lotNumber, // Update LotNumber
                            'QtyRepresented' => $request->QtyRepresented[$index] ?? null
                        ]
                    );
                } else {
                    if (!empty($lotNumber)) {
                        // Create new pack if PackId is not provided
                        SsePacks::create([
                            'SseId' => $shipmentSample->id,
                            'LotNumber' => $lotNumber,
                            'QtyRepresented' => $request->QtyRepresented[$index] ?? null
                        ]);
                    }
                }    
            }
        } 

        if ($request->has('deletedPacks')) {
            $deletedPacksArray = explode(',', $request->deletedPacks);
            foreach ($deletedPacksArray as $deletedPackId) {
                $pack = SsePacks::find($deletedPackId);
                if ($pack) {
                    $pack->delete(); // Remove pack record from database
                }
            }
        }

        if ($request->has('Work') && is_array($request->Work)) {
            SseWork::where('SseId', $shipmentSample->id)->delete();

            foreach ($request->Work as $shipment_work) {
                $shipmentWork = new SseWork();
                $shipmentWork->SseId = $shipmentSample->id;
                $shipmentWork->Work = $shipment_work;
                $shipmentWork->save();
            }
        }   

        sseHistoryLogs("update", $shipmentSample->id);  // Log history
                    
        return response()->json(['success' => 'Data is successfully updated.']);
    }

    public function view($id)
    {
        $data = ShipmentSample::with('shipment_pack', 'shipment_attachments', 'shipment_work', 'shipment_files', 'shipment_disposition')->findOrFail($id);
        $refCode = $this->refCode();
        $work = $data->shipment_work ? $data->shipment_work->pluck('Work')->toArray() : [];
        $rnd_personnel = User::whereIn('department_id', [15, 42, 20, 79, 77, 44])->where('is_active', 1)->get();
        return view('sse.view', compact('data', 'refCode', 'rnd_personnel', 'work'));
    }

    public function approvedSse($id)
    {
        $data = ShipmentSample::findOrFail($id);
        $data->ApprovedBy = auth()->user()->id;
        $data->Progress = 20;
        $data->save();

        sseHistoryLogs("approved", $data->id);  // Log history

        // Return a JSON response
        return response()->json([
            'success' => true,
            'message' => 'Successfully Approved',
        ]);
    }

    public function receivedSse($id)
    {
        $data = ShipmentSample::findOrFail($id);
        $data->Progress = 35;
        $data->DateReceived = now();
        $data->save();

        sseHistoryLogs("received", $data->id);

        Alert::success('Successfully Received')->persistent('Dismiss');
        return back();
    }

    public function startSse($id)
    {
        $data = ShipmentSample::findOrFail($id);
        $data->Progress = 50;
        $data->save();

        sseHistoryLogs('start', $id);

        Alert::success('Successfully Start')->persistent('Dismiss');
        return back();
    }

    public function dispositionSse(Request $request, $id)
    {
        $shipmentSample = ShipmentSample::with('shipment_pack', 'shipment_attachments', 'shipment_work')->findOrFail($id);
        $shipmentSample->LabRemarks = $request->Remarks;

        if ($request->has('LabDisposition') && is_array($request->LabDisposition)) {
            SseDisposition::where('SseId', $shipmentSample->id)->delete();

            foreach ($request->LabDisposition as $shipment_disposition) {
                $shipmentDisposition = new SseDisposition();
                $shipmentDisposition->SseId = $shipmentSample->id;
                $shipmentDisposition->LabDisposition = $shipment_disposition;
                $shipmentDisposition->save();
            }
        }
        $shipmentSample->save();

        sseHistoryLogs("disposition", $shipmentSample->id);

        return response()->json(['success' => 'Data is successfully updated.']);
    }

    public function sample(Request $request, $id) 
    {
        $shipmentSample = ShipmentSample::with('shipment_pack', 'shipment_attachments', 'shipment_work')->findOrFail($id);
        $shipmentSample->SampleType = $request->SampleType;
        
        if ($request->has('Name') && is_array($request->Name)) {
            foreach ($request->Name as $index => $name) {
                if ($request->hasFile('Path.' . $index)) {
                    // Delete old file if a new one is uploaded
                    $existingFile = SseFiles::where('SseId', $shipmentSample->id)
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
        
                        SseFiles::updateOrCreate([
                            'SseId' => $shipmentSample->id,
                            'Name' => $name,
                            'Path' => $filePath,
                        ]);
                    }
                }
            }
        }

        if ($request->has('deletedFiles')) {
            $deletedFilesArray = explode(',', $request->deletedFiles);
            foreach ($deletedFilesArray as $deletedFileId) {
                $file = SseFiles::find($deletedFileId);
                if ($file && Storage::exists('public/' . $file->Path)) {
                    Storage::delete('public/' . $file->Path); // Delete file from storage
                    $file->delete(); // Remove from database
                }
            }
        }

        if ($request->has('LotNumber') && is_array($request->LotNumber)) {
            foreach ($request->LotNumber as $index => $lotNumber) {
                $packId = $request->PackId[$index] ?? null; // Fetch PackId if it exists
                
                if ($packId) {
                    // Update existing record using PackId
                    SsePacks::updateOrCreate(
                        ['id' => $packId], // Find by PackId for updating
                        [
                            'SseId' => $shipmentSample->id,
                            'LotNumber' => $lotNumber, // Update LotNumber
                            'QtyRepresented' => $request->QtyRepresented[$index] ?? null
                        ]
                    );
                } else {
                    if (!empty($lotNumber)) {
                        // Create new pack if PackId is not provided
                        SsePacks::create([
                            'SseId' => $shipmentSample->id,
                            'LotNumber' => $lotNumber,
                            'QtyRepresented' => $request->QtyRepresented[$index] ?? null
                        ]);
                    }
                }    
            }
        } 

        if ($request->has('deletedPacks')) {
            $deletedPacksArray = explode(',', $request->deletedPacks);
            foreach ($deletedPacksArray as $deletedPackId) {
                $pack = SsePacks::find($deletedPackId);
                if ($pack) {
                    $pack->delete(); // Remove pack record from database
                }
            }
        }

        if ($request->has('Work') && is_array($request->Work)) {
            SseWork::where('SseId', $shipmentSample->id)->delete();

            foreach ($request->Work as $shipment_work) {
                $shipmentWork = new SseWork();
                $shipmentWork->SseId = $shipmentSample->id;
                $shipmentWork->Work = $shipment_work;
                $shipmentWork->save();
            }
        }
        $shipmentSample->save();

        sseHistoryLogs("disposition", $shipmentSample->id);

        return response()->json(['success' => 'Data is successfully updated.']);
    }

    public function addSsePersonnel(Request $request)
    {
        $shipmentSample = ShipmentSample::findOrFail($request->sse_id);
        $shipmentSample->Progress = 45; // Set Progress to 45
        $shipmentSample->save();    

        $personnel = new SsePersonnel;
        $personnel->SseId = $request->sse_id;
        $personnel->SsePersonnel = $request->sse_personnel;
        $personnel->save(); 

        speHistoryLogs("add_personnel", $shipmentSample->id);

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back()->with(['tab' => 'personnel']);
    }

    public function updateSsePersonnel(Request $request, $id)
    {
        $personnel = SsePersonnel::findOrFail($id);
        $personnel->SseId = $request->sse_id;
        $personnel->SsePersonnel = $request->sse_personnel;
        $personnel->save(); 

        speHistoryLogs("update_personnel", $personnel->id);
        
        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back()->with(['tab' => 'personnel']);
    }

    public function doneSse($id)
    {
        $data = ShipmentSample::findOrFail($id);
        $data->Progress = 55;
        $data->save();

        sseHistoryLogs('complete', $id);

        return response()->json(['success' => 'Data is successfully completed.']);
    }

    public function rejectedSse(Request $request, $id)
    {
        $data = ShipmentSample::findOrFail($id);
        $data->RejectedRemarks = $request->RejectedRemarks;
        $data->Progress = 30;
        $data->save();

        sseHistoryLogs("rejected", $data->id);

        Alert::success('Rejected', 'The shipment sample evaluation value has been successfully rejected!')
                ->autoClose(2000);

        return back();
    }

    public function acceptSse(Request $request, $id)
    {
        $data = ShipmentSample::findOrFail($id);
        $data->AcceptedRemarks = $request->AcceptedRemarks;
        $data->Progress = 60;
        $data->save();

        sseHistoryLogs("accepted", $data->id);

        Alert::success('Accepted', 'The shipment sample evaluation value has been successfully accepted!')
                ->autoClose(2000);

        return back();
    }

    public function deleteSse($id)
    {
        $speFiles = SseFiles::findOrFail($id);
        $speFiles->delete();

        Alert::success('Successfully Delete')->persistent('Dismiss');
        return back();
    }
    
    public function deletePack($id)
    {
        $ssePack = SsePacks::findOrFail($id);
        $ssePack->delete();

        Alert::success('Successfully Delete')->persistent('Dismiss');
        return back();
    }

    public function uploadFile(Request $request)
    {
        $files = $request->file('sse_file');
        $names = $request->input('name');
        $sseId = $request->input('sse_id');
        $isConfidential = $request->input('is_confidential');
        $isForReview = $request->input('is_for_review');

        if ($files) {
            foreach ($files as $index => $file) {
                $name = $names[$index];
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('attachments', $fileName, 'public');

                $uploadedFile = new SseAttachments();
                $uploadedFile->SseId = $sseId;
                $uploadedFile->Name = $name;
                $uploadedFile->Path = $filePath;
                $uploadedFile->IsConfidential = isset($isConfidential[$index]) ? $isConfidential[$index] : 0;
                $uploadedFile->IsForReview = isset($isForReview[$index]) ? $isForReview[$index] : 0;
                $uploadedFile->save();
            }
        }

        Alert::success('File(s) Stored successfully')->persistent('Dismiss');
        return back();
    }
    
    public function editFile(Request $request, $id)
    {
        $sseFile = SseAttachments::findOrFail($id);

        // Update name if provided
        if ($request->has('name')) {
            $sseFile->Name = $request->input('name');
        }

        if ($request->hasFile('sse_file')) {
            $file = $request->file('sse_file'); // Get the uploaded file
            $fileName = time() . '_' . $file->getClientOriginalName(); // Create a unique file name
            $filePath = $file->storeAs('attachments', $fileName, 'public'); // Store the file
    
            // Update the file path in the database
            $sseFile->Path = $filePath;
        }      

        // Update checkboxes (IsConfidential and IsForReview)
        if (authCheckIfItsRnd(auth()->user()->department_id)) {
            $sseFile->IsConfidential = $request->has('is_confidential') ? 1 : 0;
            $sseFile->IsForReview = $request->has('is_for_review') ? 1 : 0;
        }

        $sseFile->save();

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back()->with(['tab' => 'files']);
    }

    public function deleteFile($id)
    {
        $sseFile = SseAttachments::findOrFail($id);
        // $sseFile->Progress = 60;
        $sseFile->delete();

        Alert::success('Successfully Delete')->persistent('Dismiss');
        return back();
    }

    public function closeFileSse($id)
    {
        $data = ShipmentSample::findOrFail($id);
        $data->Status =30;
        $data->save();

        sseHistoryLogs('closed', $id);

        return response()->json(['success' => 'Data is successfully closed.']);
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

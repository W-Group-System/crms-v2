<?php

namespace App\Http\Controllers;

use App\Activity;
use App\RawMaterial;
use App\SampleRequest;
use App\Client;
use App\ConcernDepartment;
use App\Contact;
use App\IssueCategory;
use App\Product;
use App\ProductApplication;
use App\RndUser;
use App\SampleRequestProduct;
use App\SrfDetail;
use App\SrfFile;
use App\SrfPersonnel;
use App\SrfProgress;
use App\SrfRawMaterial;
use App\TransactionLogs;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Models\Audit;

class SampleRequestController extends Controller
{
    public function index(Request $request)
    {   
        $clients = Client::all();
        $contacts = Contact::all();
        $categories = IssueCategory::all();
        $departments = ConcernDepartment::all(); 
        $productApplications = ProductApplication::all();   
        $salesPersons = User::whereHas('salespersons')->get();
        // $loggedInUserId = Auth::user()->user_id;
        // $primarySalesPersons = User::whereHas('salespersons', function ($query) use ($loggedInUserId) {
        //     $query->where('user_id', $loggedInUserId);
        //     })->get();
        $productCodes = Product::where('status', '4')->get();
       
        // $sampleRequestProducts = SampleRequestProduct::with('sampleRequest')
        // ->whereHas('sampleRequest', function ($query) {
        //     $query->where('status', 10);
        // })
        // // ->get()
        // ->paginate(25);
        $search = $request->input('search');
        $sampleRequests = SampleRequest::with('requestProducts') 
        ->where(function ($query) use ($search){
            $query->where('SrfNumber', 'LIKE', '%' . $search . '%')
            ->orWhere('DateRequested', 'LIKE', '%' . $search . '%')
            ->orWhere('DateRequired', 'LIKE', '%' . $search . '%');
            // ->orWhereHas('client', function ($q) use ($search) {
            //     $q->where('name', 'LIKE', '%' . $search . '%');
            // });
        })
        ->where('status', 10) 
        ->paginate(25);

       
        return view('sample_requests.index', compact('sampleRequests','clients', 'contacts', 'categories', 'departments', 'salesPersons', 'productApplications', 'productCodes', 'search'));
    }

    public function getSampleContactsByClientF($clientId)
    {
        $contacts = Contact::where('CompanyId', $clientId)->pluck('ContactName', 'id');
        return response()->json($contacts);
    }

    public function getSampleLastIncrementF($year, $clientCode)
    {
        $lastUniqueID = SampleRequest::where('SrfNumber', 'like', 'SRF-' . $clientCode . '-' . $year . '-%')
                            ->orderBy('SrfNumber', 'desc')
                            ->first();

        if ($lastUniqueID) {
            $parts = explode('-', $lastUniqueID->SrfNumber);
            $lastIncrement = end($parts);
        } else {
            $lastIncrement = '0000';
        }

        return response()->json(['lastIncrement' => $lastIncrement]);
    }   

    public function view($id)
    {
        $sampleRequest = SampleRequest::with('requestProducts')->findOrFail($id);
        $scrfNumber = $sampleRequest->Id;
        $clientId = $sampleRequest->ClientId;
        $activities = Activity::where('ClientId', $clientId)->get();
        $SrfSupplementary = SrfDetail::where('SampleRequestId', $scrfNumber)->get();
        $assignedPersonnel = SrfPersonnel::where('SampleRequestId', $scrfNumber)->get();
        $SrfMaterials = SrfRawMaterial::where('SampleRequestId', $scrfNumber)->get();
        $rndPersonnel = User::whereHas('rndUsers')->get();
        $srfFileUploads = SrfFile::where('SampleRequestId', $scrfNumber)->get();
        $rawMaterials = RawMaterial::where('IsDeleted', '0')
        ->orWhere('deleted_at', '=', '')->get();
        $transactionLogs = TransactionLogs::where('Type', '30')
        ->where('TransactionId', $scrfNumber)
        ->get();

        $audits = Audit::where('auditable_id', $scrfNumber)
        ->whereIn('auditable_type', [SampleRequest::class, SrfRawMaterial::class, SrfDetail::class, SrfPersonnel::class, SrfFile::class])
        ->get();

        $mappedAudits = $audits->map(function ($audit) {
        $details = '';
        if ($audit->auditable_type === 'App\SrfRawMaterial') {
            $details = $audit->event . " " . 'SRF Raw Material';
        } elseif ($audit->auditable_type === 'App\SrfFile') {
            $details = $audit->event . " " . 'SRF Files';
        } elseif ($audit->auditable_type === 'App\SrfDetail') {
            $details = $audit->event . " " . 'SRF Supplementary';
        } elseif ($audit->auditable_type === 'App\SrfPersonnel') {
            $details = $audit->event . " " . 'SRF R&D Personnel';
        } elseif ($audit->auditable_type === 'App\SampleRequest') {
            if (isset($audit->new_values['Progress']) && $audit->new_values['Progress'] == 20) {
                $details = "Approve sample request entry";
            } elseif (isset($audit->new_values['Progress']) && ($audit->new_values['Progress'] == 30 || $audit->new_values['Progress'] == 80)) {
                $details = "Approve sample request entry";
            } elseif (isset($audit->new_values['Progress']) && $audit->new_values['Progress'] == 35) {
                $details = "Receive sample request entry";
            } elseif (isset($audit->new_values['Progress']) && $audit->new_values['Progress'] == 55) {
                $details = "Pause sample request transaction." . isset($audit->new_values['Remarks']);
            } elseif (isset($audit->new_values['Progress']) && $audit->new_values['Progress'] == 50) {
                $details = "Start sample request transaction";
            } else {
                $details = $audit->event . " " . 'Sample Request';
            }
        }
        return (object) [
            'CreatedDate' => $audit->created_at,
            'full_name' => $audit->user->full_name,
            'Details' => $details,
        ];
    });

    $mappedLogs = $transactionLogs->map(function ($log) {
        return (object) [
            'CreatedDate' => $log->ActionDate,
            'full_name' => $log->historyUser->full_name,
            'Details' => $log->Details,
        ];
    });

    $mappedLogsCollection = collect($mappedLogs);
    $mappedAuditsCollection = collect($mappedAudits);

    $combinedLogs = $mappedLogsCollection->merge($mappedAuditsCollection);
        return view('sample_requests.view', compact('sampleRequest', 'SrfSupplementary', 'rndPersonnel', 'assignedPersonnel', 'activities', 'srfFileUploads', 'rawMaterials', 'SrfMaterials', 'combinedLogs'));
    }               

    // public function update(Request $request, $id)
    // {
    //     $srf = SampleRequest::with('requestProducts')->findOrFail($id);
    
    //     // $srf->DateRequested = $request->input('DateRequested');
    //     // $srf->DateRequested = Carbon::createFromFormat('m/d/Y', $request->input('DateRequested'))->format('Y-m-d');
    //     $srf->DateRequired = $request->input('DateRequired');
    //     $srf->DateStarted = $request->input('DateStarted');
    //     $srf->PrimarySalesPersonId = $request->input('PrimarySalesPerson');
    //     $srf->SecondarySalesPersonId = $request->input('SecondarySalesPerson');
    //     $srf->RefCode = $request->input('RefCode');
    //     $srf->SrfType = $request->input('SrfType');
    //     $srf->SoNumber = $request->input('SoNumber');
    //     $srf->ClientId = $request->input('ClientId');
    //     $srf->ContactId = $request->input('ClientContactId');
    //     $srf->InternalRemarks = $request->input('Remarks');
    //     $srf->Courier = $request->input('Courier');
    //     $srf->AwbNumber = $request->input('AwbNumber');
    //     $srf->DateDispatched = $request->input('DateDispatched');
    //     $srf->DateSampleReceived = $request->input('DateSampleReceived');
    //     $srf->DeliveryRemarks = $request->input('DeliveryRemarks');
    //     $srf->Note = $request->input('Note');
    //     // dd($request->input('DateRequested'));
    //     $srf->save();

    //     foreach ($request->input('ProductType') as $key => $value) {
    //         $product = $srf->requestProducts()->updateOrCreate(
    //             ['id' => $request->input('product_id')[$key]], 
    //             [
    //                 'ProductType' => $value,
    //                 'ApplicationId' => $request->input('ApplicationId')[$key],
    //                 'ProductCode' => $request->input('ProductCode')[$key],
    //                 'ProductDescription' => $request->input('ProductDescription')[$key],
    //                 'NumberOfPackages' => $request->input('NumberOfPackages')[$key],
    //                 'Quantity' => $request->input('Quantity')[$key],
    //                 'UnitOfMeasure' => $request->input('UnitOfMeasure')[$key],
    //                 'Label' => $request->input('Label')[$key],
    //                 'RpeNumber' => $request->input('RpeNumber')[$key],
    //                 'CrrNumber' => $request->input('CrrNumber')[$key],
    //                 'Remarks' => $request->input('RemarksProduct')[$key],
    //             ]
    //         );
    //     }

    //     return redirect()->back()->with('success', 'Sample Request updated successfully');
    // }
    public function addSupplementary(Request $request)
    {
        SrfDetail::create([
                'SampleRequestId' => $request->input('srf_id'),
                'UserId' => auth()->user()->user_id,
                'DetailsOfRequest' => $request->input('details_of_request'),

            ]);
            return back();
    }

    public function editSupplementary(Request $request, $id)
    {
        $srfDetail = SrfDetail::findOrFail($id);
        $srfDetail->DetailsOfRequest = $request->input('details_of_request');
        $srfDetail->save();
        return back();
    }

    public function deleteSrfDetails($id)
    {
        try { 
            $srfDetail = SrfDetail::findOrFail($id); 
            $srfDetail->delete();  
            return response()->json(['success' => true, 'message' => 'Supplementary Detail deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete supplementary detail.'], 500);
        }
    }

    public function assignPersonnel(Request $request)
    {
        SrfPersonnel::create([
            'SampleRequestId' => $request->input('srf_id'),
            'CreatedDate' => now(), 
            'PersonnelType' => 20,
            'PersonnelUserId' => $request->input('RndPersonnel'),
            ]);
            return back();
    }
    public function editPersonnel(Request $request, $id)
    {
        $srfPersonnel = SrfPersonnel::findOrFail($id);
        $srfPersonnel->PersonnelUserId = $request->input('RndPersonnel');
        $srfPersonnel->save();
        return back();
    }
    public function deleteSrfPersonnel($id)
    {
        try { 
            $srfPersonnel = SrfPersonnel::findOrFail($id); 
            $srfPersonnel->delete();  
            return response()->json(['success' => true, 'message' => 'Assigned Personnel deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete Assigned Personnel.'], 500);
        }
    }
    public function uploadFile(Request $request)
    {
        $files = $request->file('srf_file');
        $names = $request->input('name');
        $srfId = $request->input('srf_id');
        
        if ($files) {
            foreach ($files as $index => $file) {
            $name = $names[$index];
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('public/srfFiles', $fileName);
            $fileUrl = '/storage/srfFiles/' . $fileName;       
            $uploadedFile = new SrfFile();
            $uploadedFile->SampleRequestId = $srfId;
            $uploadedFile->Name = $name;
            $uploadedFile->Path = $fileUrl;
            $uploadedFile->save();
            }
        }
        
        return redirect()->back()->with('success', 'File(s) Stored successfully');
    }

    public function editFile(Request $request, $id)
    {
        $srfFile = SrfFile::findOrFail($id);
        if ($request->has('name')) {
            $srfFile->Name = $request->input('name');
        }
        if ($request->hasFile('srf_file')) {
            $file = $request->file('srf_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('public/srfFiles', $fileName);
            $fileUrl = '/storage/srfFiles/' . $fileName;

            $srfFile->Path = $fileUrl;
        }

        $srfFile->save();

        return redirect()->back()->with('success', 'File updated successfully');
    }
    public function deleteFile($id)
    {
        try { 
            $srfFile = SrfFile::findOrFail($id); 
            $srfFile->delete();  
            return response()->json(['success' => true, 'message' => 'Assigned Personnel deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete Assigned Personnel.'], 500);
        }
    }

    public function addRawMaterial(Request $request)
    {
        SrfRawMaterial::create([
                'SampleRequestId' => $request->input('SampleRequestId'),
                'MaterialId' => $request->input('RawMaterial'),
                'LotNumber' => $request->input('LotNumber'),
                'Remarks' => $request->input('Remarks'),

            ]);
            return back();
    }

    public function editRawMaterial(Request $request, $id)
    {
        $srfRawMaterial = SrfRawMaterial::findOrFail($id);
        $srfRawMaterial->MaterialId = $request->input('RawMaterial');
        $srfRawMaterial->LotNumber = $request->input('LotNumber');
        $srfRawMaterial->Remarks = $request->input('Remarks');
        $srfRawMaterial->save();
        return back();
    }

    public function deleteSrfMaterial($id)
    {
        try { 
            $srfMaterial = SrfRawMaterial::findOrFail($id); 
            $srfMaterial->delete();  
            return response()->json(['success' => true, 'message' => 'Raw Material deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete RAw Material.'], 500);
        }
    }

    public function store(Request $request)
    {
        $refCode = $request->input('RefCode');
        $quantities = $request->input('Quantity');        
        foreach ($quantities as $key => $quantity) {
            if ($refCode == 2) {
                if ($quantity < 1000 && $request->input('UnitOfMeasure')[$key] == 1) {
                    return redirect()->back()->with('error', 'Quantity must be at least 1000g for QCD.')->withInput();
                } elseif ($quantity < 1 && $request->input('UnitOfMeasure')[$key] == 2) {
                    return redirect()->back()->with('error', 'Quantity must be at least 1kg for QCD.')->withInput();
                }
            }
            
            if ($refCode == 1) {
                if ($quantity > 999 && $request->input('UnitOfMeasure')[$key] == 1) {
                    return redirect()->back()->with('error', 'Quantity must be 999g or less for RND.')->withInput();
                } elseif ($quantity >= 1 && $request->input('UnitOfMeasure')[$key] == 2) {
                    return redirect()->back()->with('error', 'Quantity must be less than 1kg for RND.')->withInput();
                }
            }
        }

        $samplerequest = SampleRequest::create([
            'SrfNumber' => $request->input('SrfNumber'),
            'DateRequested' => $request->input('DateRequested'),
            'DateRequired' => $request->input('DateRequired'),
            'DateStarted' => $request->input('DateStarted'),
            'PrimarySalesPersonId' => $request->input('PrimarySalesPerson'),
            'SecondarySalesPersonId' => $request->input('SecondarySalesPerson'),
            'SoNumber' => $request->input('SoNumber'),
            'RefCode' => $request->input('RefCode'),
            'Status' => '10',
            'Progress' => '10',
            'SrfType' => $request->input('SrfType'),
            'ClientId' => $request->input('ClientId'),
            'ContactId' => $request->input('ClientContactId'),
            'InternalRemarks' => $request->input('Remarks'),
            'Courier' => $request->input('Courier'),
            'AwbNumber' => $request->input('AwbNumber'),
            'DateDispatched' => $request->input('DateDispatched'),
            'DateSampleReceived' => $request->input('DateSampleReceived'),
            'DeliveryRemarks' => $request->input('DeliveryRemarks'),
            'Note' => $request->input('Note'),

            ]);
            $maxId = SampleRequestProduct::max('Id');
            foreach ($request->input('ProductType') as $key => $value) {
                SampleRequestProduct::create([
                    'Id' => $maxId + $key + 1, 
                    'SampleRequestId' => $samplerequest->Id,
                    'ProductType' => $request->input('ProductType')[$key],
                    'ApplicationId' => $request->input('ApplicationId')[$key],
                    'ProductCode' => $request->input('ProductCode')[$key],
                    'ProductDescription' => $request->input('ProductDescription')[$key],
                    'NumberOfPackages' => $request->input('NumberOfPackages')[$key],
                    'Quantity' => $request->input('Quantity')[$key],
                    'UnitOfMeasureId' => $request->input('UnitOfMeasure')[$key],
                    'ProductIndex' => $key + 1,
                    'Label' => $request->input('Label')[$key],
                    'RpeNumber' => $request->input('RpeNumber')[$key],
                    'CrrNumber' => $request->input('CrrNumber')[$key],
                    'Remarks' => $request->input('RemarksProduct')[$key],

            ]);
         }
        return redirect()->route('sample_request.index')->with('success', 'Sample Request created successfully.');
    }
    public function update(Request $request, $id)
    {
        $srf = SampleRequest::with('requestProducts')->findOrFail($id);
    
        $srf->DateRequired = $request->input('DateRequired');
        $srf->DateStarted = $request->input('DateStarted');
        $srf->PrimarySalesPersonId = $request->input('PrimarySalesPerson');
        $srf->SecondarySalesPersonId = $request->input('SecondarySalesPerson');
        $srf->RefCode = $request->input('RefCode');
        $srf->SrfType = $request->input('SrfType');
        $srf->SoNumber = $request->input('SoNumber');
        $srf->ClientId = $request->input('ClientId');
        $srf->ContactId = $request->input('ClientContactId');
        $srf->InternalRemarks = $request->input('Remarks');
        $srf->Courier = $request->input('Courier');
        $srf->AwbNumber = $request->input('AwbNumber');
        $srf->DateDispatched = $request->input('DateDispatched');
        $srf->DateSampleReceived = $request->input('DateSampleReceived');
        $srf->DeliveryRemarks = $request->input('DeliveryRemarks');
        $srf->Note = $request->input('Note');
        $srf->save();
    
        foreach ($request->input('ProductType', []) as $key => $value) {
            $productId = $request->input('product_id.' . $key); 
    
            $srf->requestProducts()->updateOrCreate(
                ['id' => $productId],
                [
                    'SampleRequestId' => $id, 
                    'ProductType' => $value,
                    'ApplicationId' => $request->input('ApplicationId.' . $key),
                    'ProductCode' => $request->input('ProductCode.' . $key),
                    'ProductDescription' => $request->input('ProductDescription.' . $key),
                    'NumberOfPackages' => $request->input('NumberOfPackages.' . $key),
                    'Quantity' => $request->input('Quantity.' . $key),
                    'UnitOfMeasure' => $request->input('UnitOfMeasure.' . $key),
                    'Label' => $request->input('Label.' . $key),
                    'RpeNumber' => $request->input('RpeNumber.' . $key),
                    'CrrNumber' => $request->input('CrrNumber.' . $key),
                    'Remarks' => $request->input('RemarksProduct.' . $key),
                    // 'ProductIndex' => $key + 1,
                ]
            );
        }
    
        return redirect()->back()->with('success', 'Sample Request updated successfully');
    }
    
    public function approveSrfSales($id)
    {
        $approveSrfSales = SampleRequest::find($id);
        if ($approveSrfSales) {
            $buttonClicked = request()->input('submitbutton');    
            if ($buttonClicked === 'Approve to R&D') {
                $approveSrfSales->Progress = 30; 
                $approveSrfSales->InternalRemarks = request()->input('Remarks'); 
            } elseif ($buttonClicked === 'Approve to QCD') {
                $approveSrfSales->Progress = 80;
                $approveSrfSales->InternalRemarks = request()->input('submitbutton'); 
            }
            $approveSrfSales->save();
            return back();
        } 
    }    
    public function receiveSrf($id)
    {
        $receiveSrf = SampleRequest::find($id);
        if ($receiveSrf) {
                 $receiveSrf->Progress = 35; 
        }
            $receiveSrf->save();
            return back();
    } 
    public function startSrf($id)
    {
        $startSrf = SampleRequest::find($id);    
        if ($startSrf) {
                $startSrf->Progress = 50; 
                $startSrf->DateStarted = now(); 
        }
            $startSrf->save();
            return back();
    }
    public function pauseSrf($id)
    {
        $pauseSrf = SampleRequest::find($id);  
        if ($pauseSrf) {
                $pauseSrf->Progress = 55; 
                $pauseSrf->InternalRemarks = request()->input('Remarks'); 
        }
            $pauseSrf->save();
            return back();
    } 
}    


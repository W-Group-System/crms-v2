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
use App\SalesApprovers;
use App\SampleRequestProduct;
use App\SrfDetail;
use App\SrfFile;
use App\SrfPersonnel;
use App\SrfProgress;
use App\SrfRawMaterial;
use App\TransactionApproval;
use App\TransactionLogs;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use OwenIt\Auditing\Models\Audit;
use PDF;
use RealRashid\SweetAlert\Facades\Alert;

class SampleRequestController extends Controller
{
    public function index(Request $request)
    {   
        $clients = Client::where('PrimaryAccountManagerId', auth()->user()->user_id)
        ->orWhere('SecondaryAccountManagerId', auth()->user()->user_id)
        ->get();
        $contacts = Contact::all();
        $categories = IssueCategory::all();
        $departments = ConcernDepartment::all(); 
        $productApplications = ProductApplication::all();   
        // $salesPersons = User::whereHas('salespersons')->get();
        $loggedInUser = Auth::user(); 
        $role = $loggedInUser->role;
        $withRelation = $role->type == 'LS' ? 'localSalesApprovers' : 'internationalSalesApprovers';

        if ($role->name == 'Staff L2' ) {
            $salesApprovers = SalesApprovers::where('SalesApproverId', $loggedInUser->id)->pluck('UserId');
            $primarySalesPersons = User::whereIn('id', $salesApprovers)->orWhere('id', $loggedInUser->id)->get();
            $secondarySalesPersons = User::whereIn('id',$loggedInUser->salesApproverById->pluck('SalesApproverId'))->orWhere('id', $loggedInUser->id)->get();
            
        } else {
            $primarySalesPersons = User::with($withRelation)->where('id', $loggedInUser->id)->get();
            $secondarySalesPersons = User::whereIn('id', $loggedInUser->salesApproverById->pluck('SalesApproverId'))->get();
        }
        $productCodes = Product::where('status', '4')->get();
        $search = $request->input('search');
        $sort = $request->get('sort', 'Id');
        $direction = $request->get('direction', 'desc');
        $entries = $request->entries;
        $open = $request->open;
        $close = $request->close;
        $progress = $request->query('progress'); // Get the status from the query parameters

        $userId = Auth::id(); 
        $userByUser = Auth::user()->user_id; 

        
        $sampleRequests = SampleRequest::with('requestProducts') 
            ->when($progress, function($query) use ($progress, $userId, $userByUser) {
                if ($progress == '10') {
                    // When filtering by '10', include all relevant progress status records
                    $query->where('Progress', '10')
                        ->where(function($query) use ($userId, $userByUser) {
                            $query->where('PrimarySalesPersonId', $userId)
                                ->orWhere('SecondarySalesPersonId', $userId)
                                ->orWhere('PrimarySalesPersonId', $userByUser)
                                ->orWhere('SecondarySalesPersonId', $userByUser);
                        });
                } else {
                    // Apply progress filter if it's not '10'
                    $query->where('Progress', $progress);
                }
            })
            ->when($request->has('open') && $request->has('close'), function($query)use($request) {
                $query->whereIn('Status', [$request->open, $request->close]);
            })
            ->when($request->has('open') && !$request->has('close'), function($query)use($request) {
                $query->where('Status', $request->open);
            })
            ->when($request->has('close') && !$request->has('open'), function($query)use($request) {
                $query->where('Status', $request->close);
            })
            ->where(function ($query) use ($search){
                $query->where('SrfNumber', 'LIKE', '%' . $search . '%')
                ->orWhere('DateRequested', 'LIKE', '%' . $search . '%')
                ->orWhere('DateRequired', 'LIKE', '%' . $search . '%');
                // ->orWhereHas('client', function ($q) use ($search) {
                //     $q->where('name', 'LIKE', '%' . $search . '%');
                // });
            })
            ->where('SrfNumber', 'LIKE', '%' . 'SRF-LS' . '%')
            ->orderBy($sort, $direction)
            ->paginate($request->entries ?? 10);

        $openStatus = request('open');
        $closeStatus = request('close');
        $products = SampleRequestProduct::whereHas('sampleRequest', function ($query) use ($search, $openStatus, $closeStatus) {
            $query->where(function ($query) use ($search) {
                $query->where('SrfNumber', 'LIKE', '%' . $search . '%')
                      ->orWhere('DateRequested', 'LIKE', '%' . $search . '%')
                      ->orWhere('DateRequired', 'LIKE', '%' . $search . '%');
            });
            if ($openStatus || $closeStatus) {
                $query->where(function ($query) use ($openStatus, $closeStatus) {
                    if ($openStatus) {
                        $query->orWhere('Status', $openStatus);
                    }
                    if ($closeStatus) {
                        $query->orWhere('Status', $closeStatus);
                    }
                });
            }
        })
        
        ->whereHas('sampleRequest', function ($query) {
            $query->where('SrfNumber', 'LIKE', '%' . 'SRF-IS' . '%');
        }) 
        ->orderBy('id' , 'desc')
        ->paginate($request->entries ?? 10);
        $rndSrf = SampleRequest::with('requestProducts') 
        ->where(function ($query) use ($search){
            $query->where('SrfNumber', 'LIKE', '%' . $search . '%')
            ->orWhere('DateRequested', 'LIKE', '%' . $search . '%')
            ->orWhere('DateRequired', 'LIKE', '%' . $search . '%');
        })
        ->orderBy($sort, $direction)
        ->paginate($request->entries ?? 10);

       
        return view('sample_requests.index', compact('products', 'sampleRequests', 'rndSrf', 'clients', 'contacts', 'categories', 'departments', 'productApplications', 'productCodes', 'search', 'primarySalesPersons', 'secondarySalesPersons', 'entries', 'open','close'));
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
        $SampletNumber = $sampleRequest->SrfNumber;

        $clientId = $sampleRequest->ClientId;
        $activities = Activity::where('TransactionNumber', $SampletNumber)->get();
        $SrfSupplementary = SrfDetail::where('SampleRequestId', $scrfNumber)->get();
        $assignedPersonnel = SrfPersonnel::where('SampleRequestId', $scrfNumber)->get();
        $SrfMaterials = SrfRawMaterial::where('SampleRequestId', $scrfNumber)->get();
        $rndPersonnel = User::whereHas('rndUsers')->get();
        $srfProgress = SrfProgress::all();
        $srfFileUploads = SrfFile::where('SampleRequestId', $scrfNumber)->get();
        $clients = Client::where('PrimaryAccountManagerId', auth()->user()->user_id)
        ->orWhere('SecondaryAccountManagerId', auth()->user()->user_id)
        ->get();
        $loggedInUser = Auth::user(); 
        $role = $loggedInUser->role;
        $withRelation = $role->type == 'LS' ? 'localSalesApprovers' : 'internationalSalesApprovers';
        if (($role->description == 'International Sales - Supervisor') || ($role->description == 'Local Sales - Supervisor')) {
            $salesApprovers = SalesApprovers::where('SalesApproverId', $loggedInUser->id)->pluck('UserId');
            $primarySalesPersons = User::whereIn('id', $salesApprovers)->orWhere('id', $loggedInUser->id)->get();
            $secondarySalesPersons = User::whereIn('id', $salesApprovers)->orWhere('id', $loggedInUser->id)->get();
            
        } else {
            $primarySalesPersons = User::with($withRelation)->where('id', $loggedInUser->id)->get();
            $secondarySalesPersons = User::whereIn('id', $loggedInUser->salesApproverById->pluck('SalesApproverId'))->get();
        }
        $productApplications = ProductApplication::all(); 
        $productCodes = Product::where('status', '4')->get();
        $users = User::wherehas('localsalespersons')->get();
        $rawMaterials = RawMaterial::where('IsDeleted', '0')
        ->orWhere('deleted_at', '=', '')->get();
        $transactionApprovals = TransactionApproval::where('Type', '30')
        ->where('TransactionId', $scrfNumber)
        ->get();

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
    $orderedCombinedLogs = $combinedLogs->sortBy('CreatedDate');
        return view('sample_requests.view', compact('sampleRequest', 'SrfSupplementary', 'rndPersonnel', 'assignedPersonnel', 'activities', 'srfFileUploads', 'rawMaterials', 'SrfMaterials', 'orderedCombinedLogs', 'srfProgress', 'clients', 'users', 'primarySalesPersons', 'secondarySalesPersons', 'productApplications', 'productCodes','transactionApprovals'));
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
   $srfNumber = null;
    $currentYear = date('y');
    $deptCode = '';

    if (auth()->user()->role->type == 'LS') {
        $deptCode = 'LS';
    } elseif (auth()->user()->role->type == 'IS') {
        $deptCode = 'IS';
    }

    if ($deptCode) {
        $checkSrf = SampleRequest::select('SrfNumber')
            ->where('SrfNumber', 'LIKE', "SRF-$deptCode-$currentYear%")
            ->orderBy('SrfNumber', 'desc')
            ->first();

        if ($checkSrf) {
            $count = (int)substr($checkSrf->SrfNumber, -4);
        } else {
            $count = 0; 
        }

        $totalCount = str_pad($count + 1, 4, '0', STR_PAD_LEFT); 
        $srfNumber = 'SRF' . '-' . $deptCode . '-' . $currentYear . '-' . $totalCount;
    }


        $samplerequest = SampleRequest::create([
            'SrfNumber' => $srfNumber,
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


        foreach ($request->input('ProductCode', []) as $key => $value) {
            SampleRequestProduct::create([
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

    foreach ($request->input('ProductCode', []) as $key => $value) {
        $productId = $request->input('product_id.' . $key); 

        $srf->requestProducts()->updateOrCreate(
            ['id' => $productId],  
            [
                'SampleRequestId' => $id, 
                'ProductType' => $request->input('ProductType.' . $key),
                'ApplicationId' => $request->input('ApplicationId.' . $key),
                'ProductCode' =>  $value,
                'ProductDescription' => $request->input('ProductDescription.' . $key),
                'NumberOfPackages' => $request->input('NumberOfPackages.' . $key),
                'Quantity' => $request->input('Quantity.' . $key),
                'UnitOfMeasure' => $request->input('UnitOfMeasure.' . $key),
                'Label' => $request->input('Label.' . $key),
                'RpeNumber' => $request->input('RpeNumber.' . $key),
                'CrrNumber' => $request->input('CrrNumber.' . $key),
                'Remarks' => $request->input('RemarksProduct.' . $key),
                'Disposition' => $request->input('Disposition.' . $key),
                'DispositionRejectionDescription' => $request->input('DispositionRejectionDescription.' . $key),
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

                $transactionApproval = new TransactionApproval();
                $transactionApproval->Type = '30';
                $transactionApproval->TransactionId = $id;
                $transactionApproval->UserId = Auth::user()->id;
                $transactionApproval->Remarks = request()->input('Remarks');
                $transactionApproval->RemarksType = 'approved';
                
                $transactionApproval->save(); 
            } elseif ($buttonClicked === 'Approve to QCD') {
                $approveSrfSales->Progress = 80;
                $approveSrfSales->InternalRemarks = request()->input('submitbutton'); 
            }
            $approveSrfSales->save();

            Alert::success('Successfully Saved')->persistent('Dismiss');
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

            Alert::success('Successfully Saved')->persistent('Dismiss');
            return back();
    } 
    public function StartSrf($id)
    {
        $startSrf = SampleRequest::find($id);    
        if ($startSrf) {
                $startSrf->Progress = 50; 
                $startSrf->DateStarted = now(); 
        }
            $startSrf->save();
            Alert::success('Successfully Saved')->persistent('Dismiss');
            return back();
    }
    public function PauseSrf($id)
    {
        $pauseSrf = SampleRequest::find($id);  
        if ($pauseSrf) {
                $pauseSrf->Progress = 55; 
                $pauseSrf->save();

                $transactionApproval = new TransactionApproval();
                $transactionApproval->Type = '30';
                $transactionApproval->TransactionId = $id;
                $transactionApproval->UserId = Auth::user()->id;
                $transactionApproval->Remarks = request()->input('Remarks');
                $transactionApproval->RemarksType = 'paused';
                $transactionApproval->save(); 
                }

            Alert::success('Successfully Saved')->persistent('Dismiss');
            return back();
    } 
    public function RndUpdate($id)
    {
        $pauseSrf = SampleRequest::find($id);  
        if ($pauseSrf) {
                $pauseSrf->Progress = request()->input('Progress'); 
        }
            $pauseSrf->save();
            Alert::success('Successfully Saved')->persistent('Dismiss');
            return back();
    } 
    public function cancelRemarks(Request $request, $id)
    {
        $sampleRequest = SampleRequest::findOrFail($id);
        $sampleRequest->Status = 50;
        $sampleRequest->save();

        $transactionApproval = new TransactionApproval();
        $transactionApproval->Type = '30';
        $transactionApproval->TransactionId = $id;
        $transactionApproval->UserId = Auth::user()->id;
        $transactionApproval->Remarks = request()->input('cancel_remarks');
        $transactionApproval->RemarksType = 'cancelled';
        $transactionApproval->save(); 

        Alert::success('Successfully Cancelled')->persistent('Dismiss');
        return back();
    }

    public function closeRemarks(Request $request, $id)
    {
        $sampleRequest = SampleRequest::findOrFail($id);
        $sampleRequest->Status = 30;
        $sampleRequest->save();

        $transactionApproval = new TransactionApproval();
        $transactionApproval->Type = '30';
        $transactionApproval->TransactionId = $id;
        $transactionApproval->UserId = Auth::user()->id;
        $transactionApproval->Remarks = request()->input('close_remarks');
        $transactionApproval->RemarksType = 'closed';
        $transactionApproval->save(); 
        Alert::success('Successfully Closed')->persistent('Dismiss');
        return back();
    }
    public function ReturnToSales($id)
    {
        $sampleRequest = SampleRequest::findOrFail($id);
        $sampleRequest->Progress = 10;
        $sampleRequest->save(); 

        Alert::success('Successfully return to sales')->persistent('Dismiss');
        return back();
    }

    public function returnToRnd($id)
    {
        $sampleRequest = SampleRequest::findOrFail($id);
        $sampleRequest->Progress = 50;
        $sampleRequest->save(); 

        Alert::success('Successfully return to rnd')->persistent('Dismiss');
        return back();
    }

    public function initialQuantity($id)
    {
        $sampleRequest = SampleRequest::findOrFail($id);
        $sampleRequest->Progress = 11;
        $sampleRequest->save(); 

        Alert::success('Successfully return to rnd')->persistent('Dismiss');
        return back();
    }
    public function submitSrf(Request $request, $id)
    {
        $srf = SampleRequest::findOrFail($id);
        $srf->Progress = 57;
        $srf->save();

        Alert::success('Successfully Submitted')->persistent('Dismiss');
        return back();
    }

    public function deleteSrfProduct(Request $request , $id)
    {
        $product = SampleRequestProduct::find($id); 
        if ($product) {
            $product->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    public function AcceptSrf($id)
    {
        $srf = SampleRequest::findOrFail($id);
        $srf->Progress = 70;
        $srf->save(); 

        Alert::success('Sales Accepted')->persistent('Dismiss');
        return back();
    }

    public function OpenStatus(Request $request, $id)
    {
        $srf = SampleRequest::findOrFail($id);
        $srf->Status = 10;
        $srf->save();

        Alert::success('The status are now open')->persistent('Dismiss');
        return back();
    }

    public function CompleteSrf(Request $request, $id)
    {
        $srf = SampleRequest::findOrFail($id);
        $srf->Progress = 60;
        $srf->DateCompleted = date('Y-m-d h:i:s');
        $srf->save();

        Alert::success('Successfully Completed')->persistent('Dismiss');
        return back();
    }


    public function deleteSrfActivity($id)
    {
        try { 
            $activity = Activity::findOrFail($id); 
            $activity->delete();  
            return response()->json(['success' => true, 'message' => 'File deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete File.'], 500);
        }
    }

    public function print_srf(Request $request, $id)
    {
        $srf = SampleRequest::findOrFail($id);

        View::share('sample_requests', $srf);
        $pdf = PDF::loadView('sample_requests.print', [
            'sample_requests' => $srf,
        ])->setPaper('a4', 'portrait');
    
        return $pdf->stream('print.pdf');
    }
    public function editDisposition(Request $request, $sampleRequestId)
{
    $sampleRequest = SampleRequest::find($sampleRequestId);

    foreach ($sampleRequest->requestProducts as $product) {
        if (isset($request->Disposition[$product->id])) {
            $product->Disposition = $request->Disposition[$product->id];
            $product->DispositionRejectionDescription = $request->DispositionRejectionDescription[$product->id];
            $product->save();
        }
    }

    return redirect()->back()->with('success', 'Dispositions updated successfully.');
}


}    


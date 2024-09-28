<?php

namespace App\Http\Controllers;
use App\Activity;
use App\FileActivity;
use App\ProductEvaluation;
use App\Client;
use App\Exports\ProductEvaluationExport;
use App\PriceCurrency;
use App\ProductApplication;
use App\ProjectName;
use App\RequestProductEvaluation;
use App\RpeDetail;
use App\RpeFile;
use App\RpePersonnel;
use App\SalesApprovers;
use App\SalesUser;
use App\TransactionApproval;
use App\TransactionLogs;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use OwenIt\Auditing\Models\Audit;
use RealRashid\SweetAlert\Facades\Alert;
use Collective\Html\FormFacade as Form;



class RequestProductEvaluationController extends Controller
{
    // List
    public function index(Request $request)
    {   
        $search = $request->input('search');
        $open = $request->open;
        $close = $request->close;
        $status = $request->query('status'); // Get the status from the query parameters
        $progress = $request->query('progress'); // Get the status from the query parameters

        $userId = Auth::id(); 
        $userByUser = Auth::user()->user_id; 

        $request_product_evaluations = RequestProductEvaluation::with(['client', 'product_application', 'rpe_personnels'])
            ->when($request->input('status'), function($query) use ($request, $userId, $userByUser) {
                $status = $request->input('status');
                $role = auth()->user()->role;
                $userType = $role->type;  
                $userName = $role->name;  
                
                if ($status == '50') {
                    if ($userType == 'RND' && $userName == 'Staff L2') {
                        $query->where('Status', '50');
                    } else {
                        // Default logic for other users
                        $query->where('Status', '50')
                            ->where(function($query) use ($userId, $userByUser) {
                                $query->where(function($query) use ($userId, $userByUser) {
                                    $query->where('PrimarySalesPersonId', $userId)
                                        ->orWhere('SecondarySalesPersonId', $userId)
                                        ->orWhere('PrimarySalesPersonId', $userByUser)
                                        ->orWhere('SecondarySalesPersonId', $userByUser);
                                });
                                // Check for related 'crr_personnels' entries
                                $query->orWhereHas('crr_personnels', function($query) use ($userId, $userByUser) {
                                    $query->where('PersonnelUserId', $userId)
                                        ->orWhere('PersonnelUserId', $userByUser);
                                });
                            });
                    }
                } else {
                    // Apply status filter if it's not '50'
                    $query->where('Status', $status);
                }
            })
            ->when($request->input('status'), function($query) use ($request, $userId, $userByUser) {
                $status = $request->input('status');
                $role = auth()->user()->role;
                $userType = $role->type;  
                $userName = $role->name;

                if ($status == '10') {
                    if ($userType == 'RND' && $userName == 'Staff L2') {
                        $query->where('Status', '10');
                    } else {
                        // Default logic for other users
                        $query->where('Status', '10')
                            ->where(function($query) use ($userId, $userByUser) {
                                $query->where(function($query) use ($userId, $userByUser) {
                                    $query->where('PrimarySalesPersonId', $userId)
                                        ->orWhere('SecondarySalesPersonId', $userId)
                                        ->orWhere('PrimarySalesPersonId', $userByUser)
                                        ->orWhere('SecondarySalesPersonId', $userByUser);
                                });
                                // Check for related 'crr_personnels' entries
                                $query->orWhereHas('crr_personnels', function($query) use ($userId, $userByUser) {
                                    $query->where('PersonnelUserId', $userId)
                                        ->orWhere('PersonnelUserId', $userByUser);
                                });
                            });
                    }
                } else {
                    // Apply status filter if it's not '50'
                    $query->where('Status', $status);
                }
            })
            ->when($request->input('status'), function($query) use ($request, $userId, $userByUser) {
                $status = $request->input('status');
                $role = auth()->user()->role;
                $userType = $role->type;  
                $userName = $role->name; 

                if ($status == '30') {
                    if ($userType == 'RND' && $userName == 'Staff L2') {
                        $query->where('Status', '30');
                    } else {
                        // Default logic for other users
                        $query->where('Status', '30')
                            ->where(function($query) use ($userId, $userByUser) {
                                $query->where(function($query) use ($userId, $userByUser) {
                                    $query->where('PrimarySalesPersonId', $userId)
                                        ->orWhere('SecondarySalesPersonId', $userId)
                                        ->orWhere('PrimarySalesPersonId', $userByUser)
                                        ->orWhere('SecondarySalesPersonId', $userByUser);
                                });
                                // Check for related 'crr_personnels' entries
                                $query->orWhereHas('crr_personnels', function($query) use ($userId, $userByUser) {
                                    $query->where('PersonnelUserId', $userId)
                                        ->orWhere('PersonnelUserId', $userByUser);
                                });
                            });
                    }
                } else {
                    // Apply status filter if it's not '50'
                    $query->where('Status', $status);
                }
            })
            ->when($progress, function($query) use ($progress, $userId, $userByUser) {
                if ($progress == '10') {
                    // When filtering by '10', include all relevant progress status records
                    $query->where('Progress', '10')
                        ->where(function($query) use ($userId, $userByUser) {
                            $query->where('SecondarySalesPersonId', $userId)
                                // ->orWhere('SecondarySalesPersonId', $userId)
                                // ->orWhere('PrimarySalesPersonId', $userByUser)
                                ->orWhere('SecondarySalesPersonId', $userByUser);
                        });
                } else {
                    // Apply progress filter if it's not '10'
                    $query->where('Progress', $progress);
                }
            })
            ->when($request->input('DueDate') === 'past', function($query) {
                $query->where('DueDate', '<', now())
                        ->where('Status', '10'); 
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
            // ->orWhere('RpeResult', 'LIKE', '%' . $search . '%')
            ->when(auth()->user()->role->type == 'LS', function($query) {
                $query->where('RpeNumber', 'LIKE', '%' . 'RPE-LS' . '%');
            })
            ->when(auth()->user()->role->type == 'IS', function($query) {
                $query->where('RpeNumber', 'LIKE', '%' . 'RPE-IS' . '%');
            })
            // ->when(auth()->user()->role->type == 'RND', function($query) {
            //     $query->where('RpeNumber', 'LIKE', '%' . 'RPE-IS' . '%')
            //         ->orWhere('RpeNumber', 'LIKE', '%' . 'RPE-LS' . '%');
            // })
            ->when($progress == '30', function($query) {
                $query->where('Progress', '30')
                      ->where('Status', '10');
            })
            ->when($progress == '57', function($query) {
                $query->where('Progress', '57')
                      ->where('Status', '10');
            })
            ->when($progress == '81', function($query) {
                $query->where('Progress', '81')
                      ->where('Status', '10');
            })
            ->where(function($query)use($search){
                $query->where('RpeNumber', 'LIKE', '%'.$search.'%')
                    ->orWhere('DateCreated', 'LIKE','%'.$search.'%')
                    ->orWhere('DueDate', 'LIKE','%'.$search.'%')
                    ->orWhereHas('client', function($query)use($search) {
                        $query->where('Name', 'LIKE','%'.$search.'%');
                    })
                    ->orWhereHas('product_application', function($query)use($search) {
                        $query->where('Name', 'LIKE','%'.$search.'%');
                    })
                    ->orWhere('RpeResult', 'LIKE','%'.$search.'%');
            })
            ->orderBy('id', 'desc')
            ->paginate($request->entries ?? 10);

        // $clients = Client::where('PrimaryAccountManagerId', auth()->user()->user_id)
        // ->orWhere('SecondaryAccountManagerId', auth()->user()->user_id)
        // ->get();
        $clients = Client::where(function($query) {
            if (auth()->user()->role->name == "Department Admin" || auth()->user()->role->name == "Staff L1" || auth()->user()->role->name == "Staff L2") {
                if (auth()->user()->role->type == "LS"){
                    $query->where('Type', 1);
                } else {
                    $query->where('PrimaryAccountManagerId', auth()->user()->id)
                    ->orWhere('PrimaryAccountManagerId', auth()->user()->user_id)
                    ->orWhere('SecondaryAccountManagerId', auth()->user()->id)
                    ->orWhere('SecondaryAccountManagerId', auth()->user()->user_id);
                }
            }
        })
        ->get();
        // $users = User::all();
        $loggedInUser = Auth::user(); 
        $role = $loggedInUser->role;
        $withRelation = $role->type == 'LS' ? 'localSalesApprovers' : 'internationalSalesApprovers';
        if ($role->name == 'Staff L2' ) {
            $salesApprovers = SalesApprovers::where('SalesApproverId', $loggedInUser->id)->pluck('UserId');
            $primarySalesPersons = User::whereIn('id', $salesApprovers)->orWhere('id', $loggedInUser->id)->get();
            $secondarySalesPersons = User::whereIn('id', $salesApprovers)->orWhere('id', $loggedInUser->id)->get();
            
        } else {
            $primarySalesPersons = User::with($withRelation)->where('id', $loggedInUser->id)->get();
            $secondarySalesPersons = User::whereIn('id', $loggedInUser->salesApproverById->pluck('SalesApproverId'))->get();
        }
        $price_currencies = PriceCurrency::all();
        $project_names = ProjectName::all();

        $product_applications = ProductApplication::all();
        $entries = $request->entries;
        $users = User::where('is_active', 1)->get();

        return view('product_evaluations.index', compact('request_product_evaluations','clients', 'product_applications',  'price_currencies', 'project_names', 'search' , 'open', 'close', 'primarySalesPersons', 'secondarySalesPersons', 'entries', 'users')); 
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        // $salesUser = SalesUser::where('SalesUserId', $user->user_id)->first();
        // $type = $salesUser->Type == 2 ? 'IS' : 'LS';
        // $year = Carbon::parse($request->input('CreatedDate'))->format('y');
        // $lastEntry = RequestProductEvaluation::where('RpeNumber', 'LIKE', "RPE-{$type}-%")
        //             ->orderBy('id', 'desc')
        //             ->first();
        // $lastNumber = $lastEntry ? intval(substr($lastEntry->RpeNumber, -4)) : 0;
        // $newIncrement = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        // $rpeNo = "RPE-{$type}-{$year}-{$newIncrement}";

        $user = Auth::user(); 
        if (($user->department_id == 5) || ($user->department_id == 38))
        {
            $type = "";
            $year = date('y');
            if ($user->department_id == 5)
            {
                $type = "IS";
                $rpeList = RequestProductEvaluation::where('RpeNumber', 'LIKE', '%RPE-IS%')->orderBy('id', 'desc')->first();
                $count = substr($rpeList->RpeNumber, 10);
                $totalCount = $count + 1;
                
                $rpeNo = "RPE-".$type.'-'.$year.'-0'.$totalCount;
            }

            if ($user->department_id == 38)
            {
                $type = "LS";
                $rpeList = RequestProductEvaluation::where('RpeNumber', 'LIKE', '%RPE-LS%')->orderBy('id', 'desc')->first();
                $count = substr($rpeList->RpeNumber, 10);
                $totalCount = $count + 1;
                
                $rpeNo = "RPE-".$type.'-'.$year.'-0'.$totalCount;
            }
        }

        $productEvaluationData = RequestProductEvaluation::create([
            'RpeNumber' => $rpeNo,
            // 'CreatedDate' => $request->input('CreatedDate'),
            'DueDate' => $request->input('DueDate'),
            'ClientId' => $request->input('ClientId'),
            'ApplicationId' => $request->input('ApplicationId'),
            'PotentialVolume' => $request->input('PotentialVolume'),
            'TargetRawPrice' => $request->input('TargetRawPrice'),
            'ProjectNameId' => $request->input('ProjectNameId'),
            'PrimarySalesPersonId' => $request->input('PrimarySalesPersonId'),
            'SecondarySalesPersonId' => $request->input('SecondarySalesPersonId'),
            'Priority' => $request->input('Priority'),
            'AttentionTo' => $request->input('AttentionTo'),
            'UnitOfMeasureId' => $request->input('UnitOfMeasureId'),
            'CurrencyId' => $request->input('CurrencyId'),
            'SampleName' => $request->input('SampleName'),
            'Supplier' => $request->input('Supplier'),
            'RpeReferenceNumber' => $request->input('RpeReferenceNumber'),
            'ObjectiveForRpeProject' => $request->input('ObjectiveForRpeProject'),
            'Status' =>'10',
            'Progress' => '10',
        ]);
        if ($request->has('SalesRpeFile'))
        {
            $attachments = $request->file('SalesRpeFile');
            foreach($attachments as $attachment)
            {
                $name = time().'_'.$attachment->getClientOriginalName();
                $attachment->move(public_path('rpeFiles'), $name);
                $path = '/rpeFiles/'.$name;

                $rpeFiles = new RpeFile();
                $rpeFiles->Name = $name;
                $rpeFiles->Path = $path;
                $rpeFiles->RequestProductEvaluationId = $productEvaluationData['id'];

                if (auth()->user()->role->type == "IS" || auth()->user()->role->type == "LS")
                {
                    $rpeFiles->UserType = "Sales";
                }
                if (auth()->user()->role->type == "RND")
                {
                    $rpeFiles->UserType = "RND";
                }
                
                $rpeFiles->save();
            }
        }
        return redirect()->back()->with('success', 'RPE added successfully.');
    }
    public function update(Request $request, $id)
    {
        $rpe = RequestProductEvaluation::with(['client', 'product_application'])->findOrFail($id);
        $rpe->DueDate = $request->input('DueDate');
        $rpe->ClientId = $request->input('ClientId');
        $rpe->ApplicationId = $request->input('ApplicationId');
        $rpe->PotentialVolume = $request->input('PotentialVolume');
        $rpe->TargetRawPrice = $request->input('TargetRawPrice');
        $rpe->ProjectNameId = $request->input('ProjectNameId');
        $rpe->PrimarySalesPersonId = $request->input('PrimarySalesPersonId');
        $rpe->SecondarySalesPersonId = $request->input('SecondarySalesPersonId');
        $rpe->Priority = $request->input('Priority');
        $rpe->AttentionTo = $request->input('AttentionTo');
        $rpe->UnitOfMeasureId = $request->input('UnitOfMeasureId');
        $rpe->CurrencyId = $request->input('CurrencyId');
        $rpe->SampleName = $request->input('SampleName');
        $rpe->Supplier = $request->input('Supplier');
        $rpe->ObjectiveForRpeProject = $request->input('ObjectiveForRpeProject');
        $rpe->Manufacturer = $request->input('Manufacturer');
        // $rpe->Status = $request->input('Status');
        if ($request->has('action'))
        {
            if ($request->action == "update_rnd")
            {
                $rpe->DdwNumber = $request->ddw_number;
                $rpe->DateReceived = $request->date_received;
                $rpe->RpeResult = $request->rpe_recommendation;
                $rpe->DateCompleted = $request->date_completed;
                $rpe->DateStarted = $request->date_started;
            }
        }
        $rpe->save();

        if ($request->has('SalesRpeFile'))
        {
            $attachments = $request->file('SalesRpeFile');
            foreach($attachments as $attachment)
            {
                $name = time().'_'.$attachment->getClientOriginalName();
                $attachment->move(public_path('rpeFiles'), $name);
                $path = '/rpeFiles/'.$name;

                $rpeFiles = new RpeFile();
                $rpeFiles->Name = $name;
                $rpeFiles->Path = $path;
                $rpeFiles->RequestProductEvaluationId = $id;
                
                if (auth()->user()->role->type == "IS" || auth()->user()->role->type == "LS")
                {
                    $rpeFiles->UserType = "Sales";
                }
                if (auth()->user()->role->type == "RND")
                {
                    $rpeFiles->UserType = "Rnd";
                }
                
                $rpeFiles->save();
            }
        }
        return redirect()->back()->with('success', 'RPE updated successfully');
    }

    public function destroy($id)
    {
        try {
            $basePrice = RequestProductEvaluation::findOrFail($id); 
            $basePrice->delete();  
            return response()->json(['success' => true, 'message' => 'Request deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete Request.'], 500);
        }
    }

    public function view($id)
    {
        $requestEvaluation = RequestProductEvaluation::with(['client', 'product_application', 'rpePersonnel', 'supplementaryDetails'])->findOrFail($id);
        $rpeNumber = $requestEvaluation->id;
        $RequestNumber = $requestEvaluation->RpeNumber;
        $clientId = $requestEvaluation->ClientId;
        // $RpeSupplementary = RpeDetail::where('RequestProductEvaluationId', $rpeNumber)->get();
        $rndPersonnel = User::where('is_active', 1)->where('department_id', 15)->whereNotIn('id', [auth()->user()->id])->get();
        // $rndPersonnel = User::whereHas('rndUsers')->get();
        $activities = Activity::where('TransactionNumber', $RequestNumber)->get();
        $rpeFileUploads = RpeFile::where('RequestProductEvaluationId', $rpeNumber)->where('userType', 'RND')->get();
        // $clients = Client::where('PrimaryAccountManagerId', auth()->user()->user_id)
        // ->orWhere('SecondaryAccountManagerId', auth()->user()->user_id)
        // ->get();
        $clients = Client::where(function($query) {
            if (auth()->user()->role->name == "Department Admin" || auth()->user()->role->name == "Staff L1" || auth()->user()->role->name == "Staff L2") {
                if (auth()->user()->role->type == "LS"){
                    $query->where('Type', 1);
                } else {
                    $query->where('PrimaryAccountManagerId', auth()->user()->id)
                    ->orWhere('PrimaryAccountManagerId', auth()->user()->user_id)
                    ->orWhere('SecondaryAccountManagerId', auth()->user()->id)
                    ->orWhere('SecondaryAccountManagerId', auth()->user()->user_id);
                }
            }
        })
        ->get();
        // $users = User::wherehas('localsalespersons')->get();
        $users = User::where('is_active', 1)->get();
        $rpeTransactionApprovals  = TransactionApproval::where('TransactionId', $id)
        ->where('Type', '20')
        ->get();
        
        $transactionLogs = TransactionLogs::where('Type', '20')
        ->where('TransactionId', $rpeNumber)
        ->get();

        $audits = Audit::where('auditable_id', $rpeNumber)
        ->whereIn('auditable_type', [RequestProductEvaluation::class, RpeDetail::class, RpePersonnel::class, RpeFile::class])
        ->get();

        $mappedAudits = $audits->map(function ($audit) {
            $details = '';
            if ($audit->auditable_type === 'App\RpeFile') {
                $details = $audit->event . " " . 'RPE Files';
            } elseif ($audit->auditable_type === 'App\RpeDetail') {
                $details = $audit->event . " " . 'RPE Supplementary';
            } elseif ($audit->auditable_type === 'App\RpePersonnel') {
                $details = $audit->event . " " . 'RPE R&D Personnel';
            } elseif ($audit->auditable_type === 'App\RequestProductEvaluation') {
                // $details = $audit->event . " " . 'Request Product Evaluation';
                if (isset($audit->new_values['Progress']) && $audit->new_values['Progress'] == 20) {
                    $details = "Approve request product evaluation entry";
                } elseif (isset($audit->new_values['Progress']) && ($audit->new_values['Progress'] == 30 || $audit->new_values['Progress'] == 80)) {
                    $details = "Approve request product evaluation entry";
                } elseif (isset($audit->new_values['Progress']) && $audit->new_values['Progress'] == 35) {
                    $details = "Receive request product evaluation entry";
                } elseif (isset($audit->new_values['Progress']) && $audit->new_values['Progress'] == 55) {
                    $details = "Pause request product evaluation transaction." . isset($audit->new_values['Remarks']);
                } elseif (isset($audit->new_values['Progress']) && $audit->new_values['Progress'] == 50) {
                    $details = "Start request product evaluation transaction";
                } elseif (isset($audit->new_values['Progress']) && $audit->new_values['Progress'] == 57) {
                    $details = "Submitted request product evaluation transaction";
                } elseif (isset($audit->new_values['Progress']) && $audit->new_values['Progress'] == 60) {
                    $details = "Completed request product evaluation transaction";
                } elseif (isset($audit->new_values['Progress']) && $audit->new_values['Progress'] == 70) {
                    $details = "Accepted request product evaluation transaction";
                } elseif (isset($audit->new_values['Progress']) && $audit->new_values['Progress'] == 81) {
                    $details = "Submitted request product evaluation transaction for Final Review";
                } elseif (isset($audit->new_values['Status']) && $audit->new_values['Status'] == 30) {
                    $details = "Closed request product evaluation transaction";
                } else {
                    $details = $audit->event . " " . 'Product Evaluation Request';
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
                'full_name' => optional($log->historyUser)->full_name,
                'Details' => $log->Details,
            ];
        });
    
        $mappedLogsCollection = collect($mappedLogs);
        $mappedAuditsCollection = collect($mappedAudits);
    
        $combinedLogs = $mappedLogsCollection->merge($mappedAuditsCollection);
        
        // $clients = Client::where('PrimaryAccountManagerId', auth()->user()->user_id)
        // ->orWhere('SecondaryAccountManagerId', auth()->user()->user_id)
        // ->get();
        // $users = User::all();
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
        $price_currencies = PriceCurrency::all();
        $project_names = ProjectName::all();

        $product_applications = ProductApplication::all();
        return view('product_evaluations.view', compact('requestEvaluation', 'rpeTransactionApprovals','rndPersonnel','activities', 'clients','users','rpeFileUploads', 'combinedLogs', 'project_names', 'price_currencies', 'product_applications','primarySalesPersons', 'secondarySalesPersons'));
    }

    public function addSupplementary(Request $request)
    {
        RpeDetail::create([
                'RequestProductEvaluationId' => $request->input('rpe_id'),
                'UserId' => auth()->user()->id,
                'DetailsOfRequest' => $request->input('details_of_request'),

            ]);

            Alert::success('Successfully Saved')->persistent('Dismiss');
            return back();
    }

    public function editSupplementary(Request $request, $id)
    {
        $rpeDetail = RpeDetail::findOrFail($id);
        $rpeDetail->DetailsOfRequest = $request->input('details_of_request');
        $rpeDetail->save();

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }

    public function deleteRpeDetails($id)
    {
        try { 
            $rpeDetail = RpeDetail::findOrFail($id); 
            $rpeDetail->delete();  
            return response()->json(['success' => true, 'message' => 'Supplementary Detail deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete supplementary detail.'], 500);
        }
    }

    public function assignPersonnel(Request $request)
    {
        RpePersonnel::create([
            'RequestProductEvaluationId' => $request->input('rpe_id'),
            'CreatedDate' => now(), 
            'PersonnelType' => 20,
            'PersonnelUserId' => $request->input('RndPersonnel'),
            ]);
        
        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back();
    }
    public function editPersonnel(Request $request, $id)
    {
        $rpePersonnel = RpePersonnel::findOrFail($id);
        $rpePersonnel->PersonnelUserId = $request->input('RndPersonnel');
        $rpePersonnel->save();

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }
    public function deleteSrfPersonnel($id)
    {
        try { 
            $rpePersonnel = RpePersonnel::findOrFail($id); 
            $rpePersonnel->delete();  
            return response()->json(['success' => true, 'message' => 'Assigned Personnel deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete Assigned Personnel.'], 500);
        }
    }


    public function deleteActivity($id)
    {
        try { 
            $activity = Activity::findOrFail($id); 
            $activity->delete();  
            return response()->json(['success' => true, 'message' => 'File deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete File.'], 500);
        }
    }
    public function uploadFile(Request $request)
    {
        $files = $request->file('rpe_file');
        $names = $request->input('name');
        $rpeId = $request->input('rpe_id');
        $isConfidential = $request->input('is_confidential') ? 1 : 0;
        $isForReview = $request->input('is_for_review') ? 1 : 0;
        
        if ($files) {
            foreach ($files as $index => $file) {
            $name = $names[$index];
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('public/rpeFiles', $fileName);
            $fileUrl = '/storage/rpeFiles/' . $fileName;       
            $uploadedFile = new RpeFile();
            $uploadedFile->RequestProductEvaluationId = $rpeId;
            $uploadedFile->Name = $name;
            $uploadedFile->Path = $fileUrl;
            $uploadedFile->IsConfidential = $isConfidential;
            $uploadedFile->IsForReview = $isForReview;
            $uploadedFile->userType = 'RND';
            $uploadedFile->save();
            }
        }
        
        return redirect()->back()->with('success', 'File(s) Stored successfully');
    }

    public function editFile(Request $request, $id)
    {
        $rpeFile = RpeFile::findOrFail($id);
        if ($request->has('name')) {
            $rpeFile->Name = $request->input('name');
        }
        if ($request->hasFile('rpe_file')) {
            $file = $request->file('rpe_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('public/rpeFiles', $fileName);
            $fileUrl = '/storage/rpeFiles/' . $fileName;

            $rpeFile->Path = $fileUrl;
        }

        if (authCheckIfItsRnd(auth()->user()->department_id) && !authCheckIfItsRndStaff(auth()->user()->role)) {
            $rpeFile->IsConfidential = $request->has('is_confidential') ? 1 : 0;
            $rpeFile->IsForReview = $request->has('is_for_review') ? 1 : 0;
        }

        $rpeFile->save();

        return redirect()->back()->with('success', 'File updated successfully');
    }
    public function deleteFile($id)
    {
        try { 
            $rpeFile = RpeFile::findOrFail($id); 
            $rpeFile->delete();  
            return response()->json(['success' => true, 'message' => 'Assigned Personnel deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete Assigned Personnel.'], 500);
        }
    }

    public function CancelRpe($id)
    {
        $cancelRpe = RequestProductEvaluation::find($id);    
        if ($cancelRpe) {
            $cancelRpe->Status = '50'; 
        }
            $cancelRpe->save();
            return back();
    }

    public function CloseRpe($id)
    {
        $rpeList = RequestProductEvaluation::find($id);    
        $rpeList->Status = 30; 
        $rpeList->save();
        
        Alert::success('Successfully Closed')->persistent('Dismiss');
        return back();
    }
    
    public function openRpe($id)
    {
        $rpeList = RequestProductEvaluation::find($id);    
        $rpeList->Status = 10; 
        $rpeList->Progress = 10; 
        $rpeList->save();
        
        Alert::success('Successfully Open')->persistent('Dismiss');
        return back();
    }

    public function acceptRpe($id)
    {
        $rpeList = RequestProductEvaluation::find($id);
        $rpeList->Progress = 30; 
        $rpeList->save();
        
        Alert::success('Successfully Approved')->persistent('Dismiss');
        return back();
    }

    public function receivedRpe($id)
    {
        $rpeList = RequestProductEvaluation::find($id);
        $rpeList->Progress = 35;
        $rpeList->DateReceived = date('Y-m-d'); 
        $rpeList->save();
        
        Alert::success('Successfully Received')->persistent('Dismiss');
        return back();
    }

    public function startRpe($id)
    {
        $rpeList = RequestProductEvaluation::find($id);
        $rpeList->Progress = 50;
        $rpeList->DateStarted = date('Y-m-d'); 
        $rpeList->save();
        
        Alert::success('Successfully Start')->persistent('Dismiss');
        return back();
    }

    public function pauseRpe($id)
    {
        $rpeList = RequestProductEvaluation::find($id);
        $rpeList->Progress = 55;
        $rpeList->save();
        
        Alert::success('Successfully Pause')->persistent('Dismiss');
        return back();
    }

    public function initialReview($id)
    {
        $rpeList = RequestProductEvaluation::find($id);
        $rpeList->Progress = 57;
        $rpeList->save();
        
        Alert::success('Successfully Initial Review')->persistent('Dismiss');
        return back();
    }

    public function finalReview($id)
    {
        $rpeList = RequestProductEvaluation::find($id);
        $rpeList->Progress = 81;
        $rpeList->save();
        
        Alert::success('Successfully Final Review')->persistent('Dismiss');
        return back();
    }

    public function completeRpe($id)
    {
        $rpeList = RequestProductEvaluation::find($id);
        $hasFilesForReview = $rpeList->rndRpeFiles()->where('IsForReview', 1)->exists();

        if ($hasFilesForReview) {
            Alert::error('Cannot complete request as there are files still under review.')->persistent('Dismiss');
            return back(); 
        }
        $rpeList->Progress = 60;
        $rpeList->DateCompleted = date('Y-m-d');
        $rpeList->save();
        
        Alert::success('Successfully Completed')->persistent('Dismiss');
        return back();
    }

    public function salesAcceptRpe($id)
    {
        $rpeList = RequestProductEvaluation::find($id);
        $rpeList->Progress = 70;
        $rpeList->save();
        
        Alert::success('Successfully Accepted')->persistent('Dismiss');
        return back();
    }

    public function ReturnToSalesRpe($id)
    {
        $rpeList = RequestProductEvaluation::findOrFail($id);
        $rpeList->Progress = 10;
        $rpeList->save(); 

        $transactionApproval = new TransactionApproval();
        $transactionApproval->Type = '20';
        $transactionApproval->TransactionId = $id;
        $transactionApproval->UserId = Auth::user()->id;
        $transactionApproval->Remarks = request()->input('return_to_sales_remarks');
        $transactionApproval->RemarksType = 'return to sales';
        $transactionApproval->save(); 
        Alert::success('Successfully return to sales')->persistent('Dismiss');
        return back();
    }

    public function approveRpeSales($id)
    {
        $approveRpeSales = RequestProductEvaluation::find($id);
        $approveRpeSales->sales_approved_date = Carbon::now();
        if ($approveRpeSales) {
            $buttonClicked = request()->input('submitbutton');    
            if ($buttonClicked === 'Approve to R&D') {
                $approveRpeSales->Progress = 30; 

                $transactionApproval = new TransactionApproval();
                $transactionApproval->Type = '20';
                $transactionApproval->TransactionId = $id;
                $transactionApproval->UserId = Auth::user()->id;
                $transactionApproval->Remarks = request()->input('Remarks');
                $transactionApproval->RemarksType = 'approved';
                
                $transactionApproval->save(); 
            } elseif ($buttonClicked === 'Approve to QCD') {
                $approveRpeSales->Progress = 80;
                $approveRpeSales->InternalRemarks = request()->input('submitbutton'); 
            }
            $approveRpeSales->save();

            Alert::success('Successfully Saved')->persistent('Dismiss');
            return back();
        } 
    }
    
    public function export(Request $request)
    {
        return Excel::download(new ProductEvaluationExport($request->open, $request->close), 'Request Product Evaluation.xlsx');
    }

    public function refreshUserApprover(Request $request)
    {
        $user = User::where('id', $request->ps)->orWhere('user_id', $request->ps)->first();
        if ($user != null)
        {
            if($user->salesApproverById)
            {
                $approvers = $user->salesApproverById->pluck('SalesApproverId')->toArray();
                $sales_approvers = User::whereIn('id', $approvers)->pluck('full_name', 'id')->toArray();

                return Form::select('SecondarySalesPersonId', $sales_approvers, null, array('class' => 'form-control'));
            }
            elseif($user->salesApproverByUserId)
            {
                $approvers = $user->salesApproverByUserId->pluck('SalesApproverId')->toArray();
                $sales_approvers = User::whereIn('user_id', $approvers)->pluck('full_name', 'user_id')->toArray();
                
                return Form::select('SecondarySalesPersonId', $sales_approvers, null, array('class' => 'form-control'));
            }
        }

        return "";
    }
    public function editsalesRpeFiles(Request $request, $id)
    {
        $attachments = $request->file('file');
        $name = time().'_'.$attachments->getClientOriginalName();
        $attachments->move(public_path('rpeFiles'), $name);
        $path = '/rpeFiles/'.$name;

        $files = RpeFile::findOrFail($id);
        $files->Name = $name;
        $files->Path = $path;
        if (auth()->user()->role->type == "IS" || auth()->user()->role->type == "LS")
        {
            $files->userType = "Sales";
        }

        $files->save();

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back()->with(['tab' => 'files']);
    }
}
<?php

namespace App\Http\Controllers;

use App\Activity;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Validator;
use App\CustomerRequirement;
use App\Client;
use App\CrrDetail;
use App\User;
use App\PriceCurrency;
use App\NatureRequest;
use App\CrrNature;
use App\CrrPersonnel;
use App\Exports\CustomerRequirementExport;
use App\FileCrr;
use App\ProductApplication;
use App\SalesApprovers;
use App\SalesUser;
use App\SecondarySalesPerson;
use App\TransactionApproval;
use App\TransactionLogs;
use App\UnitOfMeasure;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;
use Collective\Html\FormFacade as Form;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class CustomerRequirementController extends Controller
{
    // List
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->get('sort', 'id');
        $direction = $request->get('direction', 'desc');
        $role = auth()->user()->role;
        $status = $request->query('status'); // Get the status from the query parameters
        $progress = $request->query('progress'); // Get the status from the query parameters

        $userId = Auth::id(); 
        $userByUser = Auth::user()->user_id; 

        $crrRndOpen = CustomerRequirement::where('Status', '10')->count();
        // Fetch customer requirements with applied filters
        $customer_requirements = CustomerRequirement::with(['client', 'product_application', 'crr_personnels'])
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
                    if ($userType == 'RND' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
                        $query->where('Status', '10')
                              ->where('RefCode', 'RND');
                    } elseif ($userType == 'QCD-WHI' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
                        $query->where('Status', '10')
                              ->where('RefCode', 'QCD-WHI');
                    } elseif ($userType == 'QCD-PBI' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
                        $query->where('Status', '10')
                                ->where('RefCode', 'QCD-PBI');
                    } elseif ($userType == 'QCD-MRDC' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
                        $query->where('Status', '10')
                                ->where('RefCode', 'QCD-MRDC');
                    } elseif ($userType == 'QCD-CCC' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
                        $query->where('Status', '10')
                                ->where('RefCode', 'QCD-CCC');
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
                    // Apply other status filters if status is not '10'
                    $query->where('Status', $status);
                }
            })
            ->when($request->input('status'), function($query) use ($request, $userId, $userByUser) {
                $status = $request->input('status');
                $role = auth()->user()->role;
                $userType = $role->type;  
                $userName = $role->name; 
                
                if ($status == '30') {
                    if ($userType == 'RND' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
                        $query->where('Status', '30')
                              ->where('RefCode', 'RND');
                    } elseif ($userType == 'QCD-WHI' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
                        $query->where('Status', '30')
                              ->where('RefCode', 'QCD-WHI');
                    } elseif ($userType == 'QCD-PBI' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
                        $query->where('Status', '30')
                                ->where('RefCode', 'QCD-PBI');
                    } elseif ($userType == 'QCD-MRDC' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
                        $query->where('Status', '30')
                                ->where('RefCode', 'QCD-MRDC');
                    } elseif ($userType == 'QCD-CCC' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
                        $query->where('Status', '30')
                                ->where('RefCode', 'QCD-CCC');
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
                    $query->where('Status', $status);
                }
            })
            ->when($progress, function($query) use ($progress, $userId, $userByUser) {
                if ($progress == '10') {
                    // When filtering by '10', include all relevant progress status records
                    $query->where('Progress', '10')
                        ->where(function($query) use ($userId, $userByUser) {
                            $query->where('SecondarySalesPersonId', $userId)
                                ->orWhere('SecondarySalesPersonId', $userId)
                                ->orWhere('PrimarySalesPersonId', $userByUser)
                                ->orWhere('SecondarySalesPersonId', $userByUser);
                        });
                } else {
                    // Apply progress filter if it's not '10'
                    $query->where('Progress', $progress);
                }
            })
            ->when($progress, function($query) use ($progress, $userId, $userByUser) {
                if ($progress == '20') {
                    $query->where('Progress', '20')
                        ->where(function($query) use ($userId, $userByUser) {
                            $query->where('SecondarySalesPersonId', $userId)
                                ->orWhere('SecondarySalesPersonId', $userId)
                                ->orWhere('PrimarySalesPersonId', $userByUser)
                                ->orWhere('SecondarySalesPersonId', $userByUser);
                        });
                } else {
                    // Apply progress filter if it's not '10'
                    $query->where('Progress', $progress);
                }
            })
            ->when($request->input('DueDate') === 'past', function($query) {
                $role = auth()->user()->role;
                $userType = $role->type;  
                $userName = $role->name; 

                if ($userType == 'RND' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
                    $query->where('DueDate', '<', now())
                          ->where('Status', '10')
                          ->where('RefCode', 'RND');
                } elseif ($userType == 'QCD-WHI' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
                    $query->where('DueDate', '<', now())
                          ->where('Status', '10')
                          ->where('RefCode', 'QCD-WHI');
                } elseif ($userType == 'QCD-MRDC' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
                    $query->where('DueDate', '<', now())
                          ->where('Status', '10')
                          ->where('RefCode', 'QCD-MRDC');
                } elseif ($userType == 'QCD-PBI' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
                    $query->where('DueDate', '<', now())
                          ->where('Status', '10')
                          ->where('RefCode', 'QCD-PBI');
                } elseif ($userType == 'QCD-CCC' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
                    $query->where('DueDate', '<', now())
                          ->where('Status', '10')
                          ->where('RefCode', 'QCD-CCC');;
                } else {
                    $query->where('DueDate', '<', now())
                        ->where('Status', '10');  
                }                
            })
            ->when($request->has('open') && $request->has('close'), function($query) use ($request) {
                $query->whereIn('Status', [$request->open, $request->close]);
            })
            ->when($request->has('open') && !$request->has('close'), function($query) use ($request) {
                $query->where('Status', $request->open);
            })
            ->when($request->has('close') && !$request->has('open'), function($query) use ($request) {
                $query->where('Status', $request->close);
            })
            ->where(function ($query) use ($search){
                if ($search != null)
                {
                    $query->where('CrrNumber', 'LIKE', '%' . $search . '%')
                    ->orWhere('CreatedDate', 'LIKE', '%' . $search . '%')
                    ->orWhere('DueDate', 'LIKE', '%' . $search . '%')
                    ->orWhereHas('client', function ($q) use ($search) {
                        $q->where('name', 'LIKE', '%' . $search . '%');
                    })
                    ->orWhereHas('product_application', function ($q) use ($search) {
                        $q->where('name', 'LIKE', '%' . $search . '%');
                    })
                    ->orWhereHas('primarySales', function($query)use($search) {
                        $query->where('full_name', 'LIKE', '%'.$search.'%');
                    })
                    ->orWhereHas('primarySalesById', function($query)use($search) {
                        $query->where('full_name', 'LIKE', '%'.$search.'%');
                    })
                    ->orWhere('Recommendation', 'LIKE', '%' . $search . '%');
                }
            })
            ->when(optional($role)->type, function($q) use ($role) {
                if ($role->type == "IS") {
                    $q->where('CrrNumber', 'LIKE', "%CRR-IS%");
                } elseif ($role->type == "LS") {
                    $q->where('CrrNumber', 'LIKE', '%CRR-LS%');
                } elseif ($role->type == "RND") {
                    $q->where('RefCode', 'RND')
                        ->orWhere('RefCode', Null)
                      ->where(function($query) {
                            $query->where('CrrNumber', 'LIKE', '%CRR-LS%')
                                ->orWhere('CrrNumber', 'LIKE', '%CRR-IS%');
                      });
                } elseif ($role->type == "QCD-WHI") {
                    $q->where('RefCode', 'QCD-WHI')
                      ->where(function($query) {
                        $query->where('CrrNumber', 'LIKE', '%CRR-LS%')
                            ->orWhere('CrrNumber', 'LIKE', '%CRR-IS%');
                      });
                } elseif ($role->type == "QCD-PBI") {
                    $q->where('RefCode', 'QCD-PBI')
                      ->where(function($query) {
                            $query->where('CrrNumber', 'LIKE', '%CRR-LS%')
                                ->orWhere('CrrNumber', 'LIKE', '%CRR-IS%');;
                      });
                } elseif ($role->type == "QCD-MRDC") {
                    $q->where('RefCode', 'QCD-MRDC')
                      ->where(function($query) {
                            $query->where('CrrNumber', 'LIKE', '%CRR-LS%')
                                ->orWhere('CrrNumber', 'LIKE', '%CRR-IS%');
                      });
                } elseif ($role->type == "QCD-CCC") {
                    $q->where('RefCode', 'QCD-CCC')
                      ->where(function($query) {
                            $query->where('CrrNumber', 'LIKE', '%CRR-LS%')
                                ->orWhere('CrrNumber', 'LIKE', '%CRR-IS%');
                      });
                }  
            })
            
            ->when(in_array($progress, ['30', '57', '81']), function ($query) use ($progress) {
                $role = auth()->user()->role;
                $userType = $role->type;  
                $userName = $role->name; 
                
                if ($userType == 'RND' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
                    $query->where(function($q) {
                        $q->where('RefCode', 'RND')
                          ->orWhereNull('RefCode');
                    });
                    $query->where('Progress', $progress)
                          ->where('Status', '10');
                } elseif ($userType == 'QCD-WHI' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
                    $query->where('Progress', $progress)
                          ->where('Status', '10')
                          ->where('RefCode', 'QCD-WHI');
                } elseif ($userType == 'QCD-MRDC' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
                    $query->where('Progress', $progress)
                            ->where('Status', '10')
                            ->where('RefCode', 'QCD-MRDC');
                } elseif ($userType == 'QCD-PBI' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
                    $query->where('Progress', $progress)
                            ->where('Status', '10')
                            ->where('RefCode', 'QCD-PBI');
                } elseif ($userType == 'QCD-CCC' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
                    $query->where('Progress', $progress)
                            ->where('Status', '10')
                            ->where('RefCode', 'QCD-CCC');
                } else {
                    $query->where('Progress', $progress)
                      ->where('Status', '10');
                }
            })
            ->orderBy($sort, $direction)
            ->paginate($request->entries ?? 10);

        // Fetch related data for filters and dropdowns
        $product_applications = ProductApplication::all();
        $clients = Client::where(function($query) {
                if (auth()->user()->role->name == "Department Admin")
                {
                    if (auth()->user()->role->type == "LS")
                    {
                        $query->where('Type', 1);
                    }
                    else
                    {
                        $query->where('PrimaryAccountManagerId', auth()->user()->id)
                            ->orWhere('PrimaryAccountManagerId', auth()->user()->user_id)
                            ->orWhere('SecondaryAccountManagerId', auth()->user()->id)
                            ->orWhere('SecondaryAccountManagerId', auth()->user()->user_id);
                    }
                }
                if (auth()->user()->role->name == "Staff L2")
                {
                    if (auth()->user()->role->type == "LS")
                    {
                        $query->where('Type', 1);
                    }
                    else
                    {
                        $query->where('PrimaryAccountManagerId', auth()->user()->id)
                            ->orWhere('PrimaryAccountManagerId', auth()->user()->user_id)
                            ->orWhere('SecondaryAccountManagerId', auth()->user()->id)
                            ->orWhere('SecondaryAccountManagerId', auth()->user()->user_id);
                    }
                }
                if (auth()->user()->role->name == "Staff L1")
                {
                    if (auth()->user()->role->type == "LS")
                    {
                        $query->where('Type', 1);
                    }
                    else
                    {
                        $query->where('PrimaryAccountManagerId', auth()->user()->id)
                            ->orWhere('PrimaryAccountManagerId', auth()->user()->user_id)
                            ->orWhere('SecondaryAccountManagerId', auth()->user()->id)
                            ->orWhere('SecondaryAccountManagerId', auth()->user()->user_id);
                    }
                }
            })
            ->get();
        $users = User::where('is_active', 1)->get();
        $price_currencies = PriceCurrency::all();
        $nature_requests = NatureRequest::all();

        // Fetch request parameters for view
        $open = $request->open;
        $close = $request->close;
        $entries = $request->entries;
        $refCode = $this->refCode();
        $unitOfMeasure = UnitOfMeasure::get();

        // Return view with all necessary data
        return view('customer_requirements.index', compact('customer_requirements', 'clients', 'product_applications', 'users', 'price_currencies', 'nature_requests', 'search', 'open', 'close', 'entries', 'refCode', 'unitOfMeasure')); 
    }

    // Store
    public function store(Request $request)
    {
        $request->validate([
            'NatureOfRequestId' => 'required',
            'sales_upload_crr' => 'array',
            'sales_upload_crr.*' => 'max:1024'
        ], [
            'NatureOfRequestId.required' => 'The Nature of Request is required.',
            'sales_upload_crr.*.max' => 'The file size must not exceed 1MB.'
        ]);

        $user = Auth::user(); 
        $type = "";
        $year = date('y');
        if ($user->role->type == "IS")
        {
            $type = "IS";
            $crrList = CustomerRequirement::where('CrrNumber', 'LIKE', '%CRR-IS%')->orderBy('id', 'desc')->first();
            $count = substr($crrList->CrrNumber, 10);
            $totalCount = $count + 1;
            
            $crrNo = "CRR-".$type.'-'.$year.'-'.$totalCount;
        }

        if ($user->role->type == "LS")
        {
            $type = "LS";
            $crrList = CustomerRequirement::where('CrrNumber', 'LIKE', '%CRR-LS%')->orderBy('id', 'desc')->first();
            $count = substr($crrList->CrrNumber, 10);
            $totalCount = $count + 1;
            
            $crrNo = "CRR-".$type.'-'.$year.'-'.$totalCount;
        }

        // $user = Auth::user(); 
        // $type = "";
        // $year = date('y');
        // $crrList = CustomerRequirement::orderBy('id', 'desc')->first();
        // $count = substr($crrList->CrrNumber, 10);
        // $totalCount = $count + 1;

        // if (auth()->user()->role->type == "IS")
        // {
        //     $type = "IS";
        // }

        // if (auth()->user()->role->type == "LS")
        // {
        //     $type = "LS";
        // }

        // $crrNo = "CRR-".$type.'-'.$year.'-'.$totalCount;

        $customerRequirementData = CustomerRequirement::create([
            'CrrNumber' => $crrNo,
            // 'CreatedDate' => $request->input('CreatedDate'),
            'DueDate' => $request->input('DueDate'),
            'ClientId' => $request->input('ClientId'),
            'ApplicationId' => $request->input('ApplicationId'),
            'PotentialVolume' => $request->input('PotentialVolume'),
            'TargetPrice' => $request->input('TargetPrice'),
            'Competitor' => $request->input('Competitor'),
            'PrimarySalesPersonId' => $request->input('PrimarySalesPersonId'),
            'SecondarySalesPersonId' => $request->input('SecondarySalesPersonId'),
            'Priority' => $request->input('Priority'),
            'DetailsOfRequirement' => $request->input('DetailsOfRequirement'),
            'Status' =>'10',
            'UnitOfMeasureId' => $request->input('UnitOfMeasureId'),
            'CurrencyId' => $request->input('CurrencyId'),
            'Progress' => '10',
            'CompetitorPrice' => $request->input('CompetitorPrice'),
            'RefCrrNumber' => $request->input('RefCrrNumber'),
            'RefRpeNumber' => $request->input('RefRpeNumber'),
            'RefCode' => $request->RefCode
        ]);
        if($request->has('NatureOfRequestId'))
        {
            foreach ($request->input('NatureOfRequestId') as $natureOfRequestId) {
                            CrrNature::create([
                                'CustomerRequirementId' => $customerRequirementData->id,
                                'NatureOfRequestId' => $natureOfRequestId
                            ]);
                        }
        }

        if ($request->has('sales_upload_crr'))
        {
            $attachments = $request->file('sales_upload_crr');
            foreach($attachments as $attachment)
            {
                $name = time().'_'.$attachment->getClientOriginalName();
                $attachment->move(public_path('crr_files'), $name);
                $path = '/crr_files/'.$name;

                $crrFiles = new FileCrr;
                $crrFiles->Name = $name;
                $crrFiles->Path = $path;
                $crrFiles->CustomerRequirementId = $customerRequirementData['id'];

                if (auth()->user()->role->type == "IS" || auth()->user()->role->type == "LS")
                {
                    $crrFiles->UserType = auth()->user()->role->type;
                }
                if (auth()->user()->role->type == "RND")
                {
                    $crrFiles->UserType = auth()->user()->role->type;
                }
                
                $crrFiles->save();
            }
        }

        crrHistoryLogs("create", $customerRequirementData->id);
        
        Alert::success('Successfully Save')->persistent('Dismiss');
        return back();
                    // return redirect()->back()->with('success', 'Base prices updated successfully.');
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $request->validate([
            'NatureOfRequestId' => 'required',
            'sales_upload_crr' => 'array',
            'sales_upload_crr.*' => 'max:1024'
        ], [
            'NatureOfRequestId.required' => 'The Nature of Request is required.',
            'sales_upload_crr.*.max' => 'The file size must not exceed 1MB.'
        ]);

        $customerRequirements = CustomerRequirement::findOrFail($id);
        // $customerRequirements->DateCreated = date('Y-m-d');
        $customerRequirements->ClientId = $request->ClientId;
        $customerRequirements->Priority = $request->Priority;
        $customerRequirements->ApplicationId = $request->ApplicationId;
        $customerRequirements->DueDate = $request->DueDate;
        $customerRequirements->PotentialVolume = $request->PotentialVolume;
        $customerRequirements->UnitOfMeasureId = $request->UnitOfMeasureId;
        $customerRequirements->PrimarySalesPersonId = $request->PrimarySalesPersonId;
        $customerRequirements->TargetPrice = $request->TargetPrice;
        $customerRequirements->CurrencyId = $request->CurrencyId;
        $customerRequirements->SecondarySalesPersonId = $request->SecondarySalesPersonId;
        $customerRequirements->Competitor = $request->Competitor;
        $customerRequirements->CompetitorPrice = $request->CompetitorPrice;
        $customerRequirements->RefCrrNumber = $request->RefCrrNumber;
        $customerRequirements->RefRpeNumber = $request->RefRpeNumber;
        $customerRequirements->DetailsOfRequirement = $request->DetailsOfRequirement;
        // $customerRequirements->Status = $request->Status;
        $customerRequirements->RefCode = $request->RefCode;
        if($request->has('NatureOfRequestId'))
        {
            $crrNature = CrrNature::where('CustomerRequirementId', $id)->delete();
            foreach($request->NatureOfRequestId as $key=>$natureOfRequestId)
            {
                $crrNature = new CrrNature;
                $crrNature->CustomerRequirementId = $id;
                $crrNature->NatureOfRequestId = $natureOfRequestId;
                $crrNature->save();
            }
        }
        
        $customerRequirements->save();

        if ($request->has('sales_upload_crr'))
        {
            $attachments = $request->file('sales_upload_crr');
            foreach($attachments as $attachment)
            {
                $name = time().'_'.$attachment->getClientOriginalName();
                $attachment->move(public_path('crr_files'), $name);
                $path = '/crr_files/'.$name;

                $crrFiles = new FileCrr;
                $crrFiles->Name = $name;
                $crrFiles->Path = $path;
                $crrFiles->CustomerRequirementId = $id;
                
                if (auth()->user()->role->type == "IS" || auth()->user()->role->type == "LS")
                {
                    $crrFiles->UserType = auth()->user()->role->type;
                }
                if (auth()->user()->role->type == "RND")
                {
                    $crrFiles->UserType = auth()->user()->role->type;
                }
                
                $crrFiles->save();
            }
        }

        crrHistoryLogs("update", $id);

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }

    public function view($id, $crrNumber)
    {
        $customerRequirement = CustomerRequirement::with('client', 'product_application', 'progressStatus', 'crrNature', 'primarySales', 'secondarySales', 'priority', 'crrDetails')->findOrFail($id);
        $clients = Client::where(function($query) {
            if (auth()->user()->role->name == "Department Admin")
            {
                if (auth()->user()->role->type == "LS")
                {
                    $query->where('Type', 1);
                }
                else
                {
                    $query->where('PrimaryAccountManagerId', auth()->user()->id)
                        ->orWhere('PrimaryAccountManagerId', auth()->user()->user_id)
                        ->orWhere('SecondaryAccountManagerId', auth()->user()->id)
                        ->orWhere('SecondaryAccountManagerId', auth()->user()->user_id);
                }
            }
            if (auth()->user()->role->name == "Staff L2")
            {
                if (auth()->user()->role->type == "LS")
                {
                    $query->where('Type', 1);
                }
                else
                {
                    $query->where('PrimaryAccountManagerId', auth()->user()->id)
                        ->orWhere('PrimaryAccountManagerId', auth()->user()->user_id)
                        ->orWhere('SecondaryAccountManagerId', auth()->user()->id)
                        ->orWhere('SecondaryAccountManagerId', auth()->user()->user_id);
                }
            }
            if (auth()->user()->role->name == "Staff L1")
            {
                if (auth()->user()->role->type == "LS")
                {
                    $query->where('Type', 1);
                }
                else
                {
                    $query->where('PrimaryAccountManagerId', auth()->user()->id)
                        ->orWhere('PrimaryAccountManagerId', auth()->user()->user_id)
                        ->orWhere('SecondaryAccountManagerId', auth()->user()->id)
                        ->orWhere('SecondaryAccountManagerId', auth()->user()->user_id);
                }
            }
        })
        ->get();
        $user = User::where('is_active', 1)->get();
        $currentUser = Auth::user();
        $product_applications = ProductApplication::get();
        $price_currencies = PriceCurrency::all();
        $nature_requests = NatureRequest::all();
        $rnd_personnel = User::whereIn('department_id', [15, 42])->where('is_active', 1)->get();
        $refCode = $this->refCode();
        $unitOfMeasure = UnitOfMeasure::get();

        return view('customer_requirements.view_crr',
            array(
                'crr' => $customerRequirement,
                'clients' => $clients,
                'users' => $user,
                'currentUser' => $currentUser,
                'product_applications' => $product_applications,
                'price_currencies' => $price_currencies,
                'nature_requests' => $nature_requests,
                'rnd_personnel' => $rnd_personnel,
                'refCode' => $refCode,
                'unitOfMeasure' => $unitOfMeasure
            )
        );
    }

    public function addCrrFile(Request $request)
    {
        $request->validate([
            'crr_file' => 'mimes:pdf,docx,xlsx'
        ]);

        $crrFile = new FileCrr;
        $crrFile->Name = $request->file_name;
        $crrFile->CustomerRequirementId = $request->customer_requirements_id;
        if($request->has('is_confidential'))
        {
            $crrFile->IsConfidential = 1;
        }
        else
        {
            $crrFile->IsConfidential = 0;
        }

        if($request->has('is_for_review'))
        {
            $crrFile->IsConfidential = 1;
        }
        else
        {
            $crrFile->IsForReview = 0;
        }

        $attachment = $request->file('crr_file');
        $name = time().'_'.$attachment->getClientOriginalName();
        $attachment->move(public_path().'/crr_files/', $name);

        $file_name = '/crr_files/'.$name;
        $crrFile->Path = $file_name;
        $crrFile->save();

        crrHistoryLogs('add_files', $request->customer_requirements_id);

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back()->with(['tab' => 'files']);
    }

    
    public function multipleUploadFiles(Request $request)
    {
        $request->validate([
            'crr_file[]' => 'mimes:pdf,docx,xlsx'
        ]);

        $attachments = $request->file('crr_file');
        foreach($attachments as $key=>$attachment)
        {
            $name = time().'_'.$attachment->getClientOriginalName();
            $attachment->move(public_path('crr_files'), $name);
            $file_name = '/crr_files/'.$name;

            $fileCrr = new FileCrr;
            $fileCrr->CustomerRequirementId = $request->customer_requirement_id;
            $fileCrr->Name = $request->file_name[$key];
            if ($request->has('is_confidential'))
            {
                $fileCrr->IsConfidential = 1;
            }
            else
            {
                $fileCrr->IsConfidential = 0;
            }
            if ($request->has('is_for_review'))
            { 
                $fileCrr->IsForReview = 1;
            }
            else
            {
                $fileCrr->IsForReview = 0;
            }

            $fileCrr->Path = $file_name;
            $fileCrr->save();
        }

        crrHistoryLogs('update_files', $request->customer_requirements_id);

        Alert::success('Successfully Uploaded')->persistent('Dismiss');
        return back()->with(['tab' => 'files']);
    }

    public function updateCrrFile(Request $request, $id)
    {
        $crrFile = FileCrr::findOrFail($id);
        $crrFile->Name = $request->file_name;

        if($request->has('is_confidential'))
        {
            $crrFile->IsConfidential = 1;
        }
        else
        {
            $crrFile->IsConfidential = 0;
        }

        if($request->has('is_for_review'))
        {
            $crrFile->IsForReview = 1;
        }
        else
        {
            $crrFile->IsForReview = 0;
        }

        if($request->has('crr_file'))
        {
            $attachment = $request->file('crr_file');
            $name = time().'_'.$attachment->getClientOriginalName();
            $attachment->move(public_path().'/crr_files/', $name);
    
            $file_name = '/crr_files/'.$name;
            $crrFile->Path = $file_name;
        }

        $crrFile->save();

        crrHistoryLogs('update_files', $crrFile->CustomerRequirementId);

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back()->with(['tab' => 'files']);
    }

    public function deleteCrrFile($id)
    {
        $crrFile = FileCrr::findOrFail($id);
        $crrFile->delete();

        crrHistoryLogs('delete_files', $crrFile->CustomerRequirementId);

        Alert::success('Successfully Deleted')->persistent('Dismiss');
        return back()->with(['tab' => 'files']);
    }

    public function updateCrr(Request $request, $id)
    {
        $customerRequirement = CustomerRequirement::findOrFail($id);
        $customerRequirement->DdwNumber = $request->ddw_number;
        $customerRequirement->DateReceived = $request->date_received;
        $customerRequirement->DueDate = $request->due_date;
        $customerRequirement->Recommendation = $request->recommendation;
        // $customerRequirement->Status = $request->Status;
        // $customerRequirement->Progress = $request->progress;
        $customerRequirement->save();

        crrHistoryLogs('update', $id);

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }

    public function export(Request $request)
    {
        return Excel::download(new CustomerRequirementExport($request->open, $request->close), 'Customer Requirement.xlsx');
    }

    public function delete($id)
    {
        $customerRequirement = CustomerRequirement::findOrFail($id);
        $customerRequirement->delete();

        Alert::success('Successfully Deleted')->persistent('Dismiss');
        return back();
    }

    public function refCode()
    {
        return array(
            'RND' => 'R&D',
            'QCD-WHI' => 'QCD-WHI',
            'QCD-PBI' => 'QCD-PBI',
            'QCD-MRDC' => 'QCD-MRDC',
            'QCD-CCC' => 'QCD-CCC'
        );
    }

    public function closeRemarks(Request $request, $id)
    {
        $customerRequirement = CustomerRequirement::findOrFail($id);
        // $customerRequirement->CloseRemarks = $request->close_remarks;
        $customerRequirement->Status = 30;
        $customerRequirement->save();

        $transactionApproval = new TransactionApproval;
        $transactionApproval->Type = 10;
        $transactionApproval->TransactionId = $customerRequirement->id;
        $transactionApproval->UserId = auth()->user()->id;
        $transactionApproval->Status = 20;
        $transactionApproval->Remarks = $request->close_remarks;
        $transactionApproval->RemarksType = "closed";
        $transactionApproval->save();

        crrHistoryLogs('close', $id);

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back();
    }

    public function cancelRemarks(Request $request, $id)
    {
        $customerRequirement = CustomerRequirement::findOrFail($id);
        // $customerRequirement->CancelRemarks = $request->cancel_remarks;
        $customerRequirement->Status = 50;
        $customerRequirement->save();

        $transactionApproval = new TransactionApproval;
        $transactionApproval->Type = 10;
        $transactionApproval->TransactionId = $customerRequirement->id;
        $transactionApproval->UserId = auth()->user()->id;
        $transactionApproval->Status = 0;
        $transactionApproval->Remarks = $request->cancel_remarks;
        $transactionApproval->RemarksType = "cancelled";
        $transactionApproval->save();

        crrHistoryLogs('cancel', $id);

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back();
    }

    public function acceptCrr(Request $request, $id)
    {
        $crr = CustomerRequirement::findOrFail($id);

        if ($request->action == "approved_to_sales")
        {
            $crr->Progress = 20;
            // $crr->AcceptRemarks = $request->accept_remarks;
            $crr->ApprovedBy = auth()->user()->id;
            $crr->save();

            $transactionApproval = new TransactionApproval;
            $transactionApproval->Type = 10;
            $transactionApproval->TransactionId = $crr->id;
            $transactionApproval->UserId = auth()->user()->id;
            $transactionApproval->Status = 10;
            $transactionApproval->Remarks = $request->accept_remarks;
            $transactionApproval->RemarksType = "accept";
            $transactionApproval->save();
        }
        elseif($request->action == "approved_to_RND" || $request->action == "approved_to_QCD-MRDC" || $request->action == "approved_to_QCD-WHI" || $request->action == "approved_to_QCD-PBI")
        {
            $crr->Progress = 30;
            // $crr->AcceptRemarks = $request->accept_remarks;
            $crr->ApprovedBy = auth()->user()->id;
            $crr->SalesApprovedDate = date('Y-m-d');
            $crr->save();

            $transactionApproval = new TransactionApproval;
            $transactionApproval->Type = 10;
            $transactionApproval->TransactionId = $crr->id;
            $transactionApproval->UserId = auth()->user()->id;
            $transactionApproval->Status = 10;
            $transactionApproval->Remarks = $request->accept_remarks;
            $transactionApproval->RemarksType = "accept";
            $transactionApproval->save();
        }

        crrHistoryLogs('approve', $id);

        Alert::success('Successfully Approved')->persistent('Dismiss');
        return back();
    }

    public function openStatus(Request $request, $id)
    {
        $crr = CustomerRequirement::findOrFail($id);
        $crr->Status = 10;
        $crr->save();

        $transactionApproval = new TransactionApproval;
        $transactionApproval->Type = 10;
        $transactionApproval->TransactionId = $crr->id;
        $transactionApproval->UserId = auth()->user()->id;
        $transactionApproval->Status = 40;
        $transactionApproval->Remarks = $request->open_remarks;
        $transactionApproval->RemarksType = "open";
        $transactionApproval->save();

        crrHistoryLogs('open', $id);

        Alert::success('The status are now open')->persistent('Dismiss');
        return back();
    }

    public function rndReceived(Request $request, $id)
    {
        $crr = CustomerRequirement::findOrFail($id);
        $crr->Progress = 35;
        $crr->DateReceived = date('Y-m-d h:i:s');
        $crr->save();

        crrHistoryLogs('received', $id);

        Alert::success('Successfully received')->persistent('Dismiss');
        return back();
    }

    public function startCrr(Request $request, $id)
    {
        $crr = CustomerRequirement::findOrFail($id);
        $crr->Progress = 50;
        $crr->save();

        if ($request->has('action'))
        {
            if($request->action == "continue")
            {
                $transactionApproval = new TransactionApproval;
                $transactionApproval->Type = 10;
                $transactionApproval->TransactionId = $crr->id;
                $transactionApproval->UserId = auth()->user()->id;
                $transactionApproval->Status = 50;
                $transactionApproval->Remarks = $request->open_remarks;
                $transactionApproval->RemarksType = "continue";
                $transactionApproval->save();        
            }
        }

        crrHistoryLogs('start', $id);

        Alert::success('Successfully Start')->persistent('Dismiss');
        return back();
    }

    public function pauseCrr(Request $request, $id)
    {
        $crr = CustomerRequirement::findOrFail($id);
        $crr->Progress = 55;
        $crr->save();

        $transactionApproval = new TransactionApproval;
        $transactionApproval->Type = 10;
        $transactionApproval->TransactionId = $crr->id;
        $transactionApproval->UserId = auth()->user()->id;
        $transactionApproval->Status = 20;
        $transactionApproval->Remarks = $request->pause_remarks;
        $transactionApproval->RemarksType = "paused";
        $transactionApproval->save();

        crrHistoryLogs('pause', $id);

        Alert::success('Successfully Paused')->persistent('Dismiss');
        return back();
    }

    public function submitCrr(Request $request, $id)
    {
        $crr = CustomerRequirement::findOrFail($id);
        $crr->Progress = 57;
        $crr->save();

        crrHistoryLogs('submit_initial', $id);

        Alert::success('Successfully Submitted')->persistent('Dismiss');
        return back();
    }

    public function submitFinalCrr(Request $request, $id)
    {
        $crr = CustomerRequirement::findOrFail($id);
        $crr->Progress = 81;
        $crr->save();

        crrHistoryLogs('submit_final', $id);

        Alert::success('Successfully Final Review')->persistent('Dismiss');
        return back();
    }

    public function completeCrr(Request $request, $id)
    {
        $crr = CustomerRequirement::findOrFail($id);
        $files = $crr->crrFiles->every(function($value, $key) {
            return $value->IsForReview == 0;
        });
        
        if ($files)
        {
            $crr->Progress = 60;
            $crr->DateCompleted = date('Y-m-d h:i:s');
            $crr->save();

            crrHistoryLogs('complete', $id);
    
            Alert::success('Successfully Completed')->persistent('Dismiss');
        }
        else
        {
            Alert::error('Error! Some files are still in review');
        }

        return back();
    }

    public function addSupplementary(Request $request)
    {
        $crrDetails = new CrrDetail;
        $crrDetails->CustomerRequirementId = $request->customer_requirement_id;
        $crrDetails->UserId = $request->user_id;
        $crrDetails->DetailsOfRequirement = $request->details;
        $crrDetails->save();

        crrHistoryLogs('add_supplementary', $request->customer_requirement_id);

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back()->with(['tab' => 'supplementary_details']);
    }

    public function updateSupplementary(Request $request, $id)
    {
        $crrDetails = CrrDetail::findOrFail($id);
        $crrDetails->CustomerRequirementId = $request->customer_requirement_id;
        $crrDetails->UserId = $request->user_id;
        $crrDetails->DetailsOfRequirement = $request->details;
        $crrDetails->save();

        crrHistoryLogs('update_supplementary', $request->customer_requirement_id);

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back()->with(['tab' => 'supplementary_details']);
    }

    public function deleteSupplementary(Request $request, $id)
    {
        $crrDetails = CrrDetail::findOrFail($id);
        $crrDetails->delete();

        crrHistoryLogs('delete_supplementary', $crrDetails->CustomerRequirementId);

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back()->with(['tab' => 'supplementary_details']);
    }

    public function addPersonnel(Request $request)
    {
        $personnel = new CrrPersonnel;
        $personnel->CustomerRequirementId = $request->customer_requirement_id;
        $personnel->PersonnelUserId = $request->personnel;
        $personnel->save(); 

        crrHistoryLogs('add_personnel', $request->customer_requirement_id);

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back()->with(['tab' => 'personnel']);
    }
    
    public function updatePersonnel(Request $request, $id)
    {
        $personnel = CrrPersonnel::findOrFail($id);
        $personnel->CustomerRequirementId = $request->customer_requirement_id;
        $personnel->PersonnelUserId = $request->personnel;
        $personnel->save(); 

        crrHistoryLogs('update_personnel', $request->customer_requirement_id);

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back()->with(['tab' => 'personnel']);
    }

    public function deletePersonnel($id)
    {
        $personnel = CrrPersonnel::findOrFail($id);
        $personnel->delete();

        crrHistoryLogs('delete_personnel', $personnel->CustomerRequirementId);

        Alert::success('Successfully Deleted')->persistent('Dismiss');
        return back()->with(['tab' => 'personnel']);
    }
    
    public function refreshUserApprover(Request $request)
    {
        // dd($request->all());
        // $user = User::where('id', $request->ps)->orWhere('user_id', $request->ps)->first();
        // // dd($user);
        // if ($user != null)
        // {
        //     if($user->salesApproverById)
        //     {
        //         $approvers = $user->salesApproverById->pluck('SalesApproverId')->toArray();
        //         $sales_approvers = User::whereIn('id', $approvers)->pluck('full_name', 'id')->toArray();

        //         return Form::select('SecondarySalesPersonId', $sales_approvers, null, array('class' => 'form-control'));
        //     }
        //     elseif($user->salesApproverByUserId)
        //     {
        //         $approvers = $user->salesApproverByUserId->pluck('SalesApproverId')->toArray();
        //         $sales_approvers = User::whereIn('user_id', $approvers)->pluck('full_name', 'user_id')->toArray();
                
        //         return Form::select('SecondarySalesPersonId', $sales_approvers, null, array('class' => 'form-control'));
        //     }
        // }

        // return "";
        $secondary_sales_person = SecondarySalesPerson::where('PrimarySalesPersonId', $request->ps)->pluck('SecondarySalesPersonId')->toArray();
        $users = User::whereIn('id', $secondary_sales_person)->pluck('full_name', 'id');
        
        return Form::select('SecondarySalesPersonId', $users, null, array('class' => 'form-control'));
    }

    public function returnToSales(Request $request, $id)
    {
        $crr = CustomerRequirement::findOrFail($id);
        $crr->Progress = 10;
        $crr->save();

        $transactionApproval = new TransactionApproval;
        $transactionApproval->Type = 10;
        $transactionApproval->TransactionId = $crr->id;
        $transactionApproval->UserId = auth()->user()->id;
        $transactionApproval->Status = 30;
        $transactionApproval->Remarks = $request->return_to_sales_remarks;
        $transactionApproval->RemarksType = "returned";
        $transactionApproval->save();

        crrHistoryLogs('return_to_sales', $id);

        Alert::success('Successfully return to sales')->persistent('Dismiss');
        return back();
    }

    public function returnToRnd($id)
    {
        $crr = CustomerRequirement::findOrFail($id);
        $crr->Progress = 50;
        $crr->save(); 

        crrHistoryLogs('return_to_specialist', $id);

        Alert::success('Successfully return to rnd')->persistent('Dismiss');
        return back();
    }

    public function salesAccepted($id)
    {
        $crr = CustomerRequirement::findOrFail($id);
        $crr->Progress = 70;
        $crr->save(); 

        crrHistoryLogs('sales_accepted', $id);

        Alert::success('Sales Accepted')->persistent('Dismiss');
        return back();
    }

    public function printCrr($id)
    {
        $crr = CustomerRequirement::findOrFail($id);
        $data = [];
        $data['crr'] = $crr;

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('customer_requirements.crr_pdf', $data);

        return $pdf->stream();
    }

    public function updateSalesFiles(Request $request, $id)
    {
        $attachments = $request->file('file');
        $name = time().'_'.$attachments->getClientOriginalName();
        $attachments->move(public_path('crr_files'), $name);
        $path = '/crr_files/'.$name;

        $files = FileCrr::findOrFail($id);
        $files->Name = $name;
        $files->Path = $path;
        if (auth()->user()->role->type == "IS" || auth()->user()->role->type == "LS")
        {
            $files->UserType = auth()->user()->role->type;
        }

        $files->save();

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back()->with(['tab' => 'files']);
    }

    public function deleteSalesFiles(Request $request)
    {
        $crrFile = FileCrr::findOrFail($request->id);
        $crrFile->delete();
    }
    
}


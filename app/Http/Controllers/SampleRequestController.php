<?php

namespace App\Http\Controllers;

use App\Activity;
use App\RawMaterial;
use App\SampleRequest;
use App\Client;
use App\ConcernDepartment;
use App\Contact;
use App\Exports\SampleDispatchExport;
use App\Exports\SampleRequestExport;
use App\IssueCategory;
use App\Product;
use App\ProductApplication;
use App\RndUser;
use App\SalesApprovers;
use App\SampleRequestProduct;
use App\SecondarySalesPerson;
use App\SecondarySalesPersonId;
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
use Maatwebsite\Excel\Facades\Excel;
use OwenIt\Auditing\Models\Audit;
use PDF;
use RealRashid\SweetAlert\Facades\Alert;
use Collective\Html\FormFacade as Form;


class SampleRequestController extends Controller
{
    public function index(Request $request)
    {   
        $contacts = Contact::all();
        $categories = IssueCategory::all();
        $departments = ConcernDepartment::all(); 
        $productApplications = ProductApplication::all();
        $productCodes = Product::where('status', '4')->get();
        $return_to_sales = $request->query('return_to_sales');
        
        // $salesPersons = User::whereHas('salespersons')->get();
        $loggedInUser = Auth::user(); 
        $role = $loggedInUser->role;
        $userId = Auth::id(); 
        $userByUser = Auth::user()->user_id;

        $userDispatch = User::whereIn('department_id', [38, 5, 76])
                ->where('is_active', 1)
                ->get();
        
        $withRelation = $role->type == 'LS' ? 'localSalesApprovers' : 'internationalSalesApprovers';
        $salesApprovers = SalesApprovers::where('SalesApproverId', $loggedInUser->id)->pluck('UserId');

        // if ($role->name == 'Staff L2' ) {
        //     $primarySalesPersons = User::whereIn('id', $salesApprovers)->orWhere('id', $loggedInUser->id)->get();
        //     $secondarySalesPersons = User::whereIn('id',$loggedInUser->salesApproverById->pluck('SalesApproverId'))->orWhere('id', $loggedInUser->id)->get();
            
        // } else {
        //     $primarySalesPersons = User::with($withRelation)->where('id', $loggedInUser->id)->get();
        //     $secondarySalesPersons = User::whereIn('id', $loggedInUser->salesApproverById->pluck('SalesApproverId'))->get();
        // }
        $users = User::where('is_active', 1)->get();

        $search = $request->input('search');
        $sort = $request->get('sort', 'Id');
        $direction = $request->get('direction', 'desc');
        $entries = $request->entries;
        $open = $request->open;
        $close = $request->close;
        $status = $request->query('status'); // Get the status from the query parameters
        $progress = $request->query('progress'); // Get the status from the query parameters

        
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

        $refCodeMappings = [
            1 => 'RND',
            2 => 'QCD-WHI',
            3 => 'QCD-PBI',
            4 => 'QCD-MRDC',
            5 => 'QCD-CCC',
        ];
        $srfTypeMappings = [
            1 => 'Regular',
            2 => 'PSS',
            3 => 'CSS',
        ];

        $sampleRequests = SampleRequest::with(['requestProducts', 'salesSrfFiles', 'srf_personnel'])
            // Filter by status if provided
            ->when($status, function($query) use ($request, $userId, $userByUser) {
                $status = $request->input('status');
                $role = auth()->user()->role;
                $userType = $role->type;
                $userName = $role->name;

                // Status 50 with role filtering for RND L2 Staff
                if ($status == '50') {
                    if ($userType == 'RND' && $userName == 'Staff L2') {
                        $query->where('Status', '50');
                    } else {
                        // Default for other users on Status 50
                        $query->where('Status', '50')
                            ->where(function($query) use ($userId, $userByUser) {
                                $query->where(function($query) use ($userId, $userByUser) {
                                    $query->where('PrimarySalesPersonId', $userId)
                                            ->orWhere('SecondarySalesPersonId', $userId)
                                            ->orWhere('PrimarySalesPersonId', $userByUser)
                                            ->orWhere('SecondarySalesPersonId', $userByUser);
                                })->orWhereHas('srf_personnel', function($query) use ($userId, $userByUser) {
                                    $query->where('PersonnelUserId', $userId)
                                            ->orWhere('PersonnelUserId', $userByUser);
                                });
                            });
                    }
                } else {
                    // Other status conditions
                    $query->where('Status', $status);
                }
            })

            // Filter for status 10 (Open)
            ->when($request->input('status') == '10', function($query) use ($request, $userId, $userByUser) {
                $status = $request->input('status');
                $role = auth()->user()->role;
                $userType = $role->type;  
                $userName = $role->name;

                if ($status == '10') {
                    if ($userType == 'RND' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
                        $query->where('Status', '10')
                              ->where('RefCode', '1');
                    } elseif ($userType == 'QCD-WHI' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
                        $query->where('Status', '10')
                              ->where('RefCode', '2');
                    } elseif ($userType == 'QCD-PBI' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
                        $query->where('Status', '10')
                                ->where('RefCode', '3');
                    } elseif ($userType == 'QCD-MRDC' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
                        $query->where('Status', '10')
                                ->where('RefCode', '4');
                    } elseif ($userType == 'QCD-CCC' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
                        $query->where('Status', '10')
                                ->where('RefCode', '5');
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
                                // Check for related 'srf_personnel' entries
                                $query->orWhereHas('srf_personnel', function($query) use ($userId, $userByUser) {
                                    $query->where('PersonnelUserId', $userId)
                                        ->orWhere('PersonnelUserId', $userByUser);
                                });
                            });
                    }
                } else {
                    // Apply other status filters
                    $query->where('Status', $status);
                }
            })

            // Filter for status 30 (Custom logic for status 30)
            ->when($request->input('status') == '30', function($query) use ($request, $userId, $userByUser) {
                $status = $request->input('status');
                $role = auth()->user()->role;
                $userType = $role->type;  
                $userName = $role->name;

                if ($status == '30') {
                    if ($userType == 'RND' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
                        $query->where('Status', '30')
                              ->where('RefCode', '1');
                    } elseif ($userType == 'QCD-WHI' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
                        $query->where('Status', '30')
                              ->where('RefCode', '2');
                    } elseif ($userType == 'QCD-PBI' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
                        $query->where('Status', '30')
                                ->where('RefCode', '3');
                    } elseif ($userType == 'QCD-MRDC' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
                        $query->where('Status', '30')
                                ->where('RefCode', '4');
                    } elseif ($userType == 'QCD-CCC' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
                        $query->where('Status', '30')
                                ->where('RefCode', '5');
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
                                $query->orWhereHas('srf_personnel', function($query) use ($userId, $userByUser) {
                                    $query->where('PersonnelUserId', $userId)
                                        ->orWhere('PersonnelUserId', $userByUser);
                                });
                            });
                    }
                } else {
                    $query->where('Status', $status);
                }
            })

            // Filter by progress (Progress 10 logic)
            // ->when($progress == '10', function($query) use ($userId, $userByUser) {
            //     $query->where('Progress', '10')
            //         ->where(function($query) use ($userId, $userByUser) {
            //             $query->where('SecondarySalesPersonId', $userId)
            //                     ->orWhere('SecondarySalesPersonId', $userByUser);
            //         });
            // })
            // Search filter for SrfNumber, DateRequested, and DateRequired
            ->when($search, function($query) use ($search, $refCodeMappings, $srfTypeMappings) {
                $query->where('SrfNumber', 'LIKE', '%' . $search . '%')
                    ->orWhere('DateRequested', 'LIKE', '%' . $search . '%')
                    ->orWhere('DateRequired', 'LIKE', '%' . $search . '%')
                    ->orWhere('ProductDescription', 'LIKE', '%' . $search . '%')
                    ->orWhereHas('client', function($q) use ($search) {
                        $q->where('name', 'LIKE', '%' . $search . '%')
                        ->orWhereHas('clientregion', function($regionQuery) use ($search) {
                            $regionQuery->where('Name', 'LIKE', '%' . $search . '%');
                        })
                        ->orWhereHas('clientcountry', function($countryQuery) use ($search) {
                            $countryQuery->where('Name', 'LIKE', '%' . $search . '%');
                        });
                    })
                    ->orWhereHas('primarySalesPerson', function($salesQuery) use ($search) {
                        $salesQuery->where('full_name', 'LIKE', '%' . $search . '%');
                    })
                    ->orWhereHas('primarySalesById', function($salesQuery) use ($search) {
                        $salesQuery->where('full_name', 'LIKE', '%' . $search . '%');
                    })
                    ->orWhereHas('progressStatus', function($statusQuery) use ($search) {
                        $statusQuery->where('name', 'LIKE', '%' . $search . '%');
                    })
                    ->orWhere(function ($q) use ($search) {
                        $q->whereHas('requestProducts', function($productQuery) use ($search) {
                            $productQuery->where('ProductCode', 'LIKE', '%' . $search . '%');
                        })
                        ->orWhereHas('productApplicationsId', function($applicationQuery) use ($search) {
                            $applicationQuery->where('Name', 'LIKE', '%' . $search . '%');
                        });
                    })
                    ->orWhere(function ($q) use ($search, $refCodeMappings) {
                        foreach ($refCodeMappings as $code => $label) {
                            if (stripos($label, $search) !== false) {
                                $q->orWhere('RefCode', $code);
                            }
                        }
                    })
                    ->orWhere(function ($q) use ($search, $srfTypeMappings) {
                        foreach ($srfTypeMappings as $code => $label) {
                            if (stripos($label, $search) !== false) {
                                $q->orWhere('SrfType', $code);
                            }
                        }
                    });
            })
            ->when($progress, function($query) use ($progress, $userId) {
                if ($progress == '10') {
                    // Join users and filter by SalesApproverId
                    $query->join('users', function($join) {
                            $join->on('samplerequests.PrimarySalesPersonId', '=', 'users.user_id')
                                 ->orOn('samplerequests.PrimarySalesPersonId', '=', 'users.id');
                        })
                        ->join('salesapprovers', 'users.id', '=', 'salesapprovers.UserId')
                        ->where(function($query) use ($userId) {
                            // Ensure that SalesApproverId is filtered correctly
                            $query->where('salesapprovers.SalesApproverId', $userId)
                                  ->whereNotNull('salesapprovers.SalesApproverId'); // Only include records where SalesApproverId is not null
                        })
                        ->select('samplerequests.*');
                } else {
                    // Apply progress filter for other than '10'
                    $query->where('samplerequests.Progress', $progress);
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
            ->when($return_to_sales, function($query) use ($return_to_sales, $userId, $userByUser) {
                if ($return_to_sales == '1') {
                    $query->where('ReturnToSales', '1')
                        ->where(function($query) use ($userId, $userByUser) {
                            $query->where('PrimarySalesPersonId', $userId)
                                ->orWhere('PrimarySalesPersonId', $userByUser)
                                ->orWhere('SecondarySalesPersonId', $userByUser)
                                ->orWhere('SecondarySalesPersonId', $userId);
                        });
                } else {
                    $query->where('ReturnToSales', $return_to_sales);
                }
            })
            // Filter by past dates
            ->when($request->input('DateRequired') === 'past', function($query) {
                $role = auth()->user()->role;
                $userType = $role->type;  
                $userName = $role->name; 

                if ($userType == 'RND' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
                    $query->where('DateRequired', '<', now())
                          ->where('Status', '10')
                          ->where('RefCode', '1');
                } elseif ($userType == 'QCD-WHI' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
                    $query->where('DateRequired', '<', now())
                          ->where('Status', '10')
                          ->where('RefCode', '2');
                } elseif ($userType == 'QCD-MRDC' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
                    $query->where('DateRequired', '<', now())
                            ->where('Status', '10')
                            ->where('RefCode', '4');
                } elseif ($userType == 'QCD-PBI' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
                    $query->where('DateRequired', '<', now())
                            ->where('Status', '10')
                            ->where('RefCode', '3');
                } elseif ($userType == 'QCD-CCC' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
                    $query->where('DateRequired', '<', now())
                            ->where('Status', '10')
                            ->where('RefCode', '5');
                } else {
                    $query->where('DateRequired', '<', now())
                    ->where('Status', '10');
                }
            })
            
            // Open and Close status filters
            // ->when($open && $close, function($query) use ($open, $close) {
            //     $query->whereIn('Status', [$open, $close]);
            // })
            // ->when($open && !$close, function($query) use ($open) {
            //     $query->where('Status', $open);
            // })
            // ->when($close && !$open, function($query) use ($close) {
            //     $query->where('Status', $close);
            // })
            ->when($request->has('open') && $request->has('close'), function($query) use ($request) {
                $query->whereIn('Status', [$request->open, $request->close]);
            })
            ->when($request->has('open') && !$request->has('close'), function($query) use ($request) {
                $query->where('Status', $request->open);
            })
            ->when($request->has('close') && !$request->has('open'), function($query) use ($request) {
                $query->where('Status', $request->close);
            })
            

            // Role-based filters for SrfNumber patterns
            // ->when(auth()->user()->role->type == 'LS', function($query) {
            //     $query->where('SrfNumber', 'LIKE', '%SRF-LS%');
            // })
            // ->when(auth()->user()->role->type == 'IS', function($query) {
            //     $query->where('SrfNumber', 'LIKE', '%SRF-IS%');
            // })
            ->when(optional($role)->type, function($q) use ($role) {
                if ($role->type == "IS") {
                    $q->where('SrfNumber', 'LIKE', "%SRF-IS%");
                } elseif ($role->type == "LS") {
                    $q->where('SrfNumber', 'LIKE', '%SRF-LS%');
                } elseif ($role->type == "RND") {
                    $q->where('RefCode', '1')
                      ->where(function($query) {
                          $query->where('SrfNumber', 'LIKE', '%SRF-LS%')
                                ->orWhere('SrfNumber', 'LIKE', '%SRF-IS%');
                      });
                } elseif ($role->type == "QCD-WHI") {
                    $q->where('RefCode', '2')
                      ->where(function($query) {
                          $query->where('SrfNumber', 'LIKE', '%SRF-LS%')
                                ->orWhere('SrfNumber', 'LIKE', '%SRF-IS%');
                      });
                } elseif ($role->type == "QCD-PBI") {
                    $q->where('RefCode', '3')
                      ->where(function($query) {
                          $query->where('SrfNumber', 'LIKE', '%SRF-LS%')
                                ->orWhere('SrfNumber', 'LIKE', '%SRF-IS%');
                      });
                } elseif ($role->type == "QCD-MRDC") {
                    $q->where('RefCode', '4')
                      ->where(function($query) {
                          $query->where('SrfNumber', 'LIKE', '%SRF-LS%')
                                ->orWhere('SrfNumber', 'LIKE', '%SRF-IS%');
                      });
                } elseif ($role->type == "QCD-CCC") {
                    $q->where('RefCode', '5')
                      ->where(function($query) {
                          $query->where('SrfNumber', 'LIKE', '%SRF-LS%')
                                ->orWhere('SrfNumber', 'LIKE', '%SRF-IS%');
                      });
                }  
            })
            ->when(in_array($progress, ['30', '57', '81']), function ($query) use ($progress) {
                $role = auth()->user()->role;
                $userType = $role->type;
                $userName = $role->name;
            
                $refCodes = [
                    'RND' => '1',
                    'QCD-WHI' => '2',
                    'QCD-MRDC' => '4',
                    'QCD-PBI' => '3',
                    'QCD-CCC' => '5',
                ];
            
                if (in_array($userType, array_keys($refCodes)) && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
                    $query->where('RefCode', $refCodes[$userType])
                          ->where('Progress', $progress);
                }
            
                $query->where('Status', '10')
                      ->where('Progress', $progress);
            })
            // ->when(in_array($progress, ['30', '57', '81']), function ($query) use ($progress) {
            //     $role = auth()->user()->role;
            //     $userType = $role->type;  
            //     $userName = $role->name; 
                
            //     if ($userType == 'RND' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
            //         $query->where('Progress', $progress)
            //               ->where('Status', '10')
            //               ->where('RefCode', '1');
            //     } elseif ($userType == 'QCD-WHI' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
            //         $query->where('Progress', $progress)
            //               ->where('Status', '10')
            //               ->where('RefCode', '2');
            //     } elseif ($userType == 'QCD-MRDC' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
            //         $query->where('Progress', $progress)
            //                 ->where('Status', '10')
            //                 ->where('RefCode', '4');
            //     } elseif ($userType == 'QCD-PBI' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
            //         $query->where('Progress', $progress)
            //                 ->where('Status', '10')
            //                 ->where('RefCode', '3');
            //     } elseif ($userType == 'QCD-CCC' && ($userName == 'Staff L2' || $userName == 'Department Admin')) {
            //         $query->where('Progress', $progress)
            //                 ->where('Status', '10')
            //                 ->where('RefCode', '5');
            //     } else {
            //         $query->where('Progress', $progress)
            //           ->where('Status', '10');
            //     }
            // })
            // ->when($progress == '30', function($query) {
            //     $query->where('Progress', '30')->where('Status', '10');
            // })
            // ->when($progress == '57', function($query) {
            //     $query->where('Progress', '57')->where('Status', '10');
            // })
            // ->when($progress == '81', function($query) {
            //     $query->where('Progress', '81')->where('Status', '10');
            // })

            // Order by sort and direction
            ->orderBy($sort, $direction)

            // Paginate with entries per page
            ->paginate($request->entries ?? 10);

        
        // $openStatus = request('open');
        // $closeStatus = request('close');
        $products = SampleRequestProduct::whereHas('sampleRequest', function ($query) use ($search, $open, $close) {
            $query->where(function ($query) use ($search) {
                $query->where('SrfNumber', 'LIKE', '%' . $search . '%')
                      ->orWhere('DateRequested', 'LIKE', '%' . $search . '%')
                      ->orWhere('DateRequired', 'LIKE', '%' . $search . '%');
            })
            ->when($open || $close, function ($query) use ($open, $close) {
                if ($open) {
                    $query->orWhere('Status', $open);
                }
                if ($close) {
                    $query->orWhere('Status', $close);
                }
            });
        })
        
        ->whereHas('sampleRequest', function ($query) {
            $query->where('SrfNumber', 'LIKE', '%' . 'SRF-IS' . '%');
        }) 
        ->orderBy('id', 'desc')
        ->paginate($request->entries ?? 10);

        $rndSrf = SampleRequest::with('requestProducts') 
            ->where(function ($query) use ($search){
                $query->where('SrfNumber', 'LIKE', '%' . $search . '%')
                ->orWhere('DateRequested', 'LIKE', '%' . $search . '%')
                ->orWhere('DateRequired', 'LIKE', '%' . $search . '%');
            })
            ->when($progress == '57', function($query) {
                $query->where('Progress', '57')
                    ->where('Status', '10');
            })
            ->when($progress == '81', function($query) {
                $query->where('Progress', '81')
                    ->where('Status', '10');
            })
            ->when($progress == '30', function($query) {
                $query->where('Progress', '30')
                    ->where('Status', '10');
            })
            ->when($request->input('DateRequired') === 'past', function($query) {
                $query->where('DateRequired', '<', now())
                        ->where('Status', '10');  // Fetch only records with past due dates
            })
            ->when(optional($role)->type, function($q) use ($role) {
                if ($role->type == "IS") {
                    $q->where('SrfNumber', 'LIKE', "%SRF-IS%");
                } elseif ($role->type == "LS") {
                    $q->where('SrfNumber', 'LIKE', '%SRF-LS%');
                } elseif ($role->type == "RND") {
                    $q->where(function($query) {
                        $query->where('SrfNumber', 'LIKE', '%SRF-LS%')
                              ->orWhere('SrfNumber', 'LIKE', '%SRF-IS%');
                    });
                }  
            })
            ->orderBy($sort, $direction)
            ->paginate($request->entries ?? 10);
          
       
        return view('sample_requests.index', compact('products', 'sampleRequests', 'rndSrf', 'clients', 'contacts', 'categories', 'departments', 'productApplications', 'productCodes', 'search', 'entries', 'open','close', 'users', 'loggedInUser', 'userDispatch'));
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
        $sampleRequest = SampleRequest::with(['requestProducts', 'salesSrfFiles'])->findOrFail($id);
        $scrfNumber = $sampleRequest->Id;
        $SampletNumber = $sampleRequest->SrfNumber;

        $clientId = $sampleRequest->ClientId;
        $activities = Activity::where('TransactionNumber', $SampletNumber)->get();
        $SrfSupplementary = SrfDetail::where('SampleRequestId', $scrfNumber)->get();
        $assignedPersonnel = SrfPersonnel::where('SampleRequestId', $scrfNumber)->get();
        $SrfMaterials = SrfRawMaterial::where('SampleRequestId', $scrfNumber)->get();
        // $rndPersonnel = User::whereHas('rndUsers')->get();
        $rndPersonnel = User::whereIn('department_id', [15, 42, 20, 44, 77, 78, 79])->where('is_active', 1)->get();
        $srfProgress = SrfProgress::all();
        $srfFileUploads = SrfFile::where('SampleRequestId', $scrfNumber)
        ->where(function ($query) {
            $query->where('userType', 'RND')
                  ->orWhereNull('userType')
                  ->orWhere('userType', '');
        })->get();
        $loggedInUser = Auth::user(); 
        $role = $loggedInUser->role;
        $withRelation = $role->type == 'LS' ? 'localSalesApprovers' : 'internationalSalesApprovers';
        $salesApprovers = SalesApprovers::where('SalesApproverId', $loggedInUser->id)->pluck('UserId');

        if ($role->name == 'Staff L2' ) {
            $primarySalesPersons = User::whereIn('id', $salesApprovers)->orWhere('id', $loggedInUser->id)->where('is_active', 1)->get();
            $secondarySalesPersons = User::whereIn('id',$loggedInUser->salesApproverById->pluck('SalesApproverId'))->orWhere('id', $loggedInUser->id)->where('is_active', 1)->get();
            
        } else {
            $primarySalesPersons = User::with($withRelation)->where('id', $loggedInUser->id)->where('is_active', 1)->get();
            $secondarySalesPersons = User::whereIn('id', $loggedInUser->salesApproverById->where('is_active', 1)->pluck('SalesApproverId'))->get();
        }

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
        $productApplications = ProductApplication::all(); 
        $productCodes = Product::where('status', '4')->get();
        // $users = User::where('is_active', 1)->get();

        $users = User::where(function($query) {
                if (auth()->user()->role->type == "LS"){
                    $query->where('department_id', 38);
                } elseif (auth()->user()->role->type == "IS") {
                    $query->where('department_id', 5);
            }
        })
        ->get();

        $userDispatch = User::whereIn('department_id', [38, 5, 76])
                ->where('is_active', 1)
                ->get();

        $rawMaterials = RawMaterial::where('IsDeleted', '0')
        ->orWhere('deleted_at', '=', '')->get();
        $transactionApprovals = TransactionApproval::where('Type', '30')
        ->where('TransactionId', $scrfNumber)
        ->get();

        $transactionLogs = TransactionLogs::where('Type', '30')
        ->where('TransactionId', $scrfNumber)
        ->get();

        $mappedLogs = $transactionLogs->map(function ($log) {
            return (object) [
                'CreatedDate' => $log->CreatedDate ?? $log->created_at,
                'full_name' => optional($log->historyUser)->full_name 
                    ?? optional($log->user)->full_name,
                'Details' => $log->Details,
            ];
        });

        $audits = Audit::where('auditable_id', $scrfNumber)
        // ->where('auditable_type', 'App\SrfFile')
        ->where('created_at', '<', Carbon::create(2025, 1, 31))
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
                    if (
                        $audit->url == 'http://localhost/crmsMain/crms-v2/public/ReturnToSpecialistSRF/' . $audit->auditable_id . '?' || 
                        $audit->url == 'http://crms-wgroup.wsystem.online/ReturnToSpecialistSRF/' . $audit->auditable_id . '?'
                    ) {
                        $newValues = is_array($audit->new_values) ? $audit->new_values : json_decode($audit->new_values, true);
                        $remarks = $newValues['ReturnToSpecialistRemark'] ?? '';
                        $details = "Return To Specialist" ." ". $remarks;
                    } else {
                        $details = "Start sample request transaction";
                    }
                } elseif (isset($audit->new_values['ReturnToSales']) && $audit->new_values['ReturnToSales'] == 1) {
                    if (
                        $audit->url == 'http://localhost/crmsMain/crms-v2/public/ReturnToSalesSRF/' . $audit->auditable_id . '?' || 
                        $audit->url == 'http://crms-wgroup.wsystem.online/ReturnToSalesSRF/' . $audit->auditable_id . '?'
                    ) {
                        $newValues = is_array($audit->new_values) ? $audit->new_values : json_decode($audit->new_values, true);
                        $remarks = $newValues['ReturnToSalesRemark'] ?? '';
                        $details = "Return To Sales:" ." ". $remarks;
                    } 
                }elseif (isset($audit->new_values['Progress']) && $audit->new_values['Progress'] == 57) {
                    $details = "Submitted sample request transaction";
                } elseif (isset($audit->new_values['Progress']) && $audit->new_values['Progress'] == 60) {
                    $details = "Completed sample request transaction";
                } elseif (isset($audit->new_values['Progress']) && $audit->new_values['Progress'] == 70) {
                    $details = "Accepted sample request transaction";
                } elseif (isset($audit->new_values['Status']) && $audit->new_values['Status'] == 30) {
                    $details = "Closed sample request transaction";
                }else {
                    $details = $audit->event . " " . 'Sample Request';
                }
            }
            return (object) [
                'CreatedDate' => $audit->created_at,
                'full_name' => optional($audit->user)->full_name,
                'Details' => $details,
            ];
        });
        
        // $audits = Audit::where('auditable_id', $scrfNumber)
        // ->whereIn('auditable_type', [SampleRequest::class, SrfRawMaterial::class, SrfDetail::class, SrfPersonnel::class, SrfFile::class])
        // ->get();

        // $mappedAudits = $audits->map(function ($audit) {
        // $details = '';
        //     if ($audit->auditable_type === 'App\SrfRawMaterial') {
        //         $details = $audit->event . " " . 'SRF Raw Material';
        //     } elseif ($audit->auditable_type === 'App\SrfFile') {
        //         $details = $audit->event . " " . 'SRF Files';
        //     } elseif ($audit->auditable_type === 'App\SrfDetail') {
        //         $details = $audit->event . " " . 'SRF Supplementary';
        //     } elseif ($audit->auditable_type === 'App\SrfPersonnel') {
        //         $details = $audit->event . " " . 'SRF R&D Personnel';
        //     } elseif ($audit->auditable_type === 'App\SampleRequest') {
        //         if (isset($audit->new_values['Progress']) && $audit->new_values['Progress'] == 20) {
        //             $details = "Approve sample request entry";
        //         } elseif (isset($audit->new_values['Progress']) && ($audit->new_values['Progress'] == 30 || $audit->new_values['Progress'] == 80)) {
        //             $details = "Approve sample request entry";
        //         } elseif (isset($audit->new_values['Progress']) && $audit->new_values['Progress'] == 35) {
        //             $details = "Receive sample request entry";
        //         } elseif (isset($audit->new_values['Progress']) && $audit->new_values['Progress'] == 55) {
        //             $details = "Pause sample request transaction." . isset($audit->new_values['Remarks']);
        //         } elseif (isset($audit->new_values['Progress']) && $audit->new_values['Progress'] == 50) {
        //             if (
        //                 $audit->url == 'http://localhost/crmsMain/crms-v2/public/ReturnToSpecialistSRF/' . $audit->auditable_id . '?' || 
        //                 $audit->url == 'http://crms-wgroup.wsystem.online/ReturnToSpecialistSRF/' . $audit->auditable_id . '?'
        //             ) {
        //                 $newValues = is_array($audit->new_values) ? $audit->new_values : json_decode($audit->new_values, true);
        //                 $remarks = $newValues['ReturnToSpecialistRemark'] ?? '';
        //                 $details = "Return To Specialist" ." ". $remarks;
        //             } else {
        //                 $details = "Start sample request transaction";
        //             }
        //         } elseif (isset($audit->new_values['ReturnToSales']) && $audit->new_values['ReturnToSales'] == 1) {
        //             if (
        //                 $audit->url == 'http://localhost/crmsMain/crms-v2/public/ReturnToSalesSRF/' . $audit->auditable_id . '?' || 
        //                 $audit->url == 'http://crms-wgroup.wsystem.online/ReturnToSalesSRF/' . $audit->auditable_id . '?'
        //             ) {
        //                 $newValues = is_array($audit->new_values) ? $audit->new_values : json_decode($audit->new_values, true);
        //                 $remarks = $newValues['ReturnToSalesRemark'] ?? '';
        //                 $details = "Return To Sales:" ." ". $remarks;
        //             } 
        //         }elseif (isset($audit->new_values['Progress']) && $audit->new_values['Progress'] == 57) {
        //             $details = "Submitted sample request transaction";
        //         } elseif (isset($audit->new_values['Progress']) && $audit->new_values['Progress'] == 60) {
        //             $details = "Completed sample request transaction";
        //         } elseif (isset($audit->new_values['Progress']) && $audit->new_values['Progress'] == 70) {
        //             $details = "Accepted sample request transaction";
        //         } elseif (isset($audit->new_values['Status']) && $audit->new_values['Status'] == 30) {
        //             $details = "Closed sample request transaction";
        //         }else {
        //             $details = $audit->event . " " . 'Sample Request';
        //         }
        //     }
        //     return (object) [
        //         'CreatedDate' => $audit->created_at,
        //         'full_name' => $audit->user->full_name,
        //         'Details' => $details,
        //     ];
        // });
        
        $mappedLogsCollection = collect($mappedLogs);
        $mappedAuditsCollection = collect($mappedAudits);

        // $mappedLogs = $transactionLogs;

        
        $combinedLogs = $mappedLogsCollection->merge($mappedAuditsCollection);

        $orderedCombinedLogs = $combinedLogs->sortBy('CreatedDate')->values();
        return view('sample_requests.view', compact('sampleRequest', 'SrfSupplementary', 'rndPersonnel', 'assignedPersonnel', 'activities', 'srfFileUploads', 'rawMaterials', 'SrfMaterials', 'orderedCombinedLogs', 'srfProgress', 'clients', 'users', 'userDispatch', 'primarySalesPersons', 'secondarySalesPersons', 'productApplications', 'productCodes','transactionApprovals', 'loggedInUser'));
    }               

    // public function update(Request $request, $id)
    // {
    //     $srf = SampleRequest::with('requestProducts')->findOrFail($id);
    
    //     // $srf->DateRequested = $request->input('DateRequested');
    //     // $srf->DateRequested = Carbon::createFromFormat('m/d/Y', $request->input('DateRequested'))->format('Y-m-d');
    //     $srf->DateRequired = $request->input('DateRequired');
    //     $srf->DateStarted = $request->input('DateStarted');
    //     $srf->PrimarySalesPersonId = $request->input('PrimarySalesPersonId');
    //     $srf->SecondarySalesPersonId = $request->input('SecondarySalesPersonId');
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
            srfHistoryLogs('add_supplementary', $request->input('srf_id'));
            return back();
    }

    public function editSupplementary(Request $request, $id)
    {
        $srfDetail = SrfDetail::findOrFail($id);
        $srfDetail->DetailsOfRequest = $request->input('details_of_request');
        $srfDetail->save();
        srfHistoryLogs('update_supplementary', $srfDetail->id);
        return back();
    }

    public function deleteSrfDetails($id)
    {
        try { 
            $srfDetail = SrfDetail::findOrFail($id); 
            $srfDetail->delete();  
            srfHistoryLogs('delete_supplementary', $srfDetail->SampleRequestId);
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
            srfHistoryLogs('add_personnel', $request->input('srf_id'));
            return back();
    }
    public function editPersonnel(Request $request, $id)
    {
        $srfPersonnel = SrfPersonnel::findOrFail($id);
        $srfPersonnel->PersonnelUserId = $request->input('RndPersonnel');
        $srfPersonnel->save();
        srfHistoryLogs('update_personnel', $srfPersonnel->SampleRequestId);
        return back();
    }
    public function deleteSrfPersonnel($id)
    {
        try { 
            $srfPersonnel = SrfPersonnel::findOrFail($id); 
            $srfPersonnel->delete();  
            srfHistoryLogs('delete_personnel', $srfPersonnel->SampleRequestId);
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
        $isConfidential = $request->input('is_confidential') ? 1 : 0;
        $isForReview = $request->input('is_for_review') ? 1 : 0;
        
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
            $uploadedFile->IsConfidential = $isConfidential;
            $uploadedFile->IsForReview = $isForReview;
            $uploadedFile->userType = 'RND';
            $uploadedFile->save();
            }
        }
        srfHistoryLogs('add_files', $srfId);
        return redirect()->back()->with(['tab' => 'files']);
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

        if (authCheckIfItsRnd(auth()->user()->department_id) && !authCheckIfItsRndStaff(auth()->user()->role)) {
            $srfFile->IsConfidential = $request->has('is_confidential') ? 1 : 0;
            $srfFile->IsForReview = $request->has('is_for_review') ? 1 : 0;
        }
        $srfFile->save();
        srfHistoryLogs('update_files', $srfFile->SampleRequestId);
        return redirect()->back()->with(['tab' => 'files']);
    }
    public function deleteFile($id)
    {
        try { 
            $srfFile = SrfFile::findOrFail($id); 
            $srfFile->delete();  
            srfHistoryLogs('delete_files', $srfFile->SampleRequestId);
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
            srfHistoryLogs('add_raw_mats', $request->input('SampleRequestId'));
            return back();
    }

    public function editRawMaterial(Request $request, $id)
    {
        $srfRawMaterial = SrfRawMaterial::findOrFail($id);
        $srfRawMaterial->MaterialId = $request->input('RawMaterial');
        $srfRawMaterial->LotNumber = $request->input('LotNumber');
        $srfRawMaterial->Remarks = $request->input('Remarks');
        $srfRawMaterial->save();
        srfHistoryLogs('edit_raw_mats', $srfRawMaterial->SampleRequestId);
        return back();
    }

    public function deleteSrfMaterial($id)
    {
        try { 
            $srfMaterial = SrfRawMaterial::findOrFail($id); 
            $srfMaterial->delete();
            srfHistoryLogs('delete_raw_mats', $srfMaterial->SampleRequestId);
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
                ->where('SrfNumber', 'LIKE', "SRF-%$deptCode%")
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
                'PrimarySalesPersonId' => $request->input('PrimarySalesPersonId'),
                'SecondarySalesPersonId' => $request->input('SecondarySalesPersonId'),
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
                'Eta' => $request->input('Eta'),
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

            if ($request->has('SalesSrfFile'))
            {
                $attachments = $request->file('SalesSrfFile');
                foreach($attachments as $attachment)
                {
                    $name = time().'_'.$attachment->getClientOriginalName();
                    $attachment->move(public_path('srfFiles'), $name);
                    $path = '/srfFiles/'.$name;

                    $srfFiles = new SrfFile();
                    $srfFiles->Name = $name;
                    $srfFiles->Path = $path;
                    $srfFiles->SampleRequestId = $samplerequest['Id'];

                    if (auth()->user()->role->type == "IS" || auth()->user()->role->type == "LS")
                    {
                        $srfFiles->UserType = "Sales";
                    }
                    if (auth()->user()->role->type == "RND")
                    {
                        $srfFiles->UserType = "RND";
                    }
                    
                    $srfFiles->save();
                }
            }
            srfHistoryLogs("create", $samplerequest->Id);
            return redirect()->route('sample_request.index')->with('success', 'Sample Request created successfully.');
    }

    public function update(Request $request, $id)
    {
        $srf = SampleRequest::with('requestProducts')->findOrFail($id);
        $srf->DateRequired = $request->input('DateRequired');
        $srf->DateStarted = $request->input('DateStarted');
        $srf->PrimarySalesPersonId = $request->input('PrimarySalesPersonId');
        $srf->SecondarySalesPersonId = $request->input('SecondarySalesPersonId');
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
        $srf->Eta = $request->input('Eta');
        $srf->CourierCost = $request->input('CourierCost');
        $srf->Reason = $request->input('Reason');
        $srf->DispatchBy = $request->input('DispatchBy');
        $srf->ReturnToSales = 0;
        $srf->save();

        foreach ($request->input('ProductCode', []) as $key => $value) {
            $productId = $request->input('product_id.' . $key); 

            $srf->requestProducts()->updateOrCreate(
                ['id' => $productId],  
                [
                    'SampleRequestId' => $id, 
                    'ProductIndex' => $key + 1,
                    'ProductType' => $request->input('ProductType.' . $key),
                    'ApplicationId' => $request->input('ApplicationId.' . $key),
                    'ProductCode' =>  $value,
                    'ProductDescription' => $request->input('ProductDescription.' . $key),
                    'NumberOfPackages' => $request->input('NumberOfPackages.' . $key),
                    'Quantity' => $request->input('Quantity.' . $key),
                    'UnitOfMeasureId' => $request->input('UnitOfMeasure.' . $key),
                    'Label' => $request->input('Label.' . $key),
                    'RpeNumber' => $request->input('RpeNumber.' . $key),
                    'CrrNumber' => $request->input('CrrNumber.' . $key),
                    'Remarks' => $request->input('RemarksProduct.' . $key),
                    'Disposition' => $request->input('Disposition.' . $key),
                    'DispositionRejectionDescription' => $request->input('DispositionRejectionDescription.' . $key),
                ]
            );
        }

        if ($request->has('SalesSrfFile'))
            {
                $attachments = $request->file('SalesSrfFile');
                foreach($attachments as $attachment)
                {
                    $name = time().'_'.$attachment->getClientOriginalName();
                    $attachment->move(public_path('srfFiles'), $name);
                    $path = '/srfFiles/'.$name;

                    $srfFiles = new SrfFile();
                    $srfFiles->Name = $name;
                    $srfFiles->Path = $path;
                    $srfFiles->SampleRequestId = $id;
                    
                    if (auth()->user()->role->type == "IS" || auth()->user()->role->type == "LS")
                    {
                        $srfFiles->UserType = "Sales";
                    }
                    if (auth()->user()->role->type == "RND")
                    {
                        $srfFiles->UserType = "Rnd";
                    }
                    
                    $srfFiles->save();
                }
            }
        srfHistoryLogs("update", $id);
        return redirect()->back()->with('success', 'Sample Request updated successfully');
    }
    
    public function approveSrfSales($id)
    {
        $approveSrfSales = SampleRequest::find($id);
        $approveSrfSales->sales_approved_date = Carbon::now();
        if ($approveSrfSales) {
            $buttonClicked = request()->input('submitbutton');    
            if  ($buttonClicked === 'Approve_to_sales') {
                $approveSrfSales->Progress = 20; 
                $approveSrfSales->ReturnToSales = 0;
                $transactionApproval = new TransactionApproval();
                $transactionApproval->Type = '30';
                $transactionApproval->TransactionId = $id;
                $transactionApproval->UserId = Auth::user()->id;
                $transactionApproval->Remarks = request()->input('Remarks');
                $transactionApproval->RemarksType = 'approved';
                
                $transactionApproval->save(); 
                // $approveSrfSales->Progress = 80;
                // $approveSrfSales->InternalRemarks = request()->input('submitbutton'); 
            }elseif ($buttonClicked === 'Approve_to_1' || $buttonClicked === 'Approve_to_2' || $buttonClicked === 'Approve_to_3' || $buttonClicked === 'Approve_to_4' || $buttonClicked === 'Approve_to_5') {
                $approveSrfSales->Progress = 30;  
                $approveSrfSales->ReturnToSales = 0;
                $transactionApproval = new TransactionApproval();
                $transactionApproval->Type = '30';
                $transactionApproval->TransactionId = $id;
                $transactionApproval->UserId = Auth::user()->id;
                $transactionApproval->Remarks = request()->input('Remarks');
                $transactionApproval->RemarksType = 'approved';
                
                $transactionApproval->save(); 
            }
            $approveSrfSales->save();
            srfHistoryLogs('approve', $id);

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
            srfHistoryLogs('received', $id);
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
            srfHistoryLogs('start', $id);

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
            
            srfHistoryLogs('pause', $id);
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

        srfHistoryLogs('cancel', $id);
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
        srfHistoryLogs('close', $id);
        Alert::success('Successfully Closed')->persistent('Dismiss');
        return back();
    }
    public function ReturnToSalesSRF(Request $request, $id)
    {
        $sampleRequest = SampleRequest::findOrFail($id);
        // $sampleRequest->Progress = 10;
        $sampleRequest->ReturnToSales = 1;
        $sampleRequest->ReturnToSalesRemark = request()->input('return_to_sales_remarks');
        $sampleRequest->save();

        $transactionApproval = new TransactionApproval();
        $transactionApproval->Type = '30';
        $transactionApproval->TransactionId = $id;
        $transactionApproval->UserId = Auth::user()->id;
        $transactionApproval->Remarks = request()->input('return_to_sales_remarks');
        $transactionApproval->RemarksType = 'return to sales';
        $transactionApproval->save(); 
        srfHistoryLogs('return_to_sales', $id);
        Alert::success('Successfully Returned')->persistent('Dismiss');
        return back();
    }
    public function ReturnToSpecialistSRF(Request $request, $id)
    {
        $sampleRequest = SampleRequest::findOrFail($id);
        $sampleRequest->Progress = 50;
        $sampleRequest->ReturnToSpecialistRemark = request()->input('return_to_specialist_remarks');
        $sampleRequest->save();

        $transactionApproval = new TransactionApproval();
        $transactionApproval->Type = '30';
        $transactionApproval->TransactionId = $id;
        $transactionApproval->UserId = Auth::user()->id;
        $transactionApproval->Remarks = request()->input('return_to_specialist_remarks');
        $transactionApproval->RemarksType = 'return to specialist';
        $transactionApproval->save(); 
        srfHistoryLogs('return_to_specialist', $id);
        Alert::success('Successfully Returned')->persistent('Dismiss');
        return back();
    }
    public function ReturnToSales($id)
    {
        $sampleRequest = SampleRequest::findOrFail($id);
        $sampleRequest->Progress = 10;
        $sampleRequest->save(); 

        $transactionApproval = new TransactionApproval();
        $transactionApproval->Type = '30';
        $transactionApproval->TransactionId = $id;
        $transactionApproval->UserId = Auth::user()->id;
        $transactionApproval->Remarks = request()->input('return_to_sales_remarks');
        $transactionApproval->RemarksType = 'return to sales';
        $transactionApproval->save(); 

        srfHistoryLogs('return_to_sales', $id);
        Alert::success('Successfully return to sales')->persistent('Dismiss');
        return back();
    }

    public function returnToRnd($id)
    {
        $sampleRequest = SampleRequest::findOrFail($id);
        $sampleRequest->Progress = 50;
        $sampleRequest->save(); 

        $transactionApproval = new TransactionApproval();
        $transactionApproval->Type = '30';
        $transactionApproval->TransactionId = $id;
        $transactionApproval->UserId = Auth::user()->id;
        $transactionApproval->Remarks = request()->input('return_to_specialist_remarks');
        $transactionApproval->RemarksType = 'return to specialist';
        $transactionApproval->save(); 
        srfHistoryLogs('return_to_specialist', $id);
        Alert::success('Successfully return to rnd')->persistent('Dismiss');
        return back();
    }

    public function initialQuantity($id)
    {
        $sampleRequest = SampleRequest::findOrFail($id);
        $sampleRequest->Progress = 11;
        $sampleRequest->save(); 
        srfHistoryLogs('sales_initial_quantity', $id);

        Alert::success('Successfully return to rnd')->persistent('Dismiss');
        return back();
    }
    public function submitSrf(Request $request, $id)
    {
        $srf = SampleRequest::findOrFail($id);
        $srf->Progress = 57;
        $srf->save();

        srfHistoryLogs('submit_initial', $id);
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

        srfHistoryLogs('sales_accepted', $id);
        Alert::success('Sales Accepted')->persistent('Dismiss');
        return back();
    }

    public function OpenStatus(Request $request, $id)
    {
        $srf = SampleRequest::findOrFail($id);
        $srf->Status = 10;
        $srf->save();

        srfHistoryLogs('open', $id);
        Alert::success('The status are now open')->persistent('Dismiss');
        return back();
    }

    public function CompleteSrf(Request $request, $id)
    {
        $srf = SampleRequest::findOrFail($id);

        $hasFilesForReview = $srf->rndSrfFiles()->where('IsForReview', 1)->exists();

        if ($hasFilesForReview) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot complete request as there are files still under review.'
            ], 400);
        }

        $srf->Progress = 60;
        $srf->DateCompleted = date('Y-m-d h:i:s');
        $srf->save();

        srfHistoryLogs('complete', $id);
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully Completed'
        ], 200); 
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

    public function print_srf_2(Request $request, $id)
    {
        $srf = SampleRequest::findOrFail($id);

        View::share('sample_requests', $srf);
        $pdf = PDF::loadView('sample_requests.print2', [
            'sample_requests' => $srf,
        ])->setPaper('A4', 'portrait');
    
        return $pdf->stream('print.pdf');
    }

    public function print_dispatch(Request $request, $id)
    {
        $srf = SampleRequest::findOrFail($id);

        View::share('sample_requests', $srf);
        $pdf = PDF::loadView('sample_requests.print_dispatch', [
            'sample_requests' => $srf,
        ])->setPaper('A4', 'landscape');
    
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
    public function export(Request $request)
    {
        return Excel::download(new SampleRequestExport($request->open, $request->close,$request->srfType), 'Sample Request.xlsx');
    }

    public function cs_local(Request $request)
    {    
        $search = $request->input('search');
        $sort = $request->get('sort', 'Id');
        $direction = $request->get('direction', 'desc');
        $entries = $request->entries;
        $open = $request->open;
        $close = $request->close;
        $progress = $request->query('progress');

        $userId = Auth::id(); 
        $userByUser = Auth::user()->user_id; 

        $sampleRequests = SampleRequest::with(['requestProducts', 'salesSrfFiles']) 
            ->when($progress, function($query) use ($progress, $userId, $userByUser) {
                if ($progress == '10') {
                    $query->where('Progress', '10')
                        ->where(function($query) use ($userId, $userByUser) {
                            $query->where('PrimarySalesPersonId', $userId)
                                ->orWhere('SecondarySalesPersonId', $userId)
                                ->orWhere('PrimarySalesPersonId', $userByUser)
                                ->orWhere('SecondarySalesPersonId', $userByUser);
                        });
                } else {
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
                ->orWhere('DateRequired', 'LIKE', '%' . $search . '%')
                ->orWhereHas('client', function ($q) use ($search) {
                    $q->where('name', 'LIKE', '%' . $search . '%');
                });
            })
            ->where('SrfNumber', 'LIKE', '%' . 'SRF-LS' . '%')
            ->orderBy($sort, $direction)
            ->paginate($request->entries ?? 10);
        
        return view('customer_service.customer_service_local_srf', compact('sampleRequests','search','entries', 'open','close'));
    }

    public function csSrfUpdate(Request $request, $id)
    {
        // dd($request->all());
        $srf = SampleRequest::with('requestProducts')->findOrFail($id);
        $srf->Courier = $request->input('Courier');
        $srf->AwbNumber = $request->input('AwbNumber');
        $srf->DateDispatched = $request->input('DateDispatched');
        $srf->DateSampleReceived = $request->input('DateSampleReceived');
        $srf->DeliveryRemarks = $request->input('DeliveryRemarks');
        $srf->Note = $request->input('Note');
        $srf->Eta = $request->input('Eta');
        $srf->CourierCost = $request->input('CourierCost');
        $srf->Reason = $request->input('Reason');
        $srf->DispatchBy = auth()->user()->id;
        $srf->save();
        
        return redirect()->back()->with('success', 'Sample Request updated successfully');
    }
    public function cs_international(Request $request)
    {    
        $search = $request->input('search');
        $sort = $request->get('sort', 'Id');
        $direction = $request->get('direction', 'desc');
        $entries = $request->entries;
        $open = $request->open;
        $close = $request->close;
        $progress = $request->query('progress');

        $userId = Auth::id(); 
        $userByUser = Auth::user()->user_id; 

        $sampleRequests = SampleRequest::with(['requestProducts', 'salesSrfFiles']) 
            ->when($progress, function($query) use ($progress, $userId, $userByUser) {
                if ($progress == '10') {
                    $query->where('Progress', '10')
                        ->where(function($query) use ($userId, $userByUser) {
                            $query->where('PrimarySalesPersonId', $userId)
                                ->orWhere('SecondarySalesPersonId', $userId)
                                ->orWhere('PrimarySalesPersonId', $userByUser)
                                ->orWhere('SecondarySalesPersonId', $userByUser);
                        });
                } else {
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
                ->orWhere('DateRequired', 'LIKE', '%' . $search . '%')
                ->orWhereHas('client', function ($q) use ($search) {
                    $q->where('name', 'LIKE', '%' . $search . '%');
                });
            })
            ->where('SrfNumber', 'LIKE', '%' . 'SRF-IS' . '%')
            ->orderBy($sort, $direction)
            ->paginate($request->entries ?? 10);
        
        return view('customer_service.customer_service_international_srf', compact('sampleRequests','search','entries', 'open','close'));
    }
    public function refreshUserApprover(Request $request)
    {
        // $user = User::where('id', $request->ps)->orWhere('user_id', $request->ps)->first();
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
        $ps_id = $request->ps;
        if (!is_numeric($ps_id)) {
            $ps_id = User::where('user_id', $request->ps)->value('id');
        }

        $secondary_sales_person = SecondarySalesPerson::where('PrimarySalesPersonId', $ps_id)->pluck('SecondarySalesPersonId')->toArray();
        $users = User::whereIn('id', $secondary_sales_person)
                        ->pluck('full_name', 'id');
        
        return Form::select('SecondarySalesPersonId', $users, null, array('class' => 'form-control'));
    }
    public function editsalesSrfFiles(Request $request, $id)
    {
        $attachments = $request->file('file');
        $name = time().'_'.$attachments->getClientOriginalName();
        $attachments->move(public_path('srfFiles'), $name);
        $path = '/srfFiles/'.$name;

        $files = SrfFile::findOrFail($id);
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

    public function exportSampleDispatch(Request $request)
    {
        // dd($request->all());
        return Excel::download(new SampleDispatchExport($request->sample_request_no), 'sample_dispatch.xlsx');
    }
}    


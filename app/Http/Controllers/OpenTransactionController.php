<?php

namespace App\Http\Controllers;

use App\CustomerRequirement;
use App\PriceMonitoring;
use App\RequestProductEvaluation;
use App\SampleRequest;
use App\SampleRequestProduct;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class OpenTransactionController extends Controller
{
    public function crr(Request $request)
    {
        $status = $request->query('status');
        $search = $request->get('search', '');
        $entries = $request->get('entries', '');

        $customer_requirement = CustomerRequirement::with('client', 'product_application', 'progressStatus', 'crrPersonnel')
            ->where(function($q)use($search) {
                if ($search != null)
                {
                    $q->where('CrrNumber', 'LIKE','%'.$search.'%')
                        ->orWhere('DateCreated', 'LIKE','%'.$search.'%')
                        ->orWhere('DueDate', 'LIKE','%'.$search.'%')
                        ->orWhereHas('client', function($query)use($search){
                            $query->where('Name', 'LIKE', '%'.$search.'%');
                        })
                        ->orWhereHas('product_application', function($query)use($search){
                            $query->where('Name', 'LIKE', '%'.$search.'%');
                        })
                        ->orWhereHas('crrPersonnel', function($query)use($search){
                            $query->whereHas('crrPersonnelByUserId', function($q)use($search) {
                                $q->where('full_name', 'LIKE', "%".$search."%");
                            })
                            ->orWhereHas('crrPersonnelById', function($q)use($search) {
                                $q->where('full_name', 'LIKE', "%".$search."%");
                            });
                        })
                        ->orWhere('DateCreated', 'LIKE','%'.$search.'%')
                        ->orWhere('DateCreated', 'LIKE','%'.$search.'%');
                }
            })
            ->where('status', $status)
            ->orderBy('id', 'desc')
            ->paginate($entries ?? 10);

        return view('dashboard.crr_transaction',
            array(
                'customer_requirement' => $customer_requirement,
                'status' => $status,
                'search' => $search,
                'entries' => $entries
            )
        );
    }

    public function rpe(Request $request)
    {
        $status = $request->query('status');
        $search = $request->get('search', '');
        $entries = $request->get('entries', '');

        $request_product_evaluation = RequestProductEvaluation::with('client', 'product_application', 'progressStatus', 'rpePersonnel')
            ->where(function($q)use($search) {
                if ($search != null)
                {
                    $q->where('RpeNumber', 'LIKE','%'.$search.'%')
                        ->orWhere('DateCreated', 'LIKE','%'.$search.'%')
                        ->orWhere('DueDate', 'LIKE','%'.$search.'%')
                        ->orWhereHas('client', function($query)use($search){
                            $query->where('Name', 'LIKE', '%'.$search.'%');
                        })
                        ->orWhereHas('product_application', function($query)use($search){
                            $query->where('Name', 'LIKE', '%'.$search.'%');
                        })
                        ->orWhereHas('rpePersonnel', function($query)use($search){
                            $query->whereHas('assignedPersonnel', function($q)use($search) {
                                $q->where('full_name', 'LIKE', "%".$search."%");
                            })
                            ->orWhereHas('userId', function($q)use($search) {
                                $q->where('full_name', 'LIKE', "%".$search."%");
                            });
                        })
                        ->orWhere('DateCreated', 'LIKE','%'.$search.'%')
                        ->orWhere('DateCreated', 'LIKE','%'.$search.'%');
                }
            })
            ->where('status', $status)
            ->orderBy('id', 'desc')
            ->paginate($entries ?? 10);
        
        return view('dashboard.rpe_transaction',
            array(
                'request_product_evaluation' => $request_product_evaluation,
                'status' => $status,
                'search' => $search,
                'entries' => $entries
            )
        );
    }

    public function srf(Request $request)
    {
        $status = $request->query('status');
        $search = $request->get('search', '');
        $entries = $request->get('entries', '');
        
        $sample_request_product = SampleRequest::with('client', 'productApplicationsId', 'progressStatus', 'srfPersonnel')
            ->where(function($q)use($search) {
                if ($search != null)
                {
                    $q->where('SrfNumber', 'LIKE','%'.$search.'%')
                        ->orWhere('DateCreated', 'LIKE','%'.$search.'%')
                        ->orWhere('DueDate', 'LIKE','%'.$search.'%')
                        ->orWhereHas('client', function($query)use($search){
                            $query->where('Name', 'LIKE', '%'.$search.'%');
                        })
                        ->orWhereHas('productApplicationsId', function($query)use($search){
                            $query->where('Name', 'LIKE', '%'.$search.'%');
                        })
                        ->orWhereHas('srfPersonnel', function($query)use($search){
                            $query->whereHas('assignedPersonnel', function($q)use($search) {
                                $q->where('full_name', 'LIKE', "%".$search."%");
                            })
                            ->orWhereHas('userId', function($q)use($search) {
                                $q->where('full_name', 'LIKE', "%".$search."%");
                            });
                        })
                        ->orWhere('DateCreated', 'LIKE','%'.$search.'%')
                        ->orWhere('DateCreated', 'LIKE','%'.$search.'%');
                }
            })
            ->where('status', $status)
            ->orderBy('id', 'desc')
            ->paginate($entries ?? 10);
        
        return view('dashboard.srf_transaction',
            array(
                'sample_request_product' => $sample_request_product,
                'status' => $status,
                'search' => $search,
                'entries' => $entries
            )
        );
    }

    public function salesOpenTransaction(Request $request) 
    {
        $role = auth()->user()->role;
        $entries = $request->entries;
        $search = $request->search;
        $user = auth()->user();

        $crr = CustomerRequirement::where('Status', 10)
            ->where(function($q)use($user) {
                $q->where('PrimarySalesPersonId', $user->id)
                    ->orWhere('SecondarySalesPersonId', $user->id)
                    ->orWhere('PrimarySalesPersonId', $user->user_id)
                    ->orWhere('SecondarySalesPersonId', $user->user_id);
            })
            ->when($role, function($q)use($role) {
                if ($role->type == 'IS')
                {
                    $q->where('CrrNumber', 'LIKE', '%CRR-IS%');
                }
                elseif($role->type == 'LS')
                {
                    $q->where('CrrNumber', 'LIKE', '%CRR-LS%');
                }
            })
            ->when(!empty($search), function($q)use($search) {
                $q->where('CrrNumber', 'LIKE', '%'.$search.'%')
                    ->orWhereHas('client', function($clientQuery)use($search) {
                        $clientQuery->where('Name', 'LIKE', '%'.$search.'%');
                    })
                    ->orWhereHas('product_application',function($applicationQuery)use($search) {
                        $applicationQuery->where('Name', 'LIKE', '%'.$search.'%');
                    });
            })
            ->get();
        
        $rpe = RequestProductEvaluation::where('Status', 10)
            ->where(function($q)use($user) {
                $q->where('PrimarySalesPersonId', $user->id)
                    ->orWhere('SecondarySalesPersonId', $user->id)
                    ->orWhere('PrimarySalesPersonId', $user->user_id)
                    ->orWhere('SecondarySalesPersonId', $user->user_id);
            })
            ->when($role, function($q)use($role) {
                if ($role->type == 'IS')
                {
                    $q->where('RpeNumber', 'LIKE', '%RPE-IS%');
                }
                elseif($role->type == 'LS')
                {
                    $q->where('RpeNumber', 'LIKE', '%RPE-LS%');
                }
            })
            ->when(!empty($search), function($q)use($search) {
                $q->where('RpeNumber', 'LIKE', '%'.$search.'%')
                    ->orWhereHas('client', function($clientQuery)use($search) {
                        $clientQuery->where('Name', 'LIKE', '%'.$search.'%');
                    })
                    ->orWhereHas('product_application',function($applicationQuery)use($search) {
                        $applicationQuery->where('Name', 'LIKE', '%'.$search.'%');
                    });
            })
            ->get();

        $srf = SampleRequest::where('Status', 10)
            ->where(function($q)use($user) {
                $q->where('PrimarySalesPersonId', $user->id)
                    ->orWhere('SecondarySalesPersonId', $user->id)
                    ->orWhere('PrimarySalesPersonId', $user->user_id)
                    ->orWhere('SecondarySalesPersonId', $user->user_id);
            })
            ->when($role, function($q)use($role) {
                if ($role->type == 'IS')
                {
                    $q->where('SrfNumber', 'LIKE', '%SRF-IS%');
                }
                elseif($role->type == 'LS')
                {
                    $q->where('SrfNumber', 'LIKE', '%SRF-LS%');
                }
            })
            ->when(!empty($search), function($q)use($search) {
                $q->where('SrfNumber', 'LIKE', '%'.$search.'%')
                    ->orWhereHas('client', function($clientQuery)use($search) {
                        $clientQuery->where('Name', 'LIKE', '%'.$search.'%');
                    })
                    ->orWhereHas('productApplicationsId',function($applicationQuery)use($search) {
                        $applicationQuery->where('Name', 'LIKE', '%'.$search.'%');
                    });
            })
            ->get();

        
        $prf = PriceMonitoring::where('Status', 10)
            ->where(function($q)use($user) {
                $q->where('PrimarySalesPersonId', $user->id)
                    ->orWhere('SecondarySalesPersonId', $user->id)
                    ->orWhere('PrimarySalesPersonId', $user->user_id)
                    ->orWhere('SecondarySalesPersonId', $user->user_id);
            })
            ->when($role, function($q)use($role) {
                if ($role->type == 'IS')
                {
                    $q->whereRaw('1 = 0');
                }
                if($role->type == 'LS')
                {
                    $q->where('PrfNumber', 'LIKE', '%PRF-LS%');
                }
            })
            ->when(!empty($search), function($q)use($search) {
                $q->where('PrfNumber', 'LIKE', '%'.$search.'%')
                    ->orWhereHas('client', function($clientQuery)use($search) {
                        $clientQuery->where('Name', 'LIKE', '%'.$search.'%');
                    });
                    // ->orWhereHas('product_application',function($applicationQuery)use($search) {
                    //     $applicationQuery->where('Name', 'LIKE', '%'.$search.'%');
                    // });
            })
            ->get();

        $sortedResults = $crr
        ->concat($rpe)
        ->concat($srf)
        ->concat($prf);

        // $sortedResults = $allResults->sortByDesc('created_at');

        $page = request()->get('page', 1);
        $perPage = $entries ?? 10;
        $paginatedResults = new LengthAwarePaginator(
            $sortedResults->forPage($page, $perPage),
            $sortedResults->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        
        return view('dashboard.sales_open_transaction', compact('paginatedResults', 'entries', 'search'));
    }
}

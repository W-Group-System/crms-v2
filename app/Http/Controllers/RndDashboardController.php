<?php

namespace App\Http\Controllers;

use App\CustomerRequirement;
use App\RequestProductEvaluation;
use App\SampleRequest;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class RndDashboardController extends Controller
{
    public function initialReview(Request $request)
    {
        $search = $request->search;
        $entries = $request->entries;

        $crr = CustomerRequirement::where('Status', 10)
            ->where(function($q) {
                $q->where('RefCode', 'RND');
            })
            ->where('Progress', 57)
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
            ->where('Progress', 57)
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
            ->where(function($q) {
                $q->where('RefCode', 1);
            })
            ->where('Progress', 57)
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

        $sortedResults = $crr
        ->concat($rpe)
        ->concat($srf);

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

        return view('dashboard.initial_review',
            array(
                'paginatedResults' => $paginatedResults,
                'search' => $search,
                'entries' => $entries
            )
        );
    }

    public function finalReview(Request $request)
    {
        $search = $request->search;
        $entries = $request->entries;

        $crr = CustomerRequirement::where('Status', 10)
            ->where(function($q) {
                $q->where('RefCode', 'RND');
            })
            ->where('Progress', 81)
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
            ->where('Progress', 81)
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
            ->where(function($q) {
                $q->where('RefCode', 1);
            })
            ->where('Progress', 81)
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

        $sortedResults = $crr
        ->concat($rpe)
        ->concat($srf);

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

        return view('dashboard.final_review',
            array(
                'paginatedResults' => $paginatedResults,
                'search' => $search,
                'entries' => $entries
            )
        );
    }

    public function rndNewRequest(Request $request)
    {
        $search = $request->search;
        $entries = $request->entries;

        $crr = CustomerRequirement::where('Status', 10)
            ->where(function($q) {
                $q->where('RefCode', 'RND');
            })
            ->where('Progress', 30)
            ->where('ReturnToSales', 0)
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
            ->where('Progress', 30)
            ->where('ReturnToSales', 0)
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
            ->where(function($q) {
                $q->where('RefCode', 1);
            })
            ->where('Progress', 30)
            ->where('ReturnToSales', 0)
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

        $sortedResults = $crr
        ->concat($rpe)
        ->concat($srf);

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

        return view('dashboard.rnd_new_request',
            array(
                'paginatedResults' => $paginatedResults,
                'search' => $search,
                'entries' => $entries
            )
        );
    }
}

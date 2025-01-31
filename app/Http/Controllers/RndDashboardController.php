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
        $role = auth()->user()->role;

        $crr = CustomerRequirement::where('Status', 10)
            ->where('Progress', 57)
            ->where(function($q)use($search) {
                $q->where('CrrNumber', 'LIKE', '%'.$search.'%')
                    ->orWhereHas('client', function($clientQuery)use($search) {
                        $clientQuery->where('Name', 'LIKE', '%'.$search.'%');
                    })
                    ->orWhereHas('product_application',function($applicationQuery)use($search) {
                        $applicationQuery->where('Name', 'LIKE', '%'.$search.'%');
                    });
            })
            ->when($role, function($q)use($role) {
                if ($role->type == 'RND')
                {
                    $q->where('RefCode', 'RND');
                }
                elseif($role->type == 'QCD-WHI')
                {
                    $q->where('RefCode', 'QCD-WHI');
                }
                elseif($role->type == 'QCD-PBI')
                {
                    $q->where('RefCode', 'QCD-PBI');
                }
            })
            ->get();

        $rpe = collect([]);
        if ($role->type == 'RND')
        {
            $rpe = RequestProductEvaluation::where('Status', 10)
                ->where('Progress', 57)
                ->where(function($q)use($search) {
                    $q->where('RpeNumber', 'LIKE', '%'.$search.'%')
                        ->orWhereHas('client', function($clientQuery)use($search) {
                            $clientQuery->where('Name', 'LIKE', '%'.$search.'%');
                        })
                        ->orWhereHas('product_application',function($applicationQuery)use($search) {
                            $applicationQuery->where('Name', 'LIKE', '%'.$search.'%');
                        });
                })
                ->get();
        }

        $srf = SampleRequest::where('Status', 10)
            ->where('Progress', 57)
            ->where(function($q)use($search) {
                $q->where('SrfNumber', 'LIKE', '%'.$search.'%')
                    ->orWhereHas('client', function($clientQuery)use($search) {
                        $clientQuery->where('Name', 'LIKE', '%'.$search.'%');
                    })
                    ->orWhereHas('productApplicationsId',function($applicationQuery)use($search) {
                        $applicationQuery->where('Name', 'LIKE', '%'.$search.'%');
                    });
            })
            ->when($role, function($q)use($role) {
                if ($role->type == 'RND')
                {
                    $q->where('RefCode', 1);
                }
                elseif($role->type == 'QCD-WHI')
                {
                    $q->where('RefCode', 2);
                }
                elseif($role->type == 'QCD-PBI')
                {
                    $q->where('RefCode', 3);
                }
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
        $role = auth()->user()->role;

        $crr = CustomerRequirement::where('Status', 10)
            ->where('Progress', 81)
            ->where(function($q)use($search) {
                $q->where('CrrNumber', 'LIKE', '%'.$search.'%')
                    ->orWhereHas('client', function($clientQuery)use($search) {
                        $clientQuery->where('Name', 'LIKE', '%'.$search.'%');
                    })
                    ->orWhereHas('product_application',function($applicationQuery)use($search) {
                        $applicationQuery->where('Name', 'LIKE', '%'.$search.'%');
                    });
            })
            ->when($role, function($q)use($role) {
                if ($role->type == 'RND')
                {
                    $q->where('RefCode', 'RND');
                }
                elseif($role->type == 'QCD-WHI')
                {
                    $q->where('RefCode', 'QCD-WHI');
                }
                elseif($role->type == 'QCD-PBI')
                {
                    $q->where('RefCode', 'QCD-PBI');
                }
            })
            ->get();
        
        $rpe = collect([]);
        if ($role->type == 'RND')
        {
            $rpe = RequestProductEvaluation::where('Status', 10)
                ->where('Progress', 81)
                ->where(function($q)use($search) {
                    $q->where('RpeNumber', 'LIKE', '%'.$search.'%')
                        ->orWhereHas('client', function($clientQuery)use($search) {
                            $clientQuery->where('Name', 'LIKE', '%'.$search.'%');
                        })
                        ->orWhereHas('product_application',function($applicationQuery)use($search) {
                            $applicationQuery->where('Name', 'LIKE', '%'.$search.'%');
                        });
                })
                ->get();
        }

        $srf = SampleRequest::where('Status', 10)
            ->where('Progress', 81)
            ->where(function($q)use($search) {
                $q->where('SrfNumber', 'LIKE', '%'.$search.'%')
                    ->orWhereHas('client', function($clientQuery)use($search) {
                        $clientQuery->where('Name', 'LIKE', '%'.$search.'%');
                    })
                    ->orWhereHas('productApplicationsId',function($applicationQuery)use($search) {
                        $applicationQuery->where('Name', 'LIKE', '%'.$search.'%');
                    });
            })
            ->when($role, function($q)use($role) {
                if ($role->type == 'RND')
                {
                    $q->where('RefCode', 1);
                }
                elseif($role->type == 'QCD-WHI')
                {
                    $q->where('RefCode', 2);
                }
                elseif($role->type == 'QCD-PBI')
                {
                    $q->where('RefCode', 3);
                }
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
        $role = auth()->user()->role;

        $crr = CustomerRequirement::where('Status', 10)
            ->where('Progress', 30)
            ->where('ReturnToSales', 0)
            ->where(function($q)use($search) {
                $q->where('CrrNumber', 'LIKE', '%'.$search.'%')
                    ->orWhereHas('client', function($clientQuery)use($search) {
                        $clientQuery->where('Name', 'LIKE', '%'.$search.'%');
                    })
                    ->orWhereHas('product_application',function($applicationQuery)use($search) {
                        $applicationQuery->where('Name', 'LIKE', '%'.$search.'%');
                    });
            })
            ->when($role, function($q)use($role) {
                if ($role->type == 'RND')
                {
                    $q->where('RefCode', 'RND');
                }
                elseif($role->type == 'QCD-WHI')
                {
                    $q->where('RefCode', 'QCD-WHI');
                }
                elseif($role->type == 'QCD-PBI')
                {
                    $q->where('RefCode', 'QCD-PBI');
                }
            })
            ->get();
        
        $rpe =  collect([]);
        if ($role->type == 'RND')
        {
            $rpe = RequestProductEvaluation::where('Status', 10)
                ->where('Progress', 30)
                ->where('ReturnToSales', 0)
                ->where(function($q)use($search) {
                    $q->where('RpeNumber', 'LIKE', '%'.$search.'%')
                        ->orWhereHas('client', function($clientQuery)use($search) {
                            $clientQuery->where('Name', 'LIKE', '%'.$search.'%');
                        })
                        ->orWhereHas('product_application',function($applicationQuery)use($search) {
                            $applicationQuery->where('Name', 'LIKE', '%'.$search.'%');
                        });
                })
                ->get();
        }

        $srf = SampleRequest::where('Status', 10)
            ->where('Progress', 30)
            ->where('ReturnToSales', 0)
            ->where(function($q)use($search) {
                $q->where('SrfNumber', 'LIKE', '%'.$search.'%')
                    ->orWhereHas('client', function($clientQuery)use($search) {
                        $clientQuery->where('Name', 'LIKE', '%'.$search.'%');
                    })
                    ->orWhereHas('productApplicationsId',function($applicationQuery)use($search) {
                        $applicationQuery->where('Name', 'LIKE', '%'.$search.'%');
                    });
            })
            ->when($role, function($q)use($role) {
                if ($role->type == 'RND')
                {
                    $q->where('RefCode', 1);
                }
                elseif($role->type == 'QCD-WHI')
                {
                    $q->where('RefCode', 2);
                }
                elseif($role->type == 'QCD-PBI')
                {
                    $q->where('RefCode', 3);
                }
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

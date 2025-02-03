<?php

namespace App\Http\Controllers;

use App\CustomerRequirement;
use App\Exports\RndCloseTransactionExport;
use App\Exports\RndOpenTransactionExport;
use App\RequestProductEvaluation;
use App\SampleRequest;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Maatwebsite\Excel\Facades\Excel;

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
                    $q->where('RefCode', 'RND')->orWhereNull('RefCode');
                }
                elseif($role->type == 'QCD-WHI')
                {
                    $q->where('RefCode', 'QCD-WHI');
                }
                elseif($role->type == 'QCD-PBI')
                {
                    $q->where('RefCode', 'QCD-PBI');
                }
                elseif($role->type == 'QCD-MRDC')
                {
                    $q->where('RefCode', 'QCD-MRDC');
                }
                elseif($role->type == 'QCD-CCC')
                {
                    $q->where('RefCode', 'QCD-CCC');
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
                elseif($role->type == 'QCD-MRDC')
                {
                    $q->where('RefCode', 4);
                }
                elseif($role->type == 'QCD-CCC')
                {
                    $q->where('RefCode', 5);
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
                    $q->where('RefCode', 'RND')->orWhereNull('RefCode');
                }
                elseif($role->type == 'QCD-WHI')
                {
                    $q->where('RefCode', 'QCD-WHI');
                }
                elseif($role->type == 'QCD-PBI')
                {
                    $q->where('RefCode', 'QCD-PBI');
                }
                elseif($role->type == 'QCD-MRDC')
                {
                    $q->where('RefCode', 'QCD-MRDC');
                }
                elseif($role->type == 'QCD-CCC')
                {
                    $q->where('RefCode', 'QCD-CCC');
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
                elseif($role->type == 'QCD-MRDC')
                {
                    $q->where('RefCode', 4);
                }
                elseif($role->type == 'QCD-CCC')
                {
                    $q->where('RefCode', 5);
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
                    $q->where('RefCode', 'RND')->orWhereNull('RefCode');
                }
                elseif($role->type == 'QCD-WHI')
                {
                    $q->where('RefCode', 'QCD-WHI');
                }
                elseif($role->type == 'QCD-PBI')
                {
                    $q->where('RefCode', 'QCD-PBI');
                }
                elseif($role->type == 'QCD-MRDC')
                {
                    $q->where('RefCode', 'QCD-MRDC');
                }
                elseif($role->type == 'QCD-CCC')
                {
                    $q->where('RefCode', 'QCD-CCC');
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
                elseif($role->type == 'QCD-MRDC')
                {
                    $q->where('RefCode', 4);
                }
                elseif($role->type == 'QCD-CCC')
                {
                    $q->where('RefCode', 5);
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

    public function closeTransaction(Request $request)
    {
        $search = $request->search;
        $entries = $request->entries;

        $role = auth()->user()->role;

        $crr = CustomerRequirement::where('Status', 30)
            ->where( function($q)use($search) {
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
                    $q->where(function($q) {
                        $q->where('RefCode', 'RND')->orWhereNull('RefCode');
                    });
                }
                elseif($role->type == 'QCD-WHI')
                {
                    $q->where('RefCode', 'QCD-WHI');
                }
                elseif($role->type == 'QCD-PBI')
                {
                    $q->where('RefCode', 'QCD-PBI');
                }
                elseif($role->type == 'QCD-MRDC')
                {
                    $q->where('RefCode', 'QCD-MRDC');
                }
                elseif($role->type == 'QCD-CCC')
                {
                    $q->where('RefCode', 'QCD-CCC');
                }
            })
            ->when(($role->type == 'RND' || $role->type == 'QCD-WHI' || $role->type == 'QCD-PBI' || $role->type == 'QCD-MRDC') && $role->name == 'Staff L1', function($q) {
                $q->whereHas('crr_personnels', function($q) {
                    $q->where('PersonnelUserId',  auth()->user()->user_id)->orWhere('PersonnelUserId', auth()->user()->id);
                });
            })
            ->orderBy('id','desc')
            ->get();
        // dd($crr->count());
        $rpe = collect([]);
        if ($role->type == 'RND')
        {
            $rpe = RequestProductEvaluation::where('Status', 30)
                ->where(function($q)use($search) {
                    $q->where('RpeNumber', 'LIKE', '%'.$search.'%')
                        ->orWhereHas('client', function($clientQuery)use($search) {
                            $clientQuery->where('Name', 'LIKE', '%'.$search.'%');
                        })
                        ->orWhereHas('product_application',function($applicationQuery)use($search) {
                            $applicationQuery->where('Name', 'LIKE', '%'.$search.'%');
                        });
                })
                ->when($role->type == 'RND' && $role->name == 'Staff L1', function($q) {
                    $q->whereHas('rpe_personnels', function($q) {
                        $q->where('PersonnelUserId',  auth()->user()->user_id)->orWhere('PersonnelUserId', auth()->user()->id);
                    });
                })
                ->orderBy('id','desc')
                ->get();
        }

        $srf = SampleRequest::where('Status', 30)
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
                elseif($role->type == 'QCD-MRDC')
                {
                    $q->where('RefCode', 4);
                }
                elseif($role->type == 'QCD-CCC')
                {
                    $q->where('RefCode', 5);
                }
            })
            ->when(($role->type == 'RND' || $role->type == 'QCD-WHI' || $role->type == 'QCD-PBI' || $role->type == 'QCD-MRDC') && $role->name == 'Staff L1', function($q) {
                $q->whereHas('srf_personnel', function($q) {
                    $q->where('PersonnelUserId',  auth()->user()->user_id)->orWhere('PersonnelUserId', auth()->user()->id);
                });
            })
            ->orderBy('id','desc')
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

        return view('dashboard.rnd_close_transaction', compact('search', 'entries', 'paginatedResults'));
    }

    public function exportOpenTransaction(Request $request)
    {
        return Excel::download(new RndOpenTransactionExport, 'export_open_transaction.xlsx');
    }
    public function exportCloseTransaction(Request $request)
    {
        return Excel::download(new RndCloseTransactionExport, 'export_close_transaction.xlsx');
    }
}

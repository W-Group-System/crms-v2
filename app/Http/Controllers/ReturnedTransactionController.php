<?php

namespace App\Http\Controllers;

use App\CustomerRequirement;
use App\PriceMonitoring;
use App\RequestProductEvaluation;
use App\SampleRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ReturnedTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $entries = $request->entries;
        $search = $request->search;

        $crr = CustomerRequirement::where('ReturnToSales', 1)
            ->where(function($q)use($user) {
                $q->where('PrimarySalesPersonId', $user->user_id)
                    ->orWhere('SecondarySalesPersonId', $user->user_id)
                    ->orWhere('PrimarySalesPersonId', $user->id)
                    ->orWhere('SecondarySalesPersonId', $user->id);
            })
            ->when($search, function($searchQuery)use($search) {
                $searchQuery->where(function($q) use ($search) {
                    $q->where('CrrNumber', 'LIKE', '%' . $search . '%')
                        ->orWhereHas('client', function($clientQuery) use ($search) {
                            $clientQuery->where('Name', 'LIKE', '%' . $search . '%');
                        });
                });
            })
            ->get();

        $rpe = RequestProductEvaluation::where('ReturnToSales', 1)
            ->where(function($q)use($user) {
                $q->where('PrimarySalesPersonId', $user->user_id)
                    ->orWhere('SecondarySalesPersonId', $user->user_id)
                    ->orWhere('PrimarySalesPersonId', $user->id)
                    ->orWhere('SecondarySalesPersonId', $user->id);
            })
            ->when($search, function($searchQuery)use($search) {
                $searchQuery->where(function($q) use ($search) {
                    $q->where('RpeNumber', 'LIKE', '%' . $search . '%')
                        ->orWhereHas('client', function($clientQuery) use ($search) {
                            $clientQuery->where('Name', 'LIKE', '%' . $search . '%');
                        });
                });
            })
            ->get();

        $srf = SampleRequest::where('ReturnToSales', 1)
            ->where(function($q)use($user) {
                $q->where('PrimarySalesPersonId', $user->user_id)
                    ->orWhere('SecondarySalesPersonId', $user->user_id)
                    ->orWhere('PrimarySalesPersonId', $user->id)
                    ->orWhere('SecondarySalesPersonId', $user->id);
            })
            ->when($search, function($searchQuery)use($search) {
                $searchQuery->where(function($q) use ($search) {
                    $q->where('SrfNumber', 'LIKE', '%' . $search . '%')
                        ->orWhereHas('client', function($clientQuery) use ($search) {
                            $clientQuery->where('Name', 'LIKE', '%' . $search . '%');
                        });
                });
            })
            ->get();

        $transactions = $crr->concat($rpe)->concat($srf);

        $page = request()->get('page', 1);
        $perPage = $entries ?? 10;
        $paginatedResults = new LengthAwarePaginator(
            $transactions->forPage($page, $perPage),
            $transactions->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('dashboard.returned_transaction', compact('paginatedResults','entries','search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

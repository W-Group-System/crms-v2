<?php

namespace App\Http\Controllers;

use App\CustomerComplaint2;
use App\CustomerRequirement;
use App\CustomerSatisfaction;
use App\PriceMonitoring;
use App\RequestProductEvaluation;
use App\SampleRequest;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use stdClass;

class ForApprovalTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userId = auth()->user()->id;
        
        $customerRequirement = CustomerRequirement::where('Progress', 10)
            ->where('Status', 10)
            ->where(function ($query) use ($userId) {
                $query->whereHas('salesapprovers', function ($q) use ($userId) {
                    $q->where('SalesApproverId', $userId);
                })->orWhereHas('salesapproverByUserId', function ($q) use ($userId) {
                    $q->where('SalesApproverId', $userId);
                });
            })
            ->get();

        $requestProductEvaluation = RequestProductEvaluation::where('Progress', 10)
            ->where('Status', 10)
            ->whereHas('salesapprovers', function ($q) use ($userId) {
                $q->where('SalesApproverId', $userId);
            })
            ->get();

        $sampleRequestForm = SampleRequest::with('requestProducts')
            ->where('Progress', 10)
            ->where('Status', 10)
            ->whereHas('salesapprovers', function ($q) use ($userId) {
                $q->where('SalesApproverId', $userId);
            })
            ->get();
        if (auth()->user()->role->description == "Manager") {
            $priceRequestForm = PriceMonitoring::whereIn('Progress', [10,40])
            ->where(function ($query) use ($userId) {
                $query->whereHas('salesapprovers', function ($q) use ($userId) {
                    $q->where('SalesApproverId', $userId);
                })->orWhereHas('salesapproverByUserId', function ($q) use ($userId) {
                    $q->where('SalesApproverId', $userId);
                })->orWhereHas('products', function ($q) {
                    $q->where('LsalesMarkupPercent', '<', 15);
                });
            })
            ->where('Status', '10') 
            ->get();
        } else {
            $priceRequestForm = PriceMonitoring::whereIn('Progress', [10, 40])
            ->where(function ($query) use ($userId) {
                $query->whereHas('salesapprovers', function ($q) use ($userId) {
                    $q->where('SalesApproverId', $userId);
                })->orWhereHas('salesapproverByUserId', function ($q) use ($userId) {
                    $q->where('SalesApproverId', $userId);
                });
            })
            ->get();
        }
    

        $csForm = CustomerSatisfaction::whereIn('Progress', [20, 30])
            ->where('Status', 10)
            ->where(function ($query) use ($userId) {
                $query->whereHas('salesapprovers', function ($q) use ($userId) {
                    $q->where('SalesApproverId', $userId);
                });
            })
            ->get();

            $ccForm = CustomerComplaint2::whereIn('Progress', [20, 30])
            ->where('Status', 10)
            ->where(function ($query) use ($userId) {
                // If userId is 1, show all records
                if ($userId == 15) {
                    return;
                }
        
                $query->whereHas('salesapprovers', function ($q) use ($userId) {
                    $q->where('SalesApproverId', $userId);
                });
            })
            ->when($userId == 15, function ($query) { 
                $query->whereNotNull('NotedBy');
            })
            ->get();

        $forApprovalTransactionsArray = (object) [
            'crr' => $customerRequirement,
            'rpe' => $requestProductEvaluation,
            'srf' => $sampleRequestForm,
            'prf' => $priceRequestForm,
            'cs'  => $csForm,
            'cc'  => $ccForm,
        ];

        return view('dashboard.view_for_approval_transactions', compact('forApprovalTransactionsArray'));
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

<?php

namespace App\Http\Controllers;

use App\CustomerRequirement;
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
        $customerRequirement = CustomerRequirement::where('Progress',10)
            ->whereHas('salesapprovers', function($q) {
                $q->where('SalesApproverId', auth()->user()->id);
            })
            ->where('Status',10)
            ->get();
        
        $requestProductEvaluation = RequestProductEvaluation::where('Progress', 10)
            ->whereHas('salesapprovers', function($q) {
                $q->where('SalesApproverId', auth()->user()->id);
            })
            ->where('Status',10)
            ->get();

        $sampleRequestForm = SampleRequest::with('requestProducts')
            ->where('Progress',10)
            ->whereHas('salesapprovers', function($q) {
                $q->where('SalesApproverId', auth()->user()->id);
            })
            ->where('Status',10)
            ->get();
        
        $priceRequestForm = PriceMonitoring::whereIn('Progress',[10, 40])
            ->whereHas('salesapprovers', function($q) {
                $q->where('SalesApproverId', auth()->user()->id);
            })
            ->orWhereHas('salesapproverByUserId', function($q) {
                $q->where('SalesApproverId', auth()->user()->user_id);
            })
            ->get();
        
        $forApprovalTransactionsArray = array();
        $obj = new stdClass;
        $obj->crr = $customerRequirement;
        $obj->rpe = $requestProductEvaluation;
        $obj->srf = $sampleRequestForm;
        $obj->prf = $priceRequestForm;

        $forApprovalTransactionsArray = $obj;
        
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

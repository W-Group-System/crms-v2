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

        $sampleRequestForm = SampleRequest::where('Progress', 10)
            ->where('Status', 10)
            ->where(function ($query) use ($userId) {
                $query->whereHas('salesapprovers', function ($q) use ($userId) {
                    $q->where('SalesApproverId', $userId);
                })->orWhereHas('salesapproverByUserId', function ($q) use ($userId) {
                    $q->where('SalesApproverId', $userId);
                });
            })
            ->get();

        // $sampleRequestForm = SampleRequest::with('requestProducts')
        //     ->where('Progress', 10)
        //     ->where('Status', 10)
        //     ->whereHas('salesapprovers', function ($q) use ($userId) {
        //         $q->where('SalesApproverId', $userId);
        //     })
        //     ->get();
        if (auth()->user()->role->description == "Manager") {
            $priceRequestForm = PriceMonitoring::whereIn('Progress', [10,40])
            ->where(function ($query) use ($userId) {
                $query->whereHas('salesapprovers', function ($q) use ($userId) {
                    $q->where('SalesApproverId', $userId);
                })->orWhereHas('salesapproverByUserId', function ($q) use ($userId) {
                    $q->where('SalesApproverId', $userId);
                })->orWhereHas('products', function ($q) {
                    $q->where('LsalesMarkupPercent', '<', 15);
                })->orWhereHas('products', function ($q) {
                    $q->where('LsalesMarkupPercent', '>', 15)
                      ->where('PriceRequestGaeId', '=', 6);
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
                // If userId is 1, show all records
                // if ($userId == 15) {
                //     return;
                // }
        
                $query->where(function ($q) use ($userId) {
                    $q->whereHas('salesapprovers', function ($q1) use ($userId) {
                        $q1->where('SalesApproverId', $userId);
                    })->orWhereHas('salesapprovers1', function ($q2) use ($userId) {
                        $q2->where('SalesApproverId', $userId);
                    });
                });
            })
            // ->when($userId == 15, function ($query) { 
            //     $query->whereNotNull('NotedBy');
            // })
            ->get();

        $ccForm = CustomerComplaint2::whereIn('Progress', [20, 30, 50, 60])
            ->where('Status', 10)
            ->where(function ($query) use ($userId) {
                // If userId is 1, show all records
                // if ($userId == 15) {
                //     return;
                // }
        
                // $query->where(function ($q) use ($userId) {
                //     $q->whereHas('salesapprovers', function ($q1) use ($userId) {
                //         $q1->where('SalesApproverId', $userId);
                //     })->orWhereHas('salesapprovers1', function ($q2) use ($userId) {
                //         $q2->where('SalesApproverId', $userId);
                //     });
                // });

                $query->where(function ($q) use ($userId) {
                    $q->where(function ($qInner) use ($userId) {
                        $qInner->whereHas('salesapprovers', function ($q1) use ($userId) {
                            $q1->where('SalesApproverId', $userId);
                        })->where('NotedBy');
                    })->orWhere(function ($q2) use ($userId) {
                        $q2->whereHas('salesapprovers1', function ($q3) use ($userId) {
                            $q3->where('SalesApproverId', $userId);
                        })
                        // Others must exist
                        ->whereHas('others', function ($othersQuery) {
                            $othersQuery->whereNotNull('id');
                        })
                        // Delivery Handling must exist
                        ->whereHas('delivery_handling', function ($deliveryQuery) {
                            $deliveryQuery->whereNotNull('id');
                        })
                        // Packaging must exist
                        ->whereHas('packaging', function ($packagingQuery) {
                            $packagingQuery->whereNotNull('id');
                        })
                        // Product Quality must exist
                        ->whereHas('product_quality', function ($productQuery) {
                            $productQuery->whereNotNull('id');
                        });
                    });
                });
            })
            // ->when($userId == 15, function ($query) { 
            //     $query->whereNotNull('NotedBy');
            // })
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

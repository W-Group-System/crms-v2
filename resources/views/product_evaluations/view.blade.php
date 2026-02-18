@extends('layouts.header')
@section('title', 'Request for Product Evaluation - CRMS')
@section('content')
<style>
    #form_product {
        padding: 20px 20px;
    }
    
    #productTab .nav-link {
        padding: 15px;
    }
    .form-group label {
    font-size: 0.875rem;
    line-height: 0 !important;
    vertical-align: top;
    margin-bottom: 0 !important;
    }
    .group-form{
        margin-bottom: 1rem !important;
        margin-top: 1rem !important;
    }
    .form-divider{
        border-top: 1px solid black;
    }

    .border {
        border: 1px solid #ddd;
        padding: 10px;
        margin-bottom: 10px;
        overflow: hidden; 
    }
</style>
<div class="col-12 grid-margin stretch-card">
    <div class="card rounded-0 border border-1 border-primary p-0">
        <div class="card-header bg-primary text-white font-weight-bold">
            Request for Product Evaluation Details
        </div>
        <div class="card-body" style="overflow: auto;">
            <h4 class="card-title d-flex justify-content-end align-items-center" style="margin-top: 10px">
                <div align="right">
                    {{-- @if(url()->previous() == url()->current())
                    <a href="{{ url('request_product_evaluation?open=10') }}" class="btn btn-md btn-outline-secondary">
                        <i class="icon-arrow-left"></i>&nbsp;Back
                    </a> 
                    @else
                    <a href="{{ url()->previous() ?: url('/product_evaluations') }}" class="btn btn-md btn-outline-secondary">
                        <i class="icon-arrow-left"></i>&nbsp;Back
                    </a> 
                    @endif --}}
                    @if(request('origin') == 'for_approval')
                    {{-- <a href="{{ url('/request_product_evaluation?progress=10') }}" class="btn btn-md btn-outline-secondary">
                        <i class="icon-arrow-left"></i>&nbsp;Back
                    </a>  --}}
                    <a href="{{ url('/view_for_approval_transaction') }}" class="btn btn-md btn-outline-secondary">
                        <i class="icon-arrow-left"></i>&nbsp;Back
                    </a> 
                    @elseif(request('origin') == 'open_transactions')
                        @if(auth()->user()->role->type == "LS" || auth()->user()->role->type == "IS")
                            <a href="{{ url('/sales_open_transactions') }}" class="btn btn-md btn-outline-secondary">
                                <i class="icon-arrow-left"></i>&nbsp;Back
                            </a> 
                        @else
                            <a href="{{ url('/open-transaction') }}" class="btn btn-md btn-outline-secondary">
                                <i class="icon-arrow-left"></i>&nbsp;Back
                            </a> 
                        @endif
                    @elseif(request('origin') == 'returned_transactions')
                    <a href="{{ url('/returned_transactions') }}" class="btn btn-md btn-outline-secondary">
                        <i class="icon-arrow-left"></i>&nbsp;Back
                    </a> 
                    @elseif(request('origin') == 'initial_review')
                    <a href="{{ url('/initial-review') }}" class="btn btn-md btn-outline-secondary">
                        <i class="icon-arrow-left"></i>&nbsp;Back
                    </a> 
                    @elseif(request('origin') == 'new_request')
                    <a href="{{ url('/rnd-new-request') }}" class="btn btn-md btn-outline-secondary">
                        <i class="icon-arrow-left"></i>&nbsp;Back
                    </a> 
                    @elseif(request('origin') == 'final_review')
                    <a href="{{ url('/final-review') }}" class="btn btn-md btn-outline-secondary">
                        <i class="icon-arrow-left"></i>&nbsp;Back
                    </a> 
                    @else
                    <a href="{{url('/request_product_evaluation?open=10') }}" class="btn btn-md btn-outline-secondary">
                        <i class="icon-arrow-left"></i>&nbsp;Back
                    </a> 
                    @endif

                    {{-- <a class="btn btn-outline-danger btn-icon-text" href="javascript:void(0);">
                        <i class="ti ti-printer btn-icon-prepend"></i>
                        Print
                    </a> --}}
                    <a target='_blank' href="{{ url('print_rpe/'. $requestEvaluation->id) }}" class="btn btn-outline-danger btn-icon-text"><i class="ti ti-printer btn-icon-prepend"></i>Print</a>
                    <a target='_blank' href="{{ url('rnd_print/'. $requestEvaluation->id) }}" class="btn btn-outline-danger btn-icon-text"><i class="ti ti-printer btn-icon-prepend"></i>Print</a>
                    {{-- Sales Button --}}
                    {{-- @if((auth()->user()->id == $requestEvaluation->PrimarySalesPersonId || auth()->user()->user_id == $requestEvaluation->PrimarySalesPersonId) || (auth()->user()->id == $requestEvaluation->SecondarySalesPersonId || auth()->user()->user_id == $requestEvaluation->SecondarySalesPersonId) && auth()->user()->role->name == 'Staff L1') --}}
                    @if(checkIfInGroup($requestEvaluation->PrimarySalesPersonId, auth()->user()->id))
                        @if($requestEvaluation->Status == 10)
                            <button type="button" class="btn btn-outline-warning editBtn" data-toggle="modal" data-target="#editRpe{{$requestEvaluation->id}}" data-secondarysales="{{$requestEvaluation->SecondarySalesPersonId}}">
                                <i class="ti ti-pencil"></i>&nbsp;Update
                            </button>
                        @endif

                        {{-- @if($requestEvaluation->Progress == 70)
                            <form method="POST" class="d-inline-block" action="{{url('start_rpe/'.$requestEvaluation->id)}}">
                                @csrf

                                <button type="button" class="btn btn-info returnToRnd">
                                    <i class="ti ti-check-box"></i>&nbsp;Return to RND
                                </button>
                            </form>
                        @endif --}}
                        @if(primarySalesApprover($requestEvaluation->PrimarySalesPersonId, auth()->user()->id))
                            @if($requestEvaluation->Progress != 30 && $requestEvaluation->Progress != 35 && $requestEvaluation->Progress != 40 && $requestEvaluation->Progress != 50 && $requestEvaluation->Progress != 55 && $requestEvaluation->Progress != 57 && $requestEvaluation->Progress != 60 && $requestEvaluation->Progress != 81 && $requestEvaluation->Progress != 70)
                            <button type="button" class="btn btn-md btn-outline-success"
                                data-target="#approveRpe{{ $requestEvaluation->id }}" 
                                data-toggle="modal" 
                                title='Approve RPE'>
                                <i class="ti ti-check-box">&nbsp;</i>Approve
                            </button>
                            @endif
                        @endif
                        
                        @if(auth()->user()->id != $requestEvaluation->SecondarySalesPersonId && auth()->user()->user_id != $requestEvaluation->SecondarySalesPersonId)
                            @if($requestEvaluation->Progress == 60 && $requestEvaluation->Progress != 70)
                                <form method="POST" class="d-inline-block" action="{{url('start_rpe/'.$requestEvaluation->id)}}">
                                    @csrf

                                    <button type="button" class="btn btn-outline-info returnToRnd">
                                        <i class="ti ti-check-box"></i>&nbsp;Return to RND
                                    </button>
                                </form>
                                <form method="POST" class="d-inline-block" action="{{url('sales_accept_rpe/'.$requestEvaluation->id)}}">
                                    @csrf

                                    <button type="button" class="btn btn-outline-success salesAccept">
                                        <i class="ti ti-check-box"></i>&nbsp;Accept
                                    </button>
                                </form>
                            @endif
                        @endif

                        @if(auth()->user()->id == $requestEvaluation->PrimarySalesPersonId || auth()->user()->user_id == $requestEvaluation->PrimarySalesPersonId)
                            @if($requestEvaluation->Status == 10 && ($requestEvaluation->Progress == 70 ||  $requestEvaluation->Progress == 60 || $requestEvaluation->Progress == 10 || $requestEvaluation->Progress == 20 || $requestEvaluation->Progress == 30))
                                <button type="button" class="btn btn-outline-primary" id="closeBtn" data-toggle="modal" data-target="#closeModal{{$requestEvaluation->id}}">
                                    <i class="ti ti-close"></i>&nbsp;Close
                                </button>
                            @endif
                            @if( ($requestEvaluation->Progress == 70))
                                <form method="POST" class="d-inline-block" action="{{url('start_rpe/'.$requestEvaluation->id)}}">
                                    @csrf

                                    <button type="button" class="btn btn-outline-info returnToRnd">
                                        <i class="ti ti-check-box"></i>&nbsp;Return to RND
                                    </button>
                                </form>
                            @endif
                            @if($requestEvaluation->Status == 10 && ($requestEvaluation->Progress == 60 || $requestEvaluation->Progress == 10 || $requestEvaluation->Progress == 20 || $requestEvaluation->Progress == 30))
                                <button type="button" class="btn btn-outline-danger" id="cancelBtn" data-toggle="modal" data-target="#cancelModal{{$requestEvaluation->id}}">
                                    <i class="mdi mdi-cancel"></i>&nbsp;Cancel
                                </button>
                            @endif

                            @if($requestEvaluation->Status == 30)
                                <form method="POST" class="d-inline-block" action="{{url('open_rpe/'.$requestEvaluation->id)}}">
                                    @csrf

                                    <button type="button" class="btn btn-outline-success openBtn">
                                        <i class="mdi mdi-open-in-new"></i>&nbsp;Open
                                    </button>
                                </form>
                            @endif
                        @endif
                    
                    @elseif(authCheckIfItsRndStaff(auth()->user()->role))
                    
                    {{-- RND Staff --}}
                    @php
                        $id = auth()->user()->id || auth()->user()->user_id;
                    @endphp
                    @if(rndPersonnel($requestEvaluation->rpePersonnel, $id))
                            
                        @if ($requestEvaluation->Status == 10)

                            @if($requestEvaluation->Progress != 10  && $requestEvaluation->Progress != 20  )
                            <button type="button" class="btn btn-outline-warning" data-toggle="modal" data-target="#updateRnd{{$requestEvaluation->id}}">
                                <i class="ti ti-pencil"></i>&nbsp;Update
                            </button>
                            @endif

                            @if($requestEvaluation->Progress == 35)
                                <form method="POST" action="{{url('start_rpe/'.$requestEvaluation->id)}}" class="d-inline-block">
                                    @csrf 

                                    <button type="button" class="btn btn-outline-success startBtn">
                                        <i class="ti-control-play"></i>&nbsp; Start
                                    </button>
                                </form>
                            @endif

                            @if($requestEvaluation->Progress == 50)
                                <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#pauseModal{{$requestEvaluation->id}}">
                                    <i class="ti-control-pause"></i>&nbsp; Pause
                                </button>
                            @endif

                            @if($requestEvaluation->Progress == 55)
                                <form method="POST" action="{{url('start_rpe/'.$requestEvaluation->id)}}" class="d-inline-block">
                                    @csrf 

                                    <button type="button" class="btn btn-outline-success continueBtn">
                                        <i class="ti-control-play"></i>&nbsp; Continue
                                    </button>
                                </form>
                            @endif

                            @if(rndPersonnel($requestEvaluation->rpePersonnel, $id))
                                @if($requestEvaluation->Progress == 50)
                                <form method="POST" action="{{url('initial_review_rpe/'.$requestEvaluation->id)}}" class="d-inline-block">
                                    @csrf

                                    <button type="button" class="btn btn-outline-success initialReviewBtn">
                                        <i class="ti-check"></i>&nbsp; Submit
                                    </button>
                                </form>
                                @endif
                            @endif

                            {{-- @if($requestEvaluation->Progress == 57)
                                <form method="POST" action="{{url('final_review_rpe/'.$requestEvaluation->id)}}" class="d-inline-block">
                                    @csrf 

                                    <button type="button" class="btn btn-success finalReviewBtn">
                                        <i class="ti-check"></i>&nbsp; Submit
                                    </button>
                                </form>
                            @endif

                            @if($requestEvaluation->Progress == 57 || $requestEvaluation->Progress == 81)
                                <form method="POST" class="d-inline-block" action="{{url('complete_rpe/'.$requestEvaluation->id)}}">
                                    @csrf 

                                    <button type="button" class="btn btn-primary completeBtn">
                                        <i class="ti-pencil-alt"></i>&nbsp; Completed
                                    </button>
                                </form>
                            @endif --}}
                            
                        @endif
                    @endif
                    
                    {{-- RND and Sales Manager and Supervisor --}}
                    @elseif(checkIfItsManagerOrSupervisor(auth()->user()->role) == "yes")
                            
                        @if(authCheckIfItsRnd(auth()->user()->department_id))
                            @if($requestEvaluation->Progress != 10 && $requestEvaluation->Progress != 20 && $requestEvaluation->Progress != 60 && $requestEvaluation->Progress != 35 && $requestEvaluation->Progress != 50 && $requestEvaluation->Progress != 57 && $requestEvaluation->Progress != 81 && $requestEvaluation->ReturnToSales == 0)
                                <button type="button" class="btn btn-outline-info"
                                    data-target="#returnToSales{{  $requestEvaluation->id }}" 
                                    data-toggle="modal" 
                                    title='Return To Sales SRF'>
                                    <i class="ti-control-left">&nbsp;</i>Return To Sales
                                </button>
                            @endif
                        @endif
                        @if(authCheckIfItsSales(auth()->user()->department_id))
                            @if(primarySalesApprover($requestEvaluation->PrimarySalesPersonId, auth()->user()->id))
                                
                                @if($requestEvaluation->Status == 10)
                                    <button type="button" class="btn btn-outline-warning editBtn" data-toggle="modal" data-target="#editRpe{{$requestEvaluation->id}}" data-secondarysales="{{$requestEvaluation->SecondarySalesPersonId}}">
                                        <i class="ti ti-pencil"></i>&nbsp;Update
                                    </button>

                                    {{-- @if($requestEvaluation->SecondarySalesPersonId == auth()->user()->id || $requestEvaluation->SecondarySalesPersonId == auth()->user()->user_id) --}}
                                        @if($requestEvaluation->Progress != 30 && $requestEvaluation->Progress != 35 && $requestEvaluation->Progress != 40 && $requestEvaluation->Progress != 50 && $requestEvaluation->Progress != 55 && $requestEvaluation->Progress != 57 && $requestEvaluation->Progress != 60 && $requestEvaluation->Progress != 81 && $requestEvaluation->Progress != 70)
                                        <button type="button" class="btn btn-md btn-outline-success"
                                            data-target="#approveRpe{{ $requestEvaluation->id }}" 
                                            data-toggle="modal" 
                                            title='Approve RPE'>
                                            <i class="ti ti-check-box">&nbsp;</i>Approve
                                        </button>
                                        @endif
                                    {{-- @endif --}}
                                @endif

                                @if($requestEvaluation->Progress == 60 && $requestEvaluation->Progress != 70)
                                    <form method="POST" class="d-inline-block" action="{{url('start_rpe/'.$requestEvaluation->id)}}">
                                        @csrf

                                        <button type="button" class="btn btn-outline-info returnToRnd">
                                            <i class="ti ti-check-box"></i>&nbsp;Return to RND
                                        </button>
                                    </form>
                                    <form method="POST" class="d-inline-block" action="{{url('sales_accept_rpe/'.$requestEvaluation->id)}}">
                                        @csrf

                                        <button type="button" class="btn btn-outline-success salesAccept">
                                            <i class="ti ti-check-box"></i>&nbsp;Accept
                                        </button>
                                    </form>
                                @endif

                                @if($requestEvaluation->Status == 10 && ($requestEvaluation->Progress == 70 ||  $requestEvaluation->Progress == 60 || $requestEvaluation->Progress == 10 || $requestEvaluation->Progress == 20 || $requestEvaluation->Progress == 30))
                                    <button type="button" class="btn btn-outline-primary" id="closeBtn" data-toggle="modal" data-target="#closeModal{{$requestEvaluation->id}}">
                                        <i class="ti ti-close"></i>&nbsp;Close
                                    </button>
                                @endif

                                @if($requestEvaluation->Status == 10 && ($requestEvaluation->Progress == 60 || $requestEvaluation->Progress == 10 || $requestEvaluation->Progress == 20 || $requestEvaluation->Progress == 30))
                                    <button type="button" class="btn btn-outline-danger" id="cancelBtn" data-toggle="modal" data-target="#cancelModal{{$requestEvaluation->id}}">
                                        <i class="mdi mdi-cancel"></i>&nbsp;Cancel
                                    </button>
                                @endif

                                @if($requestEvaluation->Status == 30)
                                    <form method="POST" class="d-inline-block" action="{{url('open_rpe/'.$requestEvaluation->id)}}">
                                        @csrf

                                        <button type="button" class="btn btn-outline-success openBtn">
                                            <i class="mdi mdi-open-in-new"></i>&nbsp;Open
                                        </button>
                                    </form>
                                @endif

                            @endif
                        @endif

                        @if(authCheckIfItsRnd(auth()->user()->department_id))
                            @if ($requestEvaluation->Status == 10)
                                @if( $requestEvaluation->Progress != 10  && $requestEvaluation->Progress != 20  && $requestEvaluation->Progress != 30 )
                                    <button type="button" class="btn btn-outline-warning" data-toggle="modal" data-target="#updateRnd{{$requestEvaluation->id}}">
                                        <i class="ti ti-pencil"></i>&nbsp;Update
                                    </button>
                                @endif

                                @if($requestEvaluation->Progress == 30)
                                    <form method="POST" class="d-inline-block" action="{{url('received_rpe/'.$requestEvaluation->id)}}">
                                        @csrf 
                                        <button type="button" class="btn btn-outline-success receivedBtn">
                                            <i class="ti-bookmark">&nbsp;</i> Received
                                        </button>
                                    </form>
                                @endif

                                @if($requestEvaluation->Progress == 35)
                                <form method="POST" action="{{url('start_rpe/'.$requestEvaluation->id)}}" class="d-inline-block">
                                    @csrf 

                                    <button type="button" class="btn btn-outline-success startBtn">
                                        <i class="ti-control-play"></i>&nbsp; Start
                                    </button>
                                </form>
                                @endif

                                @if($requestEvaluation->Progress == 50)
                                    <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#pauseModal{{$requestEvaluation->id}}">
                                        <i class="ti-control-pause"></i>&nbsp; Pause
                                    </button>

                                    <form method="POST" action="{{url('initial_review_rpe/'.$requestEvaluation->id)}}" class="d-inline-block">
                                        @csrf
        
                                        <button type="button" class="btn btn-outline-success initialReviewBtn">
                                            <i class="ti-check"></i>&nbsp; Submit
                                        </button>
                                    </form>
                                @endif

                                @if($requestEvaluation->Progress == 55)
                                    <form method="POST" action="{{url('start_rpe/'.$requestEvaluation->id)}}" class="d-inline-block">
                                        @csrf 

                                        <button type="button" class="btn btn-outline-success continueBtn">
                                            <i class="ti-control-play"></i>&nbsp; Continue
                                        </button>
                                    </form>
                                @endif

                                @if($requestEvaluation->Progress == 81 || $requestEvaluation->Progress == 57 || $requestEvaluation->Progress == 81)
                              
                                    {{-- <form method="POST" class="d-inline-block" action="{{url('start_rpe/'.$requestEvaluation->id)}}">
                                        @csrf

                                        <button type="button" class="btn btn-outline-info returnToRnd">
                                            <i class="ti ti-check-box"></i>&nbsp;Return to Specialist
                                        </button>
                                    </form> --}}
                                    <button type="button" class="btn btn-outline-info"
                                        data-target="#rpeReturnToSpecialist{{ $requestEvaluation->id }}" 
                                        data-toggle="modal" 
                                        title='Return To Specialist'>
                                        <i class="ti ti-check-box">&nbsp;</i>Return to Specialist
                                    </button>
                                    
                                @endif

                                @if($requestEvaluation->Progress == 57)
                                    <form method="POST" action="{{url('final_review_rpe/'.$requestEvaluation->id)}}" class="d-inline-block">
                                        @csrf 

                                        <button type="button" class="btn btn-outline-success finalReviewBtn">
                                            <i class="ti-check"></i>&nbsp; Submit
                                        </button>
                                    </form>
                                @endif

                                @if($requestEvaluation->Progress == 57 || $requestEvaluation->Progress == 81)
                                    <form method="POST" class="d-inline-block" action="{{url('complete_rpe/'.$requestEvaluation->id)}}">
                                        @csrf 

                                        <button type="button" class="btn btn-outline-primary completeBtn">
                                            <i class="ti-pencil-alt"></i>&nbsp; Completed
                                        </button>
                                    </form>
                                @endif
                                
                                
                            @endif

                        @endif

                    @endif
                    
                </div>
            </h4>
            @include('components.error')
            <div class="col-md-12">
                <label><strong>Customer Details</strong></label>
                    <hr style="margin-top: 0px; color: black; border-top-color: black;">
                    <div class="row mb-0">
                        <div class="col-sm-3 col-md-2 text-right">
                            <p class="mb-0"><b>Client Name :</b></p>
                        </div>
                        <div class="col-sm-3 col-md-2">
                            <p class="mb-0">
                                <a href="{{ url('view_client/' . optional($requestEvaluation->client)->id) }}">
                                    {{ optional($requestEvaluation->client)->Name }}
                                </a>
                            </p>
                        </div>
                    </div>
                    <div class="row mb-0">
                        <div class="col-sm-3 col-md-2 text-right">
                            <p class="mb-0"><b>Client Trade Name :</b></p>
                        </div>
                        <div class="col-sm-3 col-md-2">
                            <p class="mb-0">{{ optional($requestEvaluation->client)->TradeName }}</p>
                        </div>
                    </div>
                    <div class="row mb-0">
                        <div class="col-sm-3 col-md-2 text-right">
                            <p class="mb-0"><b>Region :</b></p>
                        </div>
                        <div class="col-sm-3 col-md-2">
                            <p class="mb-0">{{ optional(optional($requestEvaluation->client)->clientregion)->Name }}</p>
                        </div>
                    </div>
                    <div class="row mb-0">
                        <div class="col-sm-3 col-md-2 text-right">
                            <p class="mb-0"><b>Country :</b></p>
                        </div>
                        <div class="col-sm-3 col-md-2">
                            <p class="mb-0">{{ optional(optional($requestEvaluation->client)->clientcountry)->Name }}</p>
                        </div>
                    </div>
                    <label><strong>Request Details</strong></label>
                    <hr style="margin-top: 0px; color: black; border-top-color: black;">
                    <div class="row mb-0">
                        
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-sm-3 col-md-4">
                                    <p class="mb-0 text-right"><b>RPE # :</b></p>
                                </div>
                                <div class="col-sm-3 col-md-8">
                                    <p class="mb-0">{{ $requestEvaluation->RpeNumber }}</p>
                                </div>
                                <div class="col-sm-3 col-md-4">
                                    <p class="mb-0 text-right"><b>Date Requested :</b></p>
                                </div>
                                <div class="col-sm-3 col-md-8">
                                    <p class="mb-0">@if($requestEvaluation->CreatedDate != null)
                                        {{ date('M d, Y h:i A', strtotime($requestEvaluation->CreatedDate)) }}
                                        @else
                                        {{ date('M d, Y h:i A', strtotime($requestEvaluation->created_at)) }}
                                        @endif</p>
                                </div>
                                <div class="col-sm-3 col-md-4">
                                    <p class="text-right mb-0"><b>Date Required :</b></p>
                                </div>
                                <div class="col-sm-3 col-md-8">
                                    <p class="mb-0">{{ $requestEvaluation->DueDate ?? 'NA'}}</p>
                                </div>
                                <div class="col-sm-3 col-md-4">
                                    <p class="mb-0 text-right"><b>Priority :</b></p>
                                </div>
                                <div class="col-sm-3 col-md-8">
                                    <p class="mb-0"> @if($requestEvaluation->Priority == 1)
                                        IC Application
                                    @elseif($requestEvaluation->Priority == 3)
                                        Second Priority
                                    @elseif($requestEvaluation->Priority == 5)
                                        First Priority
                                    @else
                                        {{ $requestEvaluation->Priority }}
                                    @endif</p>
                                </div>
                                <div class="col-md-4">&nbsp;</div>
                                <div class="col-md-8">&nbsp;</div>
                                <div class="col-md-4">&nbsp;</div>
                                <div class="col-md-8">&nbsp;</div>
                                <div class="col-md-4">&nbsp;</div>
                                <div class="col-md-8">&nbsp;</div>
                                <div class="col-sm-3 col-md-4">
                                    <p class="text-right mb-0"><b>Project Name :</b></p>
                                </div>
                                <div class="col-sm-3 col-md-8">
                                    <p class="mb-0">{{ optional($requestEvaluation->projectName)->Name  }}</p>
                                </div>
                                <div class="col-sm-3 col-md-4">
                                    <p class="mb-0 text-right"><b>Application :</b></p>
                                </div>
                                <div class="col-sm-3 col-md-8">
                                    <p class="mb-0">{{ optional($requestEvaluation->product_application)->Name  }}</p>
                                </div>
                                <div class="col-sm-3 col-md-4">
                                    <p class="mb-0 text-right"><b>Potential Volume :</b></p>
                                </div>
                                <div class="col-sm-3 col-md-8">
                                    <p class="mb-0">
                                        {{ $requestEvaluation->PotentialVolume }} 
                                        @if ($requestEvaluation->UnitOfMeasureId == 1)
                                            g
                                        @elseif ($requestEvaluation->UnitOfMeasureId == 2)
                                            kg
                                        @endif
                                    </p>
                                </div>
                                <div class="col-sm-3 col-md-4">
                                    <p class="mb-0 text-right"><b>Target Raw Price :</b></p>
                                </div>
                                <div class="col-sm-3 col-md-8">
                                    <p class="mb-0">{{ optional($requestEvaluation->priceCurrency)->Name  }} {{ $requestEvaluation->TargetRawPrice  }}</p>
                                </div>
                                <div class="col-sm-3 col-md-4">
                                    <p class="mb-0 text-right"><b>Objective for RPE Project :</b></p>
                                </div>
                                <div class="col-sm-9 col-md-8">
                                    <p class="mb-0">{!! nl2br(e($requestEvaluation->ObjectiveForRpeProject)) !!}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-sm-3 col-md-4">
                                    <p class="mb-0 text-right"><b>Primary Sales Person :</b></p>
                                </div>
                                <div class="col-sm-3 col-md-8">
                                    <p class="mb-0">
                                        @if($requestEvaluation->primarySalesPerson)
                                        {{ optional($requestEvaluation->primarySalesPerson)->full_name}}
                                        @elseif($requestEvaluation->primarySalesPersonById)
                                        {{ optional($requestEvaluation->primarySalesPersonById)->full_name}}
                                        @endif</p>
                                </div>
                                <div class="col-sm-3 col-md-4">
                                    <p class="mb-0 text-right"><b>Secondary Sales Person :</b></p>
                                </div>
                                <div class="col-sm-3 col-md-8">
                                    <p class="mb-0">
                                        @if($requestEvaluation->secondarySalesPerson)
                                        {{ optional($requestEvaluation->secondarySalesPerson)->full_name}}
                                        @elseif($requestEvaluation->secondarySalesPersonById)
                                        {{ optional($requestEvaluation->secondarySalesPersonById)->full_name}}
                                        @endif
                                </div>
                                <div class="col-md-4">&nbsp;</div>
                                <div class="col-md-8">&nbsp;</div>
                                {{-- <div class="col-md-4">&nbsp;</div>
                                <div class="col-md-8">&nbsp;</div> --}}

                                <div class="col-sm-3 col-md-4">
                                    <p class="mb-0 text-right"><b>Attention To :</b></p>
                                </div>
                                <div class="col-sm-3 col-md-8">
                                    <p class="mb-0">  @if($requestEvaluation->AttentionTo == 1)
                                        RND
                                    @elseif($requestEvaluation->AttentionTo == 2)
                                        QCD
                                    @else
                                        {{ $requestEvaluation->AttentionTo }}
                                    @endif</p>
                                </div>
                                <div class="col-sm-3 col-md-4">
                                    <p class="mb-0 text-right"><b>Status :</b></p>
                                </div>
                                <div class="col-sm-3 col-md-8">
                                    <p class="mb-0">
                                        @if($requestEvaluation->Status == 10)
                                            Open
                                        @elseif($requestEvaluation->Status == 30)
                                            Closed
                                        @elseif($requestEvaluation->Status == 50)
                                            Cancelled
                                        @else
                                            {{ $requestEvaluation->Status }}
                                        @endif
                                    </p>
                                </div>
                                <div class="col-sm-3 col-md-4">
                                    <p class="mb-0 text-right"><b>Progress :</b></p>
                                </div>
                                <div class="col-sm-3 col-md-8">
                                    <p class="mb-0">
                                        {{ optional($requestEvaluation->progressStatus)->name  }}
                                    </p>
                                </div>
                                <div class="col-md-4">&nbsp;</div>
                                <div class="col-md-8">&nbsp;</div>

                                <div class="col-sm-3 col-md-4">
                                    <p class="text-right mb-0"><b>Sample Name :</b></p>
                                </div>
                                <div class="col-sm-3 col-md-8">
                                    <p class="mb-0">
                                        {{ $requestEvaluation->SampleName  }}
                                    </p>
                                </div>
                                <div class="col-sm-3 col-md-4">
                                    <p class="text-right mb-0"><b>Manufacturer :</b></p>
                                </div>
                                <div class="col-sm-3 col-md-8">
                                    <p class="mb-0">
                                        {{ $requestEvaluation->Manufacturer  }}
                                    </p>
                                </div>
                                <div class="col-sm-3 col-md-4">
                                    <p class="mb-0 text-right"><b>Supplier :</b></p>
                                </div>
                                <div class="col-sm-3 col-md-8">
                                    <p class="mb-0">
                                        {{ $requestEvaluation->Supplier  }}
                                    </p>
                                </div>
                                <div class="col-sm-3 col-md-4">
                                    <p class="mb-0 text-right"><b>RPE Reference Number :</b></p>
                                </div>
                                <div class="col-sm-3 col-md-8">
                                    <p class="mb-0">
                                        {{ $requestEvaluation->RpeReferenceNumber  }}
                                    </p>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="group-form">
                        <label><strong>Sales Files</strong></label>
                        <hr style="margin-top: 0px; color: black; border-top-color: black;">   
                            @foreach ($requestEvaluation->salesRpeFiles as $file)
                                @if ($file->Path)
                                    <div class="col-sm-12 d-flex align-items-center">
                                            <p class="file-link" style="margin-top: 5px;">
                                                <a href="{{ url($file->Path) }}" target="_blank">{{ $file->Name }}</a>
                                            </p>
                                            &nbsp;
                                            <a href="#" class="text-warning" data-toggle="modal" data-target="#editSalesFiles{{$file->Id}}">
                                                <i class="ti-pencil-alt"></i>
                                            </a>
                                            <a href="#" class="text-danger deleteFilesBtn" data-id="{{$file->Id}}">
                                                <i class="ti-trash"></i>
                                            </a>
                                    </div>
                                @endif
                            @endforeach
                    </div>
                    <div class="form-header">
                        <span class="header-label"><b>Approver Remarks</b></span>
                        <hr class="form-divider">
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            @if($requestEvaluation->primarySalesPersonById != null)
                                @foreach (optional($requestEvaluation->primarySalesPersonById)->salesApproverById as $approver)
                                    <p style="font-weight: bold;" class="mb-0 text-right">{{$approver->salesApprover->full_name}} :</p>
                                @endforeach
                            @else
                                @foreach (optional($requestEvaluation->primarySalesPerson)->salesApproverById as $approver)
                                    <p style="font-weight: bold;" class="mb-0 text-right">{{$approver->salesApprover->full_name}} :</p>
                                @endforeach
                            @endif
                        </div>
                        <div class="col-sm-12 col-md-2 mb-3">
                            @if($requestEvaluation->approver)
                                @php
                                    $acceptRemarks = $requestEvaluation->rpeTransactionApprovals->sortByDesc('Id')->firstWhere('RemarksType', 'accept');
                                @endphp
                                @if($acceptRemarks != null)
                                <p class="mb-0">{{$acceptRemarks->Remarks}}</p>
                                @endif
                            @else
                                <p class="mb-0">No approver remarks yet</p>
                            @endif
                        </div>
                    </div>
                    <label><strong>Evaluation Details</strong></label>
                    <hr style="margin-top: 0px; color: black; border-top-color: black;">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row mb-0">
                                <div class="col-sm-3 col-md-4">
                                    <p class="mb-0 text-right"><b>DDW Number :</b></p>
                                </div>
                                <div class="col-sm-3 col-md-8">
                                    <p class="mb-0">{{ $requestEvaluation->DdwNumber  }}</p>
                                </div>
                                @php
                                    $rpeResult = $requestEvaluation->RpeResult;
                                    $pattern = '/\[(.*?)\]/';
                                
                                    $rpeResultLinked = preg_replace_callback($pattern, function($matches) {
                                        $code = $matches[1];
                                        $product = getProductIdByCode($code);
                                        
                                        if ($product == null)
                                        {
                                            return $matches[0];
                                        }

                                        if (auth()->user()->role->type == 'LS' || auth()->user()->role->type == 'IS')
                                        {
                                            if ($product->status == 4)
                                            {
                                                return '<a href="'.url('view_product/'.$product->id).'">'.$matches[0].'</a>';
                                            }
                                        }
                                        else
                                        {
                                            if ($product->status == 4)
                                            {
                                                return '<a href="'.url('view_product/'.$product->id).'">'.$matches[0].'</a>';
                                            }
                                            if ($product->status == 2)
                                            {
                                                return '<a href="'.url('view_new_product/'.$product->id).'">'.$matches[0].'</a>';
                                            }
                                            if ($product->status == 1)
                                            {
                                                return '<a href="'.url('view_draft_product/'.$product->id).'">'.$matches[0].'</a>';
                                            }
                                            if ($product->status == 5)
                                            {
                                                return '<a href="'.url('view_archive_products/'.$product->id).'">'.$matches[0].'</a>';
                                            }
                                        }
                                        return $matches[0];
                                    }, $rpeResult);
                                @endphp
                                <div class="col-sm-3 col-md-4">
                                    <p class="text-right mb-0"><b>RPE Recommendation :</b></p>
                                </div>
                                <div class="col-sm-9 col-md-8">
                                    <p class="mb-0">{!! nl2br($rpeResultLinked) !!}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="row mb-0">
                                <div class="col-sm-3 col-md-4">
                                    <p class="mb-0 text-right"><b>Date Received :</b></p>
                                </div>
                                <div class="col-sm-3 col-md-8 mb-0">
                                    <p class="mb-0">{{ $requestEvaluation->DateReceived ? date('M d, Y', strtotime($requestEvaluation->DateReceived)) : 'NA' }}</p>
                                </div>
                                <div class="col-sm-3 col-md-4">
                                    <p class="mb-0 text-right"><b>Date Completed :</b></p>
                                </div>
                                <div class="col-sm-3 col-md-8">
                                    <p class="mb-0">{{ $requestEvaluation->DateCompleted}}</p>
                                </div>
                                @php
                                    $dateReceived = $requestEvaluation->DateReceived ? strtotime($requestEvaluation->DateReceived) : null;
                                    $dueDate = $requestEvaluation->DueDate ? strtotime($requestEvaluation->DueDate) : null;
                                    $dateCompleted = $requestEvaluation->DateCompleted ? strtotime($requestEvaluation->DateCompleted) : null;

                                    // if ($dateReceived && $dueDate) {
                                    //     $difference = ($dueDate - $dateReceived) / (60 * 60 * 24); 
                                    //     $leadtime = number_format($difference, 0);
                                    // } else {
                                    //     $leadtime = 'NA';
                                    // }
                                    $leadReceived = $requestEvaluation->DateReceived;
                                    $leadDueDate = $requestEvaluation->DueDate;

                                    if ($leadReceived && $leadDueDate) {
                                        $start = DateTime::createFromFormat('Y-m-d', date('Y-m-d', strtotime($leadReceived)));
                                        $end = DateTime::createFromFormat('Y-m-d', date('Y-m-d', strtotime($leadDueDate)));

                                        $leadtime = 0;

                                        while ($start <= $end) {
                                            $dayOfWeek = $start->format('N'); 
                                            if ($dayOfWeek < 6) { 
                                                $leadtime++;
                                            }
                                            $start->modify('+1 day');
                                        }
                                    } else {
                                        $leadtime = 'NA';
                                    }

                                    if (!$dateCompleted) {
                                        $dateCompleted = time();
                                    }

                                    if ($dueDate) {
                                        $delay = ($dateCompleted - $dueDate) / (60 * 60 * 24); 
                                        $delayed = number_format($delay, 0);
                                    } else {
                                        $delayed = 'NA';
                                    }
                                @endphp
                                <div class="col-sm-3 col-md-4">
                                    <p class="mb-0 text-right"><b>Lead Time :</b></p>
                                </div>
                                <div class="col-sm-3 col-md-8">
                                    <p class="mb-0">{{ $leadtime}} day/s</p>
                                </div>
                                <div class="col-sm-3 col-md-4">
                                    <p class="text-right mb-0"><b>Delayed :</b></p>
                                </div>
                                <div class="col-sm-3 col-md-8">
                                    <p class="mb-0">{{ $delayed }} day/s</p>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <ul class="nav nav-tabs" id="productTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link p-2 @if(session('tab') === 'supplementary_details' || session('tab') == null) active @endif" id="supplementary-tab" data-toggle="tab" href="#supplementary" role="tab" aria-controls="supplementary" aria-selected="true">Supplementary Details</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link p-2 @if(session('tab') == 'personnel') active @endif" id="srfPersonnel-tab" data-toggle="tab" href="#srfPersonnel" role="tab" aria-controls="srfPersonnel" aria-selected="true">Assigned R&D Personnel</a>
                </li>
                {{-- <li class="nav-item">
                    <a class="nav-link" id="activities-tab" data-toggle="tab" href="#activities" role="tab" aria-controls="activities" aria-selected="false">Activities</a>
                </li> --}}
                <li class="nav-item">
                    <a class="nav-link p-2  @if(session('tab') == 'files') active @endif" id="files-tab" data-toggle="tab" href="#files" role="tab" aria-controls="files" aria-selected="false">Files</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link p-2" id="history-tab" data-toggle="tab" href="#history" role="tab" aria-controls="history" aria-selected="false">History</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade  @if(session('tab') == 'supplementary_details' || session('tab') == null) active show @endif" id="supplementary" role="tabpanel" aria-labelledby="supplementary-tab">
                    {{-- <div class="d-flex">
                        <button type="button" class="btn btn-sm btn-primary ml-auto m-3" title="Add Supplementary Details" data-toggle="modal" data-target="#addRpeSuplementary">
                            <i class="ti-plus"></i>
                        </button>
                    </div> --}}
                    @if(!checkIfItsSalesDept(auth()->user()->department_id))
                        @if($requestEvaluation->Progress != 60)
                        <button type="button" class="btn btn-outline-primary float-right mb-3" data-toggle="modal" data-target="#addRpeSuplementary">
                            New 
                        </button>
                        {{-- @include('customer_requirements.add_supplementary_details') --}}
                        @endif
                    @endif
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover table-detailed" id="supplementary_table" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Actions</th>
                                    <th>Date</th>
                                    <th>Name</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($requestEvaluation->supplementaryDetails as $supplementary)
                                    <tr>
                                        <td>
                                            @if(!checkIfItsSalesDept(auth()->user()->department_id))
                                                <button type="button"  class="btn btn-sm btn-warning btn-outline"
                                                    data-target="#editRpeSupplementary{{ $supplementary->Id }}" data-toggle="modal" title='Edit Supplementary'>
                                                    <i class="ti-pencil"></i>
                                                </button>   
                                                <button type="button" class="btn btn-sm btn-danger btn-outline" onclick="confirmDelete({{ $supplementary->Id }}, 'supplementary')" title='Delete Supplementary'>
                                                    <i class="ti-trash"></i>
                                                </button> 
                                            @endif
                                        </td>
                                        <td>
                                            @if($supplementary->DateCreated != null)
                                            {{ date('M d, Y h:i A', strtotime($supplementary->DateCreated)) }}</td>
                                            @else
                                            {{date('M d, Y h:i A', strtotime($supplementary->CreatedDate))}}
                                            @endif
                                        <td>
                                            @if($supplementary->userSupplementary)
                                                {{$supplementary->userSupplementary->full_name}}
                                            @elseif($supplementary->userId)
                                                {{$supplementary->userId->full_name}}
                                            @endif
                                        </td>
                                        <td>{!! nl2br( $supplementary->DetailsOfRequest) !!}</td>
                                    </tr>
                                    @include('product_evaluations.edit_supplementary')
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade  " id="srfPersonnel" role="tabpanel" aria-labelledby="srfPersonnel-tab">
                    {{-- <div class="d-flex">
                        <button type="button" class="btn btn-sm btn-primary ml-auto m-3" title="Assign R&D"  data-toggle="modal" data-target="#addRpePersonnel">
                            <i class="ti-plus"></i>
                        </button>
                    </div> --}}
                    @if(!checkIfItsSalesDept(auth()->user()->department_id))
                        @if($requestEvaluation->Progress != 10 && $requestEvaluation->Progress != 30 && $requestEvaluation->Progress != 55 && $requestEvaluation->Progress != 60 && $requestEvaluation->Progress != 81 && rndManager(auth()->user()->role))
                        <button type="button" class="btn btn-outline-primary float-right mb-3" data-toggle="modal" data-target="#addRpePersonnel">
                            New
                        </button>
                        {{-- @include('customer_requirements.new_personnel') --}}
                        @endif
                    @endif
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover table-detailed" id="personnel_table" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Actions</th>
                                    <th>Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($requestEvaluation->rpePersonnel as $Personnel)
                                    <tr>
                                        <td>
                                            @if(!checkIfItsSalesDept(auth()->user()->department_id))
                                                <button type="button"  class="btn btn-sm btn-warning btn-outline"
                                                    data-target="#editRpePersonnel{{ $Personnel->Id }}" data-toggle="modal" title='Edit Personnel'>
                                                    <i class="ti-pencil"></i>
                                                </button>   
                                                <button type="button" class="btn btn-sm btn-danger btn-outline" onclick="confirmDelete({{ $Personnel->Id }}, 'personnel')" title='Delete Personnel'>
                                                    <i class="ti-trash"></i>
                                                </button> 
                                            @endif
                                        </td>
                                        <td>
                                            @if($Personnel->assignedPersonnel)
                                            {{ $Personnel->assignedPersonnel->full_name }}
                                            @elseif($Personnel->userId)
                                            {{$Personnel->userId->full_name}}
                                            @endif
                                        </td>
                                    </tr>
                                    @include('product_evaluations.edit_personnel') 
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="activities" role="tabpanel" aria-labelledby="activities-tab">
                    @if(checkIfItsSalesDept(auth()->user()->department_id))
                    <div class="d-flex">
                        <button type="button" class="btn btn-sm btn-primary ml-auto m-3" title="Create Activity"  data-toggle="modal" data-target="#createRpeActivity">
                            <i class="ti-plus"></i>
                        </button>
                    </div>
                    @endif
                    <div class="table-responsive">
                        <div class="filter">
                            <label><input type="checkbox" class="status-filter" value="10" checked> Open</label>
                            <label><input type="checkbox" class="status-filter" value="20" checked> Closed</label>
                        </div>
                        <table class="table table-striped table-bordered table-hover table-detailed" id="activities_table" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>#</th>
                                    <th>Schedule</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($activities as $activity)
                                    <tr data-status="{{ $activity->Status }}">
                                        <td>
                                            <button type="button"  class="btn btn-sm btn-warning btn-outline"
                                                data-target="#editRpeActivity{{ $activity->id }}" data-toggle="modal" title='Edit Activity'>
                                                <i class="ti-pencil"></i>
                                            </button>   
                                            <button type="button" class="btn btn-sm btn-danger btn-outline" onclick="confirmDelete({{ $activity->id }}, 'activity')" title='Delete Activity'>
                                                <i class="ti-trash"></i>
                                            </button> 
                                            </td>
                                        <td>{{ optional($activity)->ActivityNumber }}</td>
                                        <td>
                                            {{ optional($activity)->ScheduleFrom ? optional($activity)->ScheduleFrom : '' }}
                                            -
                                            {{ optional($activity)->ScheduleTo ? optional($activity)->ScheduleTo : '' }}
                                        </td>
                                        <td>{{ optional($activity)->Title  }}</td>
                                        <td>
                                            @if($activity->Status == 10)
                                            Open
                                            @elseif($activity->Status == 20)
                                            Closed
                                            @else
                                            {{ $activity->Status }}
                                            @endif</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- @if(authCheckIfItsRnd(auth()->user()->department_id)) --}}
                <div class="tab-pane fade  @if(session('tab') == 'files') active show @endif" id="files" role="tabpanel" aria-labelledby="files-tab">
                    <div class="d-flex">
                        <button type="button" class="btn btn-sm btn-outline-primary ml-auto m-3" title="Upload File"  data-toggle="modal" data-target="#uploadFile">
                            New
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover table-detailed" id="files_table" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Actions</th>
                                    <th>Name</th>
                                    <th>File</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($requestEvaluation->rpeFiles as $fileupload)
                                @if(((auth()->user()->role->type == "IS" || auth()->user()->role->type == "LS") && $fileupload->IsConfidential == 0 ) || (auth()->user()->role->type == "RND"))
                                    <tr>
                                        <td>
                                            @if(authCheckIfItsRnd(auth()->user()->department_id))
                                                <button type="button"  class="btn btn-sm btn-warning btn-outline"
                                                    data-target="#editRpeFile{{ $fileupload->Id }}" data-toggle="modal" title='Edit fileupload'>
                                                    <i class="ti-pencil"></i>
                                                </button>   
                                                <button type="button" class="btn btn-sm btn-danger btn-outline" onclick="confirmDelete({{ $fileupload->Id }}, 'fileupload')" title='Delete fileupload'>
                                                    <i class="ti-trash"></i>
                                                </button> 
                                            @endif
                                        </td>
                                        <td>
                                            @if($fileupload->IsForReview)
                                                <i class="ti-pencil-alt text-danger"></i>
                                            @endif
                                            @if($fileupload->IsConfidential)
                                                <i class="mdi mdi-eye-off-outline text-danger"></i>
                                            @endif{{ $fileupload->Name }}</td>
                                        <td>
                                            {{-- @if ($fileupload->Path)
                                            @endif --}}
                                            <a href="{{ url($fileupload->Path) }}" target="_blank">View File</a>
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- @endif --}}
               
                <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover table-detailed" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Name</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orderedCombinedLogs as $combinedLog)
                                    <tr>
                                        <td>
                                            @if ($combinedLog->CreatedDate != null)
                                                {{ $combinedLog->CreatedDate }}
                                            @else   
                                                {{ $combinedLog->created_at }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($combinedLog->historyUser)
                                            {{$combinedLog->historyUser->full_name}}
                                            @elseif($combinedLog->user)
                                            {{$combinedLog->user->full_name}}
                                            @endif
                                        </td>
                                        {{-- <td>{{ $combinedLog->full_name }}</td> --}}
                                        <td>{{ $combinedLog->Details }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('product_evaluations.edit_sales')
@include('product_evaluations.close_rpe')
@include('product_evaluations.cancel_rpe')
@include('product_evaluations.update_rnd')
@include('product_evaluations.pause_rpe')

{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> --}}
<script src="{{ asset('js/sweetalert2.min.js') }}"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">

<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
<script>
    function confirmDelete(id, type) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
            let url;
            if (type === 'supplementary') {
                url = '{{ url('requestEvaluation/view/supp-delete') }}/' + id;
            } else if (type === 'personnel') {
                url = '{{ url('requestEvaluation/view/personnel-delete') }}/' + id;
            } else if (type === 'fileupload') {
                url = '{{ url('requestEvaluation/view/file-delete') }}/' + id;
            } else if (type === 'activity') {
                url = '{{ url('requestEvaluation/view/activity-delete') }}/' + id;
            }

            $.ajax({
                url: url,
                method: 'DELETE',
                data: {
                    '_token': '{{ csrf_token() }}'
                },
                success: function(response) {
                    Swal.fire(
                        'Deleted!',
                        'The record has been deleted.',
                        'success'
                    ).then(() => {
                        location.reload();
                    });
                },
                error: function() {
                    Swal.fire(
                        'Error!',
                        'Something went wrong.',
                        'error'
                    );
                }
            });
        }
        });
    }

    $(document).ready(function() {
        new DataTable('.table-detailed', {
            destroy: false,
            pageLength: 10,
            paging: true,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'excel'
            ],
            // columnDefs: [{
            //     "defaultContent": "-",
            //     "targets": "_all"
            // }],
            ordering: false
        });

        $(".closeRpe").on('click', function() {
            var rpeId = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "You want to close this request!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "{{ url('CloseRpe') }}/" + rpeId,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(function() {
                                location.reload();
                            });
                        }
                    });
                }
            });
        });

        $(".cancelRpe").on('click', function() {
            var rpeId = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "You want to cancel this request!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "{{ url('CancelRpe') }}/" + rpeId,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(function() {
                                location.reload();
                            });
                        }
                    });
                }
            });
        });

        // $(".editBtn").on('click', function() {
        //     var secondarySales = $(this).data('secondarysales');
        //     var primarySales = $('[name="PrimarySalesPersonId"]').val();

        //     refreshSecondaryApprovers(primarySales,secondarySales)
        // })
        // $('[name="PrimarySalesPersonId"]').on('change', function() {
        //     var primarySales = $(this).val();

        //     refreshSecondaryApproversv2(primarySales)
        // })
        // function refreshSecondaryApprovers(primarySales,secondarySales)
        // {
        //     $.ajax({
        //         type: "POST",
        //         url: "{{url('refresh_rpe_secondary_persons')}}",
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         data: {
        //             ps: primarySales,
        //         },
        //         success: function(data)
        //         {
        //             setTimeout(() => {
        //                 $('[name="SecondarySalesPersonId"]').html(data) 
        //                 $('[name="SecondarySalesPersonId"]').val(secondarySales) 
        //             }, 500);
        //         }
        //     })
        // }
        // function refreshSecondaryApproversv2(primarySales)
        // {
        //     $.ajax({
        //         type: "POST",
        //         url: "{{url('refresh_rpe_secondary_persons')}}",
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         data: {
        //             ps: primarySales,
        //         },
        //         success: function(data)
        //         {
        //             setTimeout(() => {
        //                 $('[name="SecondarySalesPersonId"]').html(data) 
        //             }, 500);
        //         }
        //     })
        // }

        $('.openBtn').on('click', function() {
            var form = $(this).closest('form')

            Swal.fire({
                title: "Are you sure?",
                // text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Open"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit()
                }
            });
        })

        $('.acceptBtn').on('click', function() {
            var form = $(this).closest('form')

            Swal.fire({
                title: "Are you sure?",
                // text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Approved"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit()
                }
            });
        })

        $('.receivedBtn').on('click', function() {
            var form = $(this).closest('form')

            Swal.fire({
                title: "Are you sure?",
                // text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Received"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit()
                }
            });
        })

        $('.startBtn').on('click', function() {
            var form = $(this).closest('form')

            Swal.fire({
                title: "Are you sure?",
                // text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Start"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit()
                }
            });
        })

        $('.continueBtn').on('click', function() {
            var form = $(this).closest('form')

            Swal.fire({
                title: "Are you sure?",
                // text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Continue"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit()
                }
            });
        })

        $('.initialReviewBtn').on('click', function() {
            var form = $(this).closest('form')

            Swal.fire({
                title: "Are you sure?",
                // text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Submit"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit()
                }
            });
        })

        $('.finalReviewBtn').on('click', function() {
            var form = $(this).closest('form')

            Swal.fire({
                title: "Are you sure?",
                // text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Submit"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit()
                }
            });
        })

        $('.completeBtn').on('click', function() {
            var form = $(this).closest('form')

            Swal.fire({
                title: "Are you sure?",
                // text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Complete"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit()
                }
            });
        })

        $('.returnToRnd').on('click', function() {
            var form = $(this).closest('form')

            Swal.fire({
                title: "Are you sure?",
                // text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Return to RND"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit()
                }
            });
        })

        // $(".returnToSales").on('click', function() {
        //     var rpeId = $(this).data('id');

        //     Swal.fire({
        //         title: 'Are you sure?',
        //         text: "You want to return this request!",
        //         icon: 'warning',
        //         showCancelButton: true,
        //         confirmButtonColor: '#3085d6',
        //         cancelButtonColor: '#d33',
        //         confirmButtonText: 'Yes',
        //         reverseButtons: true
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             $.ajax({
        //                 type: "POST",
        //                 url: "{{ url('ReturnToSales_rpe') }}/" + rpeId,
        //                 headers: {
        //                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //                 },
        //                 success: function(response) {
        //                     Swal.fire({
        //                         icon: 'success',
        //                         title: 'Success!',
        //                         text: response.message,
        //                         showConfirmButton: false,
        //                         timer: 1500
        //                     }).then(function() {
        //                         location.reload();
        //                     });
        //                 }
        //             });
        //         }
        //     });
        // });

        $('.salesAccept').on('click', function() {
            var form = $(this).closest('form')

            Swal.fire({
                title: "Are you sure?",
                // text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Accept"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit()
                }
            });
        })

        $('.deleteFilesBtn').on('click', function() {
            var id = $(this).data('id');
            
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Delete"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "DELETE",
                        url: '{{url('requestEvaluation/view/file-delete')}}/' + id,
                        data: {
                            id: id
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function()
                        {
                            Swal.fire({
                                title: "Successfully Deleted",
                                icon: "success"
                            }).then(() => {
                                location.reload();
                            })
                        }
                    })
                }
            });
        })
    });

//     document.addEventListener('DOMContentLoaded', function() {
//     const filters = document.querySelectorAll('.status-filter');

//     filters.forEach(filter => {
//         filter.addEventListener('change', filterTable);
//     });

//     function filterTable() {
//         const selectedStatuses = Array.from(filters)
//             .filter(filter => filter.checked)
//             .map(filter => filter.value);

//         document.querySelectorAll('#activities_table tbody tr').forEach(row => {
//             const status = row.getAttribute('data-status');
//             if (selectedStatuses.includes(status)) {
//                 row.style.display = '';
//             } else {
//                 row.style.display = 'none';
//             }
//         });
//     }
//     filterTable();
// });

    </script>
@foreach ($requestEvaluation->salesRpeFiles as $file)        
@include('product_evaluations.edit_sales_files')
@endforeach
@include('product_evaluations.create_supplementary')
@include('product_evaluations.assign_personnel')
@include('product_evaluations.return_to_sales')
{{-- @include('product_evaluations.create_activity') --}}
@include('product_evaluations.upload_rpe_file')
@include('product_evaluations.rpe_approval')
@include('product_evaluations.rpe_returnToSpecialist')


{{-- @foreach ($RpeSupplementary as $supplementary) --}}
{{-- @endforeach --}}
{{-- @foreach ($assignedPersonnel as $Personnel) --}}
{{-- @include('product_evaluations.edit_personnel') --}}
{{-- @endforeach --}}
@foreach ($activities as $activity)
@include('product_evaluations.edit_activity')
@endforeach
@foreach ($requestEvaluation->rpeFiles as $fileupload)
@include('product_evaluations.edit_files')
@endforeach
{{-- @include('sample_requests.upload_srf_file') --}}
{{-- @include('sample_requests.create_raw_materials') --}}
{{-- @foreach ($requestEvaluation as $srf)
    @include('sample_requests.srf_approval')
    @include('sample_requests.srf_receive')
    @include('sample_requests.srf_start')
    @include('sample_requests.srf_pause')
@endforeach --}}
@endsection

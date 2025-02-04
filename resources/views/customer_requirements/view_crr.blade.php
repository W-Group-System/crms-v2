@extends('layouts.header')
@section('content')

<div class="col-12 grid-margin stretch-card">
    <div class="card rounded-0 border border-1 border-primary">
        <div class="card-header rounded-0 font-weight-bold text-white bg-primary">
            Customer Requirement Details
        </div>
        <div class="card-body" style="overflow: auto;">
            @include('components.error')
            {{-- <h4 class="card-title d-flex justify-content-between align-items-center">View Client Details --}}
                <div align="right">
                    {{-- {{dd(url()->previous(), url()->current())}} --}}
                    {{-- @if(url()->previous() == url()->current())
                    <a href="{{ url('customer_requirement?open=10') }}" class="btn btn-md btn-outline-secondary">
                        <i class="icon-arrow-left"></i>&nbsp;Back
                    </a> 
                    @else
                    <a href="{{ url()->previous() ?: url('/customer_requirements') }}" class="btn btn-md btn-outline-secondary">
                        <i class="icon-arrow-left"></i>&nbsp;Back
                    </a> 
                    @endif --}}

                    @if(request('origin') == 'for_approval')
                    {{-- <a href="{{ url('/customer_requirement?progress=10&open=10') }}" class="btn btn-md btn-outline-secondary">
                        <i class="icon-arrow-left"></i>&nbsp;Back
                    </a> --}}
                    <a href="{{ url('view_for_approval_transaction') }}" class="btn btn-md btn-outline-secondary">
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
                    <a href="{{url('/customer_requirement?open=10') }}" class="btn btn-md btn-outline-secondary">
                        <i class="icon-arrow-left"></i>&nbsp;Back
                    </a>
                    @endif

                    @if(auth()->user()->role->type != "LS")
                    <a class="btn btn-outline-danger btn-icon-text" href="{{url('print_crr/'.$crr->id)}}" target="_blank">
                        <i class="ti ti-printer btn-icon-prepend"></i>
                        Print
                    </a>
                    @endif

                    @if(authCheckIfItsRndStaff(auth()->user()->role))
                    @if(rndPersonnel($crr->crrPersonnel, auth()->user()->id))
                        @if($crr->Progress != 57 && $crr->Progress != 60 && $crr->Progress != 81)
                            <button type="button" class="btn btn-outline-warning" data-toggle="modal" data-target="#updateCrr-{{$crr->id}}">
                                <i class="ti ti-pencil"></i>&nbsp;Update
                            </button>
                        @endif
                        
                        @if($crr->Progress == 35)
                        <form method="POST" action="{{url('start_crr/'.$crr->id)}}" class="d-inline-block" onsubmit="show()">
                            @csrf 

                            <button type="button" class="btn btn-outline-success startCrrBtn">
                                <i class="ti-control-play"></i>&nbsp; Start
                            </button>
                        </form>
                        @endif

                        @if($crr->Progress == 50)
                            <button type="button" class="btn btn-outline-success pauseCrrBtn" data-toggle="modal" data-target="#pauseModal{{$crr->id}}">
                                <i class="ti-control-pause"></i>&nbsp; Pause
                            </button>
                        @endif
    
                        @if($crr->Progress == 55)
                            <form method="POST" action="{{url('start_crr/'.$crr->id)}}" class="d-inline-block" onsubmit="show()">
                                @csrf 
    
                                <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#continueStatus{{$crr->id}}">
                                    <i class="ti-control-play"></i>&nbsp; Continue
                                </button>
                            </form>
                        @endif
                    @endif
                    @endif

                    @if(rndPersonnel($crr->crrPersonnel, auth()->user()->id))
                        @if($crr->Progress == 50)
                        <form method="POST" action="{{url('submit_crr/'.$crr->id)}}" class="d-inline-block" onsubmit="show()">
                            @csrf 

                            <button type="button" class="btn btn-outline-warning submitCrrBtn">
                                <i class="ti-check"></i>&nbsp; Submit
                            </button>
                        </form>
                        @endif
                    @endif

                    {{-- @if((auth()->user()->id == $crr->PrimarySalesPersonId || auth()->user()->user_id == $crr->PrimarySalesPersonId) || (auth()->user()->id == $crr->SecondarySalesPersonId || auth()->user()->user_id == $crr->SecondarySalesPersonId)) --}}
                    @if(checkIfInGroup($crr->PrimarySalesPersonId, auth()->user()->id))
                        @if($crr->Status == 10)
                            @if(rndPersonnel($crr->crrPersonnel, auth()->user()->id))
                            <button type="button" class="btn btn-outline-warning" data-toggle="modal" data-target="#updateCrr-{{$crr->id}}">
                                <i class="ti ti-pencil"></i>&nbsp;Update
                            </button>
                            @endif

                            @if(auth()->user()->department_id == 5 || auth()->user()->department_id == 38)
                            <button type="button" class="btn btn-outline-warning" id="update2Crr" data-toggle="modal" data-target="#editCrr{{$crr->id}}" data-secondarysales="{{$crr->SecondarySalesPersonId}}">
                                <i class="ti ti-pencil"></i>&nbsp;Update
                            </button>
                            @endif
                        @endif

                        @if(primarySalesApprover($crr->PrimarySalesPersonId, auth()->user()->id))
                            @if($crr->Progress == 10 && $crr->Status == 10)
                            <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#acceptModal{{$crr->id}}">
                                <i class="ti ti-check-box"></i>&nbsp;Approve
                            </button>
                            @endif
                        @endif

                        @if($crr->Status == 10 && $crr->Progress == 60)
                            <form method="POST" class="d-inline-block" action="{{url('return_to_rnd/'.$crr->id)}}" onsubmit="show()">
                                @csrf

                                <button type="button" class="btn btn-outline-info returnToRnd">
                                    <i class="ti ti-check-box"></i>&nbsp;Return to RND
                                </button>
                            </form>
                        @endif

                        @if($crr->Progress == 60)
                            <form method="POST" class="d-inline-block" action="{{url('sales_accepted/'.$crr->id)}}" onsubmit="show()">
                                @csrf

                                <button type="button" class="btn btn-outline-success salesAccepted">
                                    <i class="ti ti-check-box"></i>&nbsp;Accept
                                </button>
                            </form>
                        @endif
                        
                        @if(auth()->user()->id == $crr->PrimarySalesPersonId || auth()->user()->user_id == $crr->PrimarySalesPersonId)
                            @if($crr->Status == 30)
                                {{-- <form method="POST" class="d-inline-block" action="{{url('open_status/'.$crr->id)}}" onsubmit="show()">
                                    @csrf

                                </form> --}}
                                <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#openStatus{{$crr->id}}">
                                    <i class="mdi mdi-open-in-new"></i>&nbsp;Open
                                </button>
                            @endif
                            
                            @if($crr->Status == 10 && ($crr->Progress == 60 || $crr->Progress == 10 || $crr->Progress == 20 || $crr->Progress == 30))
                                <button type="button" class="btn btn-outline-primary" id="closeBtn" data-toggle="modal" data-target="#closeModal{{$crr->id}}">
                                    <i class="ti ti-close"></i>&nbsp;Close
                                </button>
                                <button type="button" class="btn btn-outline-danger" id="cancelBtn" data-toggle="modal" data-target="#cancelModal{{$crr->id}}">
                                    <i class="mdi mdi-cancel"></i>&nbsp;Cancel
                                </button>
                            @endif
                        @endif
                    @elseif(checkIfItsManagerOrSupervisor(auth()->user()->role) == "yes")
                        
                        @if($crr->Progress != 30 && $crr->Progress != 10 && $crr->Progress != 20)
                            @if(auth()->user()->role->type == $crr->RefCode)
                            <button type="button" class="btn btn-outline-warning" data-toggle="modal" data-target="#updateCrr-{{$crr->id}}">
                                <i class="ti ti-pencil"></i>&nbsp;Update
                            </button>
                            @endif
                        @endif

                        @if(authCheckIfItsRnd(auth()->user()->department_id))
                            @if($crr->Progress != 10 && $crr->Progress != 20 && $crr->Progress != 60 && $crr->ReturnToSales == 0)
                                <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#returnToSales{{$crr->id}}">
                                    <i class="ti-back-left">&nbsp;</i> Return to Sales
                                </button>
                            @endif
                        @endif

                        {{-- @if(authCheckIfItsSales(auth()->user()->department_id))
                            @if($crr->Status == 10)
                            <button type="button" class="btn btn-outline-warning" id="update2Crr" data-toggle="modal" data-target="#editCrr{{$crr->id}}" data-secondarysales="{{$crr->SecondarySalesPersonId}}">
                                <i class="ti ti-pencil"></i>&nbsp;Update
                            </button>
                            @endif
                        @endif --}}
                        
                        {{-- @if(checkIfItsApprover(auth()->user()->id, $crr->PrimarySalesPersonId, "CRR") == "yes") --}}
                        @if(primarySalesApprover($crr->PrimarySalesPersonId, auth()->user()->id))
                            @if($crr->Progress == 10 && $crr->Status == 10)
                            <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#acceptModal{{$crr->id}}">
                                <i class="ti ti-check-box"></i>&nbsp;Approve
                            </button>
                            @endif
                            @if(authCheckIfItsSales(auth()->user()->department_id))
    
                                @if($crr->Progress == 60 && $crr->Status == 10)
                                <form method="POST" class="d-inline-block" action="{{url('return_to_rnd/'.$crr->id)}}" onsubmit="show()">
                                    @csrf
    
                                    <button type="button" class="btn btn-outline-info returnToRnd">
                                        <i class="ti ti-check-box"></i>&nbsp;Return to RND
                                    </button>
                                </form>
                                @endif 
    
                                @if($crr->Progress == 60)
                                    <form method="POST" class="d-inline-block" action="{{url('sales_accepted/'.$crr->id)}}" onsubmit="show()">
                                        @csrf
    
                                        <button type="button" class="btn btn-outline-success salesAccepted">
                                            <i class="ti ti-check-box"></i>&nbsp;Accept
                                        </button>
                                    </form>
                                @endif
    
                                @if($crr->Status == 30)
                                {{-- <form method="POST" class="d-inline-block" action="{{url('open_status/'.$crr->id)}}" onsubmit="show()">
                                    @csrf
    
                                </form> --}}
                                <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#openStatus{{$crr->id}}">
                                    <i class="mdi mdi-open-in-new"></i>&nbsp;Open
                                </button>
                                @endif
    
                                @if($crr->Status == 10 && ($crr->Progress == 60 || $crr->Progress == 10 || $crr->Progress == 20 || $crr->Progress == 30))
                                    <button type="button" class="btn btn-outline-primary" id="closeBtn" data-toggle="modal" data-target="#closeModal{{$crr->id}}">
                                        <i class="ti ti-close"></i>&nbsp;Close
                                    </button>
                                    <button type="button" class="btn btn-outline-danger" id="cancelBtn" data-toggle="modal" data-target="#cancelModal{{$crr->id}}">
                                        <i class="mdi mdi-cancel"></i>&nbsp;Cancel
                                    </button>
                                @endif
                            
                            @endif
                        @endif
                        {{-- @endif --}}
                        
                        @if($crr->RefCode == auth()->user()->role->type)
                            @if($crr->Progress == 57 || $crr->Progress == 81)
                            {{-- <form action="{{url('start_crr/'.$crr->id)}}" method="post" class="d-inline-block" onsubmit="show()">
                                @csrf --}}

                                <button type="button" class="btn btn-outline-danger " data-toggle="modal" data-target="#return{{$crr->id}}">
                                    <i class="ti-back-left">&nbsp;</i> Return To Specialist
                                </button>
                            {{-- </form> --}}
                            @endif

                            @if($crr->Progress == 30)
                                <form action="{{url('rnd_received/'.$crr->id)}}" method="post" class="d-inline-block" onsubmit="show()">
                                    @csrf

                                    <button type="button" class="btn btn-outline-success receivedBtn">
                                        <i class="ti-bookmark">&nbsp;</i> Received
                                    </button>
                                </form>
                            @endif

                            @if($crr->Progress == 35)
                                <form method="POST" action="{{url('start_crr/'.$crr->id)}}" class="d-inline-block" onsubmit="show()">
                                    @csrf 

                                    <button type="button" class="btn btn-outline-success startCrrBtn">
                                        <i class="ti-control-play"></i>&nbsp; Start
                                    </button>
                                </form>
                            @endif

                            @if($crr->Progress == 50)
                                <button type="button" class="btn btn-outline-success pauseCrrBtn" data-toggle="modal" data-target="#pauseModal{{$crr->id}}">
                                    <i class="ti-control-pause"></i>&nbsp; Pause
                                </button>
                            @endif

                            @if($crr->Progress == 55)
                                <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#continueStatus{{$crr->id}}">
                                    <i class="ti-control-play"></i>&nbsp; Continue
                                </button>
                            @endif

                            @if($crr->Progress == 50)
                            <form method="POST" action="{{url('submit_crr/'.$crr->id)}}" class="d-inline-block" onsubmit="show()">
                                @csrf 

                                <button type="button" class="btn btn-outline-warning submitCrrBtn">
                                    <i class="ti-check"></i>&nbsp; Submit
                                </button>
                            </form>
                            @endif

                            @if($crr->Progress == 57)
                                <form method="POST" action="{{url('submit_final_crr/'.$crr->id)}}" class="d-inline-block" onsubmit="show()">
                                    @csrf 

                                    <button type="button" class="btn btn-outline-success submitFinalCrr">
                                        <i class="ti-check"></i>&nbsp; Submit
                                    </button>
                                </form>
                            @endif

                            @if($crr->Progress == 57 || $crr->Progress == 81)
                                <form method="POST" action="{{url('complete_crr/'.$crr->id)}}" class="d-inline-block" onsubmit="show()">
                                    @csrf 

                                    <button type="button" class="btn btn-outline-primary completeCrr">
                                        <i class="ti-pencil-alt"></i>&nbsp; Completed
                                    </button>
                                </form>
                            @endif
                        @endif
                    @endif
                </div>
            </h4>
            <div class="col-md-12">
                <label><strong>Customer Details</strong></label>
                <hr style="margin-top: 0px; color: black; border-top-color: black;">

                <div class="row mb-0">
                    <div class="col-sm-3 col-md-2">
                        <p class="mb-0 text-right"><b>Client :</b></p>
                    </div>
                    <div class="col-sm-3 col-md-10">
                        <p class="mb-0"><a href="{{url('view_client/'.$crr->ClientId)}}" >{{optional($crr->client)->Name}}</a></p>
                    </div>
                </div>
                <div class="row mb-0">
                    <div class="col-sm-3 col-md-2">
                        <p class="mb-0 text-right"><b>Client Trade Name :</b></p>
                    </div>
                    <div class="col-sm-3 col-md-10">
                        <p class="mb-0">
                            @if($crr->client)
                            {{$crr->client->TradeName}}
                            @endif
                        </p>
                    </div>
                </div>
                <div class="row mb-0">
                    <p class="col-sm-3 mb-0 col-md-2 text-right"><b>Region :</b></p>
                    <div class="col-sm-3 col-md-10">
                        <p class="mb-0">{{optional($crr->client->clientregion)->Name}}</p>
                    </div>
                </div>
                <div class="row mb-3">
                    <p class="col-sm-3 mb-0 col-md-2 text-right"><b>Country :</b></p>
                    <div class="col-sm-3 col-md-10">
                        <p class="mb-0">{{optional($crr->client->clientcountry)->Name}}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <label><strong>Request Details</strong></label>
                <hr style="margin-top: 0px; color: black; border-top-color: black;">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="col-sm-3 col-md-4 text-right">
                                <p class="mb-0"><b>CRR # :</b></p>
                            </div>
                            <div class="col-sm-3 col-md-8">
                                <p class="mb-0">{{$crr->CrrNumber}}</p>
                            </div>
                            <div class="col-sm-3 col-md-4 text-right">
                                <p class="mb-0"><b>Date Created :</b></p>
                            </div>
                            <div class="col-sm-3 col-md-8">
                                <p class="mb-0">{{date('Y-m-d H:i A', strtotime($crr->DateCreated))}}</p>
                            </div>
                            <div class="col-sm-3 col-md-4">
                                <p class="mb-0 text-right"><b>Priority :</b></p>
                            </div>
                            <div class="col-sm-3 col-md-8">
                                <p class="mb-0">
                                    @if($crr->Priority == 1)
                                    Low
                                    @elseif($crr->Priority == 3)
                                    Medium
                                    @elseif($crr->Priority == 5)
                                    High
                                    @endif
                                </p>
                            </div>
                            <div class="col-sm-3 col-md-4">
                                <p class="mb-0 text-right"><b>Due Date :</b></p>
                            </div>
                            <div class="col-sm-3 col-md-8">
                                <p class="mb-0">
                                    @if($crr->DueDate != null)
                                    {{date('Y-m-d', strtotime($crr->DueDate))}}
                                    @else
                                    N/A
                                    @endif
                                </p>
                            </div>
                            <div class="col-sm-3 col-md-4">
                                <p class="mb-0 text-right"><b>Application : </b></p>
                            </div>
                            <div class="col-sm-3 col-md-8">
                                <p class="mb-0">{{optional($crr->product_application)->Name}}</p>
                            </div>
                            <div class="col-sm-3 col-md-4">
                                <p class="mb-0 text-right"><b>Competitor Price :</b></p>
                            </div>
                            <div class="col-sm-3 col-md-8">
                                <p class="mb-0">{{$crr->CompetitorPrice}}</p>
                            </div>
                            <div class="col-sm-3 col-md-4">
                                <p class="text-right"><b>Sales Approved Date :</b></p>
                            </div>
                            <div class="col-sm-3 col-md-8">
                                <p>
                                    @if($crr->SalesApprovedDate != null)
                                    {{date('Y-m-d', strtotime($crr->SalesApprovedDate))}}
                                    @else
                                    <p>N/A</p>
                                    @endif
                                </p>
                            </div>
                            <div class="col-sm-3 col-md-4">
                                <p class="mb-0 text-right"><b>Details of Requirement :</b></p>
                            </div>
                            <div class="col-sm-3 col-md-8">
                                <p class="mb-0">{!! nl2br(e($crr->DetailsOfRequirement)) !!}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="col-sm-3 col-md-4 text-right">
                                <p class="mb-0"><b>Primary Sales Person :</b></p>
                            </div>
                            <div class="col-sm-3 col-md-8">
                                <p class="mb-0">
                                    @if($crr->primarySales)
                                    {{$crr->primarySales->full_name}}
                                    @elseif($crr->primarySalesById)
                                    {{$crr->primarySalesById->full_name}}
                                    @endif
                                </p>
                            </div>
                            <div class="col-sm-3 col-md-4">
                                <p class="mb-0 text-right"><b>Secondary Sales Person :</b></p>
                            </div>
                            <div class="col-sm-3 col-md-8">
                                <p class="mb-0">
                                    @if($crr->secondarySales)
                                    {{$crr->secondarySales->full_name}}
                                    @elseif($crr->secondarySalesById)
                                    {{$crr->secondarySalesById->full_name}}
                                    @endif
                                </p>
                            </div>
                            <div class="col-sm-3 col-md-4">
                                <p class="mb-0 text-right"><b>Status :</b></p>
                            </div>
                            <div class="col-sm-3 col-md-8">
                                <p class="mb-0">
                                    @if($crr->Status == 10)
                                    Open
                                    @elseif($crr->Status == 30)
                                    Closed
                                    @elseif($crr->Status == 50)
                                    Cancelled
                                    @endif
                                </p>
                            </div>
                            <div class="col-sm-3 col-md-4">
                                <p class="mb-0 text-right"><b>Progress :</b></p>
                            </div>
                            <div class="col-sm-3 col-md-8">
                                <p class="mb-0">{{optional($crr->progressStatus)->name}}</p>
                            </div>
                            <div class="col-sm-3 col-md-4">
                                <p class="mb-0 text-right"><b>Nature of Request :</b></p>
                            </div>
                            <div class="col-sm-3 col-md-8">
                                @if($crr->crrNature)
                                    @foreach ($crr->crrNature as $natureOfRequests)
                                        <p class="mb-0">{{optional($natureOfRequests->natureOfRequest)->Name}}</p>
                                    @endforeach
                                @endif
                            </div>
                            <div class="col-sm-3 col-md-4">
                                <p class="mb-0 text-right"><b>REF RPE Number :</b></p>
                            </div>
                            <div class="col-sm-3 col-md-8">
                                @php
                                    $id = linkToRpe($crr->RefRpeNumber);
                                @endphp
                                <p class="mb-0">
                                    <a href="{{url('product_evaluation/view/'.$id.'/'.$crr->CrrNumber)}}" target="_blank">
                                        {{$crr->RefRpeNumber}}
                                    </a>
                                </p>
                            </div>
                            <div class="col-sm-3 col-md-4">
                                <p class="mb-0 text-right"><b>Target Price :</b></p>
                            </div>
                            <div class="col-sm-3 col-md-8">
                                <p class="mb-0">{{$crr->TargetPrice}} {{optional($crr->price)->Name}}</p>
                            </div>
                            <div class="col-sm-3 col-md-4">
                                <p class="mb-0 text-right"><b>Ref Code :</b></p>
                            </div>
                            <div class="col-sm-3 col-md-8">
                                <p class="mb-0">{{$crr->RefCode}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <label><strong>Sales Files</strong></label>
                <hr style="margin-top: 0px; color: black; border-top-color: black;">

                @if($crr->crrFiles->isNotEmpty())
                @foreach ($crr->crrFiles->whereIn('UserType', ['IS', 'LS']) as $key=>$file)
                    <small>
                        <span>{{$key+1}}. </span>
                        <a href="{{url($file->Path)}}"  target="_blank">{{$file->Name}}</a>
                    </small>
                    &nbsp;
                    <a href="#" class="text-warning" data-toggle="modal" data-target="#editSalesFiles{{$file->Id}}">
                        <i class="ti-pencil-alt"></i>
                    </a>

                    <a href="#" class="text-danger deleteFilesBtn" data-id="{{$file->Id}}">
                        <i class="ti-trash"></i>
                    </a>

                    {{-- <form method="POST" action="{{url('delete_sales_files')}}" class="d-inline-block" id="deleteSalesForm" style="display: none;">
                        @csrf
                        
                    </form> --}}
                    <br>

                    @include('customer_requirements.edit_sales_files')
                @endforeach
                @else
                <p>N/A</p>
                @endif
            </div>
            <div class="col-md-12">
                <label><strong>Approver Remarks</strong></label>
                <hr style="margin-top: 0px; color: black; border-top-color: black;">
                <div class="row mb-3">
                    <div class="col-sm-2">
                        {{-- @if(auth()->user()->salesApproverById != null)
                            @foreach (auth()->user()->salesApproverById as $approver)
                                <p style="font-weight: bold;">{{$approver->salesApprover->full_name}} :</p>
                            @endforeach
                        @else
                            @foreach (auth()->user()->secondarySales as $approver)
                                <p style="font-weight: bold;">{{$approver->salesApprover->full_name}}</p>
                            @endforeach
                        @endif --}}
                        @if($crr->primarySalesById != null)
                            @foreach (optional($crr->primarySalesById)->salesApproverById as $approver)
                                <p style="font-weight: bold;" class="mb-0 text-right">{{$approver->salesApprover->full_name}} :</p>
                            @endforeach
                        {{-- @else  --}}
                        @endif
                    </div>
                    <div class="col-sm-3">
                        @if($crr->approver)
                            @php
                                $acceptRemarks = $crr->crrTransactionApprovals->sortByDesc('Id')->firstWhere('RemarksType', 'accept');
                            @endphp
                            @if($acceptRemarks != null)
                            <p class="mb-0">{{$acceptRemarks->Remarks}}</p>
                            @endif
                        @else
                            <p class="mb-0">No approver remarks yet</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <label><strong>Recommendation</strong></label>
                <hr style="margin-top: 0px; color: black; border-top-color: black;">

                <div class="row">
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="col-sm-3 col-md-4">
                                <p class="mb-0 text-right"><b>DDW Number : </b></p>
                            </div>
                            <div class="col-sm-3 col-md-8">
                                <p class="mb-0">@if($crr->DdwNumber != null){{$crr->DdwNumber}}@else N/A @endif</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3 col-md-4"><p class="mb-0 text-right"><b>Recommendation : </b></p></div>
                            <div class="col-sm-3 col-md-8">
                                <p class="mb-0">
                                    @php
                                        $crr_result = $crr->Recommendation;
                                        $pattern = '/\[(.*?)\]/';
                                    
                                        $crr_linked = preg_replace_callback($pattern, function($matches) {
                                            $code = $matches[1];
                                            $product = getProductIdByCode($code);
                                            if ($product != null)
                                            {
                                                if (auth()->user()->role->type == 'LS' || auth()->user()->role->type == 'IS')
                                                {
                                                    if ($product != null)
                                                    {
                                                        if ($product->status == 4)
                                                        {
                                                            return '<a href="'.url('view_product/'.$product->id).'">'.$matches[0].'</a>';
                                                        }
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
                                            }
                                            else
                                            {
                                                return $matches[0];
                                            }
                                        }, $crr_result);
                                    @endphp
                                    {!! nl2br($crr_linked ) !!}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="col-sm-3 col-md-4">
                                <p class="mb-0 text-right"><b>Date Received :</b></p>
                            </div>
                            <div class="col-sm-3 col-md-8">
                                <p class="mb-0">
                                    @if($crr->DateReceived != null)
                                    {{date('Y-m-d', strtotime($crr->DateReceived))}}
                                    @else
                                    N/A
                                    @endif
                                </p>
                            </div>
                            <div class="col-sm-3 col-md-4">
                                <p class="mb-0 text-right"><b>Date Completed :</b></p>
                            </div>
                            <div class="col-sm-3 col-md-8">
                                <p class="mb-0">
                                    @if($crr->DateCompleted != null)
                                    {{date('Y-m-d', strtotime($crr->DateCompleted))}}
                                    @else
                                    N/A
                                    @endif
                                </p>
                            </div>
                            <div class="col-sm-3 col-md-4">
                                <p class="mb-0 text-right"><b>Days Late : </b></p>
                            </div>
                            <div class="col-sm-3 col-md-8">
                                @php
                                    $today = new DateTime();
                                    $due_date = new DateTime($crr->DueDate);
                                    $diff = $due_date->diff($today);

                                    $days_late = 0;
                                    $s = "";
                                    if ($today > $due_date) 
                                    {
                                        $days_late = $diff->d;
                                        $s = $days_late > 1 ? 's' : '';
                                    } 
                                    
                                @endphp
                                <p class="mb-0">
                                    {{$days_late .' day' .$s}}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <ul class="nav nav-tabs viewTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link p-2 @if(session('tab') == 'supplementary_details' || session('tab') == null) active @endif" id="supplementary_details-tab" data-toggle="tab" href="#supplementary_details" role="tab" aria-controls="supplementary_details" aria-selected="true">Supplementary Details</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link p-2 @if(session('tab') == 'personnel') active @endif" id="assigned-tab" data-toggle="tab" href="#assigned" role="tab" aria-controls="assigned" aria-selected="false">Assigned R&D Personnel</a>
                </li>
                {{-- <li class="nav-item">
                    <a class="nav-link" id="activities-tab" data-toggle="tab" href="#activities" role="tab" aria-controls="activities" aria-selected="false">Activities</a>
                </li> --}}
                <li class="nav-item">
                    <a class="nav-link p-2 @if(session('tab') == 'files') active @endif" id="files-tab" data-toggle="tab" href="#files" role="tab" aria-controls="files" aria-selected="false">Files</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link p-2" id="approvals-tab" data-toggle="tab" href="#approvals" role="tab" aria-controls="approvals" aria-selected="false">Transaction Remarks</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link p-2" id="history-tab" data-toggle="tab" href="#history" role="tab" aria-controls="history" aria-selected="false">History Logs</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade @if(session('tab') == 'supplementary_details' || session('tab') == null) active show @endif" id="supplementary_details" role="tabpanel" aria-labelledby="supplementary_details">
                    @if(!checkIfItsSalesDept(auth()->user()->department_id))
                        @if($crr->Progress != 60 && $crr->Progress != 30)
                        <button type="button" class="btn btn-outline-primary btn-sm float-right mb-3" data-toggle="modal" data-target="#addSupplementary">
                            New
                        </button>
                        @include('customer_requirements.add_supplementary_details')
                        @endif
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered tables" width="100%">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>Date</th>
                                    <th>Name</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($crr->crrDetails as $details)
                                    <tr>
                                        <td>
                                            @if($crr->Progress != 60 && $crr->Progress != 30)
                                            <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editSupplementary{{$details->Id}}">
                                                <i class="ti-pencil"></i>
                                            </button>

                                            <form method="POST" class="d-inline-block" action="{{url('delete_supplementary/'.$details->Id)}}" onsubmit="show()">
                                                @csrf 

                                                <button type="button" class="btn btn-sm btn-danger deleteSupplementaryDetailsBtn">
                                                    <i class="ti-trash"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </td>
                                        <td>{{date('M d Y', strtotime($details->DateCreated))}}</td>
                                        <td>
                                            @if($details->userByUserId)
                                                {{$details->userByUserId->full_name}}
                                            @endif
    
                                            @if($details->userById)
                                                {{$details->userById->full_name}}
                                            @endif
                                        </td>
                                        <td>
                                            {{$details->DetailsOfRequirement}}
                                        </td>
                                    </tr>
                                    @include('customer_requirements.edit_supplementary_details')
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade @if(session('tab') == 'personnel') active show @endif" id="assigned" role="tabpanel" aria-labelledby="assigned">
                    @if(!checkIfItsSalesDept(auth()->user()->department_id))
                        @if(rndManager(auth()->user()->role))
                            @if($crr->Progress != 30 && $crr->Progress != 55 && $crr->Progress != 57 && $crr->Progress != 60 && $crr->Progress != 81 && rndManager(auth()->user()->role))
                            <button type="button" class="btn btn-outline-primary btn-sm float-right mb-3" data-toggle="modal" data-target="#addPersonnel">
                                New
                            </button>
                            @include('customer_requirements.new_personnel')
                            @endif
                        @endif
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered tables" width="100%">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>Name</th>
                                </tr>
                            </thead>
                            @foreach ($crr->crrPersonnel as $personnel)
                                <tbody>
                                    <tr>
                                        <td>
                                            @if(rndManager(auth()->user()->role) && $crr->Progress != 57 && $crr->Progress != 60 && $crr->Progress != 81 && $crr->Progress != 30)
                                            <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editPersonnel{{$personnel->Id}}">
                                                <i class="ti-pencil"></i>
                                            </button>

                                            <form method="POST" class="d-inline-block" action="{{url('delete_personnel/'.$personnel->Id)}}" onsubmit="show()">
                                                @csrf

                                                <button type="button" class="btn btn-danger btn-sm deletePersonnelButton">
                                                    <i class="ti-trash"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </td>
                                        <td>
                                            @if($personnel->crrPersonnelByUserId)
                                                {{$personnel->crrPersonnelByUserId->full_name}}
                                            @elseif($personnel->crrPersonnelById)
                                                {{$personnel->crrPersonnelById->full_name}}
                                            @endif
                                        </td>

                                        @include('customer_requirements.edit_personnel')
                                    </tr>
                                </tbody>
                            @endforeach
                        </table>
                    </div>
                </div>
                {{-- <div class="tab-pane fade" id="activities" role="tabpanel" aria-labelledby="activities">
                    <div class="form-group">
                        <label>Show : </label>
                        <label class="checkbox-inline">
                            <input name="open" class="activity_status" id="IsShowOpen" type="checkbox" value="10"> Open
                        </label>
                        <label class="checkbox-inline">
                            <input name="close" class="activity_status" id="IsShowClosed" type="checkbox" value="20"> Closed
                        </label>
                    </div>
                    
                    @if(checkIfItsSalesDept(auth()->user()->department_id))
                    <button class="btn btn-primary mb-3 float-right" data-toggle="modal" data-target="#addActivity">Add Activities</button>
                    @include('activities.new_activities')
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover table-bordered table-striped" id="activityTable" width="100%">
                            <thead>
                                <tr>
                                    <th>Actions</th>
                                    <th>#</th>
                                    <th>Schedule (Y-M-D)</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($crr->activities)
                                    @foreach ($crr->activities as $a)
                                        <tr>
                                            <td width="10%">
                                                @if(checkIfItsSalesDept(auth()->user()->department_id))
                                                <button type="button" class="btn btn-warning btn-sm edit_activity" data-toggle="modal" data-target="#editActivity-{{$a->id}}" data-clientid="{{$a->ClientId}}" data-clientcontact="{{$a->ClientContactId}}">
                                                    <i class="ti ti-pencil"></i>
                                                </button>

                                                <form method="POST" class="d-inline-block" action="{{url('delete_activity/'.$a->id)}}">
                                                    @csrf

                                                    <button type="button" class="btn btn-danger btn-sm deleteActivityBtn">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </form>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{url('view_activity/'.$a->id)}}" target="_blank">
                                                    {{$a->ActivityNumber}}
                                                </a>
                                            </td>
                                            <td>{{$a->ScheduleFrom}}</td>
                                            <td>{{$a->Title}}</td>
                                            <td>
                                                @if($a->Status == 10)
                                                <div class="badge badge-success">Open</div>
                                                @elseif($a->Status == 20)
                                                <div class="badge badge-danger">Close</div>
                                                @elseif($a->Status == 50)
                                                <div class="badge badge-warning">Cancelled</div>
                                                @endif
                                            </td>
                                        </tr>

                                        @include('activities.edit_activities')
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div> --}}
                <div class="tab-pane fade @if(session('tab') == 'files') active show @endif" id="files" role="tabpanel" aria-labelledby="files-tab">
                    {{-- @if(auth()->user()->role->type != 'IS' && auth()->user()->role->type != 'LS' && $crr->Progress != 30) --}}
                    @if(rndPersonnel($crr->crrPersonnel, auth()->user()->id) && $crr->Progress != 30 )
                    <div align="right">
                        <button type="button" class="btn btn-outline-primary btn-sm mb-3" data-toggle="modal" data-target="#addCrrFiles">
                            New
                        </button>
    
                        <button type="button" class="btn btn-outline-warning btn-sm mb-3" data-toggle="modal" data-target="#uploadMultipleFiles">
                            Upload Multiple Files
                        </button>
                    </div>
                    @endif

                    @include('customer_requirements.new_crr_files')

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover tables" width="100%">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>File</th>
                                </tr>
                            </thead>
                            @foreach ($crr->crrFiles->whereNotIn('UserType', ['IS', 'LS']) as $files)
                                @if(((auth()->user()->role->type == "IS" || auth()->user()->role->type == "LS") && $files->IsConfidential == 0 ) || (auth()->user()->role->type == "RND" || str_contains(auth()->user()->role->type, 'QCD')))
                                <tbody>
                                    <tr>
                                        <td width="10%" align="center">
                                            {{-- @if(checkIfHaveFiles(auth()->user()->role) == "yes" && $crr->Progress != 30) --}}
                                            @if(authCheckIfItsRnd(auth()->user()->department_id))
                                                <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editCrrFiles-{{$files->Id}}" title="Edit">
                                                    <i class="ti-pencil"></i>
                                                </button>
                                                @if(checkIfItsManagerOrSupervisor(auth()->user()->role) == "yes")
                                                <form method="POST" class="d-inline-block" action="{{url('delete_crr_file/'.$files->Id)}}" onsubmit="show()">
                                                    @csrf 
                                                    <button type="button" class="btn btn-sm btn-danger deleteBtn" title="Delete">
                                                        <i class="ti-trash"></i>
                                                    </button>
                                                </form>
                                                @endif
                                            @endif
                                        </td>
                                        <td width="70%">
                                            @if($files->IsForReview)
                                                <i class="ti-pencil-alt text-danger"></i>
                                            @endif
                                            @if($files->IsConfidential)
                                                <i class="mdi mdi-eye-off-outline text-danger"></i>
                                            @endif

                                            {{$files->Name}}
                                        </td>
                                        <td width="15%">
                                            @if($files->IsForReview == 1)
                                                <div class="badge badge-warning">In-Review</div>
                                            @elseif($files->IsForReview == 0)
                                                <div class="badge badge-success">Approved</div>
                                            @endif
                                        </td>
                                        <td width="5%">
                                            <a href="{{url($files->Path)}}" target="_blank">
                                                <i class="ti-file"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>

                                @include('customer_requirements.edit_crr_files')
                                @endif
                            @endforeach
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="approvals" role="tabpanel" aria-labelledby="approvals-tab">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover tables" width="100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Remarks</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($crr->crrTransactionApprovals as $transactionApprovals)
                                    <tr>
                                        <td>
                                            @if($transactionApprovals->userByUserId)
                                                {{$transactionApprovals->userByUserId->full_name}}
                                            @elseif($transactionApprovals->userById)
                                                {{$transactionApprovals->userById->full_name}}
                                            @endif
                                        </td>
                                        <td>
                                            @if($transactionApprovals->Status == 20)
                                                <div class="badge badge-danger">Closed</div>
                                            @elseif($transactionApprovals->Status == 0)
                                                <div class="badge badge-warning">Cancelled</div>
                                            @elseif($transactionApprovals->Status == 10)
                                                <div class="badge badge-success">Approved</div>
                                            @elseif($transactionApprovals->Status == 30)
                                                <div class="badge badge-info">Returned</div>
                                            @elseif($transactionApprovals->Status == 40)
                                                <div class="badge badge-primary">Open</div>
                                            @elseif($transactionApprovals->Status == 50)
                                                <div class="badge badge-secondary">Continue</div>
                                            @endif
                                        </td>
                                        <td>
                                            {!! nl2br($transactionApprovals->Remarks) !!}
                                        </td>
                                        <td>{{date('M d, Y', strtotime($transactionApprovals->CreatedDate))}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered table-striped tables" width="100%">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Name</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($crr->historyLogs as $logs)
                                    <tr>
                                        <td>{{date('M d, Y - h:i A', strtotime($logs->ActionDate))}}</td>
                                        <td>
                                            @if($logs->historyUser)
                                            {{$logs->historyUser->full_name}}
                                            @elseif($logs->user)
                                            {{$logs->user->full_name}}
                                            @endif
                                        </td>
                                        <td>{{$logs->Details}}</td>
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
@include('customer_requirements.update')
@include('customer_requirements.edit2_crr')
@include('customer_requirements.close_crr')
@include('customer_requirements.cancel_crr')
@include('customer_requirements.accept_crr')
@include('customer_requirements.pause_remarks')
@include('customer_requirements.return_to_sales_remarks')
@include('customer_requirements.open_remarks')
@include('customer_requirements.continue_remarks')
@include('customer_requirements.return_to_specialist')
{{-- @foreach ($crr->crrFiles as $files)
@endforeach --}}
@include('customer_requirements.add_all_files')

<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap4.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.bootstrap4.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
<script>
    function toggleConfidential(element)
    {
        var closestRow = element.closest('.fileNameRow')
        var confidentialInput = closestRow.querySelector('input[type="checkbox"][name="is_confidential[]"]')
        
        if (confidentialInput.checked)
        {
            var hiddenInput = closestRow.querySelector('input[type="hidden"][name="is_confidential[]"]').remove()
        }
        else
        {
            var hiddenInput = document.createElement('input')
            hiddenInput.type = 'hidden'
            hiddenInput.name = 'is_confidential[]'
            hiddenInput.value = 0

            closestRow.querySelector('.group-confidential').appendChild(hiddenInput)
        }
        
    }

    $(document).ready(function(){
        $('.tables').DataTable({
            destroy: false,
            processing: true,
            pageLength: 10,
            ordering: false
        });

        var activityTable = $('#activityTable').DataTable({
            destroy: false,
            processing: true,
            pageLength: 10,
            ordering: false
        });

        $('.natureRequestSelect').select2({
            width: "85%"
        });

        $('.addRow').on('click', function() {
            var newRow = `
                <div class="input-group mb-3">
                    <select class="form-control natureRequestSelect" name="NatureOfRequestId[]" required>
                        <option value="" disabled selected>Select Nature of Request</option>
                        @foreach($nature_requests as $nature_request)
                            <option value="{{ $nature_request->id }}">{{ $nature_request->Name }}</option>
                        @endforeach
                    </select>
                    <div class="input-group-append">
                        <button type="button" class="btn btn-danger removeRow">-</button>
                    </div>
                </div>
            `;

            $('.natureOfRequestContainer').append(newRow);
            $('.natureRequestSelect').select2();
        });

        $(document).on('click', '.removeRow', function() {
            if ($('.input-group').length > 1)
            {
                $(this).closest('.input-group').remove();
            }
        });

        $('[name="crr_file"]').on('change', function(e) {
            var filename = e.target.files[0].name;

            $(".crrFileName").val(filename);
        })

        $('.deleteBtn').on('click', function() {
            var form = $(this).closest('form');

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if(result.isConfirmed)
                {
                    form.submit()
                }
            });
        })

        $('.edit_activity').on('click', function() {
            var clientId = $(this).data('clientid');
            var clientContact = $(this).data('clientcontact');

            setTimeout(function() {
                $.ajax({
                    type: "POST",
                    url: "{{url('edit_client_contact')}}",
                    data: {
                        clientId: clientId
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res)
                    {
                        setTimeout(function() {
                            $('.ClientContactId').html(res)
                            $('.ClientContactId').val(clientContact);
                        }, 500)
                    }
                })
            }, 500)
        })
        
        $(".ClientId").on('change', function() {
            var client_id = $(this).val();
            console.log(client_id);
            
            $.ajax({
                type: "POST",
                url: "{{url('refresh_client_contact')}}",
                data: {
                    client_id: client_id
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res)
                {
                    setTimeout(function() {
                        $('.ClientContactId').html(res)
                    }, 500)
                }
            })
        })

        $('.edit_activity').on('click', function() {
            var clientId = $(this).data('clientid');
            var clientContact = $(this).data('clientcontact');
            
            setTimeout(function() {
                $.ajax({
                    type: "POST",
                    url: "{{url('edit_client_contact')}}",
                    data: {
                        clientId: clientId
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res)
                    {
                        setTimeout(function() {
                            $('.ClientContactId').html(res)
                            $('.ClientContactId').val(clientContact);
                        }, 500)
                    }
                })
            }, 500)
        })

        $('.deleteActivityBtn').on('click', function() {
            var form = $(this).closest('form')

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit()
                }
            });
        })

        $('.openBtn').on('click', function() {
            var form = $(this).closest('form');

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

        $('.receivedBtn').on('click', function() {
            var form = $(this).closest('form');

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

        $('.deleteSupplementaryDetailsBtn').on('click', function() {
            var form = $(this).closest('form');

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit()
                }
            });
        })

        $('.deletePersonnelButton').on('click', function() {
            var form = $(this).closest('form');

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit()
                }
            });
        })

        $('.startCrrBtn').on('click', function() {
            var form = $(this).closest('form');
            var labelBtn = $(this).data('label');
            
            Swal.fire({
                title: "Are you sure?",
                // text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: labelBtn != null ? labelBtn : "Start"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit()
                }
            });
        })

        $('.submitCrrBtn').on('click', function() {
            var form = $(this).closest('form');
            
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

        $('.submitFinalCrr').on('click', function() {
            var form = $(this).closest('form');
            
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
        
        // $('.returnBtn').on('click', function() {
        //     var form = $(this).closest('form');
            
        //     Swal.fire({
        //         title: "Are you sure?",
        //         // text: "You won't be able to revert this!",
        //         icon: "warning",
        //         showCancelButton: true,
        //         confirmButtonColor: "#3085d6",
        //         cancelButtonColor: "#d33",
        //         confirmButtonText: "Return"
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             form.submit()
        //         }
        //     });
            
        // })

        $('.completeCrr').on('click', function() {
            var form = $(this).closest('form');
            
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

        // $("#update2Crr").on('click', function() {
        //     var secondarySales = $(this).data('secondarysales');
        //     var primarySales = $("[name='PrimarySalesPersonId']").val()
            
        //     refreshSecondaryApprovers(secondarySales, primarySales)
        // })
        
        // $('[name="PrimarySalesPersonId"]').on('change', function() {
        //     var primarySales = $(this).val();

        //     refreshSecondaryApproversv2(primarySales)
        // })

        // function refreshSecondaryApprovers(secondarySales, primarySales)
        // {
        //     $.ajax({
        //         type: "POST",
        //         url: "{{url('refresh_crr_secondary_sales_person')}}",
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         data: {
        //             ps: primarySales
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

        // $(".returnToSalesBtn").on('click', function() {
        //     var form = $(this).closest('form')

        //     Swal.fire({
        //         title: "Are you sure?",
        //         // text: "You won't be able to revert this!",
        //         icon: "warning",
        //         showCancelButton: true,
        //         confirmButtonColor: "#3085d6",
        //         cancelButtonColor: "#d33",
        //         confirmButtonText: "Return"
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             form.submit()
        //         }
        //     });
        // })

        $('.returnToRnd').on('click', function() {
            var form = $(this).closest('form')

            Swal.fire({
                title: "Are you sure?",
                // text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Return to R&D"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit()
                }
            });
        })

        $('.salesAccepted').on('click', function() {
            var form = $(this).closest('form')

            Swal.fire({
                title: "Are you sure?",
                // text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sales Accepted"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit()
                }
            });
        })

        $('#addMultipleFilesBtn').on('click', function() {
            var newRow = `
                <div class="row fileNameRow">
                    <div class="col-lg-12 mb-3">
                        <label>Name :</label>
                        <input type="text" name="file_name[]" class="form-control crrFileName" placeholder="Enter name" required>
                    </div>
                    <div class="col-lg-12 mb-3">
                        <div class="form-group group-confidential">
                            <label>Is Confidential :</label>
                            <input type="checkbox" name="is_confidential[]" value="1" onchange="toggleConfidential(this)">
                            <input type="hidden" id="hiddenConfidential" name="is_confidential[]" value="0">
                        </div>
                    </div>
                    <div class="col-lg-12 mb-3">
                        <div class="form-group">
                            <label>Is For Review :</label>
                            <input type="checkbox" name="is_for_review[]">
                        </div>
                    </div>
                    <div class="col-lg-12 mb-3">
                        <label>Browser File :</label>
                        <input type="file" name="crr_file[]" class="form-control" required>
                    </div>
                </div>
            `

            $("#multipleFilesContainer").append(newRow);
        })

        $('#closeMultipleFilesBtn').on('click', function() {
            
            // $(this).closest('.col-md-6').remove()
            var row = $("#multipleFilesContainer").children()

            if (row.length > 1)
            {
                row.last().remove()
            }
        })

        $(document).on('change', '[name="crr_file[]"]', function(e) {
            var filename = e.target.files[0].name;
            // console.log($(this).closest('fileNameRow'));
            
            $(this).closest('.fileNameRow').find('.crrFileName').val(filename);
            // $(".crrFileName").val(filename);
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
                        type: "POST",
                        url: "{{url('delete_sales_files')}}",
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
    })
</script>
@endsection

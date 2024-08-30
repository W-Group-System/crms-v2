@extends('layouts.header')
@section('content')

<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">View Client Details
                <div align="right">
                    <a href="{{ url()->previous() ?: url('/customer_requirements') }}" class="btn btn-md btn-secondary">
                        <i class="icon-arrow-left"></i>&nbsp;Back
                    </a>

                    @if(authCheckIfItsSales(auth()->user()->department_id))
                    <a class="btn btn-danger btn-icon-text" href="{{url('print_crr')}}" target="_blank">
                        <i class="ti ti-printer btn-icon-prepend"></i>
                        Print
                    </a>
                    @endif

                    @if(authCheckIfItsRndStaff(auth()->user()->role))
                        @if($crr->Progress != 57 && $crr->Progress != 60 && $crr->Progress != 81)
                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#updateCrr-{{$crr->id}}">
                            <i class="ti ti-pencil"></i>&nbsp;Update
                        </button>
                        @endif

                        @if(rndPersonnel($crr->crrPersonnel, auth()->user()->id))
                            @if($crr->Progress == 35)
                            <form method="POST" action="{{url('start_crr/'.$crr->id)}}" class="d-inline-block">
                                @csrf 

                                <button type="button" class="btn btn-success startCrrBtn">
                                    <i class="ti-control-play"></i>&nbsp; Start
                                </button>
                            </form>
                            @endif
                        @endif

                        @if($crr->Progress == 50)
                            <button type="button" class="btn btn-success pauseCrrBtn" data-toggle="modal" data-target="#pauseModal{{$crr->id}}">
                                <i class="ti-control-pause"></i>&nbsp; Pause
                            </button>
                        @endif

                        @if($crr->Progress == 55)
                            <form method="POST" action="{{url('start_crr/'.$crr->id)}}" class="d-inline-block">
                                @csrf 

                                <button type="button" class="btn btn-success startCrrBtn">
                                    <i class="ti-control-play"></i>&nbsp; Continue
                                </button>
                            </form>
                        @endif
                    @endif

                    @if(rndPersonnel($crr->crrPersonnel, auth()->user()->id))
                        @if($crr->Progress == 50)
                        <form method="POST" action="{{url('submit_crr/'.$crr->id)}}" class="d-inline-block">
                            @csrf 

                            <button type="button" class="btn btn-warning submitCrrBtn">
                                <i class="ti-check"></i>&nbsp; Submit
                            </button>
                        </form>
                        @endif
                    @endif

                    @if(auth()->user()->id == $crr->PrimarySalesPersonId || auth()->user()->user_id == $crr->PrimarySalesPersonId)
                        @if($crr->Status == 10)
                            @if(auth()->user()->department_id == 15 || auth()->user()->department_id == 42)
                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#updateCrr-{{$crr->id}}">
                                <i class="ti ti-pencil"></i>&nbsp;Update
                            </button>
                            @endif

                            @if(auth()->user()->department_id == 5 || auth()->user()->department_id == 38)
                            <button type="button" class="btn btn-warning" id="update2Crr" data-toggle="modal" data-target="#editCrr{{$crr->id}}" data-secondarysales="{{$crr->SecondarySalesPersonId}}">
                                <i class="ti ti-pencil"></i>&nbsp;Update
                            </button>
                            @endif
                        @endif

                        @if($crr->Progress == 70 && $crr->Status == 10)
                        <form method="POST" class="d-inline-block" action="{{url('return_to_rnd/'.$crr->id)}}">
                            @csrf

                            <button type="button" class="btn btn-info returnToRnd">
                                <i class="ti ti-check-box"></i>&nbsp;Return to RND
                            </button>
                        </form>
                        @endif

                        @if($crr->Status == 10)
                        @if(checkRolesIfHaveApprove('Customer Requirement', auth()->user()->department_id, auth()->user()->role_id) == "yes")
                            {{-- @if($crr->Progress == 60)
                            <button type="button" class="btn btn-info" data-toggle="modal">
                                <i class="ti ti-back-left"></i>&nbsp;Return
                            </button>
                            @endif --}}

                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#acceptModal{{$crr->id}}">
                                <i class="ti ti-check-box"></i>&nbsp;Approve
                            </button>
                        @endif
                        @endif

                        @if($crr->Progress == 60)
                        <form method="POST" class="d-inline-block" action="{{url('return_to_rnd/'.$crr->id)}}">
                            @csrf

                            <button type="button" class="btn btn-info returnToRnd">
                                <i class="ti ti-check-box"></i>&nbsp;Return to RND
                            </button>
                        </form>

                        <form method="POST" class="d-inline-block" action="{{url('sales_accepted/'.$crr->id)}}">
                            @csrf

                            <button type="button" class="btn btn-success salesAccepted">
                                <i class="ti ti-check-box"></i>&nbsp;Accept
                            </button>
                        </form>
                        @endif
                        
                        @if($crr->Status == 30)
                            <form method="POST" class="d-inline-block" action="{{url('open_status/'.$crr->id)}}">
                                @csrf

                                <button type="button" class="btn btn-success openBtn">
                                    <i class="mdi mdi-open-in-new"></i>&nbsp;Open
                                </button>
                            </form>
                        @endif
                        
                        @if($crr->Status == 10)
                            <button type="button" class="btn btn-primary" id="closeBtn" data-toggle="modal" data-target="#closeModal{{$crr->id}}">
                                <i class="ti ti-close"></i>&nbsp;Close
                            </button>
                            <button type="button" class="btn btn-danger" id="cancelBtn" data-toggle="modal" data-target="#cancelModal{{$crr->id}}">
                                <i class="mdi mdi-cancel"></i>&nbsp;Cancel
                            </button>
                        @endif
                    @elseif(checkIfItsManagerOrSupervisor(auth()->user()->role) == "yes")
                    
                        @if($crr->Progress != 30 && $crr->Progress != 10 && $crr->Progress != 20)
                            @if(auth()->user()->department_id == 15 || auth()->user()->department_id == 42)
                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#updateCrr-{{$crr->id}}">
                                <i class="ti ti-pencil"></i>&nbsp;Update
                            </button>
                            @endif
                        @else
                            @if(authCheckIfItsRnd(auth()->user()->department_id))
                                @if($crr->Progress != 10 && $crr->Progress != 20 && $crr->Progress != 60)
                                <form action="{{url('return_to_sales/'.$crr->id)}}" class="d-inline-block" method="POST">
                                    @csrf

                                    <button type="button" class="btn btn-info returnToSalesBtn">
                                        <i class="ti-back-left">&nbsp;</i> Return to Sales
                                    </button>
                                </form>
                                @endif
                            @endif
                        @endif

                        @if(authCheckIfItsSales(auth()->user()->department_id))
                            @if($crr->Status == 10)
                            <button type="button" class="btn btn-warning" id="update2Crr" data-toggle="modal" data-target="#editCrr{{$crr->id}}" data-secondarysales="{{$crr->SecondarySalesPersonId}}">
                                <i class="ti ti-pencil"></i>&nbsp;Update
                            </button>
                            @endif
                        @endif
                        
                        @if(checkIfItsApprover(auth()->user()->id, $crr->PrimarySalesPersonId, "CRR") == "yes" && $crr->Progress == 10)
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#acceptModal{{$crr->id}}">
                                <i class="ti ti-check-box"></i>&nbsp;Accept
                            </button>
                        @endif
                        
                        @if(authCheckIfItsSales(auth()->user()->department_id))

                            @if(($crr->Progress == 60 || $crr->Progress == 70) && $crr->Status == 10)
                            <form method="POST" class="d-inline-block" action="{{url('return_to_rnd/'.$crr->id)}}">
                                @csrf

                                <button type="button" class="btn btn-info returnToRnd">
                                    <i class="ti ti-check-box"></i>&nbsp;Return to RND
                                </button>
                            </form>
                            @endif 

                            @if($crr->Progress == 60)
                                <form method="POST" class="d-inline-block" action="{{url('sales_accepted/'.$crr->id)}}">
                                    @csrf

                                    <button type="button" class="btn btn-success salesAccepted">
                                        <i class="ti ti-check-box"></i>&nbsp;Accept
                                    </button>
                                </form>
                            @endif

                            @if($crr->Status == 30)
                            <form method="POST" class="d-inline-block" action="{{url('open_status/'.$crr->id)}}">
                                @csrf

                                <button type="button" class="btn btn-success openBtn">
                                    <i class="mdi mdi-open-in-new"></i>&nbsp;Open
                                </button>
                            </form>
                            @endif

                            @if($crr->Status == 10)
                            <button type="button" class="btn btn-primary" id="closeBtn" data-toggle="modal" data-target="#closeModal{{$crr->id}}">
                                <i class="ti ti-close"></i>&nbsp;Close
                            </button>
                            <button type="button" class="btn btn-danger" id="cancelBtn" data-toggle="modal" data-target="#cancelModal{{$crr->id}}">
                                <i class="mdi mdi-cancel"></i>&nbsp;Cancel
                            </button>
                            @endif
                        @else
                            @if($crr->Progress == 55 || $crr->Progress == 57 || $crr->Progress == 81)
                            <form action="{{url('start_crr/'.$crr->id)}}" method="post" class="d-inline-block">
                                @csrf

                                <button type="button" class="btn btn-danger returnBtn">
                                    <i class="ti-back-left">&nbsp;</i> Return To Specialist
                                </button>
                            </form>
                            @endif

                            @if($crr->Progress == 30)
                                <form action="{{url('rnd_received/'.$crr->id)}}" method="post" class="d-inline-block">
                                    @csrf

                                    <button type="button" class="btn btn-success receivedBtn">
                                        <i class="ti-bookmark">&nbsp;</i> Received
                                    </button>
                                </form>
                            @endif

                            @if($crr->Progress == 35)
                                <form method="POST" action="{{url('start_crr/'.$crr->id)}}" class="d-inline-block">
                                    @csrf 

                                    <button type="button" class="btn btn-success startCrrBtn">
                                        <i class="ti-control-play"></i>&nbsp; Start
                                    </button>
                                </form>
                            @endif

                            @if($crr->Progress == 50)
                                <button type="button" class="btn btn-success pauseCrrBtn" data-toggle="modal" data-target="#pauseModal{{$crr->id}}">
                                    <i class="ti-control-pause"></i>&nbsp; Pause
                                </button>
                            @endif

                            @if($crr->Progress == 55)
                                <form method="POST" action="{{url('start_crr/'.$crr->id)}}" class="d-inline-block">
                                    @csrf 

                                    <button type="button" class="btn btn-success startCrrBtn" data-label="Continue">
                                        <i class="ti-control-play"></i>&nbsp; Continue
                                    </button>
                                </form>
                            @endif

                            @if($crr->Progress == 57)
                                <form method="POST" action="{{url('submit_final_crr/'.$crr->id)}}" class="d-inline-block">
                                    @csrf 

                                    <button type="button" class="btn btn-success submitFinalCrr">
                                        <i class="ti-check"></i>&nbsp; Submit
                                    </button>
                                </form>
                            @endif

                            @if($crr->Progress == 57 || $crr->Progress == 81)
                                <form method="POST" action="{{url('complete_crr/'.$crr->id)}}" class="d-inline-block">
                                    @csrf 

                                    <button type="button" class="btn btn-primary completeCrr">
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

                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>Client :</b></label>
                    <div class="col-sm-3">
                        <label>
                            <a href="{{url('view_client/'.$crr->ClientId)}}" >{{optional($crr->client)->Name}}</a>
                        </label>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>Client Trade Name :</b></label>
                    <div class="col-sm-3">
                        <label>
                            @if($crr->client)
                            {{$crr->client->TradeName}}
                            @endif
                        </label>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>Region :</b></label>
                    <div class="col-sm-3">
                        <label>{{optional($crr->client->clientregion)->Name}}</label>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>Country :</b></label>
                    <div class="col-sm-3">
                        <label>{{optional($crr->client->clientcountry)->Name}}</label>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <label><strong>Request Details</strong></label>
                <hr style="margin-top: 0px; color: black; border-top-color: black;">

                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>CRR # :</b></label>
                    <div class="col-sm-3">
                        <label>{{$crr->CrrNumber}}</label>
                    </div>
                    <label class="col-sm-3 col-form-label"><b>Primary Sales Person :</b></label>
                    <div class="col-sm-3">
                        <label>
                            @if($crr->primarySales)
                            {{$crr->primarySales->full_name}}
                            @elseif($crr->primarySalesById)
                            {{$crr->primarySalesById->full_name}}
                            @endif
                        </label>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>Date Created :</b></label>
                    <div class="col-sm-3">
                        <label>{{date('M d Y H:i A', strtotime($crr->DateCreated))}}</label>
                    </div>
                    <label class="col-sm-3 col-form-label"><b>Secondary Sales Person :</b></label>
                    <div class="col-sm-3">
                        <label>
                            @if($crr->secondarySales)
                            {{$crr->secondarySales->full_name}}
                            @elseif($crr->secondarySalesById)
                            {{$crr->secondarySalesById->full_name}}
                            @endif
                        </label>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>Priority :</b></label>
                    <div class="col-sm-3">
                        <label>
                            @if($crr->Priority == 1)
                            Low
                            @elseif($crr->Priority == 3)
                            Medium
                            @elseif($crr->Priority == 5)
                            High
                            @endif
                        </label>
                    </div>
                    <label class="col-sm-3 col-form-label"><b>Status :</b></label>
                    <div class="col-sm-3">
                        <label>
                            @if($crr->Status == 10)
                            Open
                            @elseif($crr->Status == 30)
                            Closed
                            @elseif($crr->Status == 50)
                            Cancelled
                            @endif
                        </label>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>Due Date :</b></label>
                    <div class="col-sm-3">
                        <label>{{date('M d Y', strtotime($crr->DueDate))}}</label>
                    </div>
                    <label class="col-sm-3 col-form-label"><b>Progress :</b></label>
                    <div class="col-sm-3">
                        <label>{{optional($crr->progressStatus)->name}}</label>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>Application : </b></label>
                    <div class="col-sm-3">
                        <label>{{optional($crr->product_application)->Name}}</label>
                    </div>
                    <label class="col-sm-3 col-form-label"><b>Nature of Request :</b></label>
                    <div class="col-sm-3">
                        @if($crr->crrNature)
                        @foreach ($crr->crrNature as $natureOfRequests)
                            <label>{{optional($natureOfRequests->natureOfRequest)->Name}}</label> <br>
                        @endforeach
                        @endif
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>Competitor :</b></label>
                    <div class="col-sm-3">
                        <label>{{$crr->Competitor}}</label>
                    </div>
                    <label class="col-sm-3 col-form-label"><b>REF CRR Number :</b></label>
                    <div class="col-sm-3">
                        @php
                            $id = linkToCrr($crr->RefCrrNumber);
                        @endphp
                        <label>
                            <a href="{{url('view_customer_requirement/'.$id)}}">
                                {{$crr->RefCrrNumber}}
                            </a>
                        </label>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>Competitor Price :</b></label>
                    <div class="col-sm-3">
                        <label>{{$crr->CompetitorPrice}}</label>
                    </div>
                    <label class="col-sm-3 col-form-label"><b>REF RPE Number :</b></label>
                    <div class="col-sm-3">
                        @php
                            $id = linkToRpe($crr->RefRpeNumber);
                        @endphp
                        <label>
                            <a href="{{url('product_evaluation/view/'.$id)}}" target="_blank">
                                {{$crr->RefRpeNumber}}
                            </a>
                        </label>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>Potential Volume :</b></label>
                    <div class="col-sm-3">
                        <label>{{$crr->PotentialVolume}}</label>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>Target Price :</b></label>
                    <div class="col-sm-3">
                        <label>{{$crr->TargetPrice}}</label>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>Details of Requirement :</b></label>
                    <div class="col-sm-3">
                        <label>{{$crr->DetailsOfRequirement}}</label>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <label><strong>Approver Remarks</strong></label>
                <hr style="margin-top: 0px; color: black; border-top-color: black;">
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label">
                        {{-- @if($crr->crrTransactionApprovals->isEmpty())
                            @if($crr->approver)
                            <b>{{$crr->approver->full_name}} :</b> 
                            @else
                            <p>No approver remarks yet</p>
                            @endif
                        @else
                        @foreach ($crr->crrTransactionApprovals as $transactionApproval)
                            <b>
                                @if($transactionApproval->userByUserId)
                                    {{$transactionApproval->userByUserId->full_name}} :<br>
                                @elseif($transactionApproval->userById)
                                    {{$transactionApproval->userById->full_name}} :<br>
                                @endif
                            </b> 
                        @endforeach
                        @endif --}}
                        @if($crr->approver)
                            @php
                                $acceptRemarks = $crr->crrTransactionApprovals->sortByDesc('Id')->firstWhere('RemarksType', 'accept');
                            @endphp
                            <b>{{$crr->approver->full_name}} :</b> {{$acceptRemarks->Remarks}}
                        @else
                            <p>No approver remarks yet</p>
                        @endif
                    </label>
                </div>
            </div>
            <div class="col-md-12">
                <label><strong>Recommendation</strong></label>
                <hr style="margin-top: 0px; color: black; border-top-color: black;">

                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>DDW Number : </b></label>
                    <div class="col-sm-3">
                        <label>@if($crr->DdwNumber != null){{$crr->DdwNumber}}@else N/A @endif</label>
                    </div>
                    <label class="col-sm-3 col-form-label"><b>Date Received :</b></label>
                    <div class="col-sm-3">
                        <label>
                            @if($crr->DateReceived != null)
                            {{date('M d Y', strtotime($crr->DateReceived))}}
                            @else
                            N/A
                            @endif
                        </label>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>Recommendation : </b></label>
                    <div class="col-sm-3">
                        <label>
                            @if($crr->Recommendation != null)
                            {!! nl2br(e($crr->Recommendation)) !!}
                            @else
                            N/A
                            @endif
                        </label>
                    </div>
                    <label class="col-sm-3 col-form-label"><b>Date Completed :</b></label>
                    <div class="col-sm-3">
                        <label>
                            @if($crr->DateCompleted != null)
                            {{date('M d Y', strtotime($crr->DateCompleted))}}
                            @else
                            N/A
                            @endif
                        </label>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>Days Late : </b></label>
                    <div class="col-sm-3">
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
                        <label>
                            {{$days_late .' day' .$s}}
                        </label>
                    </div>
                </div>
            </div>
            <ul class="nav nav-tabs viewTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="supplementary_details-tab" data-toggle="tab" href="#supplementary_details" role="tab" aria-controls="supplementary_details" aria-selected="true">Supplementary Details</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="assigned-tab" data-toggle="tab" href="#assigned" role="tab" aria-controls="assigned" aria-selected="false">Assigned R&D Personnel</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="activities-tab" data-toggle="tab" href="#activities" role="tab" aria-controls="activities" aria-selected="false">Activities</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="files-tab" data-toggle="tab" href="#files" role="tab" aria-controls="files" aria-selected="false">Files</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " id="approvals-tab" data-toggle="tab" href="#approvals" role="tab" aria-controls="approvals" aria-selected="false">Transaction Remarks</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " id="history-tab" data-toggle="tab" href="#history" role="tab" aria-controls="history" aria-selected="false">History Logs</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="supplementary_details" role="tabpanel" aria-labelledby="supplementary_details">
                    @if(!checkIfItsSalesDept(auth()->user()->department_id))
                        @if($crr->Progress != 60)
                        <button type="button" class="btn btn-primary float-right mb-3" data-toggle="modal" data-target="#addSupplementary">
                            Add Supplementary Details
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
                                            @if($crr->Progress != 60)
                                            <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editSupplementary{{$details->Id}}">
                                                <i class="ti-pencil"></i>
                                            </button>

                                            <form method="POST" class="d-inline-block" action="{{url('delete_supplementary/'.$details->Id)}}">
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
                <div class="tab-pane fade " id="assigned" role="tabpanel" aria-labelledby="assigned">
                    @if(!checkIfItsSalesDept(auth()->user()->department_id))
                        @if($crr->Progress != 55 && $crr->Progress != 57 && $crr->Progress != 60 && $crr->Progress != 81 && rndManager(auth()->user()->role))
                        <button type="button" class="btn btn-primary float-right mb-3" data-toggle="modal" data-target="#addPersonnel">
                            Add Personnel
                        </button>
                        @include('customer_requirements.new_personnel')
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
                                            @if(rndManager(auth()->user()->role) && $crr->Progress != 57 && $crr->Progress != 60 && $crr->Progress != 81)
                                            <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editPersonnel{{$personnel->Id}}">
                                                <i class="ti-pencil"></i>
                                            </button>

                                            <form method="POST" class="d-inline-block" action="{{url('delete_personnel/'.$personnel->Id)}}">
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
                <div class="tab-pane fade" id="activities" role="tabpanel" aria-labelledby="activities">
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
                </div>
                <div class="tab-pane fade " id="files" role="tabpanel" aria-labelledby="files-tab">
                    @if(checkIfHaveFiles(auth()->user()->role) == "yes")
                    <button type="button" class="btn btn-primary mb-3 float-right" data-toggle="modal" data-target="#addCrrFiles">
                        Add Customer Requirement Files
                    </button>
                    @endif

                    @include('customer_requirements.new_crr_files')

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover tables" width="100%">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>Name</th>
                                    <th>File</th>
                                </tr>
                            </thead>
                            @foreach ($crr->crrFiles as $files)
                                <tbody>
                                    <tr>
                                        <td>
                                            @if(checkIfHaveFiles(auth()->user()->role) == "yes")
                                            <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editCrrFiles-{{$files->Id}}" title="Edit">
                                                <i class="ti-pencil"></i>
                                            </button>

                                            <form method="POST" class="d-inline-block" action="{{url('delete_crr_file/'.$files->Id)}}">
                                                @csrf 

                                                <button type="button" class="btn btn-sm btn-danger deleteBtn" title="Delete">
                                                    <i class="ti-trash"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </td>
                                        <td>
                                            @if($files->IsForReview)
                                                <i class="ti-pencil-alt text-danger"></i>
                                            @endif
                                            @if($files->IsConfidential)
                                                <i class="mdi mdi-eye-off-outline text-danger"></i>
                                            @endif

                                            {{$files->Name}}
                                        </td>
                                        <td>
                                            <a href="{{url($files->Path)}}" target="_blank" class="btn btn-sm btn-info" title="View a file">
                                                <i class="ti-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>

                                @include('customer_requirements.edit_crr_files')
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

<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap4.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.bootstrap4.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
<script>
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
            width: "92%"
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
            $(this).closest('.input-group').remove();
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
        
        $('.returnBtn').on('click', function() {
            var form = $(this).closest('form');
            
            Swal.fire({
                title: "Are you sure?",
                // text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Return"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit()
                }
            });
        })

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

        $("#update2Crr").on('click', function() {
            var secondarySales = $(this).data('secondarysales');
            var primarySales = $("[name='PrimarySalesPersonId']").val()

            refreshSecondaryApprovers(secondarySales, primarySales)
        })

        function refreshSecondaryApprovers(secondarySales, primarySales)
        {
            $.ajax({
                type: "POST",
                url: "{{url('refresh_user_approvers')}}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    ps: primarySales
                },
                success: function(data)
                {
                    setTimeout(() => {
                        $('[name="SecondarySalesPersonId"]').html(data)
                        $('[name="SecondarySalesPersonId"]').val(secondarySales)
                    }, 500);
                }
            })
        }

        $(".returnToSalesBtn").on('click', function() {
            var form = $(this).closest('form')

            Swal.fire({
                title: "Are you sure?",
                // text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Return"
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
    })
</script>
@endsection

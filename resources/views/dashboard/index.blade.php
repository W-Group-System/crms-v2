@extends('layouts.header')
@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin">
            <h3 class="font-weight-bold">Welcome back,&nbsp;{{auth()->user()->full_name}}!</h3>
            <h4 class="font-weight-normal mb-0" style="color: #7d7373">{{ date('l, d F') }} | <p style="font-size: 1.125rem;display: contents;" id="demo"></p></h4>
        </div>
    </div>
    @if(optional($role)->type == 'IS' || optional($role)->type == 'LS')
        <div class="row">
            <div class="col-md-4 grid-margin">
                @if ((optional($role)->name == 'Staff L2' || optional($role)->name == 'Department Admin') &&
                (optional($role)->type == 'IS' || optional($role)->type == 'LS'))
                    <div class="card mb-2">
                        <div class="card-body">
                            <p class="card-title">For Approval</p>
                            <div class="d-flex justify-content-between">
                                <div class="mb-3 mt-2">
                                    <h3 class="text-primary fs-30 font-weight-medium">
                                        {{ $totalApproval ?? '0' }}
                                        <i class="ti ti-check-box"></i>
                                    </h3>
                                </div>
                                <!-- <div class="mt-3">
                                    <a href="#" class="text-info">View all</a>
                                </div> -->
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <p>CRR</p>
                                <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('customer_requirement.index', ['progress' => 10, 'status' => 10]) }}">
                                        {{ $crrSalesForApproval ?? '0' }}
                                    </a>
                                </h5>
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <p>RPE</p>
                                <h5 class="text-primary font-weight-medium">
                                    <a href="{{ route('product_evaluation.index', ['progress' => 10, 'status' => 10]) }}">
                                        {{ $rpeSalesForApproval ?? '0' }}
                                    </a>
                                </h5>
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <p>SRF</p>
                                <h5 class="text-primary font-weight-medium">
                                    <a href="{{ route('sample_request.index', ['progress' => 10, 'status' => 10]) }}">
                                        {{ $srfSalesForApproval ?? '0' }}
                                    </a>
                                </h5>
                                <!-- <h5 class="text-primary font-weight-medium">{{ $srfSalesApproval ?? '0' }}</h5> -->
                            </div>
                            <div class="d-flex justify-content-between">
                                <p>PRF</p>
                                <h5 class="text-primary font-weight-medium">
                                    <a href="{{ route('price_monitoring.index', ['progress' => 10, 'status' => 10]) }}">
                                        {{ $prfSalesForApproval ?? '0' }}
                                    </a>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <p class="card-title">Activities</p>
                            <div class="d-flex justify-content-between">
                                <div class="mb-3 mt-2">
                                    <h3 class="text-primary fs-30 font-weight-medium">
                                        {{ $totalActivitiesCount ?? '0'}}
                                        <i class="ti ti-layers"></i>
                                    </h3>
                                </div>
                                <!-- <div class="mt-3">
                                    <a href="{{ url('/activities?open=10') }}" class="text-info">View all</a>
                                </div> -->
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <p>Open Activities</p>
                                <h5 class="text-primary font-weight-medium">{{ $openActivitiesCount ?? '0' }}</h5>
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <p>Closed Activities</p>
                                <h5 class="text-primary font-weight-medium">{{ $closedActivitiesCount ?? '0' }}</h5>
                            </div>
                        </div>
                    </div>
                @elseif ((optional($role)->name == 'Staff L1') && (optional($role)->type == 'IS' || optional($role)->type == 'LS'))
                    <div class="card mb-2">
                        <div class="card-body">
                            <p class="card-title">For Approval</p>
                            <div class="d-flex justify-content-between">
                                <div class="mb-3 mt-2">
                                    <h3 class="text-primary fs-30 font-weight-medium">
                                        {{ $totalApproval ?? '0' }}
                                        <i class="ti ti-check-box"></i>
                                    </h3>
                                </div>
                                <!-- <div class="mt-3">
                                    <a href="#" class="text-info">View all</a>
                                </div> -->
                            </div>
                            <div class="d-flex justify-content-between">
                                <p>CRR</p>
                                <h5 class="text-primary font-weight-medium">{{ $crrSalesForApproval ?? '0' }}</h5>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p>RPE</p>
                                <h5 class="text-primary font-weight-medium">{{ $rpeSalesApproval ?? '0' }}</h5>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p>SRF</p>
                                <h5 class="text-primary font-weight-medium">{{ $srfSalesApproval ?? '0' }}</h5>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p>PRF</p>
                                <h5 class="text-primary font-weight-medium">{{ $prfSalesApproval ?? '0' }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <p class="card-title">Activities</p>
                            <div class="d-flex justify-content-between">
                                <div class="mb-3 mt-2">
                                    <h3 class="text-primary fs-30 font-weight-medium">
                                        {{ $totalActivitiesCount ?? '0'}}
                                        <i class="ti ti-layers"></i>
                                    </h3>
                                </div>
                                <!-- <div class="mt-3">
                                    <a href="{{ url('/activities?open=10') }}" class="text-info">View all</a>
                                </div> -->
                            </div>
                            <div class="d-flex justify-content-between">
                                <p>Open Activities</p>
                                <h5 class="text-primary font-weight-medium">{{ $openActivitiesCount ?? '0' }}</h5>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p>Closed Activities</p>
                                <h5 class="text-primary font-weight-medium">{{ $closedActivitiesCount ?? '0' }}</h5>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <p class="card-title">Customer Requirement</p>
                        <div class="d-flex justify-content-between">
                            <div class="mb-3 mt-2">
                                <h3 class="text-primary fs-30 font-weight-medium">
                                    {{ $totalCRRCount ?? '0'}}
                                    <i class="ti ti-user"></i>
                                </h3>
                            </div>
                            <!-- <div class="mt-3">
                                <a href="#" class="text-info">View all</a>
                            </div> -->
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Cancelled</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('customer_requirement.index', ['status' => 50]) }}">
                                    {{ $crrCancelled ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>For Sales Approval</p>
                            <h5 class="text-primary font-weight-medium">{{ $crrSalesApproval ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Sales Approved</p>
                            <h5 class="text-primary font-weight-medium">{{ $crrSalesApproved ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Sales Accepted</p>
                            <h5 class="text-primary font-weight-medium">{{ $crrSalesAccepted ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>QCD/R&D Received</p>
                            <h5 class="text-primary font-weight-medium">{{ $crrRNDReceived ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>QCD/R&D Ongoing</p>
                            <h5 class="text-primary font-weight-medium">{{ $crrRnDOngoing ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>QCD/R&D Pending</p>
                            <h5 class="text-primary font-weight-medium">{{ $crrRnDPending ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>QCD/R&D Initial Review</p>
                            <h5 class="text-primary font-weight-medium">{{ $crrRnDInitial ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>QCD/R&D Final Review</p>
                            <h5 class="text-primary font-weight-medium">{{ $crrRnDFinal ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>QCD/R&D Completed</p>
                            <h5 class="text-primary font-weight-medium">{{ $crrRnDCompleted ?? '0' }}</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <p class="card-title">Request for Product Evaluation</p>
                        <div class="d-flex justify-content-between">
                            <div class="mb-3 mt-2">
                                <h3 class="text-primary fs-30 font-weight-medium">
                                    {{ $totalRPECount ?? '0'}}
                                    <i class="ti ti-file"></i>
                                </h3>
                            </div>
                            <!-- <div class="mt-3">
                                <a href="#" class="text-info">View all</a>
                            </div> -->
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Cancelled</p>
                            <h5 class="text-primary font-weight-medium">
                                <!-- <a href="{{ route('customer_requirement.index', ['status' => 50]) }}">
                                    {{ $crrCancelled ?? '0' }}
                                </a> -->
                                {{ $rpeCancelled ?? '0' }}
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>For Sales Approval</p>
                            <h5 class="text-primary font-weight-medium">{{ $rpeSalesApproval ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Sales Approved</p>
                            <h5 class="text-primary font-weight-medium">{{ $rpeSalesApproved ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Sales Accepted</p>
                            <h5 class="text-primary font-weight-medium">{{ $rpeSalesAccepted ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>QCD/R&D Received</p>
                            <h5 class="text-primary font-weight-medium">{{ $rpeRnDReceived ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>QCD/R&D Ongoing</p>
                            <h5 class="text-primary font-weight-medium">{{ $rpeRnDOngoing ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>QCD/R&D Pending</p>
                            <h5 class="text-primary font-weight-medium">{{ $rpeRnDPending ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>QCD/R&D Initial Review</p>
                            <h5 class="text-primary font-weight-medium">{{ $rpeRnDInitial ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>QCD/R&D Final Review</p>
                            <h5 class="text-primary font-weight-medium">{{ $rpeRnDFinal ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>QCD/R&D Completed</p>
                            <h5 class="text-primary font-weight-medium">{{ $rpeRnDCompleted ?? '0' }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif(optional($role)->type == 'RND' || optional($role)->type == 'QCD')
        <div class="row">
            <div class="col-md-3 grid-margin transparent">
                @if ((optional($role)->name == 'Staff L2' || optional($role)->name == 'Department Admin') &&
                (optional($role)->type == 'RND' || (optional($role)->type == 'QCD' )))
                    <div class="card mb-2 card-tale">
                        <div class="card-body">
                            <p class="card-title text-white">Initial Review</p>
                            <div class="d-flex justify-content-between">
                                <div class="mb-3 mt-2">
                                    <h3 class="fs-30 font-weight-medium text-white">
                                        {{ $totalInitialReview ?? '0' }}
                                        <i class="ti ti-check"></i>
                                    </h3>
                                </div>
                                <!-- <div class="mt-3">
                                    <a href="#" class="text-info">View all</a>
                                </div> -->
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <p>CRR</p>
                                <h5 class="font-weight-medium text-white">
                                    <a href="{{ route('customer_requirement.index', ['progress' => 57]) }}" class="text-white" onclick="show()">
                                        {{ $crrRNDInitialReview ?? '0' }}
                                    </a>
                                </h5>
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <p>RPE</p>
                                <h5 class="font-weight-medium text-white">
                                    <a href="{{ route('product_evaluation.index', ['progress' => 57]) }}" class="text-white" onclick="show()">
                                        {{ $rpeRNDInitialReview ?? '0' }}
                                    </a>
                                </h5>
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <p>SRF</p>
                                <h5 class="font-weight-medium text-white">
                                    <a href="{{ route('sample_request.index', ['progress' => 57]) }}" class="text-white" onclick="show()">
                                        {{ $srfRNDInitialReview ?? '0' }}
                                    </a>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="card card-light-blue">
                        <div class="card-body">
                            <p class="card-title text-white">Final Review</p>
                            <div class="d-flex justify-content-between">
                                <div class="mb-3 mt-2">
                                    <h3 class="fs-30 font-weight-medium">
                                        {{ $totalFinalReview ?? '0'}}
                                        <i class="ti ti-check-box"></i>
                                    </h3>
                                </div>
                                <!-- <div class="mt-3">
                                    <a href="{{ url('/activities?open=10') }}" class="text-info">View all</a>
                                </div> -->
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <p>CRR</p>
                                <h5 class="text-primary font-weight-medium">
                                    <a href="{{ route('customer_requirement.index', ['progress' => 81]) }}" class="text-white" onclick="show()">
                                        {{ $crrRNDFinallReview ?? '0' }}
                                    </a>
                                </h5>
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <p>RPE</p>
                                <h5 class="text-primary font-weight-medium">
                                    <a href="{{ route('product_evaluation.index', ['progress' => 81]) }}" class="text-white" onclick="show()">
                                        {{ $rpeRNDFinallReview ?? '0' }}
                                    </a>
                                </h5>
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <p>SRF</p>
                                <h5 class="text-primary font-weight-medium">
                                    <a href="{{ route('sample_request.index', ['progress' => 81]) }}" class="text-white" onclick="show()">
                                        {{ $srfRNDFinallReview ?? '0' }}
                                    </a>
                                </h5>
                                <!-- <h5 class="text-primary font-weight-medium">{{ $srfSalesApproval ?? '0' }}</h5> -->
                            </div>
                        </div>
                    </div>
                @elseif ((optional($role)->name == 'Staff L1') && (optional($role)->type == 'RND'))
                    <div class="card mb-2 card-tale">
                        <div class="card-body">
                            <p class="card-title text-white">Open Transactions</p>
                            <div class="d-flex justify-content-between">
                                <div class="mb-3 mt-2">
                                    <h3 class="text-white fs-30 font-weight-medium">
                                        {{ $totalOpenRND ?? '0' }}
                                        <i class="ti ti-check-box"></i>
                                    </h3>
                                </div>
                                <!-- <div class="mt-3">
                                    <a href="#" class="text-info">View all</a>
                                </div> -->
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <p>CRR</p>
                                <h5 class="text-primary font-weight-medium">
                                    <a href="{{ route('customer_requirement.index', ['status' => 10]) }}" class="text-white" onclick='show()'>
                                        {{ $rndCrrOpen ?? '0' }}
                                    </a>
                                </h5>
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <p>RPE</p>
                                <h5 class="text-primary font-weight-medium">
                                    <a href="{{ route('product_evaluation.index', ['status' => 10]) }}" class="text-white" onclick='show()'>
                                        {{ $rndRpeOpen ?? '0' }}
                                    </a>
                                </h5>
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <p>SRF</p>
                                <h5 class="text-primary font-weight-medium">
                                    <a href="{{ route('sample_request.index', ['status' => 10]) }}" class="text-white" onclick='show()'>
                                        {{ $rndSrfOpen ?? '0' }}
                                    </a>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="card card-light-blue">
                        <div class="card-body">
                            <p class="card-title text-white">Closed Transactions</p>
                            <div class="d-flex justify-content-between">
                                <div class="mb-3 mt-2">
                                    <h3 class="text-white fs-30 font-weight-medium">
                                        {{ $totalClosedRND ?? '0'}}
                                        <i class="ti ti-folder"></i>
                                    </h3>
                                </div>
                                <!-- <div class="mt-3">
                                    <a href="{{ url('/activities?open=10') }}" class="text-info">View all</a>
                                </div> -->
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <p>CRR</p>
                                <h5 class="text-primary font-weight-medium">
                                    <a href="{{ route('customer_requirement.index', ['status' => 30]) }}" class="text-white" onclick='show()'>
                                        {{ $rndCrrClosed ?? '0' }}
                                    </a>
                                </h5>
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <p>RPE</p>
                                <h5 class="text-primary font-weight-medium">
                                    <a href="{{ route('product_evaluation.index', ['status' => 30]) }}" class="text-white" onclick='show()'>
                                        {{ $rndRpeClosed ?? '0' }}
                                    </a>
                                </h5>
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <p>SRF</p>
                                <h5 class="text-primary font-weight-medium">
                                    <a href="{{ route('sample_request.index', ['status' => 30]) }}" class="text-white" onclick='show()'>
                                        {{ $rndSrfClosed ?? '0' }}
                                    </a>
                                </h5>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-md-3 grid-margin transparent">
                @if ((optional($role)->name == 'Staff L2' || optional($role)->name == 'Department Admin') &&
                (optional($role)->type == 'RND' || (optional($role)->type == 'QCD' )))
                    <div class="card mb-2 card-dark-blue">
                        <div class="card-body">
                            <p class="card-title text-white">New Request</p>
                            <div class="d-flex justify-content-between">
                                <div class="mb-3 mt-2">
                                    <h3 class="fs-30 font-weight-medium text-white">
                                        {{ $totalNewRequest ?? '0' }}
                                        <i class="ti ti-file"></i>
                                    </h3>
                                </div>
                                <!-- <div class="mt-3">
                                    <a href="#" class="text-info">View all</a>
                                </div> -->
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <p>CRR</p>
                                <h5 class="font-weight-medium text-white">
                                    <a href="{{ route('customer_requirement.index', ['progress' => 30]) }}" class="text-white" onclick="show()">
                                        {{ $crrRNDNew ?? '0' }}
                                    </a>
                                </h5>
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <p>RPE</p>
                                <h5 class="font-weight-medium text-white">
                                    <a href="{{ route('product_evaluation.index', ['progress' => 30]) }}" class="text-white" onclick="show()">
                                        {{ $rpeRNDNew ?? '0' }}
                                    </a>
                                </h5>
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <p>SRF</p>
                                <h5 class="font-weight-medium text-white">
                                    <a href="{{ route('sample_request.index', ['progress' => 30]) }}" class="text-white" onclick="show()">
                                        {{ $srfRNDNew ?? '0' }}
                                    </a>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="card card-light-danger">
                        <div class="card-body">
                            <p class="card-title text-white">Due Today</p>
                            <div class="d-flex justify-content-between">
                                <div class="mb-3 mt-2">
                                    <h3 class="fs-30 font-weight-medium">
                                        {{ $totalDueToday ?? '0'}}
                                        <i class="ti ti-bell"></i>
                                    </h3>
                                </div>
                                <!-- <div class="mt-3">
                                    <a href="{{ url('/activities?open=10') }}" class="text-info">View all</a>
                                </div> -->
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <p>CRR</p>
                                <h5 class="text-primary font-weight-medium">
                                    <a href="{{ route('customer_requirement.index', ['DueDate' => 'past']) }}" class="text-white">
                                        {{ $crrDueToday ?? '0' }}
                                    </a>
                                </h5>
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <p>RPE</p>
                                <h5 class="text-primary font-weight-medium">
                                    <a href="{{ route('product_evaluation.index', ['DueDate' => 'past']) }}" class="text-white">
                                        {{ $rpeDueToday ?? '0' }}
                                    </a>
                                </h5>
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <p>SRF</p>
                                <h5 class="text-primary font-weight-medium">
                                    <a href="{{ route('sample_request.index', ['DateRequired' => 'past']) }}" class="text-white">
                                        {{ $srfDueToday ?? '0' }}
                                    </a>
                                </h5>
                            </div>
                        </div>
                    </div>
                @elseif ((optional($role)->name == 'Staff L1') && (optional($role)->type == 'RND'))
                    <div class="card mb-2 card-dark-blue">
                        <div class="card-body">
                            <p class="card-title text-white">New Request</p>
                            <div class="d-flex justify-content-between">
                                <div class="mb-3 mt-2">
                                    <h3 class="fs-30 font-weight-medium text-white">
                                        {{ $totalNewRequest ?? '0' }}
                                        <i class="ti ti-file"></i>
                                    </h3>
                                </div>
                                <!-- <div class="mt-3">
                                    <a href="#" class="text-info">View all</a>
                                </div> -->
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <p>CRR</p>
                                <!-- <h5 class="font-weight-medium text-white">
                                    <a href="{{ route('customer_requirement.index', ['progress' => 30, 'status' => 10]) }}" class="text-white">
                                        {{ $crrRNDNew ?? '0' }}
                                    </a>
                                </h5> -->
                                <h5 class="font-weight-medium text-white">{{ $crrRNDNew ?? '0' }}</h5>
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <p>RPE</p>
                                <!-- <h5 class="font-weight-medium text-white">
                                    <a href="{{ route('product_evaluation.index', ['progress' => 30, 'status' => 10]) }}" class="text-white">
                                        {{ $rpeRNDNew ?? '0' }}
                                    </a>
                                </h5> -->
                                <h5 class="font-weight-medium text-white">{{ $rpeRNDNew ?? '0' }}</h5>
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <p>SRF</p>
                                <!-- <h5 class="font-weight-medium text-white">
                                    <a href="{{ route('sample_request.index', ['progress' => 30, 'status' => 10]) }}" class="text-white">
                                        {{ $srfRNDNew ?? '0' }}
                                    </a>
                                </h5> -->
                                <h5 class="font-weight-medium text-white">{{ $srfRNDNew ?? '0' }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card card-light-danger">
                        <div class="card-body">
                            <p class="card-title text-white">Due Today</p>
                            <div class="d-flex justify-content-between">
                                <div class="mb-3 mt-2">
                                    <h3 class="text-white fs-30 font-weight-medium">
                                        {{ $totalDue ?? '0'}}
                                        <i class="ti ti-bell"></i>
                                    </h3>
                                </div>
                                <!-- <div class="mt-3">
                                    <a href="{{ url('/activities?open=10') }}" class="text-info">View all</a>
                                </div> -->
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <p>CRR</p>
                                <h5 class="text-primary font-weight-medium">
                                    <a href="{{ route('customer_requirement.index', ['status' => 10, 'DueDate' => 'past']) }}" class="text-white" onclick="show()">
                                        {{ $crrDue ?? '0' }}
                                    </a>
                                </h5>
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <p>RPE</p>
                                <h5 class="text-primary font-weight-medium">
                                    <a href="{{ route('product_evaluation.index', ['status' => 10, 'DueDate' => 'past']) }}" class="text-white" onclick="show()">
                                        {{ $rpeDue ?? '0' }}
                                    </a>
                                </h5>
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <p>SRF</p>
                                <h5 class="text-primary font-weight-medium">
                                    <a href="{{ route('sample_request.index', ['status' => 10, 'DateRequired' => 'past']) }}" class="text-white" onclick="show()">
                                        {{ $srfDue ?? '0' }}
                                    </a>
                                </h5>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <p class="card-title mb-0">New Products</p>
                        <div class="table-responsive">
                            <table class="table table-striped table-borderless tables">
                                <thead>
                                    <tr>
                                        <th width="25%">DDW Number</th>
                                        <th width="25%">Code</th>
                                        <th width="25%">Created By</th>
                                        <th width="25%">Date Created</th>
                                    </tr>  
                                </thead>
                                <tbody>
                                    @foreach($newProducts as $product)
                                        <tr>
                                            <td>{{ $product->ddw_number }}</td>
                                            <td>
                                                <a href="{{url('view_new_product/'.$product->id)}}"  title="View Products">
                                                    {{ $product->code }}
                                                </a>
                                            </td>
                                            <td>
                                                {{ $product->userByUserId ? $product->userByUserId->full_name : ($product->userById ? $product->userById->full_name : 'N/A') }}
                                            </td>
                                            <td>{{date('m/d/Y', strtotime($product->created_at))}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(optional($role)->type == 'IS' || optional($role)->type == 'LS')
        <div class="row">
            <div class="col-md-4 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <p class="card-title">Customer Service</p>
                        <div class="d-flex justify-content-between">
                            <div class="mb-3 mt-2">
                                <h3 class="text-primary fs-30 font-weight-medium">
                                    {{ $totalCustomerServiceCount ?? '0' }}
                                    <i class="ti ti-comments"></i>
                                </h3>
                            </div>
                            <!-- <div class="mt-3">
                                <a href="#" class="text-info">View all</a>
                            </div> -->
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Customer Complaints</p>
                            <h5 class="text-primary font-weight-medium">{{ $customerComplaintsCount ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Customer Feedback</p>
                            <h5 class="text-primary font-weight-medium">{{ $customerFeedbackCount ?? '0' }}</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <p class="card-title">Sample Request</p>
                        <div class="d-flex justify-content-between">
                            <div class="mb-4 mt-2">
                                <h3 class="text-primary fs-30 font-weight-medium">
                                    {{ $totalSRFCount ?? '0'}}
                                    <i class="ti ti-package"></i>
                                </h3>
                            </div>
                            <!-- <div class="mt-3">
                                <a href="#" class="text-info">View all</a>
                            </div> -->
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Cancelled</p>
                            <h5 class="text-primary font-weight-medium">
                                <!-- <a href="{{ route('customer_requirement.index', ['status' => 50]) }}">
                                    {{ $crrCancelled ?? '0' }}
                                </a> -->
                                {{ $srfCancelled ?? '0' }}
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>For Sales Approval</p>
                            <h5 class="text-primary font-weight-medium">{{ $srfSalesApproval ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Sales Approved</p>
                            <h5 class="text-primary font-weight-medium">{{ $srfSalesApproved ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Sales Accepted</p>
                            <h5 class="text-primary font-weight-medium">{{ $srfSalesAccepted ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>QCD/R&D Received</p>
                            <h5 class="text-primary font-weight-medium">{{ $srfRnDOngoing ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>QCD/R&D Ongoing</p>
                            <h5 class="text-primary font-weight-medium">{{ $srfRnDOngoing ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>QCD/R&D Pending</p>
                            <h5 class="text-primary font-weight-medium">{{ $srfRnDPending ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>QCD/R&D Initial Review</p>
                            <h5 class="text-primary font-weight-medium">{{ $srfRnDInitial ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>QCD/R&D Final Review</p>
                            <h5 class="text-primary font-weight-medium">{{ $srfRnDFinal ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>QCD/R&D Completed</p>
                            <h5 class="text-primary font-weight-medium">{{ $srfRnDCompleted ?? '0' }}</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <p class="card-title">Price Request</p>
                        <div class="d-flex justify-content-between">
                            <div class="mb-4 mt-2">
                                <h3 class="text-primary fs-30 font-weight-medium">
                                    {{ $totalPRFCount ?? '0'}}
                                    <i class="ti ti-tag"></i>
                                </h3>
                            </div>
                            <!-- <div class="mt-3">
                                <a href="#" class="text-info">View all</a>
                            </div> -->
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>For Approval</p>
                            <h5 class="text-primary font-weight-medium">
                                <!-- <a href="{{ route('customer_requirement.index', ['status' => 50]) }}">
                                    {{ $crrCancelled ?? '0' }}
                                </a> -->
                                {{ $prfSalesApproval ?? '0' }}
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Waiting Disposition</p>
                            <h5 class="text-primary font-weight-medium">{{ $prfWaiting ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Reopened</p>
                            <h5 class="text-primary font-weight-medium">{{ $prfReopened ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Closed</p>
                            <h5 class="text-primary font-weight-medium">{{ $prfClosed ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Manager Approval</p>
                            <h5 class="text-primary font-weight-medium">{{ $prfManagerApproval ?? '0' }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif(optional($role)->type == 'RND' )
        <div class="row">
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <p class="card-title">Customer Requirement</p>
                        <div class="d-flex justify-content-between">
                            <div class="mb-3 mt-2">
                                <h3 class="text-primary fs-30 font-weight-medium">
                                    {{ $totalCRRCountRND ?? '0'}}
                                    <i class="ti ti-user"></i>
                                </h3>
                            </div>
                            <!-- <div class="mt-3">
                                <a href="#" class="text-info">View all</a>
                            </div> -->
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Open</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('customer_requirement.index', ['status' => 10]) }}">
                                    {{ $crrRndOpen ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Cancelled</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('customer_requirement.index', ['status' => 50]) }}">
                                    {{ $crrCancelledRND ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>For Sales Approval</p>
                            <h5 class="text-primary font-weight-medium">{{ $crrSalesApprovalRND ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Sales Approved</p>
                            <h5 class="text-primary font-weight-medium">{{ $crrSalesApprovedRND ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Sales Accepted</p>
                            <h5 class="text-primary font-weight-medium">{{ $crrSalesAcceptedRND ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>QCD/R&D Received</p>
                            <h5 class="text-primary font-weight-medium">{{ $crrRnDReceivedRND ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>QCD/R&D Ongoing</p>
                            <h5 class="text-primary font-weight-medium">{{ $crrRnDOngoingRND ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>QCD/R&D Pending</p>
                            <h5 class="text-primary font-weight-medium">{{ $crrRnDPendingRND ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>QCD/R&D Initial Review</p>
                            <h5 class="text-primary font-weight-medium">{{ $crrRnDInitialRND ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>QCD/R&D Final Review</p>
                            <h5 class="text-primary font-weight-medium">{{ $crrRnDFinalRND ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>R&D Completed</p>
                            <h5 class="text-primary font-weight-medium">{{ $crrRnDCompletedRND ?? '0' }}</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <p class="card-title">Request for Product Evaluation</p>
                        <div class="d-flex justify-content-between">
                            <div class="mb-3 mt-2">
                                <h3 class="text-primary fs-30 font-weight-medium">
                                    {{ $totalRPECountRND ?? '0'}}
                                    <i class="ti ti-file"></i>
                                </h3>
                            </div>
                            <!-- <div class="mt-3">
                                <a href="#" class="text-info">View all</a>
                            </div> -->
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Open</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('product_evaluation.index', ['status' => 10]) }}">
                                    {{ $rndRpeOpen ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Cancelled</p>
                            <h5 class="text-primary font-weight-medium">
                                <!-- <a href="{{ route('customer_requirement.index', ['status' => 50]) }}">
                                    {{ $crrCancelled ?? '0' }}
                                </a> -->
                                {{ $rpeCancelledRND ?? '0' }}
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>For Sales Approval</p>
                            <h5 class="text-primary font-weight-medium">{{ $rpeSalesApprovalRND ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Sales Approved</p>
                            <h5 class="text-primary font-weight-medium">{{ $rpeSalesApprovedRND ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Sales Accepted</p>
                            <h5 class="text-primary font-weight-medium">{{ $rpeSalesAcceptedRND ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>QCD/R&D Received</p>
                            <h5 class="text-primary font-weight-medium">{{ $rpeRnDReceivedRND ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>QCD/R&D Ongoing</p>
                            <h5 class="text-primary font-weight-medium">{{ $rpeRnDOngoingRND ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>QCD/R&D Pending</p>
                            <h5 class="text-primary font-weight-medium">{{ $rpeRnDPendingRND ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>QCD/R&D Initial Review</p>
                            <h5 class="text-primary font-weight-medium">{{ $rpeRnDInitialRND ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>QCD/R&D Final Review</p>
                            <h5 class="text-primary font-weight-medium">{{ $rpeRnDFinalRND ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>R&D Completed</p>
                            <h5 class="text-primary font-weight-medium">{{ $rpeRnDCompletedRND ?? '0' }}</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <p class="card-title">Sample Request</p>
                        <div class="d-flex justify-content-between">
                            <div class="mb-4 mt-2">
                                <h3 class="text-primary fs-30 font-weight-medium">
                                    {{ $totalSRFCountRND ?? '0'}}
                                    <i class="ti ti-package"></i>
                                </h3>
                            </div>
                            <!-- <div class="mt-3">
                                <a href="#" class="text-info">View all</a>
                            </div> -->
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Open</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('sample_request.index', ['status' => 10]) }}" onclick='show()'>
                                    {{ $srfImmediateOpen ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Cancelled</p>
                            <h5 class="text-primary font-weight-medium">
                                <!-- <a href="{{ route('customer_requirement.index', ['status' => 50]) }}">
                                    {{ $crrCancelled ?? '0' }}
                                </a> -->
                                {{ $srfCancelledRND ?? '0' }}
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>For Sales Approval</p>
                            <h5 class="text-primary font-weight-medium">{{ $srfSalesApprovalRND ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Sales Approved</p>
                            <h5 class="text-primary font-weight-medium">{{ $srfSalesApprovedRND ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Sales Accepted</p>
                            <h5 class="text-primary font-weight-medium">{{ $srfSalesAcceptedRND ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>QCD/R&D Received</p>
                            <h5 class="text-primary font-weight-medium">{{ $srfRnDReceivedRND ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>QCD/R&D Ongoing</p>
                            <h5 class="text-primary font-weight-medium">{{ $srfRnDOngoingRND ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>QCD/R&D Pending</p>
                            <h5 class="text-primary font-weight-medium">{{ $srfRnDPendingRND ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>QCD/R&D Initial Review</p>
                            <h5 class="text-primary font-weight-medium">{{ $srfRnDInitialRND ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>QCD/R&D Final Review</p>
                            <h5 class="text-primary font-weight-medium">{{ $srfRnDFinalRND ?? '0' }}</h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>R&D Completed</p>
                            <h5 class="text-primary font-weight-medium">{{ $srfRnDCompletedRND ?? '0' }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap4.js"></script>
<script>
    $('.tables').dataTable( {
        "dom": 'rtip'
    });
</script>
@endsection
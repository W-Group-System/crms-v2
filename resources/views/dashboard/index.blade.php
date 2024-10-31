@extends('layouts.header')
@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin">
            <h3 class="font-weight-bold">Welcome back,&nbsp;{{auth()->user()->full_name}}!</h3>
            <h4 class="font-weight-normal mb-0" style="color: #7d7373">{{ date('l, d F') }} | <p style="font-size: 1.125rem;display: contents;" id="demo"></p></h4>
        </div>
    </div>
    <div class="row">
        @if ((optional($role)->name == 'Staff L2' || optional($role)->name == 'Department Admin') && (optional($role)->type == 'IS' || optional($role)->type == 'LS'))
        <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form class="form-horizontal">
                            <h4 class="d-flex justify-content-between font-weight-bold mb-4">Account Information</h4>
                            <div class="form-group row mb-2">
                                <label class="col-sm-3 col-form-label"><b>System Role</b></label>
                                <div class="col-sm-9">
                                    <label>{{ auth()->user()->role->name ?? 'No Role Assigned' }}</label>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label class="col-sm-3 col-form-label"><b>Company</b></label>
                                <div class="col-sm-9">
                                    <label>{{auth()->user()->company->name}}</label>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label class="col-sm-3 col-form-label"><b>Department</b></label>
                                <div class="col-sm-9">
                                    <label>{{auth()->user()->department->name}}</label>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label class="col-sm-3 col-form-label"><b>Username</b></label>
                                <div class="col-sm-9">
                                    <label>{{auth()->user()->username}}</label>
                                </div>
                            </div>
                            <!-- <div class="form-group row mb-2" style="margin-top: 2.5em">
                                <div class="col-md-12">
                                    <a href="{{ route('change_password') }}" class="btn btn-info">
                                        <i style="color: #fff" class="ti ti-unlock"></i>&nbsp;Change Password
                                    </a>
                                </div>
                            </div> -->
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-3 grid-margin transparent">
                <div class="card mb-2 card-tale">
                    <div class="card-body">
                        <p class="card-title text-white">For Approval</p>
                        <div class="d-flex justify-content-between">
                            <div class="mb-3 mt-2">
                                <h3 class="fs-30 font-weight-medium text-white">
                                    {{ $totalApproval ?? '0' }}
                                    <i class="ti ti-check-box"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>CRR</p>
                            <h5 class="font-weight-medium text-white">
                            <a href="{{ route('customer_requirement.index', ['progress' => 10, 'open' => 10]) }}" class="text-white" onclick="show()">
                                    {{ $crrSalesForApproval ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>RPE</p>
                            <h5 class="font-weight-medium text-white">
                                <a href="{{ route('product_evaluation.index', ['progress' => 10]) }}" class="text-white" onclick="show()">
                                    {{ $rpeSalesForApproval ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SRF</p>
                            <h5 class="font-weight-medium text-white">
                                <a href="{{ route('sample_request.index', ['progress' => 10, 'open' => 10]) }}" class="text-white" onclick="show()">
                                    {{ $srfSalesForApproval ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>PRF</p>
                            <h5 class="font-weight-medium text-white">
                                <a href="{{ route('price_monitoring.index', ['progress' => 10]) }}" class="text-white" onclick="show()">
                                    {{ $prfSalesForApproval ?? '0' }}
                                </a>
                            </h5>
                        </div>
                    </div>
                </div>
                <div class="card card-light-blue">
                    <div class="card-body">
                        <p class="card-title text-white">Activities</p>
                        <div class="d-flex justify-content-between">
                            <div class="mb-3 mt-2">
                                <h3 class="fs-30 font-weight-medium">
                                    {{ $totalActivitiesCount ?? '0'}}
                                    <i class="ti ti-layers"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Open</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('activities.index', ['status' => 10]) }}" class="text-white" onclick="show()">
                                    {{ $openActivitiesCount ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Closed</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('activities.index', ['status' => 20]) }}" class="text-white" onclick="show()">
                                    {{ $closedActivitiesCount ?? '0' }}
                                </a>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 grid-margin transparent">
                <div class="card mb-2 card-dark-blue">
                    <div class="card-body">
                        <p class="card-title text-white">Open Transactions</p>
                        <div class="d-flex justify-content-between">
                            <div class="mb-3 mt-2">
                                <h3 class="fs-30 font-weight-medium text-white">
                                    {{ $totalSalesOpen ?? '0' }}
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
                                <a href="{{ route('customer_requirement.index', ['status' => 10]) }}" class="text-white" onclick="show()">
                                    {{ $salesCrrOpen ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>RPE</p>
                            <h5 class="font-weight-medium text-white">
                                <a href="{{ route('product_evaluation.index', ['status' => 10]) }}" class="text-white" onclick="show()">
                                    {{ $salesRpeOpen ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SRF</p>
                            <h5 class="font-weight-medium text-white">
                                <a href="{{ route('sample_request.index', ['status' => 10]) }}" class="text-white" onclick="show()">
                                    {{ $salesSrfOpen ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>PRF</p>
                            <h5 class="font-weight-medium text-white">
                                <a href="{{ route('price_monitoring.index', ['status' => 10]) }}" class="text-white" onclick="show()">
                                    {{ $salesPrfOpen ?? '0' }}
                                </a>
                            </h5>
                        </div>
                    </div>
                </div>
                <div class="card card-light-danger">
                    <div class="card-body">
                        <p class="card-title text-white">Customer Service</p>
                        <div class="d-flex justify-content-between">
                            <div class="mb-3 mt-2">
                                <h3 class="fs-30 font-weight-medium">
                                    {{ $totalCustomerServiceCount ?? '0'}}
                                    <i class="ti ti-comments"></i>
                                </h3>
                            </div>
                            <!-- <div class="mt-3">
                                <a href="{{ url('/activities?open=10') }}" class="text-info">View all</a>
                            </div> -->
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Customer Complaints</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('customer_requirement.index', ['DueDate' => 'past']) }}" class="text-white" onclick="show()">
                                    {{ $customerComplaintsCount ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Customer Feedback</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('product_evaluation.index', ['DueDate' => 'past']) }}" class="text-white" onclick="show()">
                                    {{ $customerFeedbackCount ?? '0' }}
                                </a>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        @elseif ((optional($role)->name == 'Staff L1') && (optional($role)->type == 'IS' || optional($role)->type == 'LS'))
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form class="form-horizontal">
                            <h4 class="d-flex justify-content-between font-weight-bold mb-4">Account Information</h4>
                            <div class="form-group row mb-2">
                                <label class="col-sm-3 col-form-label"><b>System Role</b></label>
                                <div class="col-sm-9">
                                    <label>{{ auth()->user()->role->name ?? 'No Role Assigned' }}</label>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label class="col-sm-3 col-form-label"><b>Company</b></label>
                                <div class="col-sm-9">
                                    <label>{{auth()->user()->company->name}}</label>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label class="col-sm-3 col-form-label"><b>Department</b></label>
                                <div class="col-sm-9">
                                    <label>{{auth()->user()->department->name}}</label>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label class="col-sm-3 col-form-label"><b>Username</b></label>
                                <div class="col-sm-9">
                                    <label>{{auth()->user()->username}}</label>
                                </div>
                            </div>
                            <div class="form-group row mb-2" style="margin-top: 2.5em">
                                <div class="col-md-12">
                                    <a href="{{ route('change_password') }}" class="btn btn-info">
                                        <i style="color: #fff" class="ti ti-unlock"></i>&nbsp;Change Password
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-3 grid-margin transparent">
                <div class="card mb-2 card-tale">
                    <div class="card-body">
                        <p class="card-title text-white">Open Transactions</p>
                        <div class="d-flex justify-content-between">
                            <div class="mb-3 mt-2">
                                <h3 class="fs-30 font-weight-medium text-white">
                                    {{ $totalSalesOpen ?? '0' }}
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
                                <a href="{{ route('customer_requirement.index', ['status' => 10]) }}" class="text-white" onclick="show()">
                                    {{ $salesCrrOpen ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>RPE</p>
                            <h5 class="font-weight-medium text-white">
                                <a href="{{ route('product_evaluation.index', ['status' => 10]) }}" class="text-white" onclick="show()">
                                    {{ $salesRpeOpen ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SRF</p>
                            <h5 class="font-weight-medium text-white">
                                <a href="{{ route('sample_request.index', ['status' => 10]) }}" class="text-white" onclick="show()">
                                    {{ $salesSrfOpen ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>PRF</p>
                            <h5 class="font-weight-medium text-white">
                                <a href="{{ route('price_monitoring.index', ['status' => 10]) }}" class="text-white" onclick="show()">
                                    {{ $salesPrfOpen ?? '0' }}
                                </a>
                            </h5>
                        </div>
                    </div>
                </div>
                <div class="card card-light-danger">
                    <div class="card-body">
                        <p class="card-title text-white">Customer Service</p>
                        <div class="d-flex justify-content-between">
                            <div class="mb-3 mt-2">
                                <h3 class="fs-30 font-weight-medium">
                                    {{ $totalCustomerServiceCount ?? '0'}}
                                    <i class="ti ti-comments"></i>
                                </h3>
                            </div>
                            <!-- <div class="mt-3">
                                <a href="{{ url('/activities?open=10') }}" class="text-info">View all</a>
                            </div> -->
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Customer Complaints</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('customer_requirement.index', ['DueDate' => 'past']) }}" class="text-white" onclick="show()">
                                    {{ $customerComplaintsCount ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Customer Feedback</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('product_evaluation.index', ['DueDate' => 'past']) }}" class="text-white" onclick="show()">
                                    {{ $customerFeedbackCount ?? '0' }}
                                </a>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 grid-margin transparent">
                <div class="card mb-2 card-dark-blue">
                    <div class="card-body">
                        <p class="card-title text-white">Returned Transactions</p>
                        <div class="d-flex justify-content-between">
                            <div class="mb-3 mt-2">
                                <h3 class="fs-30 font-weight-medium text-white">
                                    {{ $totalSalesOpen ?? '0' }}
                                    <i class="ti ti-file"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>CRR</p>
                            <h5 class="font-weight-medium text-white">
                                <a href="{{ route('customer_requirement.index', ['return_to_sales' => 1]) }}" class="text-white" onclick="show()">
                                    {{ $salesCrrReturn ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>RPE</p>
                            <h5 class="font-weight-medium text-white">
                                <a href="{{ route('product_evaluation.index', ['status' => 10]) }}" class="text-white" onclick="show()">
                                    {{ $salesRpeOpen ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SRF</p>
                            <h5 class="font-weight-medium text-white">
                                <a href="{{ route('sample_request.index', ['status' => 10]) }}" class="text-white" onclick="show()">
                                    {{ $salesSrfOpen ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>PRF</p>
                            <h5 class="font-weight-medium text-white">
                                <a href="{{ route('price_monitoring.index', ['status' => 10]) }}" class="text-white" onclick="show()">
                                    {{ $salesPrfOpen ?? '0' }}
                                </a>
                            </h5>
                        </div>
                    </div>
                </div>
                <div class="card card-light-blue">
                    <div class="card-body">
                        <p class="card-title text-white">Activities</p>
                        <div class="d-flex justify-content-between">
                            <div class="mb-3 mt-2">
                                <h3 class="fs-30 font-weight-medium">
                                    {{ $totalActivitiesCount ?? '0'}}
                                    <i class="ti ti-layers"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Open</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('activities.index', ['status' => 10]) }}" class="text-white" onclick="show()">
                                    {{ $openActivitiesCount ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Closed</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('activities.index', ['status' => 20]) }}" class="text-white" onclick="show()">
                                    {{ $closedActivitiesCount ?? '0' }}
                                </a>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- <div class="row">
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title">CRR</p>
                    <div class="d-flex justify-content-between">
                        <div class="mb-3 mt-2">
                            <h3 class="text-primary fs-30 font-weight-medium">
                                {{ $totalSalesCRR ?? '0'}}
                                <i class="ti ti-user"></i>
                            </h3>
                        </div>
                    </div>
                    <div class="mb-1 d-flex justify-content-between">
                        <p>Closed</p>
                        <h5 class="text-primary font-weight-medium">
                            <a href="{{ route('customer_requirement.index', ['status' => 30]) }}">
                                {{ $salesCrrClosed ?? '0' }}
                            </a>
                        </h5>
                    </div>
                    <div class="mb-1 d-flex justify-content-between">
                        <p>Cancelled</p>
                        <h5 class="text-primary font-weight-medium">
                            <a href="{{ route('customer_requirement.index', ['status' => 50]) }}">
                                {{ $salesCrrCancelled ?? '0' }}
                            </a>
                        </h5>
                    </div>
                    <div class="mb-1 d-flex justify-content-between">
                        <p>Sales Approval</p>
                        <h5 class="text-primary font-weight-medium">
                            <a href="{{ route('customer_requirement.index', ['status' => 10, 'progress' => 10]) }}">
                                {{ $salesCrrApproval ?? '0' }}
                            </a>
                        </h5>
                    </div>
                    <div class="mb-1 d-flex justify-content-between">
                        <p>Sales Approved</p>
                        <h5 class="text-primary font-weight-medium">
                            <a href="{{ route('customer_requirement.index', ['status' => 10, 'progress' => 20]) }}">
                                {{ $salesCrrApproved ?? '0' }}
                            </a>
                        </h5>
                    </div>
                    <div class="mb-1 d-flex justify-content-between">
                        <p>Sales Accepted</p>
                        <h5 class="text-primary font-weight-medium">
                            <a href="{{ route('customer_requirement.index', ['status' => 10, 'progress' => 70]) }}">
                                {{ $salesCrrAccepted ?? '0' }}
                            </a>
                        </h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title">RPE</p>
                    <div class="d-flex justify-content-between">
                        <div class="mb-3 mt-2">
                            <h3 class="text-primary fs-30 font-weight-medium">
                                {{ $totalSalesRPE ?? '0'}}
                                <i class="ti ti-file"></i>
                            </h3>
                        </div>
                    </div>
                    <div class="mb-1 d-flex justify-content-between">
                        <p>Closed</p>
                        <h5 class="text-primary font-weight-medium">
                            <a href="{{ route('product_evaluation.index', ['status' => 30]) }}">
                                {{ $salesRpeClosed ?? '0' }}
                            </a>
                        </h5>
                    </div>
                    <div class="mb-1 d-flex justify-content-between">
                        <p>Cancelled</p>
                        <h5 class="text-primary font-weight-medium">
                            <a href="{{ route('product_evaluation.index', ['status' => 50]) }}">
                                {{ $salesRpeCancelled ?? '0' }}
                            </a>
                        </h5>
                    </div>
                    <div class="mb-1 d-flex justify-content-between">
                        <p>Sales Approval</p>
                        <h5 class="text-primary font-weight-medium">
                            <a href="{{ route('product_evaluation.index', ['status' => 10, 'progress' => 10]) }}">
                                {{ $salesRpeApproval ?? '0' }}
                            </a>
                        </h5>
                    </div>
                    <div class="mb-1 d-flex justify-content-between">
                        <p>Sales Approved</p>
                        <h5 class="text-primary font-weight-medium">
                            <a href="{{ route('product_evaluation.index', ['status' => 10, 'progress' => 20]) }}">
                                {{ $salesRpeApproved ?? '0' }}
                            </a>
                        </h5>
                    </div>
                    <div class="mb-1 d-flex justify-content-between">
                        <p>Sales Accepted</p>
                        <h5 class="text-primary font-weight-medium">
                            <a href="{{ route('product_evaluation.index', ['status' => 10, 'progress' => 70]) }}">
                                {{ $salesRpeAccepted ?? '0' }}
                            </a>
                        </h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title">SRF</p>
                    <div class="d-flex justify-content-between">
                        <div class="mb-3 mt-2">
                            <h3 class="text-primary fs-30 font-weight-medium">
                                {{ $totalSalesSRF ?? '0'}}
                                <i class="ti ti-package"></i>
                            </h3>
                        </div>
                    </div>
                    <div class="mb-1 d-flex justify-content-between">
                        <p>Closed</p>
                        <h5 class="text-primary font-weight-medium">
                            <a href="{{ route('sample_request.index', ['status' => 30]) }}" onclick='show()'>
                                {{ $salesSrfClosed ?? '0' }}
                            </a>
                        </h5>
                    </div>
                    <div class="mb-1 d-flex justify-content-between">
                        <p>Cancelled</p>
                        <h5 class="text-primary font-weight-medium">
                            <a href="{{ route('sample_request.index', ['status' => 50]) }}" onclick='show()'>
                                {{ $salesSrfCancelled ?? '0' }}
                            </a>
                        </h5>
                    </div>
                    <div class="mb-1 d-flex justify-content-between">
                        <p>Sales Approval</p>
                        <h5 class="text-primary font-weight-medium">
                            <a href="{{ route('sample_request.index', ['status' => 10, 'progress' => 10]) }}">
                                {{ $salesSrfApproval ?? '0' }}
                            </a>
                        </h5>
                    </div>
                    <div class="mb-1 d-flex justify-content-between">
                        <p>Sales Approved</p>
                        <h5 class="text-primary font-weight-medium">
                            <a href="{{ route('sample_request.index', ['status' => 10, 'progress' => 20]) }}">
                                {{ $salesSrfApproved ?? '0' }}
                            </a>
                        </h5>
                    </div>
                    <div class="mb-1 d-flex justify-content-between">
                        <p>Sales Accepted</p>
                        <h5 class="text-primary font-weight-medium">
                            <a href="{{ route('sample_request.index', ['status' => 10, 'progress' => 70]) }}">
                                {{ $salesSrfAccepted ?? '0' }}
                            </a>
                        </h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title">Price Request</p>
                    <div class="d-flex justify-content-between">
                        <div class="mb-3 mt-2">
                            <h3 class="text-primary fs-30 font-weight-medium">
                                {{ $totalPRFCount ?? '0'}}
                                <i class="ti ti-tag"></i>
                            </h3>
                        </div>
                    </div>
                    <div class="mb-1 d-flex justify-content-between">
                        <p>Closed</p>
                        <h5 class="text-primary font-weight-medium">
                            <a href="{{ route('price_monitoring.index', ['status' => 30]) }}" onclick='show()'>
                                {{ $salesPrfClosed ?? '0' }}
                            </a>
                        </h5>
                    </div>
                    <div class="mb-1 d-flex justify-content-between">
                        <p>Reopened</p>
                        <h5 class="text-primary font-weight-medium">
                            <a href="{{ route('price_monitoring.index', ['status' => 10, 'progress' => 25 ]) }}" onclick='show()'>
                                {{ $salesPrfReopened ?? '0' }}
                            </a>
                        </h5>
                    </div>
                    <div class="mb-1 d-flex justify-content-between">
                        <p>For Approval</p>
                        <h5 class="text-primary font-weight-medium">
                            <a href="{{ route('price_monitoring.index', ['status' => 10, 'progress' => 10 ]) }}" onclick='show()'>
                                {{ $salesPrfApproval ?? '0' }}
                            </a>
                        </h5>
                    </div>
                    <div class="mb-1 d-flex justify-content-between">
                        <p>Waiting Disposition</p>
                        <h5 class="text-primary font-weight-medium">
                            <a href="{{ route('price_monitoring.index', ['status' => 10, 'progress' => 20 ]) }}" onclick='show()'>
                                {{ $salesPrfWaiting ?? '0' }}
                            </a>
                        </h5>
                    </div>
                    <div class="mb-1 d-flex justify-content-between">
                        <p>Manager Approval</p>
                        <h5 class="text-primary font-weight-medium">
                            <a href="{{ route('price_monitoring.index', ['status' => 10, 'progress' => 40 ]) }}" onclick='show()'>
                                {{ $salesPrfManager ?? '0' }}
                            </a>
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

    
</div>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap4.js"></script>
<script>
    $('.tables').dataTable( {
        "dom": 'rtip'
    });
</script>
@endsection
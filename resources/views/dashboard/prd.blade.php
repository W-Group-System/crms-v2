@extends('layouts.header')
@section('title', 'Dashboard - CRMS')
@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin">
            <h3 class="font-weight-bold">Welcome back,&nbsp;{{auth()->user()->full_name}}!</h3>
            <h4 class="font-weight-normal mb-0" style="color: #7d7373">{{ date('l, d F') }} | <p style="font-size: 1.125rem;display: contents;" id="demo"></p></h4>
        </div>
    </div>
    <div class="row">
        @if ((optional($role)->name == 'Staff L2' || optional($role)->name == 'Department Admin') && (optional($role)->type == 'PRD'))
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
                                    {{ $approvalTransactions ?? '0' }}
                                    <i class="ti ti-check-box"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SPE</p>
                            <h5 class="font-weight-medium text-white">
                            <a href="{{ route('supplier_product.index', ['progress' => 10]) }}" class="text-white" onclick="show()">
                                    {{ $speApproval ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SSE</p>
                            <h5 class="font-weight-medium text-white">
                                <a href="{{ route('shipment_sample.index', ['progress' => 10]) }}" class="text-white" onclick="show()">
                                    {{ $sseApproval ?? '0' }}
                                </a>
                            </h5>
                        </div>
                    </div>
                </div>
                <!-- <div class="card card-light-blue">
                    <div class="card-body">
                        <p class="card-title text-white">Reconfirmatory Transactions</p>
                        <div class="d-flex justify-content-between">
                            <div class="mb-3 mt-2">
                                <h3 class="fs-30 font-weight-medium">
                                    {{ $totalActivitiesCount ?? '0'}}
                                    <i class="ti ti-share-alt"></i>
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
                </div> -->
            </div>
            <div class="col-md-3 grid-margin transparent">
                <div class="card mb-2 card-dark-blue">
                    <div class="card-body">
                        <p class="card-title text-white">Open Transactions</p>
                        <div class="d-flex justify-content-between">
                            <div class="mb-3 mt-2">
                                <h3 class="fs-30 font-weight-medium text-white">
                                    {{ $openTransactions ?? '0' }}
                                    <i class="ti ti-file"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SPE</p>
                            <h5 class="font-weight-medium text-white">
                                <a href="{{ route('supplier_product.index', ['status' => 10]) }}" class="text-white" onclick="show()">
                                    {{ $speOpen ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SSE</p>
                            <h5 class="font-weight-medium text-white">
                                <a href="{{ route('shipment_sample.index', ['status' => 10]) }}" class="text-white" onclick="show()">
                                    {{ $sseOpen ?? '0' }}
                                </a>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        @elseif ((optional($role)->name == 'Staff L1') && (optional($role)->type == 'PRD'))
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
                                    {{ $openTransactions ?? '0' }}
                                    <i class="ti ti-file"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SPE</p>
                            <h5 class="font-weight-medium text-white">
                                <a href="{{ route('supplier_product.index', ['status' => 10]) }}" class="text-white" onclick="show()">
                                    {{ $speOpen ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SSE</p>
                            <h5 class="font-weight-medium text-white">
                                <a href="{{ route('shipment_sample.index', ['status' => 10]) }}" class="text-white" onclick="show()">
                                    {{ $sseOpen ?? '0' }}
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
                                    {{ $closedTransactions ?? '0'}}
                                    <i class="ti ti-folder"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SPE</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('supplier_product.index', ['status' => 30]) }}" class="text-white" onclick='show()'>
                                    {{ $speClosed ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SSE</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('shipment_sample.index', ['status' => 30]) }}" class="text-white" onclick='show()'>
                                    {{ $sseClosed ?? '0' }}
                                </a>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="col-md-3 grid-margin transparent">
                <div class="card mb-3 card-dark-blue">
                    <div class="card-body">
                        <p class="card-title text-white">Reconfirmatory Transactions</p>
                        <div class="d-flex justify-content-between">
                            <div class="mb-3 mt-2">
                                <h3 class="fs-30 font-weight-medium text-white">
                                    {{ $totalReturned ?? '0' }}
                                    <i class="ti ti-share-alt"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SPE</p>
                            <h5 class="font-weight-medium text-white">
                                <a href="{{ route('supplier_product.index', ['return_to_sales' => 1]) }}" class="text-white" onclick="show()">
                                    {{ $salesCrrReturn ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SSE</p>
                            <h5 class="font-weight-medium text-white">
                                <a href="{{ route('shipment_sample.index', ['return_to_sales' => 1]) }}" class="text-white" onclick="show()">
                                    {{ $salesRpeReturn ?? '0' }}
                                </a>
                            </h5>
                        </div>
                    </div>
                </div>
            </div> -->
        @endif
    </div>
</div>
@endsection
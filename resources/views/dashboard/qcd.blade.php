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
        {{-- <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <form class="form-horizontal">
                        <h4 class="d-flex justify-content-between font-weight-bold mb-5">Account Information</h4>
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
        </div> --}}
        @if ((optional($role)->name == 'Staff L2' || optional($role)->name == 'Department Admin') && (optional($role)->type == 'QCD-WHI'))
            {{-- <div class="col-md-3 grid-margin transparent">
                <div class="card mb-2 card-tale">
                    <div class="card-body">
                        <p class="card-title text-white">Initial Review</p>
                        <div class="d-flex justify-content-between">
                            <div class="mb-3 mt-2">
                                <h3 class="text-white fs-30 font-weight-medium">
                                    {{ $totalQCDInitialReview2 ?? '0' }}
                                    <i class="ti ti-check"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>CRR</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('customer_requirement.index', ['progress' => 57]) }}" class="text-white" onclick='show()'>
                                    {{ $crrQCDInitialReview2 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SRF</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('sample_request.index', ['progress' => 57]) }}" class="text-white" onclick='show()'>
                                    {{ $srfQCDInitialReview2 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SPE</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('supplier_product.index', ['progress' => 55]) }}" class="text-white" onclick='show()'>
                                    {{ $speQCDInitialReview2 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SSE</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('shipment_sample.index', ['progress' => 55]) }}" class="text-white" onclick='show()'>
                                    {{ $sseQCDInitialReview2 ?? '0' }}
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
                                <h3 class="text-white fs-30 font-weight-medium">
                                    {{ $totalQCDFinalReview2 ?? '0'}}
                                    <i class="ti ti-check-box"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>CRR</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('customer_requirement.index', ['progress' => 30]) }}" class="text-white" onclick='show()'>
                                    {{ $crrQCDFinalReview2 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SRF</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('sample_request.index', ['progress' => 30]) }}" class="text-white" onclick='show()'>
                                    {{ $srfQCDFinalReview2 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                    </div>
                </div>
            </div> --}}
            {{-- <div class="col-md-3 grid-margin transparent">
                <div class="card mb-2 card-dark-blue">
                    <div class="card-body">
                        <p class="card-title text-white">New Request</p>
                        <div class="d-flex justify-content-between">
                            <div class="mb-3 mt-2">
                                <h3 class="fs-30 font-weight-medium text-white">
                                    {{ $totalQCD2New ?? '0' }}
                                    <i class="ti ti-file"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>CRR</p>
                            <h5 class="font-weight-medium text-white">
                                <a href="{{ route('customer_requirement.index', ['progress' => 30]) }}" class="text-white" onclick="show()">
                                    {{ $crrQCD2New ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SRF</p>
                            <h5 class="font-weight-medium text-white">
                                <a href="{{ route('sample_request.index', ['progress' => 30]) }}" class="text-white" onclick="show()">
                                    {{ $srfQCD2New ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SPE</p>
                            <h5 class="font-weight-medium text-white">
                                <a href="{{ route('supplier_product.index', ['progress' => 20]) }}" class="text-white" onclick="show()">
                                    {{ $speQCD2New ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SSE</p>
                            <h5 class="font-weight-medium text-white">
                                <a href="{{ route('shipment_sample.index', ['progress' => 20]) }}" class="text-white" onclick="show()">
                                    {{ $sseQCD2New ?? '0' }}
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
                                    {{ $totalDueToday2 ?? '0'}}
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
                                <a href="{{ route('customer_requirement.index', ['DueDate' => 'past']) }}" class="text-white" onclick="show()">
                                    {{ $crrDueToday2 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SRF</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('sample_request.index', ['DateRequired' => 'past']) }}" class="text-white" onclick="show()">
                                    {{ $srfDueToday2 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                    </div>
                </div>
            </div> --}}
            <div class="col-lg-6">
                <div class="row">
                    <div class="col-lg-12 mb-2">
                        <div class="card rounded-0 border border-1 border-primary">
                            <div class="card-body bg-primary">
                                <div class="row">
                                    <div class="col-6">
                                        <h1 class="m-0">
                                            <i class="ti-list text-white"></i>
                                        </h1>
                                    </div>
                                    <div class="col-6">
                                        <h1 class="m-0 text-right text-white">{{$total_open_transaction}}</h1>
                                        <p class="m-0 text-right text-white">Open Transactions</p>
                                    </div>
                                </div>
                            </div>
                            <a href="{{url('open-transaction')}}" class="text-decoration-none">
                                <div class="card-footer p-2">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <p class="m-0 text-primary">View Details</p>
                                        </div>
                                        <div class="col-lg-6 text-right">
                                            <i class="ti-arrow-circle-right text-primary"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    {{-- <div class="col-lg-12 mb-2">
                        <div class="card rounded-0 border border-1 border-success">
                            <div class="card-body bg-success">
                                <div class="row">
                                    <div class="col-6">
                                        <h1 class="m-0">
                                            <i class="ti-widget text-white"></i>
                                        </h1>
                                    </div>
                                    <div class="col-6">
                                        <h1 class="m-0 text-right text-white">0</h1>
                                        <p class="m-0 text-right text-white">New Product</p>
                                    </div>
                                </div>
                            </div>
                            <a href="{{url('new_products')}}" class="text-decoration-none">
                                <div class="card-footer p-2">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <p class="m-0 text-success">View Details</p>
                                        </div>
                                        <div class="col-lg-6 text-right">
                                            <i class="ti-arrow-circle-right text-success"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div> --}}
                    <div class="col-lg-12 mb-2">
                        <div class="card rounded-0 border border-1 border-primary">
                            <div class="card-body bg-primary">
                                <div class="row">
                                    <div class="col-6">
                                        <h1 class="m-0">
                                            <i class="ti-check-box text-white"></i>
                                        </h1>
                                    </div>
                                    <div class="col-6">
                                        <h1 class="m-0 text-right text-white">{{$total_initial_count}}</h1>
                                        <p class="m-0 text-right text-white">Initial Review</p>
                                    </div>
                                </div>
                            </div>
                            <a href="{{url('initial-review')}}" class="text-decoration-none">
                                <div class="card-footer p-2">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <p class="m-0 text-primary">View Details</p>
                                        </div>
                                        <div class="col-lg-6 text-right">
                                            <i class="ti-arrow-circle-right text-primary"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-12 mb-2">
                        <div class="card rounded-0 border border-1 border-warning">
                            <div class="card-body bg-warning">
                                <div class="row">
                                    <div class="col-6">
                                        <h1 class="m-0">
                                            <i class="ti-view-list-alt text-white"></i>
                                        </h1>
                                    </div>
                                    <div class="col-6">
                                        <h1 class="m-0 text-right text-white">{{$total_new_request_count}}</h1>
                                        <p class="m-0 text-right text-white">New Request</p>
                                    </div>
                                </div>
                            </div>
                            <a href="{{url('rnd-new-request')}}" class="text-decoration-none">
                                <div class="card-footer p-2">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <p class="m-0 text-warning">View Details</p>
                                        </div>
                                        <div class="col-lg-6 text-right">
                                            <i class="ti-arrow-circle-right text-warning"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-12 mb-2">
                        <div class="card rounded-0 border border-1 border-primary">
                            <div class="card-body bg-primary">
                                <div class="row">
                                    <div class="col-6">
                                        <h1 class="m-0">
                                            <i class="ti-check-box text-white"></i>
                                        </h1>
                                    </div>
                                    <div class="col-6">
                                        <h1 class="m-0 text-right text-white">{{$total_final_count}}</h1>
                                        <p class="m-0 text-right text-white">Final Review</p>
                                    </div>
                                </div>
                            </div>
                            <a href="{{url('final-review')}}" class="text-decoration-none">
                                <div class="card-footer p-2">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <p class="m-0 text-primary">View Details</p>
                                        </div>
                                        <div class="col-lg-6 text-right">
                                            <i class="ti-arrow-circle-right text-primary"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card rounded-0 border border-1 border-primary">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>CRR</th>
                                        <th>RPE</th>
                                        <th>SRF</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($user_transaction) > 0)
                                        @foreach ($user_transaction as $transactions)
                                            <tr>
                                                <td>{{$transactions->rnd}}</td>
                                                <td>{{$transactions->crr_count}}</td>
                                                <td>{{$transactions->rpe_count}}</td>
                                                <td>{{$transactions->srf_count}}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" class="text-center">No data available.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @elseif ((optional($role)->name == 'Staff L1') && (optional($role)->type == 'QCD-WHI'))
            {{-- <div class="col-lg-6 grid-margin transparent">
                <div class="card mb-2 card-tale">
                    <div class="card-body">
                        <p class="card-title text-white">Open Transactions</p>
                        <div class="d-flex justify-content-between">
                            <div class="mb-3 mt-2">
                                <h3 class="text-white fs-30 font-weight-medium">
                                    {{ $totalOpenQCD ?? '0' }}
                                    <i class="ti ti-check-box"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>CRR</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('customer_requirement.index', ['status' => 10]) }}" class="text-white" onclick='show()'>
                                    {{ $qcdCrrOpen ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SRF</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('sample_request.index', ['status' => 10]) }}" class="text-white" onclick='show()'>
                                    {{ $qcdSrfOpen ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SPE</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('supplier_product.index', ['status' => 10]) }}" class="text-white" onclick='show()'>
                                    {{ $qcdSpeOpen ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SSE</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('shipment_sample.index', ['status' => 10]) }}" class="text-white" onclick='show()'>
                                    {{ $qcdSseOpen ?? '0' }}
                                </a>
                            </h5>
                        </div>
                    </div>
                </div>
            </div> --}}
            {{-- <div class="col-md-3 grid-margin transparent">
                <div class="card mb-2 card-light-blue">
                    <div class="card-body">
                        <p class="card-title text-white">Closed Transactions</p>
                        <div class="d-flex justify-content-between">
                            <div class="mb-3 mt-2">
                                <h3 class="text-white fs-30 font-weight-medium">
                                    {{ $totalClosedQCD ?? '0'}}
                                    <i class="ti ti-folder"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>CRR</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('customer_requirement.index', ['status' => 30]) }}" class="text-white" onclick='show()'>
                                    {{ $qcdCrrClosed ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SRF</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('sample_request.index', ['status' => 30]) }}" class="text-white" onclick='show()'>
                                    {{ $qcdSrfClosed ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SPE</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('supplier_product.index', ['status' => 30]) }}" class="text-white" onclick='show()'>
                                    {{ $qcdSpeClosed ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SSE</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('shipment_sample.index', ['status' => 30]) }}" class="text-white" onclick='show()'>
                                    {{ $qcdSseClosed ?? '0' }}
                                </a>
                            </h5>
                        </div>
                    </div>
                </div>
            </div> --}}
            <div class="col-lg-6">
                <div class="card border border-1 border-primary rounded-0">
                    <div class="card-header bg-primary rounded-0">
                        <p class="m-0 text-white font-weight-bold">Account Information</p>
                    </div>
                    <div class="card-body">
                        <form class="form-horizontal">
                            {{-- <h4 class="d-flex justify-content-between font-weight-bold mb-4">Account Information</h4> --}}
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
            <div class="col-lg-6 mb-2">
                <div class="card rounded-0 border border-1 border-primary">
                    <div class="card-body bg-primary">
                        <div class="row">
                            <div class="col-6">
                                <h1 class="m-0">
                                    <i class="ti-list text-white"></i>
                                </h1>
                            </div>
                            <div class="col-6">
                                <h1 class="m-0 text-right text-white">{{$total_open_transaction}}</h1>
                                <p class="m-0 text-right text-white">Open Transactions</p>
                            </div>
                        </div>
                    </div>
                    <a href="{{url('open-transaction')}}" class="text-decoration-none">
                        <div class="card-footer p-2">
                            <div class="row">
                                <div class="col-lg-6">
                                    <p class="m-0 text-primary">View Details</p>
                                </div>
                                <div class="col-lg-6 text-right">
                                    <i class="ti-arrow-circle-right text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        @endif

        @if ((optional($role)->name == 'Staff L2' || optional($role)->name == 'Department Admin') && (optional($role)->type == 'QCD-PBI'))
            <div class="col-md-3 grid-margin transparent">
                <div class="card mb-2 card-tale">
                    <div class="card-body">
                        <p class="card-title text-white">Initial Review</p>
                        <div class="d-flex justify-content-between">
                            <div class="mb-3 mt-2">
                                <h3 class="text-white fs-30 font-weight-medium">
                                    {{ $totalQCDInitialReview3 ?? '0' }}
                                    <i class="ti ti-check"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>CRR</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('customer_requirement.index', ['progress' => 57]) }}" class="text-white" onclick='show()'>
                                    {{ $crrQCDInitialReview3 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SRF</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('sample_request.index', ['progress' => 57]) }}" class="text-white" onclick='show()'>
                                    {{ $srfQCDInitialReview3 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SPE</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('supplier_product.index', ['progress' => 55]) }}" class="text-white" onclick='show()'>
                                    {{ $speQCDInitialReview3 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SSE</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('shipment_sample.index', ['progress' => 55]) }}" class="text-white" onclick='show()'>
                                    {{ $sseQCDInitialReview3 ?? '0' }}
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
                                <h3 class="text-white fs-30 font-weight-medium">
                                    {{ $totalQCDFinalReview3 ?? '0'}}
                                    <i class="ti ti-check-box"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>CRR</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('customer_requirement.index', ['progress' => 30]) }}" class="text-white" onclick='show()'>
                                    {{ $crrQCDFinalReview3 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SRF</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('sample_request.index', ['progress' => 30]) }}" class="text-white" onclick='show()'>
                                    {{ $srfQCDFinalReview3 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 grid-margin transparent">
                <div class="card mb-2 card-dark-blue">
                    <div class="card-body">
                        <p class="card-title text-white">New Request</p>
                        <div class="d-flex justify-content-between">
                            <div class="mb-3 mt-2">
                                <h3 class="fs-30 font-weight-medium text-white">
                                    {{ $totalQCD3New ?? '0' }}
                                    <i class="ti ti-file"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>CRR</p>
                            <h5 class="font-weight-medium text-white">
                                <a href="{{ route('customer_requirement.index', ['progress' => 30]) }}" class="text-white" onclick="show()">
                                    {{ $crrQCD3New ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SRF</p>
                            <h5 class="font-weight-medium text-white">
                                <a href="{{ route('sample_request.index', ['progress' => 30]) }}" class="text-white" onclick="show()">
                                    {{ $srfQCD3New ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SPE</p>
                            <h5 class="font-weight-medium text-white">
                                <a href="{{ route('supplier_product.index', ['progress' => 20]) }}" class="text-white" onclick="show()">
                                    {{ $speQCD3New ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SSE</p>
                            <h5 class="font-weight-medium text-white">
                                <a href="{{ route('shipment_sample.index', ['progress' => 20]) }}" class="text-white" onclick="show()">
                                    {{ $sseQCD3New ?? '0' }}
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
                                    {{ $totalDueToday3 ?? '0'}}
                                    <i class="ti ti-bell"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>CRR</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('customer_requirement.index', ['DueDate' => 'past']) }}" class="text-white" onclick="show()">
                                    {{ $crrDueToday3 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SRF</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('sample_request.index', ['DateRequired' => 'past']) }}" class="text-white" onclick="show()">
                                    {{ $srfDueToday3 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        @elseif ((optional($role)->name == 'Staff L1') && (optional($role)->type == 'QCD-PBI'))
            <div class="col-md-3 grid-margin transparent">
                <div class="card mb-2 card-tale">
                    <div class="card-body">
                        <p class="card-title text-white">Open Transactions</p>
                        <div class="d-flex justify-content-between">
                            <div class="mb-3 mt-2">
                                <h3 class="text-white fs-30 font-weight-medium">
                                    {{ $totalOpenQCD ?? '0' }}
                                    <i class="ti ti-check-box"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>CRR</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('customer_requirement.index', ['status' => 10]) }}" class="text-white" onclick='show()'>
                                    {{ $qcdCrrOpen ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SRF</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('sample_request.index', ['status' => 10]) }}" class="text-white" onclick='show()'>
                                    {{ $qcdSrfOpen ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SPE</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('supplier_product.index', ['status' => 10]) }}" class="text-white" onclick='show()'>
                                    {{ $qcdSpeOpen ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SSE</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('shipment_sample.index', ['status' => 10]) }}" class="text-white" onclick='show()'>
                                    {{ $qcdSseOpen ?? '0' }}
                                </a>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 grid-margin transparent">
                <div class="card mb-2 card-light-blue">
                    <div class="card-body">
                        <p class="card-title text-white">Closed Transactions</p>
                        <div class="d-flex justify-content-between">
                            <div class="mb-3 mt-2">
                                <h3 class="text-white fs-30 font-weight-medium">
                                    {{ $totalClosedQCD ?? '0'}}
                                    <i class="ti ti-folder"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>CRR</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('customer_requirement.index', ['status' => 30]) }}" class="text-white" onclick='show()'>
                                    {{ $qcdCrrClosed ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SRF</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('sample_request.index', ['status' => 30]) }}" class="text-white" onclick='show()'>
                                    {{ $qcdSrfClosed ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SPE</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('supplier_product.index', ['status' => 30]) }}" class="text-white" onclick='show()'>
                                    {{ $qcdSpeClosed ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SSE</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('shipment_sample.index', ['status' => 30]) }}" class="text-white" onclick='show()'>
                                    {{ $qcdSseClosed ?? '0' }}
                                </a>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if ((optional($role)->name == 'Staff L2' || optional($role)->name == 'Department Admin') && (optional($role)->type == 'QCD-MRDC'))
            <div class="col-md-3 grid-margin transparent">
                <div class="card mb-2 card-tale">
                    <div class="card-body">
                        <p class="card-title text-white">Initial Review</p>
                        <div class="d-flex justify-content-between">
                            <div class="mb-3 mt-2">
                                <h3 class="text-white fs-30 font-weight-medium">
                                    {{ $totalQCDInitialReview4 ?? '0' }}
                                    <i class="ti ti-check"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>CRR</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('customer_requirement.index', ['progress' => 57]) }}" class="text-white" onclick='show()'>
                                    {{ $crrQCDInitialReview4 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SRF</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('sample_request.index', ['progress' => 57]) }}" class="text-white" onclick='show()'>
                                    {{ $srfQCDInitialReview4 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SPE</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('supplier_product.index', ['progress' => 55]) }}" class="text-white" onclick='show()'>
                                    {{ $speQCDInitialReview4 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SSE</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('shipment_sample.index', ['progress' => 55]) }}" class="text-white" onclick='show()'>
                                    {{ $sseQCDInitialReview4 ?? '0' }}
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
                                <h3 class="text-white fs-30 font-weight-medium">
                                    {{ $totalQCDFinalReview4 ?? '0'}}
                                    <i class="ti ti-check-box"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>CRR</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('customer_requirement.index', ['progress' => 30]) }}" class="text-white" onclick='show()'>
                                    {{ $crrQCDFinalReview4 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SRF</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('sample_request.index', ['progress' => 30]) }}" class="text-white" onclick='show()'>
                                    {{ $srfQCDFinalReview4 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 grid-margin transparent">
                <div class="card mb-2 card-dark-blue">
                    <div class="card-body">
                        <p class="card-title text-white">New Request</p>
                        <div class="d-flex justify-content-between">
                            <div class="mb-3 mt-2">
                                <h3 class="fs-30 font-weight-medium text-white">
                                    {{ $totalQCD4New ?? '0' }}
                                    <i class="ti ti-file"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>CRR</p>
                            <h5 class="font-weight-medium text-white">
                                <a href="{{ route('customer_requirement.index', ['progress' => 30]) }}" class="text-white" onclick="show()">
                                    {{ $crrQCD4New ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SRF</p>
                            <h5 class="font-weight-medium text-white">
                                <a href="{{ route('sample_request.index', ['progress' => 30]) }}" class="text-white" onclick="show()">
                                    {{ $srfQCD4New ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SPE</p>
                            <h5 class="font-weight-medium text-white">
                                <a href="{{ route('supplier_product.index', ['progress' => 20]) }}" class="text-white" onclick="show()">
                                    {{ $speQCD4New ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SSE</p>
                            <h5 class="font-weight-medium text-white">
                                <a href="{{ route('shipment_sample.index', ['progress' => 20]) }}" class="text-white" onclick="show()">
                                    {{ $sseQCD4New ?? '0' }}
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
                                    {{ $totalDueToday4 ?? '0'}}
                                    <i class="ti ti-bell"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>CRR</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('customer_requirement.index', ['DueDate' => 'past']) }}" class="text-white" onclick="show()">
                                    {{ $crrDueToday4 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SRF</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('sample_request.index', ['DateRequired' => 'past']) }}" class="text-white" onclick="show()">
                                    {{ $srfDueToday4 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        @elseif ((optional($role)->name == 'Staff L1') && (optional($role)->type == 'QCD-MRDC'))
            <div class="col-md-3 grid-margin transparent">
                <div class="card mb-2 card-tale">
                    <div class="card-body">
                        <p class="card-title text-white">Open Transactions</p>
                        <div class="d-flex justify-content-between">
                            <div class="mb-3 mt-2">
                                <h3 class="text-white fs-30 font-weight-medium">
                                    {{ $totalOpenQCD ?? '0' }}
                                    <i class="ti ti-check-box"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>CRR</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('customer_requirement.index', ['status' => 10]) }}" class="text-white" onclick='show()'>
                                    {{ $qcdCrrOpen ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SRF</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('sample_request.index', ['status' => 10]) }}" class="text-white" onclick='show()'>
                                    {{ $qcdSrfOpen ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SPE</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('supplier_product.index', ['status' => 10]) }}" class="text-white" onclick='show()'>
                                    {{ $qcdSpeOpen ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SSE</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('shipment_sample.index', ['status' => 10]) }}" class="text-white" onclick='show()'>
                                    {{ $qcdSseOpen ?? '0' }}
                                </a>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 grid-margin transparent">
                <div class="card mb-2 card-light-blue">
                    <div class="card-body">
                        <p class="card-title text-white">Closed Transactions</p>
                        <div class="d-flex justify-content-between">
                            <div class="mb-3 mt-2">
                                <h3 class="text-white fs-30 font-weight-medium">
                                    {{ $totalClosedQCD ?? '0'}}
                                    <i class="ti ti-folder"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>CRR</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('customer_requirement.index', ['status' => 30]) }}" class="text-white" onclick='show()'>
                                    {{ $qcdCrrClosed ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SRF</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('sample_request.index', ['status' => 30]) }}" class="text-white" onclick='show()'>
                                    {{ $qcdSrfClosed ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SPE</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('supplier_product.index', ['status' => 30]) }}" class="text-white" onclick='show()'>
                                    {{ $qcdSpeClosed ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SSE</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('shipment_sample.index', ['status' => 30]) }}" class="text-white" onclick='show()'>
                                    {{ $qcdSseClosed ?? '0' }}
                                </a>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if ((optional($role)->name == 'Staff L2' || optional($role)->name == 'Department Admin') && (optional($role)->type == 'QCD-CCC'))
            <div class="col-md-3 grid-margin transparent">
                <div class="card mb-2 card-tale">
                    <div class="card-body">
                        <p class="card-title text-white">Initial Review</p>
                        <div class="d-flex justify-content-between">
                            <div class="mb-3 mt-2">
                                <h3 class="text-white fs-30 font-weight-medium">
                                    {{ $totalQCDInitialReview5 ?? '0' }}
                                    <i class="ti ti-check"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>CRR</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('customer_requirement.index', ['progress' => 57]) }}" class="text-white" onclick='show()'>
                                    {{ $crrQCDInitialReview5 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SRF</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('sample_request.index', ['progress' => 57]) }}" class="text-white" onclick='show()'>
                                    {{ $srfQCDInitialReview5 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SPE</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('supplier_product.index', ['progress' => 55]) }}" class="text-white" onclick='show()'>
                                    {{ $speQCDInitialReview5 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SSE</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('shipment_sample.index', ['progress' => 55]) }}" class="text-white" onclick='show()'>
                                    {{ $sseQCDInitialReview5 ?? '0' }}
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
                                <h3 class="text-white fs-30 font-weight-medium">
                                    {{ $totalQCDFinalReview5 ?? '0'}}
                                    <i class="ti ti-check-box"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>CRR</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('customer_requirement.index', ['progress' => 30]) }}" class="text-white" onclick='show()'>
                                    {{ $crrQCDFinalReview5 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SRF</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('sample_request.index', ['progress' => 30]) }}" class="text-white" onclick='show()'>
                                    {{ $srfQCDFinalReview5 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 grid-margin transparent">
                <div class="card mb-2 card-dark-blue">
                    <div class="card-body">
                        <p class="card-title text-white">New Request</p>
                        <div class="d-flex justify-content-between">
                            <div class="mb-3 mt-2">
                                <h3 class="fs-30 font-weight-medium text-white">
                                    {{ $totalQCD5New ?? '0' }}
                                    <i class="ti ti-file"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>CRR</p>
                            <h5 class="font-weight-medium text-white">
                                <a href="{{ route('customer_requirement.index', ['progress' => 30]) }}" class="text-white" onclick="show()">
                                    {{ $crrQCD5New ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SRF</p>
                            <h5 class="font-weight-medium text-white">
                                <a href="{{ route('sample_request.index', ['progress' => 30]) }}" class="text-white" onclick="show()">
                                    {{ $srfQCD5New ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SPE</p>
                            <h5 class="font-weight-medium text-white">
                                <a href="{{ route('supplier_product.index', ['progress' => 20]) }}" class="text-white" onclick="show()">
                                    {{ $speQCD5New ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SSE</p>
                            <h5 class="font-weight-medium text-white">
                                <a href="{{ route('shipment_sample.index', ['progress' => 20]) }}" class="text-white" onclick="show()">
                                    {{ $sseQCD5New ?? '0' }}
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
                                    {{ $totalDueToday5 ?? '0'}}
                                    <i class="ti ti-bell"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>CRR</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('customer_requirement.index', ['DueDate' => 'past']) }}" class="text-white" onclick="show()">
                                    {{ $crrDueToday5 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SRF</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('sample_request.index', ['DateRequired' => 'past']) }}" class="text-white" onclick="show()">
                                    {{ $srfDueToday5 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        @elseif ((optional($role)->name == 'Staff L1') && (optional($role)->type == 'QCD-CCC'))
            <div class="col-md-3 grid-margin transparent">
                <div class="card mb-2 card-tale">
                    <div class="card-body">
                        <p class="card-title text-white">Open Transactions</p>
                        <div class="d-flex justify-content-between">
                            <div class="mb-3 mt-2">
                                <h3 class="text-white fs-30 font-weight-medium">
                                    {{ $totalOpenQCD ?? '0' }}
                                    <i class="ti ti-check-box"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>CRR</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('customer_requirement.index', ['status' => 10]) }}" class="text-white" onclick='show()'>
                                    {{ $qcdCrrOpen ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SRF</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('sample_request.index', ['status' => 10]) }}" class="text-white" onclick='show()'>
                                    {{ $qcdSrfOpen ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SPE</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('supplier_product.index', ['status' => 10]) }}" class="text-white" onclick='show()'>
                                    {{ $qcdSpeOpen ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SSE</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('shipment_sample.index', ['status' => 10]) }}" class="text-white" onclick='show()'>
                                    {{ $qcdSseOpen ?? '0' }}
                                </a>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 grid-margin transparent">
                <div class="card mb-2 card-light-blue">
                    <div class="card-body">
                        <p class="card-title text-white">Closed Transactions</p>
                        <div class="d-flex justify-content-between">
                            <div class="mb-3 mt-2">
                                <h3 class="text-white fs-30 font-weight-medium">
                                    {{ $totalClosedQCD ?? '0'}}
                                    <i class="ti ti-folder"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>CRR</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('customer_requirement.index', ['status' => 30]) }}" class="text-white" onclick='show()'>
                                    {{ $qcdCrrClosed ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SRF</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('sample_request.index', ['status' => 30]) }}" class="text-white" onclick='show()'>
                                    {{ $qcdSrfClosed ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SPE</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('supplier_product.index', ['status' => 30]) }}" class="text-white" onclick='show()'>
                                    {{ $qcdSpeClosed ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SSE</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('shipment_sample.index', ['status' => 30]) }}" class="text-white" onclick='show()'>
                                    {{ $qcdSseClosed ?? '0' }}
                                </a>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- @elseif ((optional($role)->name == 'Staff L2' || optional($role)->name == 'Department Admin') && (optional($role)->type == 'QCD-PBI'))
            <div class="col-md-3 grid-margin transparent">
                <div class="card mb-2 card-tale">
                    <div class="card-body">
                        <p class="card-title text-white">Initial Review</p>
                        <div class="d-flex justify-content-between">
                            <div class="mb-3 mt-2">
                                <h3 class="text-white fs-30 font-weight-medium">
                                    {{ $totalQCDInitialReview3 ?? '0' }}
                                    <i class="ti ti-check"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>CRR</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('customer_requirement.index', ['progress' => 57]) }}" class="text-white" onclick='show()'>
                                    {{ $crrQCDInitialReview3 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SRF</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('sample_request.index', ['progress' => 57]) }}" class="text-white" onclick='show()'>
                                    {{ $srfQCDInitialReview3 ?? '0' }}
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
                                <h3 class="text-white fs-30 font-weight-medium">
                                    {{ $totalQCDFinalReview3 ?? '0'}}
                                    <i class="ti ti-check-box"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>CRR</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('customer_requirement.index', ['progress' => 30]) }}" class="text-white" onclick='show()'>
                                    {{ $crrQCDFinalReview3 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SRF</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('sample_request.index', ['progress' => 30]) }}" class="text-white" onclick='show()'>
                                    {{ $srfQCDFinalReview3 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 grid-margin transparent">
                <div class="card mb-2 card-dark-blue">
                    <div class="card-body">
                        <p class="card-title text-white">New Request</p>
                        <div class="d-flex justify-content-between">
                            <div class="mb-3 mt-2">
                                <h3 class="fs-30 font-weight-medium text-white">
                                    {{ $totalQCD3New ?? '0' }}
                                    <i class="ti ti-file"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>CRR</p>
                            <h5 class="font-weight-medium text-white">
                                <a href="{{ route('customer_requirement.index', ['progress' => 30]) }}" class="text-white" onclick="show()">
                                    {{ $crrQCD3New ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SRF</p>
                            <h5 class="font-weight-medium text-white">
                                <a href="{{ route('sample_request.index', ['progress' => 30]) }}" class="text-white" onclick="show()">
                                    {{ $srfQCD3New ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SPE</p>
                            <h5 class="font-weight-medium text-white">
                                <a href="{{ route('supplier_product.index', ['progress' => 20]) }}" class="text-white" onclick="show()">
                                    {{ $speQCD3New ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SSE</p>
                            <h5 class="font-weight-medium text-white">
                                <a href="{{ route('shipment_sample.index', ['progress' => 20]) }}" class="text-white" onclick="show()">
                                    {{ $sseQCD3New ?? '0' }}
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
                                    {{ $totalDueToday3 ?? '0'}}
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
                                <a href="{{ route('customer_requirement.index', ['DueDate' => 'past']) }}" class="text-white" onclick="show()">
                                    {{ $crrDueToday3 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SRF</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('sample_request.index', ['DateRequired' => 'past']) }}" class="text-white" onclick="show()">
                                    {{ $srfDueToday3 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        @elseif ((optional($role)->name == 'Staff L2' || optional($role)->name == 'Department Admin') && (optional($role)->type == 'QCD-MRDC'))
            <div class="col-md-3 grid-margin transparent">
                <div class="card mb-2 card-tale">
                    <div class="card-body">
                        <p class="card-title text-white">Initial Review</p>
                        <div class="d-flex justify-content-between">
                            <div class="mb-3 mt-2">
                                <h3 class="text-white fs-30 font-weight-medium">
                                    {{ $totalQCDInitialReview4 ?? '0' }}
                                    <i class="ti ti-check"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>CRR</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('customer_requirement.index', ['progress' => 57]) }}" class="text-white" onclick='show()'>
                                    {{ $crrQCDInitialReview4 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SRF</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('sample_request.index', ['progress' => 57]) }}" class="text-white" onclick='show()'>
                                    {{ $srfQCDInitialReview4 ?? '0' }}
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
                                <h3 class="text-white fs-30 font-weight-medium">
                                    {{ $totalQCDFinalReview4 ?? '0'}}
                                    <i class="ti ti-check-box"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>CRR</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('customer_requirement.index', ['progress' => 30]) }}" class="text-white" onclick='show()'>
                                    {{ $crrQCDFinalReview4 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SRF</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('sample_request.index', ['progress' => 30]) }}" class="text-white" onclick='show()'>
                                    {{ $srfQCDFinalReview4 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 grid-margin transparent">
                <div class="card mb-2 card-dark-blue">
                    <div class="card-body">
                        <p class="card-title text-white">New Request</p>
                        <div class="d-flex justify-content-between">
                            <div class="mb-3 mt-2">
                                <h3 class="fs-30 font-weight-medium text-white">
                                    {{ $totalQCD4New ?? '0' }}
                                    <i class="ti ti-file"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>CRR</p>
                            <h5 class="font-weight-medium text-white">
                                <a href="{{ route('customer_requirement.index', ['progress' => 30]) }}" class="text-white" onclick="show()">
                                    {{ $crrQCD4New ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SRF</p>
                            <h5 class="font-weight-medium text-white">
                                <a href="{{ route('sample_request.index', ['progress' => 30]) }}" class="text-white" onclick="show()">
                                    {{ $srfQCD4New ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SPE</p>
                            <h5 class="font-weight-medium text-white">
                                <a href="{{ route('supplier_product.index', ['progress' => 20]) }}" class="text-white" onclick="show()">
                                    {{ $speQCD4New ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SSE</p>
                            <h5 class="font-weight-medium text-white">
                                <a href="{{ route('shipment_sample.index', ['progress' => 20]) }}" class="text-white" onclick="show()">
                                    {{ $sseQCD4New ?? '0' }}
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
                                    {{ $totalDueToday4 ?? '0'}}
                                    <i class="ti ti-bell"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>CRR</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('customer_requirement.index', ['DueDate' => 'past']) }}" class="text-white" onclick="show()">
                                    {{ $crrDueToday4 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SRF</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('sample_request.index', ['DateRequired' => 'past']) }}" class="text-white" onclick="show()">
                                    {{ $srfDueToday4 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        @elseif ((optional($role)->name == 'Staff L2' || optional($role)->name == 'Department Admin') && (optional($role)->type == 'QCD-CCC'))
            <div class="col-md-3 grid-margin transparent">
                <div class="card mb-2 card-tale">
                    <div class="card-body">
                        <p class="card-title text-white">Initial Review</p>
                        <div class="d-flex justify-content-between">
                            <div class="mb-3 mt-2">
                                <h3 class="text-white fs-30 font-weight-medium">
                                    {{ $totalQCDInitialReview5 ?? '0' }}
                                    <i class="ti ti-check"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>CRR</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('customer_requirement.index', ['progress' => 57]) }}" class="text-white" onclick='show()'>
                                    {{ $crrQCDInitialReview5 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SRF</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('sample_request.index', ['progress' => 57]) }}" class="text-white" onclick='show()'>
                                    {{ $srfQCDInitialReview5 ?? '0' }}
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
                                <h3 class="text-white fs-30 font-weight-medium">
                                    {{ $totalQCDFinalReview5 ?? '0'}}
                                    <i class="ti ti-check-box"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>CRR</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('customer_requirement.index', ['progress' => 30]) }}" class="text-white" onclick='show()'>
                                    {{ $crrQCDFinalReview5 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SRF</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('sample_request.index', ['progress' => 30]) }}" class="text-white" onclick='show()'>
                                    {{ $srfQCDFinalReview5 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 grid-margin transparent">
                <div class="card mb-2 card-dark-blue">
                    <div class="card-body">
                        <p class="card-title text-white">New Request</p>
                        <div class="d-flex justify-content-between">
                            <div class="mb-3 mt-2">
                                <h3 class="fs-30 font-weight-medium text-white">
                                    {{ $totalQCD5New ?? '0' }}
                                    <i class="ti ti-file"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>CRR</p>
                            <h5 class="font-weight-medium text-white">
                                <a href="{{ route('customer_requirement.index', ['progress' => 30]) }}" class="text-white" onclick="show()">
                                    {{ $crrQCD5New ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SRF</p>
                            <h5 class="font-weight-medium text-white">
                                <a href="{{ route('sample_request.index', ['progress' => 30]) }}" class="text-white" onclick="show()">
                                    {{ $srfQCD5New ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SPE</p>
                            <h5 class="font-weight-medium text-white">
                                <a href="{{ route('supplier_product.index', ['progress' => 20]) }}" class="text-white" onclick="show()">
                                    {{ $speQCD5New ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SSE</p>
                            <h5 class="font-weight-medium text-white">
                                <a href="{{ route('shipment_sample.index', ['progress' => 20]) }}" class="text-white" onclick="show()">
                                    {{ $sseQCD5New ?? '0' }}
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
                                    {{ $totalDueToday5 ?? '0'}}
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
                                <a href="{{ route('customer_requirement.index', ['DueDate' => 'past']) }}" class="text-white" onclick="show()">
                                    {{ $crrDueToday5 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>SRF</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('sample_request.index', ['DateRequired' => 'past']) }}" class="text-white" onclick="show()">
                                    {{ $srfDueToday5 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        @endif --}}
    </div>
    {{-- <div class="row">
        @if ((optional($role)->name == 'Staff L2' || optional($role)->name == 'Department Admin') && (optional($role)->type == 'QCD-WHI'))
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <p class="card-title">Customer Requirement</p>
                        <div class="d-flex justify-content-between">
                            <div class="mb-3 mt-2">
                                <h3 class="text-primary fs-30 font-weight-medium">
                                    {{ $totalCrrImmediate2 ?? '0'}}
                                    <i class="ti ti-user"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Open</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('customer_requirement.index', ['status' => 10]) }}" onclick="show()">
                                    {{ $crrImmediateOpen2 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Closed</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('customer_requirement.index', ['status' => 30]) }}" onclick="show()">
                                    {{ $crrImmediateClosed2 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Cancelled</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('customer_requirement.index', ['status' => 50]) }}" onclick="show()">
                                    {{ $crrImmediateCancelled2 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <p class="card-title">Sample Request</p>
                        <div class="d-flex justify-content-between">
                            <div class="mb-4 mt-2">
                                <h3 class="text-primary fs-30 font-weight-medium">
                                    {{ $totalSrfImmediate2 ?? '0'}}
                                    <i class="ti ti-package"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Open</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('sample_request.index', ['status' => 10]) }}" onclick='show()'>
                                    {{ $srfImmediateOpen2 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Closed</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('sample_request.index', ['status' => 30]) }}" onclick='show()'>
                                    {{ $srfImmediateClosed2 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Cancelled</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('sample_request.index', ['status' => 50]) }}" onclick="show()">
                                    {{ $srfImmediateCancelled2 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        @elseif ((optional($role)->name == 'Staff L2' || optional($role)->name == 'Department Admin') && (optional($role)->type == 'QCD-PBI'))
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <p class="card-title">Customer Requirement</p>
                        <div class="d-flex justify-content-between">
                            <div class="mb-3 mt-2">
                                <h3 class="text-primary fs-30 font-weight-medium">
                                    {{ $totalCrrImmediate3 ?? '0'}}
                                    <i class="ti ti-user"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Open</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('customer_requirement.index', ['status' => 10]) }}" onclick="show()">
                                    {{ $crrImmediateOpen3 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Closed</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('customer_requirement.index', ['status' => 30]) }}" onclick="show()">
                                    {{ $crrImmediateClosed3 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Cancelled</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('customer_requirement.index', ['status' => 50]) }}" onclick="show()">
                                    {{ $crrImmediateCancelled3 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <p class="card-title">Sample Request</p>
                        <div class="d-flex justify-content-between">
                            <div class="mb-4 mt-2">
                                <h3 class="text-primary fs-30 font-weight-medium">
                                    {{ $totalSrfImmediate3 ?? '0'}}
                                    <i class="ti ti-package"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Open</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('sample_request.index', ['status' => 10]) }}" onclick='show()'>
                                    {{ $srfImmediateOpen3 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Closed</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('sample_request.index', ['status' => 30]) }}" onclick='show()'>
                                    {{ $srfImmediateClosed3 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Cancelled</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('sample_request.index', ['status' => 50]) }}" onclick="show()">
                                    {{ $srfImmediateCancelled3 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        @elseif ((optional($role)->name == 'Staff L2' || optional($role)->name == 'Department Admin') && (optional($role)->type == 'QCD-MRDC'))
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <p class="card-title">Customer Requirement</p>
                        <div class="d-flex justify-content-between">
                            <div class="mb-3 mt-2">
                                <h3 class="text-primary fs-30 font-weight-medium">
                                    {{ $totalCrrImmediate4 ?? '0'}}
                                    <i class="ti ti-user"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Open</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('customer_requirement.index', ['status' => 10]) }}" onclick="show()">
                                    {{ $crrImmediateOpen4 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Closed</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('customer_requirement.index', ['status' => 30]) }}" onclick="show()">
                                    {{ $crrImmediateClosed4 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Cancelled</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('customer_requirement.index', ['status' => 50]) }}" onclick="show()">
                                    {{ $crrImmediateCancelled4 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <p class="card-title">Sample Request</p>
                        <div class="d-flex justify-content-between">
                            <div class="mb-4 mt-2">
                                <h3 class="text-primary fs-30 font-weight-medium">
                                    {{ $totalSrfImmediate4 ?? '0'}}
                                    <i class="ti ti-package"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Open</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('sample_request.index', ['status' => 10]) }}" onclick='show()'>
                                    {{ $srfImmediateOpen4 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Closed</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('sample_request.index', ['status' => 30]) }}" onclick='show()'>
                                    {{ $srfImmediateClosed4 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Cancelled</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('sample_request.index', ['status' => 50]) }}" onclick="show()">
                                    {{ $srfImmediateCancelled4 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        @elseif ((optional($role)->name == 'Staff L2' || optional($role)->name == 'Department Admin') && (optional($role)->type == 'QCD-CCC'))
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <p class="card-title">Customer Requirement</p>
                        <div class="d-flex justify-content-between">
                            <div class="mb-3 mt-2">
                                <h3 class="text-primary fs-30 font-weight-medium">
                                    {{ $totalCrrImmediate5 ?? '0'}}
                                    <i class="ti ti-user"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Open</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('customer_requirement.index', ['status' => 10]) }}" onclick="show()">
                                    {{ $crrImmediateOpen5 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Closed</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('customer_requirement.index', ['status' => 30]) }}" onclick="show()">
                                    {{ $crrImmediateClosed5 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Cancelled</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('customer_requirement.index', ['status' => 50]) }}" onclick="show()">
                                    {{ $crrImmediateCancelled5 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <p class="card-title">Sample Request</p>
                        <div class="d-flex justify-content-between">
                            <div class="mb-4 mt-2">
                                <h3 class="text-primary fs-30 font-weight-medium">
                                    {{ $totalSrfImmediate5 ?? '0'}}
                                    <i class="ti ti-package"></i>
                                </h3>
                            </div>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Open</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('sample_request.index', ['status' => 10]) }}" onclick='show()'>
                                    {{ $srfImmediateOpen5 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Closed</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('sample_request.index', ['status' => 30]) }}" onclick='show()'>
                                    {{ $srfImmediateClosed5 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                        <div class="mb-1 d-flex justify-content-between">
                            <p>Cancelled</p>
                            <h5 class="text-primary font-weight-medium">
                                <a href="{{ route('sample_request.index', ['status' => 50]) }}" onclick="show()">
                                    {{ $srfImmediateCancelled5 ?? '0' }}
                                </a>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div> --}}
</div>

@endsection
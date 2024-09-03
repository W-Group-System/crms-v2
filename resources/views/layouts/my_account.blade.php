@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h3 class="d-flex justify-content-between font-weight-bold mb-5">My Account</h3>
            <form class="form-horizontal">
                <h4 class="d-flex justify-content-between font-weight-bold mb-4">Account Information</h4>
                <div class="form-group row mb-2" style="margin-top: 2em">
                    <label class="col-sm-2 col-form-label"><b>Name</b></label>
                    <div class="col-sm-10">
                        <label>{{auth()->user()->full_name}}</label>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-2 col-form-label"><b>System Role</b></label>
                    <div class="col-sm-10">
                        <label>{{ auth()->user()->role->name ?? 'No Role Assigned' }}</label>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-2 col-form-label"><b>Company</b></label>
                    <div class="col-sm-10">
                        <label>{{auth()->user()->company->name}}</label>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-2 col-form-label"><b>Department</b></label>
                    <div class="col-sm-10">
                        <label>{{auth()->user()->department->name}}</label>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-2 col-form-label"><b>Username</b></label>
                    <div class="col-sm-10">
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
@endsection
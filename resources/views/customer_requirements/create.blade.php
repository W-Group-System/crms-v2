@extends('layouts.header')
@section('content')
<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            Add New Customer Requirement
            <a href="{{ url('/customer_requirement') }}" class="btn btn-md btn-light"><i class="icon-arrow-left"></i>&nbsp;Back</a>
            </h4>
            <form method="POST" action="{{ route('client.store') }}" enctype="multipart/form-data">
                @csrf
                <span id="form_result"></span>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Date Created (DD/MM/YYYY) - Hour Minute</label>
                            <input type="datetime-local" class="form-control" id="DateCreated" name="DateCreated">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Client</label>
                            <select class="form-control js-example-basic-single" name="ClientId" id="ClientId" style="position: relative !important" title="Select Client">
                                <option value="" disabled selected>Select Client</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->Name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>SAP Code</label>
                            <input type="text" class="form-control" id="SapCode" name="SapCode" placeholder="Enter SAP Code">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Secondary Account Manager</label>
                            <select class="form-control js-example-basic-single" name="SecondaryAccountManagerId" id="SecondaryAccountManagerId" style="position: relative !important" title="Select Account Manager">
                                <option value="" disabled selected>Select Account Manager</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Company Name</label>
                            <input type="text" class="form-control" name="Name" placeholder="Enter Company Name" required>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Trade Name</label>
                            <input type="text" class="form-control" id="TradeName" name="TradeName" placeholder="Enter Trade Name">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>TIN</label>
                            <input type="text" class="form-control" id="TaxIdentificationNumber" name="TaxIdentificationNumber" placeholder="Enter TIN No.">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Telephone</label>
                            <input type="text" class="form-control" id="TelephoneNumber" name="TelephoneNumber" placeholder="Enter Telephone Number">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection


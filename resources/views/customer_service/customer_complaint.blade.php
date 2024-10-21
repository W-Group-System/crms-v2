@extends('layouts.cs_header')
@section('content')
<div class="col-12 text-center">
    <img src="{{ asset('images/wgroup1.png') }}" style="width: 180px;">
    <h2 class="header_h2">Customer Complaint Form</h2>
</div>
<form id="form_complaint" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="CcNumber">
    <input type="hidden" name="Status" value="10">
    <div class="row col-lg-12 mt-3" style="margin-left: 0px">
        <div class="offset-lg-1 col-lg-5">
            <div class="form-group">
                <label class="text-white display-5">Company Name</label>
                <input type="text" class="form-control" name="CompanyName" id="CompanyName" placeholder="Enter Company Name" required>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="form-group">
                <label class="text-white display-5">Contact Name</label>
                <input type="text" class="form-control" name="ContactName" id="ContactName" placeholder="Enter Contact Name" required>
            </div>
        </div>
        <div class="offset-lg-1 col-lg-10">
            <div class="form-group">
                <label class="text-white display-5">Address</label>
                <input type="text" class="form-control" name="Address" id="Address" placeholder="Enter Address" required>
            </div>
        </div>
        <div class="offset-lg-1 col-lg-5">
            <div class="form-group">
                <label class="text-white display-5">Country</label>
                <input type="text" class="form-control" name="Country" id="Country" placeholder="Enter Country">
            </div>
        </div>
        <div class="col-lg-5">
            <div class="form-group">
                <label class="text-white display-5">Telephone</label>
                <input type="text" class="form-control" name="Telephone" id="Telephone" placeholder="Enter Telephone">
            </div>
        </div>
        <div class="offset-lg-1 col-lg-10">
            <div class="form-group">
                <label class="text-white display-5">Mode of Communication</label>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                    <label class="form-check-label text-white display-5" for="inlineCheckbox1">By Phone</label>
                    <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="option2">
                    <label class="form-check-label text-white display-5" for="inlineCheckbox2">By Letter/ Fax</label>
                    <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="option2">
                    <label class="form-check-label text-white display-5" for="inlineCheckbox2">Personal</label>
                    <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="option2">
                    <label class="form-check-label text-white display-5" for="inlineCheckbox2">By Email</label>
                </div>
            </div>
        </div>
    </div>
</form>
<style>
    .form-check-inline .form-check-input {
        width: 25px;
        height: 25px;
    }
    .form-check .form-check-label {
        margin-right: 3.75rem;
    }
</style>
@endsection
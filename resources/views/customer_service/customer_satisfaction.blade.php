@extends('layouts.cs_header')
@section('content')
<div class="col-12 text-center">
    <img src="{{asset('images/wgroup1.png')}}" style="width: 180px;">
    <h2 class="header_h2">Customer Feedback Form</h2>
</div>
<form id="form_client" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row col-lg-12 mt-3" style="margin-left: 0px">
        <div class="offset-lg-1 col-lg-5">
            <div class="form-group">
                <label class="text-white display-5">Client Name</label>
                <select class="form-control js-example-basic-single" name="ClientId" id="ClientId" style="position: relative !important" title="Select Client" required>
                    <option value="" disabled selected>Select Client</option>
                    @foreach($client as $data)
                        <option value="{{ $data->id }}" >{{ $data->Name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="form-group">
                <label class="text-white display-5">Contact Name</label>
                <select class="form-control js-example-basic-single" name="ContactName" id="ContactName" style="position: relative !important" title="Select Contact Name" required>
                    <option value="" disabled selected>Select Contact</option>
                   
                </select>
            </div>
        </div>
        <div class="offset-lg-1 col-lg-5">
            <div class="form-group">
                <label class="text-white display-5">Department Concerned</label>
                <select class="form-control js-example-basic-single" name="Concerned" id="Concerned" style="position: relative !important" title="Select Client" required>
                    <option value="" disabled selected>Select Concerned</option>
                    @foreach($concern_department as $data)
                        <option value="{{ $data->id }}" >{{ $data->Name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="form-group">
                <label class="text-white display-5">Feedback Category</label>
                <select class="form-control js-example-basic-single" name="Category" id="Category" style="position: relative !important" title="Select Contact Name" required>
                    <option value="" disabled selected>Select Category</option>
                    @foreach($category as $data)
                        <option value="{{ $data->id }}" >{{ $data->Name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="offset-lg-1 col-lg-10">
            <label class="text-white display-5">Description</label>
            <textarea class="form-control" rows="5" placeholder="Enter Description"></textarea>
        </div>
        <div class="offset-lg-1 col-lg-10 mt-3 mb-2">
            <label class="text-white">FOR QUALITY CONCERNS</label>
        </div>
        <div class="offset-lg-1 col-lg-5">
            <div class="form-group">
                <label class="text-white display-5">Product/s</label>
                <input type="text" class="form-control" placeholder="Enter Product/s">
            </div>
        </div>
        <div class="col-lg-5">
            <div class="form-group">
                <label class="text-white display-5">Applications</label>
                <input type="text" class="form-control" placeholder="Enter Applications">
            </div>
        </div>
        <div class="offset-lg-1 col-lg-5">
            <div class="form-group">
                <label class="text-white display-5">Annual Potential Volume</label>
                <input type="text" class="form-control" placeholder="Enter Annual Potential Volume">
            </div>
        </div>
        <div class="col-lg-11 mb-2" align="right">
            <input type="submit" class="btn btn-primary" value="Submit">
        </div>
    </div>
</form>
<style>
    .select2-container {
        width: auto !important;
    }
</style>
<script>
    $(document).ready(function() {
        $('.js-example-basic-single').select2();
    });
</script>
@endsection
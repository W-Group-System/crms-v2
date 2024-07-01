@extends('layouts.header')
@section('content')
<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            Edit Client
            <a href="{{ url('/client') }}" class="btn btn-md btn-light"><i class="icon-arrow-left"></i>&nbsp;Back</a>
            </h4>
            <form class="forms-sample" id="form_client" enctype="multipart/form-data" action="{{ url('update_client/'.$data->id) }}">
                @csrf
                <div class="wizard" id="wizard">
                    <div class="steps clearfix">
                        <ul role="tablist">
                            <li role="tab" class="step first" aria-selected="true"><a href="#step1" data-toggle="tab">Client Details</a></li>
                            <li role="tab" class="step done" aria-selected="false"><a href="#step2-2" data-toggle="tab">Contact Details</a></li>
                            <li role="tab" class="step last done" aria-selected="false"><a href="#step3" data-toggle="tab">Files</a></li>
                        </ul>
                    </div>
                    <div class="content tab-content">
                    <span id="form_result"></span>
                        <div id="step1" class="tab-pane fade show active" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="name">Buyer Code</label>
                                        <input type="text" class="form-control" id="BuyerCode" name="BuyerCode" value="{{ $data->BuyerCode }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="name">Primary Account Manager</label>
                                        <select class="form-control js-example-basic-single" name="PrimaryAccountManagerId" id="PrimaryAccountManagerId" style="position: relative !important" title="Select Account Manager" required>
                                            <option value="" disabled selected>Select Account Manager</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" @if($user->id == $data->PrimaryAccountManagerId || $user->user_id == $data->PrimaryAccountManagerId) selected @endif>{{ $user->full_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="name">SAP Code</label>
                                        <input type="text" class="form-control" id="SapCode" name="SapCode" value="{{ $data->SapCode }}" placeholder="Enter SAP Code">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="name">Secondary Account Manager</label>
                                        <select class="form-control js-example-basic-single" name="SecondaryAccountManagerId" id="SecondaryAccountManagerId" style="position: relative !important" title="Select Account Manager">
                                            <option value="" disabled selected>Select Account Manager</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" @if($user->id == $data->SecondaryAccountManagerId) selected @endif>{{ $user->full_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="name">Company Name</label>
                                        <input type="text" class="form-control" id="Name" name="Name" value="{{ $data->Name }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="name">Trade Name</label>
                                        <input type="text" class="form-control" id="TradeName" name="TradeName" value="{{ $data->TradeName }}" placeholder="Enter Trade Name">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="name">TIN</label>
                                        <input type="text" class="form-control" id="TaxIdentificationNumber" name="TaxIdentificationNumber" value="{{ $data->TaxIdentificationNumber }}" placeholder="Enter TIN">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="name">Telephone</label>
                                        <input type="text" class="form-control" id="TelephoneNumber" name="TelephoneNumber" value="{{ $data->TelephoneNumber }}" placeholder="Enter Telephone No.">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="name">Payment Term</label>
                                        <select class="form-control js-example-basic-single" name="PaymentTermId" id="PaymentTermId" style="position: relative !important" title="Select Payment Term" required>
                                            <option value="" disabled selected>Select Payment Term</option>
                                            @foreach($payment_terms as $payment_term)
                                                <option value="{{ $payment_term->Id }}" @if($payment_term->Id == $data->PaymentTermId) selected @endif>{{ $payment_term->Name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="name">FAX</label>
                                        <input type="text" class="form-control" id="FaxNumber" name="FaxNumber" value="{{ $data->FaxNumber }}" placeholder="Enter FAX Number">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="name">Type</label>
                                        <select class="form-control js-example-basic-single" name="Type" id="Type" style="position: relative !important" title="Select Type" required>
                                            <option value="" disabled selected>Select Type</option>
                                            <option value="1"{{$data->Type == '1' ? ' selected' : ''}}>Local</option>
                                            <option value="2"{{$data->Type == '2' ? ' selected' : ''}}>International</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="name">Website</label>
                                        <input type="text" class="form-control" id="Website" name="Website" value="{{ $data->Website }}" placeholder="Enter Website">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="name">Country</label>
                                        <select class="form-control js-example-basic-single" name="ClientCountryId" id="ClientCountryId" style="position: relative !important" title="Select Country" required>
                                            <option value="" disabled selected>Select Country</option>
                                            @foreach($countries as $country)
                                                <option value="{{ $country->Id }}" @if($country->Id == $data->ClientCountryId) selected @endif>{{ $country->Name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="name">Email Address</label>
                                        <input type="email" class="form-control" id="Email" name="Email" value="{{ $data->Email }}" placeholder="Enter Email Address">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="name">Region</label>
                                        <select class="form-control js-example-basic-single" name="ClientRegionId" id="ClientRegionId" style="position: relative !important" title="Select Region" required>
                                            <option value="" disabled selected>Select Region</option>
                                            @foreach($regions as $region)
                                                <option value="{{ $region->Id }}" @if($region->Id == $data->ClientRegionId) selected @endif>{{ $region->Name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="name">Source</label>
                                        <input type="text" class="form-control" id="Source" name="Source" value="{{ $data->Source }}" placeholder="Enter Source">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="name">Area</label>
                                        <select class="form-control js-example-basic-single" name="ClientAreaId" id="ClientAreaId" style="position: relative !important" title="Select Area" required>
                                            <option value="" disabled selected>Select Area</option>
                                            @foreach($areas as $area)
                                                <option value="{{ $area->Id }}" @if($area->Id == $data->ClientAreaId) selected @endif>{{ $area->Name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="name">Business Type</label>
                                        <select class="form-control js-example-basic-single" name="BusinessTypeId" id="BusinessTypeId" style="position: relative !important" title="Select Business Type" required>
                                            <option value="" disabled selected>Select Business Type</option>
                                            @foreach($business_types as $business_type)
                                                <option value="{{ $business_type->Id }}" @if($business_type->Id == $data->BusinessTypeId) selected @endif>{{ $business_type->Name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="name">Industry</label>
                                        <select class="form-control js-example-basic-single" name="ClientIndustryId" id="ClientIndustryId" style="position: relative !important" title="Select Industry Type" required>
                                            <option value="" disabled selected>Select Industry</option>
                                            @foreach($industries as $industry)
                                                <option value="{{ $industry->id }}" @if($industry->id == $data->ClientIndustryId) selected @endif>{{ $industry->Name }}</option>
                                            @endforeach
                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="name">Status</label>
                                        <select class="form-control js-example-basic-single" name="Status" id="Status" style="position: relative !important" title="Select Industry Type" required>
                                            <option value="" disabled selected>Select Status</option>
                                            <option value="2"{{$data->Status == '2' ? ' selected' : ''}}>Current</option>
                                            <option value="1"{{$data->Status == '1' ? ' selected' : ''}}>Prospect</option>
                                            <option value="5"{{$data->Status == '5' ? ' selected' : ''}}>Archived</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <table class="table table-striped mb-5" id="table_address">
                                        <thead>
                                            <tr>
                                                <th width="10%"><a style="padding: 10px 20px" href="javascript:;" class="btn btn-primary addRow">+</th>
                                                <th style="vertical-align: middle" width="40%">Address Type</th>
                                                <th style="vertical-align: middle" width="40%">Address</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($addresses as $address)
                                                <tr>
                                                    <td><a style="padding: 10px 20px" href="javascript:;" class="btn btn-danger deleteRow">-</a></td>
                                                    <td><input type="text" name="AddressType[]" class="form-control adjust" value="{{ $address->AddressType }}" placeholder="Enter Address Type"></td>
                                                    <td><input type="text" name="Address[]" class="form-control adjust" value="{{ $address->Address }}" placeholder="Enter Address"></td>
                                                    <input type="hidden" name="AddressId[]" value="{{ $address->id }}">
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div id="step2" class="tab-pane fade" role="tabpanel">
                            @foreach($contacts as $contact)
                                <div class="row form-group-container">
                                    <div class="col-lg-12">
                                        <button type="button" class="btn btn-danger deleteRowBtn" hidden style="float: right;">Delete Row</button>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Contact Name</label>
                                            <input type="text" class="form-control" name="ContactName[]" placeholder="Enter Contact Name" value="{{ $contact->ContactName }}" required>
                                            <input type="hidden" name="ContactId[]" value="{{ $contact->id }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Designation</label>
                                            <input type="text" class="form-control" id="Designation" name="Designation[]" value="{{ $contact->Designation }}" placeholder="Enter Designation">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Birthday</label>
                                            <input type="date" class="form-control" id="Birthday" name="Birthday[]" value="{{ $contact->Birthday }}" placeholder="Enter Birthday">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Email Address</label>
                                            <input type="text" class="form-control" id="EmailAddress" name="EmailAddress[]" value="{{ $contact->EmailAddress }}" placeholder="Enter Email Address">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Telephone</label>
                                            <input type="text" class="form-control" id="PrimaryTelephone" name="PrimaryTelephone[]" value="{{ $contact->PrimaryTelephone }}" placeholder="Enter Primary Telephone">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Telephone</label>
                                            <input type="text" class="form-control" id="SecondaryTelephone" name="SecondaryTelephone[]" value="{{ $contact->SecondaryTelephone }}" placeholder="Enter Secondary Telephone">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Mobile</label>
                                            <input type="text" class="form-control" id="PrimaryMobile" name="PrimaryMobile[]" value="{{ $contact->PrimaryMobile }}" placeholder="Enter Primary Mobile">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Mobile</label>
                                            <input type="text" class="form-control" id="SecondaryMobile" name="SecondaryMobile[]" value="{{ $contact->SecondaryMobile }}" placeholder="Enter Secondary Mobile">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Skype</label>
                                            <input type="text" class="form-control" id="Skype" value="{{ $contact->Skype }}" name="Skype[]" placeholder="Enter Skype">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Viber</label>
                                            <input type="text" class="form-control" id="Viber" name="Viber[]" value="{{ $contact->Viber }}" placeholder="Enter Viber">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>WhatsApp</label>
                                            <input type="text" class="form-control" id="WhatsApp" name="WhatsApp[]" value="{{ $contact->WhatsApp }}" placeholder="Enter WhatsApp">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Facebook</label>
                                            <input type="text" class="form-control" id="Facebook" name="Facebook[]" value="{{ $contact->Facebook }}" placeholder="Enter Facebook">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>LinkedIn</label>
                                            <input type="text" class="form-control" id="LinkedIn" name="LinkedIn[]" value="{{ $contact->LinkedIn }}" placeholder="Enter LinkedIn">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <button type="button" class="btn btn-primary" id="addRowBtn">Add Row</button>
                        </div>
                        <div id="step3" class="tab-pane fade" role="tabpanel">
                            <div class="col-lg-12">
                                <table class="table table-striped mb-5" id="table_files">
                                    <thead>
                                        <tr>
                                            <th width="10%"><a style="padding: 10px 20px" href="javascript:;" class="btn btn-primary file_addRow">+</a></th>
                                            <th style="vertical-align: middle" width="40%">File Name</th>
                                            <th style="vertical-align: middle" width="40%">Browse File</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($files as $file)
                                            <tr>
                                                <td><a style="padding: 10px 20px" href="javascript:;" class="btn btn-danger file_deleteRow">-</a></td>
                                                <td><input type="text" name="FileName[]" class="form-control adjust" placeholder="Enter File Name" value="{{ $file->FileName }}"></td>
                                                <td>
                                                    <input type="hidden" name="fileId[]" value="{{ $file->id }}">
                                                    <input type="file" name="Path[]" class="form-control adjust">
                                                    @if($file->Path)
                                                        <p>Current file: <a href="{{ asset('storage/' . $file->Path) }}" target="_blank">{{ basename($file->Path) }}</a></p>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="actions clearfix">
                        <button type="button" class="btn btn-primary" id="prevBtn" disabled>Previous</button>
                        <button type="button" class="btn btn-primary" id="nextBtn">Next</button>
                        <button type="submit" class="btn btn-success" id="finishBtn" style="display: none;">Finish</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<style>
    .wizard > .steps a {
        background: #8e8dce;
        color: #ffffff;
    }
    .wizard > .steps .active a {
        background: #4B49AC;
        color: #ffffff;
        cursor: default;
    }
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#table_address thead').on('click', '.addRow', function(){
            var tr = '<tr>' +
                '<td><a style="padding: 10px 20px" href="javascript:;" class="btn btn-danger deleteRow">-</a></td>'+
                '<td><input type="text" name="AddressType[]" id="AddressType" class="form-control" placeholder="Enter Address Type"></td>'+
                '<td><input type="text" name="Address[]" id="Address" class="form-control adjust" placeholder="Enter Address"></td>'+
            '</tr>';

            $('tbody').append(tr);
        });

        $('#table_address tbody').on('click', '.deleteRow', function(){
            $(this).parent().parent().remove();
        });

        // Add function of client contacts
        $('#addRowBtn').click(function() {
            var newRow = $('<div class="row form-group-container">' +
                '<div class="col-lg-12">' +
                    '<button type="button" class="btn btn-danger deleteRowBtn" style="float: right;">Delete Row</button>' +
                '</div>' +
                '<div class="col-lg-6">' +
                    '<div class="form-group">' +
                        '<label>Contact Name</label>' +
                        '<input type="text" class="form-control" name="ContactName[]" placeholder="Enter Contact Name" required>' +
                        '<input type="hidden" name="ContactId[]">' +
                    '</div>' +
                '</div>' +
                '<div class="col-lg-6">' +
                    '<div class="form-group">' +
                        '<label>Designation</label>' +
                        '<input type="text" class="form-control" name="Designation[]" placeholder="Enter Designation">' +
                    '</div>' +
                '</div>' +
                '<div class="col-lg-6">' +
                    '<div class="form-group">' +
                        '<label>Birthday</label>' +
                        '<input type="date" class="form-control" name="Birthday[]" placeholder="Enter Birthday">' +
                    '</div>' +
                '</div>' +
                '<div class="col-lg-6">' +
                    '<div class="form-group">' +
                        '<label>Email Address</label>' +
                        '<input type="text" class="form-control" name="EmailAddress[]" placeholder="Enter Email Address">' +
                    '</div>' +
                '</div>' +
                '<div class="col-lg-6">' +
                    '<div class="form-group">' +
                        '<label>Telephone</label>' +
                        '<input type="text" class="form-control" name="PrimaryTelephone[]" placeholder="Enter Primary Telephone">' +
                    '</div>' +
                '</div>' +
                '<div class="col-lg-6">' +
                    '<div class="form-group">' +
                        '<label>Telephone</label>' +
                        '<input type="text" class="form-control" name="SecondaryTelephone[]" placeholder="Enter Secondary Telephone">' +
                    '</div>' +
                '</div>' +
                '<div class="col-lg-6">' +
                    '<div class="form-group">' +
                        '<label>Mobile</label>' +
                        '<input type="text" class="form-control" name="PrimaryMobile[]" placeholder="Enter Primary Mobile">' +
                    '</div>' +
                '</div>' +
                '<div class="col-lg-6">' +
                    '<div class="form-group">' +
                        '<label>Mobile</label>' +
                        '<input type="text" class="form-control" name="SecondaryMobile[]" placeholder="Enter Secondary Mobile">' +
                    '</div>' +
                '</div>' +
                '<div class="col-lg-6">' +
                    '<div class="form-group">' +
                        '<label>Skype</label>' +
                        '<input type="text" class="form-control" name="Skype[]" placeholder="Enter Skype">' +
                    '</div>' +
                '</div>' +
                '<div class="col-lg-6">' +
                    '<div class="form-group">' +
                        '<label>Viber</label>' +
                        '<input type="text" class="form-control" name="Viber[]" placeholder="Enter Viber">' +
                    '</div>' +
                '</div>' +
                '<div class="col-lg-6">' +
                    '<div class="form-group">' +
                        '<label>WhatsApp</label>' +
                        '<input type="text" class="form-control" name="WhatsApp[]" placeholder="Enter WhatsApp">' +
                    '</div>' +
                '</div>' +
                '<div class="col-lg-6">' +
                    '<div class="form-group">' +
                        '<label>Facebook</label>' +
                        '<input type="text" class="form-control" name="Facebook[]" placeholder="Enter Facebook">' +
                    '</div>' +
                '</div>' +
                '<div class="col-lg-6">' +
                    '<div class="form-group">' +
                        '<label>LinkedIn</label>' +
                        '<input type="text" class="form-control" name="LinkedIn[]" placeholder="Enter LinkedIn">' +
                    '</div>' +
                '</div>' +
            '</div>');

            newRow.insertBefore('#addRowBtn');
            
            // Attach the delete event to the new row's delete button
            newRow.find('.deleteRowBtn').click(function() {
                $(this).closest('.form-group-container').remove();
            });
        });

        // Attach delete event to existing rows
        $('.deleteRowBtn').click(function() {
            $(this).closest('.form-group-container').remove();
        });

         // Add file row
        $('#table_files thead').on('click', '.file_addRow', function() {
            var tr = '<tr>' +
                '<td><a style="padding: 10px 20px" href="javascript:;" class="btn btn-danger file_deleteRow">-</a></td>' +
                '<td><input type="text" name="FileName[]" class="form-control adjust" placeholder="Enter File Name"></td>' +
                '<td>' +
                    '<input type="hidden" name="fileId[]" value="">' + // Empty value for new rows
                    '<input type="file" name="Path[]" class="form-control adjust">' +
                '</td>' +
            '</tr>';

            $('#table_files tbody').append(tr);
        });

        // Delete file row
        $('#table_files tbody').on('click', '.file_deleteRow', function() {
            $(this).closest('tr').remove();
        });

        // Navigation through steps
        var currentStep = 1;
        var totalSteps = $(".wizard .steps li").length;

        function showStep(step) {
            $(".wizard .steps li").removeClass("active");
            $(".wizard .steps li:nth-child(" + step + ")").addClass("active");
            $(".tab-pane").removeClass("show active");
            $("#step" + step).addClass("show active");

            if (step === 1) {
                $("#prevBtn").attr("disabled", true);
            } else {
                $("#prevBtn").attr("disabled", false);
            }

            if (step === totalSteps) {
                $("#nextBtn").hide();
                $("#finishBtn").show();
            } else {
                $("#nextBtn").show();
                $("#finishBtn").hide();
            }
        }

        function validateStep1() {
            var isValid = true;

            $('#step1 input, #step1 select').each(function() {
                // If it's a select2 element
                if ($(this).hasClass('js-example-basic-single')) {
                    // Check the value of the select2 element
                    if ($(this).prop('required') && ($(this).val() === "" || $(this).val() === null)) {
                        isValid = false;
                        $(this).next('.select2-container').find('.select2-selection').addClass('is-invalid');
                    } else {
                        $(this).next('.select2-container').find('.select2-selection').removeClass('is-invalid');
                    }
                } else {
                    // Check normal input/select elements
                    if ($(this).val() === "" && $(this).prop('required')) {
                        isValid = false;
                        $(this).addClass('is-invalid');
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                }
            });

            return isValid;
        }

        function validateStep2() {
            var isValid = true;

            $('#step2 input, #step2 select').each(function() {
                // If it's a select2 element
                if ($(this).hasClass('js-example-basic-single')) {
                    // Check the value of the select2 element
                    if ($(this).prop('required') && ($(this).val() === "" || $(this).val() === null)) {
                        isValid = false;
                        $(this).next('.select2-container').find('.select2-selection').addClass('is-invalid');
                    } else {
                        $(this).next('.select2-container').find('.select2-selection').removeClass('is-invalid');
                    }
                } else {
                    // Check normal input/select elements
                    if ($(this).val() === "" && $(this).prop('required')) {
                        isValid = false;
                        $(this).addClass('is-invalid');
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                }
            });

            return isValid;
        }

        // Event listener to remove is-invalid class on change for select2 elements
        $('select.js-example-basic-single').on('change', function() {
            if ($(this).val() !== "" && $(this).val() !== null) {
                $(this).next('.select2-container').find('.select2-selection').removeClass('is-invalid');
            }
        });

        $("#prevBtn").click(function() {
            if (currentStep > 1) {
                currentStep--;
                showStep(currentStep);
            }
        });

        $("#nextBtn").click(function() {
            if (currentStep === 1) {
                if (validateStep1()) {
                    currentStep++;
                    showStep(currentStep);
                } else {
                    // alert("Please fill out all required fields.");
                    $('#form_result').html('<div class="alert alert-danger">Please fill out all required fields.</div>').show();
                    setTimeout(function(){
                        $('#form_result').hide();
                    }, 3000);
                    $('html, body').animate({
                        scrollTop: $('#form_result').offset().top
                    }, 1000);
                }
            } else if (currentStep === 2) {
                if (validateStep2()) { // Assuming you have a validateStep2() function
                    currentStep++;
                    showStep(currentStep);
                } else {
                    // alert("Please fill out all required fields.");
                    $('#form_result').html('<div class="alert alert-danger">Please fill out all required fields.</div>').show();
                    setTimeout(function(){
                        $('#form_result').hide();
                    }, 3000);
                    $('html, body').animate({
                        scrollTop: $('#form_result').offset().top
                    }, 1000);
                }
            } else if (currentStep < totalSteps) {
                currentStep++;
                showStep(currentStep);
            }
        });

        showStep(currentStep);
    });

    $('#form_client').on('submit', function(event) {
        event.preventDefault();

        $.ajax({
            url: "{{ url ('update_client/'.$data->id) }}",
            method: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function(data) {
                if (data.success) {
                    $('#form_result').html('<div class="alert alert-success">' + data.success + '</div>').show();
                    setTimeout(function(){
                        $('#form_result').hide();
                    }, 3000);
                    $('html, body').animate({
                        scrollTop: $('#form_client').offset().top
                    }, 1000);
                } else if (data.errors) {
                    var errorsHtml = '<div class="alert alert-danger"><ul>';
                    $.each(data.errors, function(key, error) {
                        errorsHtml += '<li>' + error + '</li>';
                    });
                    errorsHtml += '</ul></div>';
                    $('#form_result').html(errorsHtml).show();
                }
            }
        });
    });

</script>
@endsection
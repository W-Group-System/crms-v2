@extends('layouts.header')
@section('content')
<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            Add New Client
            <a href="{{ url('/client') }}" class="btn btn-md btn-light"><i class="icon-arrow-left"></i>&nbsp;Back</a>
            </h4>
            <form id="form_client" method="POST" action="{{ route('client.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="wizard" id="wizard">
                    <div class="steps clearfix">
                        <ul role="tablist">
                            <li role="tab" class="step first" aria-selected="true"><a href="#step1" data-toggle="tab">Client Details</a></li>
                            <li role="tab" class="step done" aria-selected="false"><a href="#step2" data-toggle="tab">Contact Details</a></li>
                            <li role="tab" class="step last done" aria-selected="false"><a href="#step3" data-toggle="tab">Files</a></li>
                        </ul>
                    </div>
                    <div class="content tab-content">
                    <span id="form_result"></span>
                        <div id="step1" class="tab-pane fade show active" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Buyer Code</label>
                                        <input type="text" class="form-control" id="BuyerCode" name="BuyerCode" placeholder="Enter Buyer Code" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Primary Account Manager</label>
                                        <select class="form-control js-example-basic-single" name="PrimaryAccountManagerId" id="PrimaryAccountManagerId" style="position: relative !important" title="Select Account Manager" required>
                                            <option value="" disabled selected>Select Account Manager</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->full_name }}</option>
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
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Payment Term</label>
                                        <select class="form-control js-example-basic-single" name="PaymentTermId" id="PaymentTermId" style="position: relative !important" title="Select Payment Term" required>
                                            <option value="" disabled selected>Select Payment Term</option>
                                            @foreach($payment_terms as $payment_term)
                                                <option value="{{ $payment_term->Id }}">{{ $payment_term->Name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>FAX</label>
                                        <input type="text" class="form-control" id="FaxNumber" name="FaxNumber" placeholder="Enter Fax Number">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Type</label>
                                        <select class="form-control js-example-basic-single" name="Type" id="Type" style="position: relative !important" title="Select Type" required>
                                            <option value="" disabled selected>Select Type</option>
                                            <option value="1">Local</option>
                                            <option value="2">International</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Website</label>
                                        <input type="text" class="form-control" id="Website" name="Website" placeholder="Enter Website">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Country</label>
                                        <select class="form-control js-example-basic-single" name="ClientCountryId" id="ClientCountryId" style="position: relative !important" title="Select Country" required>
                                            <option value="" disabled selected>Select Country</option>
                                            @foreach($countries as $country)
                                                <option value="{{ $country->Id }}">{{ $country->Name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Email Address</label>
                                        <input type="email" class="form-control" id="Email" name="Email" placeholder="Enter Email Address">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Region</label>
                                        <select class="form-control js-example-basic-single" name="ClientRegionId" id="ClientRegionId" style="position: relative !important" title="Select Region" required>
                                            <option value="" disabled selected>Select Region</option>
                                            @foreach($regions as $region)
                                                <option value="{{ $region->Id }}">{{ $region->Name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Source</label>
                                        <input type="text" class="form-control" id="Source" name="Source" placeholder="Enter Source">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Area</label>
                                        <select class="form-control js-example-basic-single" name="ClientAreaId" id="ClientAreaId" style="position: relative !important" title="Select Area" required>
                                            <option value="" disabled selected>Select Area</option>
                                            @foreach($areas as $area)
                                                <option value="{{ $area->Id }}">{{ $area->Name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Business Type</label>
                                        <select class="form-control js-example-basic-single" name="BusinessTypeId" id="BusinessTypeId" style="position: relative !important" title="Select Business Type" required>
                                            <option value="" disabled selected>Select Business Type</option>
                                            @foreach($business_types as $business_type)
                                                <option value="{{ $business_type->Id }}">{{ $business_type->Name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Industry</label>
                                        <select class="form-control js-example-basic-single" name="ClientIndustryId" id="ClientIndustryId" style="position: relative !important" title="Select Industry Type" required>
                                            <option value="" disabled selected>Select Industry</option>
                                            @foreach($industries as $industry)
                                                <option value="{{ $industry->id }}">{{ $industry->Name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select class="form-control js-example-basic-single" name="Status" id="Status" style="position: relative !important" title="Select Industry Type" required>
                                            <option value="" disabled selected>Select Status</option>
                                            <option value="2">Current</option>
                                            <option value="1">Prospect</option>
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
                                            <tr>
                                                <td><a style="padding: 10px 20px" href="javascript:;" class="btn btn-danger deleteRow">-</a></td>
                                                <td><input type="text" name="AddressType[]" id="AddressType" class="form-control adjust" placeholder="Enter Address Type"></td>
                                                <td><input type="text" name="Address[]" id="Address" class="form-control adjust" placeholder="Enter Address"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div id="step2" class="tab-pane fade" role="tabpanel">
                            <div class="row form-group-container">
                            <div class="col-lg-12">
                                <button type="button" class="btn btn-danger deleteRowBtn" hidden style="float: right;">Delete Row</button>
                            </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Contact Name</label>
                                        <input type="text" class="form-control" name="ContactName[]" placeholder="Enter Contact Name" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Designation</label>
                                        <input type="text" class="form-control" id="Designation" name="Designation[]" placeholder="Enter Designation">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Birthday</label>
                                        <input type="date" class="form-control" id="Birthday" name="Birthday[]" placeholder="Enter Birthday">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Email Address</label>
                                        <input type="text" class="form-control" id="EmailAddress" name="EmailAddress[]" placeholder="Enter Email Address">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Telephone</label>
                                        <input type="text" class="form-control" id="PrimaryTelephone" name="PrimaryTelephone[]" placeholder="Enter Primary Telephone">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Telephone</label>
                                        <input type="text" class="form-control" id="SecondaryTelephone" name="SecondaryTelephone[]" placeholder="Enter Secondary Telephone">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Mobile</label>
                                        <input type="text" class="form-control" id="PrimaryMobile" name="PrimaryMobile[]" placeholder="Enter Primary Mobile">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Mobile</label>
                                        <input type="text" class="form-control" id="SecondaryMobile" name="SecondaryMobile[]" placeholder="Enter Secondary Mobile">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Skype</label>
                                        <input type="text" class="form-control" id="Skype" name="Skype[]" placeholder="Enter Skype">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Viber</label>
                                        <input type="text" class="form-control" id="Viber" name="Viber[]" placeholder="Enter Viber">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>WhatsApp</label>
                                        <input type="text" class="form-control" id="WhatsApp" name="WhatsApp[]" placeholder="Enter WhatsApp">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Facebook</label>
                                        <input type="text" class="form-control" id="Facebook" name="Facebook[]" placeholder="Enter Facebook">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>LinkedIn</label>
                                        <input type="text" class="form-control" id="LinkedIn" name="LinkedIn[]" placeholder="Enter LinkedIn">
                                    </div>
                                </div>
                            </div>
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
                                        <tr>
                                            <td><a style="padding: 10px 20px" href="javascript:;" class="btn btn-danger file_deleteRow">-</a></td>
                                            <td><input type="text" name="FileName[]" class="form-control adjust" placeholder="Enter File Name"></td>
                                            <td><input type="file" name="Path[]" class="form-control adjust"></td>
                                        </tr>
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

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
<script>
    $(document).ready(function() {
        $('.js-example-basic-single').select2();

        // Add new address row
        $('#table_address thead').on('click', '.addRow', function(){
            var tr = '<tr>' +
                '<td><a style="padding: 10px 20px" href="javascript:;" class="btn btn-danger deleteRow">-</a></td>'+
                '<td><input type="text" name="AddressType[]" id="AddressType" class="form-control" placeholder="Enter Address Type"></td>'+
                '<td><input type="text" name="Address[]" id="Address" class="form-control adjust" placeholder="Enter Address"></td>'+
            '</tr>';

            $('tbody').append(tr);
        });

        // Delete address row
        $('#table_address tbody').on('click', '.deleteRow', function(){
            $(this).parent().parent().remove();
        });

        // Add new file row
        $('#table_files thead').on('click', '.file_addRow', function(){
            var tr = '<tr>' +
                '<td><a style="padding: 10px 20px" href="javascript:;" class="btn btn-danger deleteRow">-</a></td>'+
                '<td><input type="text" name="FileName[]" class="form-control adjust" placeholder="Enter File Name"></td>'+
                '<td><input type="file" name="Path[]" class="form-control adjust"></td>'+
            '</tr>';

            $('tbody').append(tr);
        });

        // Delete file row
        $('#table_files tbody').on('click', '.file_deleteRow', function(){
            $(this).parent().parent().remove();
        });

        // Add new form row
        $('#addRowBtn').click(function() {
            var newRow = $('.form-group-container').first().clone();
            
            newRow.find('input').each(function() {
                $(this).val('');
            });
        
            newRow.insertBefore('#addRowBtn');
            
            // Show delete button for the new row
            newRow.find('.deleteRowBtn').removeAttr('hidden');
            
            // Attach the delete event to the new row's delete button
            newRow.find('.deleteRowBtn').click(function() {
                $(this).closest('.form-group-container').remove();
            });
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

    // Handle form submission outside the document ready function
    $('#form_client').on('submit', function(event) {
        event.preventDefault();

        // Use FormData to handle file uploads
        var formData = new FormData(this);

        $.ajax({
            url: "{{ route('client.store') }}",
            method: "POST",
            data: formData,
            processData: false,  // Don't process the files
            contentType: false,  // Set content type to false to let jQuery determine it
            dataType: "json",
            success: function(data) {
                if (data.success) {
                    $('#form_result').html('<div class="alert alert-success">' + data.success + '</div>').show();
                    $('#form_client')[0].reset();
                    $('select.js-example-basic-single').val('').trigger('change'); // Clear all select fields
                    setTimeout(function(){
                        $('#form_result').hide();
                    }, 2000);
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
            },
            error: function(data) {
                console.log(data);
            }
        });
    });

</script>
@endsection
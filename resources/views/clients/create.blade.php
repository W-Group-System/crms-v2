@extends('layouts.header')
@section('content')
<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            Add New Client
            <a href="{{ url('/client_prospect') }}" class="btn btn-md btn-secondary"><i class="icon-arrow-left"></i>&nbsp;Back</a>
            </h4>
            <form id="form_client" method="POST" enctype="multipart/form-data">
                @csrf
                <span id="form_result"></span>
                <div class="row">
                    <input type="hidden" name="Status" id="Status" value="1">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Buyer Code</label>
                            <input type="text" class="form-control" id="BuyerCode" name="BuyerCode" placeholder="Enter Buyer Code"  value="{{ old('BuyerCode', $buyerCode) }}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Primary Account Manager</label>
                            <select class="form-control js-example-basic-single" name="PrimaryAccountManagerId" id="PrimaryAccountManagerId" style="position: relative !important" title="Select Account Manager" >
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
                            <input type="text" class="form-control" name="Name" placeholder="Enter Company Name" >
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
                            <select class="form-control js-example-basic-single" name="PaymentTermId" id="PaymentTermId" style="position: relative !important" title="Select Payment Term" >
                                <option value="" disabled selected>Select Payment Term</option>
                                @foreach($payment_terms as $payment_term)
                                    <option value="{{ $payment_term->id }}">{{ $payment_term->Name }}</option>
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
                            <select class="form-control js-example-basic-single" name="Type" id="Type" style="position: relative !important" title="Select Type" >
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
                        <div class="form-group" id="regionSelectGroup">
                            <label>Region</label>
                            <select class="form-control js-example-basic-single" name="ClientRegionId" id="ClientRegionId" style="position: relative !important" title="Select Region" disabled>
                                <option value="" disabled selected>Select Region</option>
                                <!-- Options will be dynamically populated -->
                            </select>
                        </div>
                    </div>
                    {{-- <div class="col-lg-6">
                        <div class="form-group">
                            <label>Region</label>
                            <select class="form-control js-example-basic-single" name="ClientRegionId" id="ClientRegionId" style="position: relative !important" title="Select Region" disabled>
                                <option value="" disabled selected>Select Region</option>
                                @foreach($regions as $region)
                                    <option value="{{ $region->Id }}">{{ $region->Name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div> --}}
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" class="form-control" id="Email" name="Email" placeholder="Enter Email Address">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group" id="areaSelectGroup">
                            <label>Area</label>
                            {{-- <select class="form-control js-example-basic-single" name="ClientAreaId" id="ClientAreaId" style="position: relative !important" title="Select Area" >
                                <option value="" disabled selected>Select Area</option>
                                @foreach($areas as $area)
                                    <option value="{{ $area->Id }}">{{ $area->Name }}</option>
                                @endforeach
                            </select> --}}
                            <select class="form-control js-example-basic-single" name="ClientAreaId" id="ClientAreaId" style="position: relative !important" title="Select Area" disabled>
                                <option value="" disabled selected>Select Area</option>
                                <!-- Options will be dynamically populated -->
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
                            <label>Country</label>
                            <select class="form-control js-example-basic-single" name="ClientCountryId" id="ClientCountryId" style="position: relative !important" title="Select Country" >
                                <option value="" disabled selected>Select Country</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->Name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Business Type</label>
                            <select class="form-control js-example-basic-single" name="BusinessTypeId" id="BusinessTypeId" style="position: relative !important" title="Select Business Type" >
                                <option value="" disabled selected>Select Business Type</option>
                                @foreach($business_types as $business_type)
                                    <option value="{{ $business_type->id }}">{{ $business_type->Name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Industry</label>
                            <select class="form-control js-example-basic-single" name="ClientIndustryId" id="ClientIndustryId" style="position: relative !important" title="Select Industry Type" >
                                <option value="" disabled selected>Select Industry</option>
                                @foreach($industries as $industry)
                                    <option value="{{ $industry->id }}">{{ $industry->Name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6" id="addressContainer">
                        <div class="form-group">
                            <label>Client Address</label>
                            <div class="input-group">                                
                                <input type="text" class="form-control" name="AddressType[]" placeholder="Enter Address Type">
                                <button class="btn btn-sm btn-primary addRowBtn" style="border-radius: 0px;" type="button">+</button>
                            </div>
                            <textarea type="text" class="form-control" name="Address[]" placeholder="Enter Address" rows="2"></textarea>
                        </div>
                    </div>
                    <!-- <div class="col-lg-12">
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
                    </div> -->
                    <div class="col-lg-12" align="right">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<style>
    .is-invalid {
        border: 1px solid red;
    }
</style>
<script>
    $(document).ready(function() {
        $('.js-example-basic-single').select2();

        $(document).on('click', '.addRowBtn', function() {
            var newRow = $('<div class="form-group">' +
                            '<label>Client Address</label>' +
                            '<div class="input-group">' +
                                '<input type="text" class="form-control" name="AddressType[]" placeholder="Enter Address Type">' +
                                // '<button class="btn btn-sm btn-primary addRowBtn" style="border-radius: 0px;" type="button">+</button>' +
                                '<button class="btn btn-sm btn-danger removeRowBtn" style="border-radius: 0px;" type="button">-</button>' +
                            '</div>' +
                            '<textarea type="text" class="form-control" name="Address[]" placeholder="Enter Address" rows="2"></textarea>' +
                        '</div>');

            // Append the new row to the container where addresses are listed
            $('#addressContainer').append(newRow);
        });
        $(document).on('click', '.removeRowBtn', function() {
            $(this).closest('.form-group').remove();
        });

        $('#Type').on('change', function() {
            var selectedType = $(this).val();
            var $regionSelect = $('#ClientRegionId');

            // Clear existing options
            $regionSelect.empty();

            // Show or hide the region select based on Type selection
            if (selectedType === '1' || selectedType === '2') {
                populateRegions(selectedType); // Populate regions based on selectedType
            } else {
                $regionSelect.prop('disabled', true); // Disable the region select if no type selected
                $('#regionSelectGroup').hide(); // Hide the region select group
            }
        });

        $('#ClientRegionId').on('change', function() {
            var selectedRegionId = $(this).val();
            populateAreas(selectedRegionId); // Populate areas based on selectedRegionId
        });

        // Function to populate regions based on type
        function populateRegions(type) {
            var $regionSelect = $('#ClientRegionId');

            // Clear existing options
            $regionSelect.empty();

            // AJAX request to fetch regions from Laravel backend
            $.ajax({
                url: "{{ url('/regions') }}", // Adjust the URL as per your Laravel route setup
                method: 'GET',
                data: { type: type }, // Send the type parameter to the backend
                dataType: 'json',
                success: function(response) {
                    // Populate the select box with options
                    $regionSelect.append('<option value="" disabled selected>Select Region</option>');
                    $.each(response, function(index, region) {
                        $regionSelect.append('<option value="' + region.id + '">' + region.name + '</option>');
                    });

                    // Enable the select box and show the region select group
                    $regionSelect.prop('disabled', false);
                    $('#regionSelectGroup').show();
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching regions:', error);
                    // Handle error if needed
                }
            });
        }
        function populateAreas(regionId) {
            var $areaSelect = $('#ClientAreaId');

            // Clear existing options
            $areaSelect.empty();

            // AJAX request to fetch areas from Laravel backend
            $.ajax({
                url: "{{ url('/areas') }}", // Adjust the URL as per your Laravel route setup
                method: 'GET',
                data: { regionId: regionId }, // Send the regionId parameter to the backend
                dataType: 'json',
                success: function(response) {
                    // Populate the select box with options
                    $areaSelect.append('<option value="" disabled selected>Select Area</option>');
                    $.each(response, function(index, area) {
                        $areaSelect.append('<option value="' + area.id + '">' + area.name + '</option>');
                    });

                    // Enable the select box and show the area select group
                    $areaSelect.prop('disabled', false);
                    $('#areaSelectGroup').show();
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching areas:', error);
                    // Handle error if needed
                }
            });
        }
        $('#form_client').on('submit', function(event) {
            event.preventDefault();

            // Use FormData to handle file uploads
            var formData = new FormData(this);

            // Remove all existing is-invalid classes before validation
            $('#form_client').find('.is-invalid').removeClass('is-invalid');

            $.ajax({
                url: "{{ route('client.store') }}",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        // Display a Swal success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.success,
                            timer: 1500, // Auto-close after 2 seconds
                            showConfirmButton: false
                        }).then((result) => {
                            $('#form_client')[0].reset();
                            location.reload();
                        });
                    } else if (response.errors) {
                        // Handle validation errors display
                        var errorsHtml = '<div class="alert alert-danger" style="border-color: red;"><ul>';
                        $.each(response.errors, function(index, error) {
                            errorsHtml += '<li>' + error + '</li>';
                            // Highlight the field with errors by adding a red border
                            var fieldName = index.replace('.', '\\.');
                            var $field = $('#form_client').find('[name="' + fieldName + '"]');
                            
                            // Check if the element is a regular input or select2 dropdown
                            if ($field.hasClass('js-example-basic-single')) {
                                // Select2 dropdown case
                                $field.siblings('.select2-container').addClass('is-invalid');
                            } else {
                                // Regular input case
                                $field.addClass('is-invalid');
                            }

                            if (index.includes('AddressType') || index.includes('Address')) {
                                var fieldIndex = index.match(/\d+/)[0];
                                if (index.includes('AddressType')) {
                                    $('#form_client').find('[name="AddressType[]"]').eq(fieldIndex).addClass('is-invalid');
                                } else if (index.includes('Address')) {
                                    $('#form_client').find('[name="Address[]"]').eq(fieldIndex).addClass('is-invalid');
                                }
                            }
                        });
                        errorsHtml += '</ul></div>';
                        $('html, body').animate({
                            scrollTop: $('#form_client').offset().top
                        }, 1000);
                        $('#form_result').html(errorsHtml).show();
                    }
                }
            });
        });

    });
</script>
@endsection
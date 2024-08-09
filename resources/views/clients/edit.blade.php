@extends('layouts.header')
@section('content')
<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
                Edit Client
                <a href="{{ session('last_client_page', url('/client')) }}" class="btn btn-md btn-secondary">
                    <i class="icon-arrow-left"></i>&nbsp;Back
                </a>
            </h4>
            <form id="editFormClient" enctype="multipart/form-data" method="POST" action="{{ url('client/update/'.$data->id) }}">
                @csrf
                <span id="form_result"></span>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Buyer Code</label>
                            <input type="text" class="form-control" id="BuyerCode" name="BuyerCode" value="{{ $data->BuyerCode }}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Primary Account Manager</label>
                            <select class="form-control js-example-basic-single" name="PrimaryAccountManagerId" id="PrimaryAccountManagerId" style="position: relative !important" title="Select Account Manager">
                                <option value="" disabled {{ $data->PrimaryAccountManagerId ? '' : 'selected' }}>Select Account Manager</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $data->PrimaryAccountManagerId == $user->user_id || $data->PrimaryAccountManagerId == $user->id ? 'selected' : '' }}>
                                        {{ $user->full_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>SAP Code</label>
                            <input type="text" class="form-control" id="SapCode" name="SapCode" placeholder="Enter SAP Code" value="{{ $data->SapCode }}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Secondary Account Manager</label>
                            <select class="form-control js-example-basic-single" name="SecondaryAccountManagerId" id="SecondaryAccountManagerId" style="position: relative !important" title="Select Account Manager">
                            <option value="" disabled {{ is_null($data->SecondaryAccountManagerId) ? 'selected' : '' }}>Select Account Manager</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $data->SecondaryAccountManagerId == $user->user_id || $data->SecondaryAccountManagerId == $user->id ? 'selected' : '' }}>
                                        {{ $user->full_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Company Name</label>
                            <input type="text" class="form-control" name="Name" placeholder="Enter Company Name" value="{{ $data->Name }}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Trade Name</label>
                            <input type="text" class="form-control" id="TradeName" name="TradeName" placeholder="Enter Trade Name" value="{{ $data->TradeName }}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>TIN</label>
                            <input type="text" class="form-control" id="TaxIdentificationNumber" name="TaxIdentificationNumber" placeholder="Enter TIN No." value="{{ $data->TaxIdentificationNumber }}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Telephone</label>
                            <input type="text" class="form-control" id="TelephoneNumber" name="TelephoneNumber" placeholder="Enter Telephone Number" value="{{ $data->TelephoneNumber }}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Payment Term</label>
                            <select class="form-control js-example-basic-single" name="PaymentTermId" id="PaymentTermId" style="position: relative !important" title="Select Payment Term">
                                <option value="" disabled {{ is_null($data->PaymentTermId) ? 'selected' : '' }}>Select Payment Term</option>
                                @foreach($payment_terms as $payment_term)
                                    <option value="{{ $payment_term->id }}" {{ $data->PaymentTermId == $payment_term->id ? 'selected' : '' }}>
                                        {{ $payment_term->Name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>FAX</label>
                            <input type="text" class="form-control" id="FaxNumber" name="FaxNumber" placeholder="Enter Fax Number" value="{{ $data->FaxNumber }}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Type</label>
                            <select class="form-control js-example-basic-single" name="Type" id="Type" style="position: relative !important" title="Select Type">
                                <option value="1" {{ $data->Type == 1 ? 'selected' : '' }}>Local</option>
                                <option value="2" {{ $data->Type == 2 ? 'selected' : '' }}>International</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Website</label>
                            <input type="text" class="form-control" id="Website" name="Website" placeholder="Enter Website" value="{{ $data->Website }}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group" id="regionSelectGroup">
                            <label>Region</label>
                            <select class="form-control js-example-basic-single" name="ClientRegionId" id="ClientRegionId" style="position: relative !important" title="Select Region">
                                <!-- Options will be dynamically populated -->
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" class="form-control" id="Email" name="Email" placeholder="Enter Email Address" value="{{ $data->Email }}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group" id="areaSelectGroup">
                            <label>Area</label>
                            <select class="form-control js-example-basic-single" name="ClientAreaId" id="ClientAreaId" style="position: relative !important" title="Select Area">
                                <option value="" disabled selected>Select Area</option>
                                <!-- Options will be dynamically populated -->
                            </select>
                        </div>
                    </div>  
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Source</label>
                            <input type="text" class="form-control" id="Source" name="Source" placeholder="Enter Source" value="{{ $data->Source }}">
                        </div>
                    </div> 
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Country</label>
                            <select class="form-control js-example-basic-single" name="ClientCountryId" id="ClientCountryId" style="position: relative !important" title="Select Country">
                                <option value="" disabled {{ is_null($data->ClientCountryId) ? 'selected' : '' }}>Select Country</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" {{ $data->ClientCountryId == $country->id ? 'selected' : '' }}>{{ $country->Name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Business Type</label>
                            <select class="form-control js-example-basic-single" name="BusinessTypeId" id="BusinessTypeId" style="position: relative !important" title="Select Business Type">
                                <option value="" disabled {{ is_null($data->BusinessTypeId) ? 'selected' : '' }}>Select Business Type</option>
                                @foreach($business_types as $business_type)
                                    <option value="{{ $business_type->id }}" {{ $data->BusinessTypeId == $business_type->id ? 'selected' : '' }}>{{ $business_type->Name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Industry</label>
                            <select class="form-control js-example-basic-single" name="ClientIndustryId" id="ClientIndustryId" style="position: relative !important" title="Select Industry Type" >
                                <option value="" disabled selected>Select Industry</option>
                                <option value="" disabled {{ is_null($data->ClientIndustryId) ? 'selected' : '' }}>Select Industry</option>
                                @foreach($industries as $industry)
                                    <option value="{{ $industry->id }}" {{ $data->ClientIndustryId == $industry->id }}>{{ $industry->Name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6" id="addressContainer">
                        @if(is_null($addresses) || $addresses->isEmpty())
                            <div class="form-group">
                                <label>Client Address</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="AddressType[]" placeholder="Enter Address Type">
                                    <button class="btn btn-sm btn-primary addRowBtn" style="border-radius: 0px;" type="button">+</button>
                                </div>
                                <textarea type="text" class="form-control" name="Address[]" placeholder="Enter Address" rows="2"></textarea>
                                <input type="hidden" name="AddressIds[]" value="">
                            </div>
                        @else
                            @foreach($addresses as $address)
                                <div class="form-group">
                                    <label>Client Address</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="AddressType[]" placeholder="Enter Address Type" value="{{ $address->AddressType }}">
                                        <button class="btn btn-sm btn-primary addRowBtn" style="border-radius: 0px;" type="button">+</button>
                                    </div>
                                    <textarea type="text" class="form-control" name="Address[]" placeholder="Enter Address" rows="2">{{ $address->Address }}</textarea>
                                    <input type="hidden" name="AddressIds[]" value="{{ $address->id }}">
                                </div>
                            @endforeach
                        @endif
                    </div>

                </div>
                <div class="col-lg-12" align="right">
                    <!-- <button type="button" name="delete" class="achivedClient btn btn-danger" data-id="{{$data->id}}"><i class="ti ti-archive"></i></button> -->
                    <a href="{{ session('last_client_page', url('/client')) }}" class="btn btn-md btn-secondary">Close</a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        $('.js-example-basic-single').select2();

        // Initialize region select based on existing data
        populateRegions("{{ $data->Type }}");

        // Event listener for Type dropdown change
        $('#Type').on('change', function() {
            var selectedType = $(this).val();
            populateRegions(selectedType);
        });

        // Event listener for Region dropdown change
        $('#ClientRegionId').on('change', function() {
            var selectedRegionId = $(this).val();
            if (selectedRegionId) {
                populateAreas(selectedRegionId);
            }
        });

        // Function to populate regions based on type
        function populateRegions(type) {
            var $regionSelect = $('#ClientRegionId');

            // Clear existing options
            $regionSelect.empty();
            $regionSelect.append('<option value="" disabled selected>Select Region</option>');

            // AJAX request to fetch regions from Laravel backend
            $.ajax({
                url: "{{ url('/regions') }}",
                method: 'GET',
                data: { type: type },
                dataType: 'json',
                success: function(response) {
                    if (response.length > 0) {
                        $.each(response, function(index, region) {
                            $regionSelect.append('<option value="' + region.id + '" ' + (region.id == "{{ $data->ClientRegionId }}" ? 'selected' : '') + '>' + region.name + '</option>');
                        });

                        // Enable the select box and show the region select group
                        $regionSelect.prop('disabled', false);
                        $('#regionSelectGroup').show();

                        // Populate areas if a region is already selected
                        if ("{{ $data->ClientRegionId }}" !== "") {
                            populateAreas("{{ $data->ClientRegionId }}");
                        }
                    } else {
                        $regionSelect.append('<option value="" disabled>No regions available</option>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching regions:', error);
                }
            });
        }

        // Function to populate areas based on region
        function populateAreas(regionId) {
            var $areaSelect = $('#ClientAreaId');

            // Clear existing options
            $areaSelect.empty();
            $areaSelect.append('<option value="" disabled selected>Select Area</option>');

            // AJAX request to fetch areas from Laravel backend
            $.ajax({
                url: "{{ url('/areas') }}",
                method: 'GET',
                data: { regionId: regionId },
                dataType: 'json',
                success: function(response) {
                    if (response.length > 0) {
                        $.each(response, function(index, area) {
                            $areaSelect.append('<option value="' + area.id + '" ' + (area.id == "{{ $data->ClientAreaId }}" ? 'selected' : '') + '>' + area.name + '</option>');
                        });

                        // Enable the select box and show the area select group
                        $areaSelect.prop('disabled', false);
                        $('#areaSelectGroup').show();
                    } else {
                        $areaSelect.append('<option value="" disabled>No areas available</option>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching areas:', error);
                }
            });
        }

        $(document).on('click', '.addRowBtn', function() {
            var newRow = $('<div class="form-group">' +
                            '<label>Client Address</label>' +
                            '<div class="input-group">' +
                                '<input type="text" class="form-control" name="AddressType[]" placeholder="Enter Address Type">' +
                                // '<button class="btn btn-sm btn-primary addRowBtn" style="border-radius: 0px;" type="button">+</button>' +
                                '<button class="btn btn-sm btn-danger removeRowBtn" style="border-radius: 0px;" type="button">-</button>' +
                            '</div>' +
                            '<textarea type="text" class="form-control" name="Address[]" placeholder="Enter Address" rows="2"></textarea>' +
                            '<input type="hidden" name="AddressIds[]" value="">' +
                        '</div>');

            // Append the new row to the container where addresses are listed
            $('#addressContainer').append(newRow);
        });

        $(document).on('click', '.removeRowBtn', function() {
            $(this).closest('.form-group').remove();
        });

        $('#editFormClient').on('submit', function(event) {
            event.preventDefault();

            var formData = new FormData(this);
            $('#editFormClient').find('.is-invalid').removeClass('is-invalid');

            $.ajax({
                url: "{{ url('client/update/'.$data->id) }}",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Success!',
                            text: response.success,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        }).then((result) => {
                            setTimeout(function() {
                                window.location.href = "{{ session('last_client_page', url('/client')) }}";
                            })
                        });
                    } else if (response.errors) {
                        // Handle validation errors display
                        var errorsHtml = '<div class="alert alert-danger" style="border-color: red;"><ul>';
                        $.each(response.errors, function(index, error) {
                            errorsHtml += '<li>' + error + '</li>';
                            // Highlight the field with errors by adding a red border
                            var fieldName = index.replace('.', '\\.');
                            var $field = $('#editFormClient').find('[name="' + fieldName + '"]');
                            
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
                                    $('#editFormClient').find('[name="AddressType[]"]').eq(fieldIndex).addClass('is-invalid');
                                } else if (index.includes('Address')) {
                                    $('#editFormClient').find('[name="Address[]"]').eq(fieldIndex).addClass('is-invalid');
                                }
                            }
                        });
                        errorsHtml += '</ul></div>';
                        $('html, body').animate({
                            scrollTop: $('#editFormClient').offset().top
                        }, 1000);
                        $('#form_result').html(errorsHtml).show();
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            var input = $('[name="' + key + '"]');
                            input.addClass('is-invalid');
                            input.after('<div class="invalid-feedback">' + value + '</div>');
                        });
                    }
                }
            });
        });
    });

</script>
@endsection

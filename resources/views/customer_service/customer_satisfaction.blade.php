@extends('layouts.cs_header')
@section('content')
<div class="col-12 text-center">
    <img src="{{ asset('images/whi.png') }}" style="width: 170px;" class="mt-3 mb-2">
    <h2 class="header_h2">Customer Satisfaction Form</h2>
</div>
<form id="form_satisfaction" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="CsNumber" value="{{ $newCsNo }}">
    <input type="hidden" name="Status" value="10">
    <input type="hidden" name="Progress" value="10">
    <div class="row col-lg-12 mt-3" style="margin-left: 0px">
        <div class="offset-lg-1 col-lg-5">
            <div class="form-group">
                <label class="text-white display-5">Company Name</label>
                <input type="text" class="form-control" name="CompanyName" id="CompanyName" placeholder="Enter Company Name" required>
                {{-- <select class="form-control js-example-basic-single" name="ClientId" id="ClientId" title="Select Client" required>
                    <option value="" disabled selected>Select Client</option>
                    @foreach($client as $data)
                        <option value="{{ $data->id }}" {{ old('ClientId') == $data->id ? 'selected' : '' }}>{{ $data->Name }}</option>
                    @endforeach
                </select> --}}
            </div>
        </div>
        <div class="col-lg-5">
            <div class="form-group">
                <label class="text-white display-5">Contact Name</label>
                <input type="text" class="form-control" name="ContactName" id="ContactName" placeholder="Enter Contact Name" required>
                {{-- <select class="form-control js-example-basic-single" name="ClientContactId" id="ClientContactId" title="Select Contact Name" required>
                    <option value="" disabled selected>Select Contact</option>
                </select> --}}
            </div>
        </div>
        <div class="offset-lg-1 col-lg-5">
            <div class="form-group">
                <label class="text-white display-5">Email Address</label>
                <input type="email" class="form-control" name="Email" id="Email" placeholder="Enter Email Address" required>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="form-group">
                <label class="text-white display-5">Contact Number</label>
                <input type="text" class="form-control" name="ContactNumber" id="ContactNumber" placeholder="Enter Contact Number">
            </div>
        </div>
        <div class="offset-lg-1 col-lg-5">
            <div class="form-group">
                <label class="text-white display-5">Department Concerned</label>
                <select class="form-control js-example-basic-single" name="Concerned" id="Concerned" title="Select Concerned">
                    <option value="" disabled selected>Select Concerned</option>
                    @foreach($concern_department as $data)
                        <option value="{{ $data->id }}" {{ old('Concerned') == $data->id ? 'selected' : '' }}>{{ $data->Name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="form-group">
                <label class="text-white display-5">Feedback Category</label>
                <select class="form-control js-example-basic-single" name="Category" id="Category" title="Select Category" required>
                    <option value="" disabled selected>Select Category</option>
                    @foreach($category as $data)
                        <option value="{{ $data->id }}" {{ old('Category') == $data->id ? 'selected' : '' }}>{{ $data->Name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="offset-lg-1 col-lg-10">
            <div class="form-group">
                <label class="text-white display-5">Description</label>
                <textarea class="form-control" rows="5" name="Description" placeholder="Enter Description" required>{{ old('Description') }}</textarea>
            </div>
        </div>
        <!-- <div class="offset-lg-1 col-lg-5">
            <div class="form-group">
                <label class="text-white display-5">Attachments</label>
                <input type="file" class="form-control attachments" name="Path[]" id="Path" multiple>
            </div>
        </div> -->
        <!-- <div class="offset-lg-1 col-lg-10 mt-3 mb-2">
            <label class="text-white">FOR QUALITY CONCERNS</label>
        </div>
        <div class="offset-lg-1 col-lg-5">
            <div class="form-group">
                <label class="text-white display-5">Product/s</label>
                <input type="text" class="form-control" name="Products" placeholder="Enter Product/s" value="{{ old('Products') }}">
            </div>
        </div>
        <div class="col-lg-5">
            <div class="form-group">
                <label class="text-white display-5">Applications</label>
                <input type="text" class="form-control" name="Applications" placeholder="Enter Applications" value="{{ old('Applications') }}">
            </div>
        </div>
        <div class="offset-lg-1 col-lg-5">
            <div class="form-group">
                <label class="text-white display-5">Annual Potential Volume</label>
                <input type="text" class="form-control" name="AnnualPotentialVolume" placeholder="Enter Annual Potential Volume" value="{{ old('AnnualPotentialVolume') }}">
            </div>
        </div> -->
        <div class="col-lg-11 mt-3 mb-3" align="right">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>
</form>

<style>
    .select2-container {
        width: auto !important;
    }
    div:where(.swal2-container).swal2-backdrop-show, div:where(.swal2-container).swal2-noanimation {
        background: rgb(0 0 0 / 90%);
    }
    .attachments {
        padding: 15px;
        height: 50px;
    }
</style>

<script>
    $(document).ready(function() {
        $('.js-example-basic-single').select2();

        $('#ClientId').on('change', function() {
            var clientId = $(this).val();
            if(clientId) {
                $.ajax({
                    url: '{{ url("contacts_by_client") }}/' + clientId,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('#ClientContactId').empty().append('<option value="" disabled selected>Select Contact</option>');
                        $.each(data, function(key, value) {
                            $('#ClientContactId').append('<option value="'+ key +'">'+ value +'</option>');
                        });
                        var oldClientContactId = '{{ old("ClientContactId") }}';
                        if (oldClientContactId) {
                            $('#ClientContactId').val(oldClientContactId).change();
                        }
                    }
                });
            } else {
                $('#ClientContactId').empty().append('<option value="" disabled selected>Select Contact</option>');
            }
        });

        $('#form_satisfaction').on('submit', function(event) {
            event.preventDefault();

            var formData = new FormData(this);
            $.ajax({
                url: "{{ route('customer_satisfaction.store') }}",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('input[name="CsNumber"]').val(response.newCsNo);
                    if (response.success) {
                        // Display a Swal success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Saved',
                            text: response.success,
                            timer: 2000,
                            showConfirmButton: false,
                        }).then((result) => {
                            $('#form_satisfaction')[0].reset();
                            window.location.href = "{{ url('customer_service') }}";
                        });
                    }
                }
            });
        });
    });
</script>
@endsection

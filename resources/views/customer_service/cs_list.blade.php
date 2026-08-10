@extends('layouts.header')
@section('title', 'Customer Satisfaction - CRMS')
@section('content')
<link href="{{ asset('css/filepond.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css">

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card border border-1 border-primary rounded-0">
        <div class="card-header bg-primary">
            <p class="m-0 text-white font-weight-bold">Customer Satisfaction List</p>
        </div>
        <div class="card-body">
            <div class="form-group">
                <form method="GET">
                    <label>Show : </label>
                    <label class="checkbox-inline">
                        <input name="open" class="activity_status" type="checkbox" value="10" @if(request('open', $open) == '10') checked @endif> Open
                    </label>
                    <label class="checkbox-inline">
                        <input name="close" class="activity_status" type="checkbox" value="30" @if(request('close') == '30') checked @endif> Closed
                    </label>
                    <button type="submit" class="btn btn-sm btn-primary">Filter Status</button>
                </form>
            </div>
            <div class="mb-3 d-flex gap-2">
                {{-- <form method="GET" action="{{ url('customer_complaint_export') }}">
                    <input type="hidden" name="open" value="{{ $open }}">
                    <input type="hidden" name="close" value="{{ $close }}">
                    <button type="submit" class="btn btn-outline-success">
                        Export
                    </button>
                </form> --}}
                @if ($roleType == "LS" || $roleType == "IS")
                    <button type="submit" class="btn btn-outline-primary" data-toggle="modal" data-target="#satisfactionModal">
                        Add New
                    </button>
                @endif
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <span>Showing</span>
                    <form method="GET" class="d-inline-block" onsubmit="show()">
                        <select name="entries" class="form-control">
                            <option value="10"  @if($entries == 10) selected @endif>10</option>
                            <option value="25"  @if($entries == 25) selected @endif>25</option>
                            <option value="50"  @if($entries == 50) selected @endif>50</option>
                            <option value="100" @if($entries == 100) selected @endif>100</option>
                        </select> 
                    </form>
                    <span>Entries</span>
                </div>
                <div class="col-lg-6">
                    <form method="GET" class="custom_form mb-3" enctype="multipart/form-data">
                        <div class="row height d-flex justify-content-end align-items-end">
                            <div class="col-md-9">
                                <div class="search">
                                    <i class="ti ti-search"></i>
                                    <input type="text" class="form-control" placeholder="Search Customer Satisfaction" name="search" value="{{$search}}">
                                    <button class="btn btn-sm btn-info">Search</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="spe_table" width="100%">
                    <thead>
                        <tr>
                            <th>CSR #</th>
                            <th>Date Requested</th>
                            <th>Company Name</th>
                            <th>Contact Name</th>
                            <th>Department Concerned</th>
                            <!-- <th>Category</th> -->
                            <th>Customer Remarks</th>
                            <th>Received By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($data) > 0)
                            @foreach ($data as $cs_data)
                            <tr>
                                <td class="{{ is_null($cs_data->users) ? 'text-danger-bold' : '' }}">
                                    <a href="{{ url('customer_satisfaction/view/' . $cs_data->id) }}" title="View Customer Satisfaction">{{ $cs_data->CsNumber }}</a>
                                </td>
                                <td class="{{ is_null($cs_data->users) ? 'text-danger-bold' : '' }}">{{ date('M. d, Y', strtotime($cs_data->created_at)) }}</td>
                                <td class="{{ is_null($cs_data->users) ? 'text-danger-bold' : '' }}">{{ $cs_data->CompanyName }}</td>
                                <td class="{{ is_null($cs_data->users) ? 'text-danger-bold' : '' }}">{{ $cs_data->ContactName }}</td>
                                <td class="{{ is_null($cs_data->users) ? 'text-danger-bold' : '' }}">{{ $cs_data->Concerned->Name ?? 'N/A' }}</td>
                                {{-- <td class="{{ is_null($cs_data->users) ? 'text-danger-bold' : '' }}">{{ $cs_data->category->Name }}</td> --}}
                                <td class="{{ is_null($cs_data->users) ? 'text-danger-bold' : '' }}">{{ $cs_data->Description ?? 'N/A' }}</td>
                                <td class="{{ is_null($cs_data->users) ? 'text-danger-bold' : '' }}">{{ $cs_data->users->full_name ?? 'N/A' }}</td>
                                <!-- <td>
                                    @if($cs_data->Status == 10)
                                        <div class="badge badge-success">Open</div>
                                    @else
                                        <div class="badge badge-warning">Closed</div>
                                    @endif
                                </td> -->
                            </tr>   
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" align="center">No data available.</td>
                            </tr>
                        @endif                 
                    </tbody>
                </table>
            </div>
            {{ $data->appends(request()->query())->links() }}
            @php
                $total = $data->total();
                $currentPage = $data->currentPage();
                $perPage = $data->perPage();

                $from = ($currentPage - 1) * $perPage + 1;
                $to = min($currentPage * $perPage, $total);
            @endphp
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>Showing {{ $from }} to {{ $to }} of {{ $total }} entries</div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="satisfactionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Satisfation </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form_satisfaction" method="POST" enctype="multipart/form-data" onsubmit="show()">
                    @csrf
                    <input type="hidden" name="Status" value="10">
                    <input type="hidden" name="isInternal" value="1">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="label">Customer Name</label>
                                <input type="text" class="form-control" name="ContactName" id="ContactName" placeholder="Enter Customer Name" required>
                            </div>
                        </div>
                        <div class="col-md-6"> 
                            <div class="form-group">
                                <label class="label">Company Name</label>
                                <input type="text" class="form-control" name="CompanyName" id="CompanyName" placeholder="Enter Company Name" required>
                            </div>
                        </div>
                        <div class="col-md-6"> 
                            <div class="form-group">
                                <label class="label">Contact Number</label>
                                <input type="text" class="form-control" name="ContactNumber" id="ContactNumber" placeholder="Enter Contact Number" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="label">Email Address</label>
                                <input type="email" class="form-control" name="Email" id="Email" placeholder="Enter Email Address" required>
                            </div>
                        </div>
                        <div class="col-md-6"> 
                            <div class="form-group">
                                <label class="label">Feedback Category</label>
                                <select class="form-control js-example-basic-single" name="Category" id="Category" title="Select Category" required>
                                    <option value="" disabled selected>Select Category</option>
                                    @foreach($category as $data)
                                        <option value="{{ $data->id }}" {{ old('Category') == $data->id ? 'selected' : '' }}>{{ $data->Name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6"> 
                            <div class="form-group">
                                <label class="label">Country</label>
                                <select class="form-control js-example-basic-single" name="ClientCountryId" id="Country" title="Select Country">
                                    <option value="" disabled selected>Select Country</option>
                                    @foreach($countries as $data)
                                        <option value="{{ $data->id }}" >{{ $data->Name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="label">Attachments</label>
                                <input
                                    type="file"
                                    class="filepond"
                                    name="Path[]"
                                    id="Path2"
                                    multiple
                                    accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="label" for="#">Customer Feedback</label>
                                <textarea class="form-control" rows="5" name="Description" placeholder="Enter Description" required>{{ old('Description') }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12" align="right">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<style>
    .text-danger-bold {
        font-weight: bold;
    }
</style>
<script src="https://unpkg.com/filepond/dist/filepond.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
<script>
    var roleType = "{{ $roleType }}";
    var url = "";
    if (roleType == 'LS' ) {
        url = "{{ route('customer_satisfaction_ls.store') }}";
    } 
    if (roleType == 'IS' ) {
        url = "{{ route('customer_satisfaction_is.store') }}";
    }
    
    $('#form_satisfaction').on('submit', function(event) {
        event.preventDefault();

        var formData = new FormData(this);
        var submitBtn = $("button[type='submit']");
        
        // **Disable the button and show loading**
        submitBtn.prop("disabled", true).html('<i class="fa fa-spinner fa-spin"></i> Submitting...');

        $.ajax({
            url: url,
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
                    Swal.fire({
                        icon: 'success',
                        title: 'Saved',
                        text: response.success,
                        timer: 2000,
                        showConfirmButton: false
                    }).then((result) => {
                        $('#form_satisfaction')[0].reset();
                        location.reload();
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Something went wrong. Please try again!',
                });
            },
            complete: function() {
                // **Re-enable the button after request is complete**
                submitBtn.prop("disabled", false).html('Submit');
            }
        });
    });
    document.addEventListener('DOMContentLoaded', function () {
        // Register plugins
        FilePond.registerPlugin(
            // FilePondPluginFileValidateType,
            FilePondPluginFileValidateSize,
            FilePondPluginImagePreview
        );

        // Create FilePond instance
        const pond = FilePond.create(document.querySelector('#Path'), {
            allowMultiple: true,
            maxFileSize: '10MB',

            server: {
                process: {
                    url: '{{ url("/upload-temp") }}',
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    onload: (response) => {
                        // return the file name only (so it becomes the Path[] value)
                        return JSON.parse(response).id;
                    }
                },
                revert: {
                    url: '{{ url("/upload-revert") }}',
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                }
            }
        });

        const pond2 = FilePond.create(document.querySelector('#Path2'), {
            allowMultiple: true,
            maxFileSize: '10MB',

            server: {
                process: {
                    url: '{{ url("/upload-temp") }}',
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    onload: (response) => {
                        // return the file name only (so it becomes the Path[] value)
                        return JSON.parse(response).id;
                    }
                },
                revert: {
                    url: '{{ url("/upload-revert") }}',
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                }
            }
        });
    });
</script>
@endsection
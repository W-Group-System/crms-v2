@extends('layouts.header')
@section('content')
<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">View Customer Satisfaction
                <div align="right">
                    <a href="{{ url()->previous() ?: url('customer_satisfaction') }}" class="btn btn-md btn-outline-secondary">
                        <i class="icon-arrow-left"></i>&nbsp;Back
                    </a>
                    @if($data->ReceivedBy == NULL)
                        <form action="{{ url('cs_received/' . $data->id) }}" class="d-inline-block" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-success receivedBtn">
                                <i class="ti-bookmark">&nbsp;</i> Received
                            </button>
                        </form>
                    @endif
                    @if(primarySalesApprover($data->ReceivedBy, auth()->user()->id))
                        @if($data->ReceivedBy != NULL && $data->NotedBy == NULL)
                            <form action="{{ url('cs_noted/' . $data->id) }}" class="d-inline-block" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-success notedBtn">
                                    <i class="ti-check">&nbsp;</i> Noted By
                                </button>
                            </form>
                        @endif
                    @endif
                    @if($data->ApprovedBy != NULL)
                         <button type="button" class="btn btn-outline-primary" id="assignCs" data-id="{{ $data->id }}" data-toggle="modal" data-target="#assignedCs">
                            <i class="ti ti-pencil"></i>&nbsp;Assign 
                        </button>
                        <button type="button" class="btn btn-outline-warning" id="updateCs" data-id="{{ $data->id }}" data-toggle="modal" data-target="#editCs">
                            <i class="ti ti-comment"></i>&nbsp;Remarks
                        </button>
                        <a class="btn btn-outline-danger btn-icon-text" href="{{url('print_cs/'.$data->id)}}" target="_blank">
                            <i class="ti ti-printer btn-icon-prepend"></i>
                            Print
                        </a>
                        <form action="{{ url('cs_closed/' . $data->id) }}" class="d-inline-block" method="POST">
                            @csrf
                            <button type="button" class="btn btn-outline-secondary closedBtn">
                                <i class="ti ti-close"></i>&nbsp;Close
                            </button>
                        </form>
                    @endif
                    @if($data->NotedBy != NULL && auth()->user()->id == 15)
                        @if($data->Progress != 40)
                        <form action="{{ url('cs_approved/' . $data->id) }}" class="d-inline-block" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-success approvedBtn">
                                <i class="ti-check">&nbsp;</i> Approved
                            </button>
                        </form>
                        @endif
                    @endif
                </div>
            </h4>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row mb-0" style="margin-top: 2em">
                        <label class="col-sm-3 col-form-label text-right"><b>CSR #:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->CsNumber }}</label>
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Date Requested:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->created_at }}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <label class="col-sm-3 col-form-label text-right"><b>Date Received:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->DateReceived }}</label>
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Received By:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->users->full_name ?? 'N/A' }}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <label class="col-sm-3 col-form-label text-right"><b>Concerned Department:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->concerned->Name ?? 'N/A' }}</label>
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Noted By:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->notedBy->full_name ?? 'N/A' }}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label text-right"><b>Category:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->category->Name ?? 'N/A' }}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <label class="col-sm-3 col-form-label text-right"><b>Company Name:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->CompanyName }}</label>
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Approved By:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->approvedBy->full_name ?? 'N/A' }}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <label class="col-sm-3 col-form-label text-right"><b>Contact Name:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->ContactName }}</label>
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Closed By:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->closedBy->full_name ?? 'N/A' }}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <label class="col-sm-3 col-form-label text-right"><b>Contact Number:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->ContactNumber }}</label>
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Date Closed:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->DateClosed ?? 'N/A' }}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label text-right"><b>Email:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->Email }}</label>
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Attachments:</b></label>
                        <div class="col-sm-3">
                            @if($data->cs_attachments && $data->cs_attachments->isNotEmpty())
                                @foreach($data->cs_attachments as $file)
                                    <label style="display: block;">
                                        <a href="{{ asset('storage/' . $file->Path) }}" target="_blank">{{ basename($file->Path) }}</a>
                                        <!-- <button type="button" class="btn btn-sm deleteFile">
                                            <i class="ti ti-close" style="color:red"></i>
                                        </button> -->
                                    </label>
                                @endforeach
                            @else
                                <label>No Attachments Available</label>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label text-right"><b>Description:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->Description }}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <label class="col-sm-3 col-form-label text-right"><b>Internal Instruction Remarks:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->Response }}</label>
                        </div>
                    </div>
                </div>
            </div>
            <div align="right" class="mt-3">
                <a href="{{ url('customer_satisfaction') }}" class="btn btn-outline-secondary">Close</a>
            </div>
            <!-- <ul class="nav nav-tabs viewTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link p-2 @if(session('tab') == 'files' || session('tab') == null) active @endif" id="files-tab" data-toggle="tab" href="#files" role="tab" aria-controls="files" aria-selected="true">Files</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade @if(session('tab') == 'files' || session('tab') == null) active show @endif" id="files" role="tabpanel" aria-labelledby="files">
                </div>
            </div> -->
        </div>
    </div>
</div>

<div class="modal fade" id="editCs" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCustomerRequirementLabel">Internal Instruction Remarks</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="updateCustomerSatisfaction" method="POST" action="{{ url('update_customer_satisfaction/' . $data->id) }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <textarea class="form-control" rows="10" name="Response" placeholder="Enter Internal Instruction Remarks" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer mt-3">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-outline-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="assignedCs" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCustomerRequirementLabel">Assign Department</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="assignCustomerSatisfaction" method="POST" action="{{ url('assign_customer_satisfaction/' . $data->id) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Select Department Concerned</label>
                                <select class="form-control js-example-basic-single" name="Concerned" id="Concerned" required>
                                    <option value="" disabled selected>Select Department Concerned</option>
                                    @foreach($concern_department as $dept)
                                        <option value="{{ $dept->id }}" {{ old('Concerned') == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->Name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Attachments</label>
                                <input type="file" class="form-control attachments" name="Path[]" id="Path" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer mt-3">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-outline-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .swal-wide {
        width: 450px;
    }
</style>

<script>
    $(document).ready(function () {
        $('#updateCustomerSatisfaction').on('submit', function (e) {
            e.preventDefault(); 

            var form = $(this);
            var actionUrl = form.attr('action');

            $.ajax({
                url: actionUrl,
                type: 'POST',
                data: form.serialize(),
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: "Updated",
                            text: response.message,
                            icon: "success",
                            showConfirmButton: false,
                            timer: 1500
                        }).then(function () {
                            window.location.reload(); 
                        });
                    }
                }
            });
        });

        $('#assignCustomerSatisfaction').on('submit', function (e) {
            e.preventDefault(); 

            var formData = new FormData(this); // Use FormData to handle file uploads
            var actionUrl = $(this).attr('action');

            $.ajax({
                url: actionUrl,
                type: 'POST',
                data: formData,
                processData: false, 
                contentType: false, 
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: "Assigned",
                            text: response.message,
                            icon: "success",
                            showConfirmButton: false,
                            timer: 1500
                        }).then(function () {
                            window.location.reload(); 
                        });
                    }
                },
                error: function (xhr) {
                    Swal.fire("Error", "Something went wrong! Please try again.", "error");
                }
            });
        });

        $('.notedBtn').on('click', function (e) {
            e.preventDefault(); // Prevent the default form submission

            var form = $(this).closest('form');
            var actionUrl = form.attr('action'); // Get form action URL

            $.ajax({
                url: actionUrl,
                type: 'POST',
                data: form.serialize(), // Serialize form data
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: "Noted",
                            text: response.message,
                            icon: "success",
                            showConfirmButton: false,
                            customClass: 'swal-wide',
                            timer: 1500
                        }).then(function () {
                            window.location.reload(); // Reload the page after the alert
                        });
                    }
                }
            });
        });

        $('.receivedBtn').on('click', function (e) {
            e.preventDefault(); // Prevent the default form submission

            var form = $(this).closest('form');
            var actionUrl = form.attr('action'); // Get form action URL

            $.ajax({
                url: actionUrl,
                type: 'POST',
                data: form.serialize(), // Serialize form data
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: "Received",
                            text: response.message,
                            icon: "success",
                            showConfirmButton: false,
                            customClass: 'swal-wide',
                            timer: 1500
                        }).then(function () {
                            window.location.reload(); // Reload the page after the alert
                        });
                    }
                }
            });
        });

        $('.approvedBtn').on('click', function (e) {
            e.preventDefault(); // Prevent the default form submission

            var form = $(this).closest('form');
            var actionUrl = form.attr('action'); // Get form action URL

            $.ajax({
                url: actionUrl,
                type: 'POST',
                data: form.serialize(), // Serialize form data
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: "Approved",
                            text: response.message,
                            icon: "success",
                            showConfirmButton: false,
                            customClass: 'swal-wide',
                            timer: 1500
                        }).then(function () {
                            window.location.reload(); // Reload the page after the alert
                        });
                    }
                }
            });
        });

        $('.closedBtn').on('click', function (e) {
            e.preventDefault(); // Prevent the default form submission

            var form = $(this).closest('form');
            var actionUrl = form.attr('action'); // Get form action URL

            $.ajax({
                url: actionUrl,
                type: 'POST',
                data: form.serialize(), // Serialize form data
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: "Closed",
                            text: response.message,
                            icon: "success",
                            showConfirmButton: false,
                            customClass: 'swal-wide',
                            timer: 1500
                        }).then(function () {
                            window.location.reload(); // Reload the page after the alert
                        });
                    }
                }
            });
        });
    });

</script>
@endsection

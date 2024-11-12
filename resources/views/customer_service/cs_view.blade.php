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
                    @if($data->Status == 10 && $data->ReceivedBy == NULL)
                        <form action="{{ url('cs_received/' . $data->id) }}" class="d-inline-block" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-success receivedBtn">
                                <i class="ti-bookmark">&nbsp;</i> Received
                            </button>
                        </form>
                    @endif
                    @if(primarySalesApprover($data->ReceivedBy, auth()->user()->id))
                        @if($data->Status == 10 && $data->ReceivedBy != NULL && $data->NotedBy == NULL)
                            <form action="{{ url('cs_noted/' . $data->id) }}" class="d-inline-block" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-success notedBtn">
                                    <i class="ti-check">&nbsp;</i> Noted By
                                </button>
                            </form>
                        @endif
                    @endif
                    @if($data->Progress == 30)
                        <button type="button" class="btn btn-outline-warning" id="updateCs" data-id="{{ $data->id }}" data-toggle="modal" data-target="#editCs">
                            <i class="ti ti-pencil"></i>&nbsp;Response
                        </button>
                        <form action="{{ url('cs_closed/' . $data->id) }}" class="d-inline-block" method="POST">
                            @csrf
                            <button type="button" class="btn btn-outline-warning closedBtn">
                                <i class="ti ti-close"></i>&nbsp;Close
                            </button>
                        </form>
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
                        <label class="col-sm-3 col-form-label text-right"><b>Status:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->Status == 10 ? 'Open' : 'Closed' }}</label>
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
                        <label class="col-sm-3 col-form-label text-right"><b>Response:</b></label>
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
                <h5 class="modal-title" id="addCustomerRequirementLabel">Action/ Response</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="updateCustomerSatisfaction" method="POST" action="{{ url('update_customer_satisfaction/' . $data->id) }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <textarea class="form-control" rows="10" name="Response" placeholder="Enter Response" required></textarea>
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

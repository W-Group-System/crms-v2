@extends('layouts.header')
@section('content')
<link href="{{ asset('css/filepond.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css">

<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">View Customer Satisfaction
                <div align="right">
                    <a href="{{ url()->previous() ?: url('customer_satisfaction') }}" class="btn btn-md btn-outline-secondary">
                        <i class="icon-arrow-left"></i>&nbsp;Back
                    </a>
                    @if(auth()->user()->role->type != 'IAD')
                        @if($data->ReceivedBy == NULL)
                            <form action="{{ url('cs_received/' . $data->id) }}" class="d-inline-block" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-success receivedBtn">
                                    <i class="ti-bookmark">&nbsp;</i>Receive
                                </button>
                            </form>
                        @endif
                        @if(primarySalesApprover($data->ReceivedBy, auth()->user()->id))
                            @if($data->ReceivedBy != NULL && $data->NotedBy == NULL)
                                <!-- <form action="{{ url('cs_noted/' . $data->id) }}" class="d-inline-block" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-success notedBtn">
                                        <i class="ti-check">&nbsp;</i> Noted By
                                    </button>
                                </form> -->
                                <button type="button" class="btn btn btn-outline-success" id="editNote" data-id="{{ $data->id }}" data-toggle="modal" data-target="#editNoted">
                                    <i class="ti ti-check"></i>&nbsp;Noted By
                                </button>
                            @endif
                        @endif
                        @if($data->ApprovedBy != NULL)
                            @if($data->Department == NULL)
                            <button type="button" class="btn btn-outline-primary" id="assignCs" data-id="{{ $data->id }}" data-toggle="modal" data-target="#assignedCs">
                                <i class="ti ti-pencil"></i>&nbsp;Share
                            </button>
                            @endif
                            <button type="button" class="btn btn-outline-warning" id="updateCs" data-id="{{ $data->id }}" data-toggle="modal" data-target="#editCs">
                                <i class="ti ti-comment"></i>&nbsp;Remarks
                            </button>
                            <a class="btn btn-outline-danger btn-icon-text" href="{{url('print_cs/'.$data->id)}}" target="_blank">
                                <i class="ti ti-printer btn-icon-prepend"></i>
                                Print
                            </a>
                            <!-- <form action="{{ url('cs_closed/' . $data->id) }}" class="d-inline-block" method="POST">
                                @csrf
                                <button type="button" class="btn btn-outline-secondary closedBtn">
                                    <i class="ti ti-close"></i>&nbsp;Close Satisfaction
                                </button>
                            </form> -->
                        @endif
                        @if(primarySalesApprover($data->NotedBy, auth()->user()->id) && $data->ApprovedBy == NULL)
                            <form action="{{ url('cs_approved/' . $data->id) }}" class="d-inline-block" method="POST" onsubmit="show()">
                                @csrf
                                <button type="submit" class="btn btn-outline-success approvedBtn">
                                    <i class="ti-check">&nbsp;</i> Acknowledge
                                </button>
                            </form>
                        @endif
                    @endif
                    <!-- @if($data->NotedBy != NULL && auth()->user()->id == 15)
                        @if($data->Progress != 40)
                        <form action="{{ url('cs_approved/' . $data->id) }}" class="d-inline-block" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-success approvedBtn">
                                <i class="ti-check">&nbsp;</i> Acknowledged
                            </button>
                        </form>
                        @endif
                    @endif -->
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
                        <!-- <label class="col-sm-3 col-form-label text-right"><b>Date Received:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->DateReceived }}</label>
                        </div> -->
                        <label class="offset-sm-6 col-sm-3 col-form-label text-right"><b>Received By:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->users->full_name }}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <label class="col-sm-3 col-form-label text-right"><b>Concerned Department:</b></label>
                        <div class="col-sm-3">
                            <!-- <label>{{ $data->concerned->Name }}</label> -->
                            <label>{{ $data->Department }}</label>
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Noted By:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->notedBy->full_name }}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label text-right"><b>Category:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->category->Name }}</label>
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Remarks:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->NotedRemarks }}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <label class="col-sm-3 col-form-label text-right"><b>Company Name:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->CompanyName }}</label>
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Acknowledged By:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->approvedBy->full_name }}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <label class="col-sm-3 col-form-label text-right"><b>Customer Name:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->ContactName }}</label>
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Contact Number:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->ContactNumber }}</label>
                        </div>
                        <!-- <label class="col-sm-3 col-form-label text-right"><b>Closed By:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->closedBy->full_name }}</label>
                        </div> -->
                    </div>
                    <!-- <div class="form-group row mb-0">
                        <label class="col-sm-3 col-form-label text-right"><b>Contact Number:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->ContactNumber }}</label>
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Date Closed:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->DateClosed }}</label>
                        </div>
                    </div> -->
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
                                        <button type="button" class="btn btn-sm deleteFile" data-id="{{ $file->id }}">
                                            <i class="ti ti-close" style="color:red"></i>
                                        </button>
                                    </label>
                                @endforeach
                            @else
                                <label>No Attachments Available</label>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <label class="col-sm-3 col-form-label text-right"><b>Customer Feedback:</b></label>
                        <div class="col-sm-8">
                            <label>{{ $data->Description }}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label text-right"><b>Customer Attachments:</b></label>
                        <div class="col-sm-3">
                            @if($data->customer_attachments && $data->customer_attachments->isNotEmpty())
                                @foreach($data->customer_attachments as $file)
                                    <label style="display: block;">
                                        <a href="{{ asset('storage/' . $file->Path) }}" target="_blank">{{ basename($file->Path) }}</a>
                                        <!-- <button type="button" class="btn btn-sm deleteFile" data-id="{{ $file->id }}">
                                            <i class="ti ti-close" style="color:red"></i>
                                        </button> -->
                                    </label>
                                @endforeach
                            @else
                                <label>No Attachments Available</label>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <label class="col-sm-3 col-form-label text-right"><b>Internal Remarks:</b></label>
                        <div class="col-sm-8">
                            @foreach($for_remarks->sortByDesc('created_at') as $remark)
                            <div class="border border-light rounded p-2 mb-3">
                                <label> {!! $remark->Remarks !!}</label>
                                <div>
                                    <p class="m-0">Remarks by: {{$remark->user->full_name}}</p>
                                    <p class="text-muted"><small>{{date('h:i A - M d, Y',strtotime($remark->created_at))}}</small></p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div align="right" class="mt-3">
                <a href="{{ url('cs_list') }}" class="btn btn-outline-secondary">Close</a>
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

<div class="modal fade" id="editNoted" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCustomerRequirementLabel">Noted By</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="notedRemarks" method="POST" action="{{ url('noted_remarks/' . $data->id) }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <textarea class="form-control" rows="10" name="NotedRemarks" placeholder="Enter Remarks" required></textarea>
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

<div class="modal fade" id="editCs" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCustomerRequirementLabel">Internal Remarks</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="newRemarks" method="POST" action="{{ url('new_remarks/' . $data->id) }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <textarea class="form-control" rows="10" name="Remarks" placeholder="Enter Internal Instruction Remarks" required></textarea>
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
                <h5 class="modal-title" id="addCustomerRequirementLabel">Share to</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="assignCustomerSatisfaction" method="POST" action="{{ url('assign_customer_satisfaction/' . $data->id) }}" enctype="multipart/form-data" onsubmit="show()">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Select Site Concerned</label>
                                <select class="form-control js-example-basic-single" name="SiteConcerned" id="SiteConcerned" required>
                                    <option value="" disabled selected>Select Site Concerned</option>
                                    <option value="1">WHI Head Office</option>
                                    <option value="2">WHI Carmona</option>
                                    <option value="3">MRDC</option>
                                    <option value="4">CCC Carmen</option>
                                    <option value="5">PBI Canlubang</option>
                                    <option value="6">International Warehouse</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Select Department Concerned</label>
                                <select class="form-control js-example-basic-single" name="Department" id="Department" required>
                                    <option value="" disabled selected>Select Department Concerned</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="label">Attachments</label>
                                <input
                                    type="file"
                                    class="filepond"
                                    name="Path[]"
                                    id="Path7"
                                    multiple
                                    accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                            </div>
                        </div>
                        <!-- <div class="col-md-12">
                            <div class="form-group">
                                <label>Select Department Concerned</label>
                                <select class="form-control js-example-basic-single" name="Concerned" id="Concerned" required>
                                    <option value="" disabled selected>Select Department Concerned</option>
                                    @foreach($concern_department as $dept)
                                        <option value="{{ $dept->Name }}" {{ old('Concerned') == $dept->Name ? 'selected' : '' }}>
                                            {{ $dept->Name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div> -->
                        <!-- <div class="col-md-12">
                            <div class="form-group">
                                <label>Attachments</label>
                                <input type="file" class="form-control attachments" name="Path[]" id="Path" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                            </div>
                        </div> -->
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
<script src="https://unpkg.com/filepond/dist/filepond.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>

<script>
    $(document).ready(function () {
        $('#newRemarks').on('submit', function (e) {
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
                            title: "Success",
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

        $('#notedRemarks').on('submit', function (e) {
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
                            title: "Noted",
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
            event.preventDefault();

            var formData = new FormData(this);
            var actionUrl = $(this).attr('action');

            $.ajax({
                url: actionUrl,
                method: 'POST',
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
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong. Please try again!',
                    });
                }
            });
        });

        // $('.notedBtn').on('click', function (e) {
        //     e.preventDefault(); // Prevent the default form submission

        //     var form = $(this).closest('form');
        //     var actionUrl = form.attr('action'); // Get form action URL

        //     $.ajax({
        //         url: actionUrl,
        //         type: 'POST',
        //         data: form.serialize(), // Serialize form data
        //         success: function (response) {
        //             if (response.success) {
        //                 Swal.fire({
        //                     title: "Noted",
        //                     text: response.message,
        //                     icon: "success",
        //                     showConfirmButton: false,
        //                     customClass: 'swal-wide',
        //                     timer: 1500
        //                 }).then(function () {
        //                     window.location.reload(); // Reload the page after the alert
        //                 });
        //             }
        //         }
        //     });
        // });

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
            e.preventDefault(); // Stop normal form submission

            // Show loading before sending AJAX
            Swal.fire({
                title: 'Please wait...',
                text: 'Processing request',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            var form = $(this).closest('form');
            var actionUrl = form.attr('action');

            $.ajax({
                url: actionUrl,
                type: 'POST',
                data: form.serialize(),
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: "Acknowledged",
                            text: response.message,
                            icon: "success",
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        title: "Error",
                        text: "Something went wrong",
                        icon: "error"
                    });
                }
            });
        });

        // $('.closedBtn').on('click', function (e) {
        //     e.preventDefault(); // Prevent the default form submission

        //     var form = $(this).closest('form');
        //     var actionUrl = form.attr('action'); // Get form action URL

        //     $.ajax({
        //         url: actionUrl,
        //         type: 'POST',
        //         data: form.serialize(), // Serialize form data
        //         success: function (response) {
        //             if (response.success) {
        //                 Swal.fire({
        //                     title: "Closed",
        //                     text: response.message,
        //                     icon: "success",
        //                     showConfirmButton: false,
        //                     customClass: 'swal-wide',
        //                     timer: 1500
        //                 }).then(function () {
        //                     window.location.reload(); // Reload the page after the alert
        //                 });
        //             }
        //         }
        //     });
        // });

        $(document).on('click', '.deleteFile', function() {
            var fileId = $(this).data('id');
            var button = $(this);

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to recover this file!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('delete_cs_files', '') }}/" + fileId,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire("Deleted!", response.success, "success");
                                button.closest("label").remove(); // Remove the deleted file from the UI
                            } else {
                                Swal.fire("Error!", response.error, "error");
                            }
                        },
                        error: function() {
                            Swal.fire("Error!", "Something went wrong.", "error");
                        }
                    });
                }
            });
        });

        $('#SiteConcerned').on('change', function () {
            let siteId = $(this).val();

            if (siteId) {
                $.ajax({
                    url: "{{ url('departments-by-site') }}/" + siteId,
                    type: 'GET',
                    success: function (data) {
                        $('#Department').empty(); 
                        $('#Department').append('<option value="" disabled selected>Select Department Concerned</option>');

                        $.each(data, function (key, department) {
                            $('#Department').append('<option value="' + department.Name + '">' + department.Name + '</option>');
                        });
                    }
                });
            } else {
                $('#Department').empty();
                $('#Department').append('<option value="" disabled selected>Select Department Concerned</option>');
            }
        });
    });

    const pond = FilePond.create(document.querySelector('#Path7'), {
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

</script>
@endsection

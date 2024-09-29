@extends('layouts.header')
@section('css')
    <style>
        .form-group {
            margin: 0;
        }
    </style>
@endsection
@section('content')
<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6"> 
                    <h4 class="card-title d-flex justify-content-between align-items-center" style="margin-top: 10px">View Activity</h4>
                </div>
                <div class="col-lg-6" align="right">
                    @if(url()->previous() == url()->current())
                    <a href="{{ url('/activities?open=10') }}" class="btn btn-md btn-outline-secondary">
                        <i class="icon-arrow-left"></i>&nbsp;Back
                    </a> 
                    @else
                    <a href="{{ url()->previous() ?: url('/activities') }}" class="btn btn-md btn-outline-secondary">
                        <i class="icon-arrow-left"></i>&nbsp;Back
                    </a> 
                    @endif
                    <!-- <a href="{{ url('/activities?open=10') }}" class="btn btn-md btn-light"><i class="icon-arrow-left"></i>&nbsp;Back</a> -->
                    @if ($data->Status == 10)
                        <button class="btn btn-md btn-outline-primary close-activity" data-id="{{ $data->id }}">Close</button>
                    @else
                        <button class="btn btn-md btn-outline-primary open-activity" data-id="{{ $data->id }}">Open</button>
                    @endif
                </div>
            </div>
            <form class="form-horizontal form-view" id="form_view_activity" enctype="multipart/form-data">
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label"><b>Activity #:</b></label>
                    <label class="col-sm-3 col-form-label">{{ $data->ActivityNumber }}</label>
                    <label class="col-sm-3 col-form-label"><b>Related To:</b></label>
                    <label class="col-sm-3 col-form-label">{{ $statusRelated[$data->RelatedTo] ?? 'N/A' }}</label>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label"><b>Type:</b></label>
                    <label class="col-sm-3 col-form-label">{{ $data->type == 10 ? 'Task' : 'Call' }}</label>
                    <label class="col-sm-3 col-form-label"><b>Transaction Number:</b></label>
                    <label class="col-sm-3 col-form-label">{{ $data->TransactionNumber ?? 'N/A' }}</label>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label"><b>Primary Responsible:</b></label>
                    <label class="col-sm-3 col-form-label">{{ $primaryResponsible->full_name }}</label>
                    <label class="col-sm-3 col-form-label"><b>Schedule Start:</b></label>
                    <label class="col-sm-3 col-form-label">{{ $data->ScheduleFrom }}</label>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label"><b>Secondary Responsible:</b></label>
                    <label class="col-sm-3 col-form-label">{{ $secondaryResponsible->full_name }}</label>
                    <label class="col-sm-3 col-form-label"><b>Schedule End:</b></label>
                    <label class="col-sm-3 col-form-label">{{ $data->ScheduleTo }}</label>
                </div>
                <div class="form-group row" style="margin-top: 20px">
                    <label class="col-sm-3 col-form-label"><b>Client:</b></label>
                    <label class="col-sm-3 col-form-label">{{ $clientName }}</label>
                    <label class="col-sm-3 col-form-label"><b>Status:</b></label>
                    <label class="col-sm-3 col-form-label">{{ $data->Status == 10 ? 'Open' : 'Closed' }}</label>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label"><b>Contact:</b></label>
                    <label class="col-sm-3 col-form-label">{{ $contactName }}</label>
                    <label class="col-sm-3 col-form-label"><b>Date Closed:</b></label>
                    <label class="col-sm-3 col-form-label">{{ $data->DateClosed }}</label>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label"><b>Telephone:</b></label>
                    <label class="col-sm-3 col-form-label">{{ $clientTelephone ?? 'N/A' }}</label>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label"><b>Mobile:</b></label>
                    <label class="col-sm-3 col-form-label">{{ $contactMobile ?? 'N/A' }}</label>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label"><b>Email:</b></label>
                    <label class="col-sm-3 col-form-label">{{ $contactEmail }}</label>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label"><b>Contact Skype:</b></label>
                    <label class="col-sm-3 col-form-label">{{ $contactSkype ?? 'N/A'}}</label>
                </div>
                <div class="form-group row" style="margin-top: 20px">
                    <label class="col-sm-3 col-form-label"><b>Title:</b></label>
                    <label class="col-sm-3 col-form-label">{{ $data->Title }}</label>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label"><b>Description:</b></label>
                    <label class="col-sm-9 col-form-label">{{ $data->Description }}</label>
                </div>
                <div class="form-group row" style="margin-top: 20px">
                    <label class="col-sm-3 col-form-label"><b>Response:</b></label>
                    <label class="col-sm-9 col-form-label">{{ $data->Response ?? 'N/A' }}</label>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.close-activity').on('click', function() {
            var id = $(this).data('id');

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Close"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "{{url('close_activity')}}",
                        data: {
                            id: id
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(res) {
                            Swal.fire({
                                title: res.message,
                                icon: "success"
                            }).then(() => {
                                location.reload();
                            });
                        }
                    })
                }
            });
        })

        $('.open-activity').on('click', function() {
            var id = $(this).data('id');

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Open"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "{{url('open_activity')}}",
                        data: {
                            id: id
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(res) {
                            Swal.fire({
                                title: res.message,
                                icon: "success"
                            }).then(() => {
                                location.reload();
                            });
                        }
                    })
                }
            });
        })
    })
</script>
@endsection

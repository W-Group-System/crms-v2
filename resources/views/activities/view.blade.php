@extends('layouts.header')
@section('content')
<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6"> 
                    <h4 class="card-title d-flex justify-content-between align-items-center" style="margin-top: 10px">View Activity</h4>
                </div>
                <div class="col-lg-6" align="right">
                    <a href="{{ url('/activities') }}" class="btn btn-md btn-light"><i class="icon-arrow-left"></i>&nbsp;Back</a>
                    @if ($data->Status == 10)
                    <button class="btn btn-md btn-primary close-activity" id="close-{{ $data->id }}">Close</button>
                    @else
                    <button class="btn btn-md btn-primary open-activity" id="open-{{ $data->id }}">Open</button>
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

<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Close Activity</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 20px">
                <h5 style="margin: 0">Are you sure you want to close this activity?</h5>
            </div>
            <div class="modal-footer" style="padding: 0.6875rem">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" name="close_activity" id="close_activity" class="btn btn-danger">Yes</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmModal2" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Open Activity</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 20px">
                <h5 style="margin: 0">Are you sure you want to open this activity?</h5>
            </div>
            <div class="modal-footer" style="padding: 0.6875rem">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" name="open_activity" id="open_activity" class="btn btn-danger">Yes</button>
            </div>
        </div>
    </div>
</div>

<style>
    .form-group {
        margin-bottom: 0px;
    }
</style>

<script>
    var activity_id = "{{ $data->id }}"; // Assuming $data->id is correctly set in your view

    $(document).ready(function() {
        $('.close-activity').on('click', function() {
            $('#confirmModal').modal('show');
        });

        $('#close_activity').on('click', function() {
            $.ajax({
                url: "{{ route('delete_activity', ['id' => $data->id]) }}", // Use route() helper with parameters
                type: "DELETE",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    $('#confirmModal').modal('hide');
                    window.location.href = "{{ route('activities.index') }}";
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    alert('Error - ' + errorMessage);
                }
            });
        });

        $('.open-activity').on('click', function() {
            $('#confirmModal2').modal('show');
        });

        $('#open_activity').on('click', function() {
            $.ajax({
                url: "{{ route('open_activity', ['id' => $data->id]) }}", // Use route() helper with parameters
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    $('#confirmModal2').modal('hide');
                    window.location.href = "{{ route('activities.index') }}";
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    alert('Error - ' + errorMessage);
                }
            });
        });
    });

</script>
@endsection

@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            Activity List
            <button type="button" class="btn btn-md btn-primary" name="add_activity" id="add_activity">Add Activity</button>
            </h4>
            <div class="form-group">
                <label>Show : </label>
                <label class="checkbox-inline">
                    <input checked="checked" data-val="true" id="IsShowOpen" name="IsShowOpen" type="checkbox" value="true"><input name="IsShowOpen" type="hidden" value="false"> Open
                </label>
                <label class="checkbox-inline">
                    <input data-val="true" id="IsShowClosed" name="IsShowClosed" type="checkbox" value="true"><input name="IsShowClosed" type="hidden" value="false"> Closed
                </label>
            </div>
            <table class="table table-striped table-bordered table-hover" id="activity_table" width="100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Schedule (Y-M-D)</th>
                        <th>Client</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="formActivity" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Activity</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_activity" enctype="multipart/form-data" action="">
                    <span id="form_result"></span>
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Type</label>
                                <select class="form-control js-example-basic-single" name="Type" id="Type" style="position: relative !important" title="Select Type">
                                    <option value="" disabled selected>Select Type</option>
                                    <option value="10">Task</option>
                                    <option value="20">Call</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Related To</label>
                                <select class="form-control js-example-basic-single" name="RelatedTo" id="RelatedTo" style="position: relative !important" title="Select Type">
                                    <option value="" disabled selected>Select Related Entry Type</option>
                                    <option value="10">Customer Requirement</option>
                                    <option value="20">Request Product Evaluation</option>
                                    <option value="30">Sample Request</option>
                                    <option value="35">Price Request</option>
                                    <option value="40">Complaint</option>
                                    <option value="50">Feedback</option>
                                    <option value="60">Collection</option>
                                    <option value="70">Account Targeting</option>
                                    <option value="91">Follow-up Sample/Projects</option>
                                    <option value="92">Sample Dispatch</option>
                                    <option value="93">Technical Presentation</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Client</label>
                                <select class="form-control js-example-basic-single" name="ClientId" id="ClientId" style="position: relative !important" title="Select Client">
                                    <option value="" disabled selected>Select Client</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}">{{ $client->Name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Transaction Number</label>
                                <input type="text" class="form-control" id="TransactionNumber" name="TransactionNumber" placeholder="Enter Transaction Number">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Contact</label>
                                <select class="form-control js-example-basic-single" name="ClientContactId" id="ClientContactId" style="position: relative !important" title="Select Contact">
                                    <option value="" disabled selected>Select Contact</option>
                                </select>
                            </div>
                        </div>
                        <?php $today = date('Y-m-d'); ?>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Schedule</label>
                                <input type="date" class="form-control" id="ScheduleFrom" name="ScheduleFrom" value="<?php echo $today; ?>">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Primary Responsible</label>
                                <select class="form-control js-example-basic-single" name="PrimaryResponsibleUserId" id="PrimaryResponsibleUserId" style="position: relative !important" title="Select Contact">
                                    <option value="" disabled selected>Select Primary Responsible</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" 
                                            {{ $currentUser && ($currentUser->id == $user->id || $currentUser->user_id == $user->id) ? 'selected' : '' }}>
                                            {{ $user->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Due Date</label>
                                <input type="date" class="form-control" id="ScheduleTo" name="ScheduleTo">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Secondary Responsible</label>
                                <select class="form-control js-example-basic-single" name="SecondaryResponsibleUserId" id="SecondaryResponsibleUserId" style="position: relative !important" title="Select Contact">
                                    <option value="" disabled selected>Select Secondary Responsible</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">
                                            {{ $user->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" class="form-control" id="Title" name="Title" placeholder="Enter Title">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Attachments</label>
                                <input type="file" class="form-control" name="path[]" multiple>
                                <small><b style="color:red">Note:</b> The file must be a type of: jpg, jpeg, png, pdf, doc, docx.</small>
                                <div class="col-sm-9">
                                    <ul id="fileList"></ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 edit-status" style="display: none;">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control js-example-basic-single" name="Status" id="Status" style="position: relative !important" title="Select Type">
                                    <option value="" disabled selected>Select Status</option>
                                    <option value="10">Open</option>
                                    <option value="20">Closed</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 edit-status" style="display: none;">
                            <div class="form-group">
                                <label>Date Closed</label>
                                <input type="date" class="form-control" id="DateClosed" name="DateClosed">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                            <label for="Description" class="form-label">Description</label>
                            <textarea class="form-control" id="Description" name="Description" rows="3" placeholder="Enter Description"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="action" id="action" value="Save">
                        <input type="hidden" name="hidden_id" id="hidden_id">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" name="action_button" id="action_button" class="btn btn-success" value="Save">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap4.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.bootstrap4.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>


<script>
    $(document).ready(function(){
        function fetchData() {
            var isShowOpen = $('#IsShowOpen').is(':checked') ? 'true' : 'false';
            var isShowClosed = $('#IsShowClosed').is(':checked') ? 'true' : 'false';

            dataTableInstance = new DataTable('#activity_table', {
                destroy: true, // Destroy and re-initialize DataTable on each call
                pageLength: 25,
                layout: {
                    topStart: {
                        buttons: [
                            'copy',
                            {
                                extend: 'excel',
                                text: 'Export to Excel',
                                filename: 'Activity', // Set the custom file name
                                title: 'Activity' // Set the custom title
                            }
                        ]
                    }
                },
                ajax: {
                    url: "{{ route('activities.index') }}",
                    data: {
                        isShowOpen: isShowOpen,
                        isShowClosed: isShowClosed
                    }
                },
                columns: [
                    {
                        data: 'ActivityNumber',
                        name: 'ActivityNumber'
                    },
                    {
                        data: 'ScheduleFrom',
                        name: 'ScheduleFrom',
                    },
                    {
                        data: 'client.Name',
                        name: 'client.Name'
                    },
                    {
                        data: 'Title',
                        name: 'Title'
                    },
                    {
                        data: 'Status',
                        name: 'Status',
                        render: function(data, type, row) {
                            return data == 10 ? 'Open' : 'Closed';
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false
                    }
                ],
                columnDefs: [
                    {
                        targets: [2, 3], // Target the Title column
                        render: function(data, type, row) {
                            return '<div style="white-space: break-spaces; width: 100%;">' + data + '</div>';
                        }
                    }
                ]
            });
        }
        
        // Initial load
        fetchData();

        // Reload table when checkboxes are changed
        $('#IsShowOpen, #IsShowClosed').change(function() {
            fetchData();
        });

        // client contact
        $('#ClientId').change(function() {
            var clientId = $(this).val();
            console.log(clientId);
            if (clientId) {
                $.ajax({
                    url: '{{ url("get-contacts") }}/' + clientId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#ClientContactId').empty();
                        $('#ClientContactId').append('<option value="" disabled selected>Select Contact</option>');
                        $.each(data, function(key, value) {
                            $('#ClientContactId').append('<option value="'+ key +'">'+ value +'</option>');
                        });
                    }
                });
            } else {
                $('#ClientContactId').empty();
                $('#ClientContactId').append('<option value="" disabled selected>Select Contact</option>');
            }
        });


        $('#add_activity').click(function(){
            $('#formActivity').modal('show');
            $('.modal-title').text("Add Activity");
        });

        $('#form_activity').on('submit', function(event){
            event.preventDefault();
            if($('#action').val() == 'Save')
            {
                $.ajax({
                    url: "{{ route('activity.store') }}",
                    method: "POST",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: "json",
                    success: function(data)
                    {
                        var html = '';
                        if(data.errors)
                        {
                            html = '<div class="alert alert-danger">';
                            for(var count = 0; count < data.errors.length; count++)
                            {
                                html += '<p>' + data.errors[count] + '</p>';
                            }
                            html += '</div>';
                        }
                        if(data.success)
                        {
                            html = '<div class="alert alert-success">' + data.success + '</div>';
                            $('#form_activity')[0].reset();
                            $('.js-example-basic-single').val('').trigger('change');
                            setTimeout(function(){
                                $('#formActivity').modal('hide');
                            }, 2000);
                            if (dataTableInstance) {
                                dataTableInstance.ajax.reload();
                            }
                            setTimeout(function(){
                                $('#form_result').empty(); 
                            }, 2000); 
                        }
                        $('#form_result').html(html);
                    }
                })
            }

            if($('#action').val() == 'Edit')
            {
                var formData = new FormData(this);
                formData.append('id', $('#hidden_id').val());
                $.ajax({
                    url: "{{ route('update_activity', ':id') }}".replace(':id', $('#hidden_id').val()),
                    method: "POST",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: "json",
                    success:function(data)
                    {
                        var html = '';
                        if(data.errors)
                        {
                            html = '<div class="alert alert-danger">';
                            for(var count = 0; count < data.errors.length; count++)
                            {
                                html += '<p>' + data.errors[count] + '</p>';
                            }
                            html += '</div>';
                        }
                        if(data.success)
                        {
                            html = '<div class="alert alert-success">' + data.success + '</div>';
                            $('#form_activity')[0].reset();
                            setTimeout(function(){
                                $('#formActivity').modal('hide');
                            }, 2000);
                            if (dataTableInstance) {
                                dataTableInstance.ajax.reload();
                            }
                            setTimeout(function(){
                                $('#form_result').empty(); 
                            }, 2000); 
                        }
                        $('#form_result').html(html);
                    }
                });
            }
        });

        $(document).on('click', '.edit', function(){
            $('.edit-status').show();
            var id = $(this).attr('id');
            $('#form_result').html('');
            $.ajax({
                url: "{{ route('edit_activity', ['id' => '_id_']) }}".replace('_id_', id),
                dataType: "json",
                success: function(response){
                    var data = response.data;
                    var primaryUser = response.primaryUser;
                    var secondaryUser = response.secondaryUser;
                    var files = response.files;

                    $('#Title').val(data.Title);
                    $('#TransactionNumber').val(data.TransactionNumber);
                    $('#ScheduleFrom').val(data.ScheduleFrom);
                    $('#ScheduleTo').val(data.ScheduleTo);
                    $('#Description').val(data.Description);
                    $('#ClientId').val(data.ClientId).trigger('change');
                    $('#ClientContactId').val(data.ClientContactId).trigger('change');
                    $('#PrimaryResponsibleUserId').val(primaryUser ? primaryUser.id : '').trigger('change');
                    $('#SecondaryResponsibleUserId').val(secondaryUser ? secondaryUser.id : '').trigger('change');

                    var fileList = '';

                    files.forEach(function(file) {
                        var fileName = file.split('/').pop(); 
                        fileList += '<li><a href="' + '{{ asset("storage") }}' + '/' + file + '" download="' + fileName + '">' + fileName + '</a></li>';
                    });

                    $('#fileList').html(fileList);

                    $('#fileList').html(fileList);

                    $('.edit-status').show();
                    $('#hidden_id').val(data.id);
                    $('.modal-title').text("Edit Activity");
                    $('#action_button').val("Update");
                    $('#action').val("Edit");
                    $('#Type').val(data.Type).trigger('change');
                    $('#Status').val(data.Status).trigger('change');
                    $('#RelatedTo').val(data.RelatedTo).trigger('change');
                    var clientId = data.ClientId;
                    $.ajax({
                        url: "{{ url('get-contacts') }}/" + clientId,
                        type: "GET",
                        dataType: "json",
                        success:function(contactData) {
                            $('#ClientContactId').empty();
                            $('#ClientContactId').append('<option value="" disabled selected>Select Contact</option>');
                            $.each(contactData, function(key, value) {
                                $('#ClientContactId').append('<option value="'+ key +'">'+ value +'</option>');
                            });
                            $('#ClientContactId').val(data.ClientContactId);
                        }
                    });

                    $('#formActivity').modal('show');
                }
            });
        });

    });
</script>
@endsection
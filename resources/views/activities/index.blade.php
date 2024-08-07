@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            Activity List
            <button type="button" class="btn btn-md btn-primary" data-toggle="modal" data-target="#addActivity">Add Activity</button>
            @include('activities.new_activities')
            </h4>
            <div class="form-group">
                <form method="GET" id="checkboxForm">
                    <label>Show : </label>
                    <label class="checkbox-inline">
                        <input name="status" class="activity_status" type="checkbox" value="10" @if($status == null || $status == 10) checked @endif> Open
                    </label>
                    <label class="checkbox-inline">
                        <input name="status" class="activity_status" type="checkbox" value="20" @if($status == 20) checked @endif> Closed
                    </label>
                </form>
            </div>
            <form method="GET" class="custom_form mb-3" enctype="multipart/form-data">
                <div class="row height d-flex justify-content-end align-items-end">
                    <div class="col-md-5">
                        <div class="search">
                            <i class="ti ti-search"></i>
                            <input type="text" class="form-control" placeholder="Search Activity" name="search" value="{{$search}}"> 
                            <button class="btn btn-sm btn-info">Search</button>
                        </div>
                    </div>
                </div>
            </form>
            @include('components.error')

            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="activity_table" width="100%">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Activity Number</th>
                            <th>Schedule (Y-M-D)</th>
                            <th>Client</th>
                            <th>Title</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($activities as $a)
                            <tr>
                                <td>
                                    <a href="{{url('view_activity/'.$a->id)}}" class="btn btn-info btn-sm" title="View Activity" target="_blank">
                                        <i class="ti-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-warning btn-sm" title="Edit Activity" data-toggle="modal" data-target="#editActivity-{{$a->id}}">
                                        <i class="ti-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm delete_activity" title="Delete Activity" data-id="{{$a->id}}">
                                        <i class="ti-trash"></i>
                                    </button>
                                </td>
                                <td>{{$a->ActivityNumber}}</td>
                                <td>{{$a->ScheduleFrom}}</td>
                                <td>{{$a->client->Name}}</td>
                                <td>{{$a->Title}}</td>
                                <td>
                                    @if($a->Status == 10)
                                        <div class="badge badge-success">Open</div>
                                    @else
                                        <div class="badge badge-danger">Close</div>
                                    @endif
                                </td>
                            </tr>

                            @include('activities.edit_activities')
                        @endforeach
                    </tbody>
                </table>
            </div>

            {!! $activities->appends(['search' => $search])->links() !!}
            @php
                    $total = $activities->total();
                    $currentPage = $activities->currentPage();
                    $perPage = $activities->perPage();
                    
                    $from = ($currentPage - 1) * $perPage + 1;
                    $to = min($currentPage * $perPage, $total);
                @endphp
            <p class="mt-3">{{"Showing {$from} to {$to} of {$total} entries"}}</p>
        </div>
    </div>
</div>


<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap4.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.bootstrap4.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>


{{-- <script>
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
</script> --}}
<script>
    $(document).ready(function() {
        $("input:checkbox").on('click', function() {
            var $box = $(this);

            if ($box.is(":checked")) {
                var group = "input:checkbox[name='" + $box.attr("name") + "']";

                $(group).prop("checked", false);
                $box.prop("checked", true);
            } else {
                $box.prop("checked", false);
            }
        });

        $('.activity_status').on('change', function() {
            $("#checkboxForm").submit();
        })
        
        $(".ClientId").on('change', function() {
            
            var client_id = $(this).val();

            $.ajax({
                type: "POST",
                url: "{{url('refresh_client_contact')}}",
                data: {
                    client_id: client_id
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res)
                {
                    $('.ClientContactId').html(res)
                }
            })
        })

        $('.delete_activity').on('click', function() {
            var id = $(this).data('id');

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Delete"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "{{url('delete_activity')}}",
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
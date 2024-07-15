@extends('layouts.header')
@section('css')
    <link rel="stylesheet" href="{{asset('css/sweetalert2.min.css')}}">
@endsection
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            Nature of Request List
            <button type="button" class="btn btn-md btn-primary" name="add_nature_request" id="add_nature_request">Add Nature of Request</button>
            </h4>
            <table class="table table-striped table-bordered table-hover" id="nature_request_table" width="100%">
                <thead>
                    <tr>
                        <th width="35%">Name</th>
                        <th width="55%">Description</th>
                        <th width="10%">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="formNatureRequest" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Project Name</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_nature_request" enctype="multipart/form-data" action="">
                    <span id="form_result"></span>
                    @csrf
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="Name" name="Name" placeholder="Enter Name">
                    </div>
                    <div class="form-group">
                        <label for="name">Description</label>
                        <input type="text" class="form-control" id="Description" name="Description" placeholder="Enter Description">
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
<!-- <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Nature of Request</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 20px">
                <h5 style="margin: 0">Are you sure you want to delete this data?</h5>
            </div>
            <div class="modal-footer" style="padding: 0.6875rem">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" name="delete_nature_request" id="delete_nature_request" class="btn btn-danger">Yes</button>
            </div>
        </div>
    </div>
</div> -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap4.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.bootstrap4.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>


<script>
    $(document).ready(function(){
        dataTableInstance = new DataTable('#nature_request_table', {
            destroy: true, // Destroy and re-initialize DataTable on each call
            serverSide: true,
            pageLength: 25,
            layout: {
                topStart: {
                    buttons: [
                        'copy',
                        {
                            extend: 'excel',
                            text: 'Export to Excel',
                            filename: 'NatureOfRequest', // Set the custom file name
                            title: 'Nature of Request' // Set the custom title
                        }
                    ]
                }
            },
            ajax: {
                url: "{{ route('nature_request.index') }}",
            },
            columns: [
                {
                    data: 'Name',
                    name: 'Name'
                },
                {
                    data: 'Description',
                    name: 'Description'

                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false
                }
            ],
            columnDefs: [
                {
                    targets: [0,1], // Target the Description column
                    render: function(data, type, row) {
                        return '<div style="white-space: break-spaces; width: 100%;">' + data + '</div>';
                    }
                }
            ]
        });

        $('#add_nature_request').click(function(){
            $('#formNatureRequest').modal('show');
            $('.modal-title').text("Add Nature of Request");
        });

        $('#form_nature_request').on('submit', function(event){
            event.preventDefault();
            if($('#action').val() == 'Save')
            {
                $.ajax({
                    url: "{{ route('nature_request.store') }}",
                    method: "POST",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: "json",
                    success: function(data) {
                        var html = '';
                        if (data.errors) {
                            var html = '<div class="alert alert-danger">';
                            $('input').css('border-color', ''); // Reset border color for all inputs

                            data.errors.forEach(function(error) {
                                html += '<p>' + error.message + '</p>';
                                $('input[name="' + error.field + '"]').css('border-color', 'red'); // Apply border color
                            });

                            html += '</div>';
                            $('#form_result').html(html);
                        } else {
                            // Using SweetAlert for success message
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: data.success,
                                    timer: 2000
                                }).then(function() {
                                    $('#formNatureRequest').modal('hide');
                                    if (dataTableInstance) {
                                        dataTableInstance.ajax.reload();
                                    }
                                    $('#form_result').empty(); 
                                });

                                $('#form_nature_request')[0].reset();
                                $('input').css('border-color', ''); // Reset border color for all inputs
                            }
                        }
                    }
                })
            }

            if($('#action').val() == 'Edit')
            {
                var formData = new FormData(this);
                formData.append('id', $('#hidden_id').val());
                $.ajax({
                    url: "{{ route('update_nature_request', ':id') }}".replace(':id', $('#hidden_id').val()),
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
                            $('#form_nature_request')[0].reset();
                            setTimeout(function(){
                                $('#formNatureRequest').modal('hide');
                            }, 2000);
                            $('#nature_request_table').DataTable().ajax.reload();
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
            var id = $(this).attr('id');
            $('#form_result').html('');
            $.ajax({
                url: "{{ route('edit_nature_request', ['id' => '_id_']) }}".replace('_id_', id),
                dataType: "json",
                success: function(html){
                    $('#Name').val(html.data.Name);
                    $('#Description').val(html.data.Description);
                    $('#hidden_id').val(html.data.id);
                    $('.modal-title').text("Edit Nature of Request");
                    $('#action_button').val("Update");
                    $('#action').val("Edit");
                    
                    $('#formNatureRequest').modal('show');
                }
            });
        });
                
        $(document).on('click', '.delete', function() {
            var nature_request_id = $(this).attr('id');

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                cancelButtonColor: "#a3a4a5",
                confirmButtonColor: "#FF4747",
                confirmButtonText: "Yes, delete it!",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Proceed with deletion via AJAX
                    $.ajax({
                        url: "{{ url('delete_nature_request') }}/" + nature_request_id,
                        method: "GET",
                        success: function(data) {
                            // Show success message with SweetAlert
                            Swal.fire({
                                title: "Deleted!",
                                text: "Nature of Request has been deleted.",
                                icon: "success",
                                timer: 2000
                            }).then(() => {
                                // Reload DataTable
                                if (dataTableInstance) {
                                    dataTableInstance.ajax.reload();
                                }
                            });
                        }
                    });
                }
            });
        });

    });
</script>
@endsection
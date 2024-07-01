@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            Price Request GAE List
            <button type="button" class="btn btn-md btn-primary" name="add_request_gae" id="add_request_gae">Add Price Request GAE</button>
            </h4>
            <table class="table table-striped table-hover" id="request_gae_table" width="100%">
                <thead>
                    <tr>
                        <th width="40%">Expense Name</th>
                        <th width="40%">Cost</th>
                        <th width="20%">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="formRequestGAE" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Price Request GAE</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_request_gae" enctype="multipart/form-data" action="">
                    <span id="form_result"></span>
                    @csrf
                    <div class="form-group">
                        <label for="name">Expense Name</label>
                        <input type="text" class="form-control" id="ExpenseName" name="ExpenseName" placeholder="Enter Expense Name">
                    </div>
                    <div class="form-group">
                        <label for="name">Cost</label>
                        <input type="text" class="form-control" id="Cost" name="Cost" placeholder="Enter Cost">
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
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Price Request GAE</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 20px">
                <h5 style="margin: 0">Are you sure you want to delete this data?</h5>
            </div>
            <div class="modal-footer" style="padding: 0.6875rem">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" name="delete_button_gae" id="delete_button_gae" class="btn btn-danger">Yes</button>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script> 

<script>
    $(document).ready(function(){
        $('#request_gae_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('request_gae.index') }}"
            },
            columns: [
                {
                    data: 'ExpenseName',
                    name: 'ExpenseName'
                },
                {
                    data: 'Cost',
                    name: 'Cost'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false
                }
            ],
            columnDefs: [
                {
                    targets: [0, 1], // Target the Description column
                    render: function(data, type, row) {
                        return '<div style="white-space: break-spaces; width: 100%;">' + data + '</div>';
                    }
                }
            ]
        });

        $('#add_request_gae').click(function(){
            $('#formRequestGAE').modal('show');
            $('.modal-title').text("Add Price Request GAE");
        });

        $('#form_request_gae').on('submit', function(event){
            event.preventDefault();
            if($('#action').val() == 'Save')
            {
                $.ajax({
                    url: "{{ route('request_gae.store') }}",
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
                            $('#form_request_gae')[0].reset();
                            setTimeout(function(){
                                $('#formRequestGAE').modal('hide');
                            }, 2000);
                            $('#request_gae_table').DataTable().ajax.reload();
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
                    url: "{{ route('update_request_gae', ':id') }}".replace(':id', $('#hidden_id').val()),
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
                            $('#form_request_gae')[0].reset();
                            setTimeout(function(){
                                $('#formRequestGAE').modal('hide');
                            }, 1000);
                            $('#request_gae_table').DataTable().ajax.reload();
                            setTimeout(function(){
                                $('#form_result').empty(); 
                            }, 1000); 
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
                url: "{{ route('edit_request_gae', ['id' => '_id_']) }}".replace('_id_', id),
                dataType: "json",
                success: function(html){
                    $('#ExpenseName').val(html.data.ExpenseName);
                    $('#Cost').val(html.data.Cost);
                    $('#hidden_id').val(html.data.id);
                    $('.modal-title').text("Edit Price Request GAE");
                    $('#action_button').val("Update");
                    $('#action').val("Edit");
                    $('#formRequestGAE').modal('show');
                }
            });
        });

        var request_gae_id;
        $(document).on('click', '.delete', function(){
            request_gae_id = $(this).attr('id');
            $('#confirmModal').modal('show');
            $('.modal-title').text("Delete Price Request GAE");
        }); 

        $('#delete_button_gae').click(function(){
            $.ajax({
                url: "{{ url('delete_request_gae') }}/" + request_gae_id, 
                method: "GET",
                beforeSend:function(){
                    $('#delete_button_gae').text('Deleting...');
                },
                success:function(data)
                {
                    setTimeout(function(){
                        $('#confirmModal').modal('hide');
                        $('#request_gae_table').DataTable().ajax.reload();
                    }, 2000);
                }
            })
        });
    });
</script>
@endsection
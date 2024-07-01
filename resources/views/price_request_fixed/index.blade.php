@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            Fixed Cost List
            <button type="button" class="btn btn-md btn-primary" name="add_fixed_cost" id="add_fixed_cost">Add Fixed Cost</button>
            </h4>
            <table class="table table-striped table-hover" id="fixed_cost" width="100%">
                <thead>
                    <tr>
                        <th width="20%">Effective Date</th>
                        <th width="20%">Created By</th>
                        <th width="15%">Direct Labor</th>
                        <th width="15%">Factory Overhead</th>
                        <th width="15%">Delivery Cost</th>
                        <th width="15%">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="formFixedCost" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Request Fixed Cost</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_fixed_cost" enctype="multipart/form-data" action="{{ route('region.store') }}">
                    <span id="form_result"></span>
                    @csrf
                    <div class="form-group">
                        <label>Effective Date</label>
                        <input type="date" class="form-control" id="EffectiveDate" name="EffectiveDate">
                    </div>
                    <div class="form-group">
                        <label for="name">Direct Labor</label>
                        <input type="text" class="form-control" id="DirectLabor" name="DirectLabor" placeholder="Enter Direct Labor">
                    </div>
                    <div class="form-group">
                        <label for="name">Factory Overhead</label>
                        <input type="text" class="form-control" id="FactoryOverhead" name="FactoryOverhead" placeholder="Enter Factory Overhead">
                    </div>
                    <div class="form-group">
                        <label for="name">Delivery Cost</label>
                        <input type="text" class="form-control" id="DeliveryCost" name="DeliveryCost" placeholder="Enter Delivery Cost">
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
                <h5 class="modal-title" id="deleteModalLabel">Delete Region</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 20px">
                <h5 style="margin: 0">Are you sure you want to delete this data?</h5>
            </div>
            <div class="modal-footer" style="padding: 0.6875rem">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" name="delete_fixed_cost" id="delete_fixed_cost" class="btn btn-danger">Yes</button>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script> 

<script>
    $(document).ready(function(){
        $('#fixed_cost').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('fixed_cost.index') }}"
            },
            columns: [
                {
                    data: 'EffectiveDate',
                    name: 'EffectiveDate',
                },
                {
                    data: 'user.full_name',
                    name: 'user.full_name'
                },
                {
                    data: 'DirectLabor',
                    name: 'DirectLabor'
                },
                {
                    data: 'FactoryOverhead',
                    name: 'FactoryOverhead'
                },
                {
                    data: 'DeliveryCost',
                    name: 'DeliveryCost'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false
                }
            ],
            columnDefs: [
                {
                    targets: 1, // Target the first column (index 1)
                    render: function(data, type, row) {
                        return '<div style="white-space: break-spaces; width: 100%;">' + data + '</div>';
                    }
                },
                {
                    targets: 2, // Target the second column (index 2)
                    render: function(data, type, row) {
                        return '<div style="white-space: break-spaces; width: 100%;">' + data + '</div>';
                    }
                }
            ]
        });

        $('#add_fixed_cost').click(function(){
            $('#formFixedCost').modal('show');
            $('.modal-title').text("Add New Request Fixed Cost");
        });

        $('#form_fixed_cost').on('submit', function(event){
            event.preventDefault();
            if($('#action').val() == 'Save')
            {
                $.ajax({
                    url: "{{ route('fixed_cost.store') }}",
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
                            $('#form_fixed_cost')[0].reset();
                            setTimeout(function(){
                                $('#formFixedCost').modal('hide');
                            }, 2000);
                            $('#fixed_cost').DataTable().ajax.reload();
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
                    url: "{{ route('update_fixed_cost', ':id') }}".replace(':id', $('#hidden_id').val()),
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
                            $('#form_fixed_cost')[0].reset();
                            setTimeout(function(){
                                $('#formFixedCost').modal('hide');
                            }, 2000);
                            $('#fixed_cost').DataTable().ajax.reload();
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
                url: "{{ route('edit_fixed_cost', ['id' => '_id_']) }}".replace('_id_', id),
                dataType: "json",
                success: function(html){
                    $('#EffectiveDate').val(html.data.EffectiveDate);
                    $('#DirectLabor').val(html.data.DirectLabor);
                    $('#FactoryOverhead').val(html.data.FactoryOverhead);
                    $('#DeliveryCost').val(html.data.DeliveryCost);
                    $('#hidden_id').val(html.data.id);
                    $('.modal-title').text("Edit Request Fixed Cost");
                    $('#action_button').val("Update");
                    $('#action').val("Edit");
                                   
                    $('#formFixedCost').modal('show');
                }
            });
        });

        var fixed_cost;
        $(document).on('click', '.delete', function(){
            fixed_cost = $(this).attr('id');
            $('#confirmModal').modal('show');
            $('.modal-title').text("Delete Request Fixed Cost");
        });    

        $('#delete_fixed_cost').click(function(){
            $.ajax({
                url: "{{ url('delete_fixed_cost') }}/" + fixed_cost, 
                method: "GET",
                beforeSend:function(){
                    $('#delete_fixed_cost').text('Deleting...');
                },
                success:function(data)
                {
                    setTimeout(function(){
                        $('#confirmModal').modal('hide');
                        $('#fixed_cost').DataTable().ajax.reload();
                    }, 2000);
                }
            })
        });
    });
</script>
@endsection 
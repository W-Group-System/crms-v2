@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            Department List
            <button type="button" class="btn btn-md btn-primary" name="add_department" id="add_department">Add Department</button>
            </h4>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="department_table">
                    <thead>
                        <tr>
                            <th width="25%">Company</th>
                            <th width="25%">Department</th>
                            <th width="25%">Description</th>
                            <th width="25%">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="formDepartment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Department</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>               
            </div>
            <div class="modal-body">
                <form method="POST" id="form_department" enctype="multipart/form-data" action="{{ route('department.store') }}">
                    <span id="form_result"></span>
                    @csrf
                    <div class="form-group">
                        <label>Company</label>
                        <select class="form-control js-example-basic-single" name="company_id" id="company_id" style="position: relative !important" title="Select Company">
                            <option value="" disabled selected>Select Company</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">Department</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name">
                    </div>
                    <div class="form-group">
                        <label for="name">Description</label>
                        <input type="text" class="form-control" id="description" name="description" placeholder="Enter Description">
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
                <h5 class="modal-title" id="deleteModalLabel">Delete Department</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 20px">
                <h5 style="margin: 0">Are you sure you want to delete this data?</h5>
            </div>
            <div class="modal-footer" style="padding: 0.6875rem">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" name="yes_button" id="yes_button" class="btn btn-danger">Yes</button>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script> 
<script>
    $(document).ready(function(){
        $('#department_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('department.index') }}"
            },
            columns: [
                {
                    data: 'company.name',
                    name: 'company.name'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: function(row) {
                        return row.description ? row.description : 'N/A';
                    },
                    name: 'description'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false
                }
            ]
        });

        $('#add_department').click(function(){
            $('#formDepartment').modal('show');
            $('.modal-title').text("Add Department");
        });
        
        $('#form_department').on('submit', function(event){
            event.preventDefault();
            if($('#action').val() == 'Save')
            {
                $.ajax({
                    url: "{{ route('department.store') }}",
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
                            $('#form_department')[0].reset();
                            setTimeout(function(){
                                $('#formDepartment').modal('hide');
                            }, 2000);
                            $('#department_table').DataTable().ajax.reload();
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
                    url: "{{ route('update_department', ':id') }}".replace(':id', $('#hidden_id').val()),
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
                            $('#form_department')[0].reset();
                            setTimeout(function(){
                                $('#formDepartment').modal('hide');
                            }, 2000);
                            $('#department_table').DataTable().ajax.reload();
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
                url: "{{ route('edit_department', ['id' => '_id_']) }}".replace('_id_', id),
                dataType: "json",
                success: function(html){
                    $('#name').val(html.data.name);
                    $('#description').val(html.data.description);
                    $('#hidden_id').val(html.data.id);
                    $('.modal-title').text("Edit Company");
                    $('#action_button').val("Update");
                    $('#action').val("Edit");
                    
                    var companyId = html.data.company_id;
                    $('#company_id').val(companyId);
                    
                    $('#formDepartment').modal('show');
                }
            });
        });

        var department_id;
        $(document).on('click', '.delete', function(){
            department_id = $(this).attr('id');
            console.log(department_id);
            $('#confirmModal').modal('show');
            $('.modal-title').text("Delete Department");
        });    

        $('#yes_button').click(function(){
            $.ajax({
                url: "{{ url('delete_department') }}/" + department_id, 
                method: "GET",
                beforeSend:function(){
                    $('#yes_button').text('Deleting...');
                },
                success:function(data)
                {
                    setTimeout(function(){
                        $('#confirmModal').modal('hide');
                        $('#department_table').DataTable().ajax.reload();
                    }, 2000);
                }
            })
        });
    });
</script>
@endsection
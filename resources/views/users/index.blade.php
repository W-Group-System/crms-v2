@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            Users List
            <button type="button" class="btn btn-md btn-primary" name="add_user" id="add_user">Add User</button>
            </h4>
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="user_table" width="100%">
                    <thead>
                        <tr>
                            <th width="15%">Username</th>
                            <th width="15%">Name</th>
                            <th width="15%">Role</th>
                            <th width="15%">Company</th>
                            <th width="15%">Department</th>
                            <th width="15%">Status</th>
                            <th width="10%">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="formUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_user" enctype="multipart/form-data" action="">
                    <span id="form_result"></span>
                    @csrf
                    <div class="form-group">
                        <label for="name">Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Enter Username">
                    </div>
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Enter Full Name">
                    </div>
                    <div class="form-group" id="formPasword">
                        <label for="name">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password">
                    </div>
                    <div class="form-group">
                        <label for="name">Email Address</label>
                        <input type="text" class="form-control" id="email" name="email" placeholder="Enter Email Address">
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select class="form-control js-example-basic-single" name="role_id" id="role_id" style="position: relative !important" title="Select Role">
                            <option value="" disabled selected>Select Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
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
                        <label>Department</label>
                        <select class="form-control js-example-basic-single" name="department_id" id="department_id" style="position: relative !important" title="Select Company">
                            <option value="" disabled selected>Select Department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" id="formStatus" >
                        <label for="name">Status</label>
                        <select class="form-control js-example-basic-single" name="is_active" id="is_active" style="position: relative !important" title="Select Type">
                            <option value="" disabled selected>Select Status</option>
                            <option value="0">Inactive</option>
                            <option value="1">Active</option>
                        </select>
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
                <h5 class="modal-title" id="deleteModalLabel">Delete Role</h5>
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
        $('#user_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('user.index') }}"
            },
            columns: [
                {
                    data: 'username',
                    name: 'username'
                },
                {
                    data: 'full_name',
                    name: 'full_name'
                    // render: function(data, type, row) {
                    //     return row.FirstName + ' ' + (row.MiddleName ? row.MiddleName + ' ' : '') + row.LastName;
                    // }
                },
                {
                    data: 'role.name',
                    name: 'role.name'
                    // name: 'RoleName',
                    // render: function(data, type, row) {
                    //     var roleName = '';
                    //     if (row.role) {
                    //         roleName = row.role.Name; // Assuming each user has only one role
                    //     }
                    //     return roleName;
                    // }
                },
                {
                    data: 'company.name',
                    name: 'company.name'
                },
                {
                    data: 'department.name',
                    name: 'department.name'
                },
                {
                    data: 'is_active',
                    name: 'is_active',
                    render: function(data, type, row) {
                        return data == 1 ? 'Active' : 'Inactive';
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
                    targets: 0, 
                    render: function(data, type, row) {
                        return '<div style="white-space: break-spaces; width: 100%;">' + data + '</div>';
                    }
                },
                {
                    targets: 1, 
                    render: function(data, type, row) {
                        return '<div style="white-space: break-spaces; width: 100%;">' + data + '</div>';
                    }
                },
                {
                    targets: 2,
                    render: function(data, type, row) {
                        return '<div style="white-space: break-spaces; width: 100%;">' + data + '</div>';
                    }
                },
                {
                    targets: 3,
                    render: function(data, type, row) {
                        return '<div style="white-space: break-spaces; width: 100%;">' + data + '</div>';
                    }
                },
                {
                    targets: 4,
                    render: function(data, type, row) {
                        return '<div style="white-space: break-spaces; width: 100%;">' + data + '</div>';
                    }
                },
                {
                    targets: 5,
                    render: function(data, type, row) {
                        return '<div style="white-space: break-spaces; width: 100%;">' + data + '</div>';
                    }
                },
                {
                    targets: 6,
                    render: function(data, type, row) {
                        return '<div style="white-space: break-spaces; width: 100%;">' + data + '</div>';
                    }
                }
            ]
        });

        $('#add_user').click(function(){
            $('#formUser').modal('show');
            $('.modal-title').text("Add New User");
            $('#formStatus').hide();
        });

        $('#form_user').on('submit', function(event){
            event.preventDefault();
            if($('#action').val() == 'Save')
            {
                $.ajax({
                    url: "{{ route('user.store') }}",
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
                            $('#form_user')[0].reset();
                            $('#role_id').val('').trigger('change'); // Reset select inputs
                            $('#company_id').val('').trigger('change'); // Reset select inputs
                            $('#department_id').val('').trigger('change'); // Reset select inputs
                            setTimeout(function(){
                                $('#formUser').modal('hide');
                            }, 2000);
                            $('#user_table').DataTable().ajax.reload();
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
                    url: "{{ route('update_user', ':id') }}".replace(':id', $('#hidden_id').val()),
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
                            $('#form_user')[0].reset();
                            setTimeout(function(){
                                $('#formUser').modal('hide');
                            }, 2000);
                            $('#user_table').DataTable().ajax.reload();
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
            console.log(id);
            $('#form_result').html('');
            $.ajax({
                url: "{{ route('edit_user', ['id' => '_id_']) }}".replace('_id_', id),
                dataType: "json",
                success: function(html){
                    $('#username').val(html.data.username);
                    $('#full_name').val(html.data.full_name);
                    $('#formPasword').hide();
                    $('#formStatus').show();
                    $('#is_active').val(html.data.is_active).trigger('change');
                    $('#email').val(html.data.email);
                    $('#role_id').val(html.data.role_id).trigger('change');
                    $('#company_id').val(html.data.company_id).trigger('change'); 
                    $('#department_id').val(html.data.department_id).trigger('change'); 
                    $('#hidden_id').val(html.data.id);
                    $('.modal-title').text("Edit User");
                    $('#action_button').val("Update");
                    $('#action').val("Edit");
                    $('#formUser').modal('show');
                }
            });
        });

        
    });
</script>
@endsection
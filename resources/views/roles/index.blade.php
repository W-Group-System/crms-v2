@extends('layouts.header')
@section('css')
    <link rel="stylesheet" href="{{asset('css/sweetalert2.min.css')}}">
@endsection
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            Role List
            <button type="button" class="btn btn-md btn-primary" name="add_role" id="add_role" data-toggle="modal" data-target="#formRole">Add Role</button>
            </h4>
            <form method="GET" class="custom_form mb-3" enctype="multipart/form-data">
                <div class="row height d-flex justify-content-end align-items-end">
                    <div class="col-md-5">
                        <div class="search">
                            <i class="ti ti-search"></i>
                            <input type="text" class="form-control" placeholder="Search Role" name="search" value="{{$search}}"> 
                            <button class="btn btn-sm btn-info">Search</button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="role_table" width="100%">
                    <thead>
                        <tr>
                            <th width="10%">Action</th>
                            <th width="35%">Department</th>
                            <th width="35%">Name</th>
                            <th width="50%">Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($roles->count() > 0)
                            @foreach($roles as $role)
                            <tr>
                                <td>
                                    <a href="{{url('module_access/'.$role->id)}}" class="btn btn-sm btn-info" title="Module Access" target="_blank">
                                        <i class="ti-eye"></i>
                                    </a>
                                    <button type="button" name="edit" class="edit btn btn-sm btn-warning" data-toggle="modal" data-target="#editRole-{{$role->id}}">
                                        <i class="ti ti-pencil"></i>
                                    </button>
                                    <button type="button" name="delete" class="delete btn btn-sm btn-danger" data-id="{{$role->id}}"><i class="ti ti-trash"></i></button>
                                </td>
                                <td>{{$role->department->name}}</td>
                                <td>{{$role->name}}</td>
                                <td>{{$role->description}}</td>
                            </tr>

                            @include('roles.edit_roles')
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" class="text-center">No matching records found</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            {!! $roles->appends(['search' => $search])->links() !!}
        </div>
    </div>
</div>
<div class="modal fade" id="formRole" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Role</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_role" enctype="multipart/form-data" action="{{url('new_role')}}">
                    @csrf
                    <div class="form-group">
                        <label for="department">Department</label>
                        <select name="department" class="js-example-basic-single form-control">
                            <option value="">-Department-</option>
                            @foreach ($department as $dpt)
                                <option value="{{$dpt->id}}">{{$dpt->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Description</label>
                        <input type="text" class="form-control" id="description" name="description" placeholder="Enter Description" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-success" value="Save">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $(".delete").on('click', function() {
            var id = $(this).data('id');

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    $.ajax({
                        type: "POST",
                        url: "{{url('delete_role')}}",
                        data: {
                            id: id
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(res) {
                            if (result.isConfirmed) {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: res.message,
                                    icon: "success"
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        }
                    })
            });
        })
    })
</script>
@endsection
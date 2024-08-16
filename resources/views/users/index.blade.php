@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between">
            Users List
            <button type="button" class="btn btn-md btn-primary" data-toggle="modal" data-target="#formUser">Add User</button>
            </h4>
            <form method="GET" class="custom_form mb-3" enctype="multipart/form-data">
                <div class="row height d-flex justify-content-end align-items-end">
                    <div class="col-md-5">
                        <div class="search">
                            <i class="ti ti-search"></i>
                            <input type="text" class="form-control" placeholder="Search User" name="search" value="{{$search}}"> 
                            <button class="btn btn-sm btn-info">Search</button>
                        </div>
                    </div>
                </div>
            </form>
            @include('components.error')
            <a href="{{url('export_user')}}" class="btn btn-success mb-3">Export</a>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="user_table" width="100%">
                    <thead>
                        <tr>
                            <th width="10%">Actions</th>
                            <th width="15%">Username</th>
                            <th width="15%">Name</th>
                            <th width="15%">Role</th>
                            <th width="15%">Company</th>
                            <th width="15%">Department</th>
                            <th width="15%">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>
                                <button class="btn btn-sm btn-warning" title="Edit Users" data-toggle="modal" data-target="#editUser-{{$user->id}}" title="Edit">
                                    <i class="ti-pencil"></i>
                                </button>

                                <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#changePassword-{{$user->id}}" title="Change Password">
                                    <i class="ti-key"></i>
                                </button>
                            </td>
                            <td>{{$user->username}}</td>
                            <td>{{$user->full_name}}</td>
                            <td>{{ $user->role ? $user->role->name : 'N/A' }}</td>
                            <td>{{ $user->company ? $user->company->name : 'N/A' }}</td>
                            <td>{{ $user->department ? $user->department->name : 'N/A' }}</td>
                            <td>
                                @if($user->is_active == "1")
                                    <div class="badge badge-success">Active</div>
                                @else
                                    <div class="badge badge-danger">Inactive</div>
                                @endif
                            </td>
                        </tr>

                        @include('users.edit_user')
                        @include('users.change_password')

                        @endforeach
                    </tbody>
                </table>
            </div>
            {!! $users->appends(['search' => $search])->links() !!}
            @php
                $total = $users->total();
                $currentPage = $users->currentPage();
                $perPage = $users->perPage();
                
                $from = ($currentPage - 1) * $perPage + 1;
                $to = min($currentPage * $perPage, $total);
            @endphp

            <p  class="mt-3">{{"Showing {$from} to {$to} of {$total} entries"}}</p>
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
                <form method="POST" id="form_user" action="{{url('new_user')}}">
                    @csrf
                    <div class="form-group">
                        <label for="name">Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Enter Username" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Enter Full Name" required>
                    </div>
                    <div class="form-group" id="formPasword">
                        <label for="name">Password</label>
                        <input type="password" class="form-control" name="password" placeholder="Enter Password" required>
                    </div>
                    <div class="form-group" id="formPasword">
                        <label for="name">Confirm Password</label>
                        <input type="password" class="form-control" name="password_confirmation" placeholder="Enter Password" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Email Address</label>
                        <input type="text" class="form-control" id="email" name="email" placeholder="Enter Email Address" required>
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select class="form-control js-example-basic-single" name="role_id" style="position: relative !important" title="Select Role" required>
                            <option value="" disabled selected>Select Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->department->department_code .' - '. $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Company</label>
                        <select class="form-control js-example-basic-single" name="company_id" style="position: relative !important" title="Select Company" required>
                            <option value="" disabled selected>Select Company</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Department</label>
                        <select class="form-control js-example-basic-single" name="department_id" style="position: relative !important" title="Select Company" required>
                            <option value="" disabled selected>Select Department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- <div class="form-group" id="formStatus" >
                        <label for="name">Status</label>
                        <select class="form-control js-example-basic-single" name="is_active" id="is_active" style="position: relative !important" title="Select Type">
                            <option value="" disabled selected>Select Status</option>
                            <option value="0">Inactive</option>
                            <option value="1">Active</option>
                        </select>
                    </div> --}}
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
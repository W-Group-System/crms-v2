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
            <button type="button" class="btn btn-md btn-primary" id="add_role" data-toggle="modal" data-target="#formRole">Add Role</button>
            </h4>
            <div class="row">
                <div class="col-lg-6">
                    <span>Showing</span>
                    <form action="" method="get" class="d-inline-block">
                        <select name="entries" class="form-control">
                            <option value="10"  @if($entries == 10) selected @endif>10</option>
                            <option value="25"  @if($entries == 25) selected @endif>25</option>
                            <option value="50"  @if($entries == 50) selected @endif>50</option>
                            <option value="100" @if($entries == 100) selected @endif>100</option>
                        </select>
                    </form>
                    <span>Entries</span>
                </div>
                <div class="col-lg-6">
                    <form method="GET" class="custom_form mb-3" enctype="multipart/form-data">
                        <div class="row height d-flex justify-content-end align-items-end">
                            <div class="col-md-8">
                                <div class="search">
                                    <i class="ti ti-search"></i>
                                    <input type="text" class="form-control" placeholder="Search Role" name="search" value="{{$search}}"> 
                                    <button class="btn btn-sm btn-info">Search</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="role_table" width="100%">
                    <thead>
                        <tr>
                            <th width="10%">Action</th>
                            <th width="35%">Department</th>
                            <th width="35%">Name</th>
                            <th width="50%">Description</th>
                            <th>Status</th>
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
                                    <button type="button" name="edit" class="edit btn btn-sm btn-warning editBtn" data-toggle="modal" data-target="#editRole-{{$role->id}}" data-id="{{$role->id}}">
                                        <i class="ti ti-pencil"></i>
                                    </button>

                                    @if($role->status == "Active")
                                    <form method="POST" action="{{url('deactivate/'.$role->id)}}" class="d-inline-block">
                                        @csrf 

                                        <button type="button" class="deactivate btn btn-sm btn-danger" title="Deactivate"><i class="mdi mdi-cancel"></i></button>
                                    </form>
                                    @elseif($role->status == "Inactive")
                                    <form method="POST" action="{{url('activate/'.$role->id)}}" class="d-inline-block">
                                        @csrf

                                        <button type="button" class="activate btn btn-sm btn-info" title="Activate"><i class="ti ti-check"></i></button>
                                    </form>
                                    @endif
                                </td>
                                <td>{{optional($role->department)->department_code.' - '.optional($role->department)->name}}</td>
                                <td>{{$role->name}}</td>
                                <td>{{$role->description}}</td>
                                <td>
                                    @if($role->status == "Active")
                                        <div class="badge badge-success">{{$role->status}}</div>
                                    @elseif($role->status == "Inactive")
                                        <div class="badge badge-danger">{{$role->status}}</div>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center">No matching records found</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            {!! $roles->appends(['search' => $search])->links() !!}

            @php
                $total = $roles->total();
                $currentPage = $roles->currentPage();
                $perPage = $roles->perPage();
                
                $from = ($currentPage - 1) * $perPage + 1;
                $to = min($currentPage * $perPage, $total);
            @endphp
            <p class="mt-3">{{"Showing {$from} to {$to} of {$total} entries"}}</p>
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
                        <select name="department" class="js-example-basic-single form-control departmentSelectOption" required>
                            <option value="">-Department-</option>
                            @foreach ($department as $dpt)
                                <option value="{{$dpt->id}}">{{$dpt->department_code.' - '.$dpt->name}}</option>
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
                    <div class="type-container">
                        {{-- @foreach ($collection as $item)
                            
                        @endforeach --}}
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

@foreach ($roles as $role)
@include('roles.edit_roles')
@endforeach

<script>
    $(document).ready(function() {
        $(".deactivate").on('click', function() {
            var form = $(this).closest('form');

            Swal.fire({
                title: "Are you sure?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, deactivate it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        })

        $(".activate").on('click', function() {
            var form = $(this).closest('form');

            Swal.fire({
                title: "Are you sure?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, activate it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        })

        $('#formRole').on('hidden.bs.modal', function() {
            $("[name='department']").val(null).trigger('change');
            $("[name='name']").val(null);
            $("[name='description']").val(null);
        })

        $('#add_role').on('click', function() {
            $("[name='department']").val(null).trigger('change');
            $("[name='name']").val(null);
            $("[name='description']").val(null);
        })

        $('.editBtn').on('click', function() {
            var id = $(this).data('id')

            $.ajax({
                type: "GET",
                url: "{{url('edit_role')}}/" + id,
                success: function(res)
                {
                    $("[name='department']").val(res.department).trigger('change');
                    $("[name='name']").val(res.name);
                    $("[name='description']").val(res.description);
                }
            })
        })

        $("[name='entries']").on('change', function() {
            var form = $(this).closest('form');

            form.submit();
        })

        $(".departmentSelectOption").on('change', function() {
            
            if($(this).val() == 38 || $(this).val() == 5)
            {
                if ($('.form-group:contains("Type")').length == 0)
                {
                    var newRow = `
                        <div class="form-group">
                            <label for="name">Type</label>
                            <select name="type" class="form-control">
                                <option disabled selected value>Select Type</option>
                                <option value="LS">Local Sales</option>
                                <option value="IS">International Sales</option>
                            </select>
                        </div>
                    `

                    $('.type-container').append(newRow)
                    $("[name='type']").select2()
                }
            }
            else 
            {
                $('.type-container').children().remove()
            }
        })
    })
</script>
@endsection
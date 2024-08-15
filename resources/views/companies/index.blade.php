@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            Company List
            <button type="button" class="btn btn-md btn-primary" data-toggle="modal" data-target="#formCompany">Add Company</button>
            </h4>
            <div class="row">
                <div class="col-lg-6">
                    <span>Showing</span>
                    <form action="" method="get" class="d-inline-block">
                        <select name="entries" class="form-control ">
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
                                    <input type="text" class="form-control" placeholder="Search Company" name="search" value="{{$search}}"> 
                                    <button class="btn btn-sm btn-info">Search</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @include('components.error')
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="company_table" width="100%">
                    <thead>
                        <tr>
                            <th width="10%">Action</th>
                            <th>Code</th>
                            <th width="30%">Name</th>
                            <th width="30%">Description</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($company as $comp)
                            <tr>
                                <td>
                                    <button type="button" class="btn btn-sm btn-warning editBtn" title="Edit" data-toggle="modal" data-target="#editCompany-{{$comp->id}}" data-id="{{$comp->id}}">
                                        <i class="ti-pencil"></i>
                                    </button>

                                    @if($comp->status == "Active")
                                    <form method="POST" action="{{url('deactivate_company/'.$comp->id)}}" class="d-inline-block">
                                        @csrf
                                        <button type="button" class="btn btn-sm btn-danger deactivate" title="Deactivate">
                                            <i class="mdi mdi-cancel"></i>
                                        </button>
                                    </form>
                                    @elseif($comp->status == "Inactive")
                                    <form method="POST" action="{{url('activate_company/'.$comp->id)}}" class="d-inline-block">
                                        @csrf
                                        <button type="button" class="btn btn-sm btn-info activate" title="Activate">
                                            <i class="ti-check"></i>
                                        </button>
                                    </form>
                                    @endif
                                </td>
                                <td>{{$comp->code}}</td>
                                <td>{{$comp->name}}</td>
                                <td>
                                    @if($comp->description == null)
                                    <p>No Description</p>
                                    @else
                                    {{$comp->description}}
                                    @endif
                                </td>
                                <td>
                                    @if($comp->status == "Active")
                                    <div class="badge badge-success">{{$comp->status}}</div>
                                    @elseif($comp->status == "Inactive")
                                    <div class="badge badge-danger">{{$comp->status}}</div>
                                    @endif
                                </td>
                            </tr>

                            @include('companies.edit_company')
                        @endforeach
                    </tbody>
                </table>
                {!! $company->appends(['search' => $search, 'entries' => $entries])->links() !!}
                @php
                    $total = $company->total();
                    $currentPage = $company->currentPage();
                    $perPage = $company->perPage();
                    
                    $from = ($currentPage - 1) * $perPage + 1;
                    $to = min($currentPage * $perPage, $total);
                @endphp

                <p  class="mt-3">{{"Showing {$from} to {$to} of {$total} entries"}}</p>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="formCompany" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Company</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_company" action="{{url('add_company')}}">
                    <div class="alert"></div>
                    @csrf
                    <div class="form-group">
                        <label for="name">Code</label>
                        <input type="text" class="form-control" id="code" name="code" placeholder="Enter company code" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter company name" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Description</label>
                        <input type="text" class="form-control" id="description" name="description" placeholder="Enter description" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#formCompany').on('hidden.bs.modal', function() {
            $("[name='code']").val(null);
            $("[name='name']").val(null);
            $("[name='description']").val(null);
        })

        $('.editBtn').on('click', function() {
            var id = $(this).data('id');

            $.ajax({
                type: "get",
                url: "{{url('edit_company')}}/" + id,
                success: function(res)
                {
                    $("[name='code']").val(res.code);
                    $("[name='name']").val(res.name);
                    $("[name='description']").val(res.description);
                }
            })
        })

        $('.deactivate').on('click', function() {
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
                    form.submit()
                }
            });
        })

        $('.activate').on('click', function() {
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
                    form.submit()
                }
            });
        })
        console.log('asdad');
        
        $("#form_company").on('submit', function(e) {
            e.preventDefault();

            var formData = $(this).serializeArray()
            var url = $(this).attr('action');

            
            $.ajax({
                type: "POST",
                url: url,
                data: formData,
                success: function(res)
                {
                    if (res.status == 0)
                    {
                        $('.alert').html("")
                        $.each(res.error, function(key, msg) {
                            $('.alert').addClass('alert-danger').append(msg);
                        })
                    }
                    else
                    {
                        Swal.fire({
                            icon: "success",
                            title: res.message
                        }).then(() => {
                            location.reload()
                        })
                    }
                }
            })
        })

        $(".update_company_form").on('submit', function(e) {
            e.preventDefault();

            var formData = $(this).serializeArray()
            var url = $(this).attr('action');
            
            $.ajax({
                type: "POST",
                url: url,
                data: formData,
                success: function(res)
                {
                    if (res.status == 0)
                    {
                        $('.alert').html("")
                        $.each(res.error, function(key, msg) {
                            $('.alert').addClass('alert-danger').append(msg);
                        })
                    }
                    else
                    {
                        Swal.fire({
                            icon: "success",
                            title: res.message
                        }).then(() => {
                            location.reload()
                        })
                    }
                }
            })
        })

        $('[name="entries"]').on('change', function() {
            var form = $(this).closest('form')

            form.submit()
        })
    })
</script>

@endsection

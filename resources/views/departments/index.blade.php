@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            Department List
            <button type="button" class="btn btn-md btn-primary" data-toggle="modal" data-target="#formDepartment">Add Department</button>
            </h4>
            <div class="row">
                <div class="col-lg-6">
                    <span>Show</span>
                    <form method="GET" class="d-inline-block">
                        <select name="number_of_entries" class="form-control">
                            <option value="10" @if($entries == 10) selected @endif>10</option>
                            <option value="25" @if($entries == 25) selected @endif>25</option>
                            <option value="50" @if($entries == 50) selected @endif>50</option>
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
                                    <input type="text" class="form-control" placeholder="Search Department" name="search" value="{{$search}}"> 
                                    <button class="btn btn-sm btn-info">Search</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="department_table">
                    <thead>
                        <tr>
                            <th width="10%">Action</th>
                            <th width="25%">Company</th>
                            <th width="25%">Code</th>
                            <th width="25%">Name</th>
                            <th width="25%">Description</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($departments) > 0)
                            @foreach ($departments as $dept)
                                <tr>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-warning editBtn" data-toggle="modal" data-target="#editDepartment-{{$dept->id}}" data-id="{{$dept->id}}">
                                            <i class="ti-pencil"></i>
                                        </button>
                                        @if($dept->status == "Active")
                                        <form method="POST" action="{{url('deactivate_department/'.$dept->id)}}" class="d-inline-block">
                                            @csrf

                                            <button type="button" class="btn btn-sm btn-danger deactivateBtn" title="Deactivate">
                                                <i class="mdi mdi-cancel"></i>
                                            </button>
                                        </form>
                                        @elseif($dept->status == "Inactive")
                                        <form method="POST" action="{{url('activate_department/'.$dept->id)}}" class="d-inline-block">
                                            @csrf

                                            <button type="button" class="btn btn-sm btn-info activateBtn" title="Deactivate" class="d-inline-block">
                                                <i class="ti ti-check"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                    <td>
                                        @if($dept->company)
                                        {{$dept->company->name}}
                                        @endif
                                    </td>
                                    <td>
                                        {{$dept->department_code}}
                                    </td>
                                    <td>
                                        {{$dept->name}}
                                    </td>
                                    <td>
                                        {{$dept->description}}
                                    </td>
                                    <td>
                                        @if($dept->status == "Active")
                                        <div class="badge badge-success">{{$dept->status}}</div>
                                        @elseif($dept->status == "Inactive")
                                        <div class="badge badge-danger">{{$dept->status}}</div>
                                        @endif
                                    </td>
                                </tr>

                                @include('departments.edit_departments')
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center">No data available</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                {!! $departments->appends(['search' => $search, 'entries' => $entries])->links() !!}
                @php
                    $total = $departments->total();
                    $currentPage = $departments->currentPage();
                    $perPage = $departments->perPage();
                    
                    $from = ($currentPage - 1) * $perPage + 1;
                    $to = min($currentPage * $perPage, $total);
                @endphp

                <p  class="mt-3">{{"Showing {$from} to {$to} of {$total} entries"}}</p>
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
                <form method="POST" id="form_department" enctype="multipart/form-data" action="{{ url('new_department') }}">
                    <div class="alert"></div>
                    @csrf
                    <div class="form-group">
                        <label>Company</label>
                        <select class="form-control js-example-basic-single" name="company_id" style="position: relative !important" title="Select Company" required>
                            <option value="" disabled selected>Select Company</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->code.' - '.$company->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">Code</label>
                        <input type="text" class="form-control" id="code" name="code" placeholder="Enter Code" required>
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
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
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
</div> --}}

{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
</script> --}}

<script>
    $(document).ready(function() {
        $('#form_department').on('submit', function(e) {
            e.preventDefault();

            var formData = $(this).serializeArray()

            $.ajax({
                type: "post",
                url: "{{url('new_department')}}",
                data: formData,
                success: function(res)
                {
                    if (res.status == 0)
                    {
                        $('.alert').html('');
                        $.each(res.errors, function(key,error) {
                            $('.alert').addClass('alert-danger').append(error)
                        })
                    }
                    else
                    {
                        Swal.fire({
                            icon: "success",
                            title: res.message
                        }).then(() => {
                            location.reload();
                        })
                    }
                }
            })
        })

        $('.update_form_department').on('submit', function(e) {
            e.preventDefault();

            var formData = $(this).serializeArray()
            var url = $(this).attr('action')

            $.ajax({
                type: "post",
                url: url,
                data: formData,
                success: function(res)
                {
                    if (res.status == 0)
                    {
                        $('.alert').html('');
                        $.each(res.errors, function(key,error) {
                            $('.alert').addClass('alert-danger').append(error)
                        })
                    }
                    else
                    {
                        Swal.fire({
                            icon: "success",
                            title: res.message
                        }).then(() => {
                            location.reload();
                        })
                    }
                }
            })
        })

        $('#formDepartment').on('hidden.bs.modal', function() {
            $("[name='company_id']").val(null).trigger('change');
            $("[name='code']").val(null);
            $("[name='name']").val(null);
            $("[name='description']").val(null);
        })

        $('.editBtn').on('click', function() {
            var id = $(this).data('id')

            $.ajax({
                type: "get",
                url: "{{url('edit_department')}}/" + id,
                success: function(res)
                {
                    $("[name='company_id']").val(res.data.company_id).trigger('change');
                    $("[name='code']").val(res.data.department_code);
                    $("[name='name']").val(res.data.name);
                    $("[name='description']").val(res.data.description);
                }
            })
        })

        $('.deactivateBtn').on('click', function() {
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

        $('.activateBtn').on('click', function() {
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

        $("[name='number_of_entries']").on('change', function() {
            var form = $(this).closest('form')

            form.submit()
        })
    })
</script>
@endsection
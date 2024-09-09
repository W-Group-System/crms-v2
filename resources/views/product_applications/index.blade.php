@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            Product Application List
            <button type="button" class="btn btn-md btn-primary" data-toggle="modal" id="addBtn" data-target="#formProductApplication">New</button>
            </h4>

            <div class="mb-3">
                <button class="btn btn-md btn-info" id="copy_issue_btn">Copy</button>
                <a href="{{url('export_product_application')}}" class="btn btn-md btn-success">Excel</a>
            </div>

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
                                    <input type="text" class="form-control" placeholder="Search Product Application" name="search" value="{{$search}}"> 
                                    <button class="btn btn-sm btn-info">Search</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover table-bordered" id="product_application_table" width="100%">
                    <thead>
                        <tr>
                            <th width="15%">Action</th>
                            <th width="35%">Application</th>
                            <th width="50%">Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($productApplications as $pa)
                            <tr>
                                <td>
                                    <button class="btn btn-warning btn-sm editBtn" data-toggle="modal" data-target="#productApplication-{{$pa->id}}" data-id="{{$pa->id}}" title="Edit">
                                        <i class="ti-pencil"></i>
                                    </button>

                                    <button class="btn btn-danger btn-sm deleteProductApplication" title="Delete" data-id="{{$pa->id}}">
                                        <i class="ti-trash"></i>
                                    </button>
                                </td>
                                <td>{{$pa->Name}}</td>
                                <td>{{$pa->Description}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {!! $productApplications->appends(['search' => $search, 'entries' => $entries])->links() !!}
                @php
                    $total = $productApplications->total();
                    $currentPage = $productApplications->currentPage();
                    $perPage = $productApplications->perPage();
                    
                    $from = ($currentPage - 1) * $perPage + 1;
                    $to = min($currentPage * $perPage, $total);
                @endphp

                <p  class="mt-3">{{"Showing {$from} to {$to} of {$total} entries"}}</p>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="formProductApplication" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Product Application</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_product_application" enctype="multipart/form-data" action="{{ url('new_product_applications') }}" onsubmit="show()">
                    @csrf
                    <div class="form-group">
                        <label for="name">Application</label>
                        <input type="text" class="form-control" id="Name" name="Name" placeholder="Enter Application Name" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Description</label>
                        <input type="text" class="form-control" id="Description" name="Description" placeholder="Enter Description" required>
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

@foreach ($productApplications as $pa)
    @include('product_applications.edit_product_application')
@endforeach

{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>  --}}

{{-- <script>
    $(document).ready(function(){
        $('#product_application_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('product_applications.index') }}"
            },
            columns: [
                {
                    data: 'Name',
                    name: 'Name'
                },
                {
                    data: 'Description',
                    name: 'Description',
                    render: function(data, type, row) {
                        return '<div style="white-space: break-spaces; width: 100%;">' + (data ? data : 'No Description Available') + '</div>';
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
                    targets: 0, // Target the Title column
                    render: function(data, type, row) {
                        return '<div style="white-space: break-spaces; width: 100%;">' + data + '</div>';
                    }
                }
            ]
        });

        $('#add_product_application').click(function(){
            $('#formProductApplication').modal('show');
            $('.modal-title').text("Add Product Application");
        });

        $('#form_product_application').on('submit', function(event){
            event.preventDefault();
            if($('#action').val() == 'Save')
            {
                $.ajax({
                    url: "{{ route('product_applications.store') }}",
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
                            $('#form_product_application')[0].reset();
                            setTimeout(function(){
                                $('#formProductApplication').modal('hide');
                            }, 2000);
                            $('#product_application_table').DataTable().ajax.reload();
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
                    url: "{{ route('update_product_applications', ':id') }}".replace(':id', $('#hidden_id').val()),
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
                            $('#form_product_application')[0].reset();
                            setTimeout(function(){
                                $('#formProductApplication').modal('hide');
                            }, 2000);
                            $('#product_application_table').DataTable().ajax.reload();
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
                url: "{{ route('edit_product_applications', ['id' => '_id_']) }}".replace('_id_', id),
                dataType: "json",
                success: function(html){
                    $('#Name').val(html.data.Name);
                    $('#Description').val(html.data.Description);
                    $('#hidden_id').val(html.data.id);
                    $('.modal-title').text("Edit Product Application");
                    $('#action_button').val("Update");
                    $('#action').val("Edit");
                                   
                    $('#formProductApplication').modal('show');
                }
            });
        });
                
        $(document).on('click', '.delete', function(){
            product_application_id = $(this).attr('Id');
            $('#confirmModal').modal('show');
            $('.modal-title').text("Delete Product Application");
        });    

        $('#delete_product_application').click(function(){
            $.ajax({
                url: "{{ url('delete_product_applications') }}/" + product_application_id, 
                method: "GET",
                beforeSend:function(){
                    $('#delete_product_application').text('Deleting...');
                },
                success:function(data)
                {
                    setTimeout(function(){
                        $('#confirmModal').modal('hide');
                        $('#product_application_table').DataTable().ajax.reload();
                    }, 2000);
                }
            })
        });
    });
</script> --}}

<script>
    $(document).ready(function() {
        $(".table").tablesorter({
            theme: "bootstrap"
        })

        $('.deleteProductApplication').on('click', function() {
            var id = $(this).data('id')

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "{{url('delete_product_applications')}}",
                        data:
                        {
                            id: id
                        },
                        headers: 
                        {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(res)
                        {
                            Swal.fire({
                                icon: "success",
                                title: res.message
                            }).then(() => {
                                location.reload()
                            })
                        }
                    })
                }
            });
        })

        $("[name='entries']").on('change', function() {
            $(this).closest('form').submit()
        })

        $("#addBtn").on('click', function() {
            $('[name="Name"]').val(null)
            $('[name="Description"]').val(null)
        })

        $(".editBtn").on('click', function() {
            var id = $(this).data('id')
            
            $.ajax({
                type: "GET",
                url: "{{url('edit_product_applications')}}/" + id,
                success: function(data)
                {
                    console.log(data);
                    $('[name="Name"]').val(data.data.Name)
                    $('[name="Description"]').val(data.data.Description)
                }
            })
        })

        $('#copy_issue_btn').click(function() {
            $.ajax({
                url: "{{ route('product_applications.index') }}",
                type: 'GET',
                data: {
                    search: "{{ request('search') }}",
                    sort: "{{ request('sort') }}",
                    direction: "{{ request('direction') }}",
                    fetch_all: true
                },
                success: function(data) {
                    var tableData = '';

                    $('#product_application_table thead tr').each(function(rowIndex, tr) {
                        $(tr).find('th').each(function(cellIndex, th) {
                            if($(th).text().trim() !== "Action")
                            {
                                tableData += $(th).text().trim() + '\t';
                            }
                        });
                        tableData += '\n';
                    });

                    $(data).each(function(index, item) {
                        if (item.Description == null)
                        {
                            item.Description = ""
                        }

                        tableData += item.Name + '\t' + item.Description + '\n';
                    });

                    var tempTextArea = $('<textarea>');
                    $('body').append(tempTextArea);
                    tempTextArea.val(tableData).select();
                    document.execCommand('copy');
                    tempTextArea.remove();
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Copied!',
                        text: 'Table data has been copied to the clipboard.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        });
    })
</script>
@endsection
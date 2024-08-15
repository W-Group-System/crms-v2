@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            Region List
            <button type="button" class="btn btn-md btn-primary" data-toggle="modal" data-target="#formRegion">Add Region</button>
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
                                    <input type="text" class="form-control" placeholder="Search Region" name="search" value="{{$search}}"> 
                                    <button class="btn btn-sm btn-info">Search</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <table class="table table-striped table-bordered table-hover" id="region_table" width="100%">
                <thead>
                    <tr>
                        <th width="10%">Action</th>
                        <th width="30%">Type</th>
                        <th width="30%">Region</th>
                        <th width="30%">Description</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($region as $reg)
                        <tr>
                            <td>
                                <button type="button" class="btn btn-sm btn-warning editBtn" data-toggle="modal" data-target="#updateFormRegion{{$reg->id}}" data-id="{{$reg->id}}">
                                    <i class="ti-pencil"></i>
                                </button>

                                <form method="POST" class="d-inline-block" action="{{url('delete_region/'.$reg->id)}}">
                                    @csrf

                                    <button type="button" class="btn btn-sm btn-danger deleteBtn">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </form>
                            </td>
                            <td>
                                @if($reg->Type == 1)
                                    Local
                                @elseif($reg->Type == 2)
                                    International
                                @endif
                            </td>
                            <td>{{$reg->Name}}</td>
                            <td>{{$reg->Description}}</td>
                        </tr>

                        @include('regions.edit_regions')
                    @endforeach
                </tbody>
            </table>

            {!! $region->appends(['search' => $search, 'entries' => $entries])->links() !!}
        </div>
    </div>
</div>
<div class="modal fade" id="formRegion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Region</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_region" action="{{ url('new_region') }}">
                    <span id="form_result"></span>
                    @csrf
                    <div class="form-group">
                        <label>Type</label>
                        <select class="form-control js-example-basic-single" name="Type" id="Type" style="position: relative !important" title="Select Type">
                            <option value="" disabled selected>Select Type</option>
                            <option value="1">Local</option>
                            <option value="2">International</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">Region</label>
                        <input type="text" class="form-control" id="Name" name="Name" placeholder="Enter Region">
                    </div>
                    <div class="form-group">
                        <label for="name">Description</label>
                        <input type="text" class="form-control" id="Description" name="Description" placeholder="Enter Description">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" >Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script> 

{{-- <script>
    $(document).ready(function(){
        $('#region_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('region.index') }}"
            },
            columns: [
                {
                    data: 'Type',
                    name: 'Type',
                    render: function(data, type, row) {
                        // Display "Local" for type 1 and "International" for type 2
                        return data == 1 ? 'Local' : 'International';
                    }
                },
                {
                    data: 'Name',
                    name: 'Name'
                },
                {
                    data: 'Description',
                    name: 'Description'
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

        $('#add_region').click(function(){
            $('#formRegion').modal('show');
            $('.modal-title').text("Add Region");
        });

        $('#form_region').on('submit', function(event){
            event.preventDefault();
            if($('#action').val() == 'Save')
            {
                $.ajax({
                    url: "{{ route('region.store') }}",
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
                            $('#form_region')[0].reset();
                            setTimeout(function(){
                                $('#formRegion').modal('hide');
                            }, 2000);
                            $('#region_table').DataTable().ajax.reload();
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
                    url: "{{ route('update_region', ':id') }}".replace(':id', $('#hidden_id').val()),
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
                            $('#form_region')[0].reset();
                            setTimeout(function(){
                                $('#formRegion').modal('hide');
                            }, 2000);
                            $('#region_table').DataTable().ajax.reload();
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
                url: "{{ route('edit_region', ['id' => '_id_']) }}".replace('_id_', id),
                dataType: "json",
                success: function(html){
                    $('#Name').val(html.data.Name);
                    $('#Description').val(html.data.Description);
                    $('#hidden_id').val(html.data.id);
                    $('.modal-title').text("Edit Region");
                    $('#action_button').val("Update");
                    $('#action').val("Edit");
                    
                    var type = html.data.Type;
                    $('#Type').val(type).trigger('change');
                    
                    $('#formRegion').modal('show');
                }
            });
        });
                
        $(document).on('click', '.delete', function(){
            region_id = $(this).attr('id');
            $('#confirmModal').modal('show');
            $('.modal-title').text("Delete Region");
        });    

        $('#yes_button').click(function(){
            $.ajax({
                url: "{{ url('delete_region') }}/" + region_id, 
                method: "GET",
                beforeSend:function(){
                    $('#yes_button').text('Deleting...');
                },
                success:function(data)
                {
                    setTimeout(function(){
                        $('#confirmModal').modal('hide');
                        $('#region_table').DataTable().ajax.reload();
                    }, 2000);
                }
            })
        });
    });
</script> --}}

<script>
    $(document).ready(function() {
        $("[name='entries']").on('change', function() {
            $(this).closest('form').submit()
        })

        $('#form_region').on('submit', function(e) {
            e.preventDefault();

            var formData = $(this).serializeArray();
            var action = $(this).attr('action');
            
            $.ajax({
                type: "POST",
                url: action,
                data: formData,
                success: function(res)
                {
                    if (res.status == 0)
                    {
                        var html = '';
                        if(res.errors)
                        {
                            html = '<div class="alert alert-danger">';
                            for(var count = 0; count < res.errors.length; count++)
                            {
                                html += '<p>' + res.errors[count] + '</p>';
                            }
                            html += '</div>';                            
                        }

                        $('#form_result').html(html);
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

        $("#formRegion").on('hidden.bs.modal', function() {
            $("[name='Type']").val(null).trigger('change');
            $("[name='Name']").val(null);
            $("[name='Description']").val(null);
        })

        $('.editBtn').on('click', function() {
            var id = $(this).data('id')

            $.ajax({
                type: "GET",
                url: "{{url('edit_region')}}/" + id,
                success: function(res)
                {
                    $("[name='Type']").val(res.data.Type).trigger('change');
                    $("[name='Name']").val(res.data.Name);
                    $("[name='Description']").val(res.data.Description);
                }
            })
        })

        $('.update_form_region').on('submit', function(e) {
            e.preventDefault();

            var formData = $(this).serializeArray();
            var action = $(this).attr('action');
            
            $.ajax({
                type: "POST",
                url: action,
                data: formData,
                success: function(res)
                {
                    if (res.status == 0)
                    {
                        var html = '';
                        if(res.errors)
                        {
                            html = '<div class="alert alert-danger">';
                            for(var count = 0; count < res.errors.length; count++)
                            {
                                html += '<p>' + res.errors[count] + '</p>';
                            }
                            html += '</div>';                            
                        }

                        $('#update_form_result').html(html);
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

        $(".deleteBtn").on('click', function() {
            var form = $(this).closest('form')

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
                    form.submit()
                }
            });
        })
    })
</script>
@endsection 
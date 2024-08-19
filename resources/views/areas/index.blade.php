@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            Area List
            <button type="button" class="btn btn-md btn-primary" data-toggle="modal" id="addBtn" data-target="#formArea">Add Area</button>
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
                                    <input type="text" class="form-control" placeholder="Search Areas" name="search" value="{{$search}}"> 
                                    <button class="btn btn-sm btn-info">Search</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <table class="table table-striped table-bordered table-hover" id="area_table" width="100%">
                <thead>
                    <tr>
                        <th width="10%">Action</th>
                        <th width="20%">Type</th>
                        <th width="20%">Region</th>
                        <th width="25%">Area</th>
                        <th width="25%">Description</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($areas) > 0)
                    @foreach ($areas as $area)
                    <tr>
                        <td>
                            <button type="button" class="btn btn-sm btn-warning editBtn" data-id="{{$area->id}}" data-toggle="modal" data-target="#edit{{$area->id}}">
                                <i class="ti-pencil"></i>
                            </button>

                            <form action="{{url('delete_area/'.$area->id)}}" method="POST" class="d-inline-block">
                                @csrf

                                <button type="button" class="btn btn-sm btn-danger deleteBtn">
                                    <i class="ti-trash"></i>
                                </button>
                            </form>
                        </td>
                        <td>
                            @if($area->Type == 1)
                            Local
                            @elseif($area->Type == 2)
                            International
                            @endif
                        </td>
                        <td>{{$area->region->Name}}</td>
                        <td>{{$area->Name}}</td>
                        <td>{{$area->Description}}</td>
                    </tr>

                    @include('areas.edit_area')
                    @endforeach
                    @else
                    <tr>
                        <td colspan="5" class="text-center">No data available.</td>
                    </tr>
                    @endif
                    
                </tbody>
            </table>

            {!! $areas->appends(['search' => $search, 'entries' => $entries])->links() !!}
            @php
                $total = $areas->total();
                $currentPage = $areas->currentPage();
                $perPage = $areas->perPage();
                
                $from = ($currentPage - 1) * $perPage + 1;
                $to = min($currentPage * $perPage, $total);
            @endphp
            <p class="mt-3">{{"Showing {$from} to {$to} of {$total} entries"}}</p>
        </div>
    </div>
</div>
<div class="modal fade" id="formArea" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Area</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_area" enctype="multipart/form-data" action="{{ url('new_area') }}">
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
                        <label>Region</label>
                        <select class="form-control js-example-basic-single" name="RegionId" id="RegionId" style="position: relative !important" title="Select Company">
                            <option value="" disabled selected>Select Region</option>
                            @foreach($regions as $region)
                                <option value="{{ $region->id }}">{{ $region->Name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">Area</label>
                        <input type="text" class="form-control" id="Name" name="Name" placeholder="Enter Area Name">
                    </div>
                    <div class="form-group">
                        <label for="name">Description</label>
                        <input type="text" class="form-control" id="Description" name="Description" placeholder="Enter Description">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" name="action_button" id="action_button" class="btn btn-success" value="Save">
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
                <button type="button" name="delete_area" id="delete_area" class="btn btn-danger">Yes</button>
            </div>
        </div>
    </div>
</div> --}}

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script> 

<script>
    $(document).ready(function(){
        // $('#area_table').DataTable({
        //     processing: true,
        //     serverSide: true,
        //     ajax: {
        //         url: "{{ route('area.index') }}"
        //     },
        //     columns: [
        //         {
        //             data: 'Type',
        //             name: 'Type',
        //             render: function(data, type, row) {
        //                 // Display "Local" for type 1 and "International" for type 2
        //                 return data == 1 ? 'Local' : 'International';
        //             }
        //         },
        //         {
        //             data: 'region.Name',
        //             name: 'region.Name'
        //         },
        //         {
        //             data: 'Name',
        //             name: 'Name'
        //         },
        //         {
        //             data: 'Description',
        //             name: 'Description'
        //         },
        //         {
        //             data: 'action',
        //             name: 'action',
        //             orderable: false
        //         }
        //     ],
        //     columnDefs: [
        //         {
        //             targets: 1, // Target the first column (index 1)
        //             render: function(data, type, row) {
        //                 return '<div style="white-space: break-spaces; width: 100%;">' + data + '</div>';
        //             }
        //         },
        //         {
        //             targets: 2, // Target the second column (index 2)
        //             render: function(data, type, row) {
        //                 return '<div style="white-space: break-spaces; width: 100%;">' + data + '</div>';
        //             }
        //         }
        //     ]
        // });

        // $('#add_area').click(function(){
        //     $('#formArea').modal('show');
        //     $('.modal-title').text("Add New Area");
        // });

        $('#form_area').on('submit', function(event){
            event.preventDefault();

            $.ajax({
                url: "{{ route('area.store') }}",
                method: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: "json",
                success: function(data)
                {
                    var html = '';
                    if(data.status == 0)
                    {
                        html = '<div class="alert alert-danger">';
                        for(var count = 0; count < data.errors.length; count++)
                        {
                            html += '<p>' + data.errors[count] + '</p>';
                        }
                        html += '</div>';
                        $('#form_result').html(html);
                    }
                    else
                    {
                        Swal.fire({
                            icon: "success",
                            title: data.success
                        }).then(() => {
                            location.reload()
                        })
                    }
                }

            })
        });

        $('.update_form_area').on('submit', function(e) {
            e.preventDefault();
            
            var formData = $(this).serializeArray()
            var action = $(this).attr('action');
            
            $.ajax({
                type: "POST",
                url: action,
                data: formData,
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
                        $('#update_form_result').html(html);
                    }

                    if(data.success)
                    {
                        Swal.fire({
                            icon: "success",
                            title: data.success
                        }).then(() => {
                            location.reload()
                        })
                    }
                }
            });
        })

        $('.editBtn').on('click', function(){
            var id = $(this).data('id');

            $.ajax({
                type: "get",
                url: "{{ url('edit_area') }}/" + id,
                success: function(html){
                    $('[name="Type"]').val(html.data.Type).trigger('change');
                    $('[name="RegionId"]').val(html.data.RegionId).trigger('change');
                    $('[name="Name"]').val(html.data.Name);
                    $('[name="Description"]').val(html.data.Description);
                }
            });
        });

        $("#addBtn").on('click', function() {
            $('[name="Type"]').val(null).trigger('change');
            $('[name="RegionId"]').val(null).trigger('change');
            $('[name="Name"]').val(null);
            $('[name="Description"]').val(null);
        })

        $('.deleteBtn').on('click', function() {
            var form = $(this).closest('form');

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

        $("[name='entries']").on('change', function() {
            $(this).closest('form').submit()
        })

        // var area_id;
        // $(document).on('click', '.delete', function(){
        //     area_id = $(this).attr('id');
        //     $('#confirmModal').modal('show');
        //     $('.modal-title').text("Delete Area");
        // });    

        // $('#delete_area').click(function(){
        //     $.ajax({
        //         url: "{{ url('delete_area') }}/" + area_id, 
        //         method: "GET",
        //         beforeSend:function(){
        //             $('#delete_area').text('Deleting...');
        //         },
        //         success:function(data)
        //         {
        //             setTimeout(function(){
        //                 $('#confirmModal').modal('hide');
        //                 $('#area_table').DataTable().ajax.reload();
        //             }, 2000);
        //         }
        //     })
        // });

        
    });
</script>
@endsection 
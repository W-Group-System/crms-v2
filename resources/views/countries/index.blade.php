@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            Country List
            <button type="button" class="btn btn-md btn-primary" id="add_country" data-toggle="modal" data-target="#formCountry">Add Country</button>
            </h4>
            <div class="row">
                <div class="col-lg-6">
                    <span>Show</span>
                    <form method="GET" class="d-inline-block">
                        <select name="entries" class="form-control">
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
                                    <input type="text" class="form-control" placeholder="Search Country" name="search" value="{{$search}}"> 
                                    <button class="btn btn-sm btn-info">Search</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <table class="table table-striped table-bordered table-hover" id="country_table" width="100%">
                <thead>
                    <tr>
                        <th width="10%">Action</th>
                        <th width="40%">Country</th>
                        <th width="40%">Description</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @if(count($country) > 0)
                            @foreach ($country as $c)
                                <tr>
                                    <td>
                                        <button class="btn btn-sm btn-warning editBtn" data-toggle="modal" data-target="#formCountry{{$c->id}}" data-id="{{$c->id}}">
                                            <i class="ti-pencil"></i>
                                        </button>

                                        <form action="{{url('delete_country/'.$c->id)}}" class="d-inline-block" method="post">
                                            @csrf

                                            <button class="btn btn-danger btn-sm deleteBtn" type="button">
                                                <i class="ti-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td>{{$c->Name}}</td>
                                    <td>{{$c->Description}}</td>
                                </tr>

                                @include('countries.edit_countries')
                            @endforeach
                        @else
                        <tr>
                            <td colspan="3" class="text-center">No data available</td>
                        </tr>
                        @endif
                    </tr>
                </tbody>
            </table>

            {!! $country->appends(['search' => $search, 'entries' => $entries])->links() !!}
        </div>
    </div>
</div>
<div class="modal fade" id="formCountry" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Country</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_country" enctype="multipart/form-data" action="{{ route('country.store') }}">
                    <span id="form_result"></span>
                    @csrf
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
                <h5 class="modal-title" id="deleteModalLabel">Delete Country</h5>
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script> 

<script>
    $(document).ready(function(){
        // $('#country_table').DataTable({
        //     processing: true,
        //     serverSide: true,
        //     ajax: {
        //         url: "{{ route('country.index') }}"
        //     },
        //     columns: [
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
        //             targets: 0, // Target the first column (index 1)
        //             render: function(data, type, row) {
        //                 return '<div style="white-space: break-spaces; width: 100%;">' + data + '</div>';
        //             }
        //         },
        //         {
        //             targets: 1, // Target the second column (index 2)
        //             render: function(data, type, row) {
        //                 return '<div style="white-space: break-spaces; width: 100%;">' + data + '</div>';
        //             }
        //         }
        //     ]
        // });

        $('#add_country').click(function(){
            $('[name="Name"]').val(null);
            $('[name="Description"]').val(null);
        });

        $('#form_country').on('submit', function(event){
            event.preventDefault();

            var action = $(this).attr('action')
            var formData = $(this).serializeArray()

            $.ajax({
                type: "POST",
                url: action,
                data: formData,
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
                        
                        $('#form_result').html(html)
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

        $('.editBtn').on('click', function(){
            var id = $(this).data('id');
            
            $.ajax({
                type: "get",
                url: "{{url('edit_country')}}/" + id,
                success: function(html){
                    $('[name="Name"]').val(html.data.Name);
                    $('[name="Description"]').val(html.data.Description);
                }
            });
        });

        $('#update_form_country').on('submit', function(event){
            event.preventDefault();

            var action = $(this).attr('action')
            var formData = $(this).serializeArray()

            $.ajax({
                type: "POST",
                url: action,
                data: formData,
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
                        
                        $('#update_form_result').html(html)
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
        
        // var country_id;
        // $(document).on('click', '.delete', function(){
        //     country_id = $(this).attr('id');
        //     $('#confirmModal').modal('show');
        //     $('.modal-title').text("Delete Country");
        // });    

        // $('#yes_button').click(function(){
        //     $.ajax({
        //         url: "{{ url('delete_country') }}/" + country_id, 
        //         method: "GET",
        //         beforeSend:function(){
        //             $('#yes_button').text('Deleting...');
        //         },
        //         success:function(data)
        //         {
        //             setTimeout(function(){
        //                 $('#confirmModal').modal('hide');
        //                 $('#country_table').DataTable().ajax.reload();
        //             }, 2000);
        //         }
        //     })
        // });

        $("[name='entries']").on('change', function() {
            $(this).closest('form').submit()
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
    });
</script>

@endsection 
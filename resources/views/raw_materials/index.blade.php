@extends('layouts.header')
@section('css')
    <link rel="stylesheet" href="{{asset('css/sweetalert2.min.css')}}">
@endsection
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            Raw Material List
            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#formRawMaterial">Add Raw Material</button>
            </h4>
            @include('components.error')

            <div class="table-responsive">
                <form method="GET" action="" class="custom_form mb-3" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-2">
                            <input type='text' class='form-control form-control-sm' name='search' placeholder="Search Material" value="{{$search}}">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary btn-sm btn-submit" style="width:100px;border-radius:4px" type="submit">Search</button>
                        </div>
                    </div>
                </form>
                <form action="" method="post">
                    {{csrf_field()}}

                    <button class="btn btn-success btn-sm btn-submit mb-2" style="width:100px;border-radius:4px" type="submit">Export</button>
                </form>
                <table class="table table-striped table-bordered table-hover" id="raw_material_table">
                    <thead>
                        <tr>
                            <th width="30%">Material</th>
                            <th width="30%">Description</th>
                            <th width="30%">Status</th>
                            <th width="10%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rawMaterials as $rm)
                            <tr>
                                <td>{{$rm->Name}}</td>
                                <td>{{$rm->Description}}</td>
                                <td>
                                    @if($rm->status == "Active")
                                        <div class="badge badge-success">Active</div>
                                    @else
                                        <div class="badge badge-danger">Inactive</div>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary" title="View Products" data-toggle="modal" data-target="#viewProducts-{{$rm->id}}">
                                        <i class="ti-eye"></i>
                                    </button>
    
                                    @if($rm->status == "Active")
                                    <button class="btn btn-sm btn-danger deactivate" title="Deactivate" data-id="{{$rm->id}}">
                                        <i class="ti-trash"></i>
                                    </button>
                                    @else
                                    <button class="btn btn-sm btn-info activate" title="Activate" data-id="{{$rm->id}}">
                                        <i class="ti-check"></i>
                                    </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {!! $rawMaterials->appends(['search' => $search])->links() !!}
        </div>
    </div>
</div>

<div class="modal fade" id="formRawMaterial" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Raw Material</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_raw_material" action="{{url('add_raw_material')}}">
                    {{-- <span id="form_result"></span> --}}
                    @csrf
                    <div class="form-group">
                        <label for="name">Material</label>
                        <input type="text" class="form-control" id="Name" name="Name" placeholder="Enter Material" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Description</label>
                        <textarea type="text" rows="3" class="form-control" id="Description" name="Description" placeholder="Enter Description" required></textarea>
                    </div>
                    <div class="modal-footer">
                        {{-- <input type="hidden" name="action" id="action" value="Save">
                        <input type="hidden" name="hidden_id" id="hidden_id"> --}}
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" name="action_button" id="action_button" class="btn btn-success" value="Save">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@foreach ($rawMaterials as $rm)
@include('raw_materials.view_raw_materials')
@endforeach

<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap4.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.bootstrap4.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
<script src="{{asset('js/sweetalert2.min.js')}}"></script>
<script>
    $(document).ready(function() {
        // $(".view_raw_materials_table").DataTable({
        //     processing: false,
        //     serverSide:false,
        //     ordering: false,
        // })


        $(".deactivate").on('click', function()
        {
            var id = $(this).data('id');

            $.ajax
            ({
                type: "POST",
                url: "{{url('deactivate_raw_material')}}",
                data: 
                {
                    id: id
                },
                headers: 
                {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function()
                {
                    Swal.fire
                    ({
                        icon: 'success',
                        title: 'Successfully Deactivate'
                    }).then(() => {
                        location.reload();
                    })
                }
            })
        })

        $(".activate").on('click', function()
        {
            var id = $(this).data('id');

            $.ajax
            ({
                type: "POST",
                url: "{{url('activate_raw_material')}}",
                data: 
                {
                    id: id
                },
                headers: 
                {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function()
                {
                    Swal.fire
                    ({
                        icon: 'success',
                        title: 'Successfully Activate'
                    }).then(() => {
                        location.reload();
                    })
                }
            })
        })
    })
</script>
{{-- <script>
    $(document).ready(function(){
        var viewRawMaterials = new DataTable('#view_raw_materials_table', {
            destroy: true,
            pageLength: 25,
            processing: false,
            serverSide: false,
            ordering: false,
        });

        var rawMaterialsTable = new DataTable('#raw_material_table', {
            destroy: true,
            pageLength: 25,
            processing: false,
            serverSide: true,
            ordering: false,
            layout: {
                topStart: {
                    buttons: [
                        'copy',
                        {
                            extend: 'excel',
                            text: 'Export to Excel',
                            filename: 'Raw Material', // Set the custom file name
                            title: 'Raw Material' // Set the custom title
                        }
                    ]
                }
            },
            ajax: {
                url: "{{ route('raw_material.index') }}",
            },
            columns: [
                {
                    data: 'Name',
                    name: 'Name'
                },
                {
                    data: 'Description',
                    name: 'Description',
                    render: function(data, type, row) 
                    {
                        return '<div style="white-space: break-spaces; width: 100%;">' + (data ? data : 'No Description Available') + '</div>';
                    }
                },
                {
                    data: 'status',
                    name: 'status',
                    render: function(data, type, row)
                    {
                        return data == "Active" ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Inactive</span>'
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
                    targets: 0, // Target the column
                    render: function(data, type, row) {
                        return '<div style="white-space: break-spaces; width: 100%;">' + data + '</div>';
                    }
                }
            ],
            rowCallback: function(row, data, index)
            {
                $(row).find('.viewModal').on('click', function() {
                    $("#viewProducts").modal('show');

                    var id =  $(this).data('id');

                    $.ajax({
                        type: "GET",
                        url: "{{url('get_raw_materials_products')}}",
                        data:
                        {
                            id: id
                        },
                        success: function(res)
                        {
                            viewRawMaterials.draw();

                            if (res.status == 1)
                            {

                                $('.dt-empty').remove();
                                var trRow = `
                                    <tr>
                                        <td>${res.products}</td>
                                        <td>${res.percentage}</td>
                                    </tr>
                                `
                                $(".tbodyRow").append(trRow);

                            }

                        }
                    })
                })

                $(row).find('.deactivate').on('click', function(){
                    var id = $(this).data('id');

                    $.ajax
                    ({
                        type: "POST",
                        url: "{{url('deactivate_raw_material')}}",
                        data: 
                        {
                            id: id
                        },
                        headers: 
                        {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function()
                        {
                            Swal.fire
                            ({
                                icon: 'success',
                                title: 'Successfully Deactivate'
                            }).then(() => {
                                rawMaterialsTable.draw();
                            })
                        }
                    })
                })

                $(row).find('.activate').on('click', function(){
                    var id = $(this).data('id');

                    $.ajax
                    ({
                        type: "POST",
                        url: "{{url('activate_raw_material')}}",
                        data: 
                        {
                            id: id
                        },
                        headers: 
                        {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function()
                        {
                            Swal.fire
                            ({
                                icon: 'success',
                                title: 'Successfully Activate'
                            }).then(() => {
                                rawMaterialsTable.draw();
                            })
                        }
                    })
                })
            }
        });

        $('#add_raw_material').click(function(){
            $('#formRawMaterial').modal('show');
            $('.modal-title').text("Add Raw Material");
        });

        $('#form_raw_material').on('submit', function(event){
            event.preventDefault();
            
            var formData = $(this).serializeArray();

            $.ajax(
                {
                    type: "POST",
                    url: "{{ url('add_raw_material') }}",
                    data: formData,
                    success: function(res)
                    {
                        if (res.status == 1)
                        {
                            Swal.fire({
                                title: res.message,
                                icon: 'success'
                            })
                        }

                        $("#formRawMaterial").modal('hide');
                        $('#form_raw_material').trigger('reset');
                        rawMaterialsTable.draw();
                    }
                }
            )

            // if($('#action').val() == 'Save')
            // {
            //     $.ajax({
            //         url: "{{ route('activity.store') }}",
            //         method: "POST",
            //         data: new FormData(this),
            //         contentType: false,
            //         cache: false,
            //         processData: false,
            //         dataType: "json",
            //         success: function(data)
            //         {
            //             var html = '';
            //             if(data.errors)
            //             {
            //                 html = '<div class="alert alert-danger">';
            //                 for(var count = 0; count < data.errors.length; count++)
            //                 {
            //                     html += '<p>' + data.errors[count] + '</p>';
            //                 }
            //                 html += '</div>';
            //             }
            //             if(data.success)
            //             {
            //                 html = '<div class="alert alert-success">' + data.success + '</div>';
            //                 $('#form_raw_material')[0].reset();
            //                 setTimeout(function(){
            //                     $('#formRawMaterial').modal('hide');
            //                 }, 2000);
            //                 if (dataTableInstance) {
            //                     dataTableInstance.ajax.reload();
            //                 }
            //                 setTimeout(function(){
            //                     $('#form_result').empty(); 
            //                 }, 2000); 
            //             }
            //             $('#form_result').html(html);
            //         }
            //     })
            // }

            // if($('#action').val() == 'Edit')
            // {
            //     var formData = new FormData(this);
            //     formData.append('id', $('#hidden_id').val());
            //     $.ajax({
            //         url: "{{ route('update_activity', ':id') }}".replace(':id', $('#hidden_id').val()),
            //         method: "POST",
            //         data: new FormData(this),
            //         contentType: false,
            //         cache: false,
            //         processData: false,
            //         dataType: "json",
            //         success:function(data)
            //         {
            //             var html = '';
            //             if(data.errors)
            //             {
            //                 html = '<div class="alert alert-danger">';
            //                 for(var count = 0; count < data.errors.length; count++)
            //                 {
            //                     html += '<p>' + data.errors[count] + '</p>';
            //                 }
            //                 html += '</div>';
            //             }
            //             if(data.success)
            //             {
            //                 html = '<div class="alert alert-success">' + data.success + '</div>';
            //                 $('#form_raw_material')[0].reset();
            //                 setTimeout(function(){
            //                     $('#formRawMaterial').modal('hide');
            //                 }, 2000);
            //                 if (dataTableInstance) {
            //                     dataTableInstance.ajax.reload();
            //                 }
            //                 setTimeout(function(){
            //                     $('#form_result').empty(); 
            //                 }, 2000); 
            //             }
            //             $('#form_result').html(html);
            //         }
            //     });
            // }
        });

        // $(document).on('click', '.edit', function(){
        //     $('.edit-status').show();
        //     var id = $(this).attr('id');
        //     $('#form_result').html('');
        //     $.ajax({
        //         url: "{{ route('edit_activity', ['id' => '_id_']) }}".replace('_id_', id),
        //         dataType: "json",
        //         success: function(response){
        //             var data = response.data;
        //             var primaryUser = response.primaryUser;
        //             var secondaryUser = response.secondaryUser;
        //             var files = response.files;

        //             $('#Title').val(data.Title);
        //             $('#TransactionNumber').val(data.TransactionNumber);
        //             $('#ScheduleFrom').val(data.ScheduleFrom);
        //             $('#ScheduleTo').val(data.ScheduleTo);
        //             $('#Description').val(data.Description);
        //             $('#ClientId').val(data.ClientId).trigger('change');
        //             $('#ClientContactId').val(data.ClientContactId).trigger('change');
        //             $('#PrimaryResponsibleUserId').val(primaryUser ? primaryUser.id : '').trigger('change');
        //             $('#SecondaryResponsibleUserId').val(secondaryUser ? secondaryUser.id : '').trigger('change');

        //             var fileList = '';

        //             files.forEach(function(file) {
        //                 var fileName = file.split('/').pop(); 
        //                 fileList += '<li><a href="' + '{{ asset("storage") }}' + '/' + file + '" download="' + fileName + '">' + fileName + '</a></li>';
        //             });

        //             $('#fileList').html(fileList);

        //             $('#fileList').html(fileList);

        //             $('.edit-status').show();
        //             $('#hidden_id').val(data.id);
        //             $('.modal-title').text("Edit Activity");
        //             $('#action_button').val("Update");
        //             $('#action').val("Edit");
        //             $('#Type').val(data.Type).trigger('change');
        //             $('#Status').val(data.Status).trigger('change');
        //             $('#RelatedTo').val(data.RelatedTo).trigger('change');
        //             var clientId = data.ClientId;
        //             $.ajax({
        //                 url: "{{ url('get-contacts') }}/" + clientId,
        //                 type: "GET",
        //                 dataType: "json",
        //                 success:function(contactData) {
        //                     $('#ClientContactId').empty();
        //                     $('#ClientContactId').append('<option value="" disabled selected>Select Contact</option>');
        //                     $.each(contactData, function(key, value) {
        //                         $('#ClientContactId').append('<option value="'+ key +'">'+ value +'</option>');
        //                     });
        //                     $('#ClientContactId').val(data.ClientContactId);
        //                 }
        //             });

        //             $('#formRawMaterial').modal('show');
        //         }
        //     });
        // });
    });
</script> --}}

@endsection 
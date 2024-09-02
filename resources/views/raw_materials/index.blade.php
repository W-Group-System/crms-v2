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
            <button type="button" class="btn btn-primary" data-toggle="modal" id="addBtn" data-target="#formRawMaterial">New</button>
            </h4>
            @include('components.error')
            
            <div class="mb-3">
                <button type="button" id="copy_issue_btn" class="btn btn-md btn-info mb-1">Copy</button>
                <a href="{{url('export_raw_materials')}}" id="excel_btn" class="btn btn-md btn-success mb-1">Excel</a>
            </div>
            
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
                                    <input type="text" class="form-control" placeholder="Search Raw Materials" name="search" value="{{$search}}"> 
                                    <button class="btn btn-sm btn-info">Search</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                
                <table class="table table-striped table-bordered table-hover" id="raw_material_table" >
                    <thead>
                        <tr>
                            <th width="10%">Action</th>
                            <th width="30%">Material</th>
                            <th width="30%">Description</th>
                            {{-- <th width="30%">Status</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rawMaterials as $rm)
                            <tr>
                                <td>
                                    <a href="{{url('view_raw_materials/'.$rm->id)}}" class="btn btn-sm btn-info" title="View Raw Material Details">
                                        <i class="ti-eye"></i>
                                    </a>

                                    <button type="button" data-toggle="modal" data-target="#editRawMaterials{{$rm->id}}" class="btn btn-sm btn-warning editBtn" data-id="{{$rm->id}}">
                                        <i class="ti-pencil"></i>
                                    </button>
    
                                    <form method="POST" class="d-inline-block" action="{{url('delete_raw_materials/'.$rm->id)}}" onsubmit="show()">
                                        @csrf

                                        <button type="button" class="btn btn-sm btn-danger deleteBtn" data-id="{{$rm->id}}">
                                            <i class="ti-trash"></i>
                                        </button>
                                    </form>
                                    {{-- @if($rm->status == "Active")
                                    @else
                                    <button class="btn btn-sm btn-info activate" title="Activate" data-id="{{$rm->id}}">
                                        <i class="ti-check"></i>
                                    </button>
                                    @endif --}}
                                </td>
                                <td>{{$rm->Name}}</td>
                                <td>{{$rm->Description}}</td>
                                {{-- <td>
                                    @if($rm->status == "Active")
                                        <div class="badge badge-success">Active</div>
                                    @else
                                        <div class="badge badge-danger">Inactive</div>
                                    @endif
                                </td> --}}
                            </tr>

                            @include('raw_materials.edit_raw_materials')
                        @endforeach
                    </tbody>
                </table>
            </div>
            {!! $rawMaterials->appends(['search' => $search])->links() !!}

            @php
                $total = $rawMaterials->total();
                $currentPage = $rawMaterials->currentPage();
                $perPage = $rawMaterials->perPage();
                
                $from = ($currentPage - 1) * $perPage + 1;
                $to = min($currentPage * $perPage, $total);
            @endphp

            <p  class="mt-3">{{"Showing {$from} to {$to} of {$total} entries"}}</p>
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
                <form method="POST" id="form_raw_material" action="{{url('add_raw_material')}}" onsubmit="show()">
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

        $('.table').tablesorter({
            theme: "bootstrap"
        })

        $("[name='entries']").on('change', function() {
            $(this).closest('form').submit()
        })

        $(".editBtn").on('click', function() {
            var id = $(this).data('id');

            $.ajax({
                type: "get",
                url: "{{url('edit_raw_materials')}}/" + id,
                success: function(data)
                {
                    $('[name="Name"]').val(data.Name)
                    $('[name="Description"]').val(data.Description)
                }
            })
        })

        $("#addBtn").on('click', function() {
            $('[name="Name"]').val(null)
            $('[name="Description"]').val(null)
        })

        $(".deleteBtn").on('click', function() {
            var form = $(this).closest('form')

            Swal.fire({
                title: "Are you sure?",
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

        $('#copy_issue_btn').click(function() {
            $.ajax({
                url: "{{ route('raw_material.index') }}",
                type: 'GET',
                data: {
                    search: "{{ request('search') }}",
                    sort: "{{ request('sort') }}",
                    direction: "{{ request('direction') }}",
                    fetch_all: true
                },
                success: function(data) {
                    var tableData = '';

                    $('#raw_material_table thead tr').each(function(rowIndex, tr) {
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
                            item.Description = "";    
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

        // $(".deactivate").on('click', function()
        // {
        //     var id = $(this).data('id');

        //     $.ajax
        //     ({
        //         type: "POST",
        //         url: "{{url('deactivate_raw_material')}}",
        //         data: 
        //         {
        //             id: id
        //         },
        //         headers: 
        //         {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         success: function()
        //         {
        //             Swal.fire
        //             ({
        //                 icon: 'success',
        //                 title: 'Successfully Deactivate'
        //             }).then(() => {
        //                 location.reload();
        //             })
        //         }
        //     })
        // })

        // $(".activate").on('click', function()
        // {
        //     var id = $(this).data('id');

        //     $.ajax
        //     ({
        //         type: "POST",
        //         url: "{{url('activate_raw_material')}}",
        //         data: 
        //         {
        //             id: id
        //         },
        //         headers: 
        //         {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         success: function()
        //         {
        //             Swal.fire
        //             ({
        //                 icon: 'success',
        //                 title: 'Successfully Activate'
        //             }).then(() => {
        //                 location.reload();
        //             })
        //         }
        //     })
        // })
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
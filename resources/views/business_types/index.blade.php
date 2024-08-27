@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            Business Type List
            <button type="button" class="btn btn-md btn-primary" id="add_business_type" data-toggle="modal" data-target="#formBusinessType">Add Business Type</button>
            </h4>
            <div class="mb-3">
                <button class="btn btn-info" id="copyBtn" type="button">Copy</button>
                <a href="{{url('export_business_type')}}" class="btn btn-success" target="_blank">Excel</a>
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
                                    <input type="text" class="form-control" placeholder="Search Business Type" name="search" value="{{$search}}"> 
                                    <button class="btn btn-sm btn-info">Search</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <table class="table table-striped table-bordered table-hover" id="business_type_table" width="100%">
                <thead>
                    <tr>
                        <th width="25%">Action</th>
                        <th width="35%">Name</th>
                        <th width="40%">Description</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bussinessType as $bt)
                        <tr>
                            <td>
                                <button class="btn btn-sm btn-warning editBtn" type="button" data-toggle="modal" data-target="#edit{{$bt->id}}" data-id="{{$bt->id}}">
                                    <i class="ti-pencil"></i>
                                </button>

                                <form method="POST" class="d-inline-block" action="{{url('delete_business_type/'.$bt->id)}}">
                                    @csrf 

                                    <button class="btn btn-sm btn-danger deleteBtn" type="button">
                                        <i class="ti-trash"></i>
                                    </button>
                                </form>
                            </td>
                            <td>{{$bt->Name}}</td>
                            <td>{{$bt->Description}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {!! $bussinessType->appends(['search' => $search])->links() !!}
            @php
                $total = $bussinessType->total();
                $currentPage = $bussinessType->currentPage();
                $perPage = $bussinessType->perPage();
                
                $from = ($currentPage - 1) * $perPage + 1;
                $to = min($currentPage * $perPage, $total);
            @endphp
            <p  class="mt-3">{{"Showing {$from} to {$to} of {$total} entries"}}</p>
        </div>
    </div>
</div>

<div class="modal fade" id="formBusinessType" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Bussiness Type</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_business_type" action="{{ route('business_type.store') }}">
                    <span id="form_result"></span>
                    @csrf
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="Name" name="Name" placeholder="Enter Name">
                    </div>
                    <div class="form-group">
                        <label for="name">Description</label>
                        <input type="text" class="form-control" id="Description" name="Description" placeholder="Enter Description">
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
{{-- <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Business Type</h5>
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
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>  --}}
@foreach ($bussinessType as $bt)
@include('business_types.edit_business_type')
@endforeach

<script>
    $(document).ready(function(){
        // $('#business_type_table').DataTable({
        //     processing: true,
        //     serverSide: true,
        //     ajax: {
        //         url: "{{ route('business_type.index') }}"
        //     },
        //     columns: [
        //         {
        //             data: 'Name',
        //             name: 'Name'
        //         },
        //         {
        //             data: 'Description',
        //             name: 'Description',
        //         },
        //         {
        //             data: 'action',
        //             name: 'action',
        //             orderable: false
        //         }
        //     ],
        //     columnDefs: [
        //         {
        //             targets: 1, // Target the Description column
        //             render: function(data, type, row) {
        //                 return '<div style="white-space: break-spaces; width: 100%;">' + data + '</div>';
        //             }
        //         }
        //     ]
        // });

        $('#add_business_type').click(function(){
            $("[name='Name']").val(null)
            $("[name='Description']").val(null)
            $('#form_result').html(null);
        });

        $('#form_business_type').on('submit', function(event){
            event.preventDefault();

            var formData = $(this).serializeArray()

            $.ajax({
                url: "{{ route('business_type.store') }}",
                method: "POST",
                data: formData,
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

                        $('#form_result').html(html);
                    }

                    if(data.success)
                    {
                        Swal.fire({
                            icon: "success",
                            title: "Successfully Saved"
                        }).then(() => {
                            location.reload()
                        })   
                    }
                }
            })
        });

        $('.editBtn').on('click', function(){
            var id = $(this).data('id');
            $('#update_form_result').html(null);

            $.ajax({
                type: "GET",
                url: "{{url('edit_business_type')}}/" + id,
                dataType: "json",

                success: function(html){
                    $('[name="Name"]').val(html.data.Name);
                    $('[name="Description"]').val(html.data.Description);
                }
            });
        });

        $('.update_form').on('submit', function(event){
            event.preventDefault();

            var formData = $(this).serializeArray()
            var action = $(this).attr('action')

            $.ajax({
                url: action,
                method: "POST",
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
                        $('.update_form_result').html(html);
                    }

                    if(data.success)
                    {
                        Swal.fire({
                            icon: "success",
                            title: "Successfully Updated"
                        }).then(() => {
                            location.reload()
                        }) 
                    }
                }
            });
        });

        $(".deleteBtn").on('click', function() {
            var form = $(this).closest('form')

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes! Delete it"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit()
                }
            });
        })

        $('[name="entries"]').on('change', function() {
            $(this).closest('form').submit()
        })

        $('#copyBtn').click(function() {
            $.ajax({
                url: "{{ route('business_type.index') }}",
                type: 'GET',
                data: {
                    search: "{{ request('search') }}",
                    sort: "{{ request('sort') }}",
                    direction: "{{ request('direction') }}",
                    fetch_all: true
                },
                success: function(data) {
                    var tableData = '';

                    $('#business_type_table thead tr').each(function(rowIndex, tr) {
                        $(tr).find('th').each(function(cellIndex, th) {
                            if($(th).text().trim() !== "Action")
                            {
                                tableData += $(th).text().trim() + '\t';
                            }
                        });
                        tableData += '\n';
                    });

                    $(data).each(function(index, item) {
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
    });
</script>
@endsection
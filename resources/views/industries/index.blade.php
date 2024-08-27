@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            Industry List
            <button type="button" class="btn btn-md btn-primary" id="add_industry" data-toggle="modal" data-target="#formIndustry">Add Industry</button>
            </h4>
            <div class="mb-3">
                <button type="button" id="copy_issue_btn" class="btn btn-md btn-info mb-1">Copy</button>
                <a href="{{url('export_industry')}}" id="excel_btn" class="btn btn-md btn-success mb-1">Excel</a>
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
                                    <input type="text" class="form-control" placeholder="Search Industry" name="search" value="{{$search}}"> 
                                    <button class="btn btn-sm btn-info">Search</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <table class="table table-striped table-bordered table-hover" id="industry_table" width="100%">
                <thead>
                    <tr>
                        <th width="25%">Action</th>
                        <th width="35%">Name</th>
                        <th width="40%">Description</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($industry as $i)
                        <tr>
                            <td>
                                <button type="button" class="btn btn-warning btn-sm editBtn" data-toggle="modal" data-target="#edit{{$i->id}}" data-id="{{$i->id}}">
                                    <i class="ti-pencil"></i>
                                </button>

                                <form method="POST" class="d-inline-block" action="{{url('delete_industry/'.$i->id)}}">
                                    @csrf

                                    <button type="button" class="btn btn-danger btn-sm deleteBtn">
                                        <i class="ti-trash"></i>
                                    </button>
                                </form>
                            </td>
                            <td>{{$i->Name}}</td>
                            <td>{{$i->Description}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {!! $industry->appends(['search' => $search, 'entries' => $entries])->links() !!}

            @php
                $total = $industry->total();
                $currentPage = $industry->currentPage();
                $perPage = $industry->perPage();
                
                $from = ($currentPage - 1) * $perPage + 1;
                $to = min($currentPage * $perPage, $total);
            @endphp

            <p  class="mt-3">{{"Showing {$from} to {$to} of {$total} entries"}}</p>
        </div>
    </div>
</div>
<div class="modal fade" id="formIndustry" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Industry</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_industry" enctype="multipart/form-data" action="">
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
{{-- <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Industry</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 20px">
                <h5 style="margin: 0">Are you sure you want to delete this data?</h5>
            </div>
            <div class="modal-footer" style="padding: 0.6875rem">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" name="delete_industry" id="delete_industry" class="btn btn-danger">Yes</button>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>  --}}
@foreach ($industry as $i)
@include('industries.edit_industries')
@endforeach
<script>
    $(document).ready(function(){
        // $('#industry_table').DataTable({
        //     processing: true,
        //     serverSide: true,
        //     ajax: {
        //         url: "{{ route('industry.index') }}"
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

        $('#add_industry').click(function(){
            $('[name="Name"]').val(null);
            $('[name="Description"]').val(null);
            $('#form_result').html(null);
        });

        $('#form_industry').on('submit', function(event){
            event.preventDefault();

            var formData = $(this).serializeArray()

            $.ajax({
                url: "{{ route('industry.store') }}",
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
            
            // if($('#action').val() == 'Edit')
            // {
            //     var formData = new FormData(this);
            //     formData.append('id', $('#hidden_id').val());
            //     $.ajax({
            //         url: "{{ route('update_industry', ':id') }}".replace(':id', $('#hidden_id').val()),
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
            //                 $('#form_industry')[0].reset();
            //                 setTimeout(function(){
            //                     $('#formIndustry').modal('hide');
            //                 }, 1000);
            //                 $('#industry_table').DataTable().ajax.reload();
            //                 setTimeout(function(){
            //                     $('#form_result').empty(); 
            //                 }, 1000); 
            //             }
            //             $('#form_result').html(html);
            //         }
            //     });
            // }
        });

        $(document).on('click', '.editBtn', function(){
            var id = $(this).data('id');
            $('.update_form_result').html('');

            $.ajax({
                url: "{{ route('edit_industry', ['id' => '_id_']) }}".replace('_id_', id),
                dataType: "json",
                success: function(html){
                    $('[name="Name"]').val(html.data.Name);
                    $('[name="Description"]').val(html.data.Description);
                }
            });
        });

        $('.update_form_industry').on('submit', function(event){
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

                        $(".update_form_result").html(html)
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

        $("[name='entries']").on('change', function() {
            $(this).closest('form').submit()
        })

        $('#copy_issue_btn').click(function() {
            $.ajax({
                url: "{{ route('industry.index') }}",
                type: 'GET',
                data: {
                    search: "{{ request('search') }}",
                    sort: "{{ request('sort') }}",
                    direction: "{{ request('direction') }}",
                    fetch_all: true
                },
                success: function(data) {
                    var tableData = '';

                    $('#industry_table thead tr').each(function(rowIndex, tr) {
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
    });
</script>
@endsection
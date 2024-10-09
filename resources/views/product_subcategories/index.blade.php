@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            Application Sub Categories
            <button type="button" class="btn btn-md btn-outline-primary" data-toggle="modal" data-target="#formProductSubcategories" id="addBtn">New</button>
            </h4>
            {{-- <form method="GET" class="custom_form mb-3" enctype="multipart/form-data">
                <div class="row height d-flex justify-content-end align-items-end">
                    <div class="col-md-3">
                        <div class="search">
                            <i class="ti ti-search"></i>
                            <input type="text" class="form-control" placeholder="Search Product Application Subcategories" name="search" value="{{$search}}"> 
                            <button class="btn btn-sm btn-info">Search</button>
                        </div>
                    </div>
                </div>
            </form> --}}
            <div class="mb-3">
                <button type="button" id="copy_btn" class="btn btn-outline-info">Copy</button>
                <a href="{{url('export_application_subcategories')}}" class="btn btn-outline-success" target="_blank">Excel</a>
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
                                    <input type="text" class="form-control" placeholder="Search Application Sub Categories" name="search" value="{{$search}}"> 
                                    <button class="btn btn-sm btn-info">Search</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <table class="table table-striped table-bordered table-hover table-bordered" id="product_subcategories_table" width="100%">
                <thead>
                    <tr>
                        <th width="8%">Action</th>
                        <th width="27%">Application</th>
                        <th width="30%">Subcategory</th>
                        <th width="35%">Description</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($subcategories as $sub)
                        <tr>
                            <td align="center">
                                <button class="btn btn-outline-warning btn-sm editBtn" data-toggle="modal" data-target="#formProductSubcategories-{{$sub->id}}" title="Edit" data-id="{{$sub->id}}">
                                    <i class="ti-pencil"></i>
                                </button>

                                <button class="btn btn-outline-danger btn-sm deleteSub" title="Delete" data-id="{{$sub->id}}">
                                    <i class="ti-trash"></i>
                                </button>
                            </td>
                            <td>{{$sub->application->Name}}</td>
                            <td>{{$sub->Name}}</td>
                            <td>{{$sub->Description}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {!! $subcategories->appends(['search' => $search, 'entries' => $entries])->links() !!}

            @php
                $total = $subcategories->total();
                $currentPage = $subcategories->currentPage();
                $perPage = $subcategories->perPage();
                
                $from = ($currentPage - 1) * $perPage + 1;
                $to = min($currentPage * $perPage, $total);
            @endphp

            <p  class="mt-3">{{"Showing {$from} to {$to} of {$total} entries"}}</p>
        </div>
    </div>
</div>

<div class="modal fade" id="formProductSubcategories" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Product Subcategories</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_product_subcategories" enctype="multipart/form-data" action="{{ route('product_subcategories.store') }}" onsubmit="show()">
                    <span id="form_result"></span>
                    @csrf
                    <div class="form-group">
                        <label>Application</label>
                        <select class="form-control js-example-basic-single" name="ProductApplicationId" style="position: relative !important" title="Select Type" required>
                            <option value="" disabled selected>Select Application</option>
                            @foreach($productapp as $productapps)
                                <option value="{{ $productapps->id }}">{{ $productapps->Name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">Subcategory</label>
                        <input type="text" class="form-control" id="Name" name="Name" placeholder="Enter Subcategory" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Description</label>
                        <input type="text" class="form-control" id="Description" name="Description" placeholder="Enter Description" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" id="action_button" class="btn btn-success" value="Save">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@foreach ($subcategories as $sub)
    @include('product_subcategories.edit_product_application_subcategories')
@endforeach

<style>
    .swal-wide {
        width: 400px;
    }
</style>

<script>
    $(document).ready(function() {
        $('.deleteSub').on('click', function() {
            var id = $(this).data('id')

            Swal.fire({
                title: "Are you sure?",
                // text: "You won't be able to revert this!",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
                customClass: 'swal-wide',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "{{url('delete_product_subcategories')}}",
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
            $("[name='ProductApplicationId']").val(null).trigger('change')
            $("[name='Name']").val(null)
            $("[name='Description']").val(null)
        })

        $(".editBtn").on('click', function() {
            
            var id = $(this).data('id')

            $.ajax({
                type: "GET",
                url: "{{url('edit_product_subcategories')}}/" + id,
                success: function(data)
                {
                    $("[name='ProductApplicationId']").val(data.data.ProductApplicationId).trigger('change')
                    $("[name='Name']").val(data.data.Name)
                    $("[name='Description']").val(data.data.Description)
                }
            })
        })

        $('#copy_btn').click(function() {
            
            $.ajax({
                url: "{{ route('product_subcategories.index') }}",
                type: 'GET',
                data: {
                    search: "{{ request('search') }}",
                    sort: "{{ request('sort') }}",
                    direction: "{{ request('direction') }}",
                    fetch_all: true
                },
                success: function(data) {
                    var tableData = '';

                    $('#product_subcategories_table thead tr').each(function(rowIndex, tr) {
                        
                        $(tr).find('th').each(function(cellIndex, th) {

                            if($(th).text().trim() !== "Action")
                            {
                                tableData += $(th).text().trim() + '\t';
                            }

                        });
                        tableData += '\n';
                    });
                    
                    var application = {!! json_encode($productapp) !!};
                    var applicationArray = [];
                    
                    $.each(application, function(key, data) {
                        applicationArray[data.id] = data.Name;
                    })
                    
                    $(data).each(function(index, item) {
                        if (item.Description == null)
                        {
                            item.Description == ""
                        }

                        tableData += applicationArray[item.ProductApplicationId] + '\t' + item.Name + '\t' + item.Description + '\n';
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

        $('#product_subcategories_table').tablesorter({
            theme: "bootstrap"
        })
    })
</script>
@endsection
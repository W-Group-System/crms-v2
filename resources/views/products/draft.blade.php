@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
                Product List (Draft)
            <button type="button" class="btn btn-sm  btn-primary" name="add_product" id="add_product" data-toggle="modal" data-target="#formProduct">Add Product</button>
        </h4>
            <form method="GET" class="custom_form mb-3" enctype="multipart/form-data">
                <div class="row height d-flex justify-content-end align-items-end">
                    <div class="col-md-3">
                        <div class="search">
                            <i class="ti ti-search"></i>
                            <input type="text" class="form-control" placeholder="Search Product" name="search" value="{{$search}}"> 
                            <button class="btn btn-sm btn-info">Search</button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover mt-3" id="draft_table">
                    <thead>
                        <tr>
                            <th width="10%">Action</th>
                            <th width="25%">DDW Number</th>
                            <th width="25%">Code</th>
                            <th width="25%">Created By</th>
                            <th width="15%">Date Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $p)
                            <tr>
                                <td>
                                    <div>
                                        <a href="{{url('view_draft_product/'.$p->id)}}" type="submit" class="btn btn-info btn-sm" title="View Products">
                                            <i class="ti-eye"></i>
                                        </a>
        
                                        <button class="btn btn-secondary btn-sm archiveProducts" type="button" title="Archived" data-id="{{$p->id}}">
                                            <i class="ti-archive"></i>
                                        </button>
                                    </div>

                                    <div>
                                        <button class="btn btn-success btn-sm newProducts" type="button" title="Add new products" data-id="{{$p->id}}">
                                            <i class="ti-plus"></i>
                                        </button>

                                        <button class="btn btn-sm btn-warning" title="edit" type="button" data-toggle="modal" data-target="#formProduct-{{$p->id}}">
                                            <i class="ti-pencil"></i>
                                        </button>
                                    </div>
                                </td>
                                <td>{{$p->ddw_number}}</td>
                                <td>{{$p->code}}</td>
                                <td>
                                    {{isset($p->userByUserId->full_name)? $p->userByUserId->full_name : $p->userById->full_name}}
                                </td>
                                <td>{{date('M d, Y', strtotime($p->created_at))}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {!! $products->appends(['search' => $search])->links() !!}
                @php
                    $total = $products->total();
                    $currentPage = $products->currentPage();
                    $perPage = $products->perPage();
                    
                    $from = ($currentPage - 1) * $perPage + 1;
                    $to = min($currentPage * $perPage, $total);
                @endphp
                <p class="mt-3">{{"Showing {$from} to {$to} of {$total} entries"}}</p>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="formProduct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_product" action="{{url('new_product')}}">
                    <span id="form_result"></span>
                    @csrf
                    <div class="form-group">
                        <label for="name">DDW Number</label>
                        <input type="text" class="form-control" id="ddw_number" name="ddw_number" placeholder="Enter DDW Number" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Product Code</label>
                        <input type="text" class="form-control" id="code" name="code" placeholder="Enter Product Code" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Reference Number</label>
                        <input type="text" class="form-control" id="reference_no" name="reference_no" placeholder="Enter Reference Number" required>
                    </div>
                    <div class="form-group">
                        <label>Type</label>
                        <select class="form-control js-example-basic-single" name="type" id="type" style="position: relative !important" title="Select Type" required>
                            <option value="" disabled selected>Select Type</option>
                            <option value="1">Pure</option>
                            <option value="2">Blend</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Application</label>
                        <select class="form-control js-example-basic-single" name="application_id" id="application_id" style="position: relative !important" title="Select Application" required>
                            <option value="" disabled selected>Select Application</option>
                            @foreach($product_applications as $product_application)
                                <option value="{{ $product_application->id }}">{{ $product_application->Name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Application Subcategory</label>
                        <select class="form-control js-example-basic-single" name="application_subcategory_id" id="application_subcategory_id" style="position: relative !important" title="Select Subcategory" required>
                            <option value="" disabled selected>Select Subcategory</option>
                            @foreach($product_subcategories as $product_subcategory)
                                <option value="{{ $product_subcategory->id }}">{{ $product_subcategory->Name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">Product Origin</label>
                        <input type="text" class="form-control" id="product_origin" name="product_origin" placeholder="Enter Product Origin" required>
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

@foreach ($products as $p)
    @include('products.edit_draft')
@endforeach

<script>
    $(document).ready(function() {
        $(".newProducts").on('click', function() {
            var id = $(this).data();

            $.ajax({
                type: "POST",
                url: "{{url('add_to_new_products')}}",
                data: id,
                headers: 
                {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res)
                {
                    Swal.fire({
                        icon: 'success',
                        title: res.message,
                    }).then(() => {
                        location.reload();
                    })
                }
            })
        })

        $(".archiveProducts").on('click', function() {
            var id = $(this).data();

            $.ajax({
                type: "POST",
                url: "{{url('add_to_archive_products')}}",
                data: id,
                headers: 
                {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res)
                {
                    Swal.fire({
                        icon: 'success',
                        title: res.message,
                    }).then(() => {
                        location.reload();
                    })
                }
            })
        })
    })
</script>
@endsection
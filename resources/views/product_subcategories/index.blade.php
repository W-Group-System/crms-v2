@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            Product Subcategories List
            <button type="button" class="btn btn-md btn-primary" data-toggle="modal" data-target="#formProductSubcategories">Add Product Subcategories</button>
            </h4>
            <form method="GET" class="custom_form mb-3" enctype="multipart/form-data">
                <div class="row height d-flex justify-content-end align-items-end">
                    <div class="col-md-3">
                        <div class="search">
                            <i class="ti ti-search"></i>
                            <input type="text" class="form-control" placeholder="Search Product Application Subcategories" name="search" value="{{$search}}"> 
                            <button class="btn btn-sm btn-info">Search</button>
                        </div>
                    </div>
                </div>
            </form>
            <table class="table table-striped table-bordered table-hover table-bordered" id="product_subcategories_table" width="100%">
                <thead>
                    <tr>
                        <th width="25%">Application</th>
                        <th width="30%">Subcategory</th>
                        <th width="35%">Description</th>
                        <th width="10%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($subcategories as $sub)
                        <tr>
                            <td>{{$sub->application->Name}}</td>
                            <td>{{$sub->Name}}</td>
                            <td>{{$sub->Description}}</td>
                            <td>
                                <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#formProductSubcategories-{{$sub->id}}" title="Edit">
                                    <i class="ti-pencil"></i>
                                </button>

                                <button class="btn btn-danger btn-sm deleteSub" title="Delete" data-id="{{$sub->id}}">
                                    <i class="ti-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {!! $subcategories->appends(['search' => $search])->links() !!}

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
                <form method="POST" id="form_product_subcategories" enctype="multipart/form-data" action="{{ route('product_subcategories.store') }}">
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

<script>
    $(document).ready(function() {
        $('.deleteSub').on('click', function() {
            var id = $(this).data('id')
            
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
        })
    })
</script>
@endsection
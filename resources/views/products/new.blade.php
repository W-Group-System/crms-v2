@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">Product List (New)</h4>
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
                <table class="table table-striped table-bordered table-hover" id="product_table" width="100%">
                    <thead>
                        <tr>
                            <th width="10%">Action</th>
                            <th width="15%">DDW Number</th>
                            <th width="30%">Code</th>
                            <th width="30%">Created By</th>
                            <th width="15%">Date Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>
                                    <div>
                                        <a href="{{url('view_product/'.$product->id)}}" type="button" class="btn btn-sm btn-info" target="_blank" title="View product">
                                            <i class="ti-eye"></i>
                                        </a>
    
                                        <button class="btn btn-secondary btn-sm archiveProducts" type="button" title="Archieved" data-id="{{$product->id}}">
                                            <i class="ti-archive"></i>
                                        </button>
                                    </div>
    
                                    <div>
                                        <button class="btn btn-success btn-sm currentProducts" type="button" title="Add current products" data-id="{{$product->id}}">
                                            <i class="ti-plus"></i>
                                        </button>

                                        <button class="btn btn-warning btn-sm" type="button" title="Edit" data-toggle="modal" data-target="#formProduct-{{$product->id}}">
                                            <i class="ti-pencil"></i>
                                        </button>
                                    </div>
                                </td>
                                <td>{{$product->ddw_number}}</td>
                                <td>{{$product->code}}</td>
                                <td>
                                    @if($product->userByUserId)
                                        {{$product->userByUserId->full_name}}
                                    @endif

                                    @if($product->userById)
                                        {{$product->userById->full_name}}
                                    @endif
                                </td>
                                <td>{{date('M d, Y', strtotime($product->created_at))}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {!! $products->appends(['search' => $search])->links() !!}
            </div>
        </div>
    </div>
</div>

@foreach ($products as $p)
    @include('products.edit_draft')
@endforeach

<script>
    $(document).ready(function() {
        $(".currentProducts").on('click', function() {
            var id = $(this).data();

            $.ajax({
                type: "POST",
                url: "{{url('add_to_current_products')}}",
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
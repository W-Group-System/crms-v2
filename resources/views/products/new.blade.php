@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">Product List (New)</h4>

            <div class="mb-3">
                <button class="btn btn-md btn-outline-info">Copy</button>
                <a href="{{url('export_new_products')}}" class="btn btn-md btn-outline-success">Excel</a>
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
                                    <input type="text" class="form-control" placeholder="Search Product" name="search" value="{{$search}}"> 
                                    <button class="btn btn-sm btn-info">Search</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="product_table" width="100%">
                    <thead>
                        <tr>
                            <th width="8%">Action</th>
                            <th width="15%">DDW Number</th>
                            <th width="32%">Code</th>
                            <th width="30%">Created By</th>
                            <th width="15%">Date Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($products) > 0)
                        @foreach ($products as $product)
                            <tr>
                                <td align="center">
                                    {{-- <a href="{{url('view_new_product/'.$product->id)}}" type="button" class="btn btn-sm btn-info" target="_blank" title="View product">
                                        <i class="ti-eye"></i>
                                    </a> --}}

                                    <button class="btn btn-outline-warning btn-sm" type="button" title="Edit" data-toggle="modal" data-target="#formProduct-{{$product->id}}">
                                        <i class="ti-pencil"></i>
                                    </button>

                                    {{-- <form method="POST" class="d-inline-block" action="{{url('add_to_archive_products')}}">
                                        @csrf

                                        <input type="hidden" name="id" value="{{$product->id}}">
                                        <button class="btn btn-secondary btn-sm archiveProducts" type="button" title="Archive">
                                            <i class="ti-archive"></i>
                                        </button>
                                    </form> --}}

                                    {{-- <form method="POST" class="d-inline-block" action="{{url('add_to_current_products')}}">
                                        @csrf
                                        
                                        <input type="hidden" name="id" value="{{$product->id}}">
                                        <button class="btn btn-success btn-sm currentProducts" type="button" title="Move to current products">
                                            <i class="ti-plus"></i>
                                        </button>
                                    </form> --}}

                                </td>
                                <td>{{$product->ddw_number}}</td>
                                <td>
                                    <a href="{{url('view_new_product/'.$product->id)}}">{{$product->code}}</a>
                                </td>
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
                        @else
                        <tr>
                            <td colspan="5" class="text-center">No data available.</td>
                        </tr>
                        @endif
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

@foreach ($products as $p)
    @include('products.edit_draft')
@endforeach

<script>
    $(document).ready(function() {
        $('.table').tablesorter({
            theme: "bootstrap"
        })
        
        $(".currentProducts").on('click', function() {
            var form = $(this).closest('form');

            Swal.fire({
                title: "Are you sure?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Current"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit()
                }
            });
        })

        $(".archiveProducts").on('click', function() {
            var form = $(this).closest('form');

            Swal.fire({
                title: "Are you sure?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Archive"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit()
                }
            });
        })

        $("[name='entries']").on('change', function() {
            $(this).closest('form').submit()
        })
    })
    
</script>
@endsection
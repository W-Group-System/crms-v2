@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">Product List (Current)</h4>
            <form method="GET" class="custom_form mb-3" enctype="multipart/form-data">
                <div class="row height d-flex justify-content-start align-items-start">
                    <div class="col-md-3">
                        Application Filter :
                        <select data-placeholder="Choose Application" name="application_filter" class="form-control form-control-sm js-example-basic-single">
                            <option value="">-Application-</option>
                            @foreach ($application as $a)
                                <option value="{{$a->id}}" {{$application_filter == $a->id?'selected':''}}>{{$a->Name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        Material Filter :
                        <select data-placeholder="Choose Material" name="material_filter" class="form-control form-control-sm js-example-basic-single">
                            <option value="">-Material-</option>
                            @foreach ($raw_material as $r)
                                <option value="{{$r->id}}" {{$material_filter == $r->id?'selected':''}}>{{$r->Name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        &nbsp;
                        <div>
                            <button class="btn btn-md btn-primary" type="submit">Filter</button>
                        </div>
                    </div>
                </div>
            </form>

            <div class="mb-3">
                <button type="button" class="btn btn-md btn-info">Copy</button>
                <a href="{{url('export_current_products')}}" class="btn btn-md btn-success">Excel</a>
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
                                    <input type="text" class="form-control" placeholder="Search Products" name="search" value="{{$search}}"> 
                                    <button class="btn btn-sm btn-info">Search</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover table-bordered" id="product_table" width="100%">
                    <thead>
                        <tr>
                            <th width="10%">Action</th>
                            <th width="30%">Code</th>
                            <th width="20%">Date Created</th>
                            <th width="30%">Application</th>
                            <th width="30%">RMC(USD)</th>
                            <th width="30%">RMC(EUR)</th>
                            <th width="30%">RMC(PHP)</th>
                            {{-- <!-- <th width="10%">Price</th> --> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($products) > 0)
                        @foreach ($products as $product)
                            @php
                                $usd = rmc($product->productMaterialComposition, $product->id);
                                $eur = usdToEur($usd);
                                $php = usdToPhp($usd);
                            @endphp
                            <tr>
                                <td>
                                    <a href="{{url('view_product/'.$product->id)}}" type="button" class="btn btn-sm btn-info" target="_blank" title="View product" target="_blank">
                                        <i class="ti-eye"></i>
                                    </a>

                                    <form action="{{url('add_to_archive_products')}}" class="d-inline-block" method="post">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$product->id}}">

                                        <button class="btn btn-secondary btn-sm archiveProducts" type="button" title="Archived">
                                            <i class="ti-archive"></i>
                                        </button>
                                    </form>
                                </td>
                                <td>{{$product->code}}</td>
                                <td>
                                    @if($product->userByUserId)
                                        {{$product->userByUserId->full_name}}
                                    @endif

                                    @if($product->userById)
                                        {{$product->userById->full_name}}
                                    @endif
                                </td>
                                <td>{{$product->application->Name}}</td>
                                <td>{{$usd}}</td>
                                <td>{{$eur}}</td>
                                <td>{{$php}}</td>
                            </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="7" class="text-center">No data available.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>

                {!! $products->appends(['search' => $search, 'application_filter' => $application_filter, 'material_filter' => $material_filter, 'entries' => $entries])->links() !!}

                @php
                    $total = $products->total();
                    $currentPage = $products->currentPage();
                    $perPage = $products->perPage();
                    
                    $from = ($currentPage - 1) * $perPage + 1;
                    $to = min($currentPage * $perPage, $total);
                @endphp

                <p  class="mt-3">{{"Showing {$from} to {$to} of {$total} entries"}}</p>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $(".archiveProducts").on('click', function() {
            var form = $(this).closest('form');

            Swal.fire({
                title: "Are you sure?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Archived"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        })

        $("[name='entries']").on('change', function() {
            $(this).closest('form').submit()
        })
    })
</script>
@endsection
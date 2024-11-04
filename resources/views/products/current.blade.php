@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card rounded-0 border border-1 border-primary">
        <div class="card-header bg-primary text-white">
            <p class="font-weight-bold mb-0">List of Products (Current)</p>
        </div>
        <div class="card-body">
            {{-- <h4 class="card-title d-flex justify-content-between align-items-center">Product List (Current)</h4> --}}
            <form method="GET" class="custom_form mb-3" enctype="multipart/form-data">
                <div class="row height d-flex justify-content-start align-items-start">
                    <div class="col-md-3">
                        <label>Application Filter :</label>
                        <select data-placeholder="Choose Application" name="application_filter" class="form-control form-control-sm js-example-basic-single">
                            <option value="">-Application-</option>
                            @foreach ($application as $a)
                                <option value="{{$a->id}}" {{$application_filter == $a->id?'selected':''}}>{{$a->Name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Material Filter :</label>
                        <select data-placeholder="Choose Material" name="material_filter" class="form-control form-control-sm js-example-basic-single">
                            <option value="">-Material-</option>
                            @foreach ($raw_material as $r)
                                <option value="{{$r->id}}" {{$material_filter == $r->id?'selected':''}}>{{$r->Name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3" style="margin-top: 30px;">
                        <button class="btn btn-md btn-primary" type="submit">Filter</button>
                    </div>
                </div>
            </form>

            <div class="mb-3">
                <button class="btn btn-md btn-outline-info">Copy</button>
                <a href="{{url('export_current_products')}}" class="btn btn-md btn-outline-success">Excel</a>
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
                            {{-- <th width="10%">Action</th> --}}
                            <th width="30%">Code</th>
                            <th width="30%">Created By</th>
                            <th width="20%">Date Created</th>
                            <th width="10%">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($products) > 0)
                        @foreach ($products as $product)
                            <tr>
                                {{-- <td>
                                    <a href="{{url('view_product/'.$product->id)}}" type="button" class="btn btn-sm btn-info" target="_blank" title="View product" target="_blank">
                                        <i class="ti-eye"></i>
                                    </a>

                                    <form method="POST" class="d-inline-block" action="{{url('add_to_archive_products')}}">
                                        @csrf 

                                        <input type="hidden" name="id" value="{{$product->id}}">
                                        <button class="btn btn-secondary btn-sm archiveProducts" type="button" title="Archived" data-id="{{$product->id}}">
                                            <i class="ti-archive"></i>
                                        </button>
                                    </form>
                                </td> --}}
                                <td>
                                    <a href="{{url('view_product/'.$product->id)}}">{{$product->code}}</a>
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
                                <td>
                                    @php
                                        $usd = rmc($product->productMaterialComposition, $product->id);
                                    @endphp

                                    USD {{$usd}}
                                </td>
                            </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="4" class="text-center">No data available.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>

                {!! $products->appends(['search' => $search, 'application_filter' => $application_filter, 'material_filter' => $material_filter])->links() !!}

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

{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script> 

<script>
    $(document).ready(function(){
        $('#product_table').DataTable({
            serverSide: true,
            ajax: {
                url: "{{ route('product.current') }}"
            },
            columns: [
                {
                    data: 'code',
                    name: 'code'
                },
                {
                    data: 'user_full_name',
                    name: 'user_full_name'
                },
                {
                    data: 'created_at',
                    name: 'created_at',
                    render: function(data, type, row) {
                        return moment(data).format('YYYY-MM-DD'); // Format as desired
                    }
                },
                // {
                //     data: '',
                //     name: ''
                // },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false
                }
            ],
            columnDefs: [
                {
                    targets: 0, // Target the Title column
                    render: function(data, type, row) {
                        return '<div style="white-space: break-spaces; width: 100%;">' + data + '</div>';
                    }
                }
            ]
        });
    });
</script> --}}

<script>
    $(document).ready(function() {
        $('.table').tablesorter({
            theme: "bootstrap"
        })

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
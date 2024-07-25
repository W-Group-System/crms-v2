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
            <form method="GET" class="custom_form mb-3" enctype="multipart/form-data">
                <div class="row height d-flex justify-content-end align-items-end">
                    <div class="col-md-3">
                        <div class="search">
                            <i class="ti ti-search"></i>
                            <input type="text" class="form-control" placeholder="Search Products" name="search" value="{{$search}}"> 
                            <button class="btn btn-sm btn-info">Search</button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered" id="product_table" width="100%">
                    <thead>
                        <tr>
                            <th width="10%">Action</th>
                            <th width="30%">Code</th>
                            <th width="30%">Created By</th>
                            <th width="20%">Date Created</th>
                            <!-- <th width="10%">Price</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>
                                    <a href="{{url('view_product/'.$product->id)}}" type="button" class="btn btn-sm btn-info" target="_blank" title="View product" target="_blank">
                                        <i class="ti-eye"></i>
                                    </a>

                                    <button class="btn btn-warning btn-sm archiveProducts" type="button" title="Archieved" data-id="{{$product->id}}">
                                        <i class="ti-archive"></i>
                                    </button>
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
                                <td>{{date('M d, Y', strtotime($product->created_at))}}</td>
                            </tr>
                        @endforeach
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
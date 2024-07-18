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
                            <th width="15%">DDW Number</th>
                            <th width="30%">Code</th>
                            <th width="30%">Created By</th>
                            <th width="15%">Date Created</th>
                            <th width="10%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
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
                                <td>
                                    <a href="{{url('view_product/'.$product->id)}}" type="button" class="btn btn-sm btn-info" target="_blank" title="View product">
                                        <i class="ti-eye"></i>
                                    </a>

                                    <button class="btn btn-warning btn-sm archiveProducts" type="button" title="Archieved" data-id="{{$product->id}}">
                                        <i class="ti-archive"></i>
                                    </button>
    
                                    <button class="btn btn-success btn-sm currentProducts" type="button" title="Add current products" data-id="{{$product->id}}">
                                        <i class="ti-plus"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {!! $products->appends(['search' => $search])->links() !!}
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
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('product.new') }}"
            },
            columns: [
                {
                    data: 'ddw_number',
                    name: 'ddw_number'
                },
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
                {
                    data: 'action',
                    name: 'action',
                    width: '10%',
                    orderable: false
                }
            ],
            columnDefs: [
                {
                    targets: 3, // Target the Title column
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
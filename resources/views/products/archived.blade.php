@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">Product List (Archived)</h4>

            <div class="mb-3">
                <button class="btn btn-md btn-info">Copy</button>
                <a href="{{url('export_archive_products')}}" class="btn btn-md btn-success">Export</a>
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
                    <form method="GET" class="custom_form mb-3" enctype="multipart/form-data" >
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
                <table class="table table-striped table-bordered table-hover table-bordered" id="archived_table" width="100%">
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
                        @if(count($products) > 0)
                        @foreach ($products as $product)
                            <tr>
                                <td>
                                    <a href="{{url('view_archive_products/'.$product->id)}}" type="button" class="btn btn-sm btn-info" target="_blank" title="View product" target="_blank">
                                        <i class="ti-eye"></i>
                                    </a>
    
                                    <form method="POST" action="{{url('add_to_draft_products')}}" class="d-inline-block">
                                        {{csrf_field()}}

                                        <input type="hidden" name="id" value="{{$product->id}}">
                                        <button class="btn btn-sm btn-success draftProduct" type="button" title="Add draft products" data-id="{{$product->id}}">
                                            <i class="ti-plus"></i>
                                        </button>
                                    </form>

                                    <form method="POST" class="d-inline-block" action="{{url('delete_product')}}">
                                        {{csrf_field()}}

                                        <input type="hidden" name="id" value="{{$product->id}}">
                                        <button class="btn btn-sm btn-danger deleteProduct" type="button" title="Delete" data-id="{{$product->id}}">
                                            <i class="ti-trash"></i>
                                        </button>
                                    </form>
                                    
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
                        @else
                        <tr>
                            <td colspan="5" class="text-center">No data available.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>

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
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Region</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 20px">
                <h5 style="margin: 0">Are you sure you want to delete this data?</h5>
            </div>
            <div class="modal-footer" style="padding: 0.6875rem">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" name="yes_button" id="yes_button" class="btn btn-danger">Yes</button>
            </div>
        </div>
    </div>
</div>

{{-- <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap4.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.bootstrap4.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

<script>
    $(document).ready(function(){
        dataTableInstance = new DataTable('#archived_table', {
            destroy: true, // Destroy and re-initialize DataTable on each call
            pageLength: 25,
            layout: {
                topStart: {
                    buttons: [
                        'copy',
                        {
                            extend: 'excel',
                            text: 'Export to Excel',
                            filename: 'Product (Archived)', // Set the custom file name
                            title: 'Product (Archived)' // Set the custom title
                        }
                    ]
                }
            },
            ajax: {
                url: "{{ route('product.archived') }}",
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

        $(document).on('click', '.delete', function(){
            product_id = $(this).attr('id');
            $('#confirmModal').modal('show');
            $('.modal-title').text("Delete Product");
        });    

        $('#yes_button').click(function(){
            $.ajax({
                url: "{{ url('delete_product') }}/" + product_id, 
                method: "GET",
                beforeSend:function(){
                    $('#yes_button').text('Deleting...');
                },
                success:function(data)
                {
                    setTimeout(function(){
                        $('#confirmModal').modal('hide');
                        if (dataTableInstance) {
                            dataTableInstance.ajax.reload();
                        }
                    }, 2000);
                }
            })
        });
    });
</script> --}}

<script>
$(document).ready(function() {
    $('.table').tablesorter({
        theme: "bootstrap"
    })

    $(".draftProduct").on('click', function() {
        var form = $(this).closest('form');

        Swal.fire({
            title: "Are you sure?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Draft"
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit()
            }
        });
    })

    $(".deleteProduct").on('click', function() {
        var form = $(this).closest('form');

        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
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
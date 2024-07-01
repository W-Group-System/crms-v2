@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            Product List (Current)
            <button type="button" class="btn btn-md btn-primary" name="add_product" id="add_product">Add Product</button>
            </h4>
            <table class="table table-striped table-hover" id="product_table" width="100%">
                <thead>
                    <tr>
                        <th width="15%">DDW Number</th>
                        <th width="30%">Code</th>
                        <th width="30%">Reference Number</th>
                        <!-- <th width="20%">Product Origin</th> -->
                        <th width="15%">Type</th>
                        <th width="10%">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script> 

<script>
    $(document).ready(function(){
        $('#product_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('product.index') }}"
            },
            columns: [
                {
                    data: 'ddw_number',
                    name: 'ddw_number',
                    width: '15%'
                },
                {
                    data: 'code',
                    name: 'code',
                    width: '20%'
                },
                {
                    data: 'reference_no',
                    name: 'reference_no',
                    width: '20%'
                },
                // {
                //     data: 'product_origin',
                //     name: 'product_origin',
                //     width: '20%'
                // },
                {
                    data: 'type',
                    name: 'type',
                    width: '15%',
                    render: function(data, type, row) {
                        // Display "Local" for type 1 and "International" for type 2
                        return data == 1 ? 'Local' : 'International';
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
</script>
@endsection
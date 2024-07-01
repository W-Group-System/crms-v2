@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">Product List (New)</h4>
            <table class="table table-striped table-hover" id="product_table" width="100%">
                <thead>
                    <tr>
                        <th width="15%">DDW Number</th>
                        <th width="30%">Code</th>
                        <th width="30%">Created By</th>
                        <th width="15%">Date Created</th>
                        <th width="10%">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
</script>
@endsection
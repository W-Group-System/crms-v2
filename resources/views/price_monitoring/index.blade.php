@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            Price Monitoring List
            <button type="button" class="btn btn-md btn-primary" name="add_price_monitoring" id="add_price_monitoring">Add Price Monitoring</button>
            </h4>
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="price_monitoring_table">
                    <thead>
                        <tr>
                            <th>Price Request #</th>
                            <th>Date Created</th>
                            <th>Client Name</th>
                            <th>BDE</th>
                            <th>Status</th>
                            <th>Progress</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script> 

<script>
    $(document).ready(function(){
        $('#price_monitoring_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('price_monitoring.index') }}"
            },
            columns: [
                {
                    data: 'PrfNumber',
                    name: 'PrfNumber'
                },
                {
                    data: 'DateRequested',
                    name: 'DateRequested'
                },
                {
                    data: 'ClientId',
                    name: 'ClientId'
                },
                {
                    data: 'PrimarySalesPersonId',
                    name: 'PrimarySalesPersonId'
                },
                {
                    data: 'Status',
                    name: 'Status'
                },
                {
                    data: 'Progress',
                    name: 'Progress'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false
                }
            ]
        });
    });
</script>
@endsection
@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">Client List (Archived)</h4>
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="client_archived">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Industry</th>
                            <th>Buyer Code</th>
                            <th>Name</th>
                            <!-- <th>Account Manager</th> -->
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
        $('#client_archived').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('client.archived') }}"
            },
            columns: [
                {
                    data: 'Type',
                    name: 'Type',
                    render: function(data, type, row) {
                        // Display "Local" for type 1 and "International" for type 2
                        return data == 1 ? 'Local' : 'International';
                    }
                },
                {
                    data: 'industry.Name',
                    name: 'industry.Name'
                },
                {
                    data: 'BuyerCode',
                    name: 'BuyerCode'
                },
                {
                    data: 'Name',
                    name: 'Name'
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
                    targets: [0, 1, 2, 3], 
                    render: function(data, type, row) {
                        return '<div style="white-space: break-spaces; width: 100%;">' + data + '</div>';
                    }
                }
            ]
        });
    });
</script>
@endsection

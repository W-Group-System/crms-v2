@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            Client List (Current)
            <!-- <button type="button" class="btn btn-md btn-primary" name="add_client" id="add_client">Add Client</button> -->
            </h4>
            <form method="GET" class="custom_form mb-3" enctype="multipart/form-data">
                <div class="height d-flex justify-content-between align-items-between">
                    <a href="{{url('export_current_client')}}" class="btn btn-md btn-success mb-1">Export</a>
                    <div class="col-md-5">
                        <div class="search">
                            <i class="ti ti-search"></i>
                            <input type="text" class="form-control" placeholder="Search Client" name="search" value="{{$search}}"> 
                            <button class="btn btn-sm btn-info">Search</button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="client_table" width="100%">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>
                                Type
                                <a href="{{ route('client.index', ['search' => $search, 'sort' => 'Type', 'direction' => request('sort') == 'Type' && request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'Type' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a>
                            </th>
                            <th>
                                Industry
                                <a href="{{ route('client.index', ['search' => $search, 'sort' => 'ClientIndustryId', 'direction' => request('sort') == 'ClientIndustryId' && request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'ClientIndustryId' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a>
                            </th>
                            <th>
                                Buyer Code
                                <a href="{{ route('client.index', ['search' => $search, 'sort' => 'BuyerCode', 'direction' => request('sort') == 'BuyerCode' && request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'BuyerCode' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a>
                            </th>
                            <th>
                                Name
                                <a href="{{ route('client.index', ['search' => $search, 'sort' => 'Name', 'direction' => request('sort') == 'Name' && request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'Name' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a>
                            </th>
                            <th>
                                Account Manager
                                <a href="{{ route('client.index', ['search' => $search, 'sort' => 'PrimaryAccountManagerId', 'direction' => request('sort') == 'PrimaryAccountManagerId' && request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'PrimaryAccountManagerId' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($clients->count() > 0)
                            @foreach($clients as $client)
                            <tr>
                                <td>
                                    <button type="button" class="btn btn-info btn-sm" title="View Client" onclick="viewClient({{ $client->id }})">
                                        <i class="ti-eye"></i>
                                    </button>
                                    <button type="button" name="delete" class="achivedClient btn btn-sm btn-secondary" data-id="{{$client->id}}"><i class="ti ti-archive"></i></button>
                                </td>
                                <td>
                                    @if($client->Type == "1")
                                        <label>Local</label>
                                    @else
                                        <label>International</label>
                                    @endif
                                </td>
                                <td>{{$client->industry->Name ?? 'N/A'}}</td>
                                <td>{{$client->BuyerCode ?? 'N/A'}}</td>
                                <td>{{$client->Name ?? 'N/A'}}</td>
                                <td>
                                    {{ $client->userByUserId->full_name ?? $client->userById->full_name ?? 'N/A' }} / 
                                    {{ $client->userByUserId2->full_name ?? $client->userById2->full_name ?? 'N/A' }}
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center">No matching records found</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            {!! $clients->appends(['search' => $search, 'sort' => request('sort'), 'direction' => request('direction')])->links() !!}
            @php
                $total = $clients->total();
                $currentPage = $clients->currentPage();
                $perPage = $clients->perPage();
                
                $from = ($currentPage - 1) * $perPage + 1;
                $to = min($currentPage * $perPage, $total);
            @endphp
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>Showing {{ $from }} to {{ $to }} of {{ $total }} entries</div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="formClient" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Client</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_client" enctype="multipart/form-data" action="{{ route('store') }}">
                    <span id="form_result"></span>
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Buyer Code</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Primary Account Manager</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">SAP Code</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Secondary Account Manager</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Company Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Trade Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">TIN</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Payment Term</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Type</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Telephone</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">FAX</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Website</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name"></label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name">
                    </div>
                    <div class="form-group">
                        <label for="name">Description</label>
                        <input type="text" class="form-control" id="description" name="description" placeholder="Enter Description">
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="action" id="action" value="Save">
                        <input type="hidden" name="hidden_id" id="hidden_id">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" name="action_button" id="action_button" class="btn btn-success" value="Save">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $(".achivedClient").on('click', function() {
            var clientId = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "You want to archive this client!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, confirmed it!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "{{ url('archived_client') }}/" + clientId,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(function() {
                                location.reload();
                            });
                        }
                    });
                }
            });
        });
    });

    function viewClient(clientId) {
        window.location.href = "{{ url('view_client') }}/" + clientId;
    }
</script>
@endsection

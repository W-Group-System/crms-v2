@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card border border-1 border-primary rounded-0">
        <div class="card-header bg-primary rounded-0">
            <p class="card-title m-0 text-white font-weight-bold">
                List of Clients (Current)
            </p>
        </div>
        <div class="card-body">
            <div class="row height d-flex ">
                <div class="col-md-6 mt-2 mb-2">
                    <a href="#" id="copy_client_btn" class="btn btn-md btn-outline-info mb-1">Copy</a>
                    <a href="{{url('export_current_client')}}" class="btn btn-md btn-outline-success mb-1">Excel</a>
                </div>
                <div class="col-md-6 mt-2 mb-2 text-right">
                    <a href="{{ url('client/create2') }}" id="newClient"><button class="btn btn-md btn-outline-primary"><i class="ti ti-plus"></i>&nbsp;New</button></a>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <span>Show</span>
                    <form method="GET" class="d-inline-block">
                        <select name="number_of_entries" class="form-control" onchange="this.form.submit()">
                            <option value="10" @if($entries == 10) selected @endif>10</option>
                            <option value="25" @if($entries == 25) selected @endif>25</option>
                            <option value="50" @if($entries == 50) selected @endif>50</option>
                            <option value="100" @if($entries == 100) selected @endif>100</option>
                        </select>
                    </form>
                    <span>Entries</span>
                </div>
                <div class="col-lg-6">
                    <form method="GET" class="custom_form mb-3" enctype="multipart/form-data">
                        <div class="row height d-flex justify-content-end align-items-end">
                            <div class="col-lg-10">
                                <div class="search">
                                    <i class="ti ti-search"></i>
                                    <input type="text" class="form-control" placeholder="Search Client" name="search" value="{{$search}}"> 
                                    <button class="btn btn-sm btn-info">Search</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="client_table" width="100%">
                    <thead>
                        <tr>
                            <!-- <th>Action</th> -->
                            <th>
                                Type
                                <!-- <a href="{{ route('client.index', ['search' => $search, 'sort' => 'Type', 'direction' => request('sort') == 'Type' && request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'Type' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a> -->
                            </th>
                            <th>
                                Industry
                                <!-- <a href="{{ route('client.index', ['search' => $search, 'sort' => 'ClientIndustryId', 'direction' => request('sort') == 'ClientIndustryId' && request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'ClientIndustryId' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a> -->
                            </th>
                            <th>
                                Buyer Code
                                <!-- <a href="{{ route('client.index', ['search' => $search, 'sort' => 'BuyerCode', 'direction' => request('sort') == 'BuyerCode' && request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'BuyerCode' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a> -->
                            </th>
                            <th>
                                Name
                                <!-- <a href="{{ route('client.index', ['search' => $search, 'sort' => 'Name', 'direction' => request('sort') == 'Name' && request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'Name' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a> -->
                            </th>
                            <th>
                                Account Manager
                                <!-- <a href="{{ route('client.index', ['search' => $search, 'sort' => 'PrimaryAccountManagerId', 'direction' => request('sort') == 'PrimaryAccountManagerId' && request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'PrimaryAccountManagerId' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a> -->
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($currentClient->count() > 0)
                            @foreach($currentClient as $client)
                            <tr>
                                <!-- <td>
                                    <button type="button" class="btn btn-info btn-sm" title="View Client" onclick="viewClient({{ $client->id }})">
                                        <i class="ti-eye"></i>
                                    </button>
                                    <button type="button" name="delete" class="achivedClient btn btn-sm btn-secondary" data-id="{{$client->id}}"><i class="ti ti-archive"></i></button>
                                </td> -->
                                <td>
                                    @if($client->Type == "1")
                                        <label>Local</label>
                                    @else
                                        <label>International</label>
                                    @endif
                                </td>
                                <td>{{$client->industry->Name ?? 'N/A'}}</td>
                                <td>{{$client->BuyerCode ?? 'N/A'}}</td>
                                <td>
                                    <a href="{{ url('view_client/'. $client->id) }}" onclick="viewClient({{ $client->id }})">
                                        {{ $client->Name ?? 'N/A' }}
                                    </a>
                                </td>
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
            {!! $currentClient->appends(['search' => $search, 'sort' => request('sort'), 'direction' => request('direction')])->links() !!}
            @php
                $total = $currentClient->total();
                $currentPage = $currentClient->currentPage();
                $perPage = $currentClient->perPage();
                
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
    $(document).ready(function() {
        $('.table').tablesorter({
            theme: "bootstrap"
        })

        $(".achivedClient").on('click', function() {
            var clientId = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "You want to archive this client!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, confirm it!',
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

        $('#copy_client_btn').click(function() {
            $.ajax({
                url: "{{ route('client.index') }}",
                type: 'GET',
                data: {
                    search: "{{ request('search') }}",
                    sort: "{{ request('sort') }}",
                    direction: "{{ request('direction') }}",
                    fetch_all: true
                },
                success: function(data) {
                    var tableData = '';

                    // Add the table header
                    $('#client_table thead tr').each(function(rowIndex, tr) {
                        $(tr).find('th').each(function(cellIndex, th) {
                            tableData += $(th).text().trim() + '\t'; // Add a tab space
                        });
                        tableData += '\n'; // New line after each row
                    });

                    // Add the table body from the fetched data
                    $(data).each(function(index, item) {
                        tableData += (item.Action ?? '') + '\t' +
                                    (item.Type === 1 ? 'Local' : 'International') + '\t' + 
                                    (item.industry?.Name ?? 'N/A') + '\t' + 
                                    (item.BuyerCode ?? 'N/A') + '\t' + 
                                    (item.Name ?? 'N/A') + '\t' + 
                                    ((item.user_by_user_id?.full_name ?? item.user_by_user_id?.full_name) ?? 'N/A') + ' / ' +
                                    ((item.user_by_user_id2?.full_name ?? item.user_by_user_id2?.full_name) ?? 'N/A') + '\n';
                    });

                    // Create a temporary textarea element to hold the text
                    var tempTextArea = $('<textarea>');
                    $('body').append(tempTextArea);
                    tempTextArea.val(tableData).select();
                    document.execCommand('copy');
                    tempTextArea.remove(); // Remove the temporary element

                    // Notify the user
                    Swal.fire({
                        icon: 'success',
                        title: 'Copied!',
                        text: 'Table data has been copied to the clipboard.',
                        timer: 1500,
                        showConfirmButton: false
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

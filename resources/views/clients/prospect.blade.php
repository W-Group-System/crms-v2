@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">Client List (Prospect) 
            </h4>
            <div class="row height d-flex ">
                <div class="col-md-6 mt-2 mb-2">
                    <a href="#" id="copy_prospect_btn" class="btn btn-md btn-outline-info mb-1">Copy</a>
                    <a href="{{url('export_prospect_client')}}" class="btn btn-md btn-outline-success mb-1">Excel</a>
                </div>
                <div class="col-md-6 mt-2 mb-2 text-right">
                    <a href="{{ url('client/create') }}" id="newClient"><button class="btn btn-md btn-outline-primary"><i class="ti ti-plus"></i>&nbsp;New</button></a>
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
                                    <input type="text" class="form-control" placeholder="Search Client" name="search" value="{{ $search }}">
                                    <button class="btn btn-sm btn-info">Search</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- <form method="GET" class="custom_form mb-3" enctype="multipart/form-data">
                <div class="height d-flex justify-content-between align-items-between">
                    <a href="{{url('export_prospect_client')}}" class="btn btn-md btn-success mb-1">Export</a>
                    <div class="col-md-5">
                        <div class="search">
                            <i class="ti ti-search"></i>
                            <input type="text" class="form-control" placeholder="Search Client" name="search" value="{{ $search }}">
                            <button class="btn btn-sm btn-info">Search</button>
                        </div>
                    </div>
                </div>
            </form> -->
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="prospect_table">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>
                                Type
                                <!-- <a href="{{ route('client.prospect', ['search' => $search, 'sort' => 'Type', 'direction' => request('sort') == 'Type' && request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'Type' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a> -->
                            </th>
                            <th>
                                Industry
                                <!-- <a href="{{ route('client.prospect', ['search' => $search, 'sort' => 'ClientIndustryId', 'direction' => request('sort') == 'ClientIndustryId' && request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'ClientIndustryId' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a> -->
                            </th>
                            <th>
                                Buyer Code
                                <!-- <a href="{{ route('client.prospect', ['search' => $search, 'sort' => 'BuyerCode', 'direction' => request('sort') == 'BuyerCode' && request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'BuyerCode' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a> -->
                            </th>
                            <th>
                                Name
                                <!-- <a href="{{ route('client.prospect', ['search' => $search, 'sort' => 'Name', 'direction' => request('sort') == 'Name' && request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'Name' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a> -->
                            </th>
                            <th>
                                Account Manager
                                <!-- <a href="{{ route('client.prospect', ['search' => $search, 'sort' => 'PrimaryAccountManagerId', 'direction' => request('sort') == 'PrimaryAccountManagerId' && request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'PrimaryAccountManagerId' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a> -->
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($prospectClient->count() > 0)
                            @foreach($prospectClient as $client)
                                <tr>
                                    <td align="center">
                                        <!-- <button type="button" class="btn btn-info btn-sm" title="View Client" onclick="viewClient({{ $client->id }})">
                                            <i class="ti-eye"></i>
                                        </button> -->
                                        <a href="{{ url('/edit_client/' . $client->id) }}" class="btn btn-sm btn-outline-warning"><i class="ti ti-pencil"></i></a>
                                        <!-- <button type="button" class="achivedClient btn btn-sm btn-secondary" data-id="{{$client->id}}"><i class="ti ti-archive"></i></button> -->
                                    </td>
                                    <td>{{ $client->Type == "1" ? 'Local' : 'International' }}</td>
                                    <td>{{ $client->industry->Name ?? 'N/A' }}</td>
                                    <td>{{ $client->BuyerCode ?? 'N/A' }}</td>
                                    <td>
                                        <a href="javascript:void(0);" onclick="viewClient({{ $client->id }})">
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
            {!! $prospectClient->appends(['search' => $search, 'sort' => request('sort'), 'direction' => request('direction')])->links() !!}
            @php
                $total = $prospectClient->total();
                $currentPage = $prospectClient->currentPage();
                $perPage = $prospectClient->perPage();

                $from = ($currentPage - 1) * $perPage + 1;
                $to = min($currentPage * $perPage, $total);
            @endphp
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>Showing {{ $from }} to {{ $to }} of {{ $total }} entries</div>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function(){
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

        $('#copy_prospect_btn').click(function() {
            $.ajax({
                url: "{{ route('client.prospect') }}",
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
                    $('#prospect_table thead tr').each(function(rowIndex, tr) {
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
        
        // $('.deleteClient').on('click', function(){
        //     var clientId = $(this).data('id');
        //     if(confirm('Are you sure you want to archive this client?')){
        //         $.ajax({
        //             url: "{{ url('archive_client') }}/" + clientId,
        //             type: 'POST',
        //             data: {
        //                 "_token": "{{ csrf_token() }}",
        //                 "_method": "PUT"
        //             },
        //             success: function(response) {
        //                 alert('Client archived successfully!');
        //                 location.reload();
        //             },
        //             error: function(response) {
        //                 alert('Error archiving client.');
        //             }
        //         });
        //     }
        // });
    });

    function viewClient(clientId) {
        window.location.href = "{{ url('view_client') }}/" + clientId;
    }
</script>
@endsection

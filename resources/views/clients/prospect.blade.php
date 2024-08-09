@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">Client List (Prospect)
                <a href="{{ url('client/create') }}"><button class="btn btn-md btn-primary">Add Client</button></a>
            </h4>
            <form method="GET" class="custom_form mb-3" enctype="multipart/form-data">
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
            </form>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="client_prospect">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Type</th>
                            <th>Industry</th>
                            <th>Buyer Code</th>
                            <th>Name</th>
                            <th>Account Manager</th>
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
                                        <a href="{{ url('/edit_client/' . $client->id) }}" class="btn btn-sm btn-primary"><i class="ti ti-pencil"></i></a>
                                        <button type="button" class="achivedClient btn btn-sm btn-secondary" data-id="{{$client->id}}"><i class="ti ti-archive"></i></button>
                                    </td>
                                    <td>{{ $client->Type == "1" ? 'Local' : 'International' }}</td>
                                    <td>{{ $client->industry->Name ?? 'N/A' }}</td>
                                    <td>{{ $client->BuyerCode ?? 'N/A' }}</td>
                                    <td>{{ $client->Name ?? 'N/A' }}</td>
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
            {!! $clients->appends(['search' => $search])->links() !!}
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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

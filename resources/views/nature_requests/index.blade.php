@extends('layouts.header')
@section('css')
    <link rel="stylesheet" href="{{asset('css/sweetalert2.min.css')}}">
@endsection
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            Nature of Request List
            <button type="button" class="btn btn-md btn-primary" data-toggle="modal" data-target="#AddNatureRequest">Add Nature of Request</button>
            </h4>
           <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="nature_request_table" width="100%">
                <thead>
                    <tr>
                        <th width="10%">Action</th>
                        <th width="35%">Name</th>
                        <th width="55%">Description</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($natureRequests as $natureRequest)
                        <tr>
                            <td>
                                <button type="button" class="btn btn-sm btn-warning btn-outline"
                                    data-target="#editnatureRequest{{ $natureRequest->id }}" data-toggle="modal" title='Edit natureRequest'>
                                    <i class="ti-pencil"></i>
                                </button>   
                                <button type="button" class="btn btn-sm btn-danger btn-outline" onclick="confirmDelete({{ $natureRequest->id }})" title='Delete Supplementary'>
                                    <i class="ti-trash"></i>
                                </button>  
                            </td>
                            <td>{{ $natureRequest->Name }}</td>
                            <td>{{ $natureRequest->Description }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
           </div>
           {!! $natureRequests->appends(['search' => $search])->links() !!}
        @php
            $total = $natureRequests->total();
            $currentPage = $natureRequests->currentPage();
            $perPage = $natureRequests->perPage();
            
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
    function confirmDelete(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('/delete_nature_request') }}/" + id, 
                    method: 'DELETE',
                    data: {
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire(
                            'Deleted!',
                            'The record has been deleted.',
                            'success'
                        ).then(() => {
                            location.reload(); 
                        });
                    },
                    error: function() {
                        Swal.fire(
                            'Error!',
                            'Something went wrong.',
                            'error'
                        );
                    }
                });
            }
        });
    }
</script>
@include('nature_requests.create')
@foreach ($natureRequests as $natureRequest)
@include('nature_requests.edit')
@endforeach
@endsection
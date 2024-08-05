@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            Price Monitoring List
            <button type="button" class="btn btn-md btn-primary" name="add_price_monitoring" data-toggle="modal" data-target="#AddPriceMonitoring">Add Price Monitoring</button>
            </h4>
            <form method="GET" class="custom_form mb-3" enctype="multipart/form-data">
                <div class="row height d-flex justify-content-end align-items-end">
                    <div class="col-md-5">
                        <div class="search">
                            <i class="ti ti-search"></i>
                            <input type="text" class="form-control" placeholder="Search User" name="search" value="{{$search}}"> 
                            <button class="btn btn-sm btn-info">Search</button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="price_monitoring_table">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Price Request #</th>
                            <th>Date Created</th>
                            <th>Client Name</th>
                            <th>Status</th>
                            <th>Progress</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( $price_monitorings as $priceMonitoring)
                        <tr>
                            <td align="center">
                                <a href="{{ url('price_monitoring/view/' . $priceMonitoring->id) }}" class="btn btn-sm btn-info btn-outline" title="View Price Request"><i class="ti-eye"></i></a>
                                <button type="button" class="btn btn-sm btn-warning"
                                    data-target="#editPriceRequest{{ $priceMonitoring->id }}" data-toggle="modal" title='Edit Price Request'>
                                    <i class="ti-pencil"></i>
                                </button>  
                                <button type="button" class="btn btn-sm btn-danger delete-btn" onclick="confirmDelete({{ $priceMonitoring->id }})" title='Delete Request'>
                                    <i class="ti-trash"></i>
                                </button>
                            </td>
                            <td>{{ optional($priceMonitoring)->PrfNumber }}</td>
                            <td>{{ $priceMonitoring->DateRequested }}</td>
                            <td>{{ optional($priceMonitoring->client)->Name }}</td>
                            <td>
                                @if($priceMonitoring->Status == 10)
                                        Open
                                    @elseif($priceMonitoring->Status == 30)
                                        Closed
                                    @else
                                        {{ $priceMonitoring->Status }}
                                    @endif
                            </td>
                            <td>
                                {{ optional($priceMonitoring->progressStatus)->name }}
                                {{-- @if($priceMonitoring->Progress == 10)
                                        For Approval
                                    @elseif($priceMonitoring->Progress == 30)
                                        Waiting For Disposition
                                    @else
                                        {{ $priceMonitoring->Progress }}
                                    @endif --}}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {!! $price_monitorings->appends(['search' => $search])->links() !!}
                @php
                    $total = $price_monitorings->total();
                    $currentPage = $price_monitorings->currentPage();
                    $perPage = $price_monitorings->perPage();
    
                    $from = ($currentPage - 1) * $perPage + 1;
                    $to = min($currentPage * $perPage, $total);
                @endphp
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>Showing {{ $from }} to {{ $to }} of {{ $total }} entries</div>
                </div>
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
                    url: "{{ url('/delete_price_request') }}/" + id, 
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
@include('price_monitoring.create')
@foreach ( $price_monitorings as $priceMonitoring)
@include('price_monitoring.edit')
@endforeach
@endsection
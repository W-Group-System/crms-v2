@extends('layouts.header')
@section('content')

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            New Base Price List
            </h4>
            <form method="GET" class="custom_form mb-3" enctype="multipart/form-data">
                <div class="row height d-flex justify-content-end align-items-end">
                    <div class="col-md-3">
                        <div class="search">
                            <i class="ti ti-search"></i>
                            <input type="text" class="form-control" placeholder="Search User" name="search" value="{{$search}}"> 
                            <button class="btn btn-sm btn-info">Search</button>
                        </div>
                    </div>
                </div>
            </form>
            
            <button type="button" class="btn btn-md btn-primary" data-toggle="modal" data-target="#createNewBasePrice">Add New Base Price</button>
            <button type="button" class="btn btn-md btn-warning" data-toggle="modal" data-target="#editAllNewBasePrice">Edit New Base Price</button>

            <div class="table-responsive mt-3">
                <table class="table table-striped table-hover table-bordered" id="base_price_table" width="100%">
                    <thead>
                        <tr>
                            <th>Material</th>
                            <th>Price</th>
                            <th>Created By</th>
                            <th>Date Created</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( $newBasePrice as $newBase)
                        <tr>
                            <td>{{ $newBase->productMaterial->Name }}</td>
                            <td>{{ $newBase->Price }}</td>
                            <td>{{ $newBase->userCreated->full_name }}</td>
                            <td>{{ date('M d, Y', strtotime($newBase->CreatedDate)) }}</td>
                            <td>
                                @if($newBase->Status == 1)
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($newBase->Status == 2)
                                    <span class="badge badge-danger">Disapproved</span>
                                @elseif($newBase->Status == 3)
                                    <span class="badge badge-success">Approved</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-warning"
                                    data-target="#editBase{{ $newBase->Id }}" data-toggle="modal" title='Edit New Base Price'>
                                    <i class="ti-pencil"></i>
                                </button>  
                                <button type="button" class="btn btn-sm btn-success approve-btn"  data-id="{{ $newBase->Id }}">
                                    <i class="ti-thumb-up"></i>
                                </button> 
                                {{-- <a href="approveNewBasePrice/{{ $newBase->Id }}" class="btn btn-success" title="Approve New Base Price">
                                    <i class="ti-thumb-up"></i></a> --}}
                            </td>
                        </tr>
                            
                        @endforeach
                    </tbody>
                </table>

                {!! $newBasePrice->appends(['search' => $search])->links() !!}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="formBusinessType" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Region</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_business_type" enctype="multipart/form-data" action="{{ route('business_type.store') }}">
                    <span id="form_result"></span>
                    @csrf
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="Name" name="Name" placeholder="Enter Name">
                    </div>
                    <div class="form-group">
                        <label for="name">Description</label>
                        <input type="text" class="form-control" id="Description" name="Description" placeholder="Enter Description">
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script> 
<script src="{{ asset('js/sweetalert2.min.js') }}"></script>

<script>

    $(document).ready(function(){
        @if(session('error'))
            Swal.fire({
                title: "Error",
                text: "{{ session('error') }}",
                icon: "error",
            });
        @endif
        $('.approve-btn').on('click', function(){
            var id = $(this).data('id'); 
            Swal.fire({
                title: "Do you want to approve this base price?",
                showDenyButton: true,
                showCancelButton: true,
                icon: "info",
                confirmButtonText: "Approve",
                denyButtonText: `Disapprove`
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'approveNewBasePrice/' + id, 
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}', 
                            status: 'approved' 
                        },
                        success: function(response) {
                            Swal.fire("Base price approved!", "", "success");
                            location.reload()
                        },
                        error: function(xhr, status, error) {
                            Swal.fire("Error approving base price", "", "error");
                            console.error(xhr.responseText);
                        }
                    });
                } else if (result.isDenied) {
                    $.ajax({
                        url: 'approveNewBasePrice/' + id, 
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}', 
                            status: 'disapproved' 
                        },
                        success: function(response) {
                            Swal.fire("Base price disapproved!", "", "success");
                            location.reload()
                        },
                        error: function(xhr, status, error) {
                            Swal.fire("Error approving base price", "", "error");
                            console.error(xhr.responseText);
                        }
                    });
                }
            });
        });
    });
</script>
@include('base_prices.create_new_base_price')
@include('base_prices.edit_all_new_base_price')
@foreach ( $newBasePrice as $newBase)
@include('base_prices.edit_new_base_price')
@endforeach
@endsection
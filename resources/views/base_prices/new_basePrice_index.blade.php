@extends('layouts.header')
@section('content')

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            New Base Price List
            <button type="button" class="btn btn-md btn-primary" data-toggle="modal" data-target="#createNewBasePrice">Add New Base Price</button>
            <button type="button" class="btn btn-md btn-warning" data-toggle="modal" data-target="#editAllNewBasePrice">Edit New Base Price</button>
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
            <table class="table table-striped table-hover" id="base_price_table" width="100%">
                <thead>
                    <tr>
                        <th width="20%">Action</th>
                        <th width="20%">Material</th>
                        <th width="20%">Price</th>
                        <th width="20%">Created By</th>
                        <th width="20%">Date Created</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ( $newBasePrice as $newBase)
                    <tr>
                        <td align="center">
                            <button type="button" class="btn btn-sm btn-warning"
                                data-target="#editBase{{ $newBase->Id }}" data-toggle="modal" title='Edit New Base Price'>
                                <i class="ti-pencil"></i>
                            </button>  
                            <button type="button" class="btn btn-sm btn-success approve-btn"  data-id="{{ $newBase->Id }}">
                                <i class="ti-thumb-up"></i>
                            </button> 
                            <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{ $newBase->Id }}" title='Delete Base Price'>
                                <i class="ti-trash"></i>
                            </button>
                            {{-- <a href="approveNewBasePrice/{{ $newBase->Id }}" class="btn btn-success" title="Approve New Base Price">
                                <i class="ti-thumb-up"></i></a> --}}
                        </td>
                        <td>{{ $newBase->productMaterial->Name }}</td>
                        <td>{{ $newBase->Price }}</td>
                        <td>{{ $newBase->userCreated->full_name }}</td>
                        <td>{{ $newBase->CreatedDate ?? $newBase->created_at }}</td>
                    </tr>
                        
                    @endforeach
                </tbody>
            </table>
            {!! $newBasePrice->appends(['search' => $search])->links() !!}            
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

        $('.delete-btn').on('click', function() {
        var id = $(this).data('id');
        var $row = $(this).closest('tr'); 

        if (confirm('Are you sure you want to delete this base price?')) {
            $.ajax({
                url: 'base-price/' + id,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'  
                },
                success: function(response) {
                    if (response.success) {
                        $row.remove();  
                        alert(response.message);
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    alert('An error occurred while deleting the base price.');
                }
            });
        }
    });
    });
</script>
@include('base_prices.create_new_base_price')
@include('base_prices.edit_all_new_base_price')
@foreach ( $newBasePrice as $newBase)
@include('base_prices.edit_new_base_price')
@endforeach
@endsection
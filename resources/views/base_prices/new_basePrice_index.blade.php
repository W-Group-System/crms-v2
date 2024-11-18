@extends('layouts.header')
@section('content')

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            New Base Price List
                <div align="right">
                    <button type="button" class="btn btn-md btn-outline-primary" data-toggle="modal" data-target="#createNewBasePrice">Add New Base Price</button>
                    <button type="button" class="btn btn-md btn-outline-warning" data-toggle="modal" data-target="#editAllNewBasePrice">Edit New Base Price</button>
                </div>
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
            <div class="card-title d-flex justify-content-start mt-3">
                @if (checkIfItsManagerOrSupervisor(auth()->user()->role) == "yes")
                @if(authCheckIfItsRnd(auth()->user()->department_id))
                <button type="button" id="bulk_approve" class="btn btn-sm btn-outline-success mr-2">Bulk Approve</button>
                @endif 
                @endif
                <button type="button" id="bulk_delete" class="btn btn-sm btn-outline-danger">Bulk Delete</button>
            </div>
            <table class="table table-striped table-bordered table-hover" id="base_price_table" width="100%">
                <thead>
                    <tr>
                        <th width="8%"><input type="checkbox" id="select_all"> Select All</th>
                        <th width="10%">Action</th>
                        <th width="27%">Material</th>
                        <th width="20%">Price</th>
                        <th width="20%">Created By</th>
                        <th width="15%">Date Created</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ( $newBasePrice as $newBase)
                    <tr>
                        <td align="center"><input type="checkbox" class="item-checkbox" value="{{ $newBase->Id }}"></td>
                        <td align="center">
                            <button type="button" class="btn btn-sm btn-outline-warning"
                                data-target="#editBase{{ $newBase->Id }}" data-toggle="modal" title='Edit New Base Price'>
                                <i class="ti-pencil"></i>
                            </button>  
                            @if (checkIfItsManagerOrSupervisor(auth()->user()->role) == "yes")
                            @if(authCheckIfItsRnd(auth()->user()->department_id))
                            <button type="button" class="btn btn-sm btn-outline-success approve-btn"  data-id="{{ $newBase->Id }}">
                                <i class="ti-thumb-up"></i>
                            </button> 
                            @endif 
                            @endif
                            <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="{{ $newBase->Id }}" title='Delete Base Price'>
                                <i class="ti-trash"></i>
                            </button>
                        </td>
                        <td>{{ optional($newBase->productMaterial)->Name }}</td>
                        <td>{{ $newBase->Price }}</td>
                        <td>{{ $newBase->userCreated->full_name }}</td>
                        <td>{{ $newBase->CreatedDate ?? $newBase->created_at }}</td>
                    </tr>
                        
                    @endforeach
                </tbody>
            </table>
            {!! $newBasePrice->appends(['search' => $search])->links() !!}
            @php
                $total = $newBasePrice->total();
                $currentPage = $newBasePrice->currentPage();
                $perPage = $newBasePrice->perPage();

                $from = ($currentPage - 1) * $perPage + 1;
                $to = min($currentPage * $perPage, $total);
            @endphp
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>Showing {{ $from }} to {{ $to }} of {{ $total }} entries</div>
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
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" name="action_button" id="action_button" class="btn btn-outline-success" value="Save">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .swal-wide {
        width: 400px;
    }
</style>

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
                icon: "question",
                confirmButtonText: "Approve",
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                customClass: 'swal-wide',
                reverseButtons: true
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
                } 
            });
        });

        $('.delete-btn').on('click', function() {
            var id = $(this).data('id');
            var $row = $(this).closest('tr'); 

            Swal.fire({
                title: 'Are you sure?',
                // text: "You won't be able to revert this!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                customClass: 'swal-wide',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'base-price/' + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                $row.remove();
                                Swal.fire(
                                    'Deleted!',
                                    response.message,
                                    'success'
                                );
                            } else {
                                Swal.fire(
                                    'Error!',
                                    response.message,
                                    'error'
                                );
                            }
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Error!',
                                'An error occurred while deleting the base price.',
                                'error'
                            );
                        }
                    });
                }
            });
        });

        $('#select_all').click(function() {
            var isChecked = $(this).prop('checked');
            $('.item-checkbox').prop('checked', isChecked);
        });

        $('#bulk_approve').click(function() {
            var selectedIds = getSelectedIds();
            if (selectedIds.length === 0) {
                Swal.fire("No items selected", "", "info");
                return;
            }
            Swal.fire({
                title: "Approve selected base prices?",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                icon: "question",
                confirmButtonText: "Approve",
                cancelButtonText: "Cancel",
                customClass: 'swal-wide',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'bulkApproveNewBasePrice',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            ids: selectedIds,
                        },
                        success: function(response) {
                            Swal.fire("Base prices approved!", "", "success");
                            location.reload();
                        },
                        error: function(xhr, status, error) {
                            Swal.fire("Error approving base prices", "", "error");
                            console.error(xhr.responseText);
                        }
                    });
                }
            });
        });

        $('#bulk_delete').click(function() {
            var selectedIds = getSelectedIds();
            if (selectedIds.length === 0) {
                Swal.fire("No items selected", "", "info");
                return;
            }
            Swal.fire({
                title: "Are you sure you want to delete selected base prices?",
                // text: "This action cannot be undone.",
                icon: "question",
                showCancelButton: true,                
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: "Delete",
                cancelButtonText: "Cancel",
                customClass: 'swal-wide',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'bulkDeleteBasePrice',
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}',
                            ids: selectedIds
                        },
                        success: function(response) {
                            Swal.fire("Base prices deleted!", "", "success");
                            location.reload();
                        },
                        error: function(xhr, status, error) {
                            Swal.fire("Error deleting base prices", "", "error");
                            console.error(xhr.responseText);
                        }
                    });
                }
            });
        });

        function getSelectedIds() {
            return $('.item-checkbox:checked').map(function() {
                return $(this).val();
            }).get();
        }
    });
</script>
@include('base_prices.create_new_base_price')
@include('base_prices.edit_all_new_base_price')
@foreach ( $newBasePrice as $newBase)
@include('base_prices.edit_new_base_price')
@endforeach
@endsection
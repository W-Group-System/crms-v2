@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            CRR Priority List
            <button type="button" class="btn btn-md btn-primary" data-toggle="modal" data-target="#AddCrrPriority">Add CRR Priority</button>
            </h4>
            <table class="table table-striped table-hover" id="crr_priority_table" width="100%">
                <thead>
                    <tr>
                        <th width="30%">Name</th>
                        <th width="30%">Description</th>
                        <th width="30%">Days</th>
                        <th width="10%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($crrPriorities as $crrPriority)
                        <tr>
                            <td>
                                <button type="button" class="btn btn-sm btn-warning btn-outline"
                                    data-target="#editcrrPriority{{ $crrPriority->id }}" data-toggle="modal" title='Edit Project Name'>
                                    <i class="ti-pencil"></i>
                                </button>   
                                <button type="button" class="btn btn-sm btn-danger btn-outline" onclick="confirmDelete({{ $crrPriority->id }})" title='Delete Supplementary'>
                                    <i class="ti-trash"></i>
                                </button>  
                            </td>
                            <td>{{ $crrPriority->Name }}</td>
                            <td>{{ $crrPriority->Description }}</td>
                            <td>{{ $crrPriority->Days }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {!! $crrPriorities->appends(['search' => $search])->links() !!}
        @php
            $total = $crrPriorities->total();
            $currentPage = $crrPriorities->currentPage();
            $perPage = $crrPriorities->perPage();
            
            $from = ($currentPage - 1) * $perPage + 1;
            $to = min($currentPage * $perPage, $total);
        @endphp
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>Showing {{ $from }} to {{ $to }} of {{ $total }} entries</div>
        </div>
    </div>
</div>

<div class="modal fade" id="formCrrPriority" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add CRR Priority</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Nature of Request</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 20px">
                <h5 style="margin: 0">Are you sure you want to delete this data?</h5>
            </div>
            <div class="modal-footer" style="padding: 0.6875rem">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" name="delete_crr_priority" id="delete_crr_priority" class="btn btn-danger">Yes</button>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script> 

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
                    url: "{{ url('/delete_crr_priority') }}/" + id, 
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
@include('crr_priorities.create')
@foreach ($crrPriorities as $crrPriority)
@include('crr_priorities.edit')
@endforeach
@endsection
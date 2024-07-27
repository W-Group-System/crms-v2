@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            Categorization List
            <button type="button" class="btn btn-md btn-primary" data-toggle="modal" data-target="#AddCategorization">Add Categorization</button>
            </h4>
            <form method="GET" class="custom_form mb-3" enctype="multipart/form-data">
                <div class="row height d-flex justify-content-end align-items-end">
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
            <table class="table table-striped table-bordered table-hove" id="project_name_table" width="100%">
                <thead>
                    <tr>
                        <th width="25%">Action</th>
                        <th width="35%">Name</th>
                        <th width="40%">Description</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categorizations as $categorization)
                        <tr>
                            <td>
                                <button type="button" class="btn btn-sm btn-warning btn-outline"
                                    data-target="#editCategorization{{ $categorization->id }}" data-toggle="modal" title='Edit Categorization'>
                                    <i class="ti-pencil"></i>
                                </button>   
                                <button type="button" class="btn btn-sm btn-danger btn-outline" onclick="confirmDelete({{ $categorization->id }})" title='Delete Supplementary'>
                                    <i class="ti-trash"></i>
                                </button>  
                            </td>
                            <td>{{ $categorization->Name }}</td>
                            <td>{{ $categorization->Description }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
           </div>
           {!! $categorizations->appends(['search' => $search])->links() !!}
            @php
                $total = $categorizations->total();
                $currentPage = $categorizations->currentPage();
                $perPage = $categorizations->perPage();
                
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
                    url: "{{ url('/delete_categorization') }}/" + id, 
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
@include('categorizations.create')
@foreach ($categorizations as $categorization)
@include('categorizations.edit')
@endforeach
@endsection
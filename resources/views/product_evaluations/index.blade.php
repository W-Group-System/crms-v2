@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            Product Evaluation List
            <button type="button" class="btn btn-md btn-primary" name="add_product_evaluation" data-toggle="modal" data-target="#AddProductEvaluation">Add Product Evaluation</button>
            </h4>
            <div class="form-group">
                <form method="GET" >
                    <label>Show : </label>
                    <label class="checkbox-inline">
                        <input name="open" class="activity_status" type="checkbox" value="10" @if($open == 10) checked @endif> Open
                    </label>
                    <label class="checkbox-inline">
                        <input name="close" class="activity_status" type="checkbox" value="30" @if($close == 30) checked @endif> Closed
                    </label>
                    <button type="submit" class="btn btn-sm btn-primary">Filter Status</button>
                </form>
            </div>
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
                <table class="table table-striped table-bordered table-hover" id="product_evaluation_table">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>RPE #</th>
                            <th>Date Created</th>
                            <th>Due Date</th>
                            <th>Client Name</th>
                            <th>Application</th>
                            <th>Recommendation</th>
                            <th>Status</th>
                            <th>Progress</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( $request_product_evaluations as $productEvaluation)
                        <tr>
                            <td align="center">
                                <a href="{{ url('product_evaluation/view/' . $productEvaluation->id) }}" class="btn btn-sm btn-info btn-outline" title="View Request"><i class="ti-eye"></i></a>
                                <button type="button" class="btn btn-sm btn-warning"
                                    data-target="#editRpe{{ $productEvaluation->id }}" data-toggle="modal" title='Edit New RPE'>
                                    <i class="ti-pencil"></i>
                                </button>  
                                <button type="button" class="btn btn-sm btn-danger delete-btn" onclick="confirmDelete({{ $productEvaluation->id }})" title='Delete Request'>
                                    <i class="ti-trash"></i>
                                </button>
                            </td>
                            <td>{{ optional($productEvaluation)->RpeNumber }}</td>
                            <td>{{ $productEvaluation->CreatedDate }}</td>
                            <td>{{ $productEvaluation->DueDate }}</td>
                            <td>{{ optional($productEvaluation->client)->Name }}</td>
                            <td>{{ optional($productEvaluation->product_application)->Name }}</td>
                            <td style="white-space: break-spaces; width: 100%;">{{ optional($productEvaluation)->RpeResult }}</td>
                            <td>
                                @if($productEvaluation->Status == 10)
                                        Open
                                    @elseif($productEvaluation->Status == 30)
                                        Closed
                                    @else
                                        {{ $productEvaluation->Status }}
                                    @endif
                            </td>
                            <td>{{ optional($productEvaluation->progressStatus)->name }}</td>
                            
                        </tr>
                            
                        @endforeach
                    </tbody>
                </table>
                {!! $request_product_evaluations->appends(['search' => $search])->links() !!}
                @php
                    $total = $request_product_evaluations->total();
                    $currentPage = $request_product_evaluations->currentPage();
                    $perPage = $request_product_evaluations->perPage();
    
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
<script src="{{ asset('js/sweetalert2.min.js') }}"></script>


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
                    url: "{{ url('/request_evaluation') }}/" + id, 
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
@include('product_evaluations.create')
@foreach ( $request_product_evaluations as $productEvaluation )
@include('product_evaluations.edit')
@endforeach
@endsection
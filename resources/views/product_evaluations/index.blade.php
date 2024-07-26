@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            Product Evaluation List
            <button type="button" class="btn btn-md btn-primary" name="add_product_evaluation" data-toggle="modal" data-target="#AddProductEvaluation">Add Product Evaluation</button>
            </h4>
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="product_evaluation_table">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>RPE #</th>
                            <th>Date Created</th>
                            <th>Due Date</th>
                            <th>Client Name</th>
                            <th>Application</th>
                            <th>Status</th>
                            <th>Progress</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( $request_product_evaluations as $productEvaluation)
                        <tr>
                            <td align="center">
                                <button type="button" class="btn btn-sm btn-warning"
                                    data-target="#editBase{{ $productEvaluation->id }}" data-toggle="modal" title='Edit New Base Price'>
                                    <i class="ti-pencil"></i>
                                </button>  
                                <button type="button" class="btn btn-sm btn-success approve-btn"  data-id="{{ $productEvaluation->Id }}">
                                    <i class="ti-thumb-up"></i>
                                </button> 
                                <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{ $productEvaluation->Id }}" title='Delete Base Price'>
                                    <i class="ti-trash"></i>
                                </button>
                            </td>
                            <td>{{ optional($productEvaluation)->RpeNumber }}</td>
                            <td>{{ $productEvaluation->CreatedDate }}</td>
                            <td>{{ $productEvaluation->DueDate }}</td>
                            <td>{{ optional($productEvaluation->client)->Name }}</td>
                            <td>{{ optional($productEvaluation->product_application)->Name }}</td>
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
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script> 

<script>
    $(document).ready(function(){
        // $('#product_evaluation_table').DataTable({
        //     processing: true,
        //     serverSide: true,
        //     ajax: {
        //         url: "{{ route('product_evaluation.index') }}"
        //     },
        //     columns: [
        //         {
        //             data: 'rpe_no',
        //             name: 'rpe_no'
        //         },
        //         {
        //             data: 'date_created',
        //             name: 'date_created'
        //         },
        //         {
        //             data: 'due_date',
        //             name: 'due_date'
        //         },
        //         {
        //             data: 'client.Name',
        //             name: 'client.Name'
        //         },
        //         {
        //             data: 'product_application.Name',
        //             name: 'product_application.Name'
        //         },
        //         {
        //             data: 'status',
        //             name: 'status',
        //             render: function(data, type, row) {
        //                 return data == 10 ? 'Open' : 'Closed';
        //             }
        //         },
        //         {
        //             data: 'progress',
        //             name: 'progress'
        //         },
        //         {
        //             data: 'action',
        //             name: 'action',
        //             orderable: false
        //         }
        //     ],
        //     columnDefs: [
        //         {
        //             targets: [0, 1, 2, 3,], // Target column
        //             render: function(data, type, row) {
        //                 return '<div style="white-space: break-spaces; width: 100%;">' + data + '</div>';
        //             }
        //         }
        //     ]
        // });
    });
</script>
@include('product_evaluations.create')
@endsection
@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            Product Evaluation List
            <button type="button" class="btn btn-md btn-primary" id="addRpeBtn" data-toggle="modal" data-target="#AddProductEvaluation">Add Product Evaluation</button>
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
            <div class="row">
                <div class="col-lg-6">
                    <span>Show</span>
                    <form method="GET" class="d-inline-block">
                        <select name="entries" class="form-control">
                            <option value="10" @if($entries == 10) selected @endif>10</option>
                            <option value="25" @if($entries == 25) selected @endif>25</option>
                            <option value="50" @if($entries == 50) selected @endif>50</option>
                            <option value="100" @if($entries == 100) selected @endif>100</option>
                        </select>
                    </form>
                    <span>Entries</span>
                </div>
                <div class="col-lg-6">
                    <form method="GET" class="custom_form mb-3" enctype="multipart/form-data">
                        <div class="row height d-flex justify-content-end align-items-end">
                            <div class="col-md-8">
                                <div class="search">
                                    <i class="ti ti-search"></i>
                                    <input type="text" class="form-control" placeholder="Search Request Product Evaluation" name="search" value="{{$search}}"> 
                                    <button class="btn btn-sm btn-info">Search</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="product_evaluation_table">
                    @if(auth()->user()->role->type == "IS")
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>RPE #</th>
                                <th>Date Created</th>
                                <th>Due Date</th>
                                <th>Client Name</th>
                                <th>Region</th>
                                <th>Country</th>
                                <th>Primary Sales Person</th>
                                <th>Project Name</th>
                                <th>Application</th>
                                <th>Sample Name</th>
                                <th>Manufacturer</th>
                                <th>Date Completed</th>
                                <th>Leadtime</th>
                                <th>Delayed</th>
                                <th>RPE Recommendation</th>
                                <th>Status</th>
                                <th>Progress</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ( $request_product_evaluations as $productEvaluation)
                            <tr>
                                <td align="center">
                                    <a href="{{ url('product_evaluation/view/' . $productEvaluation->id) }}" class="btn btn-sm btn-info btn-outline" title="View Request"><i class="ti-eye"></i></a>

                                    <button type="button" class="btn btn-sm btn-warning editBtn" data-target="#editRpe{{ $productEvaluation->id }}" data-toggle="modal" title='Edit New RPE' @if(auth()->user()->id != $productEvaluation->PrimarySalesPersonId && auth()->user()->user_id != $productEvaluation->PrimarySalesPersonId) disabled @endif data-secondarysales="{{$productEvaluation->SecondarySalesPersonId}}">
                                        <i class="ti-pencil"></i>
                                    </button>

                                    <button type="button" class="btn btn-sm btn-danger delete-btn" onclick="confirmDelete({{ $productEvaluation->id }})" title='Delete Request' @if(auth()->user()->id != $productEvaluation->PrimarySalesPersonId && auth()->user()->user_id != $productEvaluation->PrimarySalesPersonId) disabled @endif>
                                        <i class="ti-trash"></i>
                                    </button>
                                </td>
                                <td>{{ optional($productEvaluation)->RpeNumber }}</td>
                                <td>
                                    @if($productEvaluation->CreatedDate != null)
                                    {{ date('M d, Y h:i A', strtotime($productEvaluation->CreatedDate)) }}
                                    @else
                                    {{date('M d, Y h:i A', strtotime($productEvaluation->created_at))}}
                                    @endif
                                </td>
                                <td>{{ $productEvaluation->DueDate }}</td>
                                <td>{{ optional($productEvaluation->client)->Name }}</td>
                                <td>{{optional($productEvaluation->client->clientregion)->Name}}</td>
                                <td>{{optional($productEvaluation->client->clientcountry)->Name}}</td>
                                <td>
                                    @if($productEvaluation->primarySalesPerson)
                                    {{$productEvaluation->primarySalesPerson->full_name}}
                                    @elseif($productEvaluation->primarySalesPersonById)
                                    {{$productEvaluation->primarySalesPersonById->full_name}}
                                    @endif
                                </td>
                                <td>{{optional($productEvaluation->ProjectName)->Name}}</td>
                                <td>{{ optional($productEvaluation->product_application)->Name }}</td>
                                <td>{{$productEvaluation->SampleName}}</td>
                                <td>{{$productEvaluation->Manufacturer}}</td>
                                <td>
                                    @if($productEvaluation->DateCompleted == null)
                                    N/A
                                    @else 
                                    {{date('M d, Y', strtotime($productEvaluation->DateCompleted))}}
                                    @endif
                                </td>
                                <td></td>
                                <td></td>
                                <td style="white-space: break-spaces; width: 100%;">{{ optional($productEvaluation)->RpeResult }}</td>
                                <td>
                                    @if($productEvaluation->Status == 10)
                                            Open
                                        @elseif($productEvaluation->Status == 30)
                                            Closed
                                        @elseif($productEvaluation->Status == 50)
                                            Cancelled
                                        @else
                                            {{ $productEvaluation->Status }}
                                        @endif
                                </td>
                                <td>{{ optional($productEvaluation->progressStatus)->name }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    @else
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>RPE #</th>
                                <th>Date Created</th>
                                <th>Due Date</th>
                                <th>Client Name</th>
                                <th>Application</th>
                                <th>RPE Recommendation</th>
                                <th>Status</th>
                                <th>Progress</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ( $request_product_evaluations as $productEvaluation)
                            <tr>
                                <td align="center">
                                    <a href="{{ url('product_evaluation/view/' . $productEvaluation->id) }}" class="btn btn-sm btn-info btn-outline" title="View Request"><i class="ti-eye"></i></a>

                                    <button type="button" class="btn btn-sm btn-warning editBtn" data-target="#editRpe{{ $productEvaluation->id }}" data-toggle="modal" title='Edit New RPE' @if(auth()->user()->id != $productEvaluation->PrimarySalesPersonId && auth()->user()->user_id != $productEvaluation->PrimarySalesPersonId) disabled @endif data-secondarysales="{{$productEvaluation->SecondarySalesPersonId}}">
                                        <i class="ti-pencil"></i>
                                    </button>

                                    <button type="button" class="btn btn-sm btn-danger delete-btn" onclick="confirmDelete({{ $productEvaluation->id }})" title='Delete Request' @if(auth()->user()->id != $productEvaluation->PrimarySalesPersonId && auth()->user()->user_id != $productEvaluation->PrimarySalesPersonId) disabled @endif>
                                        <i class="ti-trash"></i>
                                    </button>
                                </td>
                                <td>{{ optional($productEvaluation)->RpeNumber }}</td>
                                <td>
                                    @if($productEvaluation->CreatedDate != null)
                                    {{ date('M d, Y h:i A', strtotime($productEvaluation->CreatedDate)) }}
                                    @else
                                    {{date('M d, Y h:i A', strtotime($productEvaluation->created_at))}}
                                    @endif
                                </td>
                                <td>{{ $productEvaluation->DueDate }}</td>
                                <td>{{ optional($productEvaluation->client)->Name }}</td>
                                <td>{{ optional($productEvaluation->product_application)->Name }}</td>
                                <td style="white-space: break-spaces; width: 100%;">{{ optional($productEvaluation)->RpeResult }}</td>
                                <td>
                                    @if($productEvaluation->Status == 10)
                                            Open
                                        @elseif($productEvaluation->Status == 30)
                                            Closed
                                        @elseif($productEvaluation->Status == 50)
                                            Cancelled
                                        @else
                                            {{ $productEvaluation->Status }}
                                        @endif
                                </td>
                                <td>{{ optional($productEvaluation->progressStatus)->name }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    @endif
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
    
    $(document).ready(function() {
        $('[name="entries"]').on('change', function() {
            $(this).closest('form').submit()
        })

        $("#addRpeBtn").on('click', function() {
            var primarySales = $('[name="PrimarySalesPersonId"]').val();

            refreshSecondaryApprovers(primarySales,"")
        })

        $(".editBtn").on('click', function() {
            var secondarySales = $(this).data('secondarysales');
            var primarySales = $('[name="PrimarySalesPersonId"]').val();

            refreshSecondaryApprovers(primarySales,secondarySales)
        })

        function refreshSecondaryApprovers(primarySales,secondarySales)
        {
            $.ajax({
                type: "POST",
                url: "{{url('refresh_user_approvers')}}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    ps: primarySales,
                },
                success: function(data)
                {
                    setTimeout(() => {
                        $('[name="SecondarySalesPersonId"]').html(data) 
                        $('[name="SecondarySalesPersonId"]').val(secondarySales) 
                    }, 500);
                }
            })
        }
        $(".table").tablesorter({
            theme : "bootstrap",
        })
    })
</script>
@include('product_evaluations.create')
@foreach ( $request_product_evaluations as $productEvaluation )
@include('product_evaluations.edit')
@endforeach
@endsection
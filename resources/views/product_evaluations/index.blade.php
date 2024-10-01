@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            Product Evaluation List
            <button type="button" class="btn btn-md btn-outline-primary" id="addRpeBtn" data-toggle="modal" data-target="#AddProductEvaluation">New</button>
            </h4>
            <div class="form-group">
                <form method="GET" onsubmit="show()">
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
            <div class="mb-3">
                <a href="#" id="copy_btn" class="btn btn-md btn-outline-info">Copy</a>
                <form method="GET" action="{{url('product_evaluation_export')}}" class="d-inline-block">
                    <input type="hidden" name="open" value="{{$open}}">
                    <input type="hidden" name="close" value="{{$close}}">
                    <button type="submit" class="btn btn-outline-success">Export</button>
                </form>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <span>Show</span>
                    <form method="GET" class="d-inline-block" onsubmit="show()">
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
                    <form method="GET" class="custom_form mb-3" enctype="multipart/form-data" onsubmit="show()">
                        <div class="row height d-flex justify-content-end align-items-end">
                            <div class="col-md-10">
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
            <div class="table-responsive" style="overflow: auto; height: 80vh;">
                <table class="table table-striped table-bordered table-hover" id="product_evaluation_table">
                    @if(auth()->user()->role->type == "IS")
                        <thead>
                            <tr>
                                <!-- <th>Action</th> -->
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
                            @if(count($request_product_evaluations) > 0)
                                @foreach ( $request_product_evaluations as $productEvaluation)
                                <tr>
                                    <!-- <td align="center">
                                        <a href="{{ url('product_evaluation/view/' . $productEvaluation->id) }}" class="btn btn-sm btn-outline-info" title="View Request"><i class="ti-eye"></i></a>

                                        <button type="button" class="btn btn-sm btn-outline-warning editBtn" data-primarysales="{{$productEvaluation->PrimarySalesPersonId}}" data-secondarysales="{{$productEvaluation->SecondarySalesPersonId}}" data-target="#editRpe{{ $productEvaluation->id }}" data-toggle="modal" title='Edit New RPE' @if(auth()->user()->id != $productEvaluation->PrimarySalesPersonId && auth()->user()->user_id != $productEvaluation->PrimarySalesPersonId) disabled @endif >
                                            <i class="ti-pencil"></i>
                                        </button>

                                        <button type="button" class="btn btn-sm btn-outline-danger delete-btn" onclick="confirmDelete({{ $productEvaluation->id }})" title='Delete Request' @if(auth()->user()->id != $productEvaluation->PrimarySalesPersonId && auth()->user()->user_id != $productEvaluation->PrimarySalesPersonId) disabled @endif>
                                            <i class="ti-trash"></i>
                                        </button>
                                    </td> -->
                                    <td><a href="{{ url('product_evaluation/view/' . $productEvaluation->id) }}" title="View Product Evaluation">{{ optional($productEvaluation)->RpeNumber }}</a></td>
                                    <td>
                                        @if($productEvaluation->CreatedDate != null)
                                        {{ date('M d, Y h:i A', strtotime($productEvaluation->CreatedDate)) }}
                                        @else
                                        {{date('M d, Y h:i A', strtotime($productEvaluation->created_at))}}
                                        @endif
                                    </td>
                                    <td>{{ $productEvaluation->DueDate }}</td>
                                    <td>{{ optional($productEvaluation->client)->Name }}</td>
                                    <td>{{ optional(optional($productEvaluation->client)->clientregion)->Name }}</td>
                                    <td>{{ optional(optional($productEvaluation->client)->clientcountry)->Name }}</td>
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
                                {{-- @include('product_evaluations.edit') --}}
                                @endforeach
                            @else 
                                <tr>
                                    <td colspan="17" class="text-center">No data available</td>
                                </tr>
                            @endif
                        </tbody>
                    @else
                        <thead>
                            <tr>
                                <!-- <th>Action</th> -->
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
                            @if(count($request_product_evaluations) > 0)
                                @foreach ( $request_product_evaluations as $productEvaluation)
                                <tr>
                                    <!-- <td align="center">
                                        <a href="{{ url('product_evaluation/view/' . $productEvaluation->id) }}" class="btn btn-sm btn-outline-info" title="View Request"><i class="ti-eye"></i></a>

                                        <button type="button" class="btn btn-sm btn-outline-warning editBtn" data-primarysales="{{$productEvaluation->PrimarySalesPersonId}}" data-secondarysales="{{$productEvaluation->SecondarySalesPersonId}}"  data-target="#editRpe{{ $productEvaluation->id }}" data-toggle="modal" title='Edit New RPE' @if(auth()->user()->id != $productEvaluation->PrimarySalesPersonId && auth()->user()->user_id != $productEvaluation->PrimarySalesPersonId) disabled @endif data-secondarysales="{{$productEvaluation->SecondarySalesPersonId}}">
                                            <i class="ti-pencil"></i>
                                        </button>

                                        <button type="button" class="btn btn-sm btn-outline-danger delete-btn" onclick="confirmDelete({{ $productEvaluation->id }})" title='Delete Request' @if(auth()->user()->id != $productEvaluation->PrimarySalesPersonId && auth()->user()->user_id != $productEvaluation->PrimarySalesPersonId) disabled @endif>
                                            <i class="ti-trash"></i>
                                        </button>
                                    </td> -->
                                    <td><a href="{{ url('product_evaluation/view/' . $productEvaluation->id) }}" title="View Product Evaluation">{{ optional($productEvaluation)->RpeNumber }}</a></td>
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
                                    {{-- <td style="white-space: break-spaces; width: 100%;">{{ optional($productEvaluation)->RpeResult }}</td> --}}
                                    <td>
                                        @php
                                            $rpeResult = $productEvaluation->RpeResult;
                                            $pattern = '/\[(.*?)\]/';
                                        
                                            $rpeResultLinked = preg_replace_callback($pattern, function($matches) {
                                                $code = $matches[1];
                                                $productId = getProductIdByCode($code);
                                                
                                                if ($productId != null) {
                                                    return '<a href="'.url('view_product/'.$productId).'">'.$matches[0].'</a>';
                                                }
                                                return $matches[0];
                                            }, $rpeResult);
                                        @endphp  

                                        {!! nl2br($rpeResultLinked) !!}
                                    </td>
                                    <td>
                                        @if($productEvaluation->Status == 10)
                                            <div class="badge badge-success">Open</div>
                                        @elseif($productEvaluation->Status == 30)
                                            <div class="badge badge-warning">Closed</div>
                                        @elseif($productEvaluation->Status == 50)
                                            <div class="badge badge-danger">Cancelled</div>
                                        @endif
                                    </td>
                                    <td>{{ optional($productEvaluation->progressStatus)->name }}</td>
                                </tr>
                                {{-- @include('product_evaluations.edit') --}}
                                @endforeach
                            @else 
                                <tr>
                                    <td colspan="8" class="text-center">No data available</td>
                                </tr>
                            @endif
                        </tbody>
                    @endif
                </table>
                <!-- {!! $request_product_evaluations->appends(['search' => $search])->links() !!} -->
                {{ $request_product_evaluations->appends(request()->query())->links() }}
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

@include('product_evaluations.create')

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

        $(".table").tablesorter({
            theme : "bootstrap",
        })

        $('#copy_btn').click(function() {
            var tableData = '';

            $('#product_evaluation_table thead tr').each(function(rowIndex, tr) {
                $(tr).find('th').each(function(cellIndex, th) {
                    tableData += $(th).text().trim() + '\t';
                });
                tableData += '\n';
            });

            $('#product_evaluation_table tbody tr').each(function(rowIndex, tr) {
                $(tr).find('td').each(function(cellIndex, td) {
                    tableData += $(td).text().trim() + '\t';
                });
                tableData += '\n';
            });

            var tempTextArea = $('<textarea>');
            $('body').append(tempTextArea);
            tempTextArea.val(tableData).select();
            document.execCommand('copy');
            tempTextArea.remove();

            Swal.fire({
                icon: 'success',
                title: 'Copied!',
                text: 'Table data has been copied to the clipboard.',
                timer: 1500,
                showConfirmButton: false
            });
        });

        
        $("#addRpeBtn").on('click', function() {
            var primarySales = $('[name="PrimarySalesPersonId"]').val();
            console.log(primarySales);
            
            refreshSecondaryApprovers(primarySales)
        })

        $('.editBtn').on('click', function() {
            var primarySales = $(this).data('primarysales')
            var secondarySales = $(this).data('secondarysales');

            $.ajax({
                type: "POST",
                url: "{{url('refresh_rpe_secondary_persons')}}",
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
                        // $('[name="SecondarySalesPersonId"]').val(secondarySales) 
                    }, 500);
                }
            })
        })

        $('[name="PrimarySalesPersonId"]').on('change', function() {
            var primarySales = $(this).val();

            refreshSecondaryApprovers(primarySales)
        })

        function refreshSecondaryApprovers(primarySales)
        {
            $.ajax({
                type: "POST",
                url: "{{url('refresh_rpe_secondary_persons')}}",
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
                    }, 500);
                }
            })
        }
    })
</script>
@endsection
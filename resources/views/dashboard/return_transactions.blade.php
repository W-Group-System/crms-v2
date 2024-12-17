@extends('layouts.header')
@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card" style="border: 1px solid #337ab7; border-radius:0;">
            <div class="card-header text-white" style="background-color:#337ab7; border-radius:0;">
                Returned Transactions
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-lg-6">
                        <span>Show</span>
                        <form method="GET" class="d-inline-block" onsubmit="show()">
                            <input type="hidden" name="status" value="{{ request()->get('status', '10') }}">
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
                        <form method="GET" action="{{url()->current()}}" class="custom_form mb-3" enctype="multipart/form-data" onsubmit="show()">
                            <input type="hidden" name="status" value="{{ request()->get('status', '10') }}">
                            <div class="row height d-flex justify-content-end align-items-end">
                                <div class="col-md-10">
                                    <div class="search">
                                        <i class="ti ti-search"></i>
                                        <input type="text" class="form-control" placeholder="Search Transactions" name="search" value="{{$search}}"> 
                                        <button class="btn btn-sm btn-info">Search</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Transaction #</th>
                                <th>Date Created</th>
                                <th>Due Date</th>
                                <th>Client Name</th>
                                <th>Application</th>
                                <th>Primary Sales Person</th>
                                <th>Status</th>
                                <th>Progress</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $isEmpty = count($crrReturned) == 0 && count($rpeReturned) == 0 && count($srfReturned) == 0;
                            @endphp

                            @if($isEmpty)
                                <tr>
                                    <td colspan="8" class="text-center">No data available.</td>
                                </tr>
                            @else
                                @foreach($crrReturned as $crr)
                                    <tr>
                                        <td>
                                            <a href="{{ url('view_customer_requirement/' . $crr->id . '/' . $crr->CrrNumber) }}" title="View Customer Requirements">
                                                {{ $crr->CrrNumber }}
                                            </a>
                                        </td>
                                        <td>{{ $crr->DateCreated }}</td>
                                        <td>{{ $crr->DueDate }}</td>
                                        <td>{{ optional($crr->client)->Name }}</td>
                                        <td>{{ optional($crr->product_application)->Name }}</td>
                                        <td>{{ $crr->primarySalesById->full_name }}</td>
                                        <td>
                                            @if($crr->Status == 10)
                                                <div class="badge badge-success">Open</div>
                                            @elseif($crr->Status == 30)
                                                <div class="badge badge-warning">Closed</div>
                                            @elseif($crr->Status == 50)
                                                <div class="badge badge-danger">Cancelled</div>
                                            @endif
                                        </td>
                                        <td>{{ $crr->progressStatus->name }}</td>
                                    </tr>
                                @endforeach

                                @foreach($rpeReturned as $rpe)
                                    <tr>
                                        <td>
                                            <a href="{{ url('product_evaluation/view/' . $rpe->id . '/' . $rpe->RpeNumber) }}" title="View Product Evaluation">
                                                {{ $rpe->RpeNumber }}
                                            </a>
                                        </td>
                                        <td>
                                            @if($rpe->DateCreated != null)
                                            {{ $rpe->DateCreated }}
                                            @elseif($rpe->CreatedDate != null)
                                            {{ $rpe->CreatedDate }}
                                            @else
                                            {{date('Y-m-d', strtotime($rpe->created_at))}}
                                            @endif
                                        </td>
                                        <td>{{ $rpe->DueDate }}</td>
                                        <td>{{ optional($rpe->client)->Name }}</td>
                                        <td>{{ optional($rpe->product_application)->Name }}</td>
                                        <td>{{ $rpe->primarySalesPerson->full_name ?? $rpe->primarySalesPersonById->full_name}}</td>
                                        <td>
                                            @if($rpe->Status == 10)
                                                <div class="badge badge-success">Open</div>
                                            @elseif($rpe->Status == 30)
                                                <div class="badge badge-warning">Closed</div>
                                            @elseif($rpe->Status == 50)
                                                <div class="badge badge-danger">Cancelled</div>
                                            @endif
                                        </td>
                                        <td>{{ $rpe->progressStatus->name }}</td>
                                    </tr>
                                @endforeach

                                @foreach($srfReturned as $srf)
                                    <tr>
                                        <td>
                                            <a href="{{ url('samplerequest/view/' . $srf->Id . '/' . $srf->SrfNumber) }}" title="View Sample Request">
                                                {{ $srf->SrfNumber }}
                                            </a>
                                        </td>
                                        <td>{{ $srf->DateRequested }}</td>
                                        <td>{{ $srf->DateRequired }}</td>
                                        <td>{{ optional($srf->client)->Name }}</td>
                                        <td>
                                            @foreach ($srf->requestProducts as $product)
                                                {{ optional($product->productApplicationsId)->Name }}<br>
                                            @endforeach
                                        </td>
                                        <td>{{ $srf->primarySalesById->full_name }}</td>
                                        <td>
                                            @if($srf->Status == 10)
                                                <div class="badge badge-success">Open</div>
                                            @elseif($srf->Status == 30)
                                                <div class="badge badge-warning">Closed</div>
                                            @elseif($srf->Status == 50)
                                                <div class="badge badge-danger">Cancelled</div>
                                            @endif
                                        </td>
                                        <td>{{ $srf->progressStatus->name }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    {{ $crrReturned->appends(request()->input())->links() }}
                    {{ $rpeReturned->appends(request()->input())->links() }}
                    {{ $srfReturned->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
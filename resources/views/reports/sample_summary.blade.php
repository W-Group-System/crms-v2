@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            Sample Dispatch Summary
            </h4>
            <div class="row height d-flex">
                <div class="col-md-6 mt-2 mb-2">
                    <a href="#" id="copy_price_btn" style="margin-top: 1.5em" class="btn btn-md btn-outline-info mb-1">Copy</a>
                    <a href="#" id="excel_price_btn" style="margin-top: 1.5em" class="btn btn-md btn-outline-success mb-1">Excel</a>
                </div>
                <!-- <form class="form-inline col-md-6" action="{{ route('reports.sample_dispatch') }}" method="GET">
                    <div class="col-md-6 mt-2 mb-2">
                        <label style="align-items: start;justify-content: left;">From (DD/MM/YYYY):</label>
                        <input type="date" class="form-control" name="from" id="from" value="{{ request('from', now()->startOfMonth()->format('Y-m-d')) }}" style="width: 100%;" onchange="this.form.submit()">
                    </div>
                    <div class="col-md-6 mt-2 mb-2">
                        <label style="align-items: start;justify-content: left;">To (DD/MM/YYYY):</label>
                        <input type="date" class="form-control" name="to" id="to" value="{{ request('to', now()->endOfMonth()->format('Y-m-d')) }}" style="width: 100%;" onchange="this.form.submit()">
                    </div>
                </form> -->
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <span>Show</span>
                    <form method="GET" class="d-inline-block">
                        <input type="hidden" name="from" value="{{ $from }}">
                        <input type="hidden" name="to" value="{{ $to }}">
                        <select name="number_of_entries" class="form-control" onchange="this.form.submit();">
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
                        <input type="hidden" name="from" value="{{ $from }}">
                        <input type="hidden" name="to" value="{{ $to }}">
                        <div class="row height d-flex justify-content-end align-items-end">
                            <div class="col-lg-9">
                                <div class="search">
                                    <i class="ti ti-search"></i>
                                    <input type="text" class="form-control" placeholder="Search Sample Dispatch" name="search" value=""> 
                                    <button class="btn btn-sm btn-info">Search</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="sample_summary_table" width="100%">
                    <thead>
                        <tr>
                            <th>Date of BDE Advise</th>
                            <th>Date of Dispatch</th>
                            <th>SRF No.</th>
                            <th>Company</th>
                            <th>Contact Person</th>
                            <th>Address</th>
                            <th>Quantity</th>
                            <th>In grams</th>
                            <th>Product</th>
                            <th>Lot Number</th>
                            <th>Product Description</th>
                            <!-- <th>Documents</th> -->
                            <th>Courier Company</th>
                            <th>AWB No.</th>
                            <th>ETA</th>
                            <th>Courier Cost</th>
                            <th>Sample Type</th>
                            <th>Issued To</th>
                            <th>Reason for Delayed Dispatch</th>
                            <th>Account Manager</th>
                            <th>Dispatch By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($sample_dispatch->count() > 0) 
                            @foreach($sample_dispatch as $data)
                                <tr>
                                    <td>{{ $data->sampleRequest->DateSampleReceived ?? 'N/A' }}</td>
                                    <td>{{ $data->sampleRequest->DateDispatched ?? 'N/A' }}</td>
                                    <td>{{ $data->sampleRequest->SrfNumber }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>{{ $data->NumberOfPackages }} x {{$data->Quantity}} {{ $data->uom->Name }}</td>
                                    <td></td>
                                    <td>{{ $data->ProductCode }}</td>
                                    <td></td>
                                    <td>{{ $data->ProductDescription ?? 'N/A'}}</td>
                                    <td>{{ $data->sampleRequest->Courier ?? 'N/A' }}</td>
                                    <td>{{ $data->sampleRequest->AwbNumber ?? 'N/A' }}</td>
                                    <td>{{ $data->sampleRequest->Eta ?? 'N/A' }}</td>
                                    <td></td>
                                    <td>
                                        @if($data->sampleRequest->SrfType == 1)
                                            Regular
                                        @elseif($data->sampleRequest->SrfType == 2)
                                            PSS
                                        @else
                                            CSS
                                        @endif
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <!-- <td>{{ $data->primarySalesPerson->full_name ?? $data->primarySalesById->full_name ?? 'N/A' }}</td> -->
                                    <td></td>
                                </tr>
                            @endforeach
                        @else 
                            <tr>
                                <td colspan="8" class="text-center">No matching records found</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.header')
@section('content')
<style>
    .form-header {
    align-items: center;
}

.header-label {
    padding: 50px 0px;
    font-weight: bold;
}

.form-divider {
    flex-grow: 1;
    border: none;
    border-top: 1px solid black;
}
</style>
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            Sample Request List
            <button type="button" class="btn btn-md btn-primary" data-toggle="modal" data-target="#formSampleRequest">Add Sample Request</button>
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
            @if(auth()->user()->department_id == 38)
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="sample_request_table">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>SRF #</th>
                            <th>Date Requested</th>
                            <th>Date Required</th>
                            <th>Client Name</th>
                            <th>Application</th>
                            <th>Status</th>
                            <th>Progress</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sampleRequests as $srf)
                        <tr>
                            <td align="center">
                                <a href="{{ url('samplerequest/view/' . $srf->Id) }}" class="btn btn-sm btn-info btn-outline" title="View Request"><i class="ti-eye"></i></a>
                                <button type="button" id="editSrf{{ $srf->Id }}" class="btn btn-sm btn-warning btn-outline"
                                    data-target="#edit{{ $srf->Id }}" data-toggle="modal" title='Edit SRF'>
                                    <i class="ti-pencil"></i>
                                </button>    
                            </td>
                                <td>{{ $srf->SrfNumber }}</td>
                                <td>{{ !empty($srf->DateRequested) ? date('m/d/Y H:i' , strtotime($srf->DateRequested)) : '00/00/0000' }}</td>
                                <td>{{ !empty($srf->DateRequired) ? date('m/d/Y', strtotime($srf->DateRequired)) : '00/00/0000' }}</td>
                                <td>{{ optional($srf->client)->Name }}</td>
                               <td>{{ optional($srf->productApplicationsId)->Name }}</td>
                               <td>
                                    @if($srf->Status == 10)
                                        Open
                                    @elseif($srf->Status == 30)
                                        Closed
                                    @else
                                        {{ $srf->Status }}
                                    @endif
                                </td>
                                <td>{{ optional($srf->progressStatus)->name }}</td>
                                
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {!! $sampleRequests->appends(['search' => $search])->links() !!}
                @php
                    $total = $sampleRequests->total();
                    $currentPage = $sampleRequests->currentPage();
                    $perPage = $sampleRequests->perPage();
    
                    $from = ($currentPage - 1) * $perPage + 1;
                    $to = min($currentPage * $perPage, $total);
                @endphp
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>Showing {{ $from }} to {{ $to }} of {{ $total }} entries</div>
                </div>
            </div>
            @elseif (auth()->user()->department_id == 5)
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="sample_request_table">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>SRF #</th>
                            <th>Date Requested</th>
                            <th>Date Required</th>
                            <th>Ref Code</th>
                            <th>Type</th>
                            <th>Client Name</th>
                            <th>Region</th>
                            <th>Country</th>
                            <th>Primary Sales Person</th>
                            <th>Index</th>
                            <th>Number of Packages</th>
                            <th>Quantity</th>
                            <th>Product Code</th>
                            <th>Product Label</th>
                            <th>Application</th>
                            <th>Description</th>
                            <th>RPE No.</th>
                            <th>CRR No.</th>
                            <th>Date Sample Received</th>
                            <th>Date Dispatched</th>
                            <th>Status</th>
                            <th>Progress</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td align="center">
                                    <a href="{{ url('samplerequest/view/' . $product->sampleRequest->Id) }}" class="btn btn-sm btn-info btn-outline" title="View Request"><i class="ti-eye"></i></a>
                                    <button type="button" id="editSrf{{ $product->sampleRequest->Id }}" class="btn btn-sm btn-warning btn-outline"
                                        data-target="#edit{{ $product->sampleRequest->Id }}" data-toggle="modal" title='Edit SRF'>
                                        <i class="ti-pencil"></i>
                                    </button>
                                </td>
                                <td>{{ $product->sampleRequest->SrfNumber }}</td>
                                <td>{{ !empty($product->sampleRequest->DateRequested) ? date('m/d/Y H:i', strtotime($product->sampleRequest->DateRequested)) : '00/00/0000' }}</td>
                                <td>{{ !empty($product->sampleRequest->DateRequired) ? date('m/d/Y', strtotime($product->sampleRequest->DateRequired)) : '00/00/0000' }}</td>
                                <td>
                                    @if($product->sampleRequest->RefCode == 1)
                                        RND
                                    @elseif($product->sampleRequest->RefCode == 2)
                                        QCD
                                    @else
                                        {{ $product->sampleRequest->RefCode }}
                                    @endif
                                </td>
                                <td>
                                    @if($product->sampleRequest->SrfType == 1)
                                        Regular
                                    @elseif($product->sampleRequest->SrfType == 2)
                                        PSS
                                    @elseif($product->sampleRequest->SrfType == 3)
                                        CSS
                                    @else
                                        {{ $product->sampleRequest->SrfType }}
                                    @endif
                                </td>
                                <td>{{ optional($product->sampleRequest->client)->Name }}</td>
                                <td>{{ optional($product->sampleRequest->client)->clientregion->Name }}</td>
                                <td>{{ optional($product->sampleRequest->client)->clientcountry->Name }}</td>
                                <td>{{ $product->sampleRequest->primarySalesPerson->full_name ?? 'N/A' }}</td>
                                <td>{{ $product->ProductIndex }}</td>
                                <td>{{ $product->NumberOfPackages }}</td>
                                <td>{{ $product->Quantity }}</td>
                                <td>{{ $product->ProductCode }}</td>
                                <td>{{ $product->Label }}</td>
                                <td>{{optional( $product->productApplicationsId)->Name }}</td>
                                <td>{{ $product->ProductDescription }}</td>
                                <td>{{ $product->RpeNumber }}</td>
                                <td>{{ $product->CrrNumber }}</td>
                                <td>{{  date('m/d/y', strtotime($product->sampleRequest->DateSampleReceived)) }}</td>
                                <td>{{  date('m/d/y', strtotime($product->sampleRequest->DateDispatched)) }}</td>
                                <td>
                                    @if($product->sampleRequest->Status == 10)
                                        Open
                                    @elseif($product->sampleRequest->Status == 30)
                                        Closed
                                    @else
                                        {{ $product->sampleRequest->Status }}
                                    @endif
                                </td>
                                <td>{{ optional($product->sampleRequest->progressStatus)->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            
                {!! $products->appends(['search' => $search])->links() !!}
            
                @php
                    $total = $products->total();
                    $currentPage = $products->currentPage();
                    $perPage = $products->perPage();
            
                    $from = ($currentPage - 1) * $perPage + 1;
                    $to = min($currentPage * $perPage, $total);
                @endphp
            
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>Showing {{ $from }} to {{ $to }} of {{ $total }} entries</div>
                </div>
            </div>
            @endif 
            
        </div>
    </div>
</div>

<script>
   
</script>
@foreach ($sampleRequests as $srf)
@foreach ($srf->requestProducts as $product)
@include('sample_requests.edit')
@endforeach  
@endforeach  

@if(auth()->user()->department_id == 38)
@foreach ($sampleRequests as $srf)
@include('sample_requests.edit')
@endforeach
@elseif ((auth()->user()->department_id == 5))
@foreach ($products as $product)
@include('sample_requests.edit')
@endforeach
@endif


@include('sample_requests.create_srf')
@endsection


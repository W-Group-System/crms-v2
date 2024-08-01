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
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hove" id="sample_request_table">
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
                        @foreach ($sampleRequests as $srf)
                        @foreach ($srf->requestProducts as $product)
                        <tr>
                            <td align="center">
                                <a href="{{ url('samplerequest/view/' . $srf->Id) }}" class="btn btn-info btn-outline" title="View Request"><i class="ti-eye"></i></a>
                                <button type="button" id="editSrf{{ $srf->Id }}" class="btn btn-warning btn-outline"
                                    data-target="#edit{{ $srf->Id }}" data-toggle="modal" title='Edit SRF'>
                                    <i class="ti-pencil"></i>
                                </button>    
                            </td>
                            <td>{{ $srf->SrfNumber }}</td>
                            <td>{{ !empty($srf->DateRequested) ? date('m/d/Y H:i' , strtotime($srf->DateRequested)) : '00/00/0000' }}</td>
                            <td>{{ !empty($srf->DateRequired) ? date('m/d/Y', strtotime($srf->DateRequired)) : '00/00/0000' }}</td>
                            <td>
                                @if($srf->RefCode == 1)
                                    RND
                                @elseif($srf->RefCode == 2)
                                    QCD
                                @else
                                    {{ $srf->RefCode }}
                                @endif
                                </td>
                               <td>
                                @if($srf->SrfType == 1)
                                    Regular
                                @elseif($srf->SrfType == 2)
                                    PSS
                                @elseif($srf->SrfType == 3)
                                    CSS
                                @else
                                {{ $srf->SrfType }}
                                @endif</td>
                               <td>{{ $srf->client->Name }}</td>
                               <td>{{ $srf->client->clientregion->Name }}</td>
                               <td>{{ $srf->client->clientcountry->Name }}</td>
                               <td>{{ $srf->primarySalesPerson->full_name ?? 'N/A' }}</td>
                               
                               <td>{{ $srf->SrfNumber }}-{{ $product->ProductIndex }}</td>
                               <td>{{ $product->NumberOfPackages }}</td>
                               <td>{{ $product->Quantity }}</td>
                               <td>{{ $product->ProductCode }}</td>
                               <td>{{ $product->Label }}</td>
                               <td>{{ $product->productApplicationsId->Name }}</td>
                               <td>{{ $product->ProductDescription }}</td>
                               <td>{{ $product->RpeNumber }}</td>
                               <td>{{ $product->CrrNumber }}</td>
                               <td>{{  date('m/d/y', strtotime($srf->DateSampleReceived)) }}</td>
                               <td>{{  date('m/d/y', strtotime($srf->DateDispatched)) }}</td>
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
                @endforeach
                    </tbody>
                </table>
                {!! $sampleRequests->links() !!}
            </div>
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
@include('sample_requests.create_srf')
@endsection
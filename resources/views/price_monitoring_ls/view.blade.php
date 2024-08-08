@extends('layouts.header')
@section('content')
<style>
    #form_product {
        padding: 20px 20px;
    }
    
    #rpeTab .nav-link {
        padding: 15px;
    }
    .form-group label {
    font-size: 0.875rem;
    line-height: 0 !important;
    vertical-align: top;
    margin-bottom: 0 !important;
    }
    .group-form{
        margin-bottom: 1rem !important;
        margin-top: 1rem !important;
    }
    .form-divider{
        border-top: 1px solid black;
    }

    .border {
        border: 1px solid #ddd;
        padding: 10px;
        margin-bottom: 10px;
        overflow: hidden; 
    }
</style>
<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6"> 
                    <h4 class="card-title d-flex justify-content-between align-items-center" style="margin-top: 10px">View Product Details</h4>
                </div>
                <div class="col-lg-12" align="right">
                    <a href="{{ url('/price_monitoring') }}" class="btn btn-md btn-light"><i class="icon-arrow-left"></i>&nbsp;Back</a>
                    {{-- @if ($price_monitorings->Progress == 10)
                        <button type="button" class="btn btn-sm btn-success"
                                data-target="#approveSrf{{ $price_monitorings->id }}" 
                                data-toggle="modal" 
                                title='Approve SRF'>
                            <i class="ti-check"><br>Approve</i>
                        </button>
                    @elseif ($price_monitorings->Progress == 30)
                        <button type="button" class="btn btn-sm btn-success"
                                data-target="#receiveSrf{{ $price_monitorings->id }}" 
                                data-toggle="modal" 
                                title='Receive SRF'>
                            <i class="ti-check"><br>Receive</i>
                        </button>
                    @endif
                    @if ($price_monitorings->Progress == 50)
                        <button type="button" class="btn btn-sm btn-warning"
                        data-target="#pauseSrf{{ $price_monitorings->id }}" 
                        data-toggle="modal" 
                        title='Pause SRF'>
                        <i class="ti-control-pause"><br>Pause</i>
                    </button>
                    @else 
                    <button type="button" class="btn btn-sm btn-warning"
                        data-target="#startSrf{{ $price_monitorings->id }}" 
                        data-toggle="modal" 
                        title='Start SRF'>
                        <i class="ti-control-play"><br>Start</i>
                    </button>
                    @endif --}}
                    {{-- <button type="button" class="btn btn-md btn-success"
                        data-target="#approveSrf{{ $price_monitorings->id }}" 
                        data-toggle="modal" 
                        title='Approve SRF'>
                        <i class="ti-check">&nbsp;Approve</i>
                    </button>
                    <button type="button" class="btn btn-md btn-success"
                        data-target="#receiveSrf{{ $price_monitorings->id }}" 
                        data-toggle="modal" 
                        title='Receive SRF'>
                    <i class="ti-check">&nbsp;Receive</i>
                    </button>
                    <button type="button" class="btn btn-md btn-warning"
                        data-target="#startSrf{{ $price_monitorings->id }}" 
                        data-toggle="modal" 
                        title='Start SRF'>
                        <i class="ti-control-play">&nbsp;Start</i>
                    </button>
                    <button type="button" class="btn btn-md btn-warning"
                        data-target="#pauseSrf{{ $price_monitorings->id }}" 
                        data-toggle="modal" 
                        title='Pause SRF'>
                        <i class="ti-control-pause">&nbsp;Pause</i>
                    </button> --}}
                </div>
            </div>
            <form class="form-horizontal" id="form_product" enctype="multipart/form-data">
                <div class="form-header">
                    <div class="group-form">
                        <div class="form-group row">
                            <p class="col-sm-2 col-form-label"><b>PRF #:</b></p>
                            <p class="col-sm-3 col-form-label">
                               {{ $price_monitorings->PrfNumber }}
                            </p>
                            <p class="offset-sm-2 col-sm-2 col-form-label"><b>Date Requested:</b></p>
                            <p class="col-sm-3 col-form-label">{{ $price_monitorings->DateRequested}}</p>
                        </div>
                         <div class="form-group row">
                            <p class="col-sm-2 col-form-label"><b>Primary Sales Person:</b></p>
                            <p class="col-sm-3 col-form-label">{{ optional($price_monitorings->primarySalesPerson)->full_name }}</p>
                        </div>
                        <div class="form-group row">
                            <p class="col-sm-2 col-form-label"><b>Secondary Sales Person:</b></p>
                            <p class="col-sm-3 col-form-label">{{ optional($price_monitorings->secondarySalesPerson)->full_name }}</p>
                            <p class="offset-sm-2 col-sm-2 col-form-label"><b>Progress:</b></p>
                            <p class="col-sm-3 col-form-label">{{ $price_monitorings->Progress}}</p>
                        </div>
                        <div class="form-group row">
                            <p class="col-sm-2 col-form-label"><b></b></p>
                            <p class="col-sm-3 col-form-label"></p>
                            <p class="offset-sm-2 col-sm-2 col-form-label"><b>Status:</b></p>
                            <p class="col-sm-3 col-form-label">
                                @if($price_monitorings->Status == 10)
                                    Open
                                    @elseif($price_monitorings->Status == 20)
                                    Closed
                                    @else
                                    {{ $price_monitorings->Status }}
                                    @endif</td>
                            </p>
                        </div>
                        <div class="form-group row">
                            <p class="col-sm-2 col-form-label"><b>Contact:</b></p>
                            <p class="col-sm-3 col-form-label">{{ $price_monitorings->ContactId }}</p>
                            <p class="offset-sm-2 col-sm-2 col-form-label"><b>Shipment Term:</b></p>
                            <p class="col-sm-3 col-form-label">{{ $price_monitorings->ShipmentTerm}}</p>
                        </div>
                        <div class="form-group row">
                            <p class="col-sm-2 col-form-label"><b>Client Name:</b></p>
                            <p class="col-sm-3 col-form-label">
                                <a href="{{ url('view_client/' . $price_monitorings->client->id) }}">
                                    {{ $price_monitorings->client->Name }}
                                </a>
                            </p>
                            <p class="offset-sm-2 col-sm-2 col-form-label"><b>Destination:</b></p>
                            <p class="col-sm-3 col-form-label">{{ $price_monitorings->Destination}}</p>
                        </div>
                        <div class="form-group row">
                            <p class="col-sm-2 col-form-label"><b>Validity Date:</b></p>
                            <p class="col-sm-3 col-form-label">{{  $price_monitorings->ValidityDate }}</p>
                            <p class="offset-sm-2 col-sm-2 col-form-label"><b>Payment Term:</b></p>
                            <p class="col-sm-3 col-form-label">{{ $price_monitorings->PaymentTermId}}</p>
                        </div>
                        <div class="form-group row">
                            <p class="col-sm-2 col-form-label"><b>Packaging Type:</b></p>
                            <p class="col-sm-3 col-form-label">{{ $price_monitorings->PackagingType}}</p>
                            <p class="offset-sm-2 col-sm-2 col-form-label"><b>Other Cost Requirements :</b></p>
                            <p class="col-sm-3 col-form-label">{{ $price_monitorings->OtherCostRequirements}}</p>
                        </div>
                        <div class="form-group row">
                            <p class="col-sm-2 col-form-label"><b>MOQ:</b></p>
                            <p class="col-sm-3 col-form-label">{{ $price_monitorings->Moq}}</p>
                            <p class="offset-sm-2 col-sm-2 col-form-label"><b>Purpose of Price Request:</b></p>
                            <p class="col-sm-3 col-form-label">{{ $price_monitorings->PriceRequestPurpose}}</p>
                        </div>
                        <div class="form-group row">
                            <p class="col-sm-2 col-form-label"><b>Shelf Life:</b></p>
                            <p class="col-sm-3 col-form-label">{{ $price_monitorings->ShelfLife}}</p>
                            <p class="offset-sm-2 col-sm-2 col-form-label"><b>Delivery Schedule:</b></p>
                            <p class="col-sm-3 col-form-label">{{ $price_monitorings->PriceLockPeriod}}</p>
                        </div>
                        <div class="form-group row">
                            <p class="col-sm-2 col-form-label"><b>With Commission :</b></p>
                            <p class="col-sm-3 col-form-label"> 
                                @if ($price_monitorings->IsWithCommission == "1")
                                YES
                                @else
                                NO
                                @endif
                            </p>
                            <p class="offset-sm-2 col-sm-2 col-form-label"><b>Tax Type:</b></p>
                            <p class="col-sm-3 col-form-label">{{ $price_monitorings->TaxType}}</p>
                        </div>
                        <div class="form-group row">
                            <p class="col-sm-2 col-form-label"><b>Commission:</b></p>
                            <p class="col-sm-3 col-form-label">{{ $price_monitorings->Commission}}</p>
                            <p class="offset-sm-2 col-sm-2 col-form-label"><b></b></p>
                            <p class="col-sm-3 col-form-label"></p>
                        </div>
                    </div>
                    <hr class="form-divider">
                    <div class="header-label"><b>Customer Details</b></div> 
                </div>
                <div class="group-form">
                <div class="form-group row">
                    <p class="col-sm-2 col-form-label"><b>Client Name:</b></p>
                    <p class="col-sm-3 col-form-label">
                        <a href="{{ url('view_client/' . $price_monitorings->client->id) }}">
                            {{ $price_monitorings->client->Name }}
                        </a>
                    </p>
                </div>
                 <div class="form-group row">
                    <p class="col-sm-2 col-form-label"><b>Client Trade Name:</b></p>
                    <p class="col-sm-3 col-form-label">{{ $price_monitorings->client->TradeName }}</p>
                </div>
                <div class="form-group row">
                    <p class="col-sm-2 col-form-label"><b>Region:</b></p>
                    <p class="col-sm-3 col-form-label">{{ optional($price_monitorings->client->clientregion)->Name }}</p>
                </div>
                <div class="form-group row">
                    <p class="col-sm-2 col-form-label"><b>Country:</b></p>
                    <p class="col-sm-3 col-form-label">{{ optional($price_monitorings->client->clientcountry)->Name }}</p>
                </div>
            </div>
            <div class="form-header">
                <hr class="form-divider">
                <span class="header-label"><b>Computation</b></span>
            </div>
          @foreach ( $price_monitorings->requestPriceProducts as $priceProduct)
          <div class="group-form">
            <div class="form-group row">
                <p class="col-sm-2 col-form-label"><b>Purpose of Price Request :</b></p>
                <p class="col-sm-3 col-form-label"> 
                        @if ($price_monitorings->PriceRequestPurpose == "10")
                        Indication
                        @elseif ($price_monitorings->PriceRequestPurpose == "20")
                        Firm
                        @elseif ($price_monitorings->PriceRequestPurpose == "30")
                        Sample
                        @else
                        {{ $price_monitorings->PriceRequestPurpose }}
                        @endif
                </p>
                <p class="offset-sm-2 col-sm-2 col-form-label"><b>Category :</b></p>
                <p class="col-sm-3 col-form-label">
                    @if ($priceProduct->Type == "1")
                        Pure
                        @elseif ($priceProduct->Type == "2")
                        Blend
                        @else
                        {{ $priceProduct->Type }}
                        @endif
                </p>
            </div>
             <div class="form-group row">
                <p class="col-sm-2 col-form-label"><b></b></p>
                <p class="col-sm-3 col-form-label"></p>
                <p class="offset-sm-2 col-sm-2 col-form-label"><b>Quantity Required :</b></p>
                <p class="col-sm-3 col-form-label">{{ $priceProduct->QuantityRequired}}</p>
            </div>
            <div class="form-group row">
                <p class="col-sm-2 col-form-label"><b>Product Code :</b></p>
                <p class="col-sm-3 col-form-label">{{ optional($priceProduct->products)->code }}</p>
                <p class="offset-sm-2 col-sm-2 col-form-label"><b>Product RMC:</b></p>
                <p class="col-sm-3 col-form-label">{{ $priceProduct->ProductRmc}}</p>
            </div>
            <div class="form-group row">
                <p class="col-sm-2 col-form-label"><b>Shipment Term :</b></p>
                <p class="col-sm-3 col-form-label">{{ $price_monitorings->ShipmentTerm }}</p>
                <p class="offset-sm-2 col-sm-2 col-form-label"><b>Shipment Cost :</b></p>
                <p class="col-sm-3 col-form-label">{{ $priceProduct->IsalesShipmentCost}}</p>
            </div>
            <div class="form-group row">
                <p class="col-sm-2 col-form-label"><b>Payment Term :</b></p>
                <p class="col-sm-3 col-form-label">{{ optional($price_monitorings->paymentterms)->Name}}</p>
                <p class="offset-sm-2 col-sm-2 col-form-label"><b>Financing Cost :</b></p>
                <p class="col-sm-3 col-form-label">{{ $priceProduct->IsalesFinancingCost }}</p>
            </div>
            <div class="form-group row">
                <p class="col-sm-2 col-form-label"><b>Other Cost Requirements :</b></p>
                <p class="col-sm-3 col-form-label">{{ $priceProduct->OtherCostRequirements }}</p>
                <p class="offset-sm-2 col-sm-2 col-form-label"><b>Others :</b></p>
                <p class="col-sm-3 col-form-label">{{ $priceProduct->IsalesOthers }}</p>
            </div>
            <div class="form-group row">
                <p class="col-sm-2 col-form-label"><b>With Commission :</b></p>
                <p class="col-sm-3 col-form-label"> 
                    @if ($price_monitorings->IsWithCommission == "1")
                    YES
                    @else
                    NO
                    @endif
                </p>
                <p class="offset-sm-2 col-sm-2 col-form-label"></b></p>
                <p class="col-sm-3 col-form-label"></p>
            </div>
            <div class="form-group row">
                <p class="col-sm-2 col-form-label"><b>Commission :</b></p>
                <p class="col-sm-3 col-form-label"> 
                    {{ $priceProduct->IsalesCommission }}
                </p>
                <p class="offset-sm-2 col-sm-2 col-form-label"><b>Total Base Price</b></p>
                <p class="col-sm-3 col-form-label">{{ $priceProduct->IsalesTotalBaseCost }}</p>
            </div>
            <div class="form-group row">
                <p class="col-sm-2 col-form-label"></p>
                <p class="col-sm-3 col-form-label"> </p>
                <p class="offset-sm-2 col-sm-2 col-form-label"><b>Base Selling Price</b></p>
                <p class="col-sm-3 col-form-label">{{ $priceProduct->IsalesBaseSellingPrice }}</p>
            </div>
            <div class="form-group row">
                <p class="col-sm-2 col-form-label"></p>
                <p class="col-sm-3 col-form-label"> </p>
                <p class="offset-sm-2 col-sm-2 col-form-label"><b>Offered Price</b></p>
                <p class="col-sm-3 col-form-label">{{ $priceProduct->IsalesOfferedPrice }}</p>
            </div>
            <div class="form-group row">
                <p class="col-sm-2 col-form-label"></p>
                <p class="col-sm-3 col-form-label"> </p>
                <p class="offset-sm-2 col-sm-2 col-form-label"><b>Margin</b></p>
                <p class="col-sm-3 col-form-label">{{ $priceProduct->IsalesMargin }}</p>
            </div>
            <div class="form-group row">
                <p class="col-sm-2 col-form-label"></p>
                <p class="col-sm-3 col-form-label"> </p>
                <p class="offset-sm-2 col-sm-2 col-form-label"><b>% Margin</b></p>
                <p class="col-sm-3 col-form-label">{{ $priceProduct->IsalesMarginPercentage }}</p>
            </div>
            <div class="form-group row">
                <p class="col-sm-2 col-form-label"></p>
                <p class="col-sm-3 col-form-label"> </p>
                <p class="offset-sm-2 col-sm-2 col-form-label"><b>TotalMargin</b></p>
                <p class="col-sm-3 col-form-label"></p>
            </div>
            <div class="form-group row">
                <p class="col-sm-2 col-form-label"></p>
                <p class="col-sm-3 col-form-label"> </p>
                <p class="offset-sm-2 col-sm-2 col-form-label"><b>Remarks</b></p>
                <p class="col-sm-3 col-form-label">{{ $price_monitorings->Remarks }}</p>
            </div>
            <br>
            <div class="form-header">
                <hr class="form-divider">
                <span class="header-label"><b>Feedback</b></span>
            </div>
            <div class="group-form">
            <div class="form-group row">
                <p class="col-sm-2 col-form-label"><b>Was Offer Accepted ? :</b></p>
                <p class="col-sm-3 col-form-label">
                    @if ($price_monitorings->IsAccepted == "1")
                    YES
                    @endif
                </p>
                <p class="offset-sm-2 col-sm-2 col-form-label"></p>
                <p class="col-sm-3 col-form-label"></p>
            </div>
            <div class="form-group row">
                <p class="col-sm-2 col-form-label"><b>Buyer Ref Code :</b></p>
                <p class="col-sm-3 col-form-label">{{ $price_monitorings->BuyerRefCode }}</p>
                <p class="offset-sm-2 col-sm-2 col-form-label"></p>
                <p class="col-sm-3 col-form-label"></p>
            </div>
            <div class="form-group row">
                <p class="col-sm-2 col-form-label"><b>Price Bid :</b></p>
                <p class="col-sm-3 col-form-label">{{ $price_monitorings->PriceBid }}</p>
                <p class="offset-sm-2 col-sm-2 col-form-label"></p>
                <p class="col-sm-3 col-form-label"></p>
            </div>
            <div class="form-group row">
                <p class="col-sm-2 col-form-label"><b>Disposition Remarks :</b></p>
                <p class="col-sm-3 col-form-label">{{ $price_monitorings->DispositionRemarks }}</p>
                <p class="offset-sm-2 col-sm-2 col-form-label"></p>
                <p class="col-sm-3 col-form-label"></p>
            </div>
        </div>
        </div>
          @endforeach
            </form>          
            <ul class="nav nav-tabs" id="rpeTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="prfFiles-tab" data-toggle="tab" href="#prfFiles" role="tab" aria-controls="files" aria-selected="true">Files</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="activities-tab" data-toggle="tab" href="#activities" role="tab" aria-controls="activities" aria-selected="false">Activities</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="history-tab" data-toggle="tab" href="#history" role="tab" aria-controls="history" aria-selected="false">History</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="prfFiles" role="tabpanel" aria-labelledby="prfFiles-tab">
                    <div class="d-flex">
                        <button type="button" class="btn btn-sm btn-primary ml-auto m-3" title="Upload File"  data-toggle="modal" data-target="#uploadPrfFile">
                            <i class="ti-plus"></i>
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover prf-detailed-table" id="prfFiles_table" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Actions</th>
                                    <th>Name</th>
                                    <th>File</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($prfFileUploads as $fileupload)
                                    <tr>
                                        <td align="center">
                                            <button type="button"  class="btn btn-sm btn-warning btn-outline"
                                                data-target="#editPrfFile{{ $fileupload->Id }}" data-toggle="modal" title='Edit fileupload'>
                                                <i class="ti-pencil"></i>
                                            </button>   
                                            <button type="button" class="btn btn-sm btn-danger btn-outline" onclick="confirmDelete({{ $fileupload->Id }}, 'fileupload')" title='Delete fileupload'>
                                                <i class="ti-trash"></i>
                                            </button> 
                                        </td>
                                        <td>{{ $fileupload->Name }}</td>
                                        <td>
                                            @if ($fileupload->Path)
                                            <a href="{{ url($fileupload->Path) }}" target="_blank">View File</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="activities" role="tabpanel" aria-labelledby="activities-tab">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover prf-detailed-table" id="activities_table" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Schedule</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($activities as $activity)
                                    <tr>
                                        <td>{{ optional($activity)->ActivityNumber }}</td>
                                        <td>
                                            {{ optional($activity)->ScheduleFrom ? optional($activity)->ScheduleFrom : '' }}
                                            -
                                            {{ optional($activity)->ScheduleTo ? optional($activity)->ScheduleTo : '' }}
                                        </td>
                                        <td>{{ optional($activity)->Title  }}</td>
                                        <td>
                                            @if($activity->Status == 10)
                                            Open
                                            @elseif($activity->Status == 20)
                                            Closed
                                            @else
                                            {{ $activity->Status }}
                                            @endif</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover prf-detailed-table" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Name</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($combinedLogs as $combinedLog)
                                    <tr>
                                        <td>{{ $combinedLog->CreatedDate }}</td>
                                        <td>{{ $combinedLog->full_name }}</td>
                                        <td>{{ $combinedLog->Details }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="{{ asset('js/sweetalert2.min.js') }}"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">

<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
<script>
    function confirmDelete(id, type) {
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
            let url;
            if (type === 'fileupload') {
                url = '{{ url('price_monitorings/view/file-delete') }}/' + id;
            } else if (type === 'personnel') {
                url = '{{ url('price_monitorings/view/personnel-delete') }}/' + id;
            } else if (type === 'SrfMaterial') {
                url = '{{ url('price_monitorings/view/material-delete') }}/' + id;
            }

            $.ajax({
                url: url,
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
        new DataTable('.prf-detailed-table', {
            pageLength: 10,
            paging: true,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'excel'
            ],
            columnDefs: [{
                "defaultContent": "-",
                "targets": "_all"
            }],
            order: []
        });
    });
    </script>
@include('price_monitoring.upload_prf_file')
@foreach ($prfFileUploads as $fileupload)
@include('price_monitoring.edit_prfFiles')
@endforeach
{{-- @include('sample_requests.create_supplementary')
@include('sample_requests.assign_personnel')
@include('sample_requests.upload_srf_file')
@include('sample_requests.create_raw_materials')
@foreach ($price_monitorings as $srf)
    @include('sample_requests.srf_approval')
    @include('sample_requests.srf_receive')
    @include('sample_requests.srf_start')
    @include('sample_requests.srf_pause')
@endforeach
@foreach ($SrfSupplementary as $supplementary)
@include('sample_requests.edit_supplementary')
@endforeach
@foreach ($assignedPersonnel as $Personnel)
@include('sample_requests.edit_personnel')
@endforeach

@foreach ($SrfMaterials as $SrfMaterial)
@include('sample_requests.edit_material')
@endforeach --}}
@endsection
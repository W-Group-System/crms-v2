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
    <div class="card rounded-0 border border-1 border-primary p-0">
        <div class="card-header rounded-0 bg-primary text-white font-weight-bold">
            Price Request Details
        </div>
        <div class="card-body" style="overflow: auto;">
            {{-- <h4 class="card-title d-flex justify-content-between align-items-center">View Product Details
            </h4> --}}
            <div align="right">
                <!-- <a href="{{ url('/price_monitoring_ls') }}" class="btn btn-md btn-outline-light"><i class="icon-arrow-left"></i>&nbsp;Back</a> -->


                    @if(request('origin') == 'for_approval')
                    <a href="{{ url('view_for_approval_transaction') }}" class="btn btn-md btn-outline-secondary">
                        <i class="icon-arrow-left"></i>&nbsp;Back
                    </a> 
                    @elseif(request('origin') == 'open_transactions')
                    <a href="{{ url('/sales_open_transactions') }}" class="btn btn-md btn-outline-secondary">
                        <i class="icon-arrow-left"></i>&nbsp;Back
                    </a> 
                    @elseif(request('origin') == 'returned_transactions')
                    <a href="{{ url('/returned_transactions') }}" class="btn btn-md btn-outline-secondary">
                        <i class="icon-arrow-left"></i>&nbsp;Back
                    </a> 
                    @else
                    <a href="{{ url()->previous() ?: url('/price_monitoring_ls') }}" class="btn btn-md btn-outline-secondary">
                        <i class="icon-arrow-left"></i>&nbsp;Back
                    </a> 
                    @endif
                    <a target='_blank' href="{{ url('quotation', $price_monitorings->id) }}" class="btn btn-outline-danger btn-icon-text" > <i class="ti ti-printer btn-icon-prepend"></i>Quotation</a>
                    <a target='_blank' href="{{ url('computation', $price_monitorings->id) }}" class="btn btn-outline-danger btn-icon-text" > <i class="ti ti-printer btn-icon-prepend"></i>Computation</a>
                    @if ($price_monitorings->Progress != 30)
                            <button type="button" class="btn btn-md btn-outline-warning"
                            data-target="#closePrf{{ $price_monitorings->id }}" 
                            data-toggle="modal">
                            <i class="ti-folder"></i>&nbsp;Close
                        </button>
                        <button type="button" class="btn btn-outline-warning editBtn"
                            data-target="#prfEdit{{ $price_monitorings->id }}" 
                            data-toggle="modal" 
                            title='Update PRF'>
                            <i class="ti ti-pencil">&nbsp;</i>Update
                
                        </button>
                        @elseif($price_monitorings->Status == 30)
                            <button type="button" class="btn btn-outline-success reopenStatus" data-id="{{ $price_monitorings->id }}">
                                <i class="mdi mdi-open-in-new"></i>&nbsp;Open
                            </button>
                        @endif
                        @php
                            $showButton = false; // Default value
                            foreach ($price_monitorings->requestPriceProducts as $product) {
                                if ($product->PriceRequestGaeId == "6") {
                                    $showButton = false;
                                    break; // Exit loop if condition met
                                }
                                if ($product->LsalesMarkupPercent >= 15) { 
                                    $showButton = true;
                                    break; // Exit loop if condition met
                                }
                            }
                        @endphp
                        @if (((prfPrimarySalesApprover(auth()->user()->id, $price_monitorings->PrimarySalesPersonId, $price_monitorings->SecondarySalesPersonId) === "true") && $price_monitorings->Progress == 10)  && $showButton)
                            <button type="button" class="btn btn-md btn-success"
                                    data-target="#approvePrf{{ $price_monitorings->id }}" 
                                    data-toggle="modal" 
                                    title='Approve PRF'>
                                <i class="ti ti-check-box">&nbsp;</i>Approve
                            </button>
                        @elseif (((prfPrimarySalesApprover(auth()->user()->id, $price_monitorings->PrimarySalesPersonId, $price_monitorings->SecondarySalesPersonId) === "true")) && $price_monitorings->Progress == 10)
                            <button type="button" class="btn btn-md btn-success"
                                    data-target="#approveManagerPrf{{ $price_monitorings->id }}" 
                                    data-toggle="modal" 
                                    title='Approve PRF'>
                                <i class="ti ti-check-box">&nbsp;</i>Approve To Manager
                            </button>
                        @endif
            
                @if(authCheckIfItsSalesManager(auth()->user()->role_id) && $price_monitorings->Progress == 40)
                <button type="button" class="btn btn-md btn-success"
                        data-target="#approvePrf{{ $price_monitorings->id }}" 
                        data-toggle="modal" 
                        title='Approve PRF'>
                    <i class="ti ti-check-box">&nbsp;</i>Approve
                </button>
                @endif

            </div>
            <div class="row">
                <p class="col-md-2 mb-0 text-right">
                    <b>PRF # :</b>
                </p>
                <p class="col-md-2 mb-0">
                    {{ $price_monitorings->PrfNumber }}
                </p>
                <p class="col-md-2 mb-0 text-right"><b>Date Requested :</b></p>
                <p class="col-md-2 mb-0">{{ $price_monitorings->DateRequested}}</p>
            </div>
            <div class="row">
                <p class="col-md-2 text-right mb-0">
                    <b>Primary Sales Person :</b>
                </p>
                <p class="col-md-2 mb-0">
                    @if($price_monitorings->primarySalesPerson)
                    {{ optional($price_monitorings->primarySalesPerson)->full_name}}
                    @elseif($price_monitorings->primarySalesPersonById)
                    {{ optional($price_monitorings->primarySalesPersonById)->full_name}}
                    @endif
                    {{-- {{ optional($price_monitorings->primarySalesPerson)->full_name }}</p> --}}
            </div>
            <div class="row">
                <p class="col-md-2 mb-0 text-right"><b>Secondary Sales Person:</b></p>
                <p class="col-md-2 mb-0">
                    @if($price_monitorings->secondarySalesPerson)
                    {{ optional($price_monitorings->secondarySalesPerson)->full_name}}
                    @elseif($price_monitorings->secondarySalesPersonById)
                    {{ optional($price_monitorings->secondarySalesPersonById)->full_name}}
                    @endif
                </p>
                <p class="col-md-2 mb-0 text-right"><b>Progress:</b></p>
                <p class="col-md-2 mb-0">
                    {{ optional($price_monitorings->progressStatus)->name }}
                    {{-- @if ($price_monitorings->Progress == '10')
                    For Approval
                    @elseif ($price_monitorings->Progress == '20')
                    Waiting For Disposition
                    @elseif ($price_monitorings->Progress == '25')
                    Reopened
                    @elseif ($price_monitorings->Progress == '30')
                    Closed
                    @else
                    Waiting For Disposition
                    @endif --}}
                </p>
            </div>
            <div class="row">
                <p class="col-md-2 mb-0"><b></b></p>
                <p class="col-md-2 mb-0"></p>
                <p class="col-md-2 mb-0 text-right">
                    <b>Status :</b>
                </p>
                <p class="col-md-2 mb-0">
                    @if($price_monitorings->Status == 10)
                        Open
                        @elseif($price_monitorings->Status == 20)
                        Closed
                        @else
                        {{ $price_monitorings->Status }}
                        @endif</td>
                </p>
            </div>
            <div class="row">
                <p class="col-md-2 mb-0 text-right"><b>Contact:</b></p>
                <p class="col-md-2 mb-0">{{ optional($price_monitorings->clientContact)->ContactName }}</p>
                <p class="col-md-2 mb-0 text-right"><b>Shipment Term:</b></p>
                <p class="col-md-2 mb-0">{{ $price_monitorings->ShipmentTerm}}</p>
            </div>
            <div class="row">
                <p class="col-md-2 text-right mb-0"><b>Client Name:</b></p>
                <p class="col-md-2 mb-0">
                    <a href="{{ url('view_client/' . $price_monitorings->client->id) }}">
                        {{ optional($price_monitorings->client)->Name }}
                    </a>
                </p>
                <p class="col-md-2 mb-0 text-right"><b>Destination:</b></p>
                <p class="col-md-2 mb-0">{{ $price_monitorings->Destination}}</p>
            </div>
            <div class="row">
                <p class="col-md-2 mb-0 text-right"><b>Validity Date:</b></p>
                <p class="col-md-2 mb-0">{{  $price_monitorings->ValidityDate }}</p>
                <p class="col-md-2 mb-0 text-right"><b>Payment Term:</b></p>
                <p class="col-md-2 mb-0">{{ optional($price_monitorings->paymentterms)->Name}}</p>
            </div>
            <div class="row">
                <p class="col-md-2 mb-0 text-right"><b>Packaging Type:</b></p>
                <p class="col-md-2 mb-0">{{ $price_monitorings->PackagingType}}</p>
            </div>
            <div class="row">
                <p class="col-md-2 mb-0 text-right"><b>MOQ:</b></p>
                <p class="col-md-2 mb-0">{{ $price_monitorings->Moq}}</p>
                <p class="col-md-2 mb-0 text-right"><b>Purpose of Price Request:</b></p>
                <p class="col-md-2 mb-0">
                    @if($price_monitorings->PriceRequestPurpose == 10)
                        Indication
                        @elseif($price_monitorings->PriceRequestPurpose == 20)
                        Firm
                        @elseif($price_monitorings->PriceRequestPurpose == 30)
                        Sample
                        @else
                        {{ $price_monitorings->PriceRequestPurpose }}
                        @endif</td>
            </div>
            <div class="row">
                <p class="col-md-2 mb-0 text-right"><b>Shelf Life:</b></p>
                <p class="col-md-2 mb-0">{{ $price_monitorings->ShelfLife}}</p>
                <p class="col-md-2 mb-0 text-right"><b>Delivery Schedule:</b></p>
                <p class="col-md-2 mb-0">{{ $price_monitorings->PriceLockPeriod}}</p>
            </div>
            <div class="form-group row">
                <p class="col-md-2 mb-0"><b></b></p>
                <p class="col-md-2 mb-0"></p>
                <p class="col-md-2 mb-0 text-right"><b>Tax Type:</b></p>
                <p class="col-md-2 mb-0">
                    @if($price_monitorings->TaxType == 10)
                    VAT Inclusive
                    @elseif($price_monitorings->TaxType == 20)
                    VAT Exclusive
                    @else
                    {{ $price_monitorings->TaxType }}
                    @endif
            </div>

            <hr class="form-divider">
            <div class="header-label"><b>Customer Details</b></div> 
            @foreach ($price_monitorings->requestPriceProducts as  $prcieProduct)
            <div class="border">
                <div class="row">
                    <p class="col-md-2 mb-0 text-right"><b>Product:</b></p>
                    <p class="col-md-2 mb-0">{{ optional($prcieProduct->products)->code }}</p>
                    <p class="col-md-2 mb-0 text-right"><b>RMC (PHP):</b></p>
                    <p class="col-md-2 mb-0">{{ $prcieProduct->ProductRmc }}</p>
                    <p class="col-md-2 mb-0 text-right"><b>Delivery Cost:</b></p>
                    <p class="col-md-2 mb-0">{{ $prcieProduct->LsalesDeliveryCost }}</p>
                </div>
                <div class="row">
                    <p class="col-md-2 mb-0 text-right"><b>Category:</b></p>
                    <p class="col-md-2 mb-0">
                        @if ($prcieProduct->Type == 1)
                            Pure
                        @elseif ($prcieProduct->Type == 2)
                            Blend
                        @else
                        {{ $prcieProduct->Type }}
                        @endif
                    </p>
                    <p class="col-md-2 mb-0 text-right"><b>Direct Labor:</b></p>
                    <p class="col-md-2 mb-0">{{ $prcieProduct->LsalesDirectLabor }}
                    <p class="col-md-2 mb-0 text-right"><b>Financing Cost:</b></p>
                    <p class="col-md-2 mb-0">{{ $prcieProduct->LsalesFinancingCost }}</p>
                </div>
                <div class="row">
                    <p class="col-md-2 mb-0 text-right"><b>Application:</b></p>
                    <p class="col-md-2 mb-0">{{ optional($prcieProduct->product_application)->Name }} </p>
                    <p class="col-md-2 mb-0 text-right"><b>Factory Overhead:</b></p>
                    <p class="col-md-2 mb-0">{{ $prcieProduct->LsalesFactoryOverhead }}</p>
                    <p class="col-md-2 mb-0 text-right"><b>GAE Type:</b></p>
                    <p class="col-md-2 mb-0">{{ optional($prcieProduct->gaeType)->ExpenseName }}</p>
                </div>
                <div class="row">
                    <p class="col-md-2 mb-0 text-right"><b>Quantity Required:</b></p>
                    <p class="col-md-2 mb-0">
                        @if (strpos($prcieProduct->QuantityRequired, ',') !== false)
                            {{ $prcieProduct->QuantityRequired }}
                        @else
                            {{ number_format($prcieProduct->QuantityRequired) }}
                        @endif
                    </p>                    
                    <p class="col-md-2 mb-0 text-right"><b>Total Manufacturing Cost:</b></p>
                    <p class="col-md-2 mb-0">{{ number_format($prcieProduct->ProductRmc + $prcieProduct->LsalesDirectLabor + $prcieProduct->LsalesFactoryOverhead, 2) }}</p>
                    <p class="col-md-2 mb-0 text-right"><b>GAE Cost:</b></p>
                    <p class="col-md-2 mb-0">{{ $prcieProduct->LsalesGaeValue }}</p>
                </div>
                <div class="form-group row">
                    <p class="col-md-2 mb-0"></p>
                    <p class="col-md-2 mb-0"></p>
                    <p class="col-md-2 mb-0 text-right"><b>Blending Loss:</b></p>
                    <p class="col-md-2 mb-0">{{ $prcieProduct->LsalesBlendingLoss }}</p>
                    <p class="col-md-2 mb-0 text-right"><b>Other Cost Requirements :</b></p>
                    <p class="col-md-2 mb-0">{{$prcieProduct->OtherCostRequirements}}</p>
                </div>
                <div class="form-group row">
                    <p class="col-md-2 mb-0"></p>
                    <p class="col-md-2 mb-0"></p>
                    <p class="col-md-2 mb-0 text-right"></p>
                    <p class="col-md-2 mb-0"></p>
                    <p class="col-md-2 mb-0 text-right"><b>Total Operating Cost:</b></p>
                    <p class="col-md-2 mb-0">{{ number_format($prcieProduct->LsalesDeliveryCost + $prcieProduct->LsalesFinancingCost + $prcieProduct->LsalesGaeValue + $prcieProduct->OtherCostRequirements, 2) }}</p>
                </div>
                @php
                    $totalCost = $prcieProduct->ProductRmc +
                                $prcieProduct->LsalesDirectLabor +
                                $prcieProduct->LsalesFactoryOverhead +
                                $prcieProduct->LsalesDeliveryCost +
                                $prcieProduct->LsalesFinancingCost +
                                $prcieProduct->LsalesGaeValue +
                                $prcieProduct->OtherCostRequirements +
                                $prcieProduct->LsalesBlendingLoss;
                    
                    $totalCost = round($totalCost, 2);
                    
                    $markupPercent = $prcieProduct->LsalesMarkupPercent;
                    $markupValue = $prcieProduct->LsalesMarkupValue;

                   
                    $markupValue = (float) $markupValue;

                    $sellingPrice = $totalCost + $markupValue;
                    $sellingPriceWithVAT = $sellingPrice * 0.12;
                    $sumWithVat = $sellingPrice + $sellingPriceWithVAT;

                    $formattedSellingPrice = number_format($sellingPrice, 2);
                    $formattedSellingPriceWithVAT = number_format($sellingPriceWithVAT, 2);
                    $formattedSumWithVat = number_format($sumWithVat, 2);
                @endphp
                <div class="col-lg-12"><hr style="background-color: rgb(219, 209, 209)"></div>
                <div class="row">
                    <p class="col-md-2 mb-0 text-right"><b>Total Product Cost:</b></p>
                    <p class="col-md-2 mb-0">
                        {{ number_format($totalCost, 2) }}
                    </p>
                    <p class="col-md-2 mb-0 text-right"><b>Markup (%):</b></p>
                    <p class="col-md-2 mb-0">{{ $prcieProduct->LsalesMarkupPercent }}</p>
                    <p class="col-md-2 mb-0 text-right"><b>Selling Price :</b></p>
                    <p class="col-md-2 mb-0">{{ $formattedSellingPrice }}</p>
                </div>
                <div class="row">
                    <p class="col-md-2 mb-0 text-right"></p>
                    <p class="col-md-2 mb-0"></p>
                    <p class="col-md-2 mb-0 text-right"><b>Markup :</b></p>
                    <p class="col-md-2 mb-0">{{ $prcieProduct->LsalesMarkupValue }}</p>
                    <p class="col-md-2 mb-0 text-right"><b>Selling Price + 12% VAT :</b></p>
                    <p class="col-md-2 mb-0">{{ $formattedSumWithVat }}</p>
                </div>
            </div>
            @endforeach
            <hr class="form-divider">
            <div class="header-label"><b>DISPOSITION</b></div> 
            <div class="row">
                <p class="col-md-2 mb-0 text-right"><b>Is Accepted:</b></p>
                <p class="col-md-2 mb-0">
                    @if ($price_monitorings->IsAccepted == "1")
                    YES
                    @endif
                </p>
            </div>
            <div class="row mb-3">
                <p class="col-md-2 mb-0 text-right"><b>Disposition Remarks :</b></p>
                <p class="col-md-2 mb-0 ">{{ $price_monitorings->DispositionRemarks }}</p>
                {{-- <p class="offset-sm-2 col-sm-2"></p>
                <p class="col-sm-3"></p> --}}
            </div>
            
            <ul class="nav nav-tabs" id="rpeTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active p-2" id="prfFiles-tab" data-toggle="tab" href="#prfFiles" role="tab" aria-controls="files" aria-selected="true">Files</a>
                </li>
                {{-- <li class="nav-item">
                    <a class="nav-link p-2" id="activities-tab" data-toggle="tab" href="#activities" role="tab" aria-controls="activities" aria-selected="false">Activities</a>
                </li> --}}
                <li class="nav-item">
                    <a class="nav-link p-2" id="history-tab" data-toggle="tab" href="#history" role="tab" aria-controls="history" aria-selected="false">History</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="prfFiles" role="tabpanel" aria-labelledby="prfFiles-tab">
                    <div class="d-flex">
                        @if(checkIfHaveFiles(auth()->user()->role) == "yes")
                        <button type="button" class="btn btn-sm btn-outline-primary ml-auto m-3" title="Upload File"  data-toggle="modal" data-target="#uploadPrfFile">
                            New
                        </button>
                        @endif
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
                                        <td>
                                            @if(checkIfHaveFiles(auth()->user()->role) == "yes")
                                            <button type="button"  class="btn btn-sm btn-warning btn-outline"
                                                data-target="#editPrfFile{{ $fileupload->Id }}" data-toggle="modal" title='Edit fileupload'>
                                                <i class="ti-pencil"></i>
                                            </button>   
                                            <button type="button" class="btn btn-sm btn-danger btn-outline" onclick="confirmDelete({{ $fileupload->Id }}, 'fileupload')" title='Delete fileupload'>
                                                <i class="ti-trash"></i>
                                            </button> 
                                            @endif
                                        </td>
                                        <td>{{ optional($fileupload)->Name }}</td>
                                        <td>
                                            @if ($fileupload->Path)
                                            <a href="{{ url($fileupload->Path) }}" target="_blank">
                                                <i class="ti-file"></i>
                                            </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- <div class="tab-pane fade" id="activities" role="tabpanel" aria-labelledby="activities-tab">
                    <div class="d-flex">
                    @if(checkIfItsSalesDept(auth()->user()->department_id))
                        <button type="button" class="btn btn-sm btn-primary ml-auto m-3" title="Create Activity"  data-toggle="modal" data-target="#createPrfActivity">
                            <i class="ti-plus"></i>
                        </button>
                    </div>
                    @endif
                    <div class="table-responsive">
                        <div class="filter">
                            <label><input type="checkbox" class="status-filter" value="10" checked> Open</label>
                            <label><input type="checkbox" class="status-filter" value="20" checked> Closed</label>
                        </div>
                        <table class="table table-striped table-bordered table-hover prf-detailed-table" id="activities_table" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Actions</th>
                                    <th>#</th>
                                    <th>Schedule</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($activities as $activity)
                                    <tr data-status="{{ $activity->Status }}">
                                        <td>
                                        @if(checkIfItsSalesDept(auth()->user()->department_id))
                                        <button type="button"  class="btn btn-sm btn-warning btn-outline"
                                        data-target="#editPrfActivity{{ $activity->id }}" data-toggle="modal" title='Edit Activity'>
                                        <i class="ti-pencil"></i>
                                        </button>   
                                        <button type="button" class="btn btn-sm btn-danger btn-outline" onclick="confirmDelete({{ $activity->id }}, 'activity')" title='Delete Activity'>
                                            <i class="ti-trash"></i>
                                        </button>
                                        @endif 
                                        </td>
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
                </div> --}}
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
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "{{ session('error') }}",
                confirmButtonText: 'OK'
            });
        @elseif(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: "{{ session('success') }}",
                confirmButtonText: 'OK'
            });
        @endif

        $(".reopenStatus").on('click', function() {
            var prfId = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "You want to open this request!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "{{ url('ReopenPrf') }}/" + prfId,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(function() {
                                location.reload();
                            });
                        }
                    });
                }
            });
        });

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
            } else if (type === 'activity') {
                url = '{{ url('price_monitorings/view/activity-delete') }}/' + id;
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

        // $(".editBtn").on('click', function() {
        //     var secondarySales = $(this).data('secondarysales');
        //     var primarySales = $('[name="PrimarySalesPersonId"]').val();

        //     refreshSecondaryApprovers(primarySales,secondarySales)
        // })
        // $('[name="PrimarySalesPersonId"]').on('change', function() {
        //     var primarySales = $(this).val();

        //     refreshSecondaryApproversv2(primarySales)
        // })
        // function refreshSecondaryApprovers(primarySales,secondarySales)
        // {
        //     $.ajax({
        //         type: "POST",
        //         url: "{{url('refresh_user_approvers')}}",
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         data: {
        //             ps: primarySales,
        //         },
        //         success: function(data)
        //         {
        //             setTimeout(() => {
        //                 $('[name="SecondarySalesPersonId"]').html(data) 
        //                 // $('[name="SecondarySalesPersonId"]').val(secondarySales) 
        //             }, 500);
        //         }
        //     })
        // }
        // function refreshSecondaryApproversv2(primarySales)
        // {
        //     $.ajax({
        //         type: "POST",
        //         url: "{{url('refresh_user_approvers')}}",
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         data: {
        //             ps: primarySales,
        //         },
        //         success: function(data)
        //         {
        //             setTimeout(() => {
        //                 $('[name="SecondarySalesPersonId"]').html(data) 
        //             }, 500);
        //         }
        //     })
        // }
    });

    document.addEventListener('DOMContentLoaded', function() {
    const filters = document.querySelectorAll('.status-filter');

    filters.forEach(filter => {
        filter.addEventListener('change', filterTable);
    });

    function filterTable() {
        const selectedStatuses = Array.from(filters)
            .filter(filter => filter.checked)
            .map(filter => filter.value);

        document.querySelectorAll('#activities_table tbody tr').forEach(row => {
            const status = row.getAttribute('data-status');
            if (selectedStatuses.includes(status)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
    filterTable();
});

    </script>
@include('price_monitoring.upload_prf_file')
{{-- @include('price_monitoring_ls.create_activity') --}}
@foreach ($prfFileUploads as $fileupload)
@include('price_monitoring.edit_prfFiles')
@endforeach
@foreach ($activities as $activity)
@include('price_monitoring_ls.edit_activity')
@endforeach


@include('price_monitoring_ls.close')
@include('price_monitoring_ls.prf_approval')
@include('price_monitoring_ls.prf_manager_approval')
@include('price_monitoring_ls.ls_view_edit')
{{-- @include('sample_requests.create_supplementary')
@include('sample_requests.assign_personnel')
@include('sample_requests.upload_srf_file')
@include('sample_requests.create_raw_materials')

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
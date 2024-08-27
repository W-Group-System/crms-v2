@extends('layouts.header')
@section('content')
<style>
    #form_product {
        padding: 20px 20px;
    }
    
    #productTab .nav-link {
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
                    <a href="{{ url('/sample_request') }}" class="btn btn-md btn-light"><i class="icon-arrow-left"></i>&nbsp;Back</a>
                    {{-- @if ($sampleRequest->Progress == 10)
                        <button type="button" class="btn btn-sm btn-success"
                                data-target="#approveSrf{{ $sampleRequest->Id }}" 
                                data-toggle="modal" 
                                title='Approve SRF'>
                            <i class="ti-check"><br>Approve</i>
                        </button>
                    @elseif ($sampleRequest->Progress == 30)
                        <button type="button" class="btn btn-sm btn-success"
                                data-target="#receiveSrf{{ $sampleRequest->Id }}" 
                                data-toggle="modal" 
                                title='Receive SRF'>
                            <i class="ti-check"><br>Receive</i>
                        </button>
                    @endif
                    @if ($sampleRequest->Progress == 50)
                        <button type="button" class="btn btn-sm btn-warning"
                        data-target="#pauseSrf{{ $sampleRequest->Id }}" 
                        data-toggle="modal" 
                        title='Pause SRF'>
                        <i class="ti-control-pause"><br>Pause</i>
                    </button>
                    @else 
                    <button type="button" class="btn btn-sm btn-warning"
                        data-target="#startSrf{{ $sampleRequest->Id }}" 
                        data-toggle="modal" 
                        title='Start SRF'>
                        <i class="ti-control-play"><br>Start</i>
                    </button>
                    @endif --}}

                    {{-- SRF Process  --}}

                    {{-- @if(authCheckIfItsSales(auth()->user()->department_id))
                    <button type="button" class="btn btn-danger btn-icon-text" >
                        <i class="ti ti-printer btn-icon-prepend"></i>
                        Print
                    </button>
                    @endif --}}

                    @if(authCheckIfItsRndStaff(auth()->user()->role))
                        @if(rndPersonnel($sampleRequest->srfPersonnel, auth()->user()->id))
                            @if($sampleRequest->Progress == 35)
                            <button type="button" class="btn btn-md btn-success startSrf"  data-id="{{ $sampleRequest->Id }}">
                                 <i class="ti-control-play">&nbsp;</i>Start
                             </button>
                            @endif
                        @endif

                        @if($sampleRequest->Progress == 50)
                        <button type="button" class="btn btn-md btn-success"
                            data-target="#pauseSrf{{ $sampleRequest->Id }}" 
                            data-toggle="modal" 
                            title='Pause SRF'>
                            <i class="ti-control-pause">&nbsp;</i>Pause
                        </button>
                        @endif

                        @if($sampleRequest->Progress == 55)
                            <button type="button" class="btn btn-md btn-success startSrf"  data-id="{{ $sampleRequest->Id }}">
                                <i class="ti-control-play">&nbsp;</i>Continue
                            </button>
                        @endif
                    @endif

                    @if(rndPersonnel($sampleRequest->srfPersonnel, auth()->user()->id))
                        @if($sampleRequest->Progress == 50)
                            <button type="button" class="btn btn-md btn-warning submitSrf"  data-id="{{ $sampleRequest->Id }}">
                                <i class="ti-check">&nbsp;</i>Submit
                            </button>
                        @endif
                    @endif

                    @if(auth()->user()->id == $sampleRequest->PrimarySalesPersonId || auth()->user()->user_id == $sampleRequest->PrimarySalesPersonId)
                            @if(auth()->user()->role->type == 'IS' || auth()->user()->role->type == 'LS')
                            <button type="button" class="btn btn-warning"
                                data-target="#salesEdit{{ $sampleRequest->Id }}" 
                                data-toggle="modal" 
                                title='Update SRF'>
                                <i class="ti ti-pencil">&nbsp;</i>Update
                            </button>
                            @endif
                        {{-- @endif --}}

                        @if($sampleRequest->Progress == 70 && $sampleRequest->Status == 10)
                            <button type="button" class="btn btn-info returnToRnd" data-id="{{ $sampleRequest->Id }}">
                                <i class="ti ti-check-box"></i>&nbsp;Return to RND
                            </button>
                        @endif

                        @if(checkRolesIfHaveApprove('Sample Request', auth()->user()->department_id, auth()->user()->role_id) == "yes")
                            <button type="button" class="btn btn-md btn-success"
                                data-target="#approveSrf{{ $sampleRequest->Id }}" 
                                data-toggle="modal" 
                                title='Approve SRF'>
                                <i class="ti ti-check-box">&nbsp;</i>Approve
                            </button>
                        @endif

                        @if($sampleRequest->Progress == 60)
                            <button type="button" class="btn btn-info returnToRnd" data-id="{{ $sampleRequest->Id }}">
                                <i class="ti ti-check-box"></i>&nbsp;Return to RND
                            </button>

                            <button type="button" class="btn btn-info salesAccepted" data-id="{{ $sampleRequest->Id }}">
                                <i class="ti ti-check-box"></i>&nbsp;Accept
                            </button>
                        @endif
                        
                        @if($sampleRequest->Status == 30)
                            <button type="button" class="btn btn-success openStatus" data-id="{{ $sampleRequest->Id }}">
                                <i class="mdi mdi-open-in-new"></i>&nbsp;Open
                            </button>
                        @endif
                        
                        @if($sampleRequest->Status == 10)
                            <button type="button" class="btn btn-primary"
                                data-target="#closeSrf{{ $sampleRequest->Id }}" 
                                data-toggle="modal" 
                                title='Close SRF'>
                                <i class="ti ti-close">&nbsp;</i>Close
                            </button>
                            <button type="button" class="btn btn-danger"
                                data-target="#cancelSrf{{ $sampleRequest->Id }}" 
                                data-toggle="modal" 
                                title='Cancel SRF'>
                                <i class="mdi mdi-cancel">&nbsp;</i>Cancel
                            </button>
                        @endif






                    @elseif(checkIfItsManagerOrSupervisor(auth()->user()->role) == "yes")
                        @if(authCheckIfItsRnd(auth()->user()->department_id))
                             @if($sampleRequest->Progress != 10 && $sampleRequest->Progress != 20 && $sampleRequest->Progress != 60)
                                <button type="button" class="btn btn-info returnToSales"  data-id="{{ $sampleRequest->Id }}" >
                                    <i class="ti-control-left">&nbsp;</i>Return To Sales
                                </button>
                            @endif
                        @endif

                        @if(authCheckIfItsSales(auth()->user()->department_id))
                            @if($sampleRequest->Status == 10)
                            <button type="button" class="btn btn-warning"
                                data-target="#salesEdit{{ $sampleRequest->Id }}" 
                                data-toggle="modal" 
                                title='Update SRF'>
                                <i class="ti ti-pencil">&nbsp;</i>Update
                            </button>
                            @endif
                        @endif
                        @if(checkIfItsApprover(auth()->user()->id, $sampleRequest->PrimarySalesPersonId, "SRF") == "yes" && $sampleRequest->Progress == 10)
                            <button type="button" class="btn btn-md btn-success"
                                data-target="#approveSrf{{ $sampleRequest->Id }}" 
                                data-toggle="modal" 
                                title='Approve SRF'>
                                <i class="ti ti-check-box">&nbsp;</i>Approve
                            </button>
                        @endif
                        
                        @if(authCheckIfItsSales(auth()->user()->department_id))

                            @if(($sampleRequest->Progress == 60 || $sampleRequest->Progress == 70) && $sampleRequest->Status == 10)
                                <button type="button" class="btn btn-info returnToRnd" data-id="{{ $sampleRequest->Id }}">
                                    <i class="ti ti-check-box"></i>&nbsp;Return to RND
                                </button>
                            @endif 

                            @if($sampleRequest->Progress == 60)
                                <button type="button" class="btn btn-info salesAccepted" data-id="{{ $sampleRequest->Id }}">
                                    <i class="ti ti-check-box"></i>&nbsp;Accept
                                </button>
                            @endif

                            @if($sampleRequest->Status == 30)
                                <button type="button" class="btn btn-success openStatus" data-id="{{ $sampleRequest->Id }}">
                                    <i class="mdi mdi-open-in-new"></i>&nbsp;Open
                                </button>
                            @endif

                            @if($sampleRequest->Status == 10)
                                <button type="button" class="btn btn-primary"
                                    data-target="#closeSrf{{ $sampleRequest->Id }}" 
                                    data-toggle="modal" 
                                    title='Close SRF'>
                                    <i class="ti ti-close">&nbsp;</i>Close
                                </button>
                                <button type="button" class="btn btn-danger"
                                    data-target="#cancelSrf{{ $sampleRequest->Id }}" 
                                    data-toggle="modal" 
                                    title='Cancel SRF'>
                                    <i class="mdi mdi-cancel">&nbsp;</i>Cancel
                                </button>
                            @endif
                        @else
                            @if($sampleRequest->Progress == 55 || $sampleRequest->Progress == 57 || $sampleRequest->Progress == 81)
                                {{-- <button type="button" class="btn btn-danger returnBtn">
                                    <i class="ti-back-left">&nbsp;</i> Return To Specialist
                                </button> --}}
                                <button type="button" class="btn btn-info returnToRnd" data-id="{{ $sampleRequest->Id }}">
                                    <i class="ti ti-check-box"></i>&nbsp;Return to Specialist
                                </button>
                                {{-- <button type="button" class="btn btn-md btn-success startSrf"  data-id="{{ $sampleRequest->Id }}">
                                    <i class="ti-control-play">&nbsp;</i>Start
                                </button> --}}
                            @endif

                            @if($sampleRequest->Progress == 30)
                            <button type="button" class="btn btn-md btn-success receiveSrf" data-id="{{ $sampleRequest->Id }}"  
                                 title='Receive SRF'>
                            <i class="ti-bookmark">&nbsp;</i>Receive
                            </button>
                            @endif

                            @if($sampleRequest->Progress == 35)
                                <button type="button" class="btn btn-md btn-success startSrf"  data-id="{{ $sampleRequest->Id }}">
                                    <i class="ti-control-play">&nbsp;</i>Start
                                </button>
                            @endif

                            @if($sampleRequest->Progress == 50)
                                <button type="button" class="btn btn-md btn-warning"
                                data-target="#pauseSrf{{ $sampleRequest->Id }}" 
                                data-toggle="modal" 
                                title='Pause SRF'>
                                <i class="ti-control-pause">&nbsp;</i>Pause
                            </button>
                            @endif

                            @if($sampleRequest->Progress == 55)
                                <button type="button" class="btn btn-md btn-success startSrf"  data-id="{{ $sampleRequest->Id }}">
                                    <i class="ti-control-play">&nbsp;</i>Continue
                                </button>
                            @endif

                            @if($sampleRequest->Progress == 57 )
                                <button type="button" class="btn btn-md btn-success completeSrf"  data-id="{{ $sampleRequest->Id }}">
                                    <i class="ti-pencil-alt">&nbsp;</i>Completed
                                </button>
                            @endif

                        @endif
                    @endif
                    
                    {{-- <button type="button" class="btn btn-md btn-warning startSrf"  data-id="{{ $sampleRequest->Id }}" 
                       >
                        <i class="ti-control-play">&nbsp;Start</i>
                    </button>
                    
                    <button type="button" class="btn btn-sm btn-warning"
                        data-target="#rndUpdate{{ $sampleRequest->Id }}" 
                        data-toggle="modal" 
                        title='RND Update'>
                        <i class="ti-control-play">&nbsp;</i>RND Update
                    </button>
                    
                    
                    
                     --}}
                </div>
            </div>
            <form class="form-horizontal" id="form_product" enctype="multipart/form-data">
                <div class="form-header">
                    <span class="header-label"><b>Customer Details</b></span>
                    <hr class="form-divider">
                </div>
                <div class="group-form">
                <div class="form-group row">
                    <p class="col-sm-2 col-form-label"><b>Client Name:</b></p>
                    <p class="col-sm-3 col-form-label">{{ optional($sampleRequest->client)->Name  }}</p>
                    <p class="offset-sm-2 col-sm-2 col-form-label"><b>Contact:</b></p>
                    <p class="col-sm-3 col-form-label">{{ optional($sampleRequest->clientContact)->ContactName}}</p>
                </div>
                 <div class="form-group row">
                    <p class="col-sm-2 col-form-label"><b>Client Trade Name:</b></p>
                    <p class="col-sm-3 col-form-label">{{ optional($sampleRequest->client)->trade_name }}</p>
                    <p class="offset-sm-2 col-sm-2 col-form-label"><b>Telephone:</b></p>
                    <p class="col-sm-3 col-form-label">{{ optional($sampleRequest->clientContact)->PrimaryTelephone}}</p>
                </div>
                <div class="form-group row">
                    <p class="col-sm-2 col-form-label"><b>Region:</b></p>
                    <p class="col-sm-3 col-form-label">{{ optional(optional($sampleRequest->client)->clientregion)->Name }}</p>
                    <p class="offset-sm-2 col-sm-2 col-form-label"><b>Mobile:</b></p>
                    <p class="col-sm-3 col-form-label">{{ optional($sampleRequest->clientContact)->PrimaryMobile}}</p>
                </div>
                <div class="form-group row">
                    <p class="col-sm-2 col-form-label"><b>Country:</b></p>
                    <p class="col-sm-3 col-form-label">{{ optional(optional($sampleRequest->client)->clientcountry)->Name }}</p>
                    <p class="offset-sm-2 col-sm-2 col-form-label"><b>Email:</b></p>
                    <p class="col-sm-3 col-form-label">{{ optional($sampleRequest->clientContact)->EmailAddress}}</p>
                </div>
                <div class="form-group row">
                    <p class="col-sm-2 col-form-label"><b></b></p>
                    <p class="col-sm-3 col-form-label"></p>
                    <p class="offset-sm-2 col-sm-2 col-form-label"><b>Skype:</b></p>
                    <p class="col-sm-3 col-form-label">{{ optional($sampleRequest->clientContact)->Skype}}</p>
                </div>
            </div>
            <div class="form-header">
                <span class="header-label"><b>Request Details</b></span>
                <hr class="form-divider">
            </div>
            <div class="group-form">
            <div class="form-group row">
                <p class="col-sm-2 col-form-label"><b>SRF #:</b></p>
                <p class="col-sm-3 col-form-label">{{ $sampleRequest->SrfNumber }}</p>
                <p class="offset-sm-2 col-sm-2 col-form-label"><b>Primary Sales Person:</b></p>
                <p class="col-sm-3 col-form-label">{{ optional($sampleRequest->primarySalesPerson)->full_name}}</p>
            </div>
             <div class="form-group row">
                <p class="col-sm-2 col-form-label"><b>Date Requested :</b></p>
                <p class="col-sm-3 col-form-label">{{ $sampleRequest->DateRequested }}</p>
                <p class="offset-sm-2 col-sm-2 col-form-label"><b>Secondary Sales Person:</b></p>
                <p class="col-sm-3 col-form-label">{{ optional($sampleRequest->secondarySalesPerson)->full_name}}</p>
            </div>
            <div class="form-group row">
                <p class="col-sm-2 col-form-label"><b>Date Required :</b></p>
                <p class="col-sm-3 col-form-label">{{ $sampleRequest->DateRequired }}</p>
                <p class="offset-sm-2 col-sm-2 col-form-label"><b></b></p>
                <p class="col-sm-3 col-form-label"></p>
            </div>
            <div class="form-group row">
                <p class="col-sm-2 col-form-label"><b>Date Started :</b></p>
                <p class="col-sm-3 col-form-label">{{ $sampleRequest->DateStarted }}</p>
                <p class="offset-sm-2 col-sm-2 col-form-label"><b>Status:</b></p>
                <p class="col-sm-3 col-form-label">
                    @if($sampleRequest->Status == 10)
                        Open
                    @elseif($sampleRequest->Status == 30)
                        Closed
                    @elseif($sampleRequest->Status == 50)
                        Cancelled
                    @else
                        {{ $sampleRequest->Status }}
                    @endif</p>
            </div>
            <div class="form-group row">
                <p class="col-sm-2 col-form-label"></p>
                <p class="col-sm-3 col-form-label"></p>
                <p class="offset-sm-2 col-sm-2 col-form-label"><b>Progress:</b></p>
                <p class="col-sm-3 col-form-label">{{ optional($sampleRequest->progressStatus)->name  }}</p>
            </div>
            <div class="form-group row">
                <p class="col-sm-2 col-form-label"></p>
                <p class="col-sm-3 col-form-label"></p>
            </div>
            <div class="form-group row">
                <p class="col-sm-2 col-form-label"><b>REF CODE</b></p>
                <p class="col-sm-3 col-form-label">
                    @if($sampleRequest->RefCode == 1)
                        RND
                    @elseif($sampleRequest->RefCode == 2)
                        QCD
                    @else
                        {{ $sampleRequest->RefCode }}
                    @endif
                </p>
            </div>
            <div class="form-group row">
                <p class="col-sm-2 col-form-label"><b>Type</b></p>
                <p class="col-sm-3 col-form-label">
                    @if($sampleRequest->SrfType == 1)
                        Regular
                    @elseif($sampleRequest->SrfType == 2)
                        PSS
                    @elseif($sampleRequest->SrfType == 3)
                        CSS
                    @else
                    {{ $sampleRequest->SrfType }}
                    @endif
                </p>
            </div>
            
            <div class="form-group row">
                <p class="col-sm-2 col-form-label"><b></b></p>
                <p class="col-sm-3 col-form-label"></p>
            </div>
            <div class="form-group row">
                <p class="col-sm-2 col-form-label"><b>Remarks</b></p>
                <p >{{ $sampleRequest->InternalRemarks }}</p>
                <p class="offset-sm-2 col-sm-2 col-form-label"></p>
                <p class="col-sm-3 col-form-label"></p>
            </div>
            <br>
            @foreach ( $sampleRequest->requestProducts as $requestProducts)
                <div class="border">
                    <div class="form-group row">
                        <p class="col-sm-2 col-form-label"><b>Index:</b></p>
                        <p class="col-sm-3 col-form-label">{{ $sampleRequest->SrfNumber}}-{{ $requestProducts->ProductIndex }}</p>
                    </div>
                    <div class="form-group row">
                        <p class="col-sm-2 col-form-label"><b></b></p>
                        <p class="col-sm-3 col-form-label"></p>
                    </div>
                    <div class="form-group row">
                        <p class="col-sm-2 col-form-label"><b>Product Type:</b></p>
                        <p class="col-sm-3 col-form-label">
                            @if($requestProducts->ProductType == 1)
                                Pure
                            @elseif($requestProducts->ProductType == 2)
                                Blend
                            @else
                            {{ $requestProducts->ProductType }}
                            @endif
                        </p>
                        <p class="offset-sm-2 col-sm-2 col-form-label"><b>RPE Number:</b></p>
                        <p class="col-sm-3 col-form-label">
                            @php
                                    $rpeNumber = $requestProducts->RpeNumber;
                                    $rpeId = getRpeIdByNumber($rpeNumber);
                                    if ($rpeId) {
                                     echo '<a href="'.url('product_evaluation/view/'.$rpeId).'">'.$rpeNumber.'</a>';
                                    } else {
                                    echo $rpeNumber;
                                }
                            @endphp
                        </p>
                    </div>
                    <div class="form-group row">
                        <p class="col-sm-2 col-form-label"><b>Application:</b></p>
                        <p class="col-sm-3 col-form-label">{{ $requestProducts->productApplicationsId->Name }}</p>
                        <p class="offset-sm-2 col-sm-2 col-form-label"><b>CRR Number:</b></p>
                        <p class="col-sm-3 col-form-label">
                            @php
                                    $crrNumber = $requestProducts->CrrNumber;
                                    $crr = getCrrIdByNumber($crrNumber);
                                    if ($crr) {
                                     echo '<a href="'.url('view_customer_requirement/'.$crr).'">'.$crrNumber.'</a>';
                                    } else {
                                    echo $crrNumber;
                                }
                            @endphp
                        </p>
                    </div>
                    <div class="form-group row">
                        <p class="col-sm-2 col-form-label"><b>Product Code:</b></p>
                        <p class="col-sm-3 col-form-label">
                            @php
                                    $prodCode = $requestProducts->ProductCode;
                                    $productId = getProductIdByCode($prodCode);
                                    if ($productId) {
                                        echo '<a href="'.url('view_product/'.$productId).'">'.$prodCode.'</a>';
                                    } else {
                                        echo $prodCode; // Or whatever you want to display if the product ID is not found
                                    }
                                @endphp</p>
                    </div>
                    <div class="form-group row">
                        <p class="col-sm-2 col-form-label"><b>Product Description:</b></p>
                        <p class="col-sm-3 col-form-label">{{ $requestProducts->ProductDescription }}</p>
                    </div>
                    <div class="form-group row">
                        <p class="col-sm-2 col-form-label"><b>Number of Packages:</b></p>
                        <p class="col-sm-3 col-form-label">{{ $requestProducts->NumberOfPackages }}</p>
                    </div>
                    <div class="form-group row">
                        <p class="col-sm-2 col-form-label"><b>Quantity:</b></p>
                        <p class="col-sm-3 col-form-label">{{ $requestProducts->Quantity }} 
                            @if ( $requestProducts->UnitOfMeasureId == 1)
                            g
                            @elseif ($requestProducts->UnitOfMeasureId == 2)
                            kg
                            @endif
                           </p>
                    </div>
                    <div class="form-group row">
                        <p class="col-sm-2 col-form-label"><b>Label:</b></p>
                        <p class="col-sm-3 col-form-label">{{ $requestProducts->Label }}</p>
                    </div>
                    <br>
                    <div class="form-group row">
                        <p class="col-sm-2 col-form-label"><b>Remarks</b></p>
                        <p class="col-sm-8 col-form-label">{{ $requestProducts->Remarks }}</p>
                    </div>
                    <div class="form-group row">
                        <p class="col-sm-2 col-form-label"><b>Disposition:</b></p>
                        <p class="col-sm-3 col-form-label">
                            @if ($requestProducts->Disposition == '1')
                                No Feedback
                            @elseif ($requestProducts->Disposition == '10')
                                Accepted
                            @elseif ($requestProducts->Disposition == '20')
                                Rejected
                            @else
                                {{ $requestProducts->Disposition }}
                            @endif
                        </p>
                        <p class="offset-sm-2 col-sm-2 col-form-label"><b>Disposition Remarks</b></p>
                        <p class="col-sm-3 col-form-label">{{ $requestProducts->DispositionRejectionDescription}}</p>
                    </div>
                </div>
                <br>
            @endforeach
            <div class="form-header">
                <span class="header-label"><b>Dispatch Details</b></span>
                <hr class="form-divider">
            </div>
            <div class="form-header">
                <span class="header-label"><b>Approver Remarks</b></span>
                <hr class="form-divider">
            </div>
            <div class="group-form">
            <div class="form-group row">
                <p class="col-sm-2 col-form-label"><b>Courier:</b></p>
                <p class="col-sm-3 col-form-label">{{ $sampleRequest->Courier  }}</p>
                <p class="offset-sm-2 col-sm-2 col-form-label"><b>Late:</b></p>
                <p class="col-sm-3 col-form-label"></p>
            </div>
             <div class="form-group row">
                <p class="col-sm-2 col-form-label"><b>AWB Number:</b></p>
                <p class="col-sm-3 col-form-label">{{ $sampleRequest->AwbNumber }}</p>
                <p class="offset-sm-2 col-sm-2 col-form-label"><b>Delivery Remarks:</b></p>
                <p class="col-sm-3 col-form-label">{{ $sampleRequest->DeliveryRemarks}}</p>
            </div>
            <div class="form-group row">
                <p class="col-sm-2 col-form-label"><b>Date Dispatched:</b></p>
                <p class="col-sm-3 col-form-label">{{ $sampleRequest->DateDispatched }}</p>
                <p class="offset-sm-2 col-sm-2 col-form-label"><b>Note:</b></p>
                <p class="col-sm-3 col-form-label">{{ $sampleRequest->Note}}</p>
            </div>
            <div class="form-group row">
                <p class="col-sm-2 col-form-label"><b>Date Sample Received:</b></p>
                <p class="col-sm-3 col-form-label">{{ $sampleRequest->DateSampleReceived }}</p>
            </div>
        </div>
        </div>
            </form>          
            <ul class="nav nav-tabs" id="productTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="supplementary-tab" data-toggle="tab" href="#supplementary" role="tab" aria-controls="supplementary" aria-selected="true">Supplementary Details</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="srfPersonnel-tab" data-toggle="tab" href="#srfPersonnel" role="tab" aria-controls="srfPersonnel" aria-selected="true">Assigned R&D Personnel</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="activities-tab" data-toggle="tab" href="#activities" role="tab" aria-controls="activities" aria-selected="false">Activities</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="files-tab" data-toggle="tab" href="#files" role="tab" aria-controls="files" aria-selected="false">Files</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="raw-materials-tab" data-toggle="tab" href="#raw_materials" role="tab" aria-controls="rawMaterials" aria-selected="false">Raw Materials</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="history-tab" data-toggle="tab" href="#history" role="tab" aria-controls="history" aria-selected="false">History</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="supplementary" role="tabpanel" aria-labelledby="supplementary-tab">
                    <div class="d-flex">
                        @if(!checkIfItsSalesDept(auth()->user()->department_id))
                            @if($sampleRequest->Progress != 60)
                            <button type="button" class="btn btn-sm btn-primary ml-auto m-3" title="Add Supplementary Details" data-toggle="modal" data-target="#addSrfSuplementary">
                                <i class="ti-plus"></i>
                            </button>
                            @endif
                        @elseif (checkIfItsSalesDept(auth()->user()->department_id))
                            @if($sampleRequest->Progress != 60 && $sampleRequest->Progress != 10)
                            <button type="button" class="btn btn-sm btn-primary ml-auto m-3" title="Add Supplementary Details" data-toggle="modal" data-target="#addSrfSuplementary">
                                <i class="ti-plus"></i>
                            </button>
                            @endif
                        @endif
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover table-detailed" id="supplementary_table" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Actions</th>
                                    <th>Date</th>
                                    <th>Name</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($SrfSupplementary as $supplementary)
                                    <tr>
                                        <td align="center">
                                        @if(!checkIfItsSalesDept(auth()->user()->department_id))
                                        @if($sampleRequest->Progress != 60)
                                            <button type="button"  class="btn btn-sm btn-warning btn-outline"
                                                data-target="#editSrfSupplementary{{ $supplementary->id }}" data-toggle="modal" title='Edit Supplementary'>
                                                <i class="ti-pencil"></i>
                                            </button>   
                                            <button type="button" class="btn btn-sm btn-danger btn-outline" onclick="confirmDelete({{ $supplementary->id }}, 'supplementary')" title='Delete Supplementary'>
                                                <i class="ti-trash"></i>
                                            </button> 
                                        @endif
                                    @elseif(checkIfItsSalesDept(auth()->user()->department_id))
                                        @if($sampleRequest->Progress != 60 && $sampleRequest->Progress != 10)
                                            <button type="button"  class="btn btn-sm btn-warning btn-outline"
                                                data-target="#editSrfSupplementary{{ $supplementary->id }}" data-toggle="modal" title='Edit Supplementary'>
                                                <i class="ti-pencil"></i>
                                            </button>   
                                            <button type="button" class="btn btn-sm btn-danger btn-outline" onclick="confirmDelete({{ $supplementary->id }}, 'supplementary')" title='Delete Supplementary'>
                                                <i class="ti-trash"></i>
                                            </button> 
                                        @endif
                                    @endif
                                        </td>
                                        <td>{{ $supplementary->DateCreated }}</td>
                                        <td>{{ optional($supplementary->userSupplementary)->full_name }}</td>
                                        <td>{{ $supplementary->DetailsOfRequest }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="srfPersonnel" role="tabpanel" aria-labelledby="srfPersonnel-tab">
                    <div class="d-flex">
                        @if(!checkIfItsSalesDept(auth()->user()->department_id))
                        @if($sampleRequest->Progress != 55 && $sampleRequest->Progress != 57 && $sampleRequest->Progress != 60 && $sampleRequest->Progress != 81 && rndManager(auth()->user()->role))
                        <button type="button" class="btn btn-sm btn-primary ml-auto m-3" title="Assign R&D"  data-toggle="modal" data-target="#addSrfPersonnel">
                            <i class="ti-plus"></i>
                        </button>
                        @endif
                        @endif
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover table-detailed" id="personnel_table" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Actions</th>
                                    <th>Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($assignedPersonnel as $Personnel)
                                    <tr>
                                        <td align="center">
                                            @if(rndManager(auth()->user()->role) && $sampleRequest->Progress != 57 && $sampleRequest->Progress != 60 && $sampleRequest->Progress != 81)
                                            <button type="button"  class="btn btn-sm btn-warning btn-outline"
                                                data-target="#editSrfPersonnel{{ $Personnel->Id }}" data-toggle="modal" title='Edit Personnel'>
                                                <i class="ti-pencil"></i>
                                            </button>   
                                            <button type="button" class="btn btn-sm btn-danger btn-outline" onclick="confirmDelete({{ $Personnel->Id }}, 'personnel')" title='Delete Personnel'>
                                                <i class="ti-trash"></i>
                                            </button> 
                                            @endif
                                        </td>
                                        <td>{{ optional($Personnel->assignedPersonnel)->full_name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="activities" role="tabpanel" aria-labelledby="activities-tab">
                    <div class="d-flex">
                        @if(checkIfItsSalesDept(auth()->user()->department_id))
                        <button type="button" class="btn btn-sm btn-primary ml-auto m-3" title="Create Activity"  data-toggle="modal" data-target="#createSrfActivity">
                            <i class="ti-plus"></i>
                        </button>
                        @endif
                    </div>
                    <div class="table-responsive">
                        <div class="filter">
                            <label><input type="checkbox" class="status-filter" value="10" checked> Open</label>
                            <label><input type="checkbox" class="status-filter" value="20" checked> Closed</label>
                        </div>
                        <table class="table table-striped table-bordered table-hover table-detailed" id="activities_table" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>#</th>
                                    <th>Schedule</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($activities as $a)
                                    <tr  data-status="{{ $a->Status }}">
                                        <td>
                                            @if(checkIfItsSalesDept(auth()->user()->department_id))
                                            <button type="button"  class="btn btn-sm btn-warning btn-outline"
                                                data-target="#editSrfActivity{{ $a->id }}" data-toggle="modal" title='Edit Activity'>
                                                <i class="ti-pencil"></i>
                                            </button>   
                                            <button type="button" class="btn btn-sm btn-danger btn-outline" onclick="confirmDelete({{ $a->id }}, 'activity')" title='Delete Activity'>
                                                <i class="ti-trash"></i>
                                            </button> 
                                            @endif
                                            </td>
                                        <td> <a href="{{url('view_activity/'.$a->id)}}" target="_blank">
                                            {{$a->ActivityNumber}}
                                        </a></td>
                                        <td>
                                            {{ optional($a)->ScheduleFrom ? optional($a)->ScheduleFrom : '' }}
                                            -
                                            {{ optional($a)->ScheduleTo ? optional($a)->ScheduleTo : '' }}
                                        </td>
                                        <td>{{ optional($a)->Title  }}</td>
                                        <td>
                                            @if($a->Status == 10)
                                            Open
                                            @elseif($a->Status == 20)
                                            Closed
                                            @else
                                            {{ $a->Status }}
                                            @endif</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="files" role="tabpanel" aria-labelledby="files-tab">
                    <div class="d-flex">
                        @if(checkIfHaveFiles(auth()->user()->role) == "yes")
                        <button type="button" class="btn btn-sm btn-primary ml-auto m-3" title="Upload File"  data-toggle="modal" data-target="#uploadFile">
                            <i class="ti-plus"></i>
                        </button>
                        @endif
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover table-detailed" id="files_table" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Actions</th>
                                    <th>Name</th>
                                    <th>File</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($srfFileUploads as $fileupload)
                                    <tr>
                                        <td align="center">
                                            @if(checkIfHaveFiles(auth()->user()->role) == "yes")
                                            <button type="button"  class="btn btn-sm btn-warning btn-outline"
                                                data-target="#editSrfFile{{ $fileupload->Id }}" data-toggle="modal" title='Edit fileupload'>
                                                <i class="ti-pencil"></i>
                                            </button>   
                                            <button type="button" class="btn btn-sm btn-danger btn-outline" onclick="confirmDelete({{ $fileupload->Id }}, 'fileupload')" title='Delete fileupload'>
                                                <i class="ti-trash"></i>
                                            </button> 
                                            @endif
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
                <div class="tab-pane fade" id="raw_materials" role="tabpanel" aria-labelledby="raw-materials-tab">
                    <div class="d-flex">
                        <button type="button" class="btn btn-sm btn-primary ml-auto m-3" title="Add Raw Material"  data-toggle="modal" data-target="#addRawMaterial">
                            <i class="ti-plus"></i>
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover table-detailed" id="raw_materials_table" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Actions</th>
                                    <th>Material</th>
                                    <th>Lot Number</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($SrfMaterials as $SrfMaterial)
                                    <tr>
                                        <td align="center">
                                            <button type="button"  class="btn btn-sm btn-warning btn-outline"
                                                data-target="#editSrfMaterial{{ $SrfMaterial->Id }}" data-toggle="modal" title='Edit SrfMaterial'>
                                                <i class="ti-pencil"></i>
                                            </button>   
                                            <button type="button" class="btn btn-sm btn-danger btn-outline" onclick="confirmDelete({{ $SrfMaterial->Id }}, 'SrfMaterial')" title='Delete Raw Material'>
                                                <i class="ti-trash"></i>
                                            </button> 
                                        </td>
                                        <td>{{ $SrfMaterial->productMaterial->Name }}</td>
                                        <td>{{ $SrfMaterial->LotNumber }}</td>
                                        <td>{{ $SrfMaterial->Remarks }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover table-detailed" style="width:100%;">
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
            if (type === 'supplementary') {
                url = '{{ url('samplerequest/view/supp-delete') }}/' + id;
            } else if (type === 'personnel') {
                url = '{{ url('samplerequest/view/personnel-delete') }}/' + id;
            }else if (type === 'activity') {
                url = '{{ url('samplerequest/view/activity-delete') }}/' + id;
            } else if (type === 'fileupload') {
                url = '{{ url('samplerequest/view/file-delete') }}/' + id;
            } else if (type === 'SrfMaterial') {
                url = '{{ url('samplerequest/view/material-delete') }}/' + id;
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

    $(".receiveSrf").on('click', function() {
            var srfId = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "You want to receive this request!",
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
                        url: "{{ url('ReceiveSrf') }}/" + srfId,
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

        $(".startSrf").on('click', function() {
            var srfId = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "You want to start this request!",
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
                        url: "{{ url('StartSrf') }}/" + srfId,
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

        $(".returnToSales").on('click', function() {
            var srfId = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "You want to return this request!",
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
                        url: "{{ url('ReturnToSales') }}/" + srfId,
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

        $(".returnToRnd").on('click', function() {
            var srfId = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "You want to return this request!",
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
                        url: "{{ url('ReturnToRnd') }}/" + srfId,
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

        $(".salesAccepted").on('click', function() {
            var srfId = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "You want to accept this request!",
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
                        url: "{{ url('AcceptSrf') }}/" + srfId,
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

        $(".submitSrf").on('click', function() {
            var srfId = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "You want to submit this request!",
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
                        url: "{{ url('SubmitSrf') }}/" + srfId,
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
        
        $(".completeSrf").on('click', function() {
            var srfId = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "You want to submit this request!",
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
                        url: "{{ url('CompleteSrf') }}/" + srfId,
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

        $(".openStatus").on('click', function() {
            var srfId = $(this).data('id');

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
                        url: "{{ url('OpenStatus') }}/" + srfId,
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

    $(document).ready(function() {
        new DataTable('.table-detailed', {
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
    document.addEventListener('DOMContentLoaded', function () {
    @if(session('error'))
        var isManager = @json(auth()->user()->role->description == 'International Sales - Supervisor' || auth()->user()->role->description == 'Local Sales - Supervisor');
        var errorMessage = @json(session('error'));
        var formType = @json(session('formType')); 

        if (isManager) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: errorMessage,
                showCancelButton: true,
                confirmButtonText: 'Proceed',
                cancelButtonText: 'Cancel',
                input: 'textarea',
                inputPlaceholder: 'Enter remarks...',
                inputValidator: (value) => {
                    if (!value) {
                        return 'You need to write something!';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    var remarks = result.value;
                    var form;

                    if (formType === 'create') {
                        form = document.getElementById('create_srf_form');
                    } else if (formType === 'update') {
                        var srfId = @json(session('srfId'));
                        form = document.getElementById('edit_sample_request' + srfId);
                    }

                    if (form) {
                        var input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'quantity_remarks';
                        input.value = remarks;
                        form.appendChild(input);
                        form.submit();
                    }
                }
            });
        } else {
            $('#formSampleRequest').modal('show');
            var $errorMessage = $('#formSampleRequest .error-message');
            $errorMessage.text(errorMessage).show();
        }
    @elseif(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: "{{ session('success') }}",
            confirmButtonText: 'OK'
        });
    @endif
});
    </script>
@include('sample_requests.create_supplementary')
@include('sample_requests.assign_personnel')
@include('sample_requests.upload_srf_file')
@include('sample_requests.create_raw_materials')
@include('sample_requests.create_activity')

@include('sample_requests.srf_start')
@include('sample_requests.srf_pause')
@include('sample_requests.rnd_update')
@include('sample_requests.srf_approval')
@include('sample_requests.cancel_srf')
@include('sample_requests.close_srf')
@include('sample_requests.edit_sales')
{{-- @include('sample_requests.srf_receive') --}}


@foreach ($SrfSupplementary as $supplementary)
@include('sample_requests.edit_supplementary')
@endforeach
@foreach ($assignedPersonnel as $Personnel)
@include('sample_requests.edit_personnel')
@endforeach
@foreach ($srfFileUploads as $fileupload)
@include('sample_requests.edit_files')
@endforeach
@foreach ($SrfMaterials as $SrfMaterial)
@include('sample_requests.edit_material')
@endforeach
@foreach ($activities as $activity)
@include('sample_requests.edit_activity')
@endforeach
@endsection
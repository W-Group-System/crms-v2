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
                <div class="col-lg-6" align="right">
                    <a href="{{ url('/customer_feedback') }}" class="btn btn-md btn-light"><i class="icon-arrow-left"></i>&nbsp;Back</a>
                    {{-- <a href="{{ url('/customer_feedback') }}" class="btn btn-sm btn-warning"><i class="icon-arrow-left"></i><br>Update</a> --}}
                    @if ($sampleRequest->Progress == 10)
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
                    @endif
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
                    <p class="col-sm-3 col-form-label">{{ $sampleRequest->client->Name  }}</p>
                    <p class="offset-sm-2 col-sm-2 col-form-label"><b>Contact:</b></p>
                    <p class="col-sm-3 col-form-label">{{ optional($sampleRequest->clientContact)->ContactName}}</p>
                </div>
                 <div class="form-group row">
                    <p class="col-sm-2 col-form-label"><b>Client Trade Name:</b></p>
                    <p class="col-sm-3 col-form-label">{{ $sampleRequest->client->trade_name }}</p>
                    <p class="offset-sm-2 col-sm-2 col-form-label"><b>Telephone:</b></p>
                    <p class="col-sm-3 col-form-label">{{ optional($sampleRequest->clientContact)->PrimaryTelephone}}</p>
                </div>
                <div class="form-group row">
                    <p class="col-sm-2 col-form-label"><b>Region:</b></p>
                    <p class="col-sm-3 col-form-label">{{ $sampleRequest->client->clientregion->Name }}</p>
                    <p class="offset-sm-2 col-sm-2 col-form-label"><b>Mobile:</b></p>
                    <p class="col-sm-3 col-form-label">{{ optional($sampleRequest->clientContact)->PrimaryMobile}}</p>
                </div>
                <div class="form-group row">
                    <p class="col-sm-2 col-form-label"><b>Country:</b></p>
                    <p class="col-sm-3 col-form-label">{{ optional($sampleRequest->client->clientcountry)->Name }}</p>
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
                <p class="col-sm-3 col-form-label">{{ $sampleRequest->primarySalesPerson->full_name}}</p>
            </div>
             <div class="form-group row">
                <p class="col-sm-2 col-form-label"><b>Date Requested :</b></p>
                <p class="col-sm-3 col-form-label">{{ $sampleRequest->DateRequested }}</p>
                <p class="offset-sm-2 col-sm-2 col-form-label"><b>Primary Sales Person:</b></p>
                <p class="col-sm-3 col-form-label">{{ $sampleRequest->secondarySalesPerson->full_name}}</p>
            </div>
            <div class="form-group row">
                <p class="col-sm-2 col-form-label"><b>Date Started :</b></p>
                <p class="col-sm-3 col-form-label">{{ $sampleRequest->DateStarted }}</p>
            </div>
            <div class="form-group row">
                <p class="col-sm-2 col-form-label"></p>
                <p class="col-sm-3 col-form-label"></p>
                <p class="offset-sm-2 col-sm-2 col-form-label"><b>Status:</b></p>
                <p class="col-sm-3 col-form-label">
                    @if($sampleRequest->Status == 10)
                    Open
                    @elseif($sampleRequest->Status == 30)
                        Closed
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
                            @if($requestProducts->SrfType == 1)
                                Pure
                            @elseif($requestProducts->ProductType == 2)
                                Blend
                            @else
                            {{ $requestProducts->ProductType }}
                            @endif
                        </p>
                        <p class="offset-sm-2 col-sm-2 col-form-label"><b>RPE Number:</b></p>
                        <p class="col-sm-3 col-form-label">{{ $requestProducts->RpeNumber}}</p>
                    </div>
                    <div class="form-group row">
                        <p class="col-sm-2 col-form-label"><b>Application:</b></p>
                        <p class="col-sm-3 col-form-label">{{ $requestProducts->productApplicationsId->Name }}</p>
                        <p class="offset-sm-2 col-sm-2 col-form-label"><b>CRR Number:</b></p>
                        <p class="col-sm-3 col-form-label">{{ $requestProducts->CrrNumber}}</p>
                    </div>
                    <div class="form-group row">
                        <p class="col-sm-2 col-form-label"><b>Product Code:</b></p>
                        <p class="col-sm-3 col-form-label">{{ $requestProducts->ProductCode }}</p>
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
                        <p class="col-sm-3 col-form-label">{{ $requestProducts->Quantity }}</p>
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
                        <p class="col-sm-3 col-form-label"></p>
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
            <div class="group-form">
            <div class="form-group row">
                <p class="col-sm-2 col-form-label"><b>Courier:</b></p>
                <p class="col-sm-3 col-form-label">{{ $sampleRequest->Courier  }}</p>
                <p class="offset-sm-2 col-sm-2 col-form-label"><b>Late:</b></p>
                <p class="col-sm-3 col-form-label">{{ optional($sampleRequest->clientContact)->ContactName}}</p>
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
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="supplementary" role="tabpanel" aria-labelledby="supplementary-tab">
                    <button type="button" class="btn btn-md btn-primary" title="Add Supplementary Details"  data-toggle="modal" data-target="#addSrfSuplementary">+ Supplementary Details</button>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="sample_request_table">
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
                                            <button type="button"  class="btn btn-warning btn-outline"
                                                data-target="#editSrfSupplementary{{ $supplementary->id }}" data-toggle="modal" title='Edit Supplementary'>
                                                <i class="ti-pencil"></i>
                                            </button>   
                                            <button type="button" class="btn btn-danger btn-outline" onclick="confirmDelete({{ $supplementary->id }})" title='Delete Supplementary'>
                                                <i class="ti-trash"></i>
                                            </button> 
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
                    <button type="button" class="btn btn-md btn-primary" title="Assign R&D"  data-toggle="modal" data-target="#addSrfPersonnel">+ R&D Personnel</button>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="sample_request_table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($assignedPersonnel as $Personnel)
                                    <tr>
                                        <td>{{ optional($Personnel->assignedPersonnel)->full_name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="activities" role="tabpanel" aria-labelledby="activities-tab">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="sample_request_table">
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
                <div class="tab-pane fade" id="files" role="tabpanel" aria-labelledby="files-tab">
                    <button type="button" class="btn btn-md btn-primary" title="Upload File"  data-toggle="modal" data-target="#uploadFile">Upload File</button>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="sample_request_table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>File</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($srfFileUploads as $fileupload)
                                    <tr>
                                        <td>{{ $fileupload->Name }}</td>
                                        {{-- <td>{{ optional($srfFile)->Path }}</td> --}}
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
                    url: '{{ url('samplerequest/view/supp-delete') }}/' + id,
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
    </script>
@include('sample_requests.create_supplementary')
@include('sample_requests.assign_personnel')
@include('sample_requests.upload_srf_file')
@foreach ($sampleRequest as $srf)
    @include('sample_requests.srf_approval')
    @include('sample_requests.srf_receive')
    @include('sample_requests.srf_start')
    @include('sample_requests.srf_pause')
@endforeach
@foreach ($SrfSupplementary as $supplementary)
@include('sample_requests.edit_supplementary')
@endforeach
@endsection
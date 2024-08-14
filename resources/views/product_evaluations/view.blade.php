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
                @include('components.error')
                <div class="col-lg-12" align="right">
                    <a href="{{ url('/sample_request') }}" class="btn btn-md btn-light"><i class="icon-arrow-left"></i>&nbsp;Back</a>
                    {{-- @if ($requestEvaluation->Progress == 10)
                        <button type="button" class="btn btn-sm btn-success"
                                data-target="#approveSrf{{ $requestEvaluation->id }}" 
                                data-toggle="modal" 
                                title='Approve SRF'>
                            <i class="ti-check"><br>Approve</i>
                        </button>
                    @elseif ($requestEvaluation->Progress == 30)
                        <button type="button" class="btn btn-sm btn-success"
                                data-target="#receiveSrf{{ $requestEvaluation->id }}" 
                                data-toggle="modal" 
                                title='Receive SRF'>
                            <i class="ti-check"><br>Receive</i>
                        </button>
                    @endif
                    @if ($requestEvaluation->Progress == 50)
                        <button type="button" class="btn btn-sm btn-warning"
                        data-target="#pauseSrf{{ $requestEvaluation->id }}" 
                        data-toggle="modal" 
                        title='Pause SRF'>
                        <i class="ti-control-pause"><br>Pause</i>
                    </button>
                    @else 
                    <button type="button" class="btn btn-sm btn-warning"
                        data-target="#startSrf{{ $requestEvaluation->id }}" 
                        data-toggle="modal" 
                        title='Start SRF'>
                        <i class="ti-control-play"><br>Start</i>
                    </button>
                    @endif --}}
                    {{-- <button type="button" class="btn btn-md btn-success"
                        data-target="#approveSrf{{ $requestEvaluation->id }}" 
                        data-toggle="modal" 
                        title='Approve SRF'>
                        <i class="ti-check">&nbsp;Approve</i>
                    </button>
                    <button type="button" class="btn btn-md btn-success"
                        data-target="#receiveSrf{{ $requestEvaluation->id }}" 
                        data-toggle="modal" 
                        title='Receive SRF'>
                    <i class="ti-check">&nbsp;Receive</i>
                    </button>
                    <button type="button" class="btn btn-md btn-warning"
                        data-target="#startSrf{{ $requestEvaluation->id }}" 
                        data-toggle="modal" 
                        title='Start SRF'>
                        <i class="ti-control-play">&nbsp;Start</i>
                    </button>
                    <button type="button" class="btn btn-md btn-warning"
                        data-target="#pauseSrf{{ $requestEvaluation->id }}" 
                        data-toggle="modal" 
                        title='Pause SRF'>
                        <i class="ti-control-pause">&nbsp;Pause</i>
                    </button> --}}
                    <button type="button" class="btn btn-md btn-warning"
                        data-target="#cancelRpe{{ $requestEvaluation->id }}" 
                        data-toggle="modal">
                        <i class="ti-na"></i>&nbsp;Cancel
                    </button>
                    <button type="button" class="btn btn-md btn-warning"
                        data-target="#closeRpe{{ $requestEvaluation->id }}" 
                        data-toggle="modal">
                        <i class="ti-file"></i>&nbsp;Close
                    </button>
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
                    <p class="col-sm-3 col-form-label">
                        <a href="{{ url('view_client/' . $requestEvaluation->client->id) }}">
                            {{ $requestEvaluation->client->Name }}
                        </a>
                    </p>
                </div>
                 <div class="form-group row">
                    <p class="col-sm-2 col-form-label"><b>Client Trade Name:</b></p>
                    <p class="col-sm-3 col-form-label">{{ $requestEvaluation->client->TradeName }}</p>
                </div>
                <div class="form-group row">
                    <p class="col-sm-2 col-form-label"><b>Region:</b></p>
                    <p class="col-sm-3 col-form-label">{{ $requestEvaluation->client->clientregion->Name }}</p>
                </div>
                <div class="form-group row">
                    <p class="col-sm-2 col-form-label"><b>Country:</b></p>
                    <p class="col-sm-3 col-form-label">{{ optional($requestEvaluation->client->clientcountry)->Name }}</p>
                </div>
            </div>
            <div class="form-header">
                <span class="header-label"><b>Request Details</b></span>
                <hr class="form-divider">
            </div>
            <div class="group-form">
            <div class="form-group row">
                <p class="col-sm-2 col-form-label"><b>RPE #:</b></p>
                <p class="col-sm-3 col-form-label">{{ $requestEvaluation->RpeNumber }}</p>
                <p class="offset-sm-2 col-sm-2 col-form-label"><b>Primary Sales Person:</b></p>
                <p class="col-sm-3 col-form-label">{{ $requestEvaluation->primarySalesPerson->full_name}}</p>
            </div>
             <div class="form-group row">
                <p class="col-sm-2 col-form-label"><b>Date Requested :</b></p>
                <p class="col-sm-3 col-form-label">{{ $requestEvaluation->CreatedDate }}</p>
                <p class="offset-sm-2 col-sm-2 col-form-label"><b>Primary Sales Person:</b></p>
                <p class="col-sm-3 col-form-label">{{ $requestEvaluation->secondarySalesPerson->full_name}}</p>
            </div>
            <div class="form-group row">
                <p class="col-sm-2 col-form-label"><b>Date Required :</b></p>
                <p class="col-sm-3 col-form-label">{{ $requestEvaluation->DueDate }}</p>
            </div>
            <div class="form-group row">
                <p class="col-sm-2 col-form-label"><b>Priority :</b></p>
                <p class="col-sm-3 col-form-label">
                    @if($requestEvaluation->Priority == 1)
                        IC Application
                    @elseif($requestEvaluation->Priority == 3)
                        Second Priority
                    @elseif($requestEvaluation->Priority == 5)
                        First Priority
                    @else
                        {{ $requestEvaluation->Priority }}
                    @endif
                </p>
                <p class="offset-sm-2 col-sm-2 col-form-label"><b>Attention To:</b></p>
                <p class="col-sm-3 col-form-label">
                    @if($requestEvaluation->AttentionTo == 1)
                        RND
                    @elseif($requestEvaluation->AttentionTo == 2)
                        QCD
                    @else
                        {{ $requestEvaluation->AttentionTo }}
                    @endif</p>
            </div>
            <div class="form-group row">
                <p class="col-sm-2 col-form-label"></p>
                <p class="col-sm-3 col-form-label"></p>
                <p class="offset-sm-2 col-sm-2 col-form-label"><b>Status:</b></p>
                <p class="col-sm-3 col-form-label">
                    @if($requestEvaluation->Status == 10)
                        Open
                    @elseif($requestEvaluation->Status == 30)
                        Closed
                    @elseif($requestEvaluation->Status == 50)
                        Cancelled
                    @else
                        {{ $requestEvaluation->Status }}
                    @endif</p>
            </div>
            <div class="form-group row">
                <p class="col-sm-2 col-form-label"></p>
                <p class="col-sm-3 col-form-label"></p>
                <p class="offset-sm-2 col-sm-2 col-form-label"><b>Progress:</b></p>
                <p class="col-sm-3 col-form-label">{{ optional($requestEvaluation->progressStatus)->name  }}</p>
            </div>
            <div class="form-group row">
                <p class="col-sm-2 col-form-label"><b>Project Name</b></p>
                <p class="col-sm-3 col-form-label">{{ optional($requestEvaluation->projectName)->Name  }}</p>
                <p class="offset-sm-2 col-sm-2 col-form-label"><b>Sample Name:</b></p>
                <p class="col-sm-3 col-form-label">{{ $requestEvaluation->SampleName  }}</p>
            </div>
            <div class="form-group row">
                <p class="col-sm-2 col-form-label"><b>Application</b></p>
                <p class="col-sm-3 col-form-label">{{ optional($requestEvaluation->product_application)->Name  }}</p>
                <p class="offset-sm-2 col-sm-2 col-form-label"><b>Manufacturer:</b></p>
                <p class="col-sm-3 col-form-label">{{ $requestEvaluation->Manufacturer  }}</p>
            </div>
            <div class="form-group row">
                <p class="col-sm-2 col-form-label"><b>Potential Volume :                </b></p>
                <p class="col-sm-3 col-form-label">{{ $requestEvaluation->PotentialVolume }} 
                    @if ($requestEvaluation->UnitOfMeasureId == 1)
                        g
                    @elseif ($requestEvaluation->UnitOfMeasureId == 2)
                        kg
                    @endif
                </p>
                <p class="offset-sm-2 col-sm-2 col-form-label"><b>Supplier :</b></p>
                <p class="col-sm-3 col-form-label">{{ $requestEvaluation->Supplier  }}</p>
            </div>
            
            <div class="form-group row">
                <p class="col-sm-2 col-form-label"><b>Target Raw Price</b></p>
                <p class="col-sm-3 col-form-label">{{ optional($requestEvaluation->priceCurrency)->Name  }} {{ $requestEvaluation->TargetRawPrice  }}</p>
            </div>
            <div class="form-group row">
                <p class="col-sm-2 col-form-label"><b>Objective for RPE Project</b></p>
                <p class="col-sm-3 col-form-label">{{ $requestEvaluation->ObjectiveForRpeProject }}</p>
                <p class="offset-sm-2 col-sm-2 col-form-label"></p>
                <p class="col-sm-3 col-form-label"></p>
            </div>
            <br>
            <div class="form-header">
                <span class="header-label"><b>Approver Remarks</b></span>
                <hr class="form-divider">
            </div>
            @foreach ( $rpeTransactionApprovals as $rpeTransactionApproval )
            <div class="group-form">
                <div class="form-group row">
                    <p class="col-sm-12 col-form-label"><b>{{ $rpeTransactionApproval->approverRPE->full_name  }}:</b></p>
                    <br><br>
                    <p class="col-sm-12 col-form-label" style="margin-left: 30px;">{{ $rpeTransactionApproval->Remarks  }}</p>
                </div>
            </div>
            @endforeach
            <br>
            <div class="form-header">
                <span class="header-label"><b>Evaluation Details</b></span>
                <hr class="form-divider">
            </div>
            <div class="group-form">
            <div class="form-group row">
                <p class="col-sm-2 col-form-label"><b>DDW Number:</b></p>
                <p class="col-sm-3 col-form-label">{{ $requestEvaluation->DdwNumber  }}</p>
                <p class="offset-sm-2 col-sm-2 col-form-label"><b>Date Received:</b></p>
                <p class="col-sm-3 col-form-label">{{ $requestEvaluation->DateReceived}}</p>
            </div>
             <div class="form-group row">
                <p class="col-sm-2 col-form-label"><b>RPE Recommendation:</b></p>
                @php
                use App\Helpers\Helpers;
            
                $rpeResult = $requestEvaluation->RpeResult;
                $pattern = '/\[(.*?)\]/';
            
                $rpeResultLinked = preg_replace_callback($pattern, function($matches) {
                    $code = $matches[1];
                    $productId = Helpers::getProductIdByCode($code);
                    if ($productId) {
                        return '<a href="'.url('view_product/'.$productId).'">'.$matches[0].'</a>';
                    }
                    return $matches[0];
                }, $rpeResult);
            @endphp            
                
                <p class="col-sm-3 col-form-label">{!! $rpeResultLinked !!}</p>
                <p class="offset-sm-2 col-sm-2 col-form-label"><b>Date Started:</b></p>
                <p class="col-sm-3 col-form-label">{{ $requestEvaluation->DateStarted}}</p>
            </div>
            <div class="form-group row">
                <p class="col-sm-2 col-form-label"><b></b></p>
                <p class="col-sm-3 col-form-label"></p>
                <p class="offset-sm-2 col-sm-2 col-form-label"><b>Date Completed:</b></p>
                <p class="col-sm-3 col-form-label">{{ $requestEvaluation->DateCompleted}}</p>
            </div>
            <div class="form-group row">
                <p class="col-sm-2 col-form-label"><b></b></p>
                <p class="col-sm-3 col-form-label"></p>
                <p class="offset-sm-2 col-sm-2 col-form-label"><b>Lead Time:</b></p>
                <p class="col-sm-3 col-form-label"></p>
            </div>
            <div class="form-group row">
                <p class="col-sm-2 col-form-label"><b></b></p>
                <p class="col-sm-3 col-form-label"></p>
                <p class="offset-sm-2 col-sm-2 col-form-label"><b>Delayed :</b></p>
                <p class="col-sm-3 col-form-label"></p>
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
                    <a class="nav-link" id="history-tab" data-toggle="tab" href="#history" role="tab" aria-controls="history" aria-selected="false">History</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="supplementary" role="tabpanel" aria-labelledby="supplementary-tab">
                    <div class="d-flex">
                        <button type="button" class="btn btn-sm btn-primary ml-auto m-3" title="Add Supplementary Details" data-toggle="modal" data-target="#addRpeSuplementary">
                            <i class="ti-plus"></i>
                        </button>
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
                                @foreach ($RpeSupplementary as $supplementary)
                                    <tr>
                                        <td align="center">
                                            <button type="button"  class="btn btn-sm btn-warning btn-outline"
                                                data-target="#editRpeSupplementary{{ $supplementary->Id }}" data-toggle="modal" title='Edit Supplementary'>
                                                <i class="ti-pencil"></i>
                                            </button>   
                                            <button type="button" class="btn btn-sm btn-danger btn-outline" onclick="confirmDelete({{ $supplementary->Id }}, 'supplementary')" title='Delete Supplementary'>
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
                    <div class="d-flex">
                        <button type="button" class="btn btn-sm btn-primary ml-auto m-3" title="Assign R&D"  data-toggle="modal" data-target="#addRpePersonnel">
                            <i class="ti-plus"></i>
                        </button>
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
                                            <button type="button"  class="btn btn-sm btn-warning btn-outline"
                                                data-target="#editRpePersonnel{{ $Personnel->Id }}" data-toggle="modal" title='Edit Personnel'>
                                                <i class="ti-pencil"></i>
                                            </button>   
                                            <button type="button" class="btn btn-sm btn-danger btn-outline" onclick="confirmDelete({{ $Personnel->Id }}, 'personnel')" title='Delete Personnel'>
                                                <i class="ti-trash"></i>
                                            </button> 
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
                        <button type="button" class="btn btn-sm btn-primary ml-auto m-3" title="Create Activity"  data-toggle="modal" data-target="#createRpeActivity">
                            <i class="ti-plus"></i>
                        </button>
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
                                @foreach ($activities as $activity)
                                    <tr data-status="{{ $activity->Status }}">
                                        <td>
                                            <button type="button"  class="btn btn-sm btn-warning btn-outline"
                                                data-target="#editRpeActivity{{ $activity->id }}" data-toggle="modal" title='Edit Activity'>
                                                <i class="ti-pencil"></i>
                                            </button>   
                                            <button type="button" class="btn btn-sm btn-danger btn-outline" onclick="confirmDelete({{ $activity->id }}, 'activity')" title='Delete Activity'>
                                                <i class="ti-trash"></i>
                                            </button> 
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
                </div>
                <div class="tab-pane fade" id="files" role="tabpanel" aria-labelledby="files-tab">
                    <div class="d-flex">
                        <button type="button" class="btn btn-sm btn-primary ml-auto m-3" title="Upload File"  data-toggle="modal" data-target="#uploadFile">
                            <i class="ti-plus"></i>
                        </button>
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
                                @foreach ($rpeFileUploads as $fileupload)
                                    <tr>
                                        <td align="center">
                                            <button type="button"  class="btn btn-sm btn-warning btn-outline"
                                                data-target="#editRpeFile{{ $fileupload->Id }}" data-toggle="modal" title='Edit fileupload'>
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
                url = '{{ url('requestEvaluation/view/supp-delete') }}/' + id;
            } else if (type === 'personnel') {
                url = '{{ url('requestEvaluation/view/personnel-delete') }}/' + id;
            } else if (type === 'fileupload') {
                url = '{{ url('requestEvaluation/view/file-delete') }}/' + id;
            } else if (type === 'activity') {
                url = '{{ url('requestEvaluation/view/activity-delete') }}/' + id;
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
@include('product_evaluations.create_supplementary')
@include('product_evaluations.assign_personnel')
@include('product_evaluations.create_activity')
@include('product_evaluations.upload_rpe_file')

@foreach ($RpeSupplementary as $supplementary)
@include('product_evaluations.edit_supplementary')
@endforeach
@foreach ($assignedPersonnel as $Personnel)
@include('product_evaluations.edit_personnel')
@endforeach
@foreach ($activities as $activity)
@include('product_evaluations.edit_activity')
@endforeach
@foreach ($rpeFileUploads as $fileupload)
@include('product_evaluations.edit_files')
@endforeach
@include('product_evaluations.cancel')
@include('product_evaluations.close')
{{-- @include('sample_requests.upload_srf_file') --}}
{{-- @include('sample_requests.create_raw_materials') --}}
{{-- @foreach ($requestEvaluation as $srf)
    @include('sample_requests.srf_approval')
    @include('sample_requests.srf_receive')
    @include('sample_requests.srf_start')
    @include('sample_requests.srf_pause')
@endforeach --}}
@endsection
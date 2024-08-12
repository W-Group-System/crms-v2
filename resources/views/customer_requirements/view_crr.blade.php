@extends('layouts.header')
@section('content')

<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">View Client Details
                <div align="right">
                    <a href="{{ url('/customer_requirement') }}" class="btn btn-md btn-secondary">
                        <i class="icon-arrow-left"></i>&nbsp;Back
                    </a>
                    <button type="button" class="btn btn-danger btn-icon-text" >
                        <i class="ti ti-printer btn-icon-prepend"></i>
                        Print
                    </button>
                    <button type="button" class="btn btn-warning" title="Update" data-toggle="modal" data-target="#updateCrr-{{$crr->id}}">
                        <i class="ti ti-pencil"></i>&nbsp;Update
                    </button>
                </div>
            </h4>
            <div class="col-md-12">
                <label><strong>Customer Details</strong></label>
                <hr style="margin-top: 0px; color: black; border-top-color: black;">

                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>Client :</b></label>
                    <div class="col-sm-3">
                        <label>
                            <a href="{{url('view_client/'.$crr->ClientId)}}" target="_blank">{{$crr->client->Name}}</a>
                        </label>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>Client Trade Name :</b></label>
                    <div class="col-sm-3">
                        <label>
                            @if($crr->client)
                            {{$crr->client->TradeName}}
                            @endif
                        </label>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>Region :</b></label>
                    <div class="col-sm-3">
                        <label>{{$crr->client->clientregion->Name}}</label>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>Country :</b></label>
                    <div class="col-sm-3">
                        <label>{{$crr->client->clientcountry->Name}}</label>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <label><strong>Request Details</strong></label>
                <hr style="margin-top: 0px; color: black; border-top-color: black;">

                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>CRR # :</b></label>
                    <div class="col-sm-3">
                        <label>{{$crr->CrrNumber}}</label>
                    </div>
                    <label class="col-sm-3 col-form-label"><b>Primary Sales Person :</b></label>
                    <div class="col-sm-3">
                        <label>{{$crr->primarySales->full_name}}</label>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>Date Created :</b></label>
                    <div class="col-sm-3">
                        <label>{{date('M d Y', strtotime($crr->DateCreated))}}</label>
                    </div>
                    <label class="col-sm-3 col-form-label"><b>Secondary Sales Person :</b></label>
                    <div class="col-sm-3">
                        <label>{{$crr->secondarySales->full_name}}</label>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>Priority :</b></label>
                    <div class="col-sm-3">
                        <label>
                            @if($crr->Priority == 1)
                            Low
                            @elseif($crr->Priority == 3)
                            Medium
                            @elseif($crr->Priority == 5)
                            High
                            @endif
                        </label>
                    </div>
                    <label class="col-sm-3 col-form-label"><b>Status :</b></label>
                    <div class="col-sm-3">
                        <label>
                            @if($crr->Status == 10)
                            Open
                            @elseif($crr->Status == 30)
                            Closed
                            @endif
                        </label>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>Due Date :</b></label>
                    <div class="col-sm-3">
                        <label>{{date('M d Y', strtotime($crr->DueDate))}}</label>
                    </div>
                    <label class="col-sm-3 col-form-label"><b>Progress :</b></label>
                    <div class="col-sm-3">
                        <label>{{$crr->progressStatus->name}}</label>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>Application : </b></label>
                    <div class="col-sm-3">
                        <label>{{$crr->product_application->Name}}</label>
                    </div>
                    <label class="col-sm-3 col-form-label"><b>Nature of Request :</b></label>
                    <div class="col-sm-3">
                        @if($crr->crrNature)
                        @foreach ($crr->crrNature as $natureOfRequests)
                            <label>{{$natureOfRequests->natureOfRequest->Name}}</label> <br>
                        @endforeach
                        @endif
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>Competitor :</b></label>
                    <div class="col-sm-3">
                        <label>{{$crr->Competitor}}</label>
                    </div>
                    <label class="col-sm-3 col-form-label"><b>REF CRR Number :</b></label>
                    <div class="col-sm-3">
                        <label>{{$crr->RefCrrNumber}}</label>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>Competitor Price :</b></label>
                    <div class="col-sm-3">
                        <label>{{$crr->CompetitorPrice}}</label>
                    </div>
                    <label class="col-sm-3 col-form-label"><b>REF RPE Number :</b></label>
                    <div class="col-sm-3">
                        <label>{{$crr->RefRpeNumber}}</label>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>Potential Volume :</b></label>
                    <div class="col-sm-3">
                        <label>{{$crr->PotentialVolume}}</label>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>Target Price :</b></label>
                    <div class="col-sm-3">
                        <label>{{$crr->TargetPrice}}</label>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>Details of Requirement :</b></label>
                    <div class="col-sm-3">
                        <label>{{$crr->DetailsOfRequirement}}</label>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <label><strong>Approver Remarks</strong></label>
                <hr style="margin-top: 0px; color: black; border-top-color: black;">
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label">
                        @foreach ($crr->crrTransactionApprovals as $transactionApproval)
                            <b>
                                @if($transactionApproval->userByUserId)
                                    {{$transactionApproval->userByUserId->full_name}} :<br>
                                @elseif($transactionApproval->userById)
                                    {{$transactionApproval->userById->full_name}} :<br>
                                @endif
                            </b> 
                        @endforeach
                    </label>
                </div>
            </div>
            <div class="col-md-12">
                <label><strong>Recommendation</strong></label>
                <hr style="margin-top: 0px; color: black; border-top-color: black;">

                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>DDW Number : </b></label>
                    <div class="col-sm-3">
                        <label>@if($crr->DdwNumber != null){{$crr->DdwNumber}}@else N/A @endif</label>
                    </div>
                    <label class="col-sm-3 col-form-label"><b>Date Received :</b></label>
                    <div class="col-sm-3">
                        <label>{{date('M d Y', strtotime($crr->DateReceived))}}</label>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>Recommendation : </b></label>
                    <div class="col-sm-3">
                        <label>{{$crr->Recommendation}}</label>
                    </div>
                    <label class="col-sm-3 col-form-label"><b>Date Completed :</b></label>
                    <div class="col-sm-3">
                        <label>{{date('M d Y', strtotime($crr->DateCompleted))}}</label>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>Days Late : </b></label>
                    <div class="col-sm-3">
                        <label></label>
                    </div>
                </div>
            </div>
            <ul class="nav nav-tabs viewTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="supplementary_details-tab" data-toggle="tab" href="#supplementary_details" role="tab" aria-controls="supplementary_details" aria-selected="true">Supplementary Details</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="assigned-tab" data-toggle="tab" href="#assigned" role="tab" aria-controls="assigned" aria-selected="false">Assigned R&D Personnel</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " id="activities-tab" data-toggle="tab" href="#activities" role="tab" aria-controls="activities" aria-selected="false">Activities</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="files-tab" data-toggle="tab" href="#files" role="tab" aria-controls="files" aria-selected="false">Files</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " id="approvals-tab" data-toggle="tab" href="#approvals" role="tab" aria-controls="approvals" aria-selected="false">Approvals</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " id="history-tab" data-toggle="tab" href="#history" role="tab" aria-controls="history" aria-selected="false">History Logs</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="supplementary_details" role="tabpanel" aria-labelledby="supplementary_details">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered tables" width="100%">
                            <thead>
                                <tr>
                                    <td>Date</td>
                                    <td>Name</td>
                                    <td>Details</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($crr->crrDetails as $details)
                                    <tr>
                                        <td>{{date('Y-m-d h:i:s', strtotime($details->DateCreated))}}</td>
                                        <td>
                                            @if($details->userByUserId)
                                                {{$details->userByUserId->full_name}}
                                            @endif
    
                                            @if($details->userById)
                                                {{$details->userById->full_name}}
                                            @endif
                                        </td>
                                        <td>
                                            {{$details->DetailsOfRequirement}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="assigned" role="tabpanel" aria-labelledby="assigned">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered tables" width="100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                </tr>
                            </thead>
                            @foreach ($crr->crrPersonnel as $personnel)
                                <tbody>
                                    <tr>
                                        <td>
                                            @if($personnel->crrPersonnelByUserId)
                                                {{$personnel->crrPersonnelByUserId->full_name}}
                                            @elseif($personnel->crrPersonnelById)
                                                {{$personnel->crrPersonnelById->full_name}}
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            @endforeach
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="activities" role="tabpanel" aria-labelledby="activities">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered table-striped tables" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Schedule (Y-M-D)</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade " id="files" role="tabpanel" aria-labelledby="files-tab">
                    <button type="button" class="btn btn-primary mb-3 float-right" data-toggle="modal" data-target="#addCrrFiles">
                        Add Customer Requirement Files
                    </button>
                    @include('customer_requirements.new_crr_files')

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover tables" width="100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>File</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            @foreach ($crr->crrFiles as $files)
                                <tbody>
                                    <tr>
                                        <td>
                                            @if($files->IsForReview)
                                                <i class="ti-pencil-alt text-danger"></i>
                                            @endif
                                            @if($files->IsConfidential)
                                                <i class="mdi mdi-eye-off-outline text-danger"></i>
                                            @endif

                                            {{$files->Name}}
                                        </td>
                                        <td>
                                            <a href="{{url($files->Path)}}" target="_blank" class="btn btn-sm btn-info" title="View a file">
                                                <i class="ti-eye"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editCrrFiles-{{$files->Id}}" title="Edit">
                                                <i class="ti-pencil"></i>
                                            </button>

                                            <form method="POST" class="d-inline-block" action="{{url('delete_crr_file/'.$files->Id)}}">
                                                @csrf 

                                                <button type="button" class="btn btn-sm btn-danger deleteBtn" title="Delete">
                                                    <i class="ti-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                </tbody>

                                @include('customer_requirements.edit_crr_files')
                            @endforeach
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="approvals" role="tabpanel" aria-labelledby="approvals-tab">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover tables" width="100%">
                            <thead>
                                <tr>
                                    <th>Index</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($crr->crrTransactionApprovals as $transactionApprovals)
                                    <tr>
                                        <td>{{$transactionApprovals->Index}}</td>
                                        <td>
                                            @if($transactionApprovals->userByUserId)
                                                {{$transactionApprovals->userByUserId->full_name}}
                                            @elseif($transactionApprovals->userById)
                                                {{$transactionApprovals->userById->full_name}}
                                            @endif
                                        </td>
                                        <td>
                                            @if($transactionApprovals->Status == 10)
                                                Declined
                                            @elseif($transactionApprovals->Status == 20)
                                                Approved
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
                        <table class="table table-hover table-bordered table-striped tables" width="100%">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Name</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($crr->historyLogs as $logs)
                                    <tr>
                                        <td>{{date('Y-m-d h:i:s', strtotime($logs->ActionDate))}}</td>
                                        <td>{{$logs->historyUser->full_name}}</td>
                                        <td>{{$logs->Details}}</td>
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
@include('customer_requirements.update')

<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap4.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.bootstrap4.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
<script>
    new DataTable('.tables', {
        destroy: true,
        processing: true,
        pageLength: 10,
        ordering: false
    });

    $('[name="crr_file"]').on('change', function(e) {
        var filename = e.target.files[0].name;

        $(".crrFileName").val(filename);
    })

    $('.deleteBtn').on('click', function() {
        var form = $(this).closest('form');

        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            form.submit()
        });
    })
</script>
@endsection

@extends('layouts.header')
@section('content')
<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">View Shipment Sample Evaluation
                <div align="right">
                    @if(url()->previous() == url()->current())
                    <a href="{{ url('shipment_sample') }}" class="btn btn-md btn-outline-secondary">
                        <i class="icon-arrow-left"></i>&nbsp;Back
                    </a> 
                    @else
                    <a href="{{ url()->previous() ?: url('/shipment_sample') }}" class="btn btn-md btn-outline-secondary">
                        <i class="icon-arrow-left"></i>&nbsp;Back
                    </a> 
                    @endif
                    @if($data->Progress == 10 && auth()->user()->role->type == 'PRD' && (auth()->user()->role->name == 'Staff L2' || auth()->user()->role->name == 'Department Admin'))
                        <form action="{{ url('sse_approved/' . $data->id) }}" class="d-inline-block" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-success approvedSseBtn">
                                <i class="ti-check">&nbsp;</i>Approved
                            </button>
                        </form>
                    @endif
                    @if(auth()->user()->role->type == 'PRD' || (auth()->user()->role->name == 'Staff L2' || auth()->user()->role->name == 'Department Admin') && auth()->id() == $data->PreparedBy)
                    <button type="button" class="btn btn-outline-warning" id="editSse" data-toggle="modal" data-target="#editSse{{$data->id}}">
                        <i class="ti ti-pencil"></i>&nbsp;Update
                    </button>
                    @endif
                    @if($data->AttentionTo == auth()->user()->role->type)
                        @if($data->Progress == 20 && rndManager(auth()->user()->role))
                            <form action="{{ url('sse_received/' . $data->id) }}" method="POST" class="d-inline-block">
                                @csrf
                                <button type="button" class="btn btn-outline-success receivedBtn">
                                    <i class="ti-bookmark">&nbsp;</i> Received
                                </button>
                            </form>    
                        @endif
                        @if($data->Progress == 35 || $data->Progress == 45)
                        <form method="POST" action="{{url('start_sse/'.$data->id)}}" class="d-inline-block" onsubmit="show()">
                            @csrf 
                            <button type="button" class="btn btn-outline-success startSseBtn">
                                <i class="ti-control-play"></i>&nbsp; Start
                            </button>
                        </form>
                        @endif
                        @if($data->Progress == 50)
                            <button type="button" class="btn btn-outline-warning" data-toggle="modal" data-target="#sample">Sample</button>
                            <form method="POST" action="{{url('done_sse/'.$data->id)}}" class="d-inline-block">
                                @csrf 
                                <button type="button" class="btn btn-outline-success doneSseBtn">
                                    <i class="ti ti-check"></i>&nbsp; Done
                                </button>
                            </form>
                        @endif  
                        @if($data->Progress == 55 && rndManager(auth()->user()->role))
                            <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#rejectedSse"><i class="ti ti-na"></i>&nbsp;Rejected</button>    
                            <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#acceptedSse"><i class="ti ti-check"></i>&nbsp;Accepted</button>  
                        @endif
                    @endif
                </div>
            </h4>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row mb-0" style="margin-top: 2em">
                        <label class="col-sm-3 col-form-label text-right"><b>SSE #:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->SseNumber }}</label>
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Date Submitted (MM/DD/YYYY):</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->DateSubmitted }}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <label class="col-sm-3 col-form-label text-right"><b>Attention To:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->AttentionTo }}</label>
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Raw Material Type:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->RmType }}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <label class="col-sm-3 col-form-label text-right"><b>Grade:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->Grade }}</label>
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Product Code:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->ProductCode }}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <label class="col-sm-3 col-form-label text-right"><b>Origin:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->Origin }}</label>
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Supplier:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->Supplier }}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label class="col-sm-3 col-form-label text-right"><b>Sse Result:</b></label>
                        <div class="col-sm-3">
                            <label>
                                @if($data->SseResult == '1')
                                    Old alternative product/ supplier
                                @elseif($data->SseResult == '2')
                                    New Product WITHOUT SPE Result
                                @elseif($data->SseResult == '3')
                                    First shipment with SPE result. {{$data->ResultSpeNo}}
                                @endif
                            </label>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <label class="col-sm-3 col-form-label text-right"><b>Prepared By:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->prepared_by->full_name ?? 'N/A' }}</label>
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Status:</b></label>
                        <div class="col-sm-3">
                            @if($data->Status == 10)
                                <label>Open</label>
                            @else
                                <label>Closed</label>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label class="col-sm-3 col-form-label text-right"><b>Approved By:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->approved_by->full_name ?? 'N/A' }}</label>
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Progress:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->progress->name ?? 'N/A' }}</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mb-3">
                    <label><strong>Purchase Details</strong></label>
                    <hr class="alert-dark mt-0">
                    <div class="form-group row mb-0" style="margin-top: 20px">
                        <label class="col-sm-3 col-form-label text-right"><b>PO #:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->PoNumber }}</label>
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Quantity:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->Quantity }}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label class="col-sm-3 col-form-label text-right"><b>Ordered:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->Ordered }}</label>
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Product ordered is:</b></label>
                        <div class="col-sm-3">
                            <label>
                                @if($data->ProductOrdered == '1')
                                    For Shipment by supplier
                                @elseif($data->ProductOrdered == '2')
                                    In transit to Manila or Plant
                                @elseif($data->ProductOrdered == '3')
                                    Delivered to plant & on stock
                                @elseif($data->ProductOrdered == '4')
                                    Shipped out to buyer
                                @elseif($data->ProductOrdered == '5')
                                    Others: {{$data->OtherProduct}}
                                @endif
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mb-3">
                    <label><strong>For DIRECT Shipment only</strong></label>
                    <hr class="alert-dark mt-0">
                    <div class="form-group row mb-0" style="margin-top: 20px">
                        <label class="col-sm-3 col-form-label text-right"><b>Buyer:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->Buyer }}</label>
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Buyer's PO #:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->BuyersPo }}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <label class="col-sm-3 col-form-label text-right"><b>Sales Agreement #:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->SalesAgreement }}</label>
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Product Declared as:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->ProductDeclared }}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <label class="col-sm-3 col-form-label text-right"><b>Instruction to Lab:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->Instruction }}</label>
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Lot Number on bags:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->LnBags }}</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <label><strong>Sample Details</strong></label>
                    <hr class="alert-dark mt-0">
                    <div class="form-group row mb-0">
                        <label class="col-sm-3 col-form-label text-right"><b>Type:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->SampleType ?? 'No Type Available' }}</label>
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Laboratory work required:</b></label>
                        <div class="col-sm-3">
                            @if($data->shipment_work && count($data->shipment_work) > 0)
                                @foreach($data->shipment_work as $work)
                                    <label>{{ $work->Work }}</label><br>
                                @endforeach
                            @else
                                <label>No Work Available</label>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <div class="col-sm-12">
                            <label class="col-form-label"><b>No. of Packs</b></label>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>Lot Number</th>
                                        <th>Qty Represented (g)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @if($data->shipment_pack && count($data->shipment_pack) > 0)
                                    @foreach($data->shipment_pack as $pack)
                                    <tr>
                                        <td>
                                        @if(rndManager(auth()->user()->role) || checkIfItsAnalyst(auth()->user()->role) && $data->Progress != 55 && $data->Progress != 30 && $data->Progress != 60)
                                            <form action="{{url('delete_sse_packs/'.$pack->id)}}" class="d-inline-block" method="post" onsubmit="show()">
                                                @csrf
                                                <button type="button" class="btn btn-sm btn-outline-danger deleteFile">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                        </td>
                                        <td>{{ $pack->LotNumber }}</td>
                                        <td>{{ $pack->QtyRepresented }}</td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3" align="center">No data available</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                        <!-- <label class="col-sm-3 col-form-label text-right"><b>Attachments:</b></label>
                        <div class="col-sm-3">
                            @if($data->shipment_attachments && count($data->shipment_attachments) > 0)
                                @foreach($data->shipment_attachments as $file)
                                    <label style="display: contents;">
                                        {{ $file->Name }} <br>
                                        <a href="{{ asset('storage/' . $file->Path) }}" target="_blank">{{ $file->Path }}</a>
                                    </label>
                                    <br>
                                @endforeach
                            @else
                                <label>No Attachments Available</label>
                            @endif
                        </div> 
                        <label class="col-sm-3 col-form-label text-right"><b>No. of Pack:</b></label>
                        <div class="col-sm-3">
                            @if($data->shipment_pack && count($data->shipment_pack) > 0)
                                @foreach($data->shipment_pack as $pack)
                                    <label style="display: contents;">
                                        {{ $pack->LotNumber }} <br>
                                        {{ $pack->QtyRepresented }}
                                    </label>
                                    <br>
                                @endforeach
                            @else
                                <label>No Pack Available</label>
                            @endif
                        </div> -->
                    </div>
                    <ul class="nav nav-tabs viewTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link p-2 active" id="assigned-tab" data-toggle="tab" href="#assigned_details" role="tab" aria-controls="assigned_details" aria-selected="true">R&D/QCD Personnel</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link p-2" id="attachment-tab" data-toggle="tab" href="#attachment1" role="tab" aria-controls="attachment1" aria-selected="false">Attachments</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link p-2" id="history-tab" data-toggle="tab" href="#history" role="tab" aria-controls="history" aria-selected="false">History Logs</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade active show" id="assigned_details" role="tabpanel" aria-labelledby="assigned_details">
                            @if(!checkIfItsSalesDept(auth()->user()->department_id))
                                @if(rndManager(auth()->user()->role))
                                    @if($data->Progress == 35 || $data->Progress == 45)
                                    <button type="button" class="btn btn-outline-primary btn-sm float-right mb-3" data-toggle="modal" data-target="#addSsePersonnel">
                                        New
                                    </button>
                                    @include('sse.new_personnel')
                                    @endif
                                @endif
                            @endif
                            <table class="table table-hover table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th width="10%">Action</th>
                                        <th width="90%">Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($data->ssePersonnel) > 0)
                                        @foreach ($data->ssePersonnel as $personnel)
                                            <tr>
                                                <td align="center">
                                                @if(rndManager(auth()->user()->role))
                                                    <button type="button" class="btn btn-outline-warning btn-sm" data-toggle="modal" data-target="#editSsePersonnel{{ $personnel->id }}">
                                                        <i class="ti-pencil"></i>
                                                    </button>
                                                @endif
                                                </td>
                                                <td>
                                                    @if($personnel->ssePersonnelById)
                                                        {{$personnel->ssePersonnelById->full_name}}
                                                    @endif
                                                </td>
                                            </tr>
                                            @include('sse.edit_personnel')
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="2" align="center">No data available</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="attachment1" role="tabpanel" aria-labelledby="attachment1">
                            <table class="table table-hover table-striped table-bordered tables">
                                <thead>
                                    <th width="10%">Action</th>
                                    <th width="30%">Attachment Name</th>
                                    <th width="60%">File</th>
                                </thead>
                                <tbody>
                                    @foreach($data->shipment_attachments as $file)
                                    <tr>
                                        <td align="center">
                                        @if(rndManager(auth()->user()->role) || checkIfItsAnalyst(auth()->user()->role) && $data->Progress != 55 && $data->Progress != 30 && $data->Progress != 60)
                                            <form action="{{url('delete_sse_file/'.$file->id)}}" class="d-inline-block" method="post">
                                                @csrf
                                                <button type="button" class="btn btn-sm btn-outline-danger deleteFile">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                        </td>
                                        <td>{{ $file->Name }}</td>
                                        <td><a href="{{ asset('storage/' . $file->Path) }}" target="_blank">{{ $file->Path }}</a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped table-bordered tables" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="25%">Date</th>
                                            <th width="30%">Name</th>
                                            <th width="45%">Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data->historyLogs as $logs)
                                            <tr>
                                                <td>{{date('M d, Y - h:i A', strtotime($logs->ActionDate))}}</td>
                                                <td>
                                                    @if($logs->historyUser)
                                                    {{$logs->historyUser->full_name}}
                                                    @elseif($logs->user)
                                                    {{$logs->user->full_name}}
                                                    @endif
                                                </td>
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
            <div align="right" class="mt-3">
                <a href="{{ url('shipment_sample') }}" class="btn btn-outline-secondary">Close</a>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="sample" tabindex="-1" role="dialog" aria-labelledby="sampleModal" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sampleModal">Sample Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="updateSample" method="POST" action="{{ url('update_sample/' . $data->id) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-check form-check-inline text-center">
                                <input class="form-check-input" type="radio" name="SampleType" id="SampleType1" value="Pre-ship sample">
                                <label class="form-check-label" for="SampleType1">Pre-ship sample</label>

                                <input class="form-check-input" type="radio" name="SampleType" id="SampleType2" value="Co-ship sample">
                                <label class="form-check-label" for="SampleType2">Co-ship sample</label>

                                <input class="form-check-input" type="radio" name="SampleType" id="SampleType3" value="Complete samples">
                                <label class="form-check-label" for="SampleType3">Complete samples</label>

                                <input class="form-check-input" type="radio" name="SampleType" id="SampleType4" value="Partial samples. More samples to follow">
                                <label class="form-check-label" for="SampleType4">Partial samples. More samples to follow</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" id="lotNoContainer">
                                <label>No of pack:</label>
                                <div class="input-group">
                                    <input type="hidden" name="PackId[]" value="">
                                    <input type="text" class="form-control" name="LotNumber[]" placeholder="Enter Lot Number">
                                    <button class="btn btn-sm btn-primary addRowBtn1" style="border-radius: 0px;" type="button">+</button>
                                </div>
                                <input type="text" class="form-control" name="QtyRepresented[]" placeholder="Enter Qty Represented">
                            </div>
                            <div class="form-group">
                                <label>Laboratory work required:</label>
                                <select class="form-control js-example-basic-multiple" id="Work" name="Work[]" style="position: relative !important" multiple>
                                    <option value="Standard QUALITY CONTROL test: pH, Viscosity, WGS, KGS">Standard QUALITY CONTROL test: pH, Viscosity, WGS, KGS</option>
                                    <option value="Particle size distribution">Particle size distribution</option>
                                    <option value="Microbacteria test">Microbacteria test</option>
                                    <option value="Other tests">Other tests</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" id="attachmentsContainer">
                                <label>Attachments:</label>
                                <div class="input-group">         
                                    <select class="form-control js-example-basic-single" name="Name[]" id="Name" title="Select Attachment Name" >
                                        <option value="" disabled selected>Select Attachment Name</option>
                                        <option value="COA">COA</option>
                                        <option value="Specifications">Specifications</option>
                                        <option value="Others">Others</option>
                                    </select>
                                    <button class="btn btn-sm btn-primary addRowBtn2" style="border-radius: 0px;" type="button">+</button>
                                </div>
                                <input type="file" class="form-control" id="Path" name="Path[]">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer mt-3">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-outline-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="rejectedSse" tabindex="-1" role="dialog" aria-labelledby="rejectedSseModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectedSseModal">Rejected</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="updateRejectedSse" method="POST" action="{{ url('update_sse_rejected/' . $data->id) }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Enter Rejected Remarks</label>
                                <textarea name="RejectedRemarks" class="form-control" cols="50" rows="10" placeholder="Enter rejected remarks"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer mt-3">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-outline-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="acceptedSse" tabindex="-1" role="dialog" aria-labelledby="acceptedSseModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="acceptedSseModal">Accepted</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="acceptedSseRejected" method="POST" action="{{ url('accept_sse/' . $data->id) }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Enter Accepted Remarks</label>
                                <textarea name="AcceptedRemarks" class="form-control" cols="50" rows="10" placeholder="Enter accepted remarks"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer mt-3">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-outline-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@include('sse.edit')

<script>
    $(document).ready(function () {
        $('.tables').DataTable({
            destroy: false,
            processing: true,
            pageLength: 10,
            ordering: false
        });

        $(document).on('click', '.addRowBtn1', function() {
            var newRow = `
                <div class="form-group" style="margin-top: 10px">
                    <div class="input-group">
                        <input type="hidden" name="PackId[]" value=""> <!-- Empty for new rows -->
                        <input type="text" class="form-control" name="LotNumber[]" placeholder="Enter Lot Number">
                        <button class="btn btn-sm btn-danger removeRowBtn1" style="border-radius: 0px;" type="button">-</button>
                    </div>
                    <input type="text" class="form-control" name="QtyRepresented[]" placeholder="Enter Qty Represented">
                </div>`;
            $('#lotNoContainer').append(newRow);
        });

        $(document).on('click', '.removeRowBtn1', function() {
            $(this).closest('.form-group').remove();
        });

        $(document).on('click', '.addRowBtn2', function() {
            var newRow = $('<div class="form-group" style="margin-top: 10px">' +
                        '<div class="input-group">' +         
                            '<select class="form-control js-example-basic-single" name="Name[]" id="Name" title="Select Attachment Name">' +
                                '<option value="" disabled selected>Select Attachment Name</option>' +
                                '<option value="COA">COA</option>' +
                                '<option value="Specifications">Specifications</option>' +
                                '<option value="Others">Others</option>' +
                            '</select>' +
                           '<button class="btn btn-sm btn-danger removeRowBtn2" style="border-radius: 0px;" type="button">-</button>' +
                        '</div>' +
                        '<input type="file" class="form-control" id="Path" name="Path[]">' +
                    '</div>');

            // Append the new row to the container where addresses are listed
            $('#attachmentsContainer').append(newRow);

             // Reinitialize select2 for the new row
            $('.js-example-basic-single').select2();
        });
        
        $(document).on('click', '.removeRowBtn2', function() {
            $(this).closest('.form-group').remove();
        });

        $("#updateSample").on('hidden.bs.modal', function() {
            $("[name='AttentionTo']").val(null).trigger('change');
            $("[name='Supplier']").val(null).trigger('change');
            $("[name='Work']").val(null).trigger('change');
        })

        $('#updateSample').on('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            var form = $(this);
            var formData = new FormData(form[0]);

            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.success, 
                    }).then(() => {
                        $('#sample').modal('hide');
                        location.reload(); // Reload page (if needed)
                    });
                },
            });
        });

        $('.approvedSseBtn').on('click', function (e) {
            e.preventDefault(); // Prevent default form submission

            var button = $(this);
            var form = button.closest('form');
            var actionUrl = form.attr('action'); // Get form action URL

            $.ajax({
                url: actionUrl,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'  // Ensure CSRF token is passed
                },
                data: form.serialize(),  // Send form data
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: "Success",
                            text: response.message,
                            icon: "success",
                            timer: 1500,
                            showConfirmButton: false
                        }).then(function () {
                            window.location.reload();  // Reload the page after success
                        });
                    }
                }
            });
        });

        $('.receivedBtn').on('click', function() {
            var form = $(this).closest('form');

            Swal.fire({
                title: "Are you sure?",
                // text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Received",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit()
                }
            });
        })

        $('.startSseBtn').on('click', function() {
            var form = $(this).closest('form');
            var labelBtn = $(this).data('label');
            
            Swal.fire({
                title: "Are you sure?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                reverseButtons: true,
                confirmButtonText: labelBtn != null ? labelBtn : "Start"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit()
                }
            });
        })

        $('.doneSseBtn').on('click', function (e) {
            e.preventDefault(); // Prevent default form submission

            var button = $(this);
            var form = button.closest('form');
            var actionUrl = form.attr('action'); // Get form action URL

            $.ajax({
                url: actionUrl,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: form.serialize(),
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: "Completed",
                            text: response.message,
                            icon: "success",
                            timer: 1500,
                            showConfirmButton: false
                        }).then(function () {
                            window.location.reload(); // Reload the page
                        });
                    }
                }
            });
        });

        $('.deleteFile').on('click', function() {
            var form = $(this).closest('form');

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                reverseButtons: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit()
                }
            });
        })
    });
</script>
@endsection
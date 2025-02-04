@extends('layouts.header')
@section('content')
<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-4"> 
                    <h4 class="card-title d-flex justify-content-between align-items-center" style="margin-top: 10px">View Supplier Product Evaluation</h4>
                </div>
                <div class="col-lg-8" align="right">
                    @if(url()->previous() == url()->current())
                    <a href="{{ url('supplier_product') }}" class="btn btn-md btn-outline-secondary">
                        <i class="icon-arrow-left"></i>&nbsp;Back
                    </a> 
                    @else
                    <a href="{{ url()->previous() ?: url('/supplier_product') }}" class="btn btn-md btn-outline-secondary">
                        <i class="icon-arrow-left"></i>&nbsp;Back
                    </a> 
                    @endif
                    @if($data->Progress == 10 && auth()->user()->role->type == 'PRD' && (auth()->user()->role->name == 'Staff L2' || auth()->user()->role->name == 'Department Admin'))
                    <form action="{{ url('spe_approved/' . $data->id) }}" class="d-inline-block" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-success approvedBtn">
                            <i class="ti-check">&nbsp;</i>Approved
                        </button>
                    </form>
                    @endif
                    @if(auth()->user()->role->type == 'PRD' || (auth()->user()->role->name == 'Staff L2' || auth()->user()->role->name == 'Department Admin') && auth()->id() == $data->PreparedBy)
                    <button type="button" class="btn btn-outline-warning" id="editSpe" data-toggle="modal" data-target="#editSpe{{$data->id}}">
                        <i class="ti ti-pencil"></i>&nbsp;Update
                    </button>
                    @endif
                    @if($data->AttentionTo == auth()->user()->role->type)
                        @if($data->Progress == 20 && rndManager(auth()->user()->role))
                            <button type="button" class="btn btn-outline-info" data-target="#returnToPurch{{ $data->id }}" data-toggle="modal" title='Return to Analyst'>
                                <i class="ti ti-back-left">&nbsp;</i>Return to Purchasing
                            </button>
                            <form action="{{url('spe_received/'.$data->id)}}" method="post" class="d-inline-block" onsubmit="show()">
                                @csrf
                                <button type="button" class="btn btn-outline-success receivedBtn">
                                    <i class="ti-bookmark">&nbsp;</i> Received
                                </button>
                            </form>    
                        @endif
                        @if($data->Progress == 35 || $data->Progress == 45)
                        <form method="POST" action="{{url('start_spe/'.$data->id)}}" class="d-inline-block" onsubmit="show()">
                            @csrf 
                            <button type="button" class="btn btn-outline-success startSpeBtn">
                                <i class="ti-control-play"></i>&nbsp; Start
                            </button>
                        </form>
                        @endif
                        @if($data->Progress != 10 &&  $data->Progress != 20 && $data->Progress != 35)
                            <button type="button" class="btn btn-outline-warning" data-toggle="modal" data-target="#disposition">Disposition</button>
                        @endif
                        @if($data->Progress == 50)
                            <!-- <button type="button" class="btn btn-outline-warning" data-toggle="modal" data-target="#reconfirmatory">Re-confirmatory</button> -->
                            <!-- <button type="button" class="btn btn-outline-warning" data-toggle="modal" data-target="#disposition">Disposition</button> -->
                            <form method="POST" action="{{url('submit_spe/'.$data->id)}}" class="d-inline-block" onsubmit="show()">
                                @csrf 
                                <button type="button" class="btn btn-outline-success submitSpeBtn">
                                    <i class="ti ti-check"></i>&nbsp;Submit
                                </button>
                            </form>
                        @endif  
                    @endif   
                    @if(rndManager(auth()->user()->role) && $data->Progress == 55 || $data->Progress == 65)
                        <!-- <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#rejected"> <i class="ti ti-na"></i>&nbsp;Rejected</button>  -->
                        <button type="button" class="btn btn-outline-info" data-target="#returnToAnalyst{{ $data->id }}" data-toggle="modal" title='Return to Analyst'>
                            <i class="ti ti-back-left">&nbsp;</i>Return to Analyst
                        </button>
                        @if($data->Progress != 65)
                        <form method="POST" action="{{url('done_spe/'.$data->id)}}" class="d-inline-block" onsubmit="show()">
                            @csrf 
                            <button type="button" class="btn btn-outline-success doneSpeBtn">
                                <i class="ti ti-check"></i>&nbsp; Submit
                            </button>
                        </form>
                        @endif
                        {{-- <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#accepted"> <i class="ti ti-check"></i>&nbsp;Completed</button>   --}}
                        <form action="{{url('accept_spe/'.$data->id)}}" method="post" onsubmit="show()" class="d-inline-block" id="completeSpeForm" onsubmit="show()">
                            @csrf 

                            <button type="button" class="btn btn-outline-success" onclick="completeSpe()">Completed</button>
                        </form>
                    @endif
                    @if(in_array(auth()->user()->role->type, ['RND', 'QCD-WHI', 'QCD-MRDC', 'QCD-PBI', 'QCD-CCC']))
                        <a class="btn btn-outline-danger btn-icon-text" href="{{url('print_spe/'.$data->id)}}" target="_blank">
                            <i class="ti ti-printer btn-icon-prepend"></i>
                            Print
                        </a>
                    @endif
                    @if(auth()->user()->role->type == 'PRD')
                        @if($data->Progress == 60 && $data->Status != 30)
                            <form method="POST" action="{{url('close_spe/'.$data->id)}}" class="d-inline-block">
                            @csrf 
                                <button type="button" class="btn btn-outline-success closeSpeBtn">
                                    <i class="ti ti-close"></i>&nbsp;Close
                                </button>
                            </form>
                        @endif
                    @endif
                </div>
                <div class="col-md-12">
                    <div class="form-group row mb-0" style="margin-top: 2em">
                        <label class="col-sm-3 col-form-label text-right"><b>SPE #:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->SpeNumber }}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <label class="col-sm-3 col-form-label text-right"><b>Date Requested (MM/DD/YYYY):</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->DateRequested }}</label>
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Attention To:</b></label>
                        <div class="col-sm-3">
                            <label>
                                @if($data->AttentionTo == 'RND')
                                    RND
                                @elseif($data->AttentionTo == 'QCD-WHI')
                                    QCD-WHI 
                                @elseif($data->AttentionTo == 'QCD-PBI')
                                    QCD-PBI
                                @elseif($data->AttentionTo == 'QCD-MRDC')
                                    QCD-MRDC
                                @else 
                                    QCD-CCC
                                @endif
                            </label>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <label class="col-sm-3 col-form-label text-right"><b>Deadline (MM/DD/YYYY):</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->Deadline}}</label>
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Manufacturer of Sample:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->Manufacturer ?? 'N/A'}}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <label class="col-sm-3 col-form-label text-right"><b>Product Name:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->ProductName}}</label>
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Quantity:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->Quantity ?? 'N/A'}}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <label class="col-sm-3 col-form-label text-right"><b>Supplier/ Trader Name:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->suppliers->Name}}</label>
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Product Application:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->ProductApplication ?? 'N/A'}}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <label class="col-sm-3 col-form-label text-right"><b>Origin:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->Origin ?? 'N/A'}}</label>
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Lot No./ Batch No.:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->LotNo ?? 'N/A'}}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label text-right"><b>Price:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->Price ?? 'N/A'}}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <label class="col-sm-3 col-form-label text-right"><b>Instruction to Laboratory:</b></label>
                        <div class="col-sm-3">
                            @if($data->supplier_instruction && count($data->supplier_instruction) > 0)
                                @foreach($data->supplier_instruction as $instruction)
                                    <label>{{ $instruction->Instruction }}</label><br>
                                @endforeach
                            @else
                                <label>No Instructions Available</label>
                            @endif
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Status:</b></label>
                        <div class="col-sm-3">
                            <label>
                                @if($data->Status == 10)
                                    Open
                                @else
                                    Closed
                                @endif
                            </label>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <label class="col-sm-3 col-form-label text-right"><b>Prepared By:</b></label>
                        <div class="col-sm-3">
                            <label>{{ optional($data->prepared_by)->full_name }}</label>
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Progress:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->progress->name }}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label text-right"><b>Approved By:</b></label>
                        <div class="col-sm-3">
                            <label>{{ optional($data->approved_by)->full_name }}</label>
                        </div>
                    </div>
                    @if($data->ReturnRemarks != null)
                    <div class="form-group row mb-0">
                        <label class="col-sm-3 col-form-label text-right"><b>Analyst Return Remarks:</b></label>
                        <div class="col-sm-9">
                            <label>{{ $data->ReturnRemarks ?? 'N/A' }}</label>
                        </div>
                    </div>
                    @endif
                    @if($data->ReturnRemarksPurch != null)
                    <div class="form-group row mb-0">
                        <label class="col-sm-3 col-form-label text-right"><b>Purchasing Return Remarks:</b></label>
                        <div class="col-sm-9">
                            <label>{{ $data->ReturnRemarksPurch ?? 'N/A' }}</label>
                        </div>
                    </div>
                    @endif
                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label text-right"><b>Disposition:</b></label>
                        <div class="col-sm-9">
                        @if($data->supplier_disposition && count($data->supplier_disposition) > 0)
                                @foreach($data->supplier_disposition as $disposition)
                                    @if($disposition->Disposition == 1) 
                                        <i class="ti ti-check"></i><label>&nbsp;Almost an exact match with the current product. The Sample works with direct replacement in the application.</label><br>
                                    @elseif($disposition->Disposition == 2) 
                                        <i class="ti ti-check"></i><label>&nbsp;Has higher quality than the existing raw materials. Needs dilution or lower proportion in product applications.</label><br>
                                    @elseif($disposition->Disposition == 3) 
                                        <i class="ti ti-check"></i><label>&nbsp;Has lower quality than the existing product. Needs higher proportion in product applications.</label><br>
                                    @elseif($disposition->Disposition == 4) 
                                        <i class="ti ti-check"></i><label>&nbsp;Cannot be fully evaluated. The company does not have a testing capability.</label><br>
                                    @elseif($disposition->Disposition == 5) 
                                        <i class="ti ti-check"></i><label>&nbsp;Rejected. Does not pass the critical parameters of the test</label>
                                    @elseif($disposition->Disposition == 7)
                                        <i class="ti ti-check"></i><label>&nbsp;Accepted as New Raw Material</label>
                                    @else
                                        <i class="ti ti-check"></i><label>&nbsp;Accepted. As a new supplier</label>
                                    @endif
                                @endforeach
                            @else
                                <label>No Disposition Available</label>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label text-right"><b>Remarks:</b></label>
                        <div class="col-sm-9">
                            <label>{{ $data->LabRemarks ?? 'N/A' }}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <div class="col-md-12">
                            <table class="table table-hover table-striped table-bordered tables">
                                <thead>
                                    <th width="10%">Action</th>
                                    <th width="30%">Attachment Name</th>
                                    <th width="60%">File</th>
                                </thead>
                                <tbody>
                                    @foreach($data->attachments as $file)
                                    <tr>
                                        <td align="center">
                                        @if(auth()->user()->role->type == 'PRD')
                                            <form action="{{url('delete_spe_file/'.$file->id)}}" class="d-inline-block" method="post">
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
                    </div>
                    <!-- <div class="form-group row mb-0">
                        <label class="col-sm-3 col-form-label text-right"><b>Attachments:</b></label>
                        <div class="col-sm-3">
                            @if($data->attachments && count($data->attachments) > 0)
                                @foreach($data->attachments as $file)
                                    <label>
                                        {{ $file->Name }} <br>
                                        <a href="{{ asset('storage/' . $file->Path) }}" target="_blank">{{ $file->Path }}</a>
                                    </label>
                                    <br>
                                @endforeach
                            @else
                                <label>No Attachments Available</label>
                            @endif
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Disposition:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->Disposition ?? ''}}</label>
                        </div>
                    </div> -->
                    <ul class="nav nav-tabs viewTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link p-2 active" id="assigned-tab" data-toggle="tab" href="#assigned_details" role="tab" aria-controls="assigned_details" aria-selected="true">R&D/QCD Personnel</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link p-2" id="file-tab" data-toggle="tab" href="#files" role="tab" aria-controls="files" aria-selected="false">Files</a>
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
                                    <button type="button" class="btn btn-outline-primary btn-sm float-right mb-3" data-toggle="modal" data-target="#addPersonnel">
                                        New
                                    </button>
                                    @include('spe.new_personnel')
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
                                    @foreach ($data->spePersonnel as $personnel)
                                        <tr>
                                            <td align="center">
                                            @if(rndManager(auth()->user()->role) && ($data->Progress == 20 || $data->Progress == 25))
                                                <button type="button" class="btn btn-outline-warning btn-sm" data-toggle="modal" data-target="#editPersonnel{{ $personnel->id }}">
                                                    <i class="ti-pencil"></i>
                                                </button>
                                            @endif
                                            </td>
                                            <td>
                                                @if($personnel->crrPersonnelById)
                                                    {{$personnel->crrPersonnelById->full_name}}
                                                @endif
                                            </td>
                                        </tr>
                                        @include('spe.edit_personnel')
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="files" role="tabpanel" aria-labelledby="files">
                            @if(authCheckIfItsRnd(auth()->user()->department_id))
                                @if($data->Progress == 35 || $data->Progress == 45 || $data->Progress == 50 || $data->Progress == 55 || $data->Progress == 65)
                                <div align="right">
                                    <button type="button" class="btn btn-outline-primary btn-sm mb-3" data-toggle="modal" data-target="#addSpeFiles">
                                        New
                                    </button>
                                </div>
                                @include('spe.add_file')
                                @endif
                            @endif
                            <table class="table table-hover table-striped table-bordered tables">
                                <thead>
                                    <th width="10%">Action</th>
                                    <th width="45%">File Name</th>
                                    <th width="45%">File</th>
                                </thead>
                                <tbody>
                                    @foreach($data->supplier_files as $file)
                                    <tr>
                                        <td align="center">
                                        @if(rndManager(auth()->user()->role) || authCheckIfItsRnd(auth()->user()->department_id))
                                            @if($data->Progress != 55 || $data->Progress != 30 || $data->Progress != 60)
                                                <button type="button" class="btn btn-sm btn-outline-warning"
                                                    data-target="#editSpeFile{{ $file->id }}" data-toggle="modal" title='Edit File'>
                                                    <i class="ti-pencil"></i>
                                                </button>   
                                                <form action="{{url('delete_spe_attachment/'.$file->id)}}" class="d-inline-block" method="POST">
                                                    @csrf
                                                    <button type="button" class="btn btn-sm btn-outline-danger deleteSpeFile">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                        </td>
                                        <td>
                                            @if($file->IsForReview)
                                                <i class="ti-pencil-alt text-danger"></i>
                                            @endif
                                            @if($file->IsConfidential)
                                                <i class="mdi mdi-eye-off-outline text-danger"></i>
                                            @endif
                                            {{ $file->Name }}
                                        </td>
                                        <td>
                                            @if(rndManager(auth()->user()->role) || authCheckIfItsRnd(auth()->user()->department_id))
                                                <a href="{{ asset('storage/' . $file->Path) }}" target="_blank">View File</a>
                                            @elseif($file->IsForReview == 0 && $file->IsConfidential == 0)
                                                <a href="{{ asset('storage/' . $file->Path) }}" target="_blank">View File</a>
                                            @else
                                                <span>Access Restricted</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @include('spe.edit_file')
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
                <a href="{{ url('supplier_product') }}" class="btn btn-outline-secondary">Close</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="disposition" tabindex="-1" role="dialog" aria-labelledby="dispositionModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dispositionModal">Disposition</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="updateDisposition" method="POST" action="{{ url('update_disposition/' . $data->id) }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-3 form-group">
                            <label>Laboratory Disposition:</label>
                            <select class="form-control js-example-basic-multiple" name="Disposition[]" style="position: relative !important" multiple>
                                <option value="1" {{ in_array('1', $dispositions ?? []) ? 'selected' : '' }}> Almost an exact match with the current product. The sample works with direct replacement in the application</option>
                                <option value="2" {{ in_array('2', $dispositions ?? []) ? 'selected' : '' }}>Has higher quality than the existing raw materials. Needs dilution or lower proportion in product application</option>
                                <option value="3" {{ in_array('3', $dispositions ?? []) ? 'selected' : '' }}>Has lower quality than the existing product. Needs higher proportion in the product applications</option>
                                <option value="4" {{ in_array('4', $dispositions ?? []) ? 'selected' : '' }}>Cannot be fully evaluated. The company does not have the testing capability</option>
                                <option value="5" {{ in_array('5', $dispositions ?? []) ? 'selected' : '' }}>Rejected. Does not pass the critical parameters of the test</option>
                                <option value="6" {{ in_array('6', $dispositions ?? []) ? 'selected' : '' }}>Accepted. As a new supplier</option>
                                <option value="7" @if(in_array('7', $dispositions)) selected @endif>Accepted as New Raw Material</option>
                            </select>
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Remarks:</label>
                            <input type="text" class="form-control" name="LabRemarks" id="LabRemarks" placeholder="Enter Remarks">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-outline-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="reconfirmatory" tabindex="-1" role="dialog" aria-labelledby="reconfirmatoryModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reconfirmatoryModal">Re-confirmatory</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="updateReconfirmatory" method="POST" action="{{ url('update_reconfirmatory/' . $data->id) }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Enter Reconfirmatory</label>
                                <textarea name="Reconfirmatory" class="form-control" cols="50" rows="10" placeholder="Enter re-confirmatory"></textarea>
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

<div class="modal fade" id="rejected" tabindex="-1" role="dialog" aria-labelledby="rejectedModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectedModal">Rejected</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="updateRejected" method="POST" action="{{ url('update_rejected/' . $data->id) }}">
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

<div class="modal fade" id="accepted" tabindex="-1" role="dialog" aria-labelledby="acceptedModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="acceptedModal">Accepted</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="acceptedRejected" method="POST" action="{{ url('accept_spe/' . $data->id) }}">
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
@include('spe.return_purch')
@include('spe.return_spe')
@include('spe.edit')
<script>
    function completeSpe() 
    {
        document.getElementById('completeSpeForm').submit()
    }

    $(document).ready(function () {
        $('.tables').DataTable({
            destroy: false,
            processing: true,
            pageLength: 10,
            ordering: false
        });

        $('.approvedBtn').on('click', function (e) {
            e.preventDefault(); // Prevent default form submission

            var button = $(this);
            var form = button.closest('form');
            var actionUrl = form.attr('action'); // Get form action URL

            // Disable the button to prevent multiple clicks
            button.prop('disabled', true);

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
                            title: "Success",
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

        $('.doneSpeBtn').on('click', function (e) {
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
                            title: "Successfully Submitted",
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

        $('.submitSpeBtn').on('click', function (e) {
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

        $('.startSpeBtn').on('click', function() {
            var form = $(this).closest('form');
            var labelBtn = $(this).data('label');
            
            Swal.fire({
                title: "Are you sure?",
                // text: "You won't be able to revert this!",
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

        $('.deleteSpeFile').on('click', function() {
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

        $('.closeSpeBtn').on('click', function (e) {
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
                            title: "Closed",
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

    });
</script>
@endsection
@extends('layouts.header')
@section('content')
<link href="{{ asset('css/filepond.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css">

<div class="col-12 grid-margin stretch-card">
    <div class="card border border-1 border-primary rounded-0">
        <div class="card-header bg-primary">
            <p class="m-0 font-weight-bold text-white">View Customer Complaint</p>
        </div>
        <div class="card-body">
            {{-- <h4 class="card-title d-flex justify-content-between align-items-center">View Customer Complaint
            </h4> --}}
            <div align="right">
                <a href="{{ url()->previous() ?: url('customer_complaint2') }}" class="btn btn-md btn-outline-secondary">
                    <i class="icon-arrow-left"></i>&nbsp;Back
                </a>
                @if(auth()->user()->role->type != 'IAD')
                    @if($data->Status == 10 && $data->ReceivedBy == NULL)
                        <form action="{{ url('cc_received/' . $data->id) }}" class="d-inline-block" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-success receivedBtn">
                                <i class="ti-bookmark"></i>&nbsp;Receive
                            </button>
                        </form>
                    @endif
                    @if(primarySalesApprover($data->ReceivedBy, auth()->user()->id))
                        @if($data->Progress == 50 && $data->SiteConcerned != NULL)
                            <form action="{{ url('cc_noted/' . $data->id) }}" class="d-inline-block" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-success notedBtn">
                                    <i class="ti-check"></i>&nbsp;Noted By
                                </button>
                            </form>
                        @endif
                    @endif
                    {{-- @if ((auth()->user()->department_id == 5 || auth()->user()->department_id == 38) && $data->NotedBy != NULL) --}}
                    @if($data->ReceivedBy == auth()->user()->id)
                        @if($data->Progress == 20)
                            <button type="button" class="btn btn-outline-warning" data-id="{{ $data->id }}" data-toggle="modal" data-target="#complaint{{$data->id}}">
                            <i class="ti ti-pencil"></i>&nbsp;Complaint 
                            </button>
                        @endif
                        @if($data->Department == NULL && $data->Progress == 50)
                            <button type="button" class="btn btn-outline-primary" data-id="{{ $data->id }}" data-toggle="modal" data-target="#update{{$data->id}}">
                                <i class="ti ti-pencil"></i>&nbsp;Assign 
                            </button>
                        @endif
                        @if($data->Progress == 80 || $data->Progress = 60)
                            @if($data->Investigation != null && $data->CorrectiveAction != null && $data->ActionObjectiveEvidence != null && $data->IsVerified != 1)
                                <button type="button" class="btn btn-outline-warning" id="recommendationCc" data-id="{{ $data->id }}" data-toggle="modal" data-target="#verificationCc">
                                    <i class="ti ti-pencil"></i>&nbsp;Verification
                                </button>
                            @endif
                        @endif
                    @endif
                    @if(primarySalesApprover($data->NotedBy, auth()->user()->id) && $data->Progress == 60 && $data->IsVerified == 1)
                        <form action="{{ url('cc_closed/' . $data->id) }}" class="d-inline-block" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger closeBtn">
                                Close Complaint
                            </button>
                        </form>
                    @endif
                    @if(primarySalesApprover($data->NotedBy, auth()->user()->id) && $data->NotedBy != NULL)
                        @if($data->Progress == 30)
                            <form action="{{ url('cc_approved/' . $data->id) }}" class="d-inline-block" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-success approvedBtn">
                                    <i class="ti-check"></i>&nbsp;Acknowledge
                                </button>
                            </form>
                        @endif
                    @endif
                    <!-- @if($data->NcarIssuance == 1)
                        <button type="button" class="btn btn-outline-warning" id="updateCc" data-id="{{ $data->id }}" data-toggle="modal" data-target="#editCc">
                            <i class="ti ti-pencil"></i>&nbsp;Investigation
                        </button>
                    @endif -->
                    @if ($data->Department == auth()->user()->role->type && ($data->Progress == 40 || $data->Progress == 80))
                        @if($data->Investigation == null || $data->CorrectiveAction == null || $data->ActionObjectiveEvidence == null)
                            <button type="button" class="btn btn-outline-warning" id="updateCc" 
                                    data-id="{{ $data->id }}" data-toggle="modal" data-target="#editCc">
                                <i class="ti ti-pencil"></i>&nbsp;Investigation
                            </button>
                        @endif
                    @endif

                    <!-- @if($data->Department == auth()->user()->role->type && $data->Progress == 40 || $data->Progress == 80)
                        @if($data->Investigation != null || $data->CorrectiveAction != null || $data->ActionObjectiveEvidence != null)
                            <button type="button" class="btn btn-outline-warning" id="updateCc" 
                                    data-id="{{ $data->id }}" data-toggle="modal" data-target="#editCc">
                                <i class="ti ti-pencil"></i>&nbsp;Investigation
                            </button>
                        @endif
                    @endif -->
                    @if($data->Progress == 60 || $data->Progress == 70)
                        <a class="btn btn-outline-danger btn-icon-text" href="{{url('print_cc/'.$data->id)}}" target="_blank">
                            <i class="ti ti-printer btn-icon-prepend"></i>
                            Print
                        </a>
                    @endif
                @endif
                <!-- <button type="button" class="btn btn-outline-warning" id="recommendationCc" data-id="{{ $data->id }}" data-toggle="modal" data-target="#verificationCc">
                    <i class="ti ti-pencil"></i>&nbsp;Verification
                </button> -->
                <!-- @if((auth()->user()->department_id == 5 || auth()->user()->department_id == 38))
                    <button type="button" class="btn btn-outline-warning" data-id="{{ $data->id }}" data-toggle="modal" data-target="#complaint{{$data->id}}">
                        <i class="ti ti-pencil"></i>&nbsp;Complaint 
                    </button>
                    <button type="button" class="btn btn-outline-primary" data-id="{{ $data->id }}" data-toggle="modal" data-target="#update{{$data->id}}">
                        <i class="ti ti-pencil"></i>&nbsp;Assign 
                    </button>
                    <button type="button" class="btn btn-outline-warning" id="recommendationCc" data-id="{{ $data->id }}" data-toggle="modal" data-target="#verificationCc">
                        <i class="ti ti-pencil"></i>&nbsp;Verification
                    </button>
                    @if($data->ApprovedBy != NULL)
                    <a class="btn btn-outline-danger btn-icon-text" href="{{url('print_cc/'.$data->id)}}" target="_blank">
                        <i class="ti ti-printer btn-icon-prepend"></i>
                        Print
                    </a>
                    @endif
                    <form action="{{ url('cc_closed/' . $data->id) }}" class="d-inline-block" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary closeBtn">
                            <i class="ti-close">&nbsp;</i> Close
                        </button>
                    </form>
                @endif -->
                <!-- @if($data->NotedBy != NULL && auth()->user()->id == 15)
                    @if($data->Progress != 40)
                    <form action="{{ url('cc_approved/' . $data->id) }}" class="d-inline-block" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-success approvedBtn">
                            <i class="ti-check">&nbsp;</i> Approved
                        </button>
                    </form>
                    @endif
                @endif -->
            </div>
            <div class="row mb-0 mt-3">
                <div class="col-sm-3 col-md-2 text-right">
                    <p class="m-0"><b>CCF # :</b></p>
                </div>
                <div class="col-sm-3 col-md-4">
                    <p class="m-0">{{ $data->CcNumber }}</p>
                </div>
                <div class="col-sm-3 col-md-2 text-right"><p class="m-0"><b>Quality Class :</b></p></div>
                <div class="col-sm-3 col-md-4">
                    <p class="m-0">{{ $data->QualityClass }}</p>
                </div>
            </div>
            <div class="row mb-0">
                <div class="col-sm-3 col-md-2 text-right"><p class="m-0"><b>Recurring Issue :</b></p></div>
                <div class="col-sm-3 col-md-4">
                    <p class="m-0">
                        @if($data->RecurringIssue == 1)
                            Yes
                        @elseif($data->RecurringIssue == 2)
                            No 
                        @endif
                    </p>
                </div>
                <div class="col-sm-3 col-md-2 text-right"><p class="m-0"><b>Previous CCF # (If Yes) :</b></p></div>
                <div class="col-sm-3 col-md-4">
                    <p class="m-0">{{ $data->PreviousCCF }}</p>
                </div>
            </div>
            <div class="row mb-0">
                <div class="col-sm-3 col-md-2 text-right"><p class="m-0"><b>NCAR :</b></p></div>
                <div class="col-sm-3 col-md-4">
                    <p class="m-0">
                        @if($data->NcarIssuance == 1)
                            Yes
                        @elseif($data->NcarIssuance == 2)
                            No 
                        @endif
                    </p>
                </div>
                <div class="col-sm-3 col-md-2 text-right"><p class="m-0"><b>NCAR # (If Yes) :</b></p></div>
                <div class="col-sm-3 col-md-4">
                    <p class="m-0">{{ $data->IssuanceNo }}</p>
                </div>
            </div>
            <div class="form-group row mb-0">
                <div class="col-sm-3 col-md-2 text-right">
                    <p class="m-0"><b>Date Received :</b></p>
                </div>
                <div class="col-sm-3 col-md-4">
                    <p class="m-0">{{ $data->DateReceived }}</p>
                </div>
                <div class="col-sm-3 col-md-2 text-right">
                    <p class="m-0"><b>Received By :</b></p>
                </div>
                <div class="col-sm-3 col-md-4">
                    <p class="m-0">{{ optional($data->users)->full_name }}</p>
                </div>
            </div>
            <div class="row mb-0">
                <div class="col-sm-3 col-md-2 text-right">
                    <p class="m-0"><b>Date Complaint :</b></p>
                </div>
                <div class="col-sm-3 col-md-4">
                    <p class="m-0">{{ date('M. d, Y', strtotime($data->created_at)) }}</p>
                </div>
                <div class="col-sm-3 col-md-2 text-right">
                    <p class="m-0"><b>Department Concerned :</b></p>
                </div>
                <div class="col-sm-3 col-md-4">
                    <p class="m-0">{{ $data->Department }}</p>
                </div>
            </div>
            {{-- <div class="row mb-3">
                <div class="col-sm-3 col-md-2 text-right">
                    <p class="m-0"><b>Address :</b></p>
                </div>
                <div class="col-sm-3 col-md-4">
                    <p class="m-0">{{ $data->Address }}</p>
                </div>
            </div> --}}
            <div class="row mb-0">
                <div class="col-sm-3 col-md-2 text-right">
                    <p class="m-0"><b>Country :</b></p>
                </div>
                <div class="col-sm-3 col-md-4">
                    <p class="m-0">{{ $data->country->Name }}</p>
                </div>
                {{-- <div class="col-sm-3 col-md-2 text-right">
                    <p class="m-0"><b>Status :</b></p>
                </div> 
                <div class="col-sm-3 col-md-4">
                    <p class="m-0">{{ $data->Status == 10 ? 'Open' : 'Closed' }}</p>
                </div>--}}
            </div>
            <div class="row mb-0">
                <div class="col-sm-3 col-md-2 text-right">
                    <p class="m-0"><b>Company Name :</b></p>
                </div>
                <div class="col-sm-3 col-md-4">
                    <p class="m-0">{{ $data->CompanyName }}</p>
                </div>
                <div class="col-sm-3 col-md-2 text-right">
                    <p class="m-0"><b>Date Closed :</b></p>
                </div>
                <div class="col-sm-3 col-md-4">
                    <p class="m-0">{{ $data->ClosedDate }}</p>
                </div>
            </div>
            <div class="row mb-0">
                <div class="col-sm-3 col-md-2 text-right">
                    <p class="m-0"><b>Contact Name :</b></p>
                </div>
                <div class="col-sm-3 col-md-4">
                    <p class="m-0">{{ $data->ContactName }}</p>
                </div>
                {{-- <div class="col-sm-3 col-md-2 text-right">
                    <p class="m-0"><b>Mode of Communication :</b></p>
                </div>
                <div class="col-sm-3 col-md-4">
                    @if($data->Moc == '1')
                        By Phone
                    @elseif($data->Moc == '2')
                        By Letter/ Fax
                    @elseif($data->Moc == '3')
                        Personal
                    @elseif($data->Moc == '4')
                        By Email
                    @endif
                </div> --}}
            </div>
            <div class="row mb-0">
                <div class="col-sm-3 col-md-2 text-right">
                    <p class="m-0"><b>Email : </b></p>
                </div>
                <div class="col-sm-3 col-md-4">
                    <p class="m-0">{{ $data->Email }}</p>
                </div>
                <div class="col-sm-3 col-md-2 text-right">
                    <p class="m-0"><b>Noted By :</b></p>
                </div>
                <div class="col-sm-3 col-md-4">
                    <p class="m-0">{{ optional($data->noted_by)->full_name }}</p>
                </div>
            </div>
            <div class="row mb-0 mb-3">
                <div class="col-sm-3 col-md-2 text-right">
                    <p class="m-0"><b>Telephone :</b></p>
                </div>
                <div class="col-sm-3 col-md-4">
                    <p class="m-0">{{ $data->Telephone }}</p>
                </div>
            </div>
            <div class="form-group row mb-0">
                <div class="col-sm-3 col-md-2 text-right">
                    <p class="m-0"><b>Site Concerned :</b></p>
                </div>
                <div class="col-sm-3 col-md-4">
                    <p class="m-0">
                        @if($data->SiteConcerned == 1) 
                            WHI Head Office
                        @elseif ($data->SiteConcerned == 2)
                            WHI Carmona
                        @elseif ($data->SiteConcerned == 3)
                            MRDC
                        @elseif ($data->SiteConcerned == 4)
                            CCC Carmen
                        @elseif ($data->SiteConcerned == 5)
                            PBI Canlubang
                        @else 
                            International Warehouse
                        @endif
                    </p>
                </div>
                <div class="col-sm-3 col-md-2 text-right">
                    <p class="m-0"><b>Acknowledged By:</b></p>
                </div>
                <div class="col-sm-3 col-md-4">
                    <p class="m-0">{{ optional($data->approved_by)->full_name }}</p>
                </div>
            </div>
            <div class="form-group row mb-3">
                <div class="col-sm-3 col-md-2 text-right">
                    <p class="m-0"><b>Customer Remarks:</b></p>
                </div>
                <div class="col-sm-3 col-md-6">
                    <p class="m-0">{{ $data->CustomerRemarks }}</p>
                </div>
            </div> 
            <div class="form-group row mb-3">
                <div class="col-sm-3 col-md-2 text-right">
                    <p class="m-0"><b>Attachments :</b></p>
                </div>
                <div class="col-sm-3 col-md-6">
                    <p class="m-0">
                        @foreach ($data->files as $key => $file)
                            @php
                                $filePath = asset('storage/' . $file->Path); 
                            @endphp
                                {{$key + 1}}. <a href="{{ $filePath }}" target="_blank">{{ $file->Path }}</a>
                                <br>
                        @endforeach
                    </p>
                </div>
            </div> 
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-3">
                            <thead>
                                <tr>
                                    <th width="50%" colspan="1" class="text-center">COMPLAINT CATEGORY</th>
                                    <th width="50%" colspan="5" class="text-center">PRODUCT DETAILS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td width="20%"><b>1. Product Quality</b></td>
                                    <!-- <td>Please<br>Check</td> -->
                                    <td width="20%">Product Name</td>
                                    <td width="15%">S/C No.</td>
                                    <td width="15%">SO No.</td>
                                    <td width="15%">Quantity</td>
                                    <td width="15%">Lot No.</td>
                                </tr>
                                <tr>
                                    <td class="break-spaces" width="20%">1.1 Physical Hazard (contamination of product by unspecified compound e.g. hard plastics, metal flakes, rust, etc.)</td>
                                    <!-- <td align="center"><input id="check-p1" type="checkbox"></td> -->
                                    <td width="20%"><input type="text" class="form-control p1-input" name="Pn1" value="{{ optional($data->product_quality)->Pn1 }}" title="{{ optional($data->product_quality)->Pn1 }}" readonly></td>
                                    <td width="15%"><input type="text" class="form-control p1-input" name="ScNo1" value="{{ optional($data->product_quality)->ScNo1 }}" title="{{ optional($data->product_quality)->ScNo1 }}" readonly></td>
                                    <td width="15%"><input type="text" class="form-control p1-input" name="SoNo1" value="{{ optional($data->product_quality)->SoNo1 }}" title="{{ optional($data->product_quality)->SoNo1 }}" readonly></td>
                                    <td width="15%"><input type="text" class="form-control p1-input" style="width: 80px" name="Quantity1" value="{{ optional($data->product_quality)->Quantity1 }}" title="{{ optional($data->product_quality)->Quantity1 }}" readonly></td>
                                    <td width="15%"><input type="text" class="form-control p1-input" style="width: 80px" name="LotNo1" value="{{ optional($data->product_quality)->LotNo1 }}" title="{{ optional($data->product_quality)->LotNo1 }}" readonly></td>
                                </tr>
                                <tr>
                                    <td class="break-spaces" width="20%">1.2 Biological Hazard (e.g. high bacteria count, etc.)</td>
                                    <!-- <td align="center"><input id="check-p2" type="checkbox"></td> -->
                                    <td width="20%"><input type="text" class="form-control p2-input" name="Pn2" value="{{ optional($data->product_quality)->Pn2 }}" title="{{ optional($data->product_quality)->Pn2 }}" readonly></td>
                                    <td width="15%"><input type="text" class="form-control p2-input" name="ScNo2" value="{{ optional($data->product_quality)->ScNo2 }}" title="{{ optional($data->product_quality)->ScNo2 }}" readonly></td>
                                    <td width="15%"><input type="text" class="form-control p2-input" name="SoNo2" value="{{ optional($data->product_quality)->SoNo2 }}" title="{{ optional($data->product_quality)->SoNo2 }}" readonly></td>
                                    <td width="15%"><input type="text" class="form-control p2-input" style="width: 80px" name="Quantity2" value="{{ optional($data->product_quality)->Quantity2 }}" title="{{ optional($data->product_quality)->Quantity2 }}" readonly></td>
                                    <td width="15%"><input type="text" class="form-control p2-input" style="width: 80px" name="LotNo2" value="{{ optional($data->product_quality)->LotNo2 }}" title="{{ optional($data->product_quality)->LotNo2 }}" readonly></td>
                                </tr>
                                <tr>
                                    <td class="break-spaces" width="20%">1.3 Chemical Hazard (e.g. high heavy metals content, etc.)</td>
                                    <!-- <td align="center"><input id="check-p3" type="checkbox"></td> -->
                                    <td width="20%"><input type="text" class="form-control p3-input" name="Pn3" value="{{ optional($data->product_quality)->Pn3 }}" title="{{ optional($data->product_quality)->Pn3 }}" readonly></td>
                                    <td width="15%"><input type="text" class="form-control p3-input" name="ScNo3" value="{{ optional($data->product_quality)->ScNo3 }}" title="{{ optional($data->product_quality)->ScNo3 }}" readonly></td>
                                    <td width="15%"><input type="text" class="form-control p3-input" name="SoNo3" value="{{ optional($data->product_quality)->SoNo3 }}" title="{{ optional($data->product_quality)->SoNo3 }}" readonly></td>
                                    <td width="15%"><input type="text" class="form-control p3-input" style="width: 80px" name="Quantity3" value="{{ optional($data->product_quality)->Quantity3 }}" title="{{ optional($data->product_quality)->Quantity3 }}" readonly></td>
                                    <td width="15%"><input type="text" class="form-control p3-input" style="width: 80px" name="LotNo3" value="{{ optional($data->product_quality)->LotNo3 }}" title="{{ optional($data->product_quality)->LotNo3 }}" readonly></td>
                                </tr>
                                <tr>
                                    <td class="break-spaces" width="20%">1.4 Visual Defects (e.g. color change, particle size)</td>
                                    <!-- <td align="center"><input id="check-p4" type="checkbox"></td> -->
                                    <td width="20%"><input type="text" class="form-control p4-input" name="Pn4" value="{{ optional($data->product_quality)->Pn4 }}" title="{{ optional($data->product_quality)->Pn4 }}" readonly></td>
                                    <td width="15%"><input type="text" class="form-control p4-input" name="ScNo4" value="{{ optional($data->product_quality)->ScNo4 }}" title="{{ optional($data->product_quality)->ScNo4 }}" readonly></td>
                                    <td width="15%"><input type="text" class="form-control p4-input" name="SoNo4" value="{{ optional($data->product_quality)->SoNo4 }}" title="{{ optional($data->product_quality)->SoNo4 }}" readonly></td>
                                    <td width="15%"><input type="text" class="form-control p4-input" style="width: 80px" name="Quantity4" value="{{ optional($data->product_quality)->Quantity4 }}" title="{{ optional($data->product_quality)->Quantity4 }}" readonly></td>
                                    <td width="15%"><input type="text" class="form-control p4-input" style="width: 80px" name="LotNo4" value="{{ optional($data->product_quality)->LotNo4 }}" title="{{ optional($data->product_quality)->LotNo4 }}" readonly></td>
                                </tr>
                                <tr>
                                    <td class="break-spaces" width="20%">1.5 Application Problems (e.g. poor dispersion, poor distribution, poor binding property, high syneresis, etc.)</td>
                                    <!-- <td align="center"><input id="check-p5" type="checkbox"></td> -->
                                    <td width="20%"><input type="text" class="form-control p5-input" name="Pn5" value="{{ optional($data->product_quality)->Pn5 }}" title="{{ optional($data->product_quality)->Pn5 }}" readonly></td>
                                    <td width="15%"><input type="text" class="form-control p5-input" name="ScNo5" value="{{ optional($data->product_quality)->ScNo5 }}" title="{{ optional($data->product_quality)->ScNo5 }}" readonly></td>
                                    <td width="15%"><input type="text" class="form-control p5-input" name="SoNo5" value="{{ optional($data->product_quality)->SoNo5 }}" title="{{ optional($data->product_quality)->SoNo5 }}" readonly></td>
                                    <td width="15%"><input type="text" class="form-control p5-input" style="width: 80px" name="Quantity5" value="{{ optional($data->product_quality)->Quantity5 }}" title="{{ optional($data->product_quality)->Quantity5 }}" readonly></td>
                                    <td width="15%"><input type="text" class="form-control p5-input" style="width: 80px" name="LotNo5" value="{{ optional($data->product_quality)->LotNo5 }}" title="{{ optional($data->product_quality)->LotNo5 }}" readonly></td>
                                </tr>
                                <tr>
                                    <td class="break-spaces" width="20%">1.6 Physical/ Chemical Properties Out-of Specification (e.g. pH, gel strength, viscosity, syneresis and contamination with other ingredients)</td>
                                    <!-- <td align="center"><input id="check-p6" type="checkbox"></td> -->
                                    <td width="20%"><input type="text" class="form-control p6-input" name="Pn6" value="{{ optional($data->product_quality)->Pn6 }}" title="{{ optional($data->product_quality)->Pn6 }}" readonly></td>
                                    <td width="15%"><input type="text" class="form-control p6-input" name="ScNo6" value="{{ optional($data->product_quality)->ScNo6 }}" title="{{ optional($data->product_quality)->ScNo6 }}" readonly></td>
                                    <td width="15%"><input type="text" class="form-control p6-input" name="SoNo6" value="{{ optional($data->product_quality)->SoNo6 }}" title="{{ optional($data->product_quality)->SoNo6 }}" readonly></td>
                                    <td width="15%"><input type="text" class="form-control p6-input" style="width: 80px" name="Quantity6" value="{{ optional($data->product_quality)->Quantity6 }}" title="{{ optional($data->product_quality)->Quantity6 }}" readonly></td>
                                    <td width="15%"><input type="text" class="form-control p6-input" style="width: 80px" name="LotNo6" value="{{ optional($data->product_quality)->LotNo6 }}" title="{{ optional($data->product_quality)->LotNo6 }}" readonly></td>
                                </tr>
                                <tr>
                                    <td colspan="7"><b>2. Packaging</b></td>
                                </tr>
                                <tr>
                                    <td class="break-spaces" width="20%">2.1 Quantity (e.g. Short-packing, under-filled bags or box, over-filled container or box, etc.)</td>
                                    <!-- <td align="center"><input id="check-pack1" type="checkbox"></td> -->
                                    <td width="20%"><input type="text" class="form-control input-pack1" name="PackPn1" value="{{ optional($data->packaging)->PackPn1 }}" title="{{ optional($data->packaging)->PackPn1 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control input-pack1" name="PackScNo1" value="{{ optional($data->packaging)->PackScNo1 }}" title="{{ optional($data->packaging)->PackScNo1 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control input-pack1" name="PackSoNo1" value="{{ optional($data->packaging)->PackSoNo1 }}" title="{{ optional($data->packaging)->PackSoNo1 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control input-pack1" style="width: 80px" name="PackQuantity1" value="{{ optional($data->packaging)->PackQuantity1 }}" title="{{ optional($data->packaging)->PackQuantity1 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control input-pack1" style="width: 80px" name="PackLotNo1" value="{{ optional($data->packaging)->PackLotNo1 }}" title="{{ optional($data->packaging)->PackLotNo1 }}" disabled></td>
                                </tr>
                                <tr>
                                    <td class="break-spaces" width="20%">2.2 Packing (e.g. leakages, corrosion, etc.)</td>
                                    <!-- <td align="center"><input id="check-pack2" type="checkbox"></td> -->
                                    <td width="20%"><input type="text" class="form-control input-pack2" name="PackPn2" value="{{ optional($data->packaging)->PackPn2 }}" title="{{ optional($data->packaging)->PackPn2 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control input-pack2" name="PackScNo2" value="{{ optional($data->packaging)->PackScNo2 }}" title="{{ optional($data->packaging)->PackScNo2 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control input-pack2" name="PackSoNo2" value="{{ optional($data->packaging)->PackSoNo2 }}" title="{{ optional($data->packaging)->PackSoNo2 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control input-pack2" style="width: 80px" name="PackQuantity2" value="{{ optional($data->packaging)->PackQuantity2 }}" title="{{ optional($data->packaging)->PackQuantity2 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control input-pack2" style="width: 80px" name="PackLotNo2" value="{{ optional($data->packaging)->PackLotNo2 }}" title="{{ optional($data->packaging)->PackLotNo2 }}" disabled></td>
                                </tr>
                                <tr>
                                    <td class="break-spaces" width="20%">2.3 Labeling (e.g. wrong or defective label, unreadable, incorrect or incomplete text, etc.)</td>
                                    <!-- <td align="center"><input id="check-pack3" type="checkbox"></td> -->
                                    <td width="20%"><input type="text" class="form-control input-pack3" name="PackPn3" value="{{ optional($data->packaging)->PackPn3 }}" title="{{ optional($data->packaging)->PackPn3 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control input-pack3" name="PackScNo3" value="{{ optional($data->packaging)->PackScNo3 }}" title="{{ optional($data->packaging)->PackScNo3 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control input-pack3" name="PackSoNo3" value="{{ optional($data->packaging)->PackSoNo3 }}" title="{{ optional($data->packaging)->PackSoNo3 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control input-pack3" style="width: 80px" name="PackQuantity3" value="{{ optional($data->packaging)->PackQuantity3 }}" title="{{ optional($data->packaging)->PackQuantity3 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control input-pack3" style="width: 80px" name="PackLotNo3" value="{{ optional($data->packaging)->PackLotNo3 }}" title="{{ optional($data->packaging)->PackLotNo3 }}" disabled></td>
                                </tr>
                                <tr>
                                    <td class="break-spaces" width="20%">2.4 Packaging material (e.g. wrong packaging (bag, pallet, etc.) material, incorrect application of packaging instructions, inadequate quality of packaging material, etc.)</td>
                                    <!-- <td align="center"><input id="check-pack4" type="checkbox"></td> -->
                                    <td width="20%"><input type="text" class="form-control input-pack4" name="PackPn4" value="{{ optional($data->packaging)->PackPn4 }}" title="{{ optional($data->packaging)->PackPn4 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control input-pack4" name="PackScNo4" value="{{ optional($data->packaging)->PackScNo4 }}" title="{{ optional($data->packaging)->PackScNo4 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control input-pack4" name="PackSoNo4" value="{{ optional($data->packaging)->PackSoNo4 }}" title="{{ optional($data->packaging)->PackSoNo4 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control input-pack4" style="width: 80px" name="PackQuantity4" value="{{ optional($data->packaging)->PackQuantity4 }}" title="{{ optional($data->packaging)->PackQuantity4 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control input-pack4" style="width: 80px" name="PackLotNo4" value="{{ optional($data->packaging)->PackLotNo4 }}" title="{{ optional($data->packaging)->PackLotNo4 }}" disabled></td>
                                </tr>
                                <tr>
                                    <td colspan="7"><b>3. Delivery and Handling</b></td>
                                </tr>
                                <tr>
                                    <td class="break-spaces" width="20%">3.1 Product Handling (e.g. wrong product, pack size or quantity)</td>
                                    <!-- <td align="center"><input id="check-d1" type="checkbox"></td> -->
                                    <td width="20%"><input type="text" class="form-control d1-input" name="DhPn1" value="{{ optional($data->delivery_handling)->DhPn1 }}" title="{{ optional($data->delivery_handling)->DhPn1 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control d1-input" name="DhScNo1" value="{{ optional($data->delivery_handling)->DhScNo1 }}" title="{{ optional($data->delivery_handling)->DhScNo1 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control d1-input" name="DhSoNo1" value="{{ optional($data->delivery_handling)->DhSoNo1 }}" title="{{ optional($data->delivery_handling)->DhSoNo1 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control d1-input" style="width: 80px" name="DhQuantity1" value="{{ optional($data->delivery_handling)->DhQuantity1 }}" title="{{ optional($data->delivery_handling)->DhQuantity1 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control d1-input" style="width: 80px" name="DhLotNo1" value="{{ optional($data->delivery_handling)->DhLotNo1 }}" title="{{ optional($data->delivery_handling)->DhLotNo1 }}" disabled></td>
                                </tr>
                                <tr>
                                    <td class="break-spaces" width="20%">3.2 Delayed Delivery (e.g. inadequate forwarder service, wrong delivery address, etc.)</td>
                                    <!-- <td align="center"><input id="check-d2" type="checkbox"></td> -->
                                    <td width="20%"><input type="text" class="form-control d2-input" name="DhPn2" value="{{ optional($data->delivery_handling)->DhPn2 }}" title="{{ optional($data->delivery_handling)->DhPn2 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control d2-input" name="DhScNo2" value="{{ optional($data->delivery_handling)->DhScNo2 }}" title="{{ optional($data->delivery_handling)->DhScNo2 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control d2-input" name="DhSoNo2" value="{{ optional($data->delivery_handling)->DhSoNo2 }}" title="{{ optional($data->delivery_handling)->DhSoNo2 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control d2-input" style="width: 80px" name="DhQuantity2" value="{{ optional($data->delivery_handling)->DhQuantity2 }}" title="{{ optional($data->delivery_handling)->DhQuantity2 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control d2-input" style="width: 80px" name="DhLotNo2" value="{{ optional($data->delivery_handling)->DhLotNo2 }}" title="{{ optional($data->delivery_handling)->DhLotNo2 }}" disabled></td>
                                </tr>
                                <tr>
                                    <td class="break-spaces" width="20%">3.3 Product Damage during transit (e.g. leakages, corrosion, damaged label/box/carton/seal, etc.)</td>
                                    <!-- <td align="center"><input id="check-d3" type="checkbox"></td> -->
                                    <td width="20%"><input type="text" class="form-control d3-input" name="DhPn3" value="{{ optional($data->delivery_handling)->DhPn3 }}" title="{{ optional($data->delivery_handling)->DhPn3 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control d3-input" name="DhScNo3" value="{{ optional($data->delivery_handling)->DhScNo3 }}" title="{{ optional($data->delivery_handling)->DhScNo3 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control d3-input" name="DhSoNo3" value="{{ optional($data->delivery_handling)->DhSoNo3 }}" title="{{ optional($data->delivery_handling)->DhSoNo3 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control d3-input" style="width: 80px" name="DhQuantity3" value="{{ optional($data->delivery_handling)->DhQuantity3 }}" title="{{ optional($data->delivery_handling)->DhQuantity3 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control d3-input" style="width: 80px" name="DhLotNo3" value="{{ optional($data->delivery_handling)->DhLotNo3 }}" title="{{ optional($data->delivery_handling)->DhLotNo3 }}" disabled></td>
                                </tr>
                                <tr>
                                    <td colspan="7"><b>4. Others</b></td>
                                </tr>
                                <tr>
                                    <td class="break-spaces" width="20%">4.1 Quality of records or documents (e.g. insufficient, inadequate, missing, etc.)</td>
                                    <!-- <td align="center"><input id="check-o1" type="checkbox"></td> -->
                                    <td width="20%"><input type="text" class="form-control o1-input" name="OthersPn1" value="{{ optional($data->others)->OthersPn1 }}" title="{{ optional($data->others)->OthersPn1 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control o1-input" name="OthersScNo1" value="{{ optional($data->others)->OthersScNo1 }}" title="{{ optional($data->others)->OthersScNo1 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control o1-input" name="OthersSoNo1" value="{{ optional($data->others)->OthersSoNo1 }}" title="{{ optional($data->others)->OthersSoNo1 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control o1-input" style="width: 80px" name="OthersQuantity1" value="{{ optional($data->others)->OthersQuantity1 }}" title="{{ optional($data->others)->OthersQuantity1 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control o1-input" style="width: 80px" name="OthersLotNo1" value="{{ optional($data->others)->OthersLotNo1 }}" title="{{ optional($data->others)->OthersLotNo1 }}" disabled></td>
                                </tr>
                                <tr>
                                    <td class="break-spaces" width="20%">4.2 Poor customer Service (e.g., courtesy, professionalism, handling, responsiveness)</td>
                                    <!-- <td align="center"><input id="check-o2" type="checkbox"></td> -->
                                    <td width="20%"><input type="text" class="form-control o2-input" name="OthersPn2" value="{{ optional($data->others)->OthersPn2 }}" title="{{ optional($data->others)->OthersPn2 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control o2-input" name="OthersScNo2" value="{{ optional($data->others)->OthersScNo2 }}" title="{{ optional($data->others)->OthersScNo2 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control o2-input" name="OthersSoNo2" value="{{ optional($data->others)->OthersSoNo2 }}" title="{{ optional($data->others)->OthersSoNo2 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control o2-input" style="width: 80px" name="OthersQuantity2" value="{{ optional($data->others)->OthersQuantity2 }}" title="{{ optional($data->others)->OthersQuantity2 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control o2-input" style="width: 80px" name="OthersLotNo2" value="{{ optional($data->others)->OthersLotNo2 }}" title="{{ optional($data->others)->OthersLotNo2 }}" disabled></td>
                                </tr>
                                <tr>
                                    <td class="break-spaces" width="20%">4.3 Payment/ Invoice (e.g. wrong price/ product details)</td>
                                    <!-- <td align="center"><input id="check-o3" type="checkbox"></td> -->
                                    <td width="20%"><input type="text" class="form-control o3-input" name="OthersPn3" value="{{ optional($data->others)->OthersPn3 }}" title="{{ optional($data->others)->OthersPn3 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control o3-input" name="OthersScNo3" value="{{ optional($data->others)->OthersScNo3 }}" title="{{ optional($data->others)->OthersScNo3 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control o3-input" name="OthersSoNo3" value="{{ optional($data->others)->OthersSoNo3 }}" title="{{ optional($data->others)->OthersSoNo3 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control o3-input" style="width: 80px" name="OthersQuantity3" value="{{ optional($data->others)->OthersQuantity3 }}" title="{{ optional($data->others)->OthersQuantity3 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control o3-input" style="width: 80px" name="OthersLotNo3" value="{{ optional($data->others)->OthersLotNo3 }}" title="{{ optional($data->others)->OthersLotNo3 }}" disabled></td>
                                </tr>
                                <tr>
                                    <td class="break-spaces" width="20%">4.4 Other Issues (please specify)</td>
                                    <!-- <td align="center"><input id="check-o4" type="checkbox"></td> -->
                                    <td width="20%"><input type="text" class="form-control o4-input" name="OthersPn4" value="{{ optional($data->others)->OthersPn4 }}" title="{{ optional($data->others)->OthersPn4 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control o4-input" name="OthersScNo4" value="{{ optional($data->others)->OthersScNo4 }}" title="{{ optional($data->others)->OthersScNo4 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control o4-input" name="OthersSoNo4" value="{{ optional($data->others)->OthersSoNo4 }}" title="{{ optional($data->others)->OthersSoNo4 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control o4-input" style="width: 80px" name="OthersQuantity4" value="{{ optional($data->others)->OthersQuantity4 }}" title="{{ optional($data->others)->OthersQuantity4 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control o4-input" style="width: 80px" name="OthersLotNo4" value="{{ optional($data->others)->OthersLotNo4 }}" title="{{ optional($data->others)->OthersLotNo4 }}" disabled></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label><strong>Quantification of Cost/s</strong></label>
                        <hr class="alert-dark mt-0">
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3 col-md-2 text-right">
                            <p class="m-0"><b>Description :</b></p>
                        </div>
                        <div class="col-sm-3 col-md-4">
                            <p class="m-0">{{ $data->Description }}</p>
                        </div>
                        <div class="col-sm-3 col-md-2 text-right">
                            <p class="m-0"><b>Currency :</b></p>
                        </div>
                        <div class="col-sm-3 col-md-4">
                            <p class="m-0">{{ $data->Currency }}</p>
                        </div>
                    </div>
                    <!-- <div class="row mb-3">
                        <div class="col-sm-3 col-md-2 text-right">
                            <p class="m-0"><b>Customer Remarks :</b></p>
                        </div>
                        <div class="col-sm-3 col-md-9">
                            <p class="m-0">{{ $data->CustomerRemarks }}</p>
                        </div>
                    </div> -->
                    <div class="col-md-12 mb-3">
                        <label><strong>Investigation</strong></label>
                        <hr class="alert-dark mt-0">
                    </div>
                    <div class=" row mb-0">
                        <div class="col-sm-3 col-md-2 text-right">
                            <p class="m-0"><b>Immediate Action :</b></p>
                        </div>
                        <div class="col-sm-3 col-md-4">
                            <p class="m-0">{{ $data->ImmediateAction }}</p>
                        </div>
                        <div class="col-sm-3 col-md-2 text-right">
                            <p class="m-0"><b>Objective Evidence :</b></p>
                        </div>
                        <div class="col-sm-3 col-md-4">
                            <p class="m-0">{{ $data->ObjectiveEvidence }}</p>
                        </div>
                    </div>
                    <div class=" row mb-0">
                        <div class="col-sm-3 col-md-2 text-right">
                            <p class="m-0"> <b>Action Date :</b></p>
                        </div>
                        <div class="col-sm-3 col-md-4">
                            <!-- <p class="m-0">{{ $data->ActionDate ? \Carbon\Carbon::parse($data->ActionDate)->format('M. d, Y') : 'N/A' }}</p> -->
                            <p class="m-0">{{ $data->ActionDate }}</p>
                        </div>
                        <div class="col-sm-3 col-md-2 text-right">
                            <p class="m-0"><b>Action Responsible :</b></p>
                        </div>
                        <div class="col-sm-3 col-md-4">
                            <p class="m-0">{{ optional($data->action_responsible)->full_name }}</p>
                        </div>
                    </div>
                    <div class="row mb-0">
                        <div class="col-sm-3 col-md-2 text-right">
                            <p class="m-0"><b>Investigation:</b></p>
                        </div>
                        <div class="col-sm-3 col-md-9">
                            <p class="m-0">{{ $data->Investigation }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3 col-md-2 text-right">
                            <p class="m-0"><b>Corrective Action :</b></p>
                        </div>
                        <div class="col-sm-3 col-md-4">
                            <p class="m-0">{{ $data->CorrectiveAction }}</p>
                        </div>
                        <div class="col-sm-3 col-md-2 text-right"><p class="m-0"><b>Objective Evidence :</b></p></div>
                        <div class="col-sm-3 col-md-4">
                            <p class="m-0">{{ $data->ActionObjectiveEvidence }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3 col-md-2 text-right">
                            <p class="m-0"><b>Files :</b></p>
                        </div>
                        <div class="col-sm-3 col-md-6">
                            <p>
                                @foreach ($data->objective as $key => $file)
                                    @php
                                        $filePath = asset('storage/' . $file->Path); 
                                    @endphp
                                    {{$key + 1}}. <a href="{{ $filePath }}" target="_blank">{{ $file->Path }}</a> 
                                    @if($data->Department == auth()->user()->role->type)
                                        <button type="button" class="btn btn-sm deleteFile2" data-id="{{ $file->id }}">
                                            <i class="ti ti-close" style="color:red"></i>
                                        </button>  
                                    @endif         
                                    <br>
                                @endforeach
                            </p>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label><strong>Verification/ Recommendation</strong></label>
                        <hr class="alert-dark mt-0">
                    </div>
                    <div class="row mb-0">
                        <div class="col-sm-3 col-md-2 text-right">
                            <p class="m-0"><b>Acceptance :</b></p>
                        </div>
                        <div class="col-sm-3 col-md-9">
                            <p class="m-0">{{ $data->Acceptance}}</p>
                        </div>
                    </div>
                    <div class="row mb-0">
                        <div class="col-sm-3 col-md-2 text-right">
                            <p class="m-0"><b>Closed By :</b></p>
                        </div>
                        <div class="col-sm-3 col-md-4">
                            <p class="m-0">{{ optional($data->closed)->full_name }}</p>
                        </div>
                        <div class="col-sm-3 col-md-2 text-right">
                            <p class="m-0"><b>Closed Date :</b></p>
                        </div>
                        <div class="col-sm-3 col-md-4">
                            <p class="m-0">{{ $data->ClosedDate }}</p>
                        </div>
                    </div>
                    <div class="row mb-0">
                        <div class="col-sm-3 col-md-2 text-right"><p class="m-0"><b>Claims/Credit Note :</b></p></div>
                        <div class="col-sm-3 col-md-4">
                            <p class="m-0">
                                @if($data->Claims == 1)
                                    Yes
                                @elseif($data->Claims == 2)
                                    No 
                                @else

                                @endif
                            </p>
                        </div>
                        <div class="col-sm-3 col-md-2 text-right"><p class="m-0"><b>Shipment Return :</b></p></div>
                        <div class="col-sm-3 col-md-4">
                            <p class="m-0">
                                @if($data->Shipment == 1)
                                    Yes
                                @elseif($data->Shipment == 2)
                                    No 
                                @else

                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="row mb-0">
                        <div class="col-sm-3 col-md-2 text-right">
                            <p class="m-0"><b>Credit Note Number :</b></p>
                        </div>
                        <div class="col-sm-3 col-md-4">
                            <p class="m-0">{{ $data->CnNumber }}</p>
                        </div>
                        <div class="col-sm-3 col-md-2 text-right">
                            <p class="m-0"><b>Return Shipment Date :</b></p>
                        </div>
                        <div class="col-sm-3 col-md-4">
                            <p class="m-0">{{ $data->ShipmentDate }}</p>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <div class="col-sm-3 col-md-2 text-right">
                            <p class="m-0"><b>Total Amount Incurred :</b></p>
                        </div>
                        <div class="col-sm-3 col-md-4">
                            <p class="m-0">{{ $data->AmountIncurred }}</p>
                        </div>
                        <div class="col-sm-3 col-md-2 text-right">
                            <p class="m-0"><b>Return Shipment Cost :</b></p>
                        </div>
                        <div class="col-sm-3 col-md-4">
                            <p class="m-0">{{ $data->ShipmentCost }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3 col-md-2 text-right">
                            <p class="m-0"><b>Files :</b></p>
                        </div>
                        <div class="col-sm-3 col-md-6">
                            <p class="m-0">
                                @foreach ($data->verification as $key => $file)
                                    @php
                                        $filePath = asset('storage/' . $file->Path); 
                                    @endphp
                                    {{$key + 1}}. <a href="{{ $filePath }}" target="_blank">{{ $file->Path }}</a> 
                                        @if($data->ReceivedBy == auth()->user()->id)
                                            <button type="button" class="btn btn-sm deleteFile2" data-id="{{ $file->id }}">
                                                <i class="ti ti-close" style="color:red"></i>
                                            </button>   
                                        @endif        
                                        <br>
                                @endforeach
                            </p>
                        </div>
                    </div>
                    {{-- <div class="col-md-12 mb-3">
                        <label><strong>Files</strong></label>
                        <hr class="alert-dark mt-0">
                    </div>
                    <div class="col-md-12">
                        @foreach ($data->files as $key => $file)
                            @php
                                $filePath = asset('storage/' . $file->Path); 
                            @endphp
                            {{$key + 1}}. <a href="{{ $filePath }}" target="_blank">{{ $file->Path }}</a> <br>
                        @endforeach
                    </div> --}}
                    <div class="col-md-12 mb-3">
                        <label><strong>Sales Remarks</strong></label>
                        <hr class="alert-dark mt-0">
                    </div>
                    <div class="row mb-0">
                        <div class="col-sm-3 col-md-2 text-right">
                            <p class="m-0"><b>Remarks :</b></p>
                        </div>
                        <div class="col-sm-3 col-md-4">
                            <p class="m-0">{{ optional($data->ccsales->first())->SalesRemarks }}</p>
                        </div>
                        <div class="col-sm-3 col-md-2 text-right">
                            <p class="m-0"><b>Attachments :</b></p>
                        </div>
                        <div class="col-sm-3 col-md-4">
                            <p class="m-0">
                                @foreach ($data->ccsales as $key => $file)
                                    @php
                                        $filePath = asset('storage/' . $file->Path); 
                                    @endphp
                                    {{$key + 1}}. <a href="{{ $filePath }}" target="_blank">{{ $file->Path }}</a> 
                                    <br>
                                @endforeach
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editCc" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Investigation </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="updateCustomerComplaint" method="POST" action="{{ url('update_customer_complaint/' . $data->id) }}" enctype="multipart/form-data" onsubmit="show()">
                    @csrf
                    <!-- <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Recurring Issue:</label>
                                <div class="form-check form-check-inline" id="issue-radio">
                                    <input class="form-check-input" type="radio" name="RecurringIssue" id="flexRadioDefault1" value="1" 
                                        {{ isset($data->RecurringIssue) && $data->RecurringIssue == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="flexRadioDefault1">Yes</label>
                                    
                                    <input class="form-check-input" type="radio" name="RecurringIssue" id="flexRadioDefault2" value="2" 
                                        {{ isset($data->RecurringIssue) && $data->RecurringIssue != 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="flexRadioDefault2">No</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group issue-check" style="display: {{ isset($data->RecurringIssue) && $data->RecurringIssue == 1 ? 'block' : 'none' }};">
                                <label for="name">Previous CCF No. (If Yes):</label>
                                <input type="text" class="form-control" id="PreviousCCF" name="PreviousCCF" 
                                    value="{{ isset($data->PreviousCCF) && !empty($data->PreviousCCF) ? $data->PreviousCCF : '' }}" 
                                    placeholder="Enter CCF No.">
                            </div>
                        </div>
                    </div> -->
                    @if($data->NcarIssuance == 1)
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">NCAR No.</label>
                                <textarea type="text" class="form-control" id="IssuanceNo" name="IssuanceNo" rows="3" placeholder="Enter NCAR No." required>{{ $data->IssuanceNo }}</textarea>
                            </div>
                        </div>
                    </div>
                    @endif
                    <label>Immediate Action/Correction:</label>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Immediate Action</label>
                                <textarea type="text" class="form-control" id="ImmediateAction" name="ImmediateAction" rows="3" placeholder="Enter Immediate Action" required>{{ $data->ImmediateAction }}</textarea>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Objective Evidence</label>
                                <textarea type="text" class="form-control" id="ObjectiveEvidence" name="ObjectiveEvidence" rows="3" placeholder="Enter Objective Evidence" required>{{ $data->ObjectiveEvidence }}</textarea>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <label>Investigation of the Problem:</label>
                            <div class="form-group">
                                <label for="name">Investigation/ Root Cause Analysis</label>
                                <textarea type="text" class="form-control" id="Investigation" name="Investigation" rows="2" placeholder="Enter Investigation of the Problem">{{ $data->Investigation }}</textarea>
                            </div>
                        </div>
                    </div>
                    <label>Corrective Action Plan:</label>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Corrective Action</label>
                                <textarea type="text" class="form-control" id="CorrectiveAction" name="CorrectiveAction" rows="3" placeholder="Enter Corrective Action">{{ $data->CorrectiveAction }}</textarea>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Objective Evidence</label>
                                <textarea type="text" class="form-control" id="ActionObjectiveEvidence" name="ActionObjectiveEvidence" rows="3" placeholder="Enter Objective Evidence">{{ $data->ActionObjectiveEvidence }}</textarea>
                            </div>
                        </div>
                        <!-- <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Attachments</label>
                                <input type="file" name="Path[]" class="form-control" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                            </div>
                        </div> -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Attachments</label>
                                <input
                                type="file"
                                class="filepond"
                                name="Path[]"
                                id="Path5"
                                multiple
                                accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
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

<div class="modal fade" id="verificationCc" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Verification/ Recommendation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="customerAcceptance" method="POST" action="{{ url('customer_acceptance/' . $data->id) }}" enctype="multipart/form-data" onsubmit="show()">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="name">Client Feedback/ Acceptance</label>
                                <textarea type="text" class="form-control" id="Acceptance" name="Acceptance" rows="3" placeholder="Enter Client Feedback/ Acceptance" required>{{ $data->Acceptance }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">With Claims/Credit Note?</label>
                                <div class="form-check form-check-inline" id="check-radio">
                                    <!-- <input class="form-check-input" type="radio" name="Claims" id="flexRadioDefault1" value="1"
                                        {{ isset($data->Claims) && $data->Claims == 1 ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="flexRadioDefault1">Yes</label>

                                    <input class="form-check-input" type="radio" name="Claims" id="flexRadioDefault2" value="2"
                                        {{ isset($data->Claims) && $data->Claims != 1 ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="flexRadioDefault2">No</label> -->
                                    @php
                                        $isDisabled = !is_null($data->Claims) ? 'disabled' : '';
                                    @endphp

                                    <input class="form-check-input" type="radio" name="Claims" id="flexRadioDefault1" value="1"
                                        {{ $data->Claims == 1 ? 'checked' : '' }} {{ $isDisabled }} required>
                                    <label class="form-check-label" for="flexRadioDefault1">Yes</label>

                                    <input class="form-check-input" type="radio" name="Claims" id="flexRadioDefault2" value="2"
                                        {{ $data->Claims == 2 ? 'checked' : '' }} {{ $isDisabled }} required>
                                    <label class="form-check-label" for="flexRadioDefault2">No</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">For Shipment Return?</label>
                                <div class="form-check form-check-inline" id="ship-radio">
                                    @php
                                        $ShipmentDisabled = !is_null($data->Shipment) ? 'disabled' : '';
                                    @endphp
                                    <input class="form-check-input" type="radio" name="Shipment" id="flexRadioDefault1" value="1" {{ $data->Shipment == 1 ? 'checked' : '' }} {{ $ShipmentDisabled }} required>
                                    <label class="form-check-label" for="flexRadioDefault1">Yes</label>
                                    <input class="form-check-input" type="radio" name="Shipment" id="flexRadioDefault2" value="2" {{ $data->Shipment == 2 ? 'checked' : '' }} {{ $ShipmentDisabled }} required>
                                    <label class="form-check-label" for="flexRadioDefault2">No</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group cn-check" @if($data->Claims != 1) style="display:none;" @endif>
                                <label for="name">Credit Note Number</label>
                                <input type="text" class="form-control" id="CnNumber" name="CnNumber" placeholder="Enter Credit Note Number" value="{{$data->CnNumber}}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group ship-check" @if($data->Shipment != 1) style="display:none;" @endif>
                                <label for="name">Return Shipment Date</label>
                                <input type="date" class="form-control" id="ShipmentDate" name="ShipmentDate" placeholder="Enter Return Shipment Date" value="{{$data->ShipmentDate}}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group cn-check" @if($data->Claims != 1) style="display:none;" @endif>
                                <label for="name">Total Amount Incurred</label>
                                <input type="text" class="form-control" id="AmountIncurred" name="AmountIncurred" placeholder="Enter Total Amount Incurred" value="{{$data->AmountIncurred}}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group ship-check" @if($data->Shipment != 1) style="display:none;" @endif>
                                <label for="name">Return Shipment Cost</label>
                                <input type="text" class="form-control" id="ShipmentCost" name="ShipmentCost" placeholder="Enter Return Shipment Cost" value="{{$data->ShipmentCost}}">
                            </div>
                        </div>
                        <!-- <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Attachments</label>
                                <input type="file" name="Path[]" class="form-control" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                            </div>
                        </div> -->
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Attachments</label>
                                <input
                                type="file"
                                class="filepond"
                                name="Path[]"
                                id="Path6"
                                multiple
                                accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                            </div>
                        </div>
                        @if($data->Claims != null || $data->Shipment != null)
                            <div class="col-lg-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="IsVerified" value="1" id="flexCheckDefault" {{ $data->IsVerified == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Is Verified?
                                    </label>
                                </div>
                            </div>
                        @endif
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

@include('customer_service.complaint_modal')
@include('customer_service.update_complaint')
@if(session('openModalId'))
    <script>
        $(document).ready(function() {
            $('#update{{ session('openModalId') }}').modal('show');
        });
    </script>
@endif
<style>
    .break-spaces {
        white-space: break-spaces !important;
        white-space-collapse: break-spaces !important;
        text-wrap: wrap !important;
    }
</style>
<script src="https://unpkg.com/filepond/dist/filepond.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>

<script>
    function toggleInputs(checkboxId, inputClass) {
        document.getElementById(checkboxId).onchange = function() {
            const inputs = document.getElementsByClassName(inputClass);
            for (let input of inputs) {
                input.disabled = !this.checked;
            }
        };
    }

    toggleInputs('check-p1', 'p1-input');
    toggleInputs('check-p2', 'p2-input');
    toggleInputs('check-p3', 'p3-input');
    toggleInputs('check-p4', 'p4-input');
    toggleInputs('check-p5', 'p5-input');
    toggleInputs('check-p6', 'p6-input');

    toggleInputs('check-pack1', 'input-pack1');
    toggleInputs('check-pack2', 'input-pack2');
    toggleInputs('check-pack3', 'input-pack3');
    toggleInputs('check-pack4', 'input-pack4');

    toggleInputs('check-d1', 'd1-input');
    toggleInputs('check-d2', 'd2-input');
    toggleInputs('check-d3', 'd3-input');
    
    toggleInputs('check-o1', 'o1-input');
    toggleInputs('check-o2', 'o2-input');
    toggleInputs('check-o3', 'o3-input');
    toggleInputs('check-o4', 'o4-input');
    
    $(document).ready(function () {
        $('#updateCustomerComplaint').on('submit', function (e) {
            e.preventDefault(); 

            var formData = new FormData(this); // Use FormData to handle file uploads
            var actionUrl = $(this).attr('action');

            $.ajax({
                url: actionUrl,
                type: 'POST',
                data: formData,
                processData: false, 
                contentType: false, 
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: "Updated",
                            text: response.message,
                            icon: "success",
                            showConfirmButton: false,
                            timer: 1500
                        }).then(function () {
                            window.location.reload(); 
                        });
                    }
                }
            });
        });

        $('#customerAcceptance').on('submit', function (e) {
            e.preventDefault(); 

            var formData = new FormData(this); // Use FormData to handle file uploads
            var actionUrl = $(this).attr('action');

            $.ajax({
                url: actionUrl,
                type: 'POST',
                data: formData,
                processData: false, 
                contentType: false, 
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: "Verified",
                            text: response.message,
                            icon: "success",
                            showConfirmButton: false,
                            timer: 1500
                        }).then(function () {
                            window.location.reload(); 
                        });
                    }
                }
            });
        });

        $('.notedBtn').on('click', function (e) {
            e.preventDefault(); 

            var form = $(this).closest('form');
            var actionUrl = form.attr('action'); 

            $.ajax({
                url: actionUrl,
                type: 'POST',
                data: form.serialize(), 
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: "Noted",
                            text: response.message,
                            icon: "success",
                            showConfirmButton: false,
                            customClass: 'swal-wide',
                            timer: 1500
                        }).then(function () {
                            window.location.reload(); 
                        });
                    }
                }
            });
        });

        $('.receivedBtn').on('click', function (e) {
            e.preventDefault(); 

            var form = $(this).closest('form');
            var actionUrl = form.attr('action'); 

            $.ajax({
                url: actionUrl,
                type: 'POST',
                data: form.serialize(), 
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: "Received",
                            text: response.message,
                            icon: "success",
                            showConfirmButton: false,
                            customClass: 'swal-wide',
                            timer: 1500
                        }).then(function () {
                            window.location.reload(); 
                        });
                    }
                }
            });
        });

        $('.approvedBtn').on('click', function (e) {
            e.preventDefault(); // Prevent the default form submission

            var form = $(this).closest('form');
            var actionUrl = form.attr('action'); // Get form action URL

            $.ajax({
                url: actionUrl,
                type: 'POST',
                data: form.serialize(), // Serialize form data
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: "Acknowledged",
                            text: response.message,
                            icon: "success",
                            showConfirmButton: false,
                            customClass: 'swal-wide',
                            timer: 1500
                        }).then(function () {
                            window.location.reload(); // Reload the page after the alert
                        });
                    }
                }
            });
        });

        $('.closeBtn').on('click', function (e) {
            e.preventDefault(); 

            // Show loading before sending AJAX
            Swal.fire({
                title: 'Please wait...',
                text: 'Processing request',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            var form = $(this).closest('form');
            var actionUrl = form.attr('action'); 

            $.ajax({
                url: actionUrl,
                type: 'POST',
                data: form.serialize(), 
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: "Closed",
                            text: response.message,
                            icon: "success",
                            showConfirmButton: false,
                            customClass: 'swal-wide',
                            timer: 1500
                        }).then(function () {
                            window.location.reload(); 
                        });
                    }
                }
            });
        });

        $('#issue-radio').on('change', function() {
            var selectedValue = $('input[name="RecurringIssue"]:checked').val(); 
            if (selectedValue == "1") {
                $('.issue-check').show(); 
            } else {
                $('.issue-check').hide(); 
            }
        });

        $('#check-radio').on('change', function() {
            var selectedValue = $('input[name="Claims"]:checked').val(); 
            if (selectedValue == "1") {
                $('.cn-check').show(); 
            } else {
                $('.cn-check').hide(); 
            }
        });

        $('#ship-radio').on('change', function() {
            var selectedValue = $('input[name="Shipment"]:checked').val(); 
            if (selectedValue == "1") {
                $('.ship-check').show(); 
            } else {
                $('.ship-check').hide(); 
            }
        });

        $(document).on('click', '.deleteFile', function() {
            var fileId = $(this).data('id');
            var button = $(this);

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to recover this file!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('delete_cc_files', '') }}/" + fileId,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: response.success,
                                    icon: "success",
                                    timer: 1500, // Auto close after 1.5 sec
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.reload(); // Reload page after deletion
                                });
                            } else {
                                Swal.fire("Error!", response.error, "error");
                            }
                        },
                        error: function() {
                            Swal.fire("Error!", "Something went wrong.", "error");
                        }
                    });
                }
            });
        });

        $(document).on('click', '.deleteFile2', function() {
            var fileId = $(this).data('id');
            var button = $(this);

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to recover this file!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('delete_cc_files2', '') }}/" + fileId,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: response.success,
                                    icon: "success",
                                    timer: 1500, // Auto close after 1.5 sec
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.reload(); // Reload page after deletion
                                });
                            } else {
                                Swal.fire("Error!", response.error, "error");
                            }
                        },
                        error: function() {
                            Swal.fire("Error!", "Something went wrong.", "error");
                        }
                    });
                }
            });
        });

    });

    document.addEventListener('DOMContentLoaded', function () {
        // Register plugins
        FilePond.registerPlugin(
            // FilePondPluginFileValidateType,
            FilePondPluginFileValidateSize,
            FilePondPluginImagePreview
        );

        // Create FilePond instance
        FilePond.create(document.querySelector('#Path5'), {
            allowMultiple: true,
            maxFileSize: '10MB',
            server: {
            process: {
                url: '{{ url("/upload-temp-cc") }}',
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                // Return the serverId (filename) so FilePond stores it in Path[]
                onload: (response) => {
                try { return JSON.parse(response).id; } catch { return response; }
                }
            },
                revert: {
                    url: '{{ url("/upload-revert-cc") }}',
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                }
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        // Register plugins
        FilePond.registerPlugin(
            // FilePondPluginFileValidateType,
            FilePondPluginFileValidateSize,
            FilePondPluginImagePreview
        );

        // Create FilePond instance
        FilePond.create(document.querySelector('#Path6'), {
            allowMultiple: true,
            maxFileSize: '10MB',
            server: {
            process: {
                url: '{{ url("/upload-temp-cc") }}',
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                // Return the serverId (filename) so FilePond stores it in Path[]
                onload: (response) => {
                try { return JSON.parse(response).id; } catch { return response; }
                }
            },
                revert: {
                    url: '{{ url("/upload-revert-cc") }}',
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                }
            }
        });
    });
</script>
@endsection
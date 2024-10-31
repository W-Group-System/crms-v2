@extends('layouts.header')
@section('content')
<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">View Customer Complaint
                <div align="right">
                    <a href="{{ url()->previous() ?: url('customer_complaint2') }}" class="btn btn-md btn-outline-secondary">
                        <i class="icon-arrow-left"></i>&nbsp;Back
                    </a>
                    <form action="{{ url('cs_received/' . $data->id) }}" class="d-inline-block" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-success receivedBtn">
                            <i class="ti-bookmark">&nbsp;</i> Received
                        </button>
                    </form>
                    <button type="button" class="btn btn-outline-warning" id="updateCc" data-id="{{ $data->id }}" data-toggle="modal" data-target="#editCc">
                        <i class="ti ti-pencil"></i>&nbsp;Investigation
                    </button>
                    <button type="button" class="btn btn-outline-warning" id="updateCc" data-id="{{ $data->id }}" data-toggle="modal" data-target="#editCc">
                        <i class="ti ti-pencil"></i>&nbsp;Verification
                    </button>
                </div>
            </h4>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row mb-0" style="margin-top: 2em">
                        <label class="col-sm-3 col-form-label text-right"><b>CCF #:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->CcNumber }}</label>
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Quality Class:</b></label>
                        <div class="col-sm-3">
                            @if($data->QualityClass == '1')
                                Critical e.g., Food Safety Hazard
                            @elseif($data->QualityClass == '2')
                                Major e.g., Damage bags (2 Major recurring or 1 critical = NCAR)
                            @elseif($data->QualityClass == '3')
                                Minor/Marginal e.g., Late response
                            @elseif($data->QualityClass == '4')
                                Product name.<br>{{$data->ProductName}}
                            @endif
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <label class="col-sm-3 col-form-label text-right"><b>Date Complaint:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->created_at }}</label>
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Received By:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->users->full_name ?? 'N/A' }}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <label class="col-sm-3 col-form-label text-right"><b>Date Received:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->DateReceived }}</label>
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Department:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->concerned->Name ?? 'N/A' }}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label text-right"><b>Address:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->Address }}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <label class="col-sm-3 col-form-label text-right"><b>Country:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->country->Name }}</label>
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Status:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->Status == 10 ? 'Open' : 'Closed' }}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <label class="col-sm-3 col-form-label text-right"><b>Company Name:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->CompanyName }}</label>
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Date Closed:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->DateClosed ?? 'N/A' }}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <label class="col-sm-3 col-form-label text-right"><b>Contact Name:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->ContactName }}</label>
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Mode of Communication:</b></label>
                        <div class="col-sm-3">
                            @if($data->Moc == '1')
                                By Phone
                            @elseif($data->Moc == '2')
                                By Letter/ Fax
                            @elseif($data->Moc == '3')
                                Personal
                            @elseif($data->Moc == '4')
                                By Email
                            @endif
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <label class="col-sm-3 col-form-label text-right"><b>Email:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->Email }}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <label class="col-sm-3 col-form-label text-right"><b>Telephone:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->Telephone }}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label text-right"><b>Site Concerned:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->SiteConcerned }}</label>
                        </div>
                    </div>
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
                                    <td width="20%"><input type="text" class="form-control p1-input" name="Pn1" value="{{ $data->product_quality->Pn1 }}" title="{{ $data->product_quality->Pn1 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control p1-input" name="ScNo1" value="{{ $data->product_quality->ScNo1 }}" title="{{ $data->product_quality->ScNo1 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control p1-input" name="SoNo1" value="{{ $data->product_quality->SoNo1 }}" title="{{ $data->product_quality->SoNo1 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control p1-input" style="width: 80px" name="Quantity1" value="{{ $data->product_quality->Quantity1 }}" title="{{ $data->product_quality->Quantity1 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control p1-input" style="width: 80px" name="LotNo1" value="{{ $data->product_quality->LotNo1 }}" title="{{ $data->product_quality->LotNo1 }}" disabled></td>
                                </tr>
                                <tr>
                                    <td class="break-spaces" width="20%">1.2 Biological Hazard (e.g. high bacteria count, etc.)</td>
                                    <!-- <td align="center"><input id="check-p2" type="checkbox"></td> -->
                                    <td width="20%"><input type="text" class="form-control p2-input" name="Pn2" value="{{ $data->product_quality->Pn2 }}" title="{{ $data->product_quality->Pn2 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control p2-input" name="ScNo2" value="{{ $data->product_quality->ScNo2 }}" title="{{ $data->product_quality->ScNo2 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control p2-input" name="SoNo2" value="{{ $data->product_quality->SoNo2 }}" title="{{ $data->product_quality->SoNo2 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control p2-input" style="width: 80px" name="Quantity2" value="{{ $data->product_quality->Quantity2 }}" title="{{ $data->product_quality->Quantity2 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control p2-input" style="width: 80px" name="LotNo2" value="{{ $data->product_quality->LotNo2 }}" title="{{ $data->product_quality->LotNo2 }}" disabled></td>
                                </tr>
                                <tr>
                                    <td class="break-spaces" width="20%">1.3 Chemical Hazard (e.g. high heavy metals content, etc.)</td>
                                    <!-- <td align="center"><input id="check-p3" type="checkbox"></td> -->
                                    <td width="20%"><input type="text" class="form-control p3-input" name="Pn3" value="{{ $data->product_quality->Pn3 }}" title="{{ $data->product_quality->Pn3 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control p3-input" name="ScNo3" value="{{ $data->product_quality->ScNo3 }}" title="{{ $data->product_quality->ScNo3 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control p3-input" name="SoNo3" value="{{ $data->product_quality->SoNo3 }}" title="{{ $data->product_quality->SoNo3 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control p3-input" style="width: 80px" name="Quantity3" value="{{ $data->product_quality->Quantity3 }}" title="{{ $data->product_quality->Quantity3 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control p3-input" style="width: 80px" name="LotNo3" value="{{ $data->product_quality->LotNo3 }}" title="{{ $data->product_quality->LotNo3 }}" disabled></td>
                                </tr>
                                <tr>
                                    <td class="break-spaces" width="20%">1.4 Visual Defects (e.g. color change, particle size)</td>
                                    <!-- <td align="center"><input id="check-p4" type="checkbox"></td> -->
                                    <td width="20%"><input type="text" class="form-control p4-input" name="Pn4" value="{{ $data->product_quality->Pn4 }}" title="{{ $data->product_quality->Pn4 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control p4-input" name="ScNo4" value="{{ $data->product_quality->ScNo4 }}" title="{{ $data->product_quality->ScNo4 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control p4-input" name="SoNo4" value="{{ $data->product_quality->SoNo4 }}" title="{{ $data->product_quality->SoNo4 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control p4-input" style="width: 80px" name="Quantity4" value="{{ $data->product_quality->Quantity4 }}" title="{{ $data->product_quality->Quantity4 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control p4-input" style="width: 80px" name="LotNo4" value="{{ $data->product_quality->LotNo4 }}" title="{{ $data->product_quality->LotNo4 }}" disabled></td>
                                </tr>
                                <tr>
                                    <td class="break-spaces" width="20%">1.5 Application Problems (e.g. poor dispersion, poor distribution, poor binding property, high syneresis, etc.)</td>
                                    <!-- <td align="center"><input id="check-p5" type="checkbox"></td> -->
                                    <td width="20%"><input type="text" class="form-control p5-input" name="Pn5" value="{{ $data->product_quality->Pn5 }}" title="{{ $data->product_quality->Pn5 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control p5-input" name="ScNo5" value="{{ $data->product_quality->ScNo5 }}" title="{{ $data->product_quality->ScNo5 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control p5-input" name="SoNo5" value="{{ $data->product_quality->SoNo5 }}" title="{{ $data->product_quality->SoNo5 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control p5-input" style="width: 80px" name="Quantity5" value="{{ $data->product_quality->Quantity5 }}" title="{{ $data->product_quality->Quantity5 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control p5-input" style="width: 80px" name="LotNo5" value="{{ $data->product_quality->LotNo5 }}" title="{{ $data->product_quality->LotNo5 }}" disabled></td>
                                </tr>
                                <tr>
                                    <td class="break-spaces" width="20%">1.6 Physical/ Chemical Properties Out-of Specification (e.g. pH, gel strength, viscosity, syneresis and contamination with other ingredients)</td>
                                    <!-- <td align="center"><input id="check-p6" type="checkbox"></td> -->
                                    <td width="20%"><input type="text" class="form-control p6-input" name="Pn6" value="{{ $data->product_quality->Pn6 }}" title="{{ $data->product_quality->Pn6 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control p6-input" name="ScNo6" value="{{ $data->product_quality->ScNo6 }}" title="{{ $data->product_quality->ScNo6 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control p6-input" name="SoNo6" value="{{ $data->product_quality->SoNo6 }}" title="{{ $data->product_quality->SoNo6 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control p6-input" style="width: 80px" name="Quantity6" value="{{ $data->product_quality->Quantity6 }}" title="{{ $data->product_quality->Quantity6 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control p6-input" style="width: 80px" name="LotNo6" value="{{ $data->product_quality->LotNo6 }}" title="{{ $data->product_quality->LotNo6 }}" disabled></td>
                                </tr>
                                <tr>
                                    <td colspan="7"><b>2. Packaging</b></td>
                                </tr>
                                <tr>
                                    <td class="break-spaces" width="20%">2.1 Quantity (e.g. Short-packing, under-filled bags or box, over-filled container or box, etc.)</td>
                                    <!-- <td align="center"><input id="check-pack1" type="checkbox"></td> -->
                                    <td width="20%"><input type="text" class="form-control input-pack1" name="PackPn1" value="{{ $data->packaging->PackPn1 }}" title="{{ $data->packaging->PackPn1 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control input-pack1" name="PackScNo1" value="{{ $data->packaging->PackScNo1 }}" title="{{ $data->packaging->PackScNo1 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control input-pack1" name="PackSoNo1" value="{{ $data->packaging->PackSoNo1 }}" title="{{ $data->packaging->PackSoNo1 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control input-pack1" style="width: 80px" name="PackQuantity1" value="{{ $data->packaging->PackQuantity1 }}" title="{{ $data->packaging->PackQuantity1 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control input-pack1" style="width: 80px" name="PackLotNo1" value="{{ $data->packaging->PackLotNo1 }}" title="{{ $data->packaging->PackLotNo1 }}" disabled></td>
                                </tr>
                                <tr>
                                    <td class="break-spaces" width="20%">2.2 Packing (e.g. leakages, corrosion, etc.)</td>
                                    <!-- <td align="center"><input id="check-pack2" type="checkbox"></td> -->
                                    <td width="20%"><input type="text" class="form-control input-pack2" name="PackPn2" value="{{ $data->packaging->PackPn2 }}" title="{{ $data->packaging->PackPn2 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control input-pack2" name="PackScNo2" value="{{ $data->packaging->PackScNo2 }}" title="{{ $data->packaging->PackScNo2 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control input-pack2" name="PackSoNo2" value="{{ $data->packaging->PackSoNo2 }}" title="{{ $data->packaging->PackSoNo2 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control input-pack2" style="width: 80px" name="PackQuantity2" value="{{ $data->packaging->PackQuantity2 }}" title="{{ $data->packaging->PackQuantity2 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control input-pack2" style="width: 80px" name="PackLotNo2" value="{{ $data->packaging->PackLotNo2 }}" title="{{ $data->packaging->PackLotNo2 }}" disabled></td>
                                </tr>
                                <tr>
                                    <td class="break-spaces" width="20%">2.3 Labeling (e.g. wrong or defective label, unreadable, incorrect or incomplete text, etc.)</td>
                                    <!-- <td align="center"><input id="check-pack3" type="checkbox"></td> -->
                                    <td width="20%"><input type="text" class="form-control input-pack3" name="PackPn3" value="{{ $data->packaging->PackPn3 }}" title="{{ $data->packaging->PackPn3 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control input-pack3" name="PackScNo3" value="{{ $data->packaging->PackScNo3 }}" title="{{ $data->packaging->PackScNo3 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control input-pack3" name="PackSoNo3" value="{{ $data->packaging->PackSoNo3 }}" title="{{ $data->packaging->PackSoNo3 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control input-pack3" style="width: 80px" name="PackQuantity3" value="{{ $data->packaging->PackQuantity3 }}" title="{{ $data->packaging->PackQuantity3 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control input-pack3" style="width: 80px" name="PackLotNo3" value="{{ $data->packaging->PackLotNo3 }}" title="{{ $data->packaging->PackLotNo3 }}" disabled></td>
                                </tr>
                                <tr>
                                    <td class="break-spaces" width="20%">2.4 Packaging material (e.g. wrong packaging (bag, pallet, etc.) material, incorrect application of packaging instructions, inadequate quality of packaging material, etc.)</td>
                                    <!-- <td align="center"><input id="check-pack4" type="checkbox"></td> -->
                                    <td width="20%"><input type="text" class="form-control input-pack4" name="PackPn4" value="{{ $data->packaging->PackPn4 }}" title="{{ $data->packaging->PackPn4 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control input-pack4" name="PackScNo4" value="{{ $data->packaging->PackScNo4 }}" title="{{ $data->packaging->PackScNo4 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control input-pack4" name="PackSoNo4" value="{{ $data->packaging->PackSoNo4 }}" title="{{ $data->packaging->PackSoNo4 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control input-pack4" style="width: 80px" name="PackQuantity4" value="{{ $data->packaging->PackQuantity4 }}" title="{{ $data->packaging->PackQuantity4 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control input-pack4" style="width: 80px" name="PackLotNo4" value="{{ $data->packaging->PackLotNo4 }}" title="{{ $data->packaging->PackLotNo4 }}" disabled></td>
                                </tr>
                                <tr>
                                    <td colspan="7"><b>3. Delivery and Handling</b></td>
                                </tr>
                                <tr>
                                    <td class="break-spaces" width="20%">3.1 Product Handling (e.g. wrong product, pack size or quantity)</td>
                                    <!-- <td align="center"><input id="check-d1" type="checkbox"></td> -->
                                    <td width="20%"><input type="text" class="form-control d1-input" name="DhPn1" value="{{ $data->delivery_handling->DhPn1 }}" title="{{ $data->delivery_handling->DhPn1 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control d1-input" name="DhScNo1" value="{{ $data->delivery_handling->DhScNo1 }}" title="{{ $data->delivery_handling->DhScNo1 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control d1-input" name="DhSoNo1" value="{{ $data->delivery_handling->DhSoNo1 }}" title="{{ $data->delivery_handling->DhSoNo1 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control d1-input" style="width: 80px" name="DhQuantity1" value="{{ $data->delivery_handling->DhQuantity1 }}" title="{{ $data->delivery_handling->DhQuantity1 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control d1-input" style="width: 80px" name="DhLotNo1" value="{{ $data->delivery_handling->DhLotNo1 }}" title="{{ $data->delivery_handling->DhLotNo1 }}" disabled></td>
                                </tr>
                                <tr>
                                    <td class="break-spaces" width="20%">3.2 Delayed Delivery (e.g. inadequate forwarder service, wrong delivery address, etc.)</td>
                                    <!-- <td align="center"><input id="check-d2" type="checkbox"></td> -->
                                    <td width="20%"><input type="text" class="form-control d2-input" name="DhPn2" value="{{ $data->delivery_handling->DhPn2 }}" title="{{ $data->delivery_handling->DhPn2 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control d2-input" name="DhScNo2" value="{{ $data->delivery_handling->DhScNo2 }}" title="{{ $data->delivery_handling->DhScNo2 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control d2-input" name="DhSoNo2" value="{{ $data->delivery_handling->DhSoNo2 }}" title="{{ $data->delivery_handling->DhSoNo2 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control d2-input" style="width: 80px" name="DhQuantity2" value="{{ $data->delivery_handling->DhQuantity2 }}" title="{{ $data->delivery_handling->DhQuantity2 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control d2-input" style="width: 80px" name="DhLotNo2" value="{{ $data->delivery_handling->DhLotNo2 }}" title="{{ $data->delivery_handling->DhLotNo2 }}" disabled></td>
                                </tr>
                                <tr>
                                    <td class="break-spaces" width="20%">3.3 Product Damage during transit (e.g. leakages, corrosion, damaged label/box/carton/seal, etc.)</td>
                                    <!-- <td align="center"><input id="check-d3" type="checkbox"></td> -->
                                    <td width="20%"><input type="text" class="form-control d3-input" name="DhPn3" value="{{ $data->delivery_handling->DhPn3 }}" title="{{ $data->delivery_handling->DhPn3 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control d3-input" name="DhScNo3" value="{{ $data->delivery_handling->DhScNo3 }}" title="{{ $data->delivery_handling->DhScNo3 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control d3-input" name="DhSoNo3" value="{{ $data->delivery_handling->DhSoNo3 }}" title="{{ $data->delivery_handling->DhSoNo3 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control d3-input" style="width: 80px" name="DhQuantity3" value="{{ $data->delivery_handling->DhQuantity3 }}" title="{{ $data->delivery_handling->DhQuantity3 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control d3-input" style="width: 80px" name="DhLotNo3" value="{{ $data->delivery_handling->DhLotNo3 }}" title="{{ $data->delivery_handling->DhLotNo3 }}" disabled></td>
                                </tr>
                                <tr>
                                    <td colspan="7"><b>4. Others</b></td>
                                </tr>
                                <tr>
                                    <td class="break-spaces" width="20%">4.1 Quality of records or documents (e.g. insufficient, inadequate, missing, etc.)</td>
                                    <!-- <td align="center"><input id="check-o1" type="checkbox"></td> -->
                                    <td width="20%"><input type="text" class="form-control o1-input" name="OthersPn1" value="{{ $data->others->OthersPn1 }}" title="{{ $data->others->OthersPn1 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control o1-input" name="OthersScNo1" value="{{ $data->others->OthersScNo1 }}" title="{{ $data->others->OthersScNo1 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control o1-input" name="OthersSoNo1" value="{{ $data->others->OthersSoNo1 }}" title="{{ $data->others->OthersSoNo1 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control o1-input" style="width: 80px" name="OthersQuantity1" value="{{ $data->others->OthersQuantity1 }}" title="{{ $data->others->OthersQuantity1 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control o1-input" style="width: 80px" name="OthersLotNo1" value="{{ $data->others->OthersLotNo1 }}" title="{{ $data->others->OthersLotNo1 }}" disabled></td>
                                </tr>
                                <tr>
                                    <td class="break-spaces" width="20%">4.2 Poor customer Service (e.g., courtesy, professionalism, handling, responsiveness)</td>
                                    <!-- <td align="center"><input id="check-o2" type="checkbox"></td> -->
                                    <td width="20%"><input type="text" class="form-control o2-input" name="OthersPn2" value="{{ $data->others->OthersPn2 }}" title="{{ $data->others->OthersPn2 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control o2-input" name="OthersScNo2" value="{{ $data->others->OthersScNo2 }}" title="{{ $data->others->OthersScNo2 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control o2-input" name="OthersSoNo2" value="{{ $data->others->OthersSoNo2 }}" title="{{ $data->others->OthersSoNo2 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control o2-input" style="width: 80px" name="OthersQuantity2" value="{{ $data->others->OthersQuantity2 }}" title="{{ $data->others->OthersQuantity2 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control o2-input" style="width: 80px" name="OthersLotNo2" value="{{ $data->others->OthersLotNo2 }}" title="{{ $data->others->OthersLotNo2 }}" disabled></td>
                                </tr>
                                <tr>
                                    <td class="break-spaces" width="20%">4.3 Payment/ Invoice (e.g. wrong price/ product details)</td>
                                    <!-- <td align="center"><input id="check-o3" type="checkbox"></td> -->
                                    <td width="20%"><input type="text" class="form-control o3-input" name="OthersPn3" value="{{ $data->others->OthersPn3 }}" title="{{ $data->others->OthersPn3 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control o3-input" name="OthersScNo3" value="{{ $data->others->OthersScNo3 }}" title="{{ $data->others->OthersScNo3 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control o3-input" name="OthersSoNo3" value="{{ $data->others->OthersSoNo3 }}" title="{{ $data->others->OthersSoNo3 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control o3-input" style="width: 80px" name="OthersQuantity3" value="{{ $data->others->OthersQuantity3 }}" title="{{ $data->others->OthersQuantity3 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control o3-input" style="width: 80px" name="OthersLotNo3" value="{{ $data->others->OthersLotNo3 }}" title="{{ $data->others->OthersLotNo3 }}" disabled></td>
                                </tr>
                                <tr>
                                    <td class="break-spaces" width="20%">4.4 Other Issues (please specify)</td>
                                    <!-- <td align="center"><input id="check-o4" type="checkbox"></td> -->
                                    <td width="20%"><input type="text" class="form-control o4-input" name="OthersPn4" value="{{ $data->others->OthersPn4 }}" title="{{ $data->others->OthersPn4 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control o4-input" name="OthersScNo4" value="{{ $data->others->OthersScNo4 }}" title="{{ $data->others->OthersScNo4 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control o4-input" name="OthersSoNo4" value="{{ $data->others->OthersSoNo4 }}" title="{{ $data->others->OthersSoNo4 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control o4-input" style="width: 80px" name="OthersQuantity4" value="{{ $data->others->OthersQuantity4 }}" title="{{ $data->others->OthersQuantity4 }}" disabled></td>
                                    <td width="15%"><input type="text" class="form-control o4-input" style="width: 80px" name="OthersLotNo4" value="{{ $data->others->OthersLotNo4 }}" title="{{ $data->others->OthersLotNo4 }}" disabled></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label><strong>Quantification of Cost/s</strong></label>
                        <hr class="alert-dark mt-0">
                    </div>
                    <div class="form-group row mb-0">
                        <label class="col-sm-3 col-form-label text-right"><b>Description:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->Description }}</label>
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Currency (In PHP/ In US$/ In EUR):</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->Currency ?? 'N/A' }}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label text-right"><b>Customer Remarks:</b></label>
                        <div class="col-sm-9">
                            <label>{{ $data->CustomerRemarks }}</label>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label><strong>Investigation (Department Concerned Responsibility)</strong></label>
                        <hr class="alert-dark mt-0">
                    </div>
                    <div class="form-group row mb-0">
                        <label class="col-sm-3 col-form-label text-right"><b>Immediate Action:</b></label>
                        <div class="col-sm-3">
                            <label></label>
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Objective Evidence:</b></label>
                        <div class="col-sm-3">
                            <label></label>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <label class="col-sm-3 col-form-label text-right"><b>Action/ Implementation Date:</b></label>
                        <div class="col-sm-3">
                            <label></label>
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Action Responsible:</b></label>
                        <div class="col-sm-3">
                            <label></label>
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
                <form id="updateCustomerComplaint" method="POST" action="{{ url('update_customer_complaint/' . $data->id) }}">
                    @csrf
                    <label>Immediate Action/Correction:</label>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Immediate Action</label>
                                <textarea type="text" class="form-control" id="ImmediateAction" name="ImmediateAction" rows="3" placeholder="Enter Immediate Action"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Objective Evidence</label>
                                <textarea type="text" class="form-control" id="ObjectiveEvidence" name="ObjectiveEvidence" rows="3" placeholder="Enter Objective Evidence"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="name">Investigation/ Root Cause Analysis</label>
                                <textarea type="text" class="form-control" id="Investigation" name="Investigation" rows="2" placeholder="Enter Immediate Action"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Corrective Action</label>
                                <textarea type="text" class="form-control" id="CorrectiveAction" name="CorrectiveAction" rows="3" placeholder="Enter Corrective Action"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Objective Evidence</label>
                                <textarea type="text" class="form-control" id="ObjectiveEvidence" name="ObjectiveEvidence" rows="3" placeholder="Enter Objective Evidence"></textarea>
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

<style>
    .break-spaces {
        white-space: break-spaces !important;
        white-space-collapse: break-spaces !important;
        text-wrap: wrap !important;
    }
</style>
@endsection
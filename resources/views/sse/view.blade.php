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
                    <div class="form-group row mb-0">
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
                    <div class="form-group row mb-0">
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
                                    Others
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
                            <label>{{ $data->SampleType }}</label>
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Laboratory work required:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->ProductDeclared }}</label>
                            @if($data->shipment_work && count($data->shipment_work) > 0)
                                @foreach($data->shipment_work as $work)
                                    <label>{{ $work->Work }}</label><br>
                                @endforeach
                            @else
                                <label>No Work Available</label>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <label class="col-sm-3 col-form-label text-right"><b>Attachments:</b></label>
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
@endsection
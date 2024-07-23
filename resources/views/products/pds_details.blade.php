@extends('layouts.header')
@section('content')

<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h2 class="text-center">{{$pds->products->code}}</h2>
            <h5 class="text-center">Control No : {{$pds->ControlNumber}}</h5>
            <h5 class="text-center">Client : @if($pds->Clients)  {{$pds->clients->Name}} @endif</h5>

            <div class="row">
                <div class="col-lg-12 mb-3">
                    <label><strong>DESCRIPTION</strong></label>
                    <hr style="margin-top: 0px; color: black; border-top-color: black;">
                    <p>{{$pds->Description}}</p>
                </div>
                <div class="col-lg-12 mb-3">
                    <label><strong>APPLICATION</strong></label>
                    <hr style="margin-top: 0px; color: black; border-top-color: black;">
                    <p>{{$pds->Application}}</p>
                </div>
                <div class="col-lg-12 mb-3">
                    <label><strong>POTENTIAL BENEFITS</strong></label>
                    <hr style="margin-top: 0px; color: black; border-top-color: black;">
                    @foreach ($pds->productPotentialBenefit as $data)
                        <p>{{$data->Benefit}}</p> <br>
                    @endforeach
                </div>
                <div class="col-lg-12 mb-3">
                    <label><strong>PHYSICO-CHEMICAL ANALYSES</strong></label>
                    <hr style="margin-top: 0px; color: black; border-top-color: black;">
                    <div class="row">
                        @foreach ($pds->productPhysicoChemicalAnalyses as $data)
                            <div class="col-lg-4">
                                <p>{{$data->Parameter}}</p>
                            </div>
                            <div class="col-lg-4">
                                <p>{{$data->Value}}</p>
                            </div>
                            <div class="col-lg-4">
                                <p>{{$data->Remarks}}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-12 mb-3">
                    <label><strong>MICROBIOLOGICAL ANALYSES</strong></label>
                    <hr style="margin-top: 0px; color: black; border-top-color: black;">
                    <div class="row">
                        @foreach ($pds->productMicrobiologicalAnalysis as $data)
                            <div class="col-lg-4">
                                <p>{{$data->Parameter}}</p>
                            </div>
                            <div class="col-lg-4">
                                <p>{{$data->Value}}</p>
                            </div>
                            <div class="col-lg-4">
                                <p>{{$data->Remarks}}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-12 mb-3">
                    <label><strong>HEAVY METALS</strong></label>
                    <hr style="margin-top: 0px; color: black; border-top-color: black;">
                    <div class="row">
                        @foreach ($pds->productHeavyMetal as $data)
                            <div class="col-lg-6">
                                <p>{{$data->Parameter}}</p>
                            </div>
                            <div class="col-lg-6">
                                <p>{{$data->Value}}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-6 mb-3">
                    <label><strong>NUTRITIONAL INFORMATION</strong></label>
                    <hr style="margin-top: 0px; color: black; border-top-color: black;">
                    <div class="row">
                        @foreach ($pds->productNutritionalInformation as $data)
                            <div class="col-lg-6">
                                <p>{{$data->Parameter}}</p>
                            </div>
                            <div class="col-lg-6">
                                <p>{{$data->Value}}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-6 mb-3">
                    <label><strong>ALLERGENS</strong></label>
                    <hr style="margin-top: 0px; color: black; border-top-color: black;">
                    <div class="row">
                        <div class="col-lg-4"></div>
                        <div class="col-lg-4">YES</div>
                        <div class="col-lg-4">NO</div>
                        @foreach ($pds->productAllergens as $data)
                            <div class="col-lg-4">
                                <p>{{$data->Parameter}}</p>
                            </div>
                            <div class="col-lg-4">
                                <p>
                                    @if($data->IsAllergen == 1)
                                        <i class="ti-close"></i>
                                    @endif
                                </p>
                            </div>
                            <div class="col-lg-4">
                                <p>
                                    @if($data->IsAllergen == 0)
                                        <i class="ti-close"></i>
                                    @endif
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-12 mb-3">
                    <label><strong>DIRECTION FOR USE</strong></label>
                    <hr style="margin-top: 0px; color: black; border-top-color: black;">
                    {!! nl2br($pds->DirectionForUse) !!}
                </div>
                <div class="col-lg-12 mb-3">
                    <label><strong>STORAGE</strong></label>
                    <hr style="margin-top: 0px; color: black; border-top-color: black;">
                    {!! nl2br($pds->Storage) !!}
                </div>
                <div class="col-lg-12 mb-3">
                    <label><strong>TECHNICAL ASSISTANCE</strong></label>
                    <hr style="margin-top: 0px; color: black; border-top-color: black;">
                    {!! nl2br($pds->TechnicalAssistance) !!}
                </div>
                <div class="col-lg-12 mb-3">
                    <label><strong>PURITY AND LEGAL STATUS</strong></label>
                    <hr style="margin-top: 0px; color: black; border-top-color: black;">
                    {!! nl2br($pds->PurityAndLegalStatus) !!}
                </div>
                <div class="col-lg-12 mb-3">
                    <label><strong>PACKAGING</strong></label>
                    <hr style="margin-top: 0px; color: black; border-top-color: black;">
                    {!! nl2br($pds->Packaging) !!}
                </div>
                <div class="col-lg-12 mb-3">
                    <label><strong>CERTIFICATIONS</strong></label>
                    <hr style="margin-top: 0px; color: black; border-top-color: black;">
                    {!! nl2br($pds->Certification) !!}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
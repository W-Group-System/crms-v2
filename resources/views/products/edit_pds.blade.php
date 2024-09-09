<div class="modal fade" id="pdsModal-{{$data->productDataSheet->Id}}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Edit PDS</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form method="POST" action="{{url('edit_product_ds/'.$data->productDataSheet->Id)}}" onsubmit="show()">
                {{csrf_field()}}
                
                <input type="hidden" name="product_id" value="{{$data->id}}">
                {{-- <input type="hidden" name="product_datasheet_id" value="{{$data->productDataSheet->Id}}"> --}}
                <div class="modal-body" style="padding: 20px">
                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            Product: {{$data->code}}
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label>Company :</label>
                            <select data-placeholder="Choose company" name="company" class="js-example-basic-single form-control form-control-sm">
                                <option value="">-Company-</option>
                                @foreach ($client as $c)
                                    <option value="{{$c->id}}" @if($c->id == $data->productDataSheet->CompanyId) selected @endif>{{$c->Name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label>Control Number :</label>
                            <input type="text" name="control_number" class="form-control form-control-sm" placeholder="Enter control number" value="{{$data->productDataSheet->ControlNumber}}" required>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label>Date Issued :</label>
                            <input type="date" name="date_issued" class="form-control form-control-sm" value="{{$data->productDataSheet->DateIssued}}" required>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label>Description :</label>
                            <textarea name="description" class="form-control form-control-sm" cols="30" rows="10" placeholder="Enter description" required>{!! nl2br($data->productDataSheet->Description) !!}</textarea>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label>Description 2:</label>
                            <textarea name="description2" class="form-control form-control-sm" cols="30" rows="10" placeholder="Enter description">{!! nl2br($data->productDataSheet->Description2) !!}</textarea>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label>Appearance :</label>
                            <textarea name="appearance" class="form-control form-control-sm" cols="30" rows="10" placeholder="Enter appearance">{!! nl2br($data->productDataSheet->Appearance) !!}</textarea>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label>Application :</label>
                            <textarea name="application" class="form-control form-control-sm" cols="30" rows="10" placeholder="Enter application">{!! nl2br($data->productDataSheet->Application) !!}</textarea>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <div class="mb-3">
                                <button class="btn btn-sm btn-success addPotentialBenefit" type="button">
                                    <i class="ti-plus"></i>
                                </button>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <label>Potential Benefits :</label>
                                    <div class="potentialBenefitContainer">
                                        @foreach ($data->productDataSheet->productPotentialBenefit as $pb)
                                            <div class="row">
                                                <div class="col-lg-10">
                                                    <input type="text" name="potentialBenefit[]" class="form-control form-control-sm mb-2" value="{{$pb->Benefit}}">
                                                </div>
                                                <div class="col-lg-2">
                                                    <button class="btn btn-sm btn-danger removePotentialBenefit" type="button">
                                                        <i class="ti-minus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <div class="mb-3">
                                <button class="btn btn-sm btn-success addPca" type="button">
                                    <i class="ti-plus"></i>
                                </button>
                            </div>

                            <div class="row">
                                <div class="col-lg-12 mb-3">
                                    <label>Physico Chemical Analyses :</label>
                                    <div class="pcaContainer">
                                        @foreach ($data->productDataSheet->productPhysicoChemicalAnalyses as $pca)
                                            <div class="row">
                                                <div class="col-lg-3">
                                                    <input type="text" name="pcaParameter[]" placeholder="Enter parameter" class="form-control form-control-sm mb-2" value="{{$pca->Parameter}}">
                                                </div>
                                                <div class="col-lg-3">
                                                    <input type="text" name="pcaValue[]" placeholder="Enter value" class="form-control form-control-sm mb-2" value="{{$pca->Value}}">
                                                </div>
                                                <div class="col-lg-3">
                                                    <input type="text" name="pcaRemark[]" placeholder="Enter remark" class="form-control form-control-sm mb-2" value="{{$pca->Remarks}}">
                                                </div>
                                                <div class="col-lg-3">
                                                    <button class="btn btn-sm btn-danger removePca" type="button">
                                                        <i class="ti-minus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 mb-3">
                            <div class="mb-3">
                                <button class="btn btn-sm btn-success addMa" type="button">
                                    <i class="ti-plus"></i>
                                </button>
                            </div>

                            <div class="row">
                                <div class="col-lg-12 mb-3">
                                    <label>Microbiological Analyses :</label>
                                    <div class="maContainer">
                                        @foreach ($data->productDataSheet->productMicrobiologicalAnalysis as $ma)
                                            <div class="row">
                                                <div class="col-lg-3">
                                                    <input type="text" name="maParameter[]" placeholder="Enter parameter" class="form-control form-control-sm mb-2" value="{{$ma->Parameter}}">
                                                </div>
                                                <div class="col-lg-3">
                                                    <input type="text" name="maValue[]" placeholder="Enter value" class="form-control form-control-sm mb-2" value="{{$ma->Value}}">
                                                </div>
                                                <div class="col-lg-3">
                                                    <input type="text" name="maRemark[]" placeholder="Enter remark" class="form-control form-control-sm mb-2" value="{{$ma->Remarks}}">
                                                </div>
                                                <div class="col-lg-3">
                                                    <button class="btn btn-sm btn-danger removeMa" type="button">
                                                        <i class="ti-minus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 mb-3">
                            <div class="mb-3">
                                <button class="btn btn-sm btn-success addHeavyMetals" type="button">
                                    <i class="ti-plus"></i>
                                </button>
                            </div>

                            <div class="row">
                                <div class="col-lg-12 mb-3">
                                    <label>Heavy Metals :</label>
                                    <div class="heavyMetalsContainer">
                                        @foreach ($data->productDataSheet->productHeavyMetal as $heavyMetals)
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <input type="text" name="heavyMetalsParameter[]" placeholder="Enter parameter" class="form-control form-control-sm mb-2" value="{{$heavyMetals->Parameter}}">
                                                </div>
                                                <div class="col-lg-4">
                                                    <input type="text" name="heavyMetalsValue[]" placeholder="Enter value" class="form-control form-control-sm mb-2" value="{{$heavyMetals->Value}}">
                                                </div>
                                                <div class="col-lg-4">
                                                    <button class="btn btn-sm btn-danger removeHeavyMetals" type="button">
                                                        <i class="ti-minus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <div class="mb-3">
                                <button class="btn btn-sm btn-success addNutritionalInfo" type="button">
                                    <i class="ti-plus"></i>
                                </button>
                            </div>

                            <div class="row">
                                <div class="col-lg-12 mb-3">
                                    <label>Nutritional Information :</label>
                                    <div class="nutrionalInfoContainer">
                                        @foreach ($data->productDataSheet->productNutritionalInformation as $nutrionalInfo)
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <input type="text" name="nutrionalInfoParameter[]" placeholder="Enter parameter" class="form-control form-control-sm mb-2" value="{{$nutrionalInfo->Parameter}}">
                                                </div>
                                                <div class="col-lg-4">
                                                    <input type="text" name="nutrionalInfoValue[]" placeholder="Enter value" class="form-control form-control-sm mb-2" value="{{$nutrionalInfo->Value}}">
                                                </div>
                                                <div class="col-lg-4">
                                                    <button class="btn btn-sm btn-danger removeNutritionalInfo" type="button">
                                                        <i class="ti-minus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <div class="mb-3">
                                <button class="btn btn-sm btn-success addAllergens" type="button">
                                    <i class="ti-plus"></i>
                                </button>
                            </div>

                            <div class="row">
                                <div class="col-lg-12 mb-3">
                                    <label>Allergens :</label>
                                    <div class="allergensContainer">
                                        @foreach ($data->productDataSheet->productAllergens as $allergens)
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <input type="text" name="allergensParameter[]" placeholder="Enter parameter" class="form-control form-control-sm mb-2" value="{{$allergens->Parameter}}">
                                                </div>
                                                <div class="col-lg-4">
                                                    <input type="checkbox" name="isAllergen[]" class="form-control form-control-sm" @if($allergens->IsAllergen == 1)checked @endif>
                                                </div>
                                                <div class="col-lg-4">
                                                    <button class="btn btn-sm btn-danger removeAllergens" type="button">
                                                        <i class="ti-minus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label>Direction for use :</label>
                            <textarea name="direction_for_use" class="form-control form-control-sm" cols="30" rows="10">{!! nl2br($data->productDataSheet->DirectionForUse) !!}</textarea>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label>Storage :</label>
                            <textarea name="storage" class="form-control form-control-sm" cols="30" rows="10">{!! nl2br($data->productDataSheet->Storage) !!}</textarea>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label>Technical Assistance :</label>
                            <textarea name="technical_assistance" class="form-control form-control-sm" cols="30" rows="10">{!! nl2br($data->productDataSheet->TechnicalAssistance) !!}</textarea>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label>Purity and Legal Status :</label>
                            <textarea name="purity_and_legal_status" class="form-control form-control-sm" cols="30" rows="10">{!! nl2br($data->productDataSheet->PurityAndLegalStatus) !!}</textarea>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label>Packaging :</label>
                            <textarea name="packaging" class="form-control form-control-sm" cols="30" rows="10">{!! nl2br($data->productDataSheet->Packaging) !!}</textarea>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label>Certifications :</label>
                            <textarea name="certifications" class="form-control form-control-sm" cols="30" rows="10">{!! nl2br($data->productDataSheet->Certification) !!}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="padding: 0.6875rem">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" id="yes_button" class="btn btn-success">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
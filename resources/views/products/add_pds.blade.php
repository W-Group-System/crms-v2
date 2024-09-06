<div class="modal fade" id="pdsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Add PDS</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form method="POST" action="{{url('add_product_ds')}}" onsubmit="show()">
                {{csrf_field()}}
                
                <input type="hidden" name="product_id" value="{{$data->id}}">
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
                                    <option value="{{$c->id}}" >{{$c->Name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label>Control Number :</label>
                            <input type="text" name="control_number" class="form-control form-control-sm" placeholder="Enter control number" required>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label>Date Issued :</label>
                            <input type="date" name="date_issued" class="form-control form-control-sm" min="{{date('Y-m-d')}}" value="{{date('Y-m-d')}}" required>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label>Description :</label>
                            <textarea name="description" class="form-control form-control-sm" cols="30" rows="10" placeholder="Enter description" required>{{'consist of refined carrageenan and other hydrocolloid gum standardized with monosaccharide. It is stable in neutral or alkali medium and insoluble in alcohol and other organic solvents.'}}</textarea>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label>Description 2:</label>
                            <textarea name="description2" class="form-control form-control-sm" cols="30" rows="10" placeholder="Enter description"></textarea>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label>Appearance :</label>
                            <textarea name="appearance" class="form-control form-control-sm" cols="30" rows="10" placeholder="Enter appearance"></textarea>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label>Application :</label>
                            <textarea name="application" class="form-control form-control-sm" cols="30" rows="10" placeholder="Enter application"></textarea>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <div class="mb-3">
                                <button class="btn btn-sm btn-success addPotentialBenefit" type="button">
                                    <i class="ti-plus"></i>
                                </button>
                            </div>

                            <div class="row">
                                <div class="col-lg-12 mb-3">
                                    <label>Potential Benefits :</label>
                                    <div class="potentialBenefitContainer">
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
                                        @php
                                            $analysis = physioChemicalAnalysis();
                                        @endphp
                                        @foreach ($analysis as $pca)
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
                                        @php
                                            $microbiological_analysis = microbiologicalAnalysis();
                                        @endphp
                                        @foreach ($microbiological_analysis as $ma)
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
                                        @php
                                            $heavyMetals = heavyMetals()
                                        @endphp
                                        @foreach ($heavyMetals as $heavyMetals)
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
                                        @php
                                            $nutrionalInformation = nutrionalInformation();
                                        @endphp
                                        @foreach ($nutrionalInformation as $nutrionalInfo)
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
                                        @php
                                            $allergens = allergens();
                                        @endphp
                                        @foreach ($allergens as $allergens)
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
                            <textarea name="direction_for_use" class="form-control form-control-sm" cols="30" rows="10">{{'Premix 0.5 - 1.5% of test12345 with sugar and other dry ingredients. Complete dissolution is achieved by heating up to 80° to 90°C.'}}</textarea>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label>Storage :</label>
                            <textarea name="storage" class="form-control form-control-sm" cols="30" rows="10">{{'Store in a cool dry place. Shelf life of product is 2 years from manufacturing date.'}}</textarea>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label>Technical Assistance :</label>
                            <textarea name="technical_assistance" class="form-control form-control-sm" cols="30" rows="10">{{'For technical related queries or assistance needed regarding this or any Rico Carrageenan product, you may contact the sales representative you are in communication with or you may send your questions to sales@rico.com.ph.We will coordinate with you at the soonest possible time.'}}</textarea>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label>Purity and Legal Status :</label>
                            <textarea name="purity_and_legal_status" class="form-control form-control-sm" cols="30" rows="10">{{'test12345 confirms to the definition and specifications from JECFA (FAO/WHO), FDA 21 CFR 172.620 and European Commission Regulation 231 / 2012 / EC.However, we recommend that the user ensures that this product is in compliance with the local regulations in force, particularly in the country where the product is to be consumed.'}}</textarea>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label>Packaging :</label>
                            <textarea name="packaging" class="form-control form-control-sm" cols="30" rows="10">{{'25 kg multi-walled paper or Kraft bags with heat sealed Polyethylene liner.'}}</textarea>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label>Certifications :</label>
                            <textarea name="certifications" class="form-control form-control-sm" cols="30" rows="10">{{'Non-GMO (Genetically Modified Organisms) and Allergen-free as well as HALAL and KOSHER Certificates can be issued upon request.'}}</textarea>
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
<div class="modal fade" id="complaint{{$data->id}}">
    <link href="{{ asset('css/filepond.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Complaint</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{url('cc_update/'.$data->id)}}" onsubmit="show()">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Recurring Issue:</label>
                                <div class="form-check form-check-inline" id="issue-radio2"> 
                                    <input class="form-check-input" type="radio" name="RecurringIssue" id="flexRadioDefault1" value="1"
                                        {{ isset($data->RecurringIssue) && $data->RecurringIssue == 1 ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="flexRadioDefault1">Yes</label>
                                    
                                    <input class="form-check-input" type="radio" name="RecurringIssue" id="flexRadioDefault2" value="2"
                                        {{ isset($data->RecurringIssue) && $data->RecurringIssue != 1 ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="flexRadioDefault2">No</label>
                                </div>
                            </div>
                        </div>                        
                        <div class="col-lg-6" >
                            <div class="form-group">
                                <label for="name">For NCAR Issuance:</label>
                                <div class="form-check form-check-inline" id="issue-radio">
                                    <input class="form-check-input" type="radio" name="NcarIssuance" id="flexRadioDefault1" value="1" 
                                        {{ isset($data->NcarIssuance) && $data->NcarIssuance == 1 ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="flexRadioDefault1">Yes</label>
                                    <input class="form-check-input" type="radio" name="NcarIssuance" id="flexRadioDefault2" value="2" 
                                        {{ isset($data->NcarIssuance) && $data->NcarIssuance != 1 ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="flexRadioDefault2">No</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 issue-check1" style="display: {{ $data->RecurringIssue == 1 ? 'block' : 'none' }};">
                            <div class="form-group issue-check">
                                <label for="name">Previous CCF No. (If Yes):</label>
                                <input type="text" class="form-control" id="PreviousCCF" name="PreviousCCF" placeholder="Enter CCF No.">
                            </div>
                        </div>
                        <div class="col-lg-12 mb-2">
                            <label class="display-5"><b>Quantification of Cost/s:</b></label>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Description:</label>
                                <input type="text" class="form-control" name="Description" id="Description" placeholder="Enter Description">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Currency (In PHP/ In US$/ In EUR):</label>
                                <input type="text" class="form-control" name="Currency" id="Currency" placeholder="Enter Currency">
                            </div>
                        </div> 
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-3" style="background: rgb(255 255 255 / 91%)">
                            <thead>
                                <tr>
                                    <th width="50%" colspan="1" class="text-center">COMPLAINT CATEGORY</th>
                                    <th width="50%" colspan="6" class="text-center">PRODUCT DETAILS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><b>1. Product Quality</b></td>
                                    <td>Please<br>Check</td>
                                    <td>Product Name</td>
                                    <td>S/C No.</td>
                                    <td>SO No.</td>
                                    <td>Quantity</td>
                                    <td>Lot No.</td>
                                </tr>
                                <tr>
                                    <td class="break-spaces">1.1 Physical Hazard (contamination of product by unspecified compound e.g. hard plastics, metal flakes, rust, etc.)</td>
                                    <td align="center"><input id="check-p1" name="check_p1" type="checkbox" @if($data->product_quality === null || $data->product_quality === '') unchecked @endif></td>
                                    <td><input type="text" class="form-control p1-input" name="Pn1" value="{{ optional($data->product_quality)->Pn1 }}" @if($data->product_quality == null) disabled @endif></td>
                                    <td><input type="text" class="form-control p1-input" name="ScNo1" value="{{ optional($data->product_quality)->ScNo1 }}" @if($data->product_quality == null) disabled @endif></td>
                                    <td><input type="text" class="form-control p1-input" name="SoNo1" value="{{ optional($data->product_quality)->SoNo1 }}" @if($data->product_quality == null) disabled @endif></td>
                                    <td><input type="text" class="form-control p1-input" name="Quantity1" value="{{ optional($data->product_quality)->Quantity1 }}" @if($data->product_quality == null) disabled @endif></td>
                                    <td><input type="text" class="form-control p1-input" name="LotNo1" value="{{ optional($data->product_quality)->LotNo1 }}" @if($data->product_quality == null) disabled @endif></td>
                                </tr>
                                <tr>
                                    <td class="break-spaces">1.2 Biological Hazard (e.g. high bacteria count, etc.)</td>
                                    <td align="center"><input id="check-p2" name="check_p2" type="checkbox" @if($data->product_quality != null || $data->product_quality === '') unchecked @endif></td>
                                    <td><input type="text" class="form-control p2-input" name="Pn2" value="{{ optional($data->product_quality)->Pn2 }}" @if($data->product_quality == null) disabled @endif></td>
                                    <td><input type="text" class="form-control p2-input" name="ScNo2" value="{{ optional($data->product_quality)->ScNo2 }}" @if($data->product_quality == null) disabled @endif></td>
                                    <td><input type="text" class="form-control p2-input" name="SoNo2" value="{{ optional($data->product_quality)->SoNo2 }}" @if($data->product_quality == null) disabled @endif></td>
                                    <td><input type="text" class="form-control p2-input" name="Quantity2" value="{{ optional($data->product_quality)->Quantity2 }}" @if($data->product_quality == null) disabled @endif></td>
                                    <td><input type="text" class="form-control p2-input" name="LotNo2" value="{{ optional($data->product_quality)->LotNo2 }}" @if($data->product_quality == null) disabled @endif></td>
                                </tr>
                                <tr>
                                    <td class="break-spaces">1.3 Chemical Hazard (e.g. high heavy metals content, etc.)</td>
                                    <td align="center"><input id="check-p3" name="check_p3" type="checkbox"  @if($data->product_quality != null || $data->product_quality === '') unchecked @endif></td>
                                    <td><input type="text" class="form-control p3-input" name="Pn3" value="{{ optional($data->product_quality)->Pn3 }}"  @if($data->product_quality == null) disabled @endif></td>
                                    <td><input type="text" class="form-control p3-input" name="ScNo3" value="{{ optional($data->product_quality)->ScNo3 }}"  @if($data->product_quality == null) disabled @endif></td>
                                    <td><input type="text" class="form-control p3-input" name="SoNo3" value="{{ optional($data->product_quality)->SoNo3 }}"  @if($data->product_quality == null) disabled @endif></td>
                                    <td><input type="text" class="form-control p3-input" name="Quantity3" value="{{ optional($data->product_quality)->Quantity3 }}"  @if($data->product_quality == null) disabled @endif></td>
                                    <td><input type="text" class="form-control p3-input" name="LotNo3" value="{{ optional($data->product_quality)->LotNo3 }}"  @if($data->product_quality == null) disabled @endif></td>
                                </tr>
                                <tr>
                                    <td class="break-spaces">1.4 Visual Defects (e.g. color change, particle size)</td>
                                    <td align="center"><input id="check-p4" name="check_p4" type="checkbox"  @if($data->product_quality != null || $data->product_quality === '') unchecked @endif></td>
                                    <td><input type="text" class="form-control p4-input" name="Pn4" value="{{ optional($data->product_quality)->Pn4 }}" @if($data->product_quality == null) disabled @endif></td>
                                    <td><input type="text" class="form-control p4-input" name="ScNo4" value="{{ optional($data->product_quality)->ScNo4 }}" @if($data->product_quality == null) disabled @endif></td>
                                    <td><input type="text" class="form-control p4-input" name="SoNo4" value="{{ optional($data->product_quality)->SoNo4 }}" @if($data->product_quality == null) disabled @endif></td>
                                    <td><input type="text" class="form-control p4-input" name="Quantity4" value="{{ optional($data->product_quality)->Quantity4 }}" @if($data->product_quality == null) disabled @endif></td>
                                    <td><input type="text" class="form-control p4-input" name="LotNo4" value="{{ optional($data->product_quality)->LotNo4 }}" @if($data->product_quality == null) disabled @endif></td>
                                </tr>
                                <tr>
                                    <td class="break-spaces">1.5 Application Problems (e.g. poor dispersion, poor distribution, poor binding property, high syneresis, etc.)</td>
                                    <td align="center"><input id="check-p5" name="check_p5" type="checkbox"  @if($data->product_quality != null || $data->product_quality === '') unchecked @endif></td>
                                    <td><input type="text" class="form-control p5-input" name="Pn5" value="{{ optional($data->product_quality)->Pn5 }}" @if($data->product_quality == null) disabled @endif></td>
                                    <td><input type="text" class="form-control p5-input" name="ScNo5" value="{{ optional($data->product_quality)->ScNo5 }}" @if($data->product_quality == null) disabled @endif></td>
                                    <td><input type="text" class="form-control p5-input" name="SoNo5" value="{{ optional($data->product_quality)->SoNo5 }}" @if($data->product_quality == null) disabled @endif></td>
                                    <td><input type="text" class="form-control p5-input" name="Quantity5" value="{{ optional($data->product_quality)->Quantity5 }}" @if($data->product_quality == null) disabled @endif></td>
                                    <td><input type="text" class="form-control p5-input" name="LotNo5" value="{{ optional($data->product_quality)->LotNo5 }}" @if($data->product_quality == null) disabled @endif></td>
                                </tr>
                                <tr>
                                    <td class="break-spaces">1.6 Physical/ Chemical Properties Out-of Specification (e.g. pH, gel strength, viscosity, syneresis and contamination with other ingredients)</td>
                                    <td align="center"><input id="check-p6" name="check_p6" type="checkbox"  @if($data->product_quality != null || $data->product_quality === '') unchecked @endif></td>
                                    <td><input type="text" class="form-control p6-input" name="Pn6" value="{{ optional($data->product_quality)->Pn6 }}"  @if($data->product_quality == null) disabled @endif ></td>
                                    <td><input type="text" class="form-control p6-input" name="ScNo6" value="{{ optional($data->product_quality)->ScNo6 }}"  @if($data->product_quality == null) disabled @endif ></td>
                                    <td><input type="text" class="form-control p6-input" name="SoNo6" value="{{ optional($data->product_quality)->SoNo6 }}"  @if($data->product_quality == null) disabled @endif ></td>
                                    <td><input type="text" class="form-control p6-input" name="Quantity6" value="{{ optional($data->product_quality)->Quantity6 }}"  @if($data->product_quality == null) disabled @endif ></td>
                                    <td><input type="text" class="form-control p6-input" name="LotNo6" value="{{ optional($data->product_quality)->LotNo6 }}"  @if($data->product_quality == null) disabled @endif ></td>
                                </tr>
                                <tr>
                                    <td colspan="7"><b>2. Packaging</b></td>
                                </tr>
                                <tr>
                                    <td class="break-spaces">2.1 Quantity (e.g. Short-packing, under-filled bags or box, over-filled container or box, etc.)</td>
                                    <td align="center"><input id="check-pack1" name="check-pack1" type="checkbox" @if($data->packaging != null || $data->packaging === '') unchecked @endif></td>
                                    <td><input type="text" class="form-control input-pack1" name="PackPn1" value="{{ optional($data->packaging)->PackPn1 }}" @if($data->packaging == null) disabled @endif></td>
                                    <td><input type="text" class="form-control input-pack1" name="PackScNo1" value="{{ optional($data->packaging)->PackScNo1 }}" @if($data->packaging == null) disabled @endif></td>
                                    <td><input type="text" class="form-control input-pack1" name="PackSoNo1" value="{{ optional($data->packaging)->PackSoNo1 }}" @if($data->packaging == null) disabled @endif></td>
                                    <td><input type="text" class="form-control input-pack1" name="PackQuantity1" value="{{ optional($data->packaging)->PackQuantity1 }}" @if($data->packaging == null) disabled @endif></td>
                                    <td><input type="text" class="form-control input-pack1" name="PackLotNo1" value="{{ optional($data->packaging)->PackLotNo1 }}" @if($data->packaging == null) disabled @endif></td>
                                </tr>
                                <tr>
                                    <td class="break-spaces">2.2 Packing (e.g. leakages, corrosion, etc.)</td>
                                    <td align="center"><input id="check-pack2" type="checkbox" @if($data->packaging != null || $data->packaging === '') unchecked @endif></td>
                                    <td><input type="text" class="form-control input-pack2" name="PackPn2" value="{{ optional($data->packaging)->PackPn2 }}" @if($data->packaging == null) disabled @endif></td>
                                    <td><input type="text" class="form-control input-pack2" name="PackScNo2" value="{{ optional($data->packaging)->PackScNo2 }}" @if($data->packaging == null) disabled @endif></td>
                                    <td><input type="text" class="form-control input-pack2" name="PackSoNo2" value="{{ optional($data->packaging)->PackSoNo2 }}" @if($data->packaging == null) disabled @endif></td>
                                    <td><input type="text" class="form-control input-pack2" name="PackQuantity2" value="{{ optional($data->packaging)->PackQuantity2 }}" @if($data->packaging == null) disabled @endif></td>
                                    <td><input type="text" class="form-control input-pack2" name="PackLotNo2" value="{{ optional($data->packaging)->PackLotNo2 }}" @if($data->packaging == null) disabled @endif></td>
                                </tr>
                                <tr>
                                    <td class="break-spaces">2.3 Labeling (e.g. wrong or defective label, unreadable, incorrect or incomplete text, etc.)</td>
                                    <td align="center"><input id="check-pack3" type="checkbox" @if($data->packaging != null || $data->packaging === '') unchecked @endif></td>
                                    <td><input type="text" class="form-control input-pack3" name="PackPn3" value="{{ optional($data->packaging)->PackPn3 }}" @if($data->packaging == null) disabled @endif></td>
                                    <td><input type="text" class="form-control input-pack3" name="PackScNo3" value="{{ optional($data->packaging)->PackScNo3 }}" @if($data->packaging == null) disabled @endif></td>
                                    <td><input type="text" class="form-control input-pack3" name="PackSoNo3" value="{{ optional($data->packaging)->PackSoNo3 }}" @if($data->packaging == null) disabled @endif></td>
                                    <td><input type="text" class="form-control input-pack3" name="PackQuantity3" value="{{ optional($data->packaging)->PackQuantity3 }}" @if($data->packaging == null) disabled @endif></td>
                                    <td><input type="text" class="form-control input-pack3" name="PackLotNo3" value="{{ optional($data->packaging)->PackLotNo3 }}" @if($data->packaging == null) disabled @endif></td>
                                </tr>
                                <tr>
                                    <td class="break-spaces">2.4 Packaging material (e.g. wrong packaging (bag, pallet, etc.) material, incorrect application of packaging instructions, inadequate quality of packaging material, etc.)</td>
                                    <td align="center"><input id="check-pack4" type="checkbox" @if($data->packaging != null || $data->packaging === '') unchecked @endif></td>
                                    <td><input type="text" class="form-control input-pack4" name="PackPn4" value="{{ optional($data->packaging)->PackPn4 }}" @if($data->packaging == null) disabled @endif></td>
                                    <td><input type="text" class="form-control input-pack4" name="PackScNo4" value="{{ optional($data->packaging)->PackScNo4 }}" @if($data->packaging == null) disabled @endif></td>
                                    <td><input type="text" class="form-control input-pack4" name="PackSoNo4" value="{{ optional($data->packaging)->PackSoNo4 }}" @if($data->packaging == null) disabled @endif></td>
                                    <td><input type="text" class="form-control input-pack4" name="PackQuantity4" value="{{ optional($data->packaging)->PackQuantity4 }}" @if($data->packaging == null) disabled @endif></td>
                                    <td><input type="text" class="form-control input-pack4" name="PackLotNo4" value="{{ optional($data->packaging)->PackLotNo4 }}" @if($data->packaging == null) disabled @endif></td>
                                </tr>
                                <tr>
                                    <td colspan="7"><b>3. Delivery and Handling</b></td>
                                </tr>
                                <tr>
                                    <td class="break-spaces">3.1 Product Handling (e.g. wrong product, pack size or quantity)</td>
                                    <td align="center"><input id="check-d1" name="check-d1" type="checkbox" @if($data->delivery_handling != null || $data->delivery_handling === '') unchecked @endif></td>
                                    <td><input type="text" class="form-control d1-input" name="DhPn1" value="{{ optional($data->delivery_handling)->DhPn1 }}" @if($data->delivery_handling == null) disabled @endif></td>
                                    <td><input type="text" class="form-control d1-input" name="DhScNo1" value="{{ optional($data->delivery_handling)->DhScNo1 }}" @if($data->delivery_handling == null) disabled @endif></td>
                                    <td><input type="text" class="form-control d1-input" name="DhSoNo1" value="{{ optional($data->delivery_handling)->DhSoNo1 }}" @if($data->delivery_handling == null) disabled @endif></td>
                                    <td><input type="text" class="form-control d1-input" name="DhQuantity1" value="{{ optional($data->delivery_handling)->DhQuantity1 }}" @if($data->delivery_handling == null) disabled @endif></td>
                                    <td><input type="text" class="form-control d1-input" name="DhLotNo1" value="{{ optional($data->delivery_handling)->DhLotNo1 }}" @if($data->delivery_handling == null) disabled @endif></td>
                                </tr>
                                <tr>
                                    <td class="break-spaces">3.2 Delayed Delivery (e.g. inadequate forwarder service, wrong delivery address, etc.)</td>
                                    <td align="center"><input id="check-d2" name="check-d2" type="checkbox" @if($data->delivery_handling != null || $data->delivery_handling === '') unchecked @endif></td>
                                    <td><input type="text" class="form-control d2-input" name="DhPn2" value="{{ optional($data->delivery_handling)->DhPn2 }}" @if($data->delivery_handling == null) disabled @endif></td>
                                    <td><input type="text" class="form-control d2-input" name="DhScNo2" value="{{ optional($data->delivery_handling)->DhScNo2 }}" @if($data->delivery_handling == null) disabled @endif></td>
                                    <td><input type="text" class="form-control d2-input" name="DhSoNo2" value="{{ optional($data->delivery_handling)->DhSoNo2 }}" @if($data->delivery_handling == null) disabled @endif></td>
                                    <td><input type="text" class="form-control d2-input" name="DhQuantity2" value="{{ optional($data->delivery_handling)->DhQuantity2 }}" @if($data->delivery_handling == null) disabled @endif></td>
                                    <td><input type="text" class="form-control d2-input" name="DhLotNo2" value="{{ optional($data->delivery_handling)->DhLotNo2 }}" @if($data->delivery_handling == null) disabled @endif></td>
                                </tr>
                                <tr>
                                    <td class="break-spaces">3.3 Product Damage during transit (e.g. leakages, corrosion, damaged label/box/carton/seal, etc.)</td>
                                    <td align="center"><input id="check-d3" name="check-d3" type="checkbox" @if($data->delivery_handling != null || $data->delivery_handling === '') unchecked @endif></td>
                                    <td><input type="text" class="form-control d3-input" name="DhPn3" value="{{ optional($data->delivery_handling)->DhPn3}}" @if($data->delivery_handling == null) disabled @endif></td>
                                    <td><input type="text" class="form-control d3-input" name="DhScNo3" value="{{ optional($data->delivery_handling)->DhScNo3}}" @if($data->delivery_handling == null) disabled @endif></td>
                                    <td><input type="text" class="form-control d3-input" name="DhSoNo3" value="{{ optional($data->delivery_handling)->DhSoNo3}}" @if($data->delivery_handling == null) disabled @endif></td>
                                    <td><input type="text" class="form-control d3-input" name="DhQuantity3" value="{{ optional($data->delivery_handling)->DhQuantity3}}" @if($data->delivery_handling == null) disabled @endif></td>
                                    <td><input type="text" class="form-control d3-input" name="DhLotNo3" value="{{ optional($data->delivery_handling)->DhLotNo3}}" @if($data->delivery_handling == null) disabled @endif></td>
                                </tr>
                                <tr>
                                    <td colspan="7"><b>4. Others</b></td>
                                </tr>
                                <tr>
                                    <td class="break-spaces">4.1 Quality of records or documents (e.g. insufficient, inadequate, missing, etc.)</td>
                                    <td align="center"><input id="check-o1" name="check-o1" type="checkbox" @if($data->others != null || $data->packaging === '') unchecked @endif></td>
                                    <td><input type="text" class="form-control o1-input" name="OthersPn1" value="{{ optional($data->others)->OthersPn1 }}" @if($data->others == null) disabled @endif></td>
                                    <td><input type="text" class="form-control o1-input" name="OthersScNo1" value="{{ optional($data->others)->OthersScNo1 }}" @if($data->others == null) disabled @endif></td>
                                    <td><input type="text" class="form-control o1-input" name="OthersSoNo1" value="{{ optional($data->others)->OthersSoNo1 }}" @if($data->others == null) disabled @endif></td>
                                    <td><input type="text" class="form-control o1-input" name="OthersQuantity1" value="{{ optional($data->others)->OthersQuantity1 }}" @if($data->others == null) disabled @endif></td>
                                    <td><input type="text" class="form-control o1-input" name="OthersLotNo1" value="{{ optional($data->others)->OthersLotNo1 }}" @if($data->others == null) disabled @endif></td>
                                </tr>
                                <tr>
                                    <td class="break-spaces">4.2 Poor customer Service (e.g., courtesy, professionalism, handling, responsiveness)</td>
                                    <td align="center"><input id="check-o2" name="check-o2" type="checkbox" @if($data->others != null || $data->packaging === '') unchecked @endif></td>
                                    <td><input type="text" class="form-control o2-input" name="OthersPn2" value="{{ optional($data->others)->OthersPn2 }}" @if($data->others == null) disabled @endif></td>
                                    <td><input type="text" class="form-control o2-input" name="OthersScNo2" value="{{ optional($data->others)->OthersScNo2 }}" @if($data->others == null) disabled @endif></td>
                                    <td><input type="text" class="form-control o2-input" name="OthersSoNo2" value="{{ optional($data->others)->OthersSoNo2 }}" @if($data->others == null) disabled @endif></td>
                                    <td><input type="text" class="form-control o2-input" name="OthersQuantity2" value="{{ optional($data->others)->OthersQuantity2 }}" @if($data->others == null) disabled @endif></td>
                                    <td><input type="text" class="form-control o2-input" name="OthersLotNo2" value="{{ optional($data->others)->OthersLotNo2 }}" @if($data->others == null) disabled @endif></td>
                                </tr>
                                <tr>
                                    <td class="break-spaces">4.3 Payment/ Invoice (e.g. wrong price/ product details)</td>
                                    <td align="center"><input id="check-o3" name="check-o3" type="checkbox" @if($data->others != null || $data->packaging === '') unchecked @endif></td>
                                    <td><input type="text" class="form-control o3-input" name="OthersPn3" value="{{ optional($data->others)->OthersPn3 }}" @if($data->others == null) disabled @endif></td>
                                    <td><input type="text" class="form-control o3-input" name="OthersScNo3" value="{{ optional($data->others)->OthersScNo3 }}" @if($data->others == null) disabled @endif></td>
                                    <td><input type="text" class="form-control o3-input" name="OthersSoNo3" value="{{ optional($data->others)->OthersSoNo3 }}" @if($data->others == null) disabled @endif></td>
                                    <td><input type="text" class="form-control o3-input" name="OthersQuantity3" value="{{ optional($data->others)->OthersQuantity3 }}" @if($data->others == null) disabled @endif></td>
                                    <td><input type="text" class="form-control o3-input" name="OthersLotNo3" value="{{ optional($data->others)->OthersLotNo3 }}" @if($data->others == null) disabled @endif></td>
                                </tr>
                                <tr>
                                    <td class="break-spaces">4.4 Other Issues (please specify)</td>
                                    <td align="center"><input id="check-o4" name="check-o4" type="checkbox" @if($data->others != null || $data->packaging === '') unchecked @endif></td>
                                    <td><input type="text" class="form-control o4-input" name="OthersPn4" value="{{ optional($data->others)->OthersPn4 }}" @if($data->others == null) disabled @endif></td>
                                    <td><input type="text" class="form-control o4-input" name="OthersScNo4" value="{{ optional($data->others)->OthersScNo4 }}" @if($data->others == null) disabled @endif></td>
                                    <td><input type="text" class="form-control o4-input" name="OthersSoNo4" value="{{ optional($data->others)->OthersSoNo4 }}" @if($data->others == null) disabled @endif></td>
                                    <td><input type="text" class="form-control o4-input" name="OthersQuantity4" value="{{ optional($data->others)->OthersQuantity4 }}" @if($data->others == null) disabled @endif></td>
                                    <td><input type="text" class="form-control o4-input" name="OthersLotNo4" value="{{ optional($data->others)->OthersLotNo4 }}" @if($data->others == null) disabled @endif></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 mb-2">
                            <label class="display-5"><b>Sales Remarks:</b></label>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Remarks</label>
                                <textarea class="form-control" name="SalesRemarks" id="SalesRemarks" rows="5" required placeholder="Enter Sales Remarks"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Attachments</label>
                                <input
                                type="file"
                                class="filepond"
                                name="Path[]"
                                id="Path3"
                                multiple
                                accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </div>
                
            </form>
        </div>
    </div>
</div>
<style>
    .is-invalid {
        border-color: red;
    }
</style>
<script src="https://unpkg.com/filepond/dist/filepond.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>

<script>
    $('#issue-radio2').on('change', function() {
        var selectedValue = $('input[name="RecurringIssue"]:checked').val(); 
        // if (selectedValue == "2") {
        //     $('.issue-check1').hide();
        // } else {
        //     $('.issue-check1').show();
        // }

        if (selectedValue == "1") {
            $('.issue-check1').show();
            $('#PreviousCCF').prop("required", true); // make required
        } else {
            $('.issue-check1').hide();
            $('#PreviousCCF').prop("required", false); // remove required
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        function toggleFields(checkboxId, inputClass) {
            const checkbox = document.getElementById(checkboxId);
            const inputs = document.querySelectorAll('.' + inputClass);

            function updateInputs() {
                inputs.forEach(input => {
                    input.disabled = !checkbox.checked;
                    input.required = checkbox.checked;
                });
            }

            checkbox.addEventListener('change', updateInputs);
            updateInputs(); // initial load
        }

        toggleFields('check-p1', 'p1-input');
        toggleFields('check-p2', 'p2-input');
        toggleFields('check-p3', 'p3-input');
        toggleFields('check-p4', 'p4-input');
        toggleFields('check-p5', 'p5-input');
        toggleFields('check-p6', 'p6-input');

        toggleFields('check-pack1', 'input-pack1');
        toggleFields('check-pack2', 'input-pack2');
        toggleFields('check-pack3', 'input-pack3');
        toggleFields('check-pack4', 'input-pack4');

        toggleFields('check-d1', 'd1-input');
        toggleFields('check-d2', 'd2-input');
        toggleFields('check-d3', 'd3-input');

        toggleFields('check-o1', 'o1-input');
        toggleFields('check-o2', 'o2-input');
        toggleFields('check-o3', 'o3-input');
        toggleFields('check-o4', 'o4-input');
    });

    document.addEventListener('DOMContentLoaded', function () {
        // Register plugins
        FilePond.registerPlugin(
            // FilePondPluginFileValidateType,
            FilePondPluginFileValidateSize,
            FilePondPluginImagePreview
        );

        // Create FilePond instance
        FilePond.create(document.querySelector('#Path3'), {
            allowMultiple: true,
            maxFileSize: '10MB',
            server: {
            process: {
                url: '{{ url("/upload-temp-remarks") }}',
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                // Return the serverId (filename) so FilePond stores it in Path[]
                onload: (response) => {
                try { return JSON.parse(response).id; } catch { return response; }
                }
            },
                revert: {
                    url: '{{ url("/upload-revert-remarks") }}',
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                }
            }
        });
    });
</script>
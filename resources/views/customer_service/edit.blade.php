<div class="modal fade" id="edit{{ $srf->Id }}" tabindex="-1" role="dialog" aria-labelledby="editSrf" aria-hidden="true">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editSrf">Edit Sample Request Form</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form method="POST" id="edit_sample_request{{ $srf->Id }}" enctype="multipart/form-data" action="{{ url('sample_request_cs/edit/' . $srf->Id) }}">
                <span></span>
                @csrf
                     <div class="form-header">
                        <span class="header-label">Request Details</span>
                        <hr class="form-divider">
                    </div>
                    <div class="row">    
                    <div class="col-md-6">
                    <div class="form-group">
                        <label for="DateRequested">Date Requested (MM/DD/YYYY Hour Min):</label>
                        <input type="datetime" class="form-control" value="{{ !empty($srf->DateRequested) ? date('m/d/y H:i', strtotime($srf->DateRequested)) : '' }}" placeholder="" readonly>
                    </div>
                    <div class="form-group">
                        <label for="DateRequired">Date Required (MM/DD/YYYY):</label>
                        <input type="date" class="form-control DateRequired{{ $srf->Id  }}" value="{{ old('DateRequired', !empty($srf->DateRequired) ? date('Y-m-d', strtotime($srf->DateRequired)) : '') }}" placeholder="" readonly>
                    </div>
                    <div class="form-group">
                        <label for="DateStarted">Date Started (MM/DD/YYYY):</label>
                        <input type="date" class="form-control"  value="{{ old('DateStarted', !empty($srf->DateStarted) ? date('Y-m-d', strtotime($srf->DateStarted)) : '') }}" 
                        placeholder="" 
                        readonly>
                    </div>
                    <div class="form-group">
                        <label>Primary Sales Person:</label>
                        <input type="text" class="form-control"  value="{{ $srf->primarySalesPerson ? $srf->primarySalesPerson->full_name : ($srf->primarySalesById ? $srf->primarySalesById->full_name : '') }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Secondary Sales Person:</label>
                        <input type="text" class="form-control"  value="{{ $srf->secondarySalesPerson ? $srf->secondarySalesPerson->full_name : ($srf->secondarySalesById ? $srf->secondarySalesById->full_name : '') }}" readonly>
                    </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>REF Code:</label>
                            <input type="text" class="form-control"   value="{{ $srf->RefCode == 1 ? 'RND' : ($srf->RefCode == 2 ? 'QCD' : $srf->RefCode) }}"  readonly>
                        </div>
                        <div class="form-group">
                            <label>Type:</label>
                            <input type="text" class="form-control"   value="{{ $srf->SrfType == 1 ? 'Regular' : ($srf->SrfType == 2 ? 'PSS' : ($srf->SrfType == 3 ? 'CSS' : $srf->SrfType)) }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="SoNumber">SO Number</label>
                            <input type="text" class="form-control" placeholder="Enter SO Number" value="{{ $srf->SoNumber}}" readonly>
                        </div>
                        <div class="form-group">
                            <label>Client:</label>
                            <input type="text" class="form-control" value="{{ optional($srf->client)->Name }}" readonly>
                        </div>
                        <div class="form-group">
                            <label>Contact:</label>
                            <input type="text" class="form-control" value="{{ optional($srf->clientContact)->ContactName }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="Remarks">Remarks (Internal)</label>
                            <textarea  class="form-control" placeholder="Enter Remarks" readonly>{{ ($srf->InternalRemarks) }}</textarea >
                        </div>
                    </div>
                </div>
                <div class="modal-footer"></div>
                <div class="form-header">
                    <span class="header-label">Files</span>
                    <hr class="form-divider">
                </div>
               <div class="files">
                @foreach ($srf->salesSrfFiles as $files)
                <div class="srf-file{{ $srf->Id }}">
                    <div class="form-group">
                        <label for="name"><b>Name</b></label>
                        <input type="text" name="name" class="form-control" value="{{ optional($files)->Name }}">
                    </div>
                </div>
                @endforeach
               </div>
                <div class="form-header">
                    <span class="header-label">Product</span>
                    <hr class="form-divider">
                </div>
                    <div class="productRows{{ $srf->Id }}">
                    @foreach ($srf->requestProducts as $index => $product )
                    <div class="create_srf_form{{ $srf->Id }}">
                    <div class="create_srf_forms{{ $product->id }} row"  data-row-index="{{ $index }}">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="hidden" name="product_id[]" value="{{ $product->id }}">
                                <label>Product Type:</label>
                                <input type="text" class="form-control"  value="{{ $product->ProductType == 1 ? 'Pure' : ($product->ProductType == 2 ? 'Blend' : $product->ProductType) }}"  readonly>
                            </div>
                            <div class="form-group">
                                <label>Application:</label>
                                <input type="text" class="form-control" value="{{optional($product->productApplicationsId)->Name }}"  readonly>
                            </div>
                            <div class="form-group">
                                <label>Product Code:</label>
                                <input type="text" class="form-control" value="{{ ($product->ProductCode) }}" readonly>
                            </div>                            
                            <div class="form-group">
                                <label for="ProductDescription">Product Description:</label>
                                <textarea class="form-control"placeholder="Enter Product Description" rows="8" readonly>{{ ($product->ProductDescription) }}</textarea>
                            </div>                            
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="NumberOfPackages">Number Of Packages</label>
                                <input type="number" class="form-control" value="{{  $product->NumberOfPackages }}" readonly>
                            </div>
                            <div class="row">
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label for="Quantity">Quantity</label>
                                        <input type="number" class="form-control" value="{{ ($product->Quantity) }}" readonly>
                                    </div>
                                </div>                                
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>Unit</label>
                                        <input type="text" class="form-control"   value="{{ $product->UnitOfMeasureId == 1 ? 'Grams' : ($product->UnitOfMeasureId == 2 ? 'Kilograms' : $product->UnitOfMeasureId) }}"  readonly>
                                    </div>
                                </div>                                
                            </div>
                            <div class="form-group">
                                <label for="Label">Label:</label>
                                <input type="text" class="form-control" value="{{ ($product->Label) }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="RpeNumber">RPE Number:</label>
                                <input type="text" class="form-control" value="{{ ($product->RpeNumber) }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="CrrNumber">CRR Number:</label>
                                <input type="text" class="form-control"  value="{{ ($product->CrrNumber) }}" readonly>
                            </div>                            
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="RemarksProduct">Remarks</label>
                                <textarea class="form-control" placeholder="Enter Remarks" readonly>{{ ( $product->Remarks) }}</textarea>
                            </div>
                        </div>                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Disposition:</label>
                                <input type="text" class="form-control"   value="{{ $product->Disposition == 1 ? 'No feedback' : ($product->UnitOfMeasureId == 10 ? 'Accepted' : ($srf->SrfType == 20 ? 'Rejected' : "")) }}"  readonly>
                            </div>                            
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Disposition Remarks</label>
                                <textarea class="form-control" name="DispositionRejectionDescription[]" placeholder="Enter Disposition Remarks" readonly>{{ ($product->DispositionRejectionDescription) }}</textarea>
                            </div>
                        </div>                        
                    </div>
                    </div>
                    @endforeach
                </div>
                <div class="modal-footer product-footer"></div>
                <div class="form-header">
                    <span class="header-label">Dispatch Details</span>
                    <hr class="form-divider">
                </div>
                <div class="row" >
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="Courier">Courier:</label>
                        <input type="text" class="form-control" name="Courier" placeholder="Enter Courier" value="{{ old('Courier', $srf->Courier) }}">
                    </div>
                    <div class="form-group">
                        <label for="AwbNumber">AWB Number:</label>
                        <input type="text" class="form-control" name="AwbNumber" placeholder="Enter AWB Number" value="{{ old('AwbNumber', $srf->AwbNumber) }}">
                    </div>
                    <div class="form-group">
                        <label for="DateDispatched">Date Dispatched (MM/DD/YYYY):</label>
                        <input type="date" class="form-control DateDispatched{{ $srf->Id  }}" name="DateDispatched" placeholder="Enter Date Dispatched" value="{{ old('DateDispatched', !empty($srf->DateDispatched) ? date('Y-m-d', strtotime($srf->DateDispatched)) : '') }}">
                    </div>
                    <div class="form-group">
                        <label>Date Sample Received (MM/DD/YYYY):</label>
                        <input type="date" class="form-control DateSampleReceived{{ $srf->Id }}" name="DateSampleReceived"  placeholder="Enter Sample Received" value="{{ old('DateSampleReceived', !empty($srf->DateSampleReceived) ? date('Y-m-d', strtotime($srf->DateSampleReceived)) : '') }}">
                    </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="DeliveryRemarks">Delivery Remarks</label>
                    <textarea class="form-control" name="DeliveryRemarks" placeholder="Enter Delivery Remarks">{{ old('DeliveryRemarks', $srf->DeliveryRemarks) }}</textarea>
                </div>
                <div class="form-group">
                    <label for="Note">Notes</label>
                    <textarea class="form-control" name="Note" placeholder="Enter Delivery Notes">{{ old('Note', $srf->Note) }}</textarea>
                </div>
            </div>
            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <input type="submit"  class="btn btn-success" value="Save">
                </div>
            </div>
            </form>
        </div>
      </div>
    </div>
  </div>
  <script>
  

</script>
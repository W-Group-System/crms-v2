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
            <form method="POST" id="edit_sample_request{{ $srf->Id }}" enctype="multipart/form-data" action="{{ url('sample_request/edit/' . $srf->Id) }}">
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
                        <input type="datetime" class="form-control" name="DateRequested" value="{{ !empty($srf->DateRequested) ? date('m/d/y H:i', strtotime($srf->DateRequested)) : '' }}" placeholder="" readonly>
                    </div>
                    <div class="form-group">
                        <label for="DateRequired">Date Required (MM/DD/YYYY):</label>
                        <input type="date" class="form-control DateRequired{{ $srf->Id  }}" name="DateRequired" value="{{ old('DateRequired', !empty($srf->DateRequired) ? date('Y-m-d', strtotime($srf->DateRequired)) : '') }}" placeholder="" >
                    </div>
                    <div class="form-group">
                        <label for="DateStarted">Date Started (MM/DD/YYYY):</label>
                        <input type="date" class="form-control" name="DateStarted"  value="{{ old('DateStarted', !empty($srf->DateStarted) ? date('Y-m-d', strtotime($srf->DateStarted)) : '') }}" 
                        placeholder="" 
                        readonly>
                    </div>
                    <div class="form-group">
                        <label>Primary Sales Person:</label>
                        <select class="form-control js-example-basic-single" name="PrimarySalesPerson" style="position: relative !important" title="Select PrimarySalesPerson" >
                            <option value="" disabled selected>Primary Sales Person</option>
                            @foreach ($primarySalesPersons as $salesPerson)
                                <option value="{{ $salesPerson->user_id }}" @if (old('PrimarySalesPerson', $srf->PrimarySalesPersonId) == $salesPerson->user_id) selected @endif>{{ $salesPerson->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Secondary Sales Person:</label>
                        <select class="form-control js-example-basic-single" name="SecondarySalesPerson" style="position: relative !important" title="Select SecondarySalesPerson" >
                            <option value="" disabled selected>Secondary Sales Person</option>
                            @foreach ($secondarySalesPersons as $salesPerson)
                                <option value="{{ $salesPerson->user_id }}"  @if (old('SecondarySalesPerson', $srf->SecondarySalesPersonId) == $salesPerson->user_id) selected @endif>{{ $salesPerson->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>REF Code:</label>
                            <select class="form-control js-example-basic-single editRefCode{{ $srf->Id }}" name="RefCode" id="" style="position: relative !important" title="Select Ref Code">
                                <option value="" disabled selected>Select REF Code</option>
                                <option value="1" {{ old('RefCode', $srf->RefCode) == "1" ? 'selected' : '' }}>RND</option>
                                <option value="2" {{ old('RefCode', $srf->RefCode) == "2" ? 'selected' : '' }}>QCD</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Type:</label>
                            <select class="form-control js-example-basic-single editSrfType{{ $srf->Id }}" name="SrfType" id="" style="position: relative !important" title="Select Type">
                                <option value="" disabled selected>Select Type</option>
                                <option value="1" {{ old('SrfType', $srf->SrfType) == "1" ? 'selected' : '' }}>Regular</option>
                                <option value="2" {{ old('SrfType', $srf->SrfType) == "2" ? 'selected' : '' }}>PSS</option>
                                <option value="3" {{ old('SrfType', $srf->SrfType) == "3" ? 'selected' : '' }}>CSS</option>
                            </select>
                        </div>
                        <div class="form-group editSoNumberGroup{{ $srf->Id }}" id="" style="display: none;">
                            <label for="SoNumber">SO Number</label>
                            <input type="text" class="form-control" name="SoNumber" placeholder="Enter SO Number" value="{{ old('SoNumber', $srf->SoNumber) }}">
                        </div>
                        <div class="form-group">
                            <label>Client:</label>
                            <select class="form-control js-example-basic-single editClientId{{ $srf->Id }}" name="ClientId" id="" style="position: relative !important" title="Select ClientId">
                                <option value="" disabled selected>Select Client</option>
                                @foreach ($clients as $client)
                                <option value="{{ $client->id }}" {{ old('ClientId', $srf->ClientId) == $client->id ? 'selected' : '' }} data-type="{{ $client->Type }}">{{ $client->Name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Contact:</label>
                            <select class="form-control js-example-basic-single editClientContactId{{ $srf->Id }}" name="ClientContactId" id="" style="position: relative !important" title="Select ClientContacId">
                                <option value="" disabled {{ old('ClientContactId', $srf->ContactId) ? '' : 'selected' }}>Select Contact</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="Remarks">Remarks (Internal)</label>
                            <textarea  class="form-control" name="Remarks" placeholder="Enter Remarks">{{ old('Remarks', $srf->InternalRemarks) }}</textarea>
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
                        <input type="text" name="name" id="fileName" class="form-control" value="{{ optional($files)->Name }}">
                    </div>
                    <div class="form-group">
                        <label for="srf_file"><b>Browse Files</b></label>
                        <input type="file" class="form-control file" name="srf_file">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="srf_id" value="{{$files->Id }}">
                    </div>
                </div>
                @endforeach
                {{-- <div class="form-group">
                    <button type="button" class="btn btn-sm btn-primary addSrfFile{{ $srf->Id }}"><i class="ti-plus"></i></button>
                    <button type="button" class="btn btn-sm btn-danger deleteRowBtn{{ $srf->Id }}" hidden><i class="ti-trash"></i></button>
                </div> --}}
               </div>
                <div class="form-header">
                    <span class="header-label">Product</span>
                    <hr class="form-divider">
                </div>
                    <div class="productRows{{ $srf->Id }}">
                    @foreach ($srf->requestProducts as $index => $product )
                    <div class="create_srf_form{{ $srf->Id }}">
                    <div class="create_srf_forms{{ $product->id }} row"  data-row-index="{{ $index }}">
                        <div class="col-lg-12">
                            <button type="button" class="btn btn-danger delete-product" data-id="{{ $product->id }}" style="float: right;">Delete</button>
                            <button type="button" class="btn btn-secondary duplicate-product" data-id="{{ $product->id }}" style="float: right; margin-right: 10px;">Duplicate</button>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="hidden" name="product_id[]" value="{{ $product->id }}">
                                <label>Product Type:</label>
                                <select class="form-control js-example-basic-single ProductType" name="ProductType[]" style="position: relative !important" title="Select Product Type">
                                    <option value="" disabled selected>Select Product Type</option>
                                    <option value="1" {{ old('ProductType.'.$index, $product->ProductType) == "1" ? 'selected' : '' }}>Pure</option>
                                    <option value="2" {{ old('ProductType.'.$index, $product->ProductType) == "2" ? 'selected' : '' }}>Blend</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Application:</label>
                                <select class="form-control js-example-basic-single" name="ApplicationId[]" style="position: relative !important" title="Select Application">
                                    <option value="" disabled>Select Application</option>
                                    @foreach ($productApplications as $application)
                                        <option value="{{ $application->id }}" 
                                            {{ old('ApplicationId.'.$index, $product->ApplicationId) == $application->id ? 'selected' : '' }}>
                                            {{ $application->Name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Product Code:</label>
                                <input type="text" class="form-control" name="ProductCode[]" value="{{ old('ProductCode.'.$index, $product->ProductCode) }}">
                                {{-- <select class="form-control js-example-basic-single" name="ProductCode[]" style="position: relative !important" title="Select Product Code" required>
                                    <option value="" disabled>Select Product Code</option>
                                    @foreach ($productCodes as $productCode)
                                        <option value="{{ $productCode->code }}" 
                                            {{ old('ProductCode.'.$index, $product->ProductCode) == $productCode->code ? 'selected' : '' }}>
                                            {{ $productCode->code }}
                                        </option>
                                    @endforeach
                                </select> --}}
                            </div>                            
                            <div class="form-group">
                                <label for="ProductDescription">Product Description:</label>
                                <textarea class="form-control" name="ProductDescription[]" placeholder="Enter Product Description" rows="8">{{ old('ProductDescription.'.$index, $product->ProductDescription) }}</textarea>
                            </div>                            
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="NumberOfPackages">Number Of Packages</label>
                                <input type="number" class="form-control" name="NumberOfPackages[]" value="{{ old('NumberOfPackages.'.$index, $product->NumberOfPackages) }}">
                            </div>
                            <div class="row">
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label for="Quantity">Quantity</label>
                                        <input type="number" class="form-control" name="Quantity[]" value="{{ old('Quantity.'.$index, $product->Quantity) }}">
                                    </div>
                                </div>                                
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>Unit</label>
                                        <select class="form-control js-example-basic-single" name="UnitOfMeasure[]" style="position: relative !important" title="Select Unit">
                                            <option value="1" {{ old('UnitOfMeasure.'.$index, $product->UnitOfMeasure) == "1" ? 'selected' : '' }}>Grams</option>
                                            <option value="2" {{ old('UnitOfMeasure.'.$index, $product->UnitOfMeasure) == "2" ? 'selected' : '' }}>Kilograms</option>
                                        </select>
                                    </div>
                                </div>                                
                            </div>
                            <div class="form-group">
                                <label for="Label">Label:</label>
                                <input type="text" class="form-control" name="Label[]" value="{{ old('Label.'.$index, $product->Label) }}">
                            </div>
                            <div class="form-group">
                                <label for="RpeNumber">RPE Number:</label>
                                <input type="text" class="form-control" name="RpeNumber[]" value="{{ old('RpeNumber.'.$index, $product->RpeNumber) }}">
                            </div>
                            <div class="form-group">
                                <label for="CrrNumber">CRR Number:</label>
                                <input type="text" class="form-control" name="CrrNumber[]" value="{{ old('CrrNumber.'.$index, $product->CrrNumber) }}">
                            </div>                            
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="RemarksProduct">Remarks</label>
                                <textarea class="form-control" name="RemarksProduct[]" placeholder="Enter Remarks">{{ old('RemarksProduct.'.$index, $product->Remarks) }}</textarea>
                            </div>
                        </div>                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Disposition:</label>
                                <select class="form-control js-example-basic-single" name="Disposition[]" style="position: relative !important" title="Select Disposition">
                                    <option value="0" {{ old('Disposition.'.$index, $product->Disposition) == "0" ? 'selected' : '' }}>Select Disposition</option>
                                    <option value="1" {{ old('Disposition.'.$index, $product->Disposition) == "1" ? 'selected' : '' }}>No feedback</option>
                                    <option value="10" {{ old('Disposition.'.$index, $product->Disposition) == "10" ? 'selected' : '' }}>Accepted</option>
                                    <option value="20" {{ old('Disposition.'.$index, $product->Disposition) == "20" ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>                            
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Disposition Remarks</label>
                                <textarea class="form-control" name="DispositionRejectionDescription[]" placeholder="Enter Disposition Remarks">{{ old('DispositionRejectionDescription.'.$index, $product->DispositionRejectionDescription) }}</textarea>
                            </div>
                        </div>                        
                    </div>
                    </div>
                    @endforeach
                </div>
                <div class="col-lg-12 row">
                    <button type="button" class="btn btn-primary addSrfProductRowBtn{{ $srf->Id }}"  style="float: left; margin:5px;"><i class="ti ti-plus"></i></button> 
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
    $(document).ready(function() {
    function loadClientContacts(clientId, selectedContactId) {
        var $contactSelect = $('.editClientContactId{{ $srf->Id }}');
        $contactSelect.empty().append('<option value="" disabled>Select Contact</option>');
        if (clientId) {
        $.ajax({
            url: '{{ url("sample_contacts-by-client-f") }}/' + clientId,
            type: "GET",
            dataType: "json",
            success: function(data) {
                if ($.isEmptyObject(data)) {
                    $contactSelect.append('<option value="" disabled>Select Contact</option>'); 
                } else {
                    $.each(data, function(key, value) {
                        var isSelected = (key == selectedContactId) ? ' selected' : '';
                        $contactSelect.append('<option value="' + key + '"' + isSelected + '>' + value + '</option>');
                    });
                    if (selectedContactId) {
                        $contactSelect.val(selectedContactId).trigger('change');
                    }
                }
            },
            error: function(xhr, status, error) {
                console.log('AJAX error:', error); 
            }
        });
    } else {
        $contactSelect.empty().append('<option value="" disabled>Select Contact</option>'); 
    }
}

    function loadSrfTypes(refCode, selectedType) {
    var $srfTypeSelect = $('.editSrfType{{ $srf->Id }}');
    $srfTypeSelect.empty().append('<option value="" disabled>Select Type</option>');

    if (refCode === '1') {
        $srfTypeSelect.append('<option value="1">Regular</option>');
    } else if (refCode === '2') {
        $srfTypeSelect.append('<option value="1">Regular</option>');
        $srfTypeSelect.append('<option value="2">PSS</option>');
        $srfTypeSelect.append('<option value="3">CSS</option>');
    }

    if (selectedType) {
        $srfTypeSelect.val(selectedType);
    }

    checkSoNumberVisibility();
}

function checkSoNumberVisibility() {
    var selectedType = $('.editSrfType{{ $srf->Id }}').val();
    if (selectedType === '2' || selectedType === '3') {
        $('.editSoNumberGroup{{ $srf->Id }}').show();
    } else {
        $('.editSoNumberGroup{{ $srf->Id }}').hide();
    }
}

$('.editClientId{{ $srf->Id }}').on('change', function() {
    var editclientId = $(this).val();
    loadClientContacts(editclientId);
});

$('.editRefCode{{ $srf->Id }}').on('change', function() {
    var refCode = $(this).val();
    loadSrfTypes(refCode, $('.editSrfType{{ $srf->Id }}').val());
});

$('.editSrfType{{ $srf->Id }}').on('change', function() {
    checkSoNumberVisibility();
});

$(document).ready(function() {
    var initialRefCode = '{{ old('RefCode', $srf->RefCode) }}';
    var initialSrfType = '{{ old('SrfType', $srf->SrfType) }}';
    var initialClientId = '{{ old('ClientId', $srf->ClientId) }}';
    var initialClientContactId = '{{ old('ClientContactId', $srf->ContactId) }}';

    $('.editRefCode{{ $srf->Id }}').val(initialRefCode).trigger('change');
    loadSrfTypes(initialRefCode, initialSrfType);
    $('.editClientId{{ $srf->Id }}').val(initialClientId).trigger('change');
    loadClientContacts(initialClientId, initialClientContactId);
    $('.editClientId{{ $srf->Id }}').on('change', function() {
        var clientId = $(this).val();
        loadClientContacts(clientId);
    });
    $('.editRefCode{{ $srf->Id }}').on('change', function() {
        var refCode = $(this).val();
        loadSrfTypes(refCode, $('.editSrfType{{ $srf->Id }}').val());
    });

    $('.editSrfType{{ $srf->Id }}').on('change', function() {
        checkSoNumberVisibility();
    });
});


    function addProductRow() {
       var newProductForm = `
                       <div class="productRows{{ $srf->Id }} row">
                          <div class="col-lg-12">
                                <button type="button" class="btn btn-danger editDeleteSrfBtn" style="float: right;">Delete Row</button>
                            </div>
                          <div class="col-md-6">
                <div class="form-group">
                    <label>Product Type:</label>
                    <select class="form-control js-example-basic-single ProductType" name="ProductType[]" style="position: relative !important" title="Select Product Type">
                        <option value="" disabled {{ old('ProductType') === null ? 'selected' : '' }}>Select Product Type</option>
                        <option value="1" {{ old('ProductType') == '1' ? 'selected' : '' }}>Pure</option>
                        <option value="2" {{ old('ProductType') == '2' ? 'selected' : '' }}>Blend</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Application:</label>
                    <select class="form-control js-example-basic-single ApplicationId" name="ApplicationId[]" style="position: relative !important" title="Select Application" required>
                        <option value="" disabled selected>Select Application</option>
                        @foreach ($productApplications as $application)
                            <option value="{{ $application->id }}" {{ in_array($application->id, old('ApplicationId', [])) ? 'selected' : '' }}>{{ $application->Name }}</option>
                        @endforeach
                    </select>
                </div>
            <div class="form-group">
                <label>Product Code:</label>
                <input type="text" class="form-control" name="ProductCode[]" >
            </div>
            @foreach(old('ProductDescription', ['']) as $index => $description)
                <div class="form-group">
                    <label for="ProductDescription">Product Description:</label>
                    <textarea class="form-control" name="ProductDescription[]" placeholder="Enter Product Description" rows="8">{{ $description }}</textarea>
                </div>
            @endforeach
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="NumberOfPackages">Number Of Packages</label>
                @if(old('NumberOfPackages'))
                    @foreach(old('NumberOfPackages') as $index => $numberOfPackage)
                        <input type="number" class="form-control" name="NumberOfPackages[]" value="{{ $numberOfPackage }}">
                    @endforeach
                @else
                    <input type="number" class="form-control" name="NumberOfPackages[]">
                @endif
            </div>
            
            <div class="row">
                <div class="col-md-7">
                    @foreach(old('Quantity', ['']) as $index => $quantity)
                    <div class="form-group">
                        <label for="Quantity">Quantity</label>
                        <input type="number" class="form-control" name="Quantity[]" value="{{ $quantity }}">
                    </div>
                    @endforeach
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label>Unit</label>
                        <select class="form-control js-example-basic-single UnitOfMeasure" name="UnitOfMeasure[]" style="position: relative !important" title="Select Unit">
                            <option value="1" {{ old('UnitOfMeasure') == '1' ? 'selected' : '' }}>Grams</option>
                            <option value="2" {{ old('UnitOfMeasure') == '2' ? 'selected' : '' }}>Kilograms</option>
                        </select>
                    </div>
                </div>
            </div>
            @foreach(old('Label', ['']) as $index => $label)
                <div class="form-group" >
                    <label for="Label">Label:</label>
                    <input type="text" class="form-control" name="Label[]" value="{{ $label }}">
                </div>
            @endforeach
            @foreach(old('RpeNumber', ['']) as $index => $rpe)
                <div class="form-group" >
                    <label for="RpeNumber">RPE Number:</label>
                    <input type="text" class="form-control" name="RpeNumber[]" value="{{ $rpe }}">
                </div>
            @endforeach
            @foreach(old('CrrNumber', ['']) as $index => $crr)
                <div class="form-group" >
                    <label for="CrrNumber">CRR Number:</label>
                    <input type="text" class="form-control" name="CrrNumber[]" value="{{ $crr }}">
                </div>
            @endforeach
        </div>
        <div class="col-md-12">
            @foreach(old('RemarksProduct', ['']) as $index => $remarksproduct)
                <div class="form-group">
                    <label for="RemarksProduct">Remarks</label>
                    <textarea class="form-control" name="RemarksProduct[]" placeholder="Enter Remarks">{{ $remarksproduct }}</textarea>
                </div>
            @endforeach
        </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Disposition:</label>
                    <select class="form-control js-example-basic-single Disposition" name="Disposition[]" style="position: relative !important" title="Select Disposition">
                        <option value="0">Select Disposition</option>
                        <option value="1">No feedback</option>
                        <option value="10">Accepted</option>
                        <option value="20">Rejected</option>
                    </select>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>Disposition Remarks</label>
                    <textarea class="form-control" name="DispositionRejectionDescription[]" placeholder="Enter DispositionRemarks"></textarea>
                </div>
            </div>
                       </div>`;
                       $('.productRows{{ $srf->Id }}').append(newProductForm);
                       $('.ProductType, .ApplicationId, .Disposition, .UnitOfMeasure', '.ProductCode').select2();
   }

 $(document).off('click', '.addSrfProductRowBtn{{ $srf->Id }}').on('click', '.addSrfProductRowBtn{{ $srf->Id }}', function() {
    addProductRow();
});

   $(document).on('click', '.editDeleteSrfBtn', function() {
        $(this).closest('.create_srf_form{{ $srf->Id }}').remove();
    });
    

});
$(document).ready(function() {
    $(document).off('click', '.duplicate-product');
    $(document).on('click', '.duplicate-product', function() {
        var productId = $(this).data('id');
        var row = $(this).closest('.create_srf_forms' + productId);
        
        var newRow = row.clone();

        newRow.find('input[name="product_id[]"]').val('');
        
        var newIndex = $('.create_srf_forms' + productId).length;
        
        newRow.find('input, select, textarea').each(function() {
            var $this = $(this);
            var name = $this.attr('name');
            if (name) {
                $(this).attr('name', name.replace(/\[\d+\]/, '[' + newIndex + ']'));
            }
            var id = $(this).attr('id');
            if (id) {
                $(this).attr('id', id.replace(/\d+/, newIndex));
            }
        });

        newRow.find('select').removeClass('select2-hidden-accessible').next('.select2-container').remove();

        newRow.find('.js-example-basic-single').select2();

        row.after(newRow);

        $(this).prop('disabled', false);
    });

    $(document).off('click', '.delete-product');
    $(document).on('click', '.delete-product', function() {
        var productId = $(this).data('id'); 
        var row = $(this).closest('.create_srf_forms' + productId);
        var deleteButton = $(this);
        deleteButton.prop('disabled', true);

        $.ajax({
            url: "{{ url('delete-srf-product') }}/" + productId,
            type: 'DELETE',
            data: {
                '_token': '{{ csrf_token() }}', 
            },
            success: function(response) {
                if (response.success) {
                    row.remove();
                } else {
                    alert('Failed to delete the product.');
                }
            },
            complete: function() {
                deleteButton.prop('disabled', false);
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    var dueDateInput = document.querySelector('.DateRequired{{ $srf->Id  }}');
    var dispatchInput = document.querySelector('.DateDispatched{{ $srf->Id  }}');
    var sampleReceivedInput = document.querySelector('.DateSampleReceived{{ $srf->Id  }}');
    
    var storedDate = '{{ !empty($srf->DateRequired) ? date('Y-m-d', strtotime($srf->DateRequired)) : '' }}';
    var storedDispatched = '{{ !empty($srf->DateDispatched) ? date('Y-m-d', strtotime($srf->DateDispatched)) : '' }}';
    var storedSampleReceived = '{{ !empty($srf->DateSampleReceived) ? date('Y-m-d', strtotime($srf->DateSampleReceived)) : '' }}';
    var today = new Date().toISOString().split('T')[0];

    if (dueDateInput) {
        if (storedDate) {
            dueDateInput.setAttribute('min', storedDate);
        } else {
            dueDateInput.setAttribute('min', today);
        }
    }
    
    if (dispatchInput) {
        if (storedDispatched) {
            dispatchInput.setAttribute('min', storedDispatched);
        } else {
            dispatchInput.setAttribute('min', today);
        }
    }
    
    if (sampleReceivedInput) {
        if (storedSampleReceived) {
            sampleReceivedInput.setAttribute('min', storedSampleReceived);
        } else {
            sampleReceivedInput.setAttribute('min', today);
        }
    }
});

$(document).ready(function() {
    function addSrfFileForm() {
        var newProductForm = `
        <div class="srf-file{{ $srf->Id }}">
            <div class="form-group">
                <label for="name"><b>Name</b></label>
                <input type="text" name="name[]" class="form-control" placeholder="">
            </div>
            <div class="form-group">
                <label for="srf_file"><b>Browse Files</b></label>
                <input type="file" class="form-control" name="srf_file[]" multiple>
            </div>
            <div class="form-group">
                <input type="hidden" class="form-control" name="srf_id" value="{{ $srf->Id }}">
            </div>
            <div class="form-group">
                <button type="button" class="btn btn-sm btn-primary addSrfFile{{ $srf->Id }}"><i class="ti-plus"></i></button>
                <button type="button" class="btn btn-sm btn-danger deleteRowBtn{{ $srf->Id }}"><i class="ti-trash"></i></button>
            </div>
        </div>`;
    
        $('.srf-file{{ $srf->Id }}').last().find('.addSrfFile{{ $srf->Id }}').hide();
        $('.srf-file{{ $srf->Id }}').last().find('.deleteRowBtn{{ $srf->Id }}').show();
        $('.srf-file{{ $srf->Id }}').last().after(newProductForm);
    }

    $(document).on('click', '.addSrfFile{{ $srf->Id }}', function() {
        addSrfFileForm();
    });

    $(document).on('click', '.deleteRowBtn{{ $srf->Id }}', function() {
        var currentRow = $(this).closest('.srf-file{{ $srf->Id }}');
        
        if ($('.srf-file{{ $srf->Id }}').length > 1) {
            if ($('.srf-file{{ $srf->Id }}').last().is(currentRow)) {
                currentRow.prev().find('.addSrfFile{{ $srf->Id }}').show();
                currentRow.prev().find('.deleteRowBtn{{ $srf->Id }}').show();
            }
            currentRow.remove();
        }
        
        if ($('.srf-file{{ $srf->Id }}').length === 1) {
            $('.srf-file{{ $srf->Id }}').find('.addSrfFile{{ $srf->Id }}').show();
            $('.srf-file{{ $srf->Id }}').find('.deleteRowBtn{{ $srf->Id }}').hide();
        }
    });

    $(document).on('change', 'input[type="file"]', function() {
        var files = $(this)[0].files;
        var filenames = $.map(files, function(val) { return val.name; }).join(', ');
        $(this).closest('.srf-file').find('input[name="name[]"]').val(filenames);
    });

    if ($('.srf-file{{ $srf->Id }}').length === 1) {
        $('.srf-file{{ $srf->Id }}').find('.deleteRowBtn{{ $srf->Id }}').hide();
    }
});

</script>
<div class="modal fade" id="salesEdit{{$sampleRequest->Id}}" tabindex="-1" role="dialog" aria-labelledby="editSrf" aria-hidden="true">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editSrf">Edit Sample Request Form</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form method="POST" id="edit_sample_request{{$sampleRequest->Id }}" enctype="multipart/form-data" action="{{ url('sample_request/edit/' .$sampleRequest->Id) }}">
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
                        <input type="datetime" class="form-control" name="DateRequested" value="{{ !empty( $sampleRequest->DateRequested) ? date('m/d/y H:i', strtotime( $sampleRequest->DateRequested)) : '' }}" placeholder="" readonly>
                    </div>
                    <div class="form-group">
                        <label for="DateRequired">Date Required (MM/DD/YYYY):</label>
                        <input type="date" class="form-control DateRequired{{$sampleRequest->Id  }}" name="DateRequired" value="{{ old('DateRequired', !empty( $sampleRequest->DateRequired) ? date('Y-m-d', strtotime( $sampleRequest->DateRequired)) : '') }}" placeholder="" >
                    </div>
                    <div class="form-group">
                        <label for="DateStarted">Date Started (MM/DD/YYYY):</label>
                        <input type="date" class="form-control" name="DateStarted"  value="{{ old('DateStarted', !empty( $sampleRequest->DateStarted) ? date('Y-m-d', strtotime( $sampleRequest->DateStarted)) : '') }}" 
                        placeholder="" 
                        readonly>
                    </div>
                    <div class="form-group">
                        <label>Primary Sales Person</label>
                                @if(auth()->user()->role->name == "Staff L1")
                                <input type="hidden" name="PrimarySalesPerson" value="{{auth()->user()->id}}">
                                <input type="text" class="form-control" value="{{auth()->user()->full_name}}" readonly>
                                @elseif (auth()->user()->role->name == "Staff L2" || auth()->user()->role->name == "Department Admin")
                                @php
                                    $subordinates = getUserApprover(auth()->user()->getSalesApprover);
                                @endphp
                                <select class="form-control js-example-basic-single" name="PrimarySalesPerson" style="position: relative !important" title="Select Sales Person">
                                    <option value="" disabled selected>Select Sales Person</option>
                                    @foreach($subordinates as $user)
                                        <option value="{{ $user->id }}" @if($user->user_id == $sampleRequest->PrimarySalesPersonId || $user->id == $sampleRequest->PrimarySalesPersonId) selected @endif>{{ $user->full_name }}</option>
                                    @endforeach
                                </select>
                                @endif
                    </div>
                    <div class="form-group">
                        <label>Secondary Sales Person:</label>
                        <select class="form-control js-example-basic-single" name="SecondarySalesPerson" style="position: relative !important" title="Select SecondarySalesPerson" >
                            <option value="" disabled selected>Secondary Sales Person</option>
                            @foreach ($users as $salesPerson)
                                <option value="{{ $salesPerson->id }}" @if($salesPerson->user_id == $sampleRequest->SecondarySalesPersonId || $salesPerson->id == $sampleRequest->SecondarySalesPersonId) selected @endif>{{ $salesPerson->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Upload Files</label>
                        <input type="file" name="SalesSrfFile[]" class="form-control" multiple>
                    </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>REF Code:</label>
                            <select class="form-control js-example-basic-single editRefCode{{$sampleRequest->Id }}" name="RefCode" id="" style="position: relative !important" title="Select Ref Code">
                                <option value="" disabled selected>Select REF Code</option>
                                <option value="1" {{ old('RefCode',  $sampleRequest->RefCode) == "1" ? 'selected' : '' }}>RND</option>
                                <option value="2" {{ old('RefCode',  $sampleRequest->RefCode) == "2" ? 'selected' : '' }}>QCD</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Type:</label>
                            <select class="form-control js-example-basic-single editSrfType{{$sampleRequest->Id }}" name="SrfType" id="" style="position: relative !important" title="Select Type">
                                <option value="" disabled selected>Select Type</option>
                                <option value="1" {{ old('SrfType',  $sampleRequest->SrfType) == "1" ? 'selected' : '' }}>Regular</option>
                                <option value="2" {{ old('SrfType',  $sampleRequest->SrfType) == "2" ? 'selected' : '' }}>PSS</option>
                                <option value="3" {{ old('SrfType',  $sampleRequest->SrfType) == "3" ? 'selected' : '' }}>CSS</option>
                            </select>
                        </div>
                        <div class="form-group editSoNumberGroup{{$sampleRequest->Id }}" id="" style="display: none;">
                            <label for="SoNumber">SO Number</label>
                            <input type="text" class="form-control" name="SoNumber" placeholder="Enter SO Number" value="{{ old('SoNumber',  $sampleRequest->SoNumber) }}">
                        </div>
                        <div class="form-group">
                            <label>Client:</label>
                            <select class="form-control js-example-basic-single editClientId{{$sampleRequest->Id }}" name="ClientId" id="" style="position: relative !important" title="Select ClientId" required>
                                <option value="" disabled selected>Select Client</option>
                                @foreach ($clients as $client)
                                <option value="{{ $client->id }}" {{ old('ClientId',  $sampleRequest->ClientId) == $client->id ? 'selected' : '' }} data-type="{{ $client->Type }}">{{ $client->Name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Contact:</label>
                            <select class="form-control js-example-basic-single editClientContactId{{$sampleRequest->Id }}" name="ClientContactId" id="" style="position: relative !important" title="Select ClientContacId">
                                <option value="" disabled {{ old('ClientContactId',  $sampleRequest->ContactId) ? '' : 'selected' }}>Select Contact</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="Remarks">Remarks (Internal)</label>
                            <textarea  class="form-control" name="Remarks" placeholder="Enter Remarks">{{ old('Remarks',  $sampleRequest->InternalRemarks) }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer"></div>
                <div class="form-header">
                    <span class="header-label">Product</span>
                    <hr class="form-divider">
                </div>
                    <div class="productRows{{$sampleRequest->Id }}">
                    @foreach ( $sampleRequest->requestProducts as $index => $product )
                    <div class="create_srf_form{{$sampleRequest->Id }}">
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
                    <button type="button" class="btn btn-primary addSrfProductRowBtn{{$sampleRequest->Id }}"  style="float: left; margin:5px;"><i class="ti ti-plus"></i></button> 
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
                        <input type="text" class="form-control" name="Courier" placeholder="Enter Courier" value="{{ old('Courier',  $sampleRequest->Courier) }}">
                    </div>
                    <div class="form-group">
                        <label for="AwbNumber">AWB Number:</label>
                        <input type="text" class="form-control" name="AwbNumber" placeholder="Enter AWB Number" value="{{ old('AwbNumber',  $sampleRequest->AwbNumber) }}">
                    </div>
                    <div class="form-group">
                        <label for="DateDispatched">Date Dispatched (MM/DD/YYYY):</label>
                        <input type="date" class="form-control DateDispatched{{$sampleRequest->Id  }}" name="DateDispatched" placeholder="Enter Date Dispatched" value="{{ old('DateDispatched', !empty( $sampleRequest->DateDispatched) ? date('Y-m-d', strtotime( $sampleRequest->DateDispatched)) : '') }}">
                    </div>
                    <div class="form-group">
                        <label>Date Sample Received (MM/DD/YYYY):</label>
                        <input type="date" class="form-control DateSampleReceived{{$sampleRequest->Id }}" name="DateSampleReceived"  placeholder="Enter Sample Received" value="{{ old('DateSampleReceived', !empty( $sampleRequest->DateSampleReceived) ? date('Y-m-d', strtotime( $sampleRequest->DateSampleReceived)) : '') }}">
                    </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="DeliveryRemarks">Delivery Remarks</label>
                    <textarea class="form-control" name="DeliveryRemarks" placeholder="Enter Delivery Remarks">{{ old('DeliveryRemarks',  $sampleRequest->DeliveryRemarks) }}</textarea>
                </div>
                <div class="form-group">
                    <label for="Note">Notes</label>
                    <textarea class="form-control" name="Note" placeholder="Enter Delivery Notes">{{ old('Note',  $sampleRequest->Note) }}</textarea>
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
        var $contactSelect = $('.editClientContactId{{$sampleRequest->Id }}');
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
    var  $sampleRequestTypeSelect = $('.editSrfType{{$sampleRequest->Id }}');
     $sampleRequestTypeSelect.empty().append('<option value="" disabled>Select Type</option>');

    if (refCode === '1') {
         $sampleRequestTypeSelect.append('<option value="1">Regular</option>');
    } else if (refCode === '2') {
         $sampleRequestTypeSelect.append('<option value="1">Regular</option>');
         $sampleRequestTypeSelect.append('<option value="2">PSS</option>');
         $sampleRequestTypeSelect.append('<option value="3">CSS</option>');
    }

    if (selectedType) {
         $sampleRequestTypeSelect.val(selectedType);
    }

    checkSoNumberVisibility();
}

function checkSoNumberVisibility() {
    var selectedType = $('.editSrfType{{$sampleRequest->Id }}').val();
    if (selectedType === '2' || selectedType === '3') {
        $('.editSoNumberGroup{{$sampleRequest->Id }}').show();
    } else {
        $('.editSoNumberGroup{{$sampleRequest->Id }}').hide();
    }
}

$('.editClientId{{$sampleRequest->Id }}').on('change', function() {
    var editclientId = $(this).val();
    loadClientContacts(editclientId);
});

$('.editRefCode{{$sampleRequest->Id }}').on('change', function() {
    var refCode = $(this).val();
    loadSrfTypes(refCode, $('.editSrfType{{$sampleRequest->Id }}').val());
});

$('.editSrfType{{$sampleRequest->Id }}').on('change', function() {
    checkSoNumberVisibility();
});

$(document).ready(function() {
    var initialRefCode = '{{ old('RefCode',  $sampleRequest->RefCode) }}';
    var initialSrfType = '{{ old('SrfType',  $sampleRequest->SrfType) }}';
    var initialClientId = '{{ old('ClientId',  $sampleRequest->ClientId) }}';
    var initialClientContactId = '{{ old('ClientContactId',  $sampleRequest->ContactId) }}';

    $('.editRefCode{{$sampleRequest->Id }}').val(initialRefCode).trigger('change');
    loadSrfTypes(initialRefCode, initialSrfType);
    $('.editClientId{{$sampleRequest->Id }}').val(initialClientId).trigger('change');
    loadClientContacts(initialClientId, initialClientContactId);
    $('.editClientId{{$sampleRequest->Id }}').on('change', function() {
        var clientId = $(this).val();
        loadClientContacts(clientId);
    });
    $('.editRefCode{{$sampleRequest->Id }}').on('change', function() {
        var refCode = $(this).val();
        loadSrfTypes(refCode, $('.editSrfType{{$sampleRequest->Id }}').val());
    });

    $('.editSrfType{{$sampleRequest->Id }}').on('change', function() {
        checkSoNumberVisibility();
    });
});


    function addProductRow() {
       var newProductForm = `
                       <div class="productRows{{$sampleRequest->Id }} row">
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
                       $('.productRows{{$sampleRequest->Id }}').append(newProductForm);
                       $('.ProductType, .ApplicationId, .Disposition, .UnitOfMeasure', '.ProductCode').select2();
   }

 $(document).off('click', '.addSrfProductRowBtn{{$sampleRequest->Id }}').on('click', '.addSrfProductRowBtn{{$sampleRequest->Id }}', function() {
    addProductRow();
});

   $(document).on('click', '.editDeleteSrfBtn', function() {
        $(this).closest('.create_srf_form{{$sampleRequest->Id }}').remove();
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
    var dueDateInput = document.querySelector('.DateRequired{{$sampleRequest->Id  }}');
    var dispatchInput = document.querySelector('.DateDispatched{{$sampleRequest->Id  }}');
    var sampleReceivedInput = document.querySelector('.DateSampleReceived{{$sampleRequest->Id  }}');
    
    var storedDate = '{{ !empty( $sampleRequest->DateRequired) ? date('Y-m-d', strtotime( $sampleRequest->DateRequired)) : '' }}';
    var storedDispatched = '{{ !empty( $sampleRequest->DateDispatched) ? date('Y-m-d', strtotime( $sampleRequest->DateDispatched)) : '' }}';
    var storedSampleReceived = '{{ !empty( $sampleRequest->DateSampleReceived) ? date('Y-m-d', strtotime( $sampleRequest->DateSampleReceived)) : '' }}';
    var today = new Date().toISOString().split('T')[0];

    if (dueDateInput) {
        if (storedDate) {
            dueDateInput.setAttribute('min', storedDate);
        } else {
            dueDateInput.setAttribute('min', today);
        }
    }
    
    // if (dispatchInput) {
    //     if (storedDispatched) {
    //         dispatchInput.setAttribute('min', storedDispatched);
    //     } else {
    //         dispatchInput.setAttribute('min', today);
    //     }
    // }
    
    // if (sampleReceivedInput) {
    //     if (storedSampleReceived) {
    //         sampleReceivedInput.setAttribute('min', storedSampleReceived);
    //     } else {
    //         sampleReceivedInput.setAttribute('min', today);
    //     }
    // }
});

</script>
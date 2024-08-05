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
            <form method="POST" enctype="multipart/form-data" action="{{ url('sample_request/edit/' . $srf->Id) }}">
                <span id="form_result"></span>
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
                        <input type="date" class="form-control" name="DateRequired" value="{{ !empty($srf->DateRequired) ? date('Y-m-d', strtotime($srf->DateRequired)) : '' }}" placeholder="" >
                    </div>
                    <div class="form-group">
                        <label for="DateStarted">Date Started (MM/DD/YYYY):</label>
                        <input type="date" class="form-control" name="DateStarted" value="{{ !empty($srf->DateStarted) ? date('Y-m-d', strtotime($srf->DateStarted)) : '' }}" placeholder="" readonly>
                    </div>
                    <div class="form-group">
                        <label>Primary Sales Person:</label>
                        <select class="form-control js-example-basic-single" name="PrimarySalesPerson" style="position: relative !important" title="Select PrimarySalesPerson" >
                            <option value="" disabled selected>Primary Sales Person</option>
                            @foreach ($salesPersons as $salesPerson)
                                <option value="{{ $salesPerson->user_id }}" @if ( $srf->PrimarySalesPersonId == $salesPerson->user_id) selected @endif>{{ $salesPerson->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Secondary Sales Person:</label>
                        <select class="form-control js-example-basic-single" name="SecondarySalesPerson" style="position: relative !important" title="Select SecondarySalesPerson" >
                            <option value="" disabled selected>Secondary Sales Person</option>
                            @foreach ($salesPersons as $salesPerson)
                                <option value="{{ $salesPerson->user_id }}" @if ( $srf->SecondarySalesPersonId == $salesPerson->user_id) selected @endif>{{ $salesPerson->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>REF Code:</label>
                            <select class="form-control js-example-basic-single editRefCode{{ $srf->Id }}" name="RefCode" id="" style="position: relative !important" title="Select Ref Code">
                                <option value="" disabled selected>Select REF Code</option>
                                <option value="1" @if ( $srf->RefCode == "1") selected @endif>RND</option>
                                <option value="2" @if ( $srf->RefCode == "2") selected @endif>QCD</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Type:</label>
                            <select class="form-control js-example-basic-single editSrfType{{ $srf->Id }}" name="SrfType" id="" style="position: relative !important" title="Select Type">
                                <option value="" disabled selected>Select Type</option>
                                <option value="1" @if ( $srf->SrfType == "1") selected @endif>Regular</option>
                                <option value="2" @if ( $srf->SrfType == "2") selected @endif>PSS</option>
                                <option value="3" @if ( $srf->SrfType == "3") selected @endif>CSS</option>
                            </select>
                        </div>
                        <div class="form-group editSoNumberGroup{{ $srf->Id }}" id="" style="display: none;">
                            <label for="SoNumber">SO Number</label>
                            <input type="text" class="form-control" name="SoNumber" placeholder="Enter SO Number" value="{{ !empty($srf->SoNumber) ? ($srf->SoNumber) : '' }}">
                        </div>
                        <div class="form-group">
                            <label>Client:</label>
                            <select class="form-control js-example-basic-single editClientId{{ $srf->Id }}" name="ClientId" id="" style="position: relative !important" title="Select ClientId" onchange="generateUniqueId()">
                                <option value="" disabled selected>Select Client</option>
                                @foreach ($clients as $client)
                                <option value="{{ $client->id }}"  @if ( $srf->ClientId == $client->id) selected @endif data-type="{{ $client->Type }}">{{ $client->Name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Contact:</label>
                            <select class="form-control js-example-basic-single editClientContactId{{ $srf->Id }}" name="ClientContactId" id="" style="position: relative !important" title="Select ClientContacId">
                                <option value="" disabled selected>Select Contact</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="Remarks">Remarks (Internal)</label>
                            <textarea  class="form-control" name="Remarks" placeholder="Enter Remarks">{{ !empty($srf->InternalRemarks) ? ($srf->InternalRemarks) : '' }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer"></div>
                <div class="form-header">
                    <span class="header-label">Product</span>
                    <hr class="form-divider">
                </div>
                <div class="edit_srf">  
                    <div class="col-lg-12">
                        <button type="button" class="btn btn-danger editeditDeleteRowBtn" hidden style="float: right;">Delete Row</button>
                    </div>  
                    <div id="productRows{{ $srf->Id }}">
                    @foreach ($srf->requestProducts as $index => $product )
                    <div class="row product-row{{ $product->id }}" data-index="{{ $index }}">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label >{{ $product->id }}</label>
                                <input type="hidden" name="product_id[]" value="{{ $product->id }}">
                                <label>Product Type:</label>
                                <select class="form-control js-example-basic-single" name="ProductType[]" style="position: relative !important" title="Select Product Type">
                                    <option value="" disabled selected>Select Product Type</option>
                                    <option value="1" @if ( $product->ProductType == "1") selected @endif>Pure</option>
                                    <option value="2" @if ( $product->ProductType == "2") selected @endif>Blend</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Application:</label>
                                <select class="form-control js-example-basic-single" name="ApplicationId[]" style="position: relative !important" title="Select Application" >
                                    <option value="" disabled selected>Select Application</option>
                                    @foreach ($productApplications as $application)
                                        <option value="{{ $application->id }}"  @if ( $product->ApplicationId == $application->id) selected @endif >{{ $application->Name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="ProductCode">Product Code:</label>
                                <input type="text" class="form-control" name="ProductCode[]" value="{{  $product->ProductCode }}" placeholder="Enter Product Code">
                            </div>
                            <div class="form-group">
                                <label for="ProductDescription">Product Description:</label>
                                <textarea class="form-control" name="ProductDescription[]" placeholder="Enter Product Description" rows="8">{{  $product->ProductDescription }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="NumberOfPackages">Number Of Packages</label>
                                <input type="number" class="form-control" name="NumberOfPackages[]" value="{{  $product->NumberOfPackages }}">
                            </div>
                            <div class="row">
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label for="Quantity">Quantity</label>
                                        <input type="number" class="form-control" name="Quantity[]" value="{{  $product->Quantity }}">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>Unit</label>
                                        <select class="form-control js-example-basic-single" name="UnitOfMeasure[]" style="position: relative !important" title="Select Unit">
                                            <option value="1" @if ( $product->UnitOfMeasure == "1") selected @endif>Grams</option>
                                            <option value="2" @if ( $product->UnitOfMeasure == "2") selected @endif>Kilograms</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" >
                                <label for="Label">Label:</label>
                                <input type="text" class="form-control" name="Label[]" value="{{  $product->Label }}">
                            </div>
                            <div class="form-group" >
                                <label for="RpeNumber">RPE Number:</label>
                                <input type="text" class="form-control" name="RpeNumber[]" value="{{  $product->RpeNumber }}">
                            </div>
                            <div class="form-group" >
                                <label for="CrrNumber">CRR Number:</label>
                                <input type="text" class="form-control" name="CrrNumber[]" value="{{  $product->CrrNumber }}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="RemarksProduct">Remarks</label>
                                <textarea class="form-control" name="RemarksProduct[]" placeholder="Enter Remarks">{{  $product->Remarks }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Disposition:</label>
                                <select class="form-control js-example-basic-single" name="Disposition[]" style="position: relative !important" title="Select Disposition" >
                                    <option value="0" @if ( $product->Disposition == "0") selected @endif>Select Disposition</option>
                                    <option value="1" @if ( $product->Disposition == "1") selected @endif>No feedback</option>
                                    <option value="10" @if ( $product->Disposition == "10") selected @endif>Accepted</option>
                                    <option value="20" @if ( $product->Disposition == "20") selected @endif>Rejected</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Disposition Remarks</label>
                                <textarea class="form-control" name="DispositionRejectionDescription[]" placeholder="Enter DispositionRemarks">{{  $product->DispositionRejectionDescription }}</textarea>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="col-lg-12">
                    {{-- <button type="button" class="btn btn-primary editAddRowButton{{ $srf->Id }}">Add Row</button> --}}
                </div>
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
                        <input type="text" class="form-control" name="Courier" placeholder="Enter Courier" value="{{ $srf->Courier }}">
                    </div>
                    <div class="form-group">
                        <label for="AwbNumber">AWB Number:</label>
                        <input type="text" class="form-control" name="AwbNumber" placeholder="Enter AWB Number" value="{{ $srf->AwbNumber }}">
                    </div>
                    <div class="form-group">
                        <label for="DateDispatched">Date Dispatched (MM/DD/YYYY):</label>
                        <input type="date" class="form-control" name="DateDispatched" placeholder="Enter Date Dispatched" value="{{ !empty($srf->DateDispatched) ? date('Y-m-d', strtotime($srf->DateDispatched)) : '' }}">
                    </div>
                    <div class="form-group">
                        <label for="DateSampleReceived">Date Sample Received (MM/DD/YYYY):</label>
                        <input type="date" class="form-control" name="DateSampleReceived"  placeholder="Enter Sample Received" value="{{ !empty($srf->DateSampleReceived) ? date('Y-m-d' , strtotime($srf->DateSampleReceived)) : '' }}">
                    </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="DeliveryRemarks">Delivery Remarks</label>
                    <textarea class="form-control" name="DeliveryRemarks" placeholder="Enter Delivery Remarks">{{ $srf->DeliveryRemarks }}</textarea>
                </div>
                <div class="form-group">
                    <label for="Note">Notes</label>
                    <textarea class="form-control" name="Note" placeholder="Enter Delivery Notes">{{ $srf->Note }}</textarea>
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
        if (clientId) {
            $.ajax({
                url: '{{ url("sample_contacts-by-client-f") }}/' + clientId,
                type: "GET",
                dataType: "json",
                success: function(data) {
                        var $contactSelect = $('.editClientContactId{{ $srf->Id }}');
                        $contactSelect.empty();
                        $contactSelect.append('<option value="" disabled selected>Select Contact</option>');
                        $.each(data, function(key, value) {
                            var isSelected = (key == selectedContactId) ? ' selected' : '';
                            $contactSelect.append('<option value="' + key + '"' + isSelected + '>' + value + '</option>');
                        });
                    },
                error: function(xhr, status, error) {
                    console.log('AJAX error:', error); 
                }
            });
        } else {
            $('.editClientContactId{{ $srf->Id }}').empty();
        }
    }

    function loadSrfTypes(refCode, selectedType) {
        $('.editSrfType{{ $srf->Id }}').empty().append('<option value="" disabled selected>Select Type</option>');

        if (refCode === '1') { 
            $('.editSrfType{{ $srf->Id }}').append('<option value="1">Regular</option>');
        } else if (refCode === '2') { 
            $('.editSrfType{{ $srf->Id }}').append('<option value="1">Regular</option>');
            $('.editSrfType{{ $srf->Id }}').append('<option value="2">PSS</option>');
            $('.editSrfType{{ $srf->Id }}').append('<option value="3">CSS</option>');
        }

        if (selectedType) {
            $('.editSrfType{{ $srf->Id }}').val(selectedType);
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
        loadSrfTypes(refCode, '{{ $srf->SrfType }}');
    });

    $('.editSrfType{{ $srf->Id }}').on('change', function() {
        checkSoNumberVisibility();
    });

    var initialRefCode = '{{ $srf->RefCode }}';
    var initialSrfType = '{{ $srf->SrfType }}';
    var initialClientId = '{{ $srf->ClientId }}';
    var initialClientContactId = '{{ $srf->ContactId }}';
    $('.editRefCode{{ $srf->Id }}').val(initialRefCode).trigger('change');
    loadSrfTypes(initialRefCode, initialSrfType);
    $('.editClientId{{ $srf->Id }}').val(initialClientId).trigger('change');
    loadClientContacts(initialClientId, initialClientContactId);


    // Jun

// $('.editAddProductRowBtn').click(function() {
//     let newRow = `
//         <!-- Copy the structure of your product row here -->
//         <div class="form-group">
//             <label>Product Type:</label>
//             <select class="form-control js-example-basic-single" name="ProductType[]" style="position: relative !important" title="Select Product Type">
//                 <option value="" disabled selected>Select Product Type</option>
//                 <option value="1">Pure</option>
//                 <option value="2">Blend</option>
//             </select>
//         </div>
//         <!-- Add other product fields as needed -->
//         <button type="button" class="btn btn-danger deleteRowBtn">Delete Row</button>
//     </div>`;
//     $('#productRows{{ $srf->Id }}').append(newRow);
//     updateButtonsVisibility();
// });

// $('#editDuplicateProductForm').click(function() {
//     let lastRow = $('#productRows{{ $srf->Id }} .product-row{{ $product->id }}').last();
//     let clonedRow = lastRow.clone();
//     rowIndex++;
//     clonedRow.attr('data-index', rowIndex);
//     clonedRow.find('input, select, textarea').val('');
//     $('#productRows{{ $srf->Id }}').append(clonedRow);
//     updateButtonsVisibility();
// });

// $(document).on('click', '.deleteRowBtn', function() {
//     $(this).closest('.product-row{{ $product->id }}').remove();
//     updateButtonsVisibility();
// });

// function updateButtonsVisibility() {
//     if ($('#productRows{{ $srf->Id }} .product-row{{ $product->id }}').length > 1) {
//         $('#editDuplicateProductForm').show();
//     } else {
//         $('#editDuplicateProductForm').hide();
//     }
//     $('.editAddProductRowBtn').show();
// }

// updateButtonsVisibility();

});

document.addEventListener('DOMContentLoaded', function () {
    let rowIndex = {{ $srf->requestProducts->count() }}; // Start index after existing rows
    console.log(rowIndex);

    document.querySelector('.editAddRowButton{{ $srf->Id }}').addEventListener('click', function () {
        rowIndex++;
        const productRows = document.getElementById('productRows{{ $srf->Id }}');
        
        // Create a new product row element
        const newRow = document.createElement('div');
        newRow.classList.add('row', 'product-row' + rowIndex);
        newRow.dataset.index = rowIndex;
        newRow.innerHTML = `
            <div class="col-md-6">
                <div class="form-group">
                    <label>Product Type:</label>
                    <select class="form-control js-example-basic-single" name="ProductType[]" style="position: relative !important" title="Select Product Type">
                        <option value="" disabled selected>Select Product Type</option>
                        <option value="1">Pure</option>
                        <option value="2">Blend</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Application:</label>
                    <select class="form-control js-example-basic-single" name="ApplicationId[]" style="position: relative !important" title="Select Application">
                        <option value="" disabled selected>Select Application</option>
                        @foreach ($productApplications as $application)
                            <option value="{{ $application->id }}">{{ $application->Name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="ProductCode">Product Code:</label>
                    <input type="text" class="form-control" name="ProductCode[]" placeholder="Enter Product Code">
                </div>
                <div class="form-group">
                    <label for="ProductDescription">Product Description:</label>
                    <textarea class="form-control" name="ProductDescription[]" placeholder="Enter Product Description" rows="8"></textarea>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="NumberOfPackages">Number Of Packages</label>
                    <input type="number" class="form-control" name="NumberOfPackages[]">
                </div>
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label for="Quantity">Quantity</label>
                            <input type="number" class="form-control" name="Quantity[]">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>Unit</label>
                            <select class="form-control js-example-basic-single" name="UnitOfMeasure[]" style="position: relative !important" title="Select Unit">
                                <option value="1">Grams</option>
                                <option value="2">Kilograms</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="Label">Label:</label>
                    <input type="text" class="form-control" name="Label[]">
                </div>
                <div class="form-group">
                    <label for="RpeNumber">RPE Number:</label>
                    <input type="text" class="form-control" name="RpeNumber[]">
                </div>
                <div class="form-group">
                    <label for="CrrNumber">CRR Number:</label>
                    <input type="text" class="form-control" name="CrrNumber[]">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="RemarksProduct">Remarks</label>
                    <textarea class="form-control" name="RemarksProduct[]" placeholder="Enter Remarks"></textarea>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Disposition:</label>
                    <select class="form-control js-example-basic-single" name="Disposition[]" style="position: relative !important" title="Select Disposition">
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
        `;

        productRows.appendChild(newRow);
    });
});
</script>


<div class="modal fade" id="formSampleRequest" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Region</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_sample_request" enctype="multipart/form-data" action="{{ route('sample_request.store') }}">
                    <span id="form_result"></span>
                    @csrf
                    <?php
                     $now = date('Y-m-d\TH:i');
                     $today = date('Y-m-d');
                    ?>
                    <div class="form-header">
                        <span class="header-label">Request Details</span>
                        <hr class="form-divider">
                    </div>
                    <div class="row">    
                    <div class="col-md-6">
                    <div class="form-group">
                        <label for="DateRequested">Date Requested (MM/DD/YYYY Hour Min):</label>
                        <input type="datetime-local" class="form-control DateRequested"  name="DateRequested" value="<?php echo $now; ?>" placeholder="" readonly>
                    </div>
                    <div class="form-group">
                        <label for="DateRequired">Date Required (MM/DD/YYYY):</label>
                        <input type="date" class="form-control" name="DateRequired" value="<?php echo $today; ?>" min="<?php echo $today; ?>" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="DateStarted">Date Started (MM/DD/YYYY):</label>
                        <input type="date" class="form-control" name="DateStarted" value="" placeholder="" readonly>
                    </div>
                    <div class="form-group">
                        <label>Primary Sales Person:</label>
                        <select class="form-control js-example-basic-single" name="PrimarySalesPerson" style="position: relative !important" title="Select PrimarySalesPerson" required>
                            <option value="" disabled selected>Primary Sales Person</option>
                            @foreach ($salesPersons as $salesPerson)
                                <option value="{{ $salesPerson->user_id }}" >{{ $salesPerson->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Secondary Sales Person:</label>
                        <select class="form-control js-example-basic-single" name="SecondarySalesPerson"  style="position: relative !important" title="Select SecondarySalesPerson" required>
                            <option value="" disabled selected>Secondary Sales Person</option>
                            @foreach ($salesPersons as $salesPerson)
                                <option value="{{ $salesPerson->user_id }}" >{{ $salesPerson->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>REF Code:</label>
                        <select class="form-control js-example-basic-single" name="RefCode" id="RefCode" style="position: relative !important" title="Select Ref Code" required>
                            <option value="" disabled selected>Select REF Code</option>
                            <option value="1">RND</option>
                            <option value="2">QCD</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Type:</label>
                        <select class="form-control js-example-basic-single" name="SrfType" id="SrfType" style="position: relative !important" title="Select Type" required>
                            <option value="" disabled selected>Select Type</option>
                            <option value="1">Regular</option>
                            <option value="2">PSS</option>
                            <option value="3">CSS</option>
                        </select>
                    </div>
                    <div class="form-group" id="SoNumberGroup" style="display: none;">
                        <label for="SoNumber">SO Number</label>
                        <input type="text" class="form-control" name="SoNumber" placeholder="Enter SO Number">
                    </div>
                   
                    <div class="form-group">
                        <label>Client:</label>
                        <select class="form-control js-example-basic-single ClientId" name="ClientId"  style="position: relative !important" title="Select ClientId" onchange="generateUniqueId()" required>
                            <option value="" disabled selected>Select Client</option>
                            @foreach ($clients as $client)
                            <option value="{{ $client->id }}" data-type="{{ $client->Type }}">{{ $client->Name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Contact:</label>
                        <select class="form-control js-example-basic-single" name="ClientContactId" id="ClientContactId" style="position: relative !important" title="Select ClientContacId" required>
                            <option value="" disabled selected>Select Contact</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="Remarks">Remarks (Internal)</label>
                        <textarea class="form-control" name="Remarks" placeholder="Enter Remarks"></textarea>
                    </div>
                   
                    <div class="form-group" hidden >
                        <label for="SrfNumber">Unique ID:</label>
                        <input type="text" class="form-control" id="SrfNumber" name="SrfNumber" readonly>
                    </div>
                </div>
            </div>
            <div class="modal-footer"></div>
            <div class="form-header">
                <span class="header-label">Product</span>
                <hr class="form-divider">
            </div>
            <div class="row form_request_product" >  
                <div class="col-lg-12">
                    <button type="button" class="btn btn-danger deleteRowBtn" hidden style="float: right;">Delete Row</button>
                </div>  

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
                    <select class="form-control js-example-basic-single" name="ApplicationId[]" style="position: relative !important" title="Select Application" required>
                        <option value="" disabled selected>Select Application</option>
                        @foreach ($productApplications as $application)
                            <option value="{{ $application->id }}" >{{ $application->Name }}</option>
                        @endforeach
                    </select>
                </div>
            {{-- <div class="form-group">
                <label for="ProductCode">Product Code:</label>
                <input type="text" class="form-control" id="ProductCode" name="ProductCode[]" placeholder="Enter Product Code">
            </div> --}}
            <div class="form-group">
                <label>Product Code:</label>
                <select class="form-control js-example-basic-single" name="ProductCode[]"  style="position: relative !important" title="Select Product Code" required>
                    <option value="" disabled selected>Product Code</option>
                    @foreach ($productCodes as $productCode)
                        <option value="{{ $productCode->code }}" >{{ $productCode->code }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="ProductDescription">Product Description:</label>
                <textarea class="form-control"  name="ProductDescription[]" placeholder="Enter Product Description" rows="8"></textarea>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="NumberOfPackages">Number Of Packages</label>
                <input type="number" class="form-control" name="NumberOfPackages[]" value="0">
            </div>
            <div class="row">
                <div class="col-md-7">
                    <div class="form-group">
                        <label for="Quantity">Quantity</label>
                        <input type="number" class="form-control" name="Quantity[]" value="0">
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
            <div class="form-group" >
                <label for="Label">Label:</label>
                <input type="text" class="form-control" name="Label[]">
            </div>
            <div class="form-group" >
                <label for="RpeNumber">RPE Number:</label>
                <input type="text" class="form-control" name="RpeNumber[]">
            </div>
            <div class="form-group" >
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
        <div class="col-lg-12">
            <button type="button" class="btn btn-primary" id="addProductRowBtn" style="float: left; margin:5px;">Add Row</button> 
            <button type="button" class="btn btn-info duplicateProductForm"  style="float: left; margin:5px;">Duplicate</button>
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
            <input type="text" class="form-control" name="Courier" placeholder="Enter Courier">
        </div>
        <div class="form-group">
            <label for="AwbNumber">AWB Number:</label>
            <input type="text" class="form-control"  name="AwbNumber" placeholder="Enter AWB Number">
        </div>
        <div class="form-group">
            <label for="DateDispatched">Date Dispatched (MM/DD/YYYY):</label>
            <input type="date" id="DateDispatched" class="form-control" name="DateDispatched" placeholder="Enter Date Dispatched">
        </div>

        <div class="form-group">
            <label for="DateSampleReceived">Date Sample Received (MM/DD/YYYY):</label>
            <input type="date" id="DateSampleReceived" class="form-control" name="DateSampleReceived" placeholder="Enter Sample Received">
        </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label for="DeliveryRemarks">Delivery Remarks</label>
        <textarea class="form-control" name="DeliveryRemarks" placeholder="Enter Delivery Remarks"></textarea>
    </div>
    <div class="form-group">
        <label for="Note">Notes</label>
        <textarea class="form-control" name="Note" placeholder="Enter Delivery Notes"></textarea>
    </div>
</div>
</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="submit"  class="btn btn-success" value="Save">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/sweetalert2.min.js') }}"></script>
<script>
     @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "{{ session('error') }}",
                confirmButtonText: 'OK'
            });
        @elseif(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: "{{ session('success') }}",
                confirmButtonText: 'OK'
            });
        @endif

     function generateUniqueId() {
        const clientSelect = document.querySelector('.ClientId');
        const clientId = clientSelect.value;
        const clientType = clientSelect.options[clientSelect.selectedIndex].getAttribute('data-type');
        const dateRequested = document.querySelector('.DateRequested').value;
        const year = new Date(dateRequested).getFullYear().toString().slice(-2);
        let clientCode = clientType == 1 ? 'LS' : 'IS';

        fetch(`sample_get-last-increment-f/${year}/${clientCode}`)
            .then(response => response.json())
            .then(data => {
                const lastIncrement = data.lastIncrement;
                const increment = ('000' + (parseInt(lastIncrement) + 1)).slice(-4);
                const uniqueId = `SRF-${clientCode}-${year}-${increment}`;
                document.getElementById('SrfNumber').value = uniqueId;
            });
    }
    $(document).ready(function() {
    function addProductRow() {
        var newProductForm = `
        <div class="row form_request_product">
            <div class="col-lg-12">
                <button type="button" class="btn btn-danger deleteRowBtn" style="float: right;">Delete Row</button>
            </div>
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
                <label>Product Code:</label>
                <select class="form-control js-example-basic-single" name="ProductCode[]"  style="position: relative !important" title="Select Product Code" >
                    <option value="" disabled selected>Product Code</option>
                    @foreach ($productCodes as $productCode)
                        <option value="{{ $productCode->code }}" >{{ $productCode->code }}</option>
                    @endforeach
                </select>
                </div>
                <div class="form-group">
                    <label for="ProductDescription">Product Description:</label>
                    <textarea class="form-control" name="ProductDescription[]" placeholder="Enter Product Description" rows="8"></textarea>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="NumberOfPackages">Number Of Packages</label>
                    <input type="number" class="form-control" name="NumberOfPackages[]" value="0">
                </div>
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label for="Quantity">Quantity</label>
                            <input type="number" class="form-control" name="Quantity[]" value="0">
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
            <div class="col-lg-12">
                <button type="button" class="btn btn-primary addProductRowBtn" style="float: left;">Add Row</button>
                <button type="button" class="btn btn-info duplicateProductForm" style="float: left; margin-left: 5px;">Duplicate</button>
            </div>
        </div>`;

        $('.form_request_product').last().find('.addProductRowBtn, .duplicateProductForm').hide();
        
        $('.form_request_product').last().after(newProductForm);
        $('.js-example-basic-single').select2();
        $('.form_request_product').last().find('.deleteRowBtn').removeAttr('hidden');
    }

    function duplicateProductRow() {
        var lastRow = $('.form_request_product').last();
        var newRow = lastRow.clone();

        newRow.find('select').removeClass('select2-hidden-accessible').next('.select2-container').remove();

        newRow.find('input, textarea, select').each(function() {
            var $this = $(this);
            var name = $this.attr('name');

            if ($this.is('select')) {
                $this.val(lastRow.find('[name="' + name + '"]').val()).trigger('change');
            } else {
                $this.val(lastRow.find('[name="' + name + '"]').val());
            }
        });
        lastRow.find('.addProductRowBtn, .duplicateProductForm').hide();
        newRow.insertAfter(lastRow);
        newRow.find('.js-example-basic-single').select2();
        newRow.find('.deleteRowBtn').removeAttr('hidden');
    }

    $(document).on('click', '#addProductRowBtn', function() {
        addProductRow();
        $('#addProductRowBtn').hide(); 
    });
    $(document).on('click', '.addProductRowBtn', function() {
        addProductRow();
    });

    $(document).on('click', '.duplicateProductForm', function() {
        duplicateProductRow();
    });

    $(document).on('click', '.deleteRowBtn', function() {
        var currentRow = $(this).closest('.form_request_product');
        
        if ($('.form_request_product').last().is(currentRow)) {
            currentRow.prev().find('.addProductRowBtn, .duplicateProductForm').show();
        }

        currentRow.remove();

        
    });
});




$(document).ready(function() {
        const $dateRequested = $('.DateRequested');
        const $dateDispatched = $('#DateDispatched');
        const $dateSampleReceived = $('#DateSampleReceived');

        function updateMinDate() {
            const dateRequestedValue = new Date($dateRequested.val());
            const formattedDateRequested = dateRequestedValue.toISOString().split('T')[0];

            $dateDispatched.attr('min', formattedDateRequested);
            $dateSampleReceived.attr('min', formattedDateRequested);
        }

        updateMinDate();

        $dateRequested.on('change', updateMinDate);
    });

    $(document).ready(function() {
        $('.ClientId').on('change', function() {
            var clientId = $(this).val();
            if(clientId) {
                $.ajax({
                    url: '{{ url("sample_contacts-by-client-f") }}/' + clientId,
                    type: "GET",
                    dataType: "json",
                    success:function(data) {
                        $('#ClientContactId').empty();
                        $('#ClientContactId').append('<option value="" disabled selected>Select Contact</option>');
                        $.each(data, function(key, value) {
                            $('#ClientContactId').append('<option value="'+ key +'">'+ value +'</option>');
                        });
                    }
                });
            } else {
                $('#ClientContactId').empty();
            }
        });
        $('#SrfType').empty().append('<option value="" disabled selected>Select Type</option>');

        $('#RefCode').change(function() {
            var refCode = $(this).val();
            $('#SrfType').empty().append('<option value="" disabled selected>Select Type</option>');

            if (refCode === '1') { 
                $('#SrfType').append('<option value="1">Regular</option>');
            } else if (refCode === '2') { 
                $('#SrfType').append('<option value="1">Regular</option>');
                $('#SrfType').append('<option value="2">PSS</option>');
                $('#SrfType').append('<option value="3">CSS</option>');
            }
            
            checkSoNumberVisibility();
        });
        function checkSoNumberVisibility() {
            var selectedType = $('#SrfType').val();
            if (selectedType === '2' || selectedType === '3') {
                $('#SoNumberGroup').show();
            } else {
                $('#SoNumberGroup').hide();
            }
        }

        $('#SrfType').change(function() {
            var selectedType = $(this).val();
            if (selectedType === '2' || selectedType === '3') {
                $('#SoNumberGroup').show();
            } else {
                $('#SoNumberGroup').hide();
            }
        });
    });
    // $('#add_sample_request').click(function(){
    //         $('#formSampleRequest').modal('show');
    //         $('.modal-title').text("Add Sample Request");
    //     });
</script>


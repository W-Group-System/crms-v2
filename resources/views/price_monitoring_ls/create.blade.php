<div class="modal fade" id="AddPriceMonitoringLs" tabindex="-1" role="dialog" aria-labelledby="addPriceMonitoring" aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addPriceMonitoringLabel">Add Price Monitoring</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" action="{{ url('local_price_monitoring') }}" onsubmit="show()">
                    @csrf
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                {{-- <label>Primary Sales Person</label>
                                <select class="form-control js-example-basic-single" name="PrimarySalesPersonId" id="PrimarySalesPersonId" style="position: relative !important" title="Select Sales Person">
                                    <option value="" disabled selected>Select Sales Person</option>
                                    @foreach($primarySalesPersons as $user)
                                        <option value="{{ $user->user_id }}">{{ $user->full_name }}</option>
                                    @endforeach
                                </select> --}}
                                <label>Primary Sales Person</label>
                                {{-- @if(auth()->user()->role->name == "Staff L1")
                                    <input type="hidden" name="PrimarySalesPersonId" value="{{auth()->user()->id}}">
                                    <input type="text" class="form-control" value="{{auth()->user()->full_name}}" readonly>
                                @elseif(auth()->user()->role->name == "Department Admin" || auth()->user()->role->name == "Staff L2")
                                    @php
                                        $subordinates = getUserApprover(auth()->user()->getSalesApprover);
                                    @endphp
                                    <select class="form-control js-example-basic-single" name="PrimarySalesPersonId" id="PrimarySalesPersonId" style="position: relative !important" title="Select Sales Person">
                                        <option value="" disabled selected>Select Sales Person</option>
                                        @foreach($subordinates as $subordinate)
                                            <option value="{{ $subordinate->id }}" @if(old('PrimarySalesPersonId') == $subordinate->id || auth()->user()->id == $subordinate->id) selected @endif>{{ $subordinate->full_name }}</option>
                                        @endforeach
                                    </select>
                                @endif --}}
                                <select class="form-control js-example-basic-single" name="PrimarySalesPersonId" style="position: relative !important" title="Select Sales Person" required>
                                    <option value="" disabled selected>Select Sales Person</option>
                                    @foreach($loggedInUser->groupSales as $group_sales)
                                        @php
                                            $user = $group_sales->user;
                                        @endphp
                                        <option value="{{ $user->id }}" @if($user->id == $loggedInUser->id) selected @endif>{{ $user->full_name }}</option>
                                    @endforeach
                                </select> 
                            </div>
                            <div class="form-group">
                                <label>Secondary Sales Person</label>
                                <select class="form-control js-example-basic-single" name="SecondarySalesPersonId" style="position: relative !important" title="Select Sales Person" required>
                                    <option value="" disabled selected>Select Sales Person</option>
                                    @foreach($loggedInUser->groupSales as $group_sales)
                                        @php
                                            $user = $group_sales->user;
                                        @endphp
                                        <option value="{{ $user->id }}" >{{ $user->full_name }}</option>
                                    @endforeach
                                </select> 
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Date Requested (DD/MM/YYYY)</label>
                                <input type="date" class="form-control DateRequested" name="DateRequested" readonly>
                            </div>
                        </div>
                        <div class="col-lg-12"><hr style="background-color: black"></div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Client</label>
                                <select class="form-control js-example-basic-single ClientId" name="ClientId"  style="position: relative !important" title="Select Client" required>
                                    <option value="" disabled selected>Select Client</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}">{{ $client->Name }}</option>
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
                                <label>Validity Date</label>
                                <input type="date" class="form-control ValidityDate" name="ValidityDate" placeholder="Enter Validity">
                            </div>
                            <div class="form-group">
                                <label>Packaging Type</label>
                                <input type="text" class="form-control" name="PackagingType" placeholder="Enter Packaging Type">
                            </div>
                            <div class="form-group">
                                <label>MOQ</label>
                                <input type="text" class="form-control" name="Moq" placeholder="Enter Moq">
                            </div>
                            <div class="form-group">
                                <label>Shelf Life</label>
                                <input type="text" class="form-control" name="ShelfLife" placeholder="Enter Shelf Life" value="2 Years">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Shipment Term</label>
                                <input type="text" class="form-control" name="ShipmentTerm" placeholder="Enter Shipment Term">
                            </div>
                            <div class="form-group">
                                <label>Destination</label>
                                <input type="text" class="form-control" name="Destination" placeholder="Enter Destination">
                            </div>
                            {{-- <div class="form-group">
                                <label>Payment Term</label>
                                <input type="text" class="form-control payment-term" name="PaymentTerm" placeholder="" readonly>
                            </div> --}}
                            <div class="form-group">
                                <label>Payment Term</label>
                                <select class="form-control js-example-basic-single" name="PaymentTerm" style="position: relative !important" title="Select Payment Term">
                                    <option value="" disabled selected>Select Payment Term</option>
                                    @foreach($payment_terms as $paymentTerm)
                                        <option value="{{ $paymentTerm->id }}">{{ $paymentTerm->Name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Purpose of Price Request</label>
                                <select class="form-control js-example-basic-single" name="PriceRequestPurpose"  style="position: relative !important" title="Select Purpose">
                                   <option value="" disabled selected>Select Purpose</option>
                                   <option value="10">Indication</option>
                                   <option value="20">Firm</option>
                                   <option value="30">Sample</option>
                                </select>
                           </div>
                           <div class="form-group">
                            <label>Delivery Schedule</label>
                            <input type="text" class="form-control" name="DeliverySchedule" placeholder="Enter Other Cost Requirement">
                            </div>
                            <div class="form-group">
                                <label>Tax Type</label>
                                <select class="form-control js-example-basic-single" name="TaxType"  style="position: relative !important" title="Select Tax Type">
                                   <option value="10">VAT Inclusive</option>
                                   <option value="20">VAT Exclusive</option>
                                </select>
                           </div>
                           <div class="form-group">
                                <label>Other Remarks</label>
                                <input type="text" class="form-control" name="OtherRemarks" placeholder="Input Other Remarks" >
                            </div>
                        </div>
                        <div class="col-lg-12"><hr style="background-color: black"></div>
                        <div class="add_create_prf_form col-lg-12 row">
                            <div class="col-lg-4">
                                <div><label>PRODUCT</label></div>
                                <div class="form-group">
                                    <label>Product</label>
                                    <select class="form-control js-example-basic-single product-pick product-select" name="Product[]" style="position: relative !important" title="Select Product" required>
                                        <option value="" disabled selected>Select Product</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" data-type="{{ $product->type }}" data-application_id="{{ $product->application_id }}">{{ $product->code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                     <label>Category</label>
                                     <select class="form-control js-example-basic-single category-select" name="Type[]"  style="position: relative !important" title="Select Category">
                                        <option value="" disabled selected>Select Category</option>
                                        <option value="1">Pure</option>
                                        <option value="2">Blend</option>
                                     </select>
                                </div>
                                <div class="form-group">
                                    <label>Application:</label>
                                    <select class="form-control js-example-basic-single application-select" name="ApplicationId[]" style="position: relative !important" title="Select Application" required>
                                        <option value="" disabled selected>Select Application</option>
                                        @foreach ($productApplications as $application)
                                            <option value="{{ $application->id }}" >{{ $application->Name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Quantity Required</label>
                                    <input type="text" class="form-control" name="QuantityRequired[]" value="0" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, ',');">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div><label>MANUFACTURING COST</label></div>
                                <div class="form-group">
                                    <label>RMC (PHP)</label>
                                    <input type="number" class="form-control rmc-input" name="Rmc[]" value="0" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Direct Labor</label>
                                    <input type="number" class="form-control direct-labor-input" name="DirectLabor[]" value="2.16" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Factory Overhead</label>
                                    <input type="number" class="form-control factory-overhead-input" name="FactoryOverhead[]" value="24.26" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Total Manufacturing Cost</label>
                                    <input type="number" class="form-control total-manufacturing-cost-input" name="TotalManufacturingCost[]" value="0" readonly>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-12"><hr style="background-color: rgb(219, 209, 209) !important"></div>
                                </div>
                                <div class="form-group">
                                    <label>Blending Loss:</label>
                                    <input type="number" class="form-control blending-loss" name="BlendingLoss[]"  value="0" readonly>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div><label>OPERATING COST</label></div>
                                <div class="form-group">
                                    <label>Delivery Type</label>
                                    <select class="form-control js-example-basic-single delivery-type" name="DeliveryType[]" style="position: relative !important" title="Select Delivery Type">
                                        <option value="10">Courier</option>
                                        <option value="20">Delivery</option>
                                        <option value="30">Pickup</option>
                                     </select>
                                </div>
                                <div class="form-group">
                                    <label>Delivery Cost</label>
                                    <input type="number" class="form-control delivery-cost" name="DeliveryCost[]" value="0">
                                </div>
                                <div class="form-group">
                                    <label>Financing Cost</label>
                                    <input type="number" class="form-control financing-cost" name="FinancingCost[]" value="0" readonly>
                                </div>
                                <div class="form-group">
                                    <label>GAE Type:</label>
                                    <select class="form-control js-example-basic-single PriceGae" name="PriceGae[]" style="position: relative !important" title="Select GAE Type">
                                        @foreach ($pricegaes as $gaeType)
                                            <option value="{{ $gaeType->id }}" >{{ $gaeType->ExpenseName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>GAE Cost</label>
                                    <input type="number" class="form-control GaeCost" name="GaeCost[]" value="0" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Other Cost Requirement</label>
                                    <input type="number" step=".01" class="form-control other-cost" name="OtherCostRequirement[]" placeholder="Enter Other Cost Requirement" value="0">
                                </div>
                                <div class="form-group">
                                    <label>Total Operating Cost</label>
                                    <input type="number" class="form-control total-operation-cost" name="TotalOperatingCost[]" value="0" readonly>
                                </div>
                            </div>
                        <div class="col-lg-12"><hr style="background-color: rgb(219, 209, 209) !important"></div>
                            <div class="col-lg-4">
                                <div><label>PRODUCT COST</label></div>
                                <div class="form-group">
                                    <label>Total Product Cost (PHP)</label>
                                    <input type="number" class="form-control total-product-cost" name="TotalProductCost[]" value="0" readonly>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div><label>MARKUP COST</label></div>
                                <div class="form-group">
                                    <label>Markup (%)</label>
                                    <input type="number" step=".01" class="form-control markup-percent" name="MarkupPercent[]" value="0">
                                </div>
                                <div class="form-group">
                                    <label>Markup (PHP)</label>
                                    <input type="number" step=".01" class="form-control markup-php" name="MarkupPhp[]" value="0">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div><label>SELLING PRICE</label></div>
                                <div class="form-group">
                                    <label>Selling Price (PHP)</label>
                                    <input type="number" step=".01" class="form-control selling-price-php" name="SellingPricePhp[]" value="0">
                                </div>
                                <div class="form-group">
                                    <label>Selling Price + 12% VAT (PHP)</label>
                                    <input type="number" step=".01" class="form-control selling-price-vat" name="SellingPriceVat[]" value="0">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <button type="button" class="btn btn-primary addPrfProductRowBtn" id="addPrfProductRowBtn" style="float: left; margin:5px;"><i class="ti ti-plus"></i></button> 
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <input type="submit"  class="btn btn-success" value="Send Request">
                    </div>
                </form>
            </div>
		</div>
	</div>
</div>
{{-- <script src="{{ asset('js/sweetalert2.min.js') }}"></script> --}}
<script>
    //  @if(session('error'))
    //         Swal.fire({
    //             icon: 'error',
    //             title: 'Oops...',
    //             text: "{{ session('error') }}",
    //             confirmButtonText: 'OK'
    //         });
    //     @elseif(session('success'))
    //         Swal.fire({
    //             icon: 'success',
    //             title: 'Success',
    //             text: "{{ session('success') }}",
    //             confirmButtonText: 'OK'
    //         });
    //     @endif

        $(document).ready(function() {
        $('.ClientId').change(function() {
            var clientId = $(this).val();
            if(clientId) {
                $.ajax({
                    url: '{{ url("client-contact") }}/' + clientId,
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

                $.ajax({
                    url: '{{ url("get-payment-term") }}/' + clientId,
                    type: "GET",
                    dataType: "json",
                    success:function(data) {
                        $('.payment-term').val(data.PaymentTerm || '');
                    }
                });
            } else {
                $('#ClientContactId').empty();
                $('.GaeCost').val("");
            }
        });

        function fetchGaeCost(priceGae, $row) {
    if (priceGae) {
        $.ajax({
            url: '{{ url("getGaeCost") }}/' + priceGae,
            type: "GET",
            dataType: "json",
            success: function(data) {
                $row.find('.GaeCost').val(data.Cost);
                updateTotalOperationCost($row);
                updateTotalProductCost($row);
            }
        });
    } else {
        $row.find('.GaeCost').val(0);
    }
}

$(document).ready(function() {
    var $initialRow = $('.add_create_prf_form');
    var initialGae = $initialRow.find('.PriceGae').val();
    fetchGaeCost(initialGae, $initialRow);

    $(document).on('change', '.PriceGae', function() {
        var $row = $(this).closest('.add_create_prf_form');
        var priceGae = $(this).val();
        fetchGaeCost(priceGae, $row);
    });
});

        $(document).on('change', '.product-select', function() {
        var $row = $(this).closest('.add_create_prf_form');
        var productId = $(this).val();
        
        if (productId) {
            $.ajax({
                url: '{{ url("product-rmc") }}/' + productId,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $row.find('.rmc-input').val(data.rmc);
                    var directLabor = parseFloat($row.find('.direct-labor-input').val());
                    var factoryOverhead = parseFloat($row.find('.factory-overhead-input').val());
                    var rmc = parseFloat(data.rmc);
                    var totalManufacturingCost = rmc + directLabor + factoryOverhead;
                    $row.find('.total-manufacturing-cost-input').val(totalManufacturingCost.toFixed(2));
                    var blendingLoss = 0.01 * rmc ;
                    $row.find('.blending-loss').val(blendingLoss.toFixed(2));
                    var financingCost = 0.05 * totalManufacturingCost ;
                    $row.find('.financing-cost').val(financingCost.toFixed(2));

                    updateTotalOperationCost($row);
                    updateTotalProductCost($row);
                },
                error: function() {
                    alert("Failed to fetch RMC value.");
                }
            });
        }
    });

    $(document).on('change', '.delivery-type', function() {
        var $row = $(this).closest('.add_create_prf_form');
        var deliveryType = $(this).val();
        var deliveryCostInput = $row.find('.delivery-cost');

        if (deliveryType === '10') {
            deliveryCostInput.val(0);
            deliveryCostInput.prop('readonly', false);
        } else if (deliveryType === '20') {
            deliveryCostInput.val(1.84);
            deliveryCostInput.prop('readonly', true);
        } else if (deliveryType === '30') {
            deliveryCostInput.val(0);
            deliveryCostInput.prop('readonly', true);
        }
        updateTotalOperationCost($row);
        updateTotalProductCost($row);
    });

        $('.delivery-type').trigger('change');

        function updateTotalOperationCost($row) {
        var deliveryCost = parseFloat($row.find('.delivery-cost').val());
        var financingCost = parseFloat($row.find('.financing-cost').val());
        var gaeCost = parseFloat($row.find('.GaeCost').val()); 
        var otherCost = parseFloat($row.find('.other-cost').val()); 
        
        var totalOperationCost = deliveryCost + financingCost + gaeCost + otherCost;
        $row.find('.total-operation-cost').val(totalOperationCost.toFixed(2)); 

        updateTotalProductCost($row);
    }

    $(document).on('input', '.delivery-cost, .other-cost', function() {
        var $row = $(this).closest('.add_create_prf_form');
        updateTotalOperationCost($row);
        updateTotalProductCost($row);
    });

function updateTotalProductCost($row) {
        var totalManufacturing = parseFloat($row.find('.total-manufacturing-cost-input').val());
        var totalOperating = parseFloat($row.find('.total-operation-cost').val());
        var blendingLoss = parseFloat($row.find('.blending-loss').val()); 
        
        var totalProductCost = totalManufacturing + totalOperating + blendingLoss;
        $row.find('.total-product-cost').val(totalProductCost.toFixed(2)); 

        updateMarkupPHP($row);
        updateMarkupPercent($row);
    }

function updateSellingPrice($row) {
        var totalProductCost = parseFloat($row.find('.total-product-cost').val());
        var markupPHP = parseFloat($row.find('.markup-php').val());

        if (!isNaN(totalProductCost) && !isNaN(markupPHP)) {
            var sellingPrice = totalProductCost + markupPHP;
            $row.find('.selling-price-php').val(sellingPrice.toFixed(2));
        }
    }

    function updateSellingPriceWithVAT($row) {
        var sellingPrice = parseFloat($row.find('.selling-price-php').val());

        if (!isNaN(sellingPrice)) {
            var sellingPriceWithVAT = sellingPrice + (sellingPrice * 0.12);
            $row.find('.selling-price-vat').val(sellingPriceWithVAT.toFixed(2));
        }
    }

    function updateMarkupPHP($row) {
        var totalProductCost = parseFloat($row.find('.total-product-cost').val());
        var markupPercent = parseFloat($row.find('.markup-percent').val());

        if (!isNaN(totalProductCost) && !isNaN(markupPercent)) {
            var markupPHP = (markupPercent / 100) * totalProductCost;
            $row.find('.markup-php').val(markupPHP.toFixed(2));
            updateSellingPrice($row);
            updateSellingPriceWithVAT($row);
        }
    }

    function updateMarkupPercent($row) {
        var totalProductCost = parseFloat($row.find('.total-product-cost').val());
        var markupPHP = parseFloat($row.find('.markup-php').val());

        if (!isNaN(totalProductCost) && !isNaN(markupPHP)) {
            var markupPercent = (markupPHP / totalProductCost) * 100;
            $row.find('.markup-percent').val(markupPercent.toFixed(2));
            updateSellingPrice($row);
            updateSellingPriceWithVAT($row);
        }
    }

    $(document).on('input', '.markup-percent', function() {
        var $row = $(this).closest('.add_create_prf_form');
        updateMarkupPHP($row);
    });

    $(document).on('input', '.markup-php', function() {
        var $row = $(this).closest('.add_create_prf_form');
        updateMarkupPercent($row);
    });

    $(document).on('input', '.selling-price-php', function() {
        var $row = $(this).closest('.add_create_prf_form');
        var sellingPrice = parseFloat($(this).val());
        var totalProductCost = parseFloat($row.find('.total-product-cost').val());

        if (!isNaN(sellingPrice) && !isNaN(totalProductCost)) {
            var markupPHP = sellingPrice - totalProductCost;
            var markupPercent = (markupPHP / totalProductCost) * 100;
            var sellingPriceWithVAT = sellingPrice + (sellingPrice * 0.12);

            $row.find('.markup-php').val(markupPHP.toFixed(2));
            $row.find('.markup-percent').val(markupPercent.toFixed(2));
            $row.find('.selling-price-vat').val(sellingPriceWithVAT.toFixed(2));
        }
    });

    $(document).on('input', '.selling-price-vat', function() {
        var $row = $(this).closest('.add_create_prf_form');
        var sellingPriceWithVAT = parseFloat($(this).val());
        var totalProductCost = parseFloat($row.find('.total-product-cost').val());

        if (!isNaN(sellingPriceWithVAT) && !isNaN(totalProductCost)) {
            var sellingPrice = sellingPriceWithVAT / 1.12;
            var markupPHP = sellingPrice - totalProductCost;
            var markupPercent = (markupPHP / totalProductCost) * 100;

            $row.find('.selling-price-php').val(sellingPrice.toFixed(2));
            $row.find('.markup-php').val(markupPHP.toFixed(2));
            $row.find('.markup-percent').val(markupPercent.toFixed(2));
        }
    });
    
        function addProductRow() {
        var newProductForm = `
                        <div class="add_create_prf_form col-lg-12 row">
                            <div class="col-lg-12"><hr style="background-color: black"></div>
                            <div class="col-lg-12">
                                <button type="button" class="btn btn-danger deletePrfBtn" style="float: right;">Delete Row</button>
                            </div>
                            <div class="col-lg-4">
                                <div><label>PRODUCT</label></div>
                                <div class="form-group">
                                    <label>Product</label>
                                    <select class="form-control js-example-basic-single product-pick product-select" name="Product[]" style="position: relative !important" title="Select Product" required>
                                        <option value="" disabled selected>Select Product</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" data-type="{{ $product->type }}" data-application_id="{{ $product->application_id }}">{{ $product->code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                     <label>Category</label>
                                     <select class="form-control js-example-basic-single category-select" name="Type[]"  style="position: relative !important" title="Select Category">
                                        <option value="" disabled selected>Select Category</option>
                                        <option value="1">Pure</option>
                                        <option value="2">Blend</option>
                                     </select>
                                </div>
                                <div class="form-group">
                                    <label>Application:</label>
                                    <select class="form-control js-example-basic-single application-select" name="ApplicationId[]" style="position: relative !important" title="Select Application" required>
                                        <option value="" disabled selected>Select Application</option>
                                        @foreach ($productApplications as $application)
                                            <option value="{{ $application->id }}" >{{ $application->Name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Quantity Required</label>
                                    <input type="number" class="form-control" name="QuantityRequired[]" value="0">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div><label>MANUFACTURING COST</label></div>
                                <div class="form-group">
                                    <label>RMC (PHP)</label>
                                    <input type="number" class="form-control rmc-input" name="Rmc[]" value="0" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Direct Labor</label>
                                    <input type="number" class="form-control direct-labor-input" name="DirectLabor[]" value="2.16" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Factory Overhead</label>
                                    <input type="number" class="form-control factory-overhead-input" name="FactoryOverhead[]" value="24.26" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Total Manufacturing Cost</label>
                                    <input type="number" class="form-control total-manufacturing-cost-input" name="TotalManufacturingCost[]" value="0" readonly>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-12"><hr style="background-color: rgb(219, 209, 209) !important"></div>
                                </div>
                                <div class="form-group">
                                    <label>Blending Loss:</label>
                                    <input type="number" class="form-control blending-loss" name="BlendingLoss[]"  value="0" readonly>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div><label>OPERATING COST</label></div>
                                <div class="form-group">
                                    <label>Delivery Type</label>
                                    <select class="form-control js-example-basic-single delivery-type" name="DeliveryType[]" style="position: relative !important" title="Select Delivery Type">
                                        <option value="10">Courier</option>
                                        <option value="20">Delivery</option>
                                        <option value="30">Pickup</option>
                                     </select>
                                </div>
                                <div class="form-group">
                                    <label>Delivery Cost</label>
                                    <input type="number" class="form-control delivery-cost" name="DeliveryCost[]" value="0">
                                </div>
                                <div class="form-group">
                                    <label>Financing Cost</label>
                                    <input type="number" class="form-control financing-cost" name="FinancingCost[]" value="0" readonly>
                                </div>
                                <div class="form-group">
                                    <label>GAE Type:</label>
                                    <select class="form-control js-example-basic-single PriceGae" name="PriceGae[]" style="position: relative !important" title="Select GAE Type">
                                        @foreach ($pricegaes as $gaeType)
                                            <option value="{{ $gaeType->id }}" >{{ $gaeType->ExpenseName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>GAE Cost</label>
                                    <input type="number" class="form-control GaeCost" name="GaeCost[]" value="0" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Other Cost Requirement</label>
                                    <input type="number" step=".01" class="form-control other-cost" name="OtherCostRequirement[]" placeholder="Enter Other Cost Requirement" value="0">
                                </div>
                                <div class="form-group">
                                    <label>Total Operating Cost</label>
                                    <input type="number" class="form-control total-operation-cost" name="TotalOperatingCost[]" value="0" readonly>
                                </div>
                            </div>
                        <div class="col-lg-12"><hr style="background-color: rgb(219, 209, 209) !important"></div>
                            <div class="col-lg-4">
                                <div><label>PRODUCT COST</label></div>
                                <div class="form-group">
                                    <label>Total Product Cost (PHP)</label>
                                    <input type="number" class="form-control total-product-cost" name="TotalProductCost[]" value="0" readonly>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div><label>MARKUP COST</label></div>
                                <div class="form-group">
                                    <label>Markup (%)</label>
                                    <input type="number" step=".01" class="form-control markup-percent" name="MarkupPercent[]" value="0">
                                </div>
                                <div class="form-group">
                                    <label>Markup (PHP)</label>
                                    <input type="number" step=".01" class="form-control markup-php" name="MarkupPhp[]" value="0">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div><label>SELLING PRICE</label></div>
                                <div class="form-group">
                                    <label>Selling Price (PHP)</label>
                                    <input type="number" step=".01" class="form-control selling-price-php" name="SellingPricePhp[]" value="0">
                                </div>
                                <div class="form-group">
                                    <label>Selling Price + 12% VAT (PHP)</label>
                                    <input type="number" step=".01" class="form-control selling-price-vat" name="SellingPriceVat[]" value="0">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <button type="button" class="btn btn-primary newAddFormPrfProductRowBtn"><i class="ti ti-plus"></i></button> 
                            </div>
                        </div>`;

        $('.add_create_prf_form').last().find('.newAddFormPrfProductRowBtn').hide();
        
        $('.add_create_prf_form').last().after(newProductForm);
        $('.js-example-basic-single').select2();
        $('.add_create_prf_form').last().find('.deletePrfBtn').removeAttr('hidden');
    }

    $(document).on('click', '.addPrfProductRowBtn', function() {
        addProductRow();
        $('.addPrfProductRowBtn').hide(); 
    });

    $(document).on('click', '.newAddFormPrfProductRowBtn', function() {
        addProductRow();
    });
    });

    $(document).on('click', '.deletePrfBtn', function() {
        var currentRow = $(this).closest('.add_create_prf_form');
        
        if ($('.add_create_prf_form').last().is(currentRow)) {
            currentRow.prev().find('.addPrfProductRowBtn').show();
        }

        currentRow.remove();

        if ($('.add_create_prf_form').length === 1) {
            $('.addPrfProductRowBtn').show();
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        var validityDateInput = document.querySelector('.ValidityDate');
        var dateRequestedInput = document.querySelector('.DateRequested');

        var today = new Date().toISOString().split('T')[0];

            validityDateInput.setAttribute('min', today);
            dateRequestedInput.value = today;
    });
    
  
    $(document).ready(function(){
        @if ($errors->any())
            $('#AddPriceMonitoringLs').modal('show');
        @endif
    });
</script>
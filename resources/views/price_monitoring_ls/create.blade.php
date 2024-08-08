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
                <form method="POST" enctype="multipart/form-data" action="{{ url('local_price_monitoring') }}">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Primary Sales Person</label>
                                <select class="form-control js-example-basic-single" name="PrimarySalesPersonId" id="PrimarySalesPersonId" style="position: relative !important" title="Select Sales Person">
                                    <option value="" disabled selected>Select Sales Person</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->user_id }}">{{ $user->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Secondary Sales Person</label>
                                <select class="form-control js-example-basic-single" name="SecondarySalesPersonId" id="SecondarySalesPersonId" style="position: relative !important" title="Select Sales Person">
                                    <option value="" disabled selected>Select Sales Person</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->user_id }}">{{ $user->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Date Requested (DD/MM/YYYY)</label>
                                <input type="date" class="form-control" name="DateRequested">
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
                                <input type="date" class="form-control" name="ValidityDate" placeholder="Enter Validity">
                            </div>
                            <div class="form-group">
                                <label>Moq</label>
                                <input type="text" class="form-control" name="Moq" placeholder="Enter Moq">
                            </div>
                            <div class="form-group">
                                <label>Shelf Life</label>
                                <input type="text" class="form-control" name="ShelfLife" placeholder="Enter Shelf Life">
                            </div>
                            <div class="form-group">
                                <label>With Commission?</label>
                                <input type="checkbox" name="WithCommission">
                            </div>
                            <div class="form-group">
                                <label >Enter Commission</label>
                                <input type="text" class="form-control" name="EnterCommission" placeholder="Enter Commission">
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
                            <div class="form-group">
                                <label>Payment Term</label>
                                <input type="text" class="form-control payment-term" name="PaymentTerm" placeholder="" readonly>
                            </div>
                            {{-- <div class="form-group">
                                <label>Payment Term</label>
                                <select class="form-control js-example-basic-single" name="PaymentTerm" style="position: relative !important" title="Select Payment Term">
                                    <option value="" disabled selected>Select Payment Term</option>
                                    @foreach($payment_terms as $paymentTerm)
                                        <option value="{{ $paymentTerm->id }}">{{ $paymentTerm->Name }}</option>
                                    @endforeach
                                </select>
                            </div> --}}
                            <div class="form-group">
                                <label>Other Cost Requirement</label>
                                <input type="number" step=".01" class="form-control" name="OtherCostRequirement" placeholder="Enter Other Cost Requirement">
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
                        </div>
                        <div class="col-lg-12"><hr style="background-color: black"></div>
                        <div class="row col-lg-12">
                            <div class="col-lg-4">
                                <div><label>PRODUCT</label></div>
                                <div class="form-group">
                                    <label>Product</label>
                                    <select class="form-control js-example-basic-single" name="Product" id="product-select" style="position: relative !important" title="Select Product" required>
                                        <option value="" disabled selected>Select Product</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                     <label>Category</label>
                                     <select class="form-control js-example-basic-single" name="Type"  style="position: relative !important" title="Select Category">
                                        <option value="" disabled selected>Select Category</option>
                                        <option value="1">Pure</option>
                                        <option value="2">Blend</option>
                                     </select>
                                </div>
                                <div class="form-group">
                                    <label>Application:</label>
                                    <select class="form-control js-example-basic-single" name="ApplicationId" style="position: relative !important" title="Select Application" required>
                                        <option value="" disabled selected>Select Application</option>
                                        @foreach ($productApplications as $application)
                                            <option value="{{ $application->id }}" >{{ $application->Name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Quantity Required</label>
                                    <input type="number" class="form-control" name="QuantityRequired" value="0">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div><label>MANUFACTURING COST</label></div>
                                <div class="form-group">
                                    <label>RMC (PHP)</label>
                                    <input type="number" class="form-control" name="Rmc" id="rmc-input" value="0" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Direct Labor</label>
                                    <input type="number" class="form-control" name="DirectLabor" id="direct-labor-input" value="2.16" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Factory Overhead</label>
                                    <input type="number" class="form-control" name="FactoryOverhead" id="factory-overhead-input" value="24.26" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Total Manufacturing Cost</label>
                                    <input type="number" class="form-control" name="TotalManufacturingCost" id="total-manufacturing-cost-input" value="0" readonly>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-12"><hr style="background-color: rgb(219, 209, 209) !important"></div>
                                </div>
                                <div class="form-group">
                                    <label>Blending Loss:</label>
                                    <input type="number" class="form-control" name="BlendingLoss" id="blending-loss" value="0" readonly>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div><label>OPERATING COST</label></div>
                                <div class="form-group">
                                    <label>Delivery Type</label>
                                    <select class="form-control js-example-basic-single" name="DeliveryType" id="delivery-type" style="position: relative !important" title="Select Delivery Type">
                                        <option value="10">Courier</option>
                                        <option value="20">Delivery</option>
                                        <option value="30">Pickup</option>
                                     </select>
                                </div>
                                <div class="form-group">
                                    <label>Delivery Cost</label>
                                    <input type="number" class="form-control" name="DeliveryCost" id="delivery-cost" value="0">
                                </div>
                                <div class="form-group">
                                    <label>Financing Cost</label>
                                    <input type="number" class="form-control" name="FinancingCost" id="financing-cost" value="0" readonly>
                                </div>
                                <div class="form-group">
                                    <label>GAE Type:</label>
                                    <select class="form-control js-example-basic-single PriceGae" name="PriceGae" style="position: relative !important" title="Select GAE Type">
                                        @foreach ($pricegaes as $gaeType)
                                            <option value="{{ $gaeType->id }}" >{{ $gaeType->ExpenseName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>GAE Cost</label>
                                    <input type="number" class="form-control GaeCost" name="GaeCost" value="0" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Total Operating Cost</label>
                                    <input type="number" class="form-control total-operation-cost" name="TotalOperatingCost" value="0" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12"><hr style="background-color: rgb(219, 209, 209) !important"></div>
                        <div class="row col-lg-12">
                            <div class="col-lg-4">
                                <div><label>PRODUCT COST</label></div>
                                <div class="form-group">
                                    <label>Total Product Cost (PHP)</label>
                                    <input type="number" class="form-control total-product-cost" name="TotalProductCost" value="0" readonly>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div><label>MARKUP COST</label></div>
                                <div class="form-group">
                                    <label>Markup (%)</label>
                                    <input type="number" step=".01" class="form-control markup-percent" name="MarkupPercent" value="0">
                                </div>
                                <div class="form-group">
                                    <label>Markup (PHP)</label>
                                    <input type="number" step=".01" class="form-control markup-php" name="MarkupPhp" value="0">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div><label>SELLING PRICE</label></div>
                                <div class="form-group">
                                    <label>Selling Price (PHP)</label>
                                    <input type="number" step=".01" class="form-control selling-price-php" name="SellingPricePhp" value="0">
                                </div>
                                <div class="form-group">
                                    <label>Selling Price + 12% VAT (PHP)</label>
                                    <input type="number" step=".01" class="form-control selling-price-vat" name="SellingPriceVat" value="0">
                                </div>
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
                        $('.payment-term').val(data.PaymentTerm);
                    }
                });
            } else {
                $('#ClientContactId').empty();
                $('.GaeCost').val("");
            }
        });

        function fetchGaeCost(priceGae) {
            if(priceGae) {
                $.ajax({
                    url: '{{ url("getGaeCost") }}/' + priceGae,
                    type: "GET",
                    dataType: "json",
                    success:function(data) {
                        $('.GaeCost').val(data.Cost);
                        updateTotalOperationCost();
                        updateTotalProductCost();
                    }
                });
            } else {
                $('.GaeCost').val(0);
            }
        }

        var initialGae = $('.PriceGae').val();
        fetchGaeCost(initialGae);

        $('.PriceGae').change(function() {
            var selectedGae = $(this).val();
            fetchGaeCost(selectedGae);
        });

        $('#product-select').on('change', function() {
        var productId = $(this).val();
        if (productId) {
            $.ajax({
                url: '{{ url("product-rmc") }}/' + productId,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#rmc-input').val(data.rmc);
                    var directLabor = parseFloat($('#direct-labor-input').val());
                    var factoryOverhead = parseFloat($('#factory-overhead-input').val());
                    var rmc = parseFloat(data.rmc);
                    var totalManufacturingCost = rmc + directLabor + factoryOverhead;
                    $('#total-manufacturing-cost-input').val(totalManufacturingCost.toFixed(2));
                    var blendingLoss = 0.01 * rmc ;
                    $('#blending-loss').val(blendingLoss.toFixed(2));
                    var financingCost = 0.15 * totalManufacturingCost ;
                    $('#financing-cost').val(financingCost.toFixed(2));

                    updateTotalOperationCost();
                    updateTotalProductCost();

                },
                error: function() {
                    alert("Failed to fetch RMC value.");
                }
            });
        }
    });

    $('#delivery-type').on('change', function() {
            var deliveryType = $(this).val();
            var deliveryCostInput = $('#delivery-cost');
            
            if (deliveryType === '10') {
                deliveryCostInput.val(0);
                deliveryCostInput.prop('readonly', true);
            } else if (deliveryType === '20') {
                deliveryCostInput.val(1.84);
                deliveryCostInput.prop('readonly', true);
            } else if (deliveryType === '30') {
                deliveryCostInput.val(0);
                deliveryCostInput.prop('readonly', false);
            }
            updateTotalOperationCost();
            updateTotalProductCost();

        });

        $('#delivery-type').trigger('change');
    });

    function updateTotalOperationCost() {
    var deliveryCost = parseFloat($('#delivery-cost').val());
    var financingCost = parseFloat($('#financing-cost').val());
    var gaeCost = parseFloat($('.GaeCost').val()); 
    
    var totalOperationCost = deliveryCost + financingCost + gaeCost;
    $('.total-operation-cost').val(totalOperationCost.toFixed(2)); 

    updateTotalProductCost();

}

$('#delivery-cost').on('input', function() {
    updateTotalOperationCost();
    updateTotalProductCost();
});

function updateTotalProductCost() {
    var totalmanufacturing = parseFloat($('#total-manufacturing-cost-input').val());
    var totaloperating = parseFloat($('.total-operation-cost').val());
    var blendingloss = parseFloat($('#blending-loss').val()); 
    
    var totalproductcost = totalmanufacturing + totaloperating + blendingloss;
    $('.total-product-cost').val(totalproductcost.toFixed(2)); 

    updateMarkupPHP();
    updateMarkupPercent();
}
function updateSellingPrice() {
    var totalProductCost = parseFloat($('.total-product-cost').val());
    var markupPHP = parseFloat($('.markup-php').val());

    if (!isNaN(totalProductCost) && !isNaN(markupPHP)) {
        var sellingPrice = totalProductCost + markupPHP;
        $('.selling-price-php').val(sellingPrice.toFixed(2));
    }
}

function updateSellingPriceWithVAT() {
    var sellingPrice = parseFloat($('.selling-price-php').val());

    if (!isNaN(sellingPrice)) {
        var sellingPriceWithVAT = sellingPrice + (sellingPrice * 0.12);
        $('.selling-price-vat').val(sellingPriceWithVAT.toFixed(2));
    }
}

function updateMarkupPHP() {
        var totalProductCost = parseFloat($('.total-product-cost').val());
        var markupPercent = parseFloat($('.markup-percent').val());

        if (!isNaN(totalProductCost) && !isNaN(markupPercent)) {
            var markupPHP = (markupPercent / 100) * totalProductCost;
            $('.markup-php').val(markupPHP.toFixed(2));
            updateSellingPrice();
            updateSellingPriceWithVAT();
        }
    }

    function updateMarkupPercent() {
        var totalProductCost = parseFloat($('.total-product-cost').val());
        var markupPHP = parseFloat($('.markup-php').val());

        if (!isNaN(totalProductCost) && !isNaN(markupPHP)) {
            var markupPercent = (markupPHP / totalProductCost) * 100;
            $('.markup-percent').val(markupPercent.toFixed(2));
            updateSellingPrice();
            updateSellingPriceWithVAT();
        }
    }

    $('.markup-percent').on('input', function() {
        updateMarkupPHP();
    });

    $('.markup-php').on('input', function() {
        updateMarkupPercent();
    });

    $('.selling-price-php').on('input', function() {
        var sellingPrice = parseFloat($(this).val());
        var totalProductCost = parseFloat($('.total-product-cost').val());

        if (!isNaN(sellingPrice) && !isNaN(totalProductCost)) {
            var markupPHP = sellingPrice - totalProductCost;
            var markupPercent = (markupPHP / totalProductCost) * 100;
            var sellingPriceWithVAT = sellingPrice + (sellingPrice * 0.12);

            $('.markup-php').val(markupPHP.toFixed(2));
            $('.markup-percent').val(markupPercent.toFixed(2));
            $('.selling-price-vat').val(sellingPriceWithVAT.toFixed(2));
        }
    });

    $('.selling-price-vat').on('input', function() {
        var sellingPriceWithVAT = parseFloat($(this).val());
        var totalProductCost = parseFloat($('.total-product-cost').val());

        if (!isNaN(sellingPriceWithVAT) && !isNaN(totalProductCost)) {
            var sellingPrice = sellingPriceWithVAT / 1.12;
            var markupPHP = sellingPrice - totalProductCost;
            var markupPercent = (markupPHP / totalProductCost) * 100;

            $('.selling-price-php').val(sellingPrice.toFixed(2));
            $('.markup-php').val(markupPHP.toFixed(2));
            $('.markup-percent').val(markupPercent.toFixed(2));
        }
    });
    
</script>
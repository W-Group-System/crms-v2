<div class="modal fade" id="prfEdit{{ $price_monitorings->id }}" tabindex="-1" role="dialog" aria-labelledby="editPriceMonitoring" aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editPriceMonitoringLabel">Update Price Request</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" action="{{ url('price_monitoring_local/edit/' . $price_monitorings->id) }}" onsubmit="show()">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                {{-- <label>Primary Sales Person</label>
                                @if(auth()->user()->role->name == "Staff L1")
                                <input type="hidden" name="PrimarySalesPersonId" value="{{auth()->user()->id}}">
                                <input type="text" class="form-control" value="{{auth()->user()->full_name}}" readonly>
                                @elseif (auth()->user()->role->name == "Staff L2" || auth()->user()->role->name == "Department Admin")
                                @php
                                    $subordinates = getUserApprover(auth()->user()->getSalesApprover);
                                @endphp
                                <select class="form-control js-example-basic-single" name="PrimarySalesPersonId" style="position: relative !important" title="Select Sales Person">
                                    <option value="" disabled selected>Select Sales Person</option>
                                    @foreach($subordinates as $user)
                                        <option value="{{ $user->id }}" @if($user->user_id == $price_monitorings->PrimarySalesPersonId || $user->id == $price_monitorings->PrimarySalesPersonId) selected @endif>{{ $user->full_name }}</option>
                                    @endforeach
                                </select>
                                @endif --}}
                                {{-- @php
                                    $primary_sales = "";
                                    if ($price_monitorings->primarySalesPersonById == null)
                                    {
                                        $primary_sales = $price_monitorings->primarySalesPerson;
                                    }
                                    else
                                    {
                                        $primary_sales = $price_monitorings->primarySalesPersonById;
                                    }
                                @endphp
                                <label>Primary Sales Person</label>
                                <input type="hidden" name="PrimarySalesPersonId" value="{{$primary_sales->id}}">
                                <input type="text" class="form-control" value="{{$primary_sales->full_name}}" readonly> --}}
                                <select class="form-control js-example-basic-single" name="PrimarySalesPersonId" style="position: relative !important" title="Select Sales Person" required>
                                    <option value="" disabled selected>Select Sales Person</option>
                                    @foreach($loggedInUser->groupSales as $group_sales)
                                        @php
                                            $user = $group_sales->user;
                                        @endphp
                                        <option value="{{ $user->id }}" @if($user->id == $price_monitorings->PrimarySalesPersonId || $user->user_id == $price_monitorings->PrimarySalesPersonId) selected @endif>{{ $user->full_name }}</option>
                                    @endforeach
                                </select> 
                            </div>
                            <div class="form-group">
                                <label>Secondary Sales Person</label>
                                {{-- <select class="form-control js-example-basic-single" name="SecondarySalesPersonId"  style="position: relative !important" title="Select Sales Person">
                                    <option value="" disabled selected>Select Sales Person</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->user_id }}" @if ($price_monitorings->SecondarySalesPersonId == $user->user_id) selected @endif>{{ $user->full_name }}</option>
                                        <option value="{{ $user->id }}" @if($user->user_id == $price_monitorings->SecondarySalesPersonId || $user->id == $price_monitorings->SecondarySalesPersonId) selected @endif>{{ $user->full_name }}</option>
                                    @endforeach
                                </select> --}}
                                {{-- @php
                                    $secondary_sales = "";
                                    if ($price_monitorings->secondarySalesPersonById == null)
                                    {
                                        $secondary_sales = $price_monitorings->secondarySalesPerson;
                                    }
                                    else
                                    {
                                        $secondary_sales = $price_monitorings->secondarySalesPersonById;
                                    }
                                @endphp
                                @if($price_monitorings->SecondarySalesPersonId == auth()->user()->id || $price_monitorings->SecondarySalesPersonId == auth()->user()->user_id)
                                <input type="hidden" name="SecondarySalesPersonId" value="{{$secondary_sales->id}}">
                                <input type="text" class="form-control" value="{{$secondary_sales->full_name}}" readonly>
                                @else
                                <select class="form-control js-example-basic-single" name="SecondarySalesPersonId" style="position: relative !important" title="Select Sales Person" required>
                                    <option value="" disabled selected>Select Sales Person</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" @if($user->id == $price_monitorings->SecondarySalesPersonId || $user->user_id == $price_monitorings->SecondarySalesPersonId) selected @endif>{{ $user->full_name }}</option>
                                    @endforeach
                                </select> 
                                @endif --}}
                                <select class="form-control js-example-basic-single" name="SecondarySalesPersonId" style="position: relative !important" title="Select Sales Person" required>
                                    <option value="" disabled selected>Select Sales Person</option>
                                    @foreach($loggedInUser->groupSales as $group_sales)
                                        @php
                                            $user = $group_sales->user;
                                        @endphp
                                        <option value="{{ $user->id }}" @if($user->id == $price_monitorings->SecondarySalesPersonId || $user->user_id == $price_monitorings->SecondarySalesPersonId) selected @endif>{{ $user->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Date Requested (DD/MM/YYYY)</label>
                                <input type="datetime" class="form-control" name="DateRequested" value="{{ !empty($price_monitorings->DateRequested) ? date('Y-m-d  ', strtotime($price_monitorings->DateRequested)) : '' }}" readonly>
                            </div>
                        </div>
                        <div class="col-lg-12"><hr style="background-color: black"></div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Client</label>
                                <select class="form-control js-example-basic-single PrfEditClientId PrfEditClient{{ $price_monitorings->id }}" name="ClientId"  style="position: relative !important" title="Select Client" required>
                                    <option value="" disabled selected>Select Client</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" @if ($price_monitorings->ClientId == $client->id) selected @endif>{{ $client->Name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Contact:</label>
                                <select class="form-control js-example-basic-single" name="ClientContactId" id="PrfEditContactClientId{{ $price_monitorings->id }}" style="position: relative !important" title="Select ClientContacId" required>
                                    <option value="" disabled selected>Select Contact</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Validity Date</label>
                                <input type="date" class="form-control ValidityDate{{ $price_monitorings->id }}" name="ValidityDate"  value="{{ !empty($price_monitorings->ValidityDate) ? date('Y-m-d', strtotime($price_monitorings->ValidityDate)) : '' }}" >
                            </div>
                            <div class="form-group">
                                <label>Packaging Type</label>
                                <input type="text" class="form-control" name="PackagingType" value="{{ $price_monitorings->PackagingType }}" placeholder="Enter Packaging Type">
                            </div>
                            <div class="form-group">
                                <label>MOQ</label>
                                <input type="text" class="form-control" name="Moq" value="{{ $price_monitorings->Moq }}">
                            </div>
                            <div class="form-group">
                                <label>Shelf Life</label>
                                <input type="text" class="form-control" name="ShelfLife" value="{{ $price_monitorings->ShelfLife ?? "2 Years"}}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Shipment Term</label>
                                <input type="text" class="form-control" name="ShipmentTerm" value="{{ $price_monitorings->ShipmentTerm}}">
                            </div>
                            <div class="form-group">
                                <label>Destination</label>
                                <input type="text" class="form-control" name="Destination" value="{{ $price_monitorings->Destination}}">
                            </div>
                            {{-- <div class="form-group">
                                <label>Payment Term</label>
                                <input type="text" class="form-control payment-term" name="PaymentTerm" value="{{ $price_monitorings->PaymentTermId}}" readonly>
                            </div> --}}
                            <div class="form-group">
                                <label>Payment Term</label>
                                <select class="form-control js-example-basic-single" name="PaymentTerm" style="position: relative !important" title="Select Payment Term">
                                    <option value="" disabled selected>Select Payment Term</option>
                                    @foreach($payment_terms as $paymentTerm)
                                        <option value="{{ $paymentTerm->id }}" @if ($price_monitorings->PaymentTermId == $paymentTerm->id) selected @endif>{{ $paymentTerm->Name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Purpose of Price Request</label>
                                <select class="form-control js-example-basic-single" name="PriceRequestPurpose"  style="position: relative !important" title="Select Purpose">
                                   <option value="" @if ($price_monitorings->PriceRequestPurpose == '') selected @endif disabled selected>Select Purpose</option>
                                   <option value="10" @if ($price_monitorings->PriceRequestPurpose == '10') selected @endif>Indication</option>
                                   <option value="20" @if ($price_monitorings->PriceRequestPurpose == '20') selected @endif>Firm</option>
                                   <option value="30" @if ($price_monitorings->PriceRequestPurpose == '30') selected @endif>Sample</option>
                                </select>
                           </div>
                           <div class="form-group">
                            <label>Delivery Schedule</label>
                            <input type="text" class="form-control" name="DeliverySchedule" value="{{ $price_monitorings->PriceLockPeriod }}">
                            </div>
                            <div class="form-group">
                                <label>Tax Type</label>
                                <select class="form-control js-example-basic-single" name="TaxType"  style="position: relative !important" title="Select Tax Type">
                                   <option value="10" @if ($price_monitorings->TaxType == '10') selected @endif>VAT Inclusive</option>
                                   <option value="20" @if ($price_monitorings->TaxType == '20') selected @endif>VAT Exclusive</option>
                                </select>
                           </div>
                            <div class="form-group">
                                <label>Other Remarks</label>
                                <input type="text" class="form-control" name="OtherRemarks" placeholder="Input Other Remarks" value="{{ $price_monitorings->OtherRemarks }}">
                            </div>
                        </div>
                        <div class="col-lg-12"><hr style="background-color: black"></div>
                        <div class="prfForm{{ $price_monitorings->id }}">
                            @foreach ($price_monitorings->requestPriceProducts as $index => $priceProducts)
                            <div class="create_prf_form{{ $price_monitorings->id }} col-lg-12 row">
                                <div class="create_prf_forms{{ $priceProducts->id }} col-lg-12 row" data-row-index="{{ $index }}">
                                    <div class="col-lg-12">
                                        <button type="button" class="btn btn-danger delete-product" data-id="{{ $priceProducts->id }}" style="float: right;">Delete</button>
                                    </div>
                                    <div class="col-lg-4">
                                        <div><label>PRODUCT</label></div>
                                        <div class="form-group">
                                            <label>Product</label>
                                            <input type="hidden" class="form-control" name="product_id[]" value="{{ $priceProducts->id }}">
                                            <select class="form-control js-example-basic-single product-select" name="Product[]" style="position: relative !important" title="Select Product" required>
                                                <option value="" disabled selected>Select Product</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}" data-type="{{ $product->type }}" data-application_id="{{ $product->application_id }}" @if ($priceProducts->ProductId == $product->id) selected @endif>{{ $product->code }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Category</label>
                                            <select class="form-control js-example-basic-single category-select" name="Type[]"  style="position: relative !important" title="Select Category" required>
                                                <option value="" disabled @if ($priceProducts->Type == '') selected @endif>Select Category</option>
                                                <option value="1" @if ($priceProducts->Type == '1') selected @endif>Pure</option>
                                                <option value="2" @if ($priceProducts->Type == '2') selected @endif>Blend</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Application:</label>
                                            <select class="form-control js-example-basic-single application-select" name="ApplicationId[]" style="position: relative !important" title="Select Application" required>
                                                <option value="" disabled selected>Select Application</option>
                                                @foreach ($productApplications as $application)
                                                    <option value="{{ $application->id }}" @if ($priceProducts->ApplicationId == $application->id) selected @endif>{{ $application->Name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Quantity Required</label>
                                            <input type="text" class="form-control" name="QuantityRequired[]" 
                                                value="{{($priceProducts->QuantityRequired ?? 0) }}" 
                                                oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, ',');">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div><label>MANUFACTURING COST</label></div>
                                        <div class="form-group">
                                            <label>RMC (PHP)</label>
                                            <input type="text" class="form-control rmc-input" name="Rmc[]" value="{{ $priceProducts->ProductRmc ?? 0 }}" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Direct Labor</label>
                                            <input type="text" class="form-control direct-labor-input" name="DirectLabor[]" value="2.16" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Factory Overhead</label>
                                            <input type="text" class="form-control factory-overhead-input" name="FactoryOverhead[]" value="24.26" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Total Manufacturing Cost</label>
                                            <input type="text" class="form-control total-manufacturing-cost-input" name="TotalManufacturingCost[]" value="0" readonly>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-lg-12"><hr style="background-color: rgb(219, 209, 209) !important"></div>
                                        </div>
                                        <div class="form-group">
                                            <label>Blending Loss:</label>
                                            <input type="text" class="form-control blending-loss" name="BlendingLoss[]"  value="0" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        {{-- {{dd($price_monitorings)}} --}}
                                        <div><label>OPERATING COST</label></div>
                                        <div class="form-group">
                                            <label>Delivery Type</label>
                                            <select class="form-control js-example-basic-single delivery-type" name="DeliveryType[]" title="Select Delivery Type">
                                                <option value="10" @if($priceProducts->LsalesDeliveryType == 10) selected @endif>Courier</option>
                                                <option value="20" @if($priceProducts->LsalesDeliveryType == 20) selected @endif>Delivery</option>
                                                <option value="30" @if($priceProducts->LsalesDeliveryType == 30) selected @endif>Pickup</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Delivery Cost</label>
                                            <input type="text" class="form-control delivery-cost" name="DeliveryCost[]" value="0">
                                        </div>
                                        <div class="form-group">
                                            <label>Financing Cost</label>
                                            <input type="text" class="form-control financing-cost" name="FinancingCost[]" value="0" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>GAE Type:</label>
                                            <select class="form-control js-example-basic-single PriceGae" name="PriceGae[]" title="Select GAE Type">
                                                @foreach ($pricegaes as $gaeType)
                                                    <option value="{{ $gaeType->id }}" @if($priceProducts->PriceRequestGaeId == $gaeType->id) selected @endif>{{ $gaeType->ExpenseName }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>GAE Cost</label>
                                            <input type="text" class="form-control GaeCost" name="GaeCost[]" value="0" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Other Cost Requirement</label>
                                            <input type="text" class="form-control other-cost" name="OtherCostRequirement[]" placeholder="Enter Other Cost Requirement" value="{{  $priceProducts->OtherCostRequirements ?? 0  }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Total Operating Cost</label>
                                            <input type="text" class="form-control total-operation-cost" name="TotalOperatingCost[]" value="0" readonly>
                                        </div>
                                    </div>
                                <div class="col-lg-12"><hr style="background-color: rgb(219, 209, 209) !important"></div>
                                    <div class="col-lg-4">
                                        <div><label>PRODUCT COST</label></div>
                                        <div class="form-group">
                                            <label>Total Product Cost (PHP)</label>
                                            <input type="text" class="form-control total-product-cost" name="TotalProductCost[]" value="0" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div><label>MARKUP COST</label></div>
                                        <div class="form-group">
                                            <label>Markup (%)</label>
                                            <input type="text" class="form-control markup-percent" name="MarkupPercent[]" value="{{ $priceProducts->LsalesMarkupPercent ?? 0 }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Markup (PHP)</label>
                                            <input type="text" class="form-control markup-php" name="MarkupPhp[]" value="{{ $priceProducts->LsalesMarkupValue ?? 0 }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div><label>SELLING PRICE</label></div>
                                        <div class="form-group">
                                            <label>Selling Price (PHP)</label>
                                            <input type="text" class="form-control selling-price-php" name="SellingPricePhp[]" value="0">
                                        </div>
                                        <div class="form-group">
                                            <label>Selling Price + 12% VAT (PHP)</label>
                                            <input type="text" class="form-control selling-price-vat" name="SellingPriceVat[]" value="0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                            <div class="col-lg-12">
                                <button type="button" class="btn btn-primary addPrfProductRowBtn{{ $price_monitorings->id }}" id="addPrfProductRowBtn{{ $price_monitorings->id }}" style="float: left; margin:5px;"><i class="ti ti-plus"></i></button> 
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
        var price_monitoringsId = '{{ $price_monitorings->id }}';
        var clientIdSelector = '.PrfEditClient' + price_monitoringsId;
        var contactIdSelector = '#PrfEditContactClientId' + price_monitoringsId;

        var storedClientId = $(clientIdSelector).val();

        if(storedClientId) {
            $.ajax({
                url: '{{ url("client-contact") }}/' + storedClientId,
                type: "GET",
                dataType: "json",
                success:function(data) {
                    $(contactIdSelector).empty();
                    $(contactIdSelector).append('<option value="" disabled>Select Contact</option>');
                    $.each(data, function(key, value) {
                        $(contactIdSelector).append('<option value="'+ key +'">'+ value +'</option>');
                    });

                    var storedClientContactId = '{{ $price_monitorings->ContactId }}';
                    if(storedClientContactId) {
                        $(contactIdSelector).val(storedClientContactId);
                    }
                }
            });

            $.ajax({
                url: '{{ url("get-payment-term") }}/' + storedClientId,
                type: "GET",
                dataType: "json",
                success:function(data) {
                    $('.payment-term').val(data.PaymentTerm);
                }
            });
        }

        $(clientIdSelector).change(function() {
        var clientId = $(this).val();
        if(clientId) {
            $.ajax({
                url: '{{ url("client-contact") }}/' + clientId,
                type: "GET",
                dataType: "json",
                success:function(data) {
                    $(contactIdSelector).empty();
                    $(contactIdSelector).append('<option value="" disabled selected>Select Contact</option>');
                    $.each(data, function(key, value) {
                        $(contactIdSelector).append('<option value="'+ key +'">'+ value +'</option>');
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
            $(contactIdSelector).empty();
            $('.payment-term').val("");
        }
    });
    
    // Update 10/28/24

$(document).ready(function() {
    var $initialRow = $('.create_prf_form{{ $price_monitorings->id }}');
   var initialGae = $initialRow.find('.PriceGae').val();
   fetchGaeCost(initialGae, $initialRow);

   $(document).on('change', '.PriceGae', function() {
       var $row = $(this).closest('.create_prf_form{{ $price_monitorings->id }}');
       var priceGae = $(this).val();
       fetchGaeCost(priceGae, $row);
   });

   function fetchGaeCost(priceGae, $row) {
   if (priceGae) {
       $.ajax({
           url: '{{ url("getGaeCost") }}/' + priceGae,
           type: "GET",
           dataType: "json",
           success: function(data) {
               $row.find('.GaeCost').val(data.Cost);
               updateTotalOperationCost();
               updateTotalProductCost();
           }
       });
   } else {
       $row.find('.GaeCost').val(0);
   }
}

    function calculateCosts($row, rmc) {
        var directLabor = parseFloat($row.find('.direct-labor-input').val());
        var factoryOverhead = parseFloat($row.find('.factory-overhead-input').val());
        var totalMC = rmc + directLabor + factoryOverhead;
        var totalManufacturingCost = Math.round((totalMC + Number.EPSILON) * 100) / 100;
        $row.find('.total-manufacturing-cost-input').val(totalManufacturingCost.toFixed(2));
        
        var blendingLoss = 0.01 * rmc;
        $row.find('.blending-loss').val(blendingLoss.toFixed(2));
        
        var financingCost = 0.05 * totalManufacturingCost;
        $row.find('.financing-cost').val(financingCost.toFixed(2));
        
        updateTotalOperationCost($row);
        updateTotalProductCost($row);
    }

    $(document).on('change', '.product-select', function() {
        var $row = $(this).closest('.create_prf_form{{ $price_monitorings->id }}');
        var productId = $(this).val();
        
        if (productId) {
            $.ajax({
                url: '{{ url("product-rmc") }}/' + productId,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $row.find('.rmc-input').val(data.rmc);
                    calculateCosts($row, parseFloat(data.rmc));
                },
                error: function() {
                    alert("Failed to fetch RMC value.");
                }
            });
        }
    });

    $('.product-select').each(function() {
        var $row = $(this).closest('.create_prf_form{{ $price_monitorings->id }}');
        var rmc = parseFloat($row.find('.rmc-input').val());
        
        if (!isNaN(rmc) && rmc > 0) {
            calculateCosts($row, rmc);
        }
    });

    function updateTotalOperationCost() {
    $('.create_prf_form{{ $price_monitorings->id }}').each(function() {
        var $row = $(this);
        var deliveryCost = parseFloat($row.find('.delivery-cost').val()) || 0;
        var financingCost = parseFloat($row.find('.financing-cost').val()) || 0;
        var gaeCost = parseFloat($row.find('.GaeCost').val()) || 0; 
        var otherCost = parseFloat($row.find('.other-cost').val()) || 0;

        var totalOperationCost = deliveryCost + financingCost + gaeCost + otherCost;
        $row.find('.total-operation-cost').val(totalOperationCost);

        updateTotalProductCost($row); 
    });
}


function updateTotalProductCost() {
    $('.create_prf_form{{ $price_monitorings->id }}').each(function() {
        var $row = $(this);
        var totalManufacturing = parseFloat($row.find('.total-manufacturing-cost-input').val()) || 0;
        var totalOperating = parseFloat($row.find('.total-operation-cost').val()) || 0;
        var blendingLoss = parseFloat($row.find('.blending-loss').val()) || 0;

        var totalProductCost = totalManufacturing + totalOperating + blendingLoss;
        $row.find('.total-product-cost').val(totalProductCost); 

        updateMarkupPHP($row);
        updateSellingPrice($row);
        updateSellingPriceWithVAT($row);
    });
}


function updateSellingPrice($row) {
        var totalProductCost = parseFloat($row.find('.total-product-cost').val()) || 0;
        var markupPHP = parseFloat($row.find('.markup-php').val()) || 0;

        if (!isNaN(totalProductCost) && !isNaN(markupPHP)) {
            var sellingPrice = totalProductCost + markupPHP;
            $row.find('.selling-price-php').val(sellingPrice.toFixed(2));
            updateSellingPriceWithVAT($row);
        }
    }


    function updateSellingPriceWithVAT($row) {
        var sellingPrice = parseFloat($row.find('.selling-price-php').val()) || 0;

        if (!isNaN(sellingPrice)) {
            var sellingPriceWithVAT = sellingPrice * 1.12;
            $row.find('.selling-price-vat').val(sellingPriceWithVAT.toFixed(2));
        }
    }


   function updateMarkupPHP($row) {
        var totalProductCost = parseFloat($row.find('.total-product-cost').val()) || 0;
        var markupPercent = parseFloat($row.find('.markup-percent').val()) || 0;

        if (!isNaN(totalProductCost) && !isNaN(markupPercent)) {
            var markupPHP = (markupPercent / 100) * totalProductCost;
            $row.find('.markup-php').val(markupPHP.toFixed(2));
            updateSellingPrice($row);
            updateSellingPriceWithVAT($row);
        }
    }


    function updateMarkupPercent($row) {
        var totalProductCost = parseFloat($row.find('.total-product-cost').val()) || 0;
        var markupPHP = parseFloat($row.find('.markup-php').val()) || 0;

        if (!isNaN(totalProductCost) && !isNaN(markupPHP)) {
            var markupPercent = (markupPHP / totalProductCost) * 100;
            $row.find('.markup-percent').val(markupPercent.toFixed(2));
            updateSellingPrice($row);
            updateSellingPriceWithVAT($row);
        }
    }

    $(document).on('change', '.delivery-type', function() {
       var $row = $(this).closest('.create_prf_form{{ $price_monitorings->id }}');
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

       

   $(document).on('input', '.delivery-cost, .other-cost', function() {
       var $row = $(this).closest('.create_prf_form{{ $price_monitorings->id }}');
       updateTotalOperationCost($row);
       updateTotalProductCost($row);
   });

   
   $(document).on('input', '.markup-percent', function() {
       var $row = $(this).closest('.create_prf_form{{ $price_monitorings->id }}');
       updateMarkupPHP($row);
   });

   $(document).on('input', '.markup-php', function() {
       var $row = $(this).closest('.create_prf_form{{ $price_monitorings->id }}');
       updateMarkupPercent($row);
   });

   $(document).on('input', '.selling-price-php', function() {
       var $row = $(this).closest('.create_prf_form{{ $price_monitorings->id }}');
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
        var $row = $(this).closest('.create_prf_form{{ $price_monitorings->id }}');
        var sellingPriceWithVAT = parseFloat($(this).val()) || 0;
        var sellingPrice = sellingPriceWithVAT / 1.12;
        var totalProductCost = parseFloat($row.find('.total-product-cost').val()) || 0;
        
        if (!isNaN(sellingPrice) && !isNaN(totalProductCost)) {
            var markupPHP = sellingPrice - totalProductCost;
            var markupPercent = (markupPHP / totalProductCost) * 100;
            
            $row.find('.selling-price-php').val(sellingPrice.toFixed(2));
            $row.find('.markup-php').val(markupPHP.toFixed(2));
            $row.find('.markup-percent').val(markupPercent.toFixed(2));
        }
    });
    
});

  

//    $(document).on('input', '.selling-price-php', function() {
//         var $row = $(this).closest('.create_prf_form{{ $price_monitorings->id }}');
//         updateSellingPrice($row);
//         updateSellingPriceWithVAT($row);
//     });



//    $(document).on('input', '.selling-price-php', function() {
//        var $row = $(this).closest('.create_prf_form{{ $price_monitorings->id }}');
//        var sellingPrice = parseFloat($(this).val());
//        var totalProductCost = parseFloat($row.find('.total-product-cost').val());

//        if (!isNaN(sellingPrice) && !isNaN(totalProductCost)) {
//            var markupPHP = sellingPrice - totalProductCost;
//            var markupPercent = (markupPHP / totalProductCost) * 100;
//            var sellingPriceWithVAT = sellingPrice + (sellingPrice * 0.12);

//            $row.find('.markup-php').val(markupPHP.toFixed(2));
//            $row.find('.markup-percent').val(markupPercent.toFixed(2));
//            $row.find('.selling-price-vat').val(sellingPriceWithVAT.toFixed(2));
//        }
//    });

//    $(document).on('input', '.selling-price-vat', function() {
//        var $row = $(this).closest('.create_prf_form{{ $price_monitorings->id }}');
//        var sellingPriceWithVAT = parseFloat($(this).val());
//        var totalProductCost = parseFloat($row.find('.total-product-cost').val());

//        if (!isNaN(sellingPriceWithVAT) && !isNaN(totalProductCost)) {
//            var sellingPrice = sellingPriceWithVAT / 1.12;
//            var markupPHP = sellingPrice - totalProductCost;
//            var markupPercent = (markupPHP / totalProductCost) * 100;

//            $row.find('.selling-price-php').val(sellingPrice.toFixed(2));
//            $row.find('.markup-php').val(markupPHP.toFixed(2));
//            $row.find('.markup-percent').val(markupPercent.toFixed(2));
//        }
//    });

$(document).ready(function() {
    $('.create_prf_form{{ $price_monitorings->id }}').each(function() {
        var $row = $(this);
        updateTotalProductCost($row); 
    });
});


 
       function addProductRow() {
       var newProductForm = `
                       <div class="create_prf_form{{ $price_monitorings->id }} col-lg-12 row">
                          <div class="col-lg-12">
                                <button type="button" class="btn btn-danger editDeletePrfBtn" style="float: right;">Delete Row</button>
                            </div>
                           <div class="col-lg-4">
                               <div><label>PRODUCT</label></div>
                               <div class="form-group">
                                   <label>Product</label>
                                   <select class="form-control js-example-basic-single product-select" name="Product[]" style="position: relative !important" title="Select Product" required>
                                       <option value="" disabled selected>Select Product</option>
                                       @foreach($products as $product)
                                           <option value="{{ $product->id }}" data-type="{{ $product->type }}" data-application_id="{{ $product->application_id }}">{{ $product->code }}</option>
                                       @endforeach
                                   </select>
                               </div>
                               <div class="form-group">
                                    <label>Category</label>
                                    <select class="form-control js-example-basic-single category-select ProductType" name="Type[]"  style="position: relative !important" title="Select Category">
                                       <option value="" disabled selected>Select Category</option>
                                       <option value="1">Pure</option>
                                       <option value="2">Blend</option>
                                    </select>
                               </div>
                               <div class="form-group">
                                   <label>Application:</label>
                                   <select class="form-control js-example-basic-single application-select ApplicationId" name="ApplicationId[]" style="position: relative !important" title="Select Application" required>
                                       <option value="" disabled selected>Select Application</option>
                                       @foreach ($productApplications as $application)
                                           <option value="{{ $application->id }}" >{{ $application->Name }}</option>
                                       @endforeach
                                   </select>
                               </div>
                               <div class="form-group">
                                   <label>Quantity Required</label>
                                   <input type="text" class="form-control" name="QuantityRequired[]" value="0">
                               </div>
                           </div>
                           <div class="col-lg-4">
                               <div><label>MANUFACTURING COST</label></div>
                               <div class="form-group">
                                   <label>RMC (PHP)</label>
                                   <input type="text" class="form-control rmc-input" name="Rmc[]" value="0" readonly>
                               </div>
                               <div class="form-group">
                                   <label>Direct Labor</label>
                                   <input type="text" class="form-control direct-labor-input" name="DirectLabor[]" value="2.16" readonly>
                               </div>
                               <div class="form-group">
                                   <label>Factory Overhead</label>
                                   <input type="text" class="form-control factory-overhead-input" name="FactoryOverhead[]" value="24.26" readonly>
                               </div>
                               <div class="form-group">
                                   <label>Total Manufacturing Cost</label>
                                   <input type="text" class="form-control total-manufacturing-cost-input" name="TotalManufacturingCost[]" value="0" readonly>
                               </div>
                               <div class="form-group">
                                   <div class="col-lg-12"><hr style="background-color: rgb(219, 209, 209) !important"></div>
                               </div>
                               <div class="form-group">
                                   <label>Blending Loss:</label>
                                   <input type="text" class="form-control blending-loss" name="BlendingLoss[]"  value="0" readonly>
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
                                   <input type="text" class="form-control delivery-cost" name="DeliveryCost[]" value="0">
                               </div>
                               <div class="form-group">
                                   <label>Financing Cost</label>
                                   <input type="text" class="form-control financing-cost" name="FinancingCost[]" value="0" readonly>
                               </div>
                               <div class="form-group">
                                   <label>GAE Type:</label>
                                   <select class="form-control js-example-basic-single PriceGae" name="PriceGae[]" style="position: relative !important" title="Select GAE Type">
                                        <option value="" >Select GAE Type</option>
                                       @foreach ($pricegaes as $gaeType)
                                           <option value="{{ $gaeType->id }}" >{{ $gaeType->ExpenseName }}</option>
                                       @endforeach
                                   </select>
                               </div>
                               <div class="form-group">
                                   <label>GAE Cost</label>
                                   <input type="text" class="form-control GaeCost" name="GaeCost[]" value="0" readonly>
                               </div>
                               <div class="form-group">
                                    <label>Other Cost Requirement</label>
                                    <input type="text" class="form-control other-cost" name="OtherCostRequirement[]" placeholder="Enter Other Cost Requirement" value="0">
                                </div>
                               <div class="form-group">
                                   <label>Total Operating Cost</label>
                                   <input type="text" class="form-control total-operation-cost" name="TotalOperatingCost[]" value="0" readonly>
                               </div>
                           </div>
                       <div class="col-lg-12"><hr style="background-color: rgb(219, 209, 209) !important"></div>
                           <div class="col-lg-4">
                               <div><label>PRODUCT COST</label></div>
                               <div class="form-group">
                                   <label>Total Product Cost (PHP)</label>
                                   <input type="text" class="form-control total-product-cost" name="TotalProductCost[]" value="0" readonly>
                               </div>
                           </div>
                           <div class="col-lg-4">
                               <div><label>MARKUP COST</label></div>
                               <div class="form-group">
                                   <label>Markup (%)</label>
                                   <input type="text" class="form-control markup-percent" name="MarkupPercent[]" value="0">
                               </div>
                               <div class="form-group">
                                   <label>Markup (PHP)</label>
                                   <input type="text" class="form-control markup-php" name="MarkupPhp[]" value="0">
                               </div>
                           </div>
                           <div class="col-lg-4">
                               <div><label>SELLING PRICE</label></div>
                               <div class="form-group">
                                   <label>Selling Price (PHP)</label>
                                   <input type="text" class="form-control selling-price-php" name="SellingPricePhp[]" value="0">
                               </div>
                               <div class="form-group">
                                   <label>Selling Price + 12% VAT (PHP)</label>
                                   <input type="text" class="form-control selling-price-vat" name="SellingPriceVat[]" value="0">
                               </div>
                           </div>
                       </div>`;
                       $('.prfForm{{ $price_monitorings->id }}').append(newProductForm);
                       $('.ProductType, .product-select, .ApplicationId, .delivery-type, .PriceGae').select2();

   }

   $(document).on('click', '.addPrfProductRowBtn{{ $price_monitorings->id }}', function() {
        addProductRow();
    });

   $(document).on('click', '.editDeletePrfBtn', function() {
        $(this).closest('.create_prf_form{{ $price_monitorings->id }}').remove();
    });
   });

   document.addEventListener('DOMContentLoaded', function() {
        var validityDateInput = document.querySelector('.ValidityDate{{ $price_monitorings->id }}');
        var storedDate = '{{ !empty($price_monitorings->ValidityDate) ? date('Y-m-d', strtotime($price_monitorings->ValidityDate)) : '' }}';
        var today = new Date().toISOString().split('T')[0];

        if (storedDate) {
            validityDateInput.setAttribute('min', storedDate);
        } else {
            validityDateInput.setAttribute('min', today);
        }
    });

// $(document).ready(function() {
//     // $(document).off('click', '.delete-product');
// });
$(document).on('click', '.delete-product', function() {
    var productId = $(this).data('id'); 
    var row = $(this).closest('.create_prf_forms' + productId);
    var deleteButton = $(this);
    deleteButton.prop('disabled', true);

    $.ajax({
        url: "{{ url('delete-product') }}/" + productId,
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

$(document).ready(function() {
    function handleProductChange(event) {
        var $productSelect = $(event.target);
        var selectedOption = $productSelect.find('option:selected');
        var selectedType = selectedOption.data('type');
        var selectedApplicationId = selectedOption.data('application_id');

        var $row = $productSelect.closest('.create_prf_form{{ $price_monitorings->id }}');
        
        $row.find('.category-select').val(selectedType).change();
        $row.find('.application-select').val(selectedApplicationId).change();
    }

    $(document).on('change', '.product-select', handleProductChange);
});
</script>
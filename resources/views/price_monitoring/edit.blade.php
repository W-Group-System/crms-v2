<div class="modal fade" id="editPriceRequest{{  $priceMonitoring->id }}" tabindex="-1" role="dialog" aria-labelledby="editPriceRequest" aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editPriceRequestLabel">Price Request</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method="POST" action="{{ url('price_monitoring/edit/' . $priceMonitoring->id ) }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                        <span id="form_result"></span>
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Primary Sales Person</label>
                                    <select class="form-control js-example-basic-single" name="PrimarySalesPersonId" style="position: relative !important" title="Select Sales Person">
                                        <option value="" disabled selected>Select Sales Person</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->user_id }}" @if ( $priceMonitoring->PrimarySalesPersonId == $user->user_id) selected @endif>{{ $user->full_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Secondary Sales Person</label>
                                    <select class="form-control js-example-basic-single" name="SecondarySalesPersonId" style="position: relative !important" title="Select Sales Person">
                                        <option value="" disabled selected>Select Sales Person</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->user_id }}" @if ( $priceMonitoring->SecondarySalesPersonId == $user->user_id) selected @endif>{{ $user->full_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="name">Date Requested (DD/MM/YYYY)</label>
                                    <input type="date" class="form-control" name="DateRequested" value="{{ !empty($priceMonitoring->DateRequested) ? date('Y-m-d', strtotime($priceMonitoring->DateRequested)) : '' }}" readonly>
                                </div>
                            </div>
                            <div class="col-lg-12"><hr style="background-color: black"></div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Client</label>
                                    <select class="form-control js-example-basic-single EditClientId{{  $priceMonitoring->id }}" name="ClientId"  style="position: relative !important" title="Select Client" required>
                                        <option value="" disabled selected>Select Client</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}" @if ( $priceMonitoring->ClientId == $client->id) selected @endif>{{ $client->Name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="name">Country</label>
                                    <input type="text" class="form-control editCountryName{{  $priceMonitoring->id }}" name="CountryName" readonly>
                                    <input type="hidden" class="CountryId" name="CountryId">
                                </div>
                                <div class="form-group">
                                    <label for="name">Region</label>
                                    <input type="text" class="form-control editRegionName{{  $priceMonitoring->id }}" name="RegionName" readonly>
                                    <input type="hidden" class="RegionId" name="RegionId">
                                </div>
                            </div>
                            <div class="col-lg-12"><hr style="background-color: black"></div>
                           @foreach ( $priceMonitoring->requestPriceProducts as $priceProducts )
                           <div class="col-lg-6"><label>Computation</label>
                            <div class="form-group">
                                <input type="hidden" name="requestPriceId" value="{{ $priceProducts->Id }}">
                                <label>Purpose of Price Request</label>
                                <select class="form-control js-example-basic-single" name="PriceRequestPurpose"  style="position: relative !important" title="Select Purpose">
                                   <option value="" disabled @if ( $priceMonitoring->PriceRequestPurpose == "") selected @endif>Select Purpose</option>
                                   <option value="10" @if ( $priceMonitoring->PriceRequestPurpose == "10") selected @endif>Indication</option>
                                   <option value="20" @if ( $priceMonitoring->PriceRequestPurpose == "20") selected @endif>Firm</option>
                                   <option value="30" @if ( $priceMonitoring->PriceRequestPurpose == "30") selected @endif>Sample</option>
                                </select>
                           </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                 <label>Category</label>
                                 <select class="form-control js-example-basic-single" name="Type"  style="position: relative !important" title="Select Category">
                                    <option value=""  @if ( $priceProducts->Type == "") selected @endif>Select Category</option>
                                    <option value="1" @if ( $priceProducts->Type == "1") selected @endif>Pure</option>
                                    <option value="2" @if ( $priceProducts->Type == "2") selected @endif>Blend</option>
                                 </select>
                            </div>
                            <div class="form-group">
                                <label>Quantity Required</label>
                                <input type="number" class="form-control" name="QuantityRequired" value="{{ !empty($priceProducts->QuantityRequired) ? ($priceProducts->QuantityRequired) : 0 }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Product</label>
                                <select class="form-control js-example-basic-single" name="Product" style="position: relative !important" title="Select Product" required>
                                    <option value="" disabled selected>Select Product</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" @if ( $priceProducts->ProductId == $product->id) selected @endif>{{ $product->code }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Shipment Term</label>
                                <input type="text" class="form-control" name="ShipmentTerm" placeholder="Enter Shipment Term" value="{{ $priceMonitoring->ShipmentTerm }}">
                            </div>
                            <div class="form-group">
                                <label>Payment Term</label>
                                <select class="form-control js-example-basic-single" name="PaymentTerm" style="position: relative !important" title="Select Payment Term">
                                    <option value="" disabled selected>Select Payment Term</option>
                                    @foreach($payment_terms as $paymentTerm)
                                        <option value="{{ $paymentTerm->id }}" @if ($priceMonitoring->PaymentTermId == $paymentTerm->id) selected @endif>{{ $paymentTerm->Name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Other Cost Requirement</label>
                                <input type="text" class="form-control" name="OtherCostRequirement" placeholder="Enter Other Cost Requirement" value="{{ $priceMonitoring->OtherCostRequirements  }}">
                            </div>
                            <div class="form-group">
                                <label>With Commission?</label>
                                <input type="checkbox" name="WithCommission" value="1" {{ $priceMonitoring->IsWithCommission ? 'checked' : '' }}>
                            </div>
                            <div class="form-group">
                                <label >Enter Commission</label>
                                <input type="text" class="form-control" name="EnterCommission" placeholder="Enter Commission" value="{{ $priceMonitoring->Commission}}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>RMC (USD)</label>
                                <input type="number" class="form-control" name="Rmc" value="{{ !empty($priceProducts->ProductRmc) ? ($priceProducts->ProductRmc) : 0 }}">
                            </div>
                            <div class="form-group"><label>Additional Cost:</label></div>
                            <div class="form-group">
                                <label>Shipment Cost</label>
                                <input type="number" class="form-control" name="ShipmentCost" value="{{ !empty($priceProducts->IsalesShipmentCost) ? ($priceProducts->IsalesShipmentCost) : 0 }}">
                            </div>
                            <div class="form-group">
                                <label>Financing Cost</label>
                                <input type="number" class="form-control" name="FinancingCost" value="{{ !empty($priceProducts->IsalesFinancingCost) ? ($priceProducts->IsalesFinancingCost) : 0 }}">
                            </div>
                            <div class="form-group">
                                <label>Commission</label>
                                <input type="number" class="form-control" name="Commision" value="{{ !empty($priceProducts->IsalesCommission) ? ($priceProducts->IsalesCommission) : 0 }}">
                            </div>
                            <div class="form-group">
                                <label>Others</label>
                                <input type="number" class="form-control" name="Others" value="{{ !empty($priceProducts->IsalesOthers) ? ($priceProducts->IsalesOthers) : 0 }}">
                            </div>
                            <div class="form-group">
                                <label>Total Base Cost</label>
                                <input type="number" class="form-control" name="TotalBaseCost" value="{{ !empty($priceProducts->IsalesTotalBaseCost) ? ($priceProducts->IsalesTotalBaseCost) : 0 }}">
                            </div>
                            <div class="form-group">
                                <label>Base Selling Price</label>
                                <input type="number" class="form-control" name="BaseSellingPrice" value="{{ !empty($priceProducts->IsalesBaseSellingPrice) ? ($priceProducts->IsalesBaseSellingPrice) : 0 }}">
                            </div>
                            <div class="form-group">
                                <label>Offered Price</label>
                                <input type="number" class="form-control" name="OfferedPrice" value="{{ !empty($priceProducts->IsalesOfferedPrice) ? ($priceProducts->IsalesOfferedPrice) : 0 }}">
                            </div>
                            <div class="form-group">
                                <label>Margin</label>
                                <input type="number" class="form-control" name="Margin" value="{{ !empty($priceProducts->IsalesMargin) ? ($priceProducts->IsalesMargin) : 0 }}">
                            </div>
                            <div class="form-group">
                                <label>(%) Margin</label>
                                <input type="number" class="form-control" name="MarginPercent" value="{{ !empty($priceProducts->IsalesMarginPercentage) ? ($priceProducts->IsalesMarginPercentage) : 0 }}">
                            </div>
                            <div class="form-group">
                                <label>Total Margin</label>
                                <input type="number" class="form-control" name="TotalMargin" value="0">
                            </div>
                            <div class="form-group">
                                <label>Remarks</label>
                                <input type="text" class="form-control" name="Remarks" placeholder="Enter Remarks" value="{{ $priceMonitoring->Remarks }}">
                            </div>
                        </div>
                           @endforeach
                           <div class="col-lg-12"><hr style="background-color: black"></div>
                           <div class="col-lg-6">
                               <div class="form-group">
                                   <label>Is Accepted?</label>
                                   <input type="checkbox" name="IsAccepted" value="1" {{ $priceMonitoring->IsAccepted ? 'checked' : '' }}>
                               </div>
                               <div class="form-group">
                                   <label >Price Bid</label>
                                   <input type="number" step="0.01" class="form-control" name="PriceBid" value="{{ $priceMonitoring->PriceBid ?? 0}}">
                               </div>
                           </div>
                           <div class="col-lg-6">
                               <div class="form-group">
                                   <label >Remarks</label>
                                   <input type="text" class="form-control" name="DispositionRemarks" value="{{ $priceMonitoring->DispositionRemarks}}" placeholder="Enter Disposition Remarks">
                               </div>
                           </div>
                        </div>
                </div>    
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>            
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
        function fetchClientDetails(clientId) {
            if(clientId) {
                $.ajax({
                    url: '{{ url("client-details") }}/' + clientId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if(data) {
                            $('.editCountryName{{  $priceMonitoring->id }}').val(data.CountryName);
                            $('.CountryId').val(data.ClientCountryId);
                            $('.editRegionName{{  $priceMonitoring->id }}').val(data.RegionName);
                            $('.RegionId').val(data.ClientRegionId);
                        } else {
                            $('.editCountryName{{  $priceMonitoring->id }}').val('');
                            $('.CountryId').val('');
                            $('.editRegionName{{  $priceMonitoring->id }}').val('');
                            $('.RegionId').val('');
                        }
                    },
                    error: function() {
                        $('.editCountryName{{  $priceMonitoring->id }}').val('');
                        $('.CountryId').val('');
                        $('.editRegionName{{  $priceMonitoring->id }}').val('');
                        $('.RegionId').val('');
                    }
                });
            } else {
                $('.editCountryName{{  $priceMonitoring->id }}').val('');
                $('.CountryId').val('');
                $('.editRegionName{{  $priceMonitoring->id }}').val('');
                $('.RegionId').val('');
            }
        }

        var initialClientId = $('.EditClientId{{  $priceMonitoring->id }}').val();
        if (initialClientId) {
            fetchClientDetails(initialClientId);
        }

        $('.EditClientId{{  $priceMonitoring->id }}').change(function() {
            var clientId = $(this).val();
            fetchClientDetails(clientId);
        });
    });
</script>
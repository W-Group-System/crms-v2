<div class="modal fade" id="AddPriceMonitoring" tabindex="-1" role="dialog" aria-labelledby="addPriceMonitoring" aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addPriceMonitoringLabel">Add Price Monitoring</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" action="{{ url('price_monitoring') }}">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Primary Sales Person</label>
                                <select class="form-control js-example-basic-single" name="PrimarySalesPersonId" id="PrimarySalesPersonId" style="position: relative !important" title="Select Sales Person">
                                    <option value="" disabled selected>Select Sales Person</option>
                                    @foreach($primarySalesPersons as $user)
                                        <option value="{{ $user->user_id }}">{{ $user->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Secondary Sales Person</label>
                                <select class="form-control js-example-basic-single" name="SecondarySalesPersonId" id="SecondarySalesPersonId" style="position: relative !important" title="Select Sales Person">
                                    <option value="" disabled selected>Select Sales Person</option>
                                    @foreach($secondarySalesPersons as $user)
                                        <option value="{{ $user->user_id }}">{{ $user->full_name }}</option>
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
                        <div class="col-lg-6"></div>
                        <div class="col-lg-12"><hr style="background-color: black"></div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Client</label>
                                <select class="form-control js-example-basic-single ClientId" name="ClientId"  style="position: relative !important" title="Select Client" required>
                                    <option value="" disabled selected>Select Client</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}">{{ $client->Name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Country</label>
                                <input type="text" class="form-control CountryName" name="CountryName" readonly>
                                <input type="hidden" class="CountryId" name="CountryId">
                            </div>
                            <div class="form-group">
                                <label for="name">Region</label>
                                <input type="text" class="form-control RegionName" name="RegionName" readonly>
                                <input type="hidden" class="RegionId" name="RegionId">
                            </div>
                        </div>
                        <div class="col-lg-12"><hr style="background-color: black"></div>
                        <div class="col-lg-6"><label>Computation</label>
                            <div class="form-group">
                                <label>Purpose of Price Request</label>
                                <select class="form-control js-example-basic-single" name="PriceRequestPurpose"  style="position: relative !important" title="Select Purpose">
                                   <option value="" disabled selected>Select Purpose</option>
                                   <option value="10">Indication</option>
                                   <option value="20">Firm</option>
                                   <option value="30">Sample</option>
                                </select>
                           </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                 <label>Category</label>
                                 <select class="form-control js-example-basic-single" name="Type"  style="position: relative !important" title="Select Category">
                                    <option value="" disabled selected>Select Category</option>
                                    <option value="1">Pure</option>
                                    <option value="2">Blend</option>
                                 </select>
                            </div>
                            <div class="form-group">
                                <label>Quantity Required</label>
                                <input type="number" class="form-control" name="QuantityRequired" value="0">
                            </div>
                        </div>
                        <div class="col-lg-6"></div>
                        <div class="col-lg-6">
                           
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Product</label>
                                <select class="form-control js-example-basic-single" name="Product" style="position: relative !important" title="Select Product" required>
                                    <option value="" disabled selected>Select Product</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->code }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Shipment Term</label>
                                <input type="text" class="form-control" name="ShipmentTerm" placeholder="Enter Shipment Term">
                            </div>
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
                                <label>Other Cost Requirement</label>
                                <input type="text" class="form-control" name="OtherCostRequirement" placeholder="Enter Other Cost Requirement">
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
                                <label>RMC (USD)</label>
                                <input type="number" class="form-control" name="Rmc" value="0">
                            </div>
                            <div class="form-group"><label>Additional Cost:</label></div>
                            <div class="form-group">
                                <label>Shipment Cost</label>
                                <input type="number" class="form-control" name="ShipmentCost" value="0">
                            </div>
                            <div class="form-group">
                                <label>Financing Cost</label>
                                <input type="number" class="form-control" name="FinancingCost" value="0">
                            </div>
                            <div class="form-group">
                                <label>Commission</label>
                                <input type="number" class="form-control" name="Commision" value="0">
                            </div>
                            <div class="form-group">
                                <label>Others</label>
                                <input type="number" class="form-control" name="Others" value="0">
                            </div>
                            <div class="form-group">
                                <label>Total Base Cost</label>
                                <input type="number" class="form-control" name="TotalBaseCost" value="0">
                            </div>
                            <div class="form-group">
                                <label>Base Selling Price</label>
                                <input type="number" class="form-control" name="BaseSellingPrice" value="0">
                            </div>
                            <div class="form-group">
                                <label>Offered Price</label>
                                <input type="number" class="form-control" name="OfferedPrice" value="0">
                            </div>
                            <div class="form-group">
                                <label>Margin</label>
                                <input type="number" class="form-control" name="Margin" value="0">
                            </div>
                            <div class="form-group">
                                <label>(%) Margin</label>
                                <input type="number" class="form-control" name="MarginPercent" value="0">
                            </div>
                            <div class="form-group">
                                <label>Total Margin</label>
                                <input type="number" class="form-control" name="TotalMargin" value="0">
                            </div>
                            <div class="form-group">
                                <label>Remarks</label>
                                <input type="text" class="form-control" name="Remarks" placeholder="Enter Remarks">
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
                    url: '{{ url("client-details") }}/' + clientId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if(data) {
                            $('.CountryName').val(data.CountryName);
                            $('.CountryId').val(data.ClientCountryId);
                            $('.RegionName').val(data.RegionName);
                            $('.RegionId').val(data.ClientRegionId);
                        } else {
                            $('.Country').val('');
                            $('.Region').val('');
                        }
                    },
                    error: function() {
                        $('input[name="Country"]').val('');
                        $('input[name="Region"]').val('');
                    }
                });
            } else {
                $('input[name="Country"]').val('');
                $('input[name="Region"]').val('');
            }
        });

        $('#AddPriceMonitoring').on('hide.bs.modal', function () {
        $(this).find('form')[0].reset();
        $(this).find('select').val('').trigger('change'); 
        $(this).find('input[type="checkbox"]').prop('checked', false);
    });
    });

    document.addEventListener('DOMContentLoaded', function() {
        // var validityDateInput = document.querySelector('.ValidityDate');
        var dateRequestedInput = document.querySelector('.DateRequested');

        var today = new Date().toISOString().split('T')[0];

            // validityDateInput.setAttribute('min', today);
            dateRequestedInput.value = today;
    });
</script>
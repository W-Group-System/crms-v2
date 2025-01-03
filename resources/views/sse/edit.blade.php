<div class="modal fade" id="editSse{{$data->id}}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSseLabel">Edit Shipment Sample Evaluation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" id="form_shipment_sample{{$data->id}}">
                    @csrf
                    <span id="form_result"></span>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date Submitted (MM/DD/YYYY):</label>
                                <input type="date" class="form-control" id="DateSubmitted" name="DateSubmitted" value="{{ $data->DateSubmitted }}">
                            </div>
                            <div class="form-group">
                                <label>Raw Material Type:</label>
                                <input type="text" class="form-control" id="RmType" name="RmType" placeholder="Enter Raw Material Type" value="{{ $data->RmType }}">
                            </div>
                            <div class="form-group">
                                <label>Grade:</label>
                                <input type="text" class="form-control" id="Grade" name="Grade" placeholder="Enter Grade" value="{{ $data->Grade }}">
                            </div>
                            <div class="form-group mb-0">
                                <label for="SseResult">Result:</label>
                                <select class="form-control js-example-basic-single" id="SseResult" name="SseResult" title="Select Result" style="position: relative !important;" onchange="toggleSseField()">
                                    <option value="" disabled selected>Select Result</option>
                                    <option value="1" {{ $data->SseResult == 1 ? 'selected' : '' }}>Old alternative product/ supplier</option>
                                    <option value="2" {{ $data->SseResult == 2 ? 'selected' : '' }}>New Product WITHOUT SPE Result</option>
                                    <option value="3" {{ $data->SseResult == 3 ? 'selected' : '' }}>First shipment with SPE result</option>
                                </select>
                            </div>
                            <div class="form-group" id="otherResult" style="display: {{ $data->SseResult == 3 ? 'block' : 'none' }};">
                                <input type="text" class="form-control" id="ResultSpeNo" name="ResultSpeNo" placeholder="Enter SPE #" value="{{ $data->ResultSpeNo ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Attention To:</label>
                                <select id="AttentionTo" name="AttentionTo" class="form-control js-example-basic-single">
                                    <option disabled selected value>Select REF Code</option>
                                    @foreach ($refCode as $key => $code)
                                        <option value="{{ $key }}" 
                                            {{ $key == $data->AttentionTo ? 'selected' : '' }}>
                                            {{ $code }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Product Code:</label>
                                <input type="text" class="form-control" id="ProductCode" name="ProductCode" placeholder="Enter Product Code" value="{{ $data->ProductCode }}">
                            </div>
                            <div class="form-group">
                                <label>Origin:</label>
                                <input type="text" class="form-control" id="Origin" name="Origin" placeholder="Enter Origin" value="{{ $data->Origin }}">
                            </div>
                            <div class="form-group">
                                <label>Supplier:</label>
                                <input type="text" class="form-control" id="Supplier" name="Supplier" placeholder="Enter Supplier" value="{{ $data->Supplier }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-header">
                        <span class="header-label font-weight-bold">Purchase Details</span>
                        <hr class="form-divider alert-dark">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>PO #:</label>
                                <input type="text" class="form-control" id="PoNumber" name="PoNumber" placeholder="Enter Po Number" value="{{ $data->PoNumber }}">
                            </div>
                            <div class="form-group">
                                <label>Ordered:</label>
                                <input type="text" class="form-control" id="Ordered" name="Ordered" placeholder="Enter Ordered" value="{{ $data->Ordered }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Quantity:</label>
                                <input type="text" class="form-control" id="Quantity" name="Quantity" placeholder="Enter Quantity" value="{{ $data->Quantity }}">
                            </div>
                            <div class="form-group mb-0">
                                <label>Product ordered is:</label>
                                <select class="form-control js-example-basic-single" id="ProductOrdered" name="ProductOrdered" style="position: relative !important" title="Select Product Ordered" onchange="toggleSseProduct()">
                                    <option value="" disabled selected>Select Product Ordered</option>
                                    <option value="1" {{ $data->ProductOrdered == 1 ? 'selected' : '' }}>For Shipment by supplier</option>
                                    <option value="2" {{ $data->ProductOrdered == 2 ? 'selected' : '' }}>In transit to Manila or Plant</option>
                                    <option value="3" {{ $data->ProductOrdered == 3 ? 'selected' : '' }}>Delivered to plant & on stock</option>
                                    <option value="4" {{ $data->ProductOrdered == 4 ? 'selected' : '' }}>Shipped out to buyer</option>
                                    <option value="5" {{ $data->ProductOrdered == 5 ? 'selected' : '' }}>Others</option>
                                </select>
                            </div>
                            <div class="form-group" id="otherProduct" style="display: {{ $data->ProductOrdered == 5 ? 'block' : 'none' }};">
                                <input type="text" class="form-control" id="OtherProduct" name="OtherProduct" placeholder="Enter Product Ordered" value="{{ $data->OtherProduct ?? '' }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-header">
                        <span class="header-label font-weight-bold">For DIRECT Shipment only</span>
                        <hr class="form-divider alert-dark">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Buyer:</label>
                                <input type="text" class="form-control" id="Buyer" name="Buyer" placeholder="Enter Buyer" value="{{ $data->Buyer }}">
                            </div>
                            <div class="form-group">
                                <label>Buyer's PO #:</label>
                                <input type="text" class="form-control" id="BuyersPo" name="BuyersPo" placeholder="Enter Buyer's Po" value="{{ $data->BuyersPo }}">
                            </div>
                            <div class="form-group">
                                <label>Instruction to Lab:</label>
                                <textarea type="text" class="form-control" id="Instruction" name="Instruction" placeholder="Enter Instruction" rows="2">{{ $data->Instruction }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Sales Agreement #:</label>
                                <input type="text" class="form-control" id="SalesAgreement" name="SalesAgreement" placeholder="Enter Sales Agreement" value="{{ $data->SalesAgreement }}">
                            </div>
                            <div class="form-group">
                                <label>Product Declared as:</label>
                                <input type="text" class="form-control" id="ProductDeclared" name="ProductDeclared" placeholder="Enter Product Declared" value="{{ $data->ProductDeclared }}">
                            </div>
                            <div class="form-group">
                                <label>Remarks:</label>
                                <input type="text" class="form-control" id="LnBags" name="LnBags" placeholder="Enter Remarks" value="{{ $data->LnBags }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-header">
                        <span class="header-label font-weight-bold">Sample Details</span>
                        <hr class="form-divider alert-dark">
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-check form-check-inline text-center">
                                <input class="form-check-input" type="radio" name="SampleType" id="SampleType1" value="Pre-ship sample">
                                <label class="form-check-label" for="SampleType1">Pre-ship sample</label>

                                <input class="form-check-input" type="radio" name="SampleType" id="SampleType2" value="Co-ship sample">
                                <label class="form-check-label" for="SampleType2">Co-ship sample</label>

                                <input class="form-check-input" type="radio" name="SampleType" id="SampleType3" value="Complete samples">
                                <label class="form-check-label" for="SampleType3">Complete samples</label>

                                <input class="form-check-input" type="radio" name="SampleType" id="SampleType4" value="Partial samples. More samples to follow">
                                <label class="form-check-label" for="SampleType4">Partial samples. More samples to follow</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" id="lotNoContainer">
                                <label>No of pack:</label>
                                <div class="input-group">
                                    <input type="hidden" name="PackId[]" value="">
                                    <input type="text" class="form-control" name="LotNumber[]" placeholder="Enter Lot Number">
                                    <button class="btn btn-sm btn-primary addRowBtn1" style="border-radius: 0px;" type="button">+</button>
                                </div>
                                <input type="text" class="form-control" name="QtyRepresented[]" placeholder="Enter Qty Represented">
                            </div>
                            <div class="form-group">
                                <label>Laboratory work required:</label>
                                <select class="form-control js-example-basic-multiple" id="Work" name="Work[]" style="position: relative !important" multiple>
                                    <option value="Standard QUALITY CONTROL test: pH, Viscosity, WGS, KGS">Standard QUALITY CONTROL test: pH, Viscosity, WGS, KGS</option>
                                    <option value="Particle size distribution">Particle size distribution</option>
                                    <option value="Microbacteria test">Microbacteria test</option>
                                    <option value="Retain Sample">Retain Sample</option>
                                    <option value="Other tests">Other tests</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" id="attachmentsContainer">
                                <label>Attachments:</label>
                                <div class="input-group">         
                                    <select class="form-control js-example-basic-single" name="Name[]" id="Name" title="Select Attachment Name" >
                                        <option value="" disabled selected>Select Attachment Name</option>
                                        <option value="COA">COA</option>
                                        <option value="Specifications">Specifications</option>
                                        <option value="Others">Others</option>
                                    </select>
                                    <button class="btn btn-sm btn-primary addRowBtn2" style="border-radius: 0px;" type="button">+</button>
                                </div>
                                <input type="file" class="form-control" id="Path" name="Path[]">
                            </div>
                        </div>
                    </div>
                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <input type="hidden" name="hidden_id" id="hidden_id" value="{{ $data->id }}">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" id="action_button" class="btn btn-outline-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    #Name {
        width: 395px !important;
    }
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        $(".js-example-basic-multiple, .js-example-basic-single").select2();

        // Reset the form and select fields when the modal is hidden
        $("#editSse{{$data->id}}").on("hidden.bs.modal", function () {
            $(this).find("form").trigger("reset");
            $(this).find(".js-example-basic-single").trigger("change");
            $(this).find(".js-example-basic-multiple").trigger("change");
        });
        

        // Handle form submission
        $("#form_shipment_sample{{$data->id}}").on("submit", function (e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                url: "{{ route('update_shipment_sample', $data->id) }}",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    // Show success message
                    Swal.fire("Success", response.message, "success");

                    // Delay hiding the modal by 2 seconds (2000 ms)
                    setTimeout(function () {
                        // Hide modal
                        $("#editSse{{$data->id}}").modal("hide");
                        location.reload();
                        // Optionally reload or update your table (if required)
                        // location.reload(); // or any other code to update the UI
                    }, 2000); // 2000ms = 2 seconds

                },
                error: function (xhr) {
                    let errors = xhr.responseJSON?.errors || {};
                    let errorMessage = Object.values(errors).map((msg) => msg[0]).join("<br>");
                    Swal.fire("Error", errorMessage || "An unexpected error occurred.", "error");
                },
            });
        });
    });
    function toggleSseField() {
        const sseResult = $("#SseResult").val();
        if (sseResult == 3) {
            $("#otherResult").show();
        } else {
            $("#otherResult").hide();
            $("#ResultSpeNo").val(""); // Clear the input field
        }
    }

    function toggleSseProduct() {
        const sseResult = $("#ProductOrdered").val();
        if (sseResult == 5) {
            $("#otherProduct").show();
        } else {
            $("#otherProduct").hide();
            $("#OtherProduct").val(""); // Clear the input field
        }
    }
</script>

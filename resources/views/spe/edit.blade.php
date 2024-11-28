<div class="modal fade" id="editSpe{{$data->id}}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSpeLabel">Edit Supplier Product Evaluation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" id="form_supplier_product_{{$data->id}}">
                    @csrf
                    <span id="form_result"></span>
                    <div class="row">
                        <!-- First Column -->
                        <div class="col-md-6">
                            <!-- Date Requested -->
                            <div class="form-group">
                                <label for="DateRequested">Date Requested (MM/DD/YYYY):</label>
                                <input type="date" class="form-control" id="DateRequested" name="DateRequested" 
                                    value="{{ $data->DateRequested }}" readonly>
                            </div>
                            <!-- Deadline -->
                            <div class="form-group">
                                <label for="Deadline">Deadline (MM/DD/YYYY):</label>
                                <input type="date" class="form-control" id="Deadline" name="Deadline" 
                                    value="{{ $data->Deadline }}">
                            </div>
                            <!-- Product Name -->
                            <div class="form-group">
                                <label for="ProductName">Product Name:</label>
                                <input type="text" class="form-control" id="ProductName" name="ProductName" 
                                    placeholder="Enter Product Name" value="{{ $data->ProductName }}">
                            </div>
                            <!-- Supplier -->
                            <div class="form-group">
                                <label for="Supplier">Supplier/Trader Name:</label>
                                <select class="form-control js-example-basic-single" id="Supplier" name="Supplier">
                                    <option value="" disabled {{ empty($data->Supplier) ? 'selected' : '' }}>Select Client</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->Id }}" 
                                            {{ isset($data->Supplier) && $data->Supplier == $supplier->Id ? 'selected' : '' }}>
                                            {{ $supplier->Name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Origin -->
                            <div class="form-group">
                                <label for="Origin">Origin:</label>
                                <input type="text" class="form-control" id="Origin" name="Origin" 
                                    placeholder="Enter Origin" value="{{ $data->Origin }}">
                            </div>
                            <!-- Price -->
                            <div class="form-group">
                                <label for="Price">Price:</label>
                                <input type="text" class="form-control" id="Price" name="Price" 
                                    placeholder="Enter Price" value="{{ $data->Price }}">
                            </div>
                            <div class="form-group" id="attachmentsContainer">
                                <label>Attachments:</label>
                                <div class="input-group">         
                                    <select class="form-control js-example-basic-single" name="Name[]" id="Name" title="Select Attachment Name" >
                                        <option value="" disabled selected>Select Attachment Name</option>
                                        <option value="Sample">Sample</option>
                                        <option value="Specifications">Specifications</option>
                                        <option value="COA">COA</option>
                                        <option value="Recipe">Recipe</option>
                                    </select>
                                    <button class="btn btn-sm btn-primary addRowBtn" style="border-radius: 0px;" type="button">+</button>
                                </div>
                                <input type="file" class="form-control" id="Path" name="Path[]">
                            </div>
                        </div>

                        <!-- Second Column -->
                        <div class="col-md-6">
                            <!-- Attention To -->
                            <div class="form-group">
                                <label for="AttentionTo">Attention To:</label>
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
                            <!-- Manufacturer -->
                            <div class="form-group">
                                <label for="Manufacturer">Manufacturer of Sample:</label>
                                <input type="text" class="form-control" id="Manufacturer" name="Manufacturer" 
                                    placeholder="Enter Manufacturer" value="{{ $data->Manufacturer }}">
                            </div>
                            <!-- Quantity -->
                            <div class="form-group">
                                <label for="Quantity">Quantity:</label>
                                <input type="text" class="form-control" id="Quantity" name="Quantity" 
                                    placeholder="Enter Quantity" value="{{ $data->Quantity }}">
                            </div>
                            <!-- Product Application -->
                            <div class="form-group">
                                <label for="ProductApplication">Product Application:</label>
                                <input type="text" class="form-control" id="ProductApplication" name="ProductApplication" 
                                    placeholder="Enter Product Application" value="{{ $data->ProductApplication }}">
                            </div>
                            <!-- Lot No. -->
                            <div class="form-group">
                                <label for="LotNo">Lot No./ Batch No.:</label>
                                <input type="text" class="form-control" id="LotNo" name="LotNo" 
                                    placeholder="Enter Lot No./ Batch No." value="{{ $data->LotNo }}">
                            </div>
                            <!-- Instruction -->
                            <div class="form-group">
                                <label for="Instruction">Instruction to Laboratory:</label>
                                <select class="form-control js-example-basic-multiple" id="Instruction" name="Instruction[]" multiple>
                                    <option value="Physical Chemical Testing" 
                                        {{ in_array('Physical Chemical Testing', $instructions ?? []) ? 'selected' : '' }}>
                                        Physical Chemical Testing
                                    </option>
                                    <option value="Microbiological Testing" 
                                        {{ in_array('Microbiological Testing', $instructions ?? []) ? 'selected' : '' }}>
                                        Microbiological Testing
                                    </option>
                                    <option value="Mesh Analysis" 
                                        {{ in_array('Mesh Analysis', $instructions ?? []) ? 'selected' : '' }}>
                                        Mesh Analysis
                                    </option>
                                </select>
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

<script>
    $(document).ready(function () {
        $(".js-example-basic-multiple, .js-example-basic-single").select2();

        // Reset the form and select fields when the modal is hidden
        $("#editSpe{{$data->id}}").on("hidden.bs.modal", function () {
            $(this).find("form").trigger("reset");
            $(this).find(".js-example-basic-single").trigger("change");
            $(this).find(".js-example-basic-multiple").trigger("change");
        });

        $(document).on('click', '.addRowBtn', function() {
            var newRow = $('<div class="form-group" style="margin-top: 10px">' +
                        '<label>Attachments</label>' +
                        '<div class="input-group">' +         
                            '<select class="form-control js-example-basic-single" name="Name[]" id="Name" title="Select Attachment Name">' +
                                '<option value="" disabled selected>Select Attachment Name</option>' +
                                '<option value="Sample">Sample</option>' +
                                '<option value="Specifications">Specifications</option>' +
                                '<option value="COA">COA</option>' +
                                '<option value="Recipe">Recipe</option>' +
                            '</select>' +
                           '<button class="btn btn-sm btn-danger removeRowBtn" style="border-radius: 0px;" type="button">-</button>' +
                        '</div>' +
                        '<input type="file" class="form-control" id="Path" name="Path[]">' +
                    '</div>');

            // Append the new row to the container where addresses are listed
            $('#attachmentsContainer').append(newRow);

             // Reinitialize select2 for the new row
            $('.js-example-basic-single').select2();
        });

        $(document).on('click', '.removeRowBtn', function() {
            $(this).closest('.form-group').remove();
        });
        

        // Handle form submission
        $("#form_supplier_product_{{$data->id}}").on("submit", function (e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                url: "{{ route('update_spe', $data->id) }}",
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
                        $("#editSpe{{$data->id}}").modal("hide");
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

</script>

<div class="modal fade" id="update{{$data->id}}">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign Complaint</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="assignCustomerComplaint" method="POST" action="{{url('cc_assign/'.$data->id)}}" enctype="multipart/form-data" onsubmit="show()">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Select Department Concerned</label>
                                <select class="form-control js-example-basic-single" name="Department" id="Department" required>
                                    <option value="" disabled selected>Select Department Concerned</option>
                                    @foreach($concern_department as $dept)
                                        <option value="{{ $dept->Name }}" {{ old('Department') == $dept->Name ? 'selected' : '' }}>
                                            {{ $dept->Name }}
                                        </option>
                                    @endforeach
                                </select> 
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Select Site Concerned</label>
                                <select class="form-control js-example-basic-single" name="SiteConcerned" id="SiteConcerned" title="Select Site Concerned" required>
                                    <option value="" disabled selected>Select Site Concerned</option>
                                    <option value="WHI Carmona">WHI Carmona</option>
                                    <option value="WHI Head Office">WHI Head Office</option>
                                    <option value="CCC Carmen">CCC Carmen</option>
                                    <option value="PBI Canlubang">PBI Canlubang</option>
                                    <option value="International Warehouse">International Warehouse</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Definition of Quality Class</label>
                                <select class="form-control js-example-basic-single" name="QualityClass" id="QualityClass" title="Select Quality Class" required>
                                    <option value="" disabled selected>Select Quality Class</option>
                                    <option value="Critical e.g., Food Safety Hazard">Critical e.g., Food Safety Hazard</option>
                                    <option value="Major e.g., Damage bags (2 Major recurring or 1 critical = NCAR)">Major e.g., Damage bags (2 Major recurring or 1 critical = NCAR)</option>
                                    <option value="Minor/Marginal e.g., Late response">Minor/Marginal e.g., Late response</option>
                                    <option value="Product name">Product name</option>
                                </select>
                            </div>
                            <div class="form-group" id="pName" style="display: none; margin-top: -10px">
                                <input type="text" class="form-control" id="ProductName" name="ProductName" placeholder="Enter Product Name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Attachments</label>
                                <input type="file" name="Path[]" class="form-control" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer mt-3">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-outline-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.js-example-basic-single').select2();

        $('#QualityClass').on('change', function() {
            var selectedValue = $(this).val(); 
            if (selectedValue == "Product name") {
                $('#pName').show(); 
            } else {
                $('#pName').hide(); 
            }
        });

        $('#assignCustomerComplaint').on('submit', function (e) {
            e.preventDefault(); 

            var formData = new FormData(this); // Use FormData to handle file uploads
            var actionUrl = $(this).attr('action');

            $.ajax({
                url: actionUrl,
                type: 'POST',
                data: formData,
                processData: false, 
                contentType: false, 
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: "Assigned",
                            text: response.message,
                            icon: "success",
                            showConfirmButton: false,
                            timer: 1500
                        }).then(function () {
                            window.location.reload(); 
                        });
                    }
                },
                error: function (xhr) {
                    Swal.fire("Error", "Something went wrong! Please try again.", "error");
                }
            });
        });
    });
</script>
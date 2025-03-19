@extends('layouts.cs_header')
@section('content')
<div class="col-12 text-center">
    <img src="{{ asset('images/whi.png') }}" style="width: 170px;" class="mt-3 mb-2">
    <h2 class="header_h2">Customer Complaint Form</h2>
</div>
<form id="form_complaint" method="POST" enctype="multipart/form-data" onsubmit="show()">
    @csrf
    <input type="hidden" name="CcNumber" value="{{ $newCcNo }}">
    <input type="hidden" name="Status" value="10">
    <div class="row col-lg-12 mt-3" style="margin-left: 0px">
        <div class="offset-lg-1 col-lg-5">
            <div class="form-group">
                <label class="text-white display-5">Company Name</label>
                <input type="text" class="form-control" name="CompanyName" id="CompanyName" placeholder="Enter Company Name" required>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="form-group">
                <label class="text-white display-5">Contact Name</label>
                <input type="text" class="form-control" name="ContactName" id="ContactName" placeholder="Enter Contact Name" required>
            </div>
        </div>
        {{-- <div class="offset-lg-1 col-lg-10">
            <div class="form-group">
                <label class="text-white display-5">Address</label>
                <input type="text" class="form-control" name="Address" id="Address" placeholder="Enter Address">
            </div>
        </div> --}}
        <div class="offset-lg-1 col-lg-5">
            <div class="form-group">
                <label class="text-white display-5">Email Address</label>
                <input type="email" class="form-control" name="Email" id="Email" placeholder="Enter Email Address" required>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="form-group">
                <label class="text-white display-5">Telephone</label>
                <input type="text" class="form-control" name="Telephone" id="Telephone" placeholder="Enter Telephone">
            </div>
        </div>
        {{-- <div class="offset-lg-1 col-lg-10">
            <div class="form-group">
                <label class="text-white display-5">Mode of Communication</label>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="Moc" id="flexRadioDefault1" value="1">
                    <label class="form-check-label text-white display-5" for="flexRadioDefault1">By Phone</label>
                    <input class="form-check-input" type="radio" name="Moc" id="flexRadioDefault2" value="2">
                    <label class="form-check-label text-white display-5" for="flexRadioDefault2">By Letter/ Fax</label>
                    <input class="form-check-input" type="radio" name="Moc" id="flexRadioDefault3" value="3">
                    <label class="form-check-label text-white display-5" for="flexRadioDefault3">Personal</label>
                    <input class="form-check-input" type="radio" name="Moc" id="flexRadioDefault4" value="4">
                    <label class="form-check-label text-white display-5" for="flexRadioDefault4">By Email</label>
                </div>
            </div>
        </div> --}}
        <div class="offset-lg-1 col-lg-5 mb-3">
            <div class="form-group">
                <label class="text-white display-5">Country</label>
                <select class="form-control js-example-basic-single" name="Country" id="Country" title="Select Country">
                    <option value="" disabled selected>Select Country</option>
                    @foreach($countries as $data)
                        <option value="{{ $data->id }}" >{{ $data->Name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-lg-5 mb-3">
            <div class="form-group">
                <label class="text-white display-5">Attachments</label>
                <input type="file" class="form-control attachments" name="Path[]" id="Path" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
            </div>
        </div>
        {{-- <div class="col-lg-5 mb-3">
            <div class="form-group">
                <label class="text-white display-5">Definition of Quality Class</label>
                <select class="form-control js-example-basic-single" name="QualityClass" id="QualityClass" title="Select Quality Class" required>
                    <option value="" disabled selected>Select Quality Class</option>
                    <option value="1">Critical e.g., Food Safety Hazard</option>
                    <option value="2">Major e.g., Damage bags (2 Major recurring or 1 critical = NCAR)</option>
                    <option value="3">Minor/Marginal e.g., Late response</option>
                    <option value="4">Product name</option>
                </select>
            </div>
            <div class="form-group" id="pName" style="display: none; margin-top: -10px">
                <input type="text" class="form-control" id="ProductName" name="ProductName" placeholder="Enter Product Name">
            </div>
        </div> --}}
        {{-- <div class="offset-lg-1 col-lg-10 table-responsive">
            <table class="table table-bordered" style="background: rgb(255 255 255 / 91%)">
                <thead>
                    <tr>
                        <th width="50%" colspan="1" class="text-center">COMPLAINT CATEGORY</th>
                        <th width="50%" colspan="6" class="text-center">PRODUCT DETAILS</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><b>1. Product Quality</b></td>
                        <td>Please<br>Check</td>
                        <td>Product Name</td>
                        <td>S/C No.</td>
                        <td>SO No.</td>
                        <td>Quantity</td>
                        <td>Lot No.</td>
                    </tr>
                    <tr>
                        <td class="break-spaces">1.1 Physical Hazard (contamination of product by unspecified compound e.g. hard plastics, metal flakes, rust, etc.)</td>
                        <td align="center"><input id="check-p1" type="checkbox"></td>
                        <td><input type="text" class="form-control p1-input" name="Pn1" disabled></td>
                        <td><input type="text" class="form-control p1-input" name="ScNo1" disabled></td>
                        <td><input type="text" class="form-control p1-input" name="SoNo1" disabled></td>
                        <td><input type="text" class="form-control p1-input" name="Quantity1" disabled></td>
                        <td><input type="text" class="form-control p1-input" name="LotNo1" disabled></td>
                    </tr>
                    <tr>
                        <td class="break-spaces">1.2 Biological Hazard (e.g. high bacteria count, etc.)</td>
                        <td align="center"><input id="check-p2" type="checkbox"></td>
                        <td><input type="text" class="form-control p2-input" name="Pn2" disabled></td>
                        <td><input type="text" class="form-control p2-input" name="ScNo2" disabled></td>
                        <td><input type="text" class="form-control p2-input" name="SoNo2" disabled></td>
                        <td><input type="text" class="form-control p2-input" name="Quantity2" disabled></td>
                        <td><input type="text" class="form-control p2-input" name="LotNo2" disabled></td>
                    </tr>
                    <tr>
                        <td class="break-spaces">1.3 Chemical Hazard (e.g. high heavy metals content, etc.)</td>
                        <td align="center"><input id="check-p3" type="checkbox"></td>
                        <td><input type="text" class="form-control p3-input" name="Pn3" disabled></td>
                        <td><input type="text" class="form-control p3-input" name="ScNo3" disabled></td>
                        <td><input type="text" class="form-control p3-input" name="SoNo3" disabled></td>
                        <td><input type="text" class="form-control p3-input" name="Quantity3" disabled></td>
                        <td><input type="text" class="form-control p3-input" name="LotNo3" disabled></td>
                    </tr>
                    <tr>
                        <td class="break-spaces">1.4 Visual Defects (e.g. color change, particle size)</td>
                        <td align="center"><input id="check-p4" type="checkbox"></td>
                        <td><input type="text" class="form-control p4-input" name="Pn4" disabled></td>
                        <td><input type="text" class="form-control p4-input" name="ScNo4" disabled></td>
                        <td><input type="text" class="form-control p4-input" name="SoNo4" disabled></td>
                        <td><input type="text" class="form-control p4-input" name="Quantity4" disabled></td>
                        <td><input type="text" class="form-control p4-input" name="LotNo4" disabled></td>
                    </tr>
                    <tr>
                        <td class="break-spaces">1.5 Application Problems (e.g. poor dispersion, poor distribution, poor binding property, high syneresis, etc.)</td>
                        <td align="center"><input id="check-p5" type="checkbox"></td>
                        <td><input type="text" class="form-control p5-input" name="Pn5" disabled></td>
                        <td><input type="text" class="form-control p5-input" name="ScNo5" disabled></td>
                        <td><input type="text" class="form-control p5-input" name="SoNo5" disabled></td>
                        <td><input type="text" class="form-control p5-input" name="Quantity5" disabled></td>
                        <td><input type="text" class="form-control p5-input" name="LotNo5" disabled></td>
                    </tr>
                    <tr>
                        <td class="break-spaces">1.6 Physical/ Chemical Properties Out-of Specification (e.g. pH, gel strength, viscosity, syneresis and contamination with other ingredients)</td>
                        <td align="center"><input id="check-p6" type="checkbox"></td>
                        <td><input type="text" class="form-control p6-input" name="Pn6" disabled></td>
                        <td><input type="text" class="form-control p6-input" name="ScNo6" disabled></td>
                        <td><input type="text" class="form-control p6-input" name="SoNo6" disabled></td>
                        <td><input type="text" class="form-control p6-input" name="Quantity6" disabled></td>
                        <td><input type="text" class="form-control p6-input" name="LotNo6" disabled></td>
                    </tr>
                    <tr>
                        <td colspan="7"><b>2. Packaging</b></td>
                    </tr>
                    <tr>
                        <td class="break-spaces">2.1 Quantity (e.g. Short-packing, under-filled bags or box, over-filled container or box, etc.)</td>
                        <td align="center"><input id="check-pack1" type="checkbox"></td>
                        <td><input type="text" class="form-control input-pack1" name="PackPn1" disabled></td>
                        <td><input type="text" class="form-control input-pack1" name="PackScNo1" disabled></td>
                        <td><input type="text" class="form-control input-pack1" name="PackSoNo1" disabled></td>
                        <td><input type="text" class="form-control input-pack1" name="PackQuantity1" disabled></td>
                        <td><input type="text" class="form-control input-pack1" name="PackLotNo1" disabled></td>
                    </tr>
                    <tr>
                        <td class="break-spaces">2.2 Packing (e.g. leakages, corrosion, etc.)</td>
                        <td align="center"><input id="check-pack2" type="checkbox"></td>
                        <td><input type="text" class="form-control input-pack2" name="PackPn2" disabled></td>
                        <td><input type="text" class="form-control input-pack2" name="PackScNo2" disabled></td>
                        <td><input type="text" class="form-control input-pack2" name="PackSoNo2" disabled></td>
                        <td><input type="text" class="form-control input-pack2" name="PackQuantity2" disabled></td>
                        <td><input type="text" class="form-control input-pack2" name="PackLotNo2" disabled></td>
                    </tr>
                    <tr>
                        <td class="break-spaces">2.3 Labeling (e.g. wrong or defective label, unreadable, incorrect or incomplete text, etc.)</td>
                        <td align="center"><input id="check-pack3" type="checkbox"></td>
                        <td><input type="text" class="form-control input-pack3" name="PackPn3" disabled></td>
                        <td><input type="text" class="form-control input-pack3" name="PackScNo3" disabled></td>
                        <td><input type="text" class="form-control input-pack3" name="PackSoNo3" disabled></td>
                        <td><input type="text" class="form-control input-pack3" name="PackQuantity3" disabled></td>
                        <td><input type="text" class="form-control input-pack3" name="PackLotNo3" disabled></td>
                    </tr>
                    <tr>
                        <td class="break-spaces">2.4 Packaging material (e.g. wrong packaging (bag, pallet, etc.) material, incorrect application of packaging instructions, inadequate quality of packaging material, etc.)</td>
                        <td align="center"><input id="check-pack4" type="checkbox"></td>
                        <td><input type="text" class="form-control input-pack4" name="PackPn4" disabled></td>
                        <td><input type="text" class="form-control input-pack4" name="PackScNo4" disabled></td>
                        <td><input type="text" class="form-control input-pack4" name="PackSoNo4" disabled></td>
                        <td><input type="text" class="form-control input-pack4" name="PackQuantity4" disabled></td>
                        <td><input type="text" class="form-control input-pack4" name="PackLotNo4" disabled></td>
                    </tr>
                    <tr>
                        <td colspan="7"><b>3. Delivery and Handling</b></td>
                    </tr>
                    <tr>
                        <td class="break-spaces">3.1 Product Handling (e.g. wrong product, pack size or quantity)</td>
                        <td align="center"><input id="check-d1" type="checkbox"></td>
                        <td><input type="text" class="form-control d1-input" name="DhPn1" disabled></td>
                        <td><input type="text" class="form-control d1-input" name="DhScNo1" disabled></td>
                        <td><input type="text" class="form-control d1-input" name="DhSoNo1" disabled></td>
                        <td><input type="text" class="form-control d1-input" name="DhQuantity1" disabled></td>
                        <td><input type="text" class="form-control d1-input" name="DhLotNo1" disabled></td>
                    </tr>
                    <tr>
                        <td class="break-spaces">3.2 Delayed Delivery (e.g. inadequate forwarder service, wrong delivery address, etc.)</td>
                        <td align="center"><input id="check-d2" type="checkbox"></td>
                        <td><input type="text" class="form-control d2-input" name="DhPn2" disabled></td>
                        <td><input type="text" class="form-control d2-input" name="DhScNo2" disabled></td>
                        <td><input type="text" class="form-control d2-input" name="DhSoNo2" disabled></td>
                        <td><input type="text" class="form-control d2-input" name="DhQuantity2" disabled></td>
                        <td><input type="text" class="form-control d2-input" name="DhLotNo2" disabled></td>
                    </tr>
                    <tr>
                        <td class="break-spaces">3.3 Product Damage during transit (e.g. leakages, corrosion, damaged label/box/carton/seal, etc.)</td>
                        <td align="center"><input id="check-d3" type="checkbox"></td>
                        <td><input type="text" class="form-control d3-input" name="DhPn3" disabled></td>
                        <td><input type="text" class="form-control d3-input" name="DhScNo3" disabled></td>
                        <td><input type="text" class="form-control d3-input" name="DhSoNo3" disabled></td>
                        <td><input type="text" class="form-control d3-input" name="DhQuantity3" disabled></td>
                        <td><input type="text" class="form-control d3-input" name="DhLotNo3" disabled></td>
                    </tr>
                    <tr>
                        <td colspan="7"><b>4. Others</b></td>
                    </tr>
                    <tr>
                        <td class="break-spaces">4.1 Quality of records or documents (e.g. insufficient, inadequate, missing, etc.)</td>
                        <td align="center"><input id="check-o1" type="checkbox"></td>
                        <td><input type="text" class="form-control o1-input" name="OthersPn1" disabled></td>
                        <td><input type="text" class="form-control o1-input" name="OthersScNo1" disabled></td>
                        <td><input type="text" class="form-control o1-input" name="OthersSoNo1" disabled></td>
                        <td><input type="text" class="form-control o1-input" name="OthersQuantity1" disabled></td>
                        <td><input type="text" class="form-control o1-input" name="OthersLotNo1" disabled></td>
                    </tr>
                    <tr>
                        <td class="break-spaces">4.2 Poor customer Service (e.g., courtesy, professionalism, handling, responsiveness)</td>
                        <td align="center"><input id="check-o2" type="checkbox"></td>
                        <td><input type="text" class="form-control o2-input" name="OthersPn2" disabled></td>
                        <td><input type="text" class="form-control o2-input" name="OthersScNo2" disabled></td>
                        <td><input type="text" class="form-control o2-input" name="OthersSoNo2" disabled></td>
                        <td><input type="text" class="form-control o2-input" name="OthersQuantity2" disabled></td>
                        <td><input type="text" class="form-control o2-input" name="OthersLotNo2" disabled></td>
                    </tr>
                    <tr>
                        <td class="break-spaces">4.3 Payment/ Invoice (e.g. wrong price/ product details)</td>
                        <td align="center"><input id="check-o3" type="checkbox"></td>
                        <td><input type="text" class="form-control o3-input" name="OthersPn3" disabled></td>
                        <td><input type="text" class="form-control o3-input" name="OthersScNo3" disabled></td>
                        <td><input type="text" class="form-control o3-input" name="OthersSoNo3" disabled></td>
                        <td><input type="text" class="form-control o3-input" name="OthersQuantity3" disabled></td>
                        <td><input type="text" class="form-control o3-input" name="OthersLotNo3" disabled></td>
                    </tr>
                    <tr>
                        <td class="break-spaces">4.4 Other Issues (please specify)</td>
                        <td align="center"><input id="check-o4" type="checkbox"></td>
                        <td><input type="text" class="form-control o4-input" name="OthersPn4" disabled></td>
                        <td><input type="text" class="form-control o4-input" name="OthersScNo4" disabled></td>
                        <td><input type="text" class="form-control o4-input" name="OthersSoNo4" disabled></td>
                        <td><input type="text" class="form-control o4-input" name="OthersQuantity4" disabled></td>
                        <td><input type="text" class="form-control o4-input" name="OthersLotNo4" disabled></td>
                    </tr>
                </tbody>
            </table>
        </div> --}}
        {{-- <div class="offset-lg-1 col-lg-10 mt-3">
            <label class="text-white display-5">Quantification of Cost/s:</label>
        </div> --}}
        {{-- <div class="offset-lg-1 col-lg-5">
            <div class="form-group">
                <label class="text-white display-5">Description</label>
                <input type="text" class="form-control" name="Description" id="Description" placeholder="Enter Description">
            </div>
        </div> --}}
        {{-- <div class="col-lg-5">
            <div class="form-group">
                <label class="text-white display-5">Currency (In PHP/ In US$/ In EUR)</label>
                <input type="text" class="form-control" name="Currency" id="Currency" placeholder="Enter Currency">
            </div>
        </div> --}}
        <div class="offset-lg-1 col-lg-10">
            <div class="form-group">
                <label class="text-white display-5">Customer Remarks</label>
                <textarea type="text" class="form-control" name="CustomerRemarks" id="CustomerRemarks" placeholder="Enter Customer Remarks" rows="5" required></textarea>
            </div>
        </div>
        {{-- <div class="offset-lg-1 col-lg-5">
            <div class="form-group">
                <label class="text-white display-5">Site Concerned</label>
                <select class="form-control js-example-basic-single" name="SiteConcerned" id="SiteConcerned" title="Select Site Concerned" >
                    <option value="" disabled selected>Select Site Concerned</option>
                    <option value="WHI Carmona">WHI Carmona</option>
                    <option value="WHI Head Office">WHI Head Office</option>
                    <option value="CCC Carmen">CCC Carmen</option>
                    <option value="PBI Canlubang">PBI Canlubang</option>
                    <option value="International Warehouse">International Warehouse</option>
                </select>
            </div>
        </div> --}}
        {{-- <div class="col-lg-5">
            <div class="form-group">
                <label class="text-white display-5">Department</label>
                <select class="form-control js-example-basic-single" name="Department" id="Department" title="Select Department" >
                    <option value="" disabled selected>Select Department</option>
                    @foreach($concern_department as $data)
                        <option value="{{ $data->id }}">{{ $data->Name }}</option>
                    @endforeach
                </select>
            </div>
        </div> --}}
        <div class="col-lg-11 mt-3 mb-3" align="right">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>
</form>
<style>
    .form-check-inline .form-check-input {
        width: 25px;
        height: 25px;
    }
    .form-check .form-check-label {
        margin-right: 3.75rem;
    }
    .select2-container {
        width: auto !important;
    }
    .break-spaces {
        white-space: break-spaces !important;
        white-space-collapse: break-spaces !important;
        text-wrap: wrap !important;
    }
</style>
<script>
    $(document).ready(function() {
        $('.js-example-basic-single').select2();

        // $('#QualityClass').on('change', function() {
        //     var selectedValue = $(this).val(); 
        //     if (selectedValue == "4") {
        //         $('#pName').show(); 
        //     } else {
        //         $('#pName').hide(); 
        //     }
        // });

        // function toggleInputs(checkboxId, inputClass) {
        //     document.getElementById(checkboxId).onchange = function() {
        //         const inputs = document.getElementsByClassName(inputClass);
        //         for (let input of inputs) {
        //             input.disabled = !this.checked;
        //         }
        //     };
        // }

        // toggleInputs('check-p1', 'p1-input');
        // toggleInputs('check-p2', 'p2-input');
        // toggleInputs('check-p3', 'p3-input');
        // toggleInputs('check-p4', 'p4-input');
        // toggleInputs('check-p5', 'p5-input');
        // toggleInputs('check-p6', 'p6-input');

        // toggleInputs('check-pack1', 'input-pack1');
        // toggleInputs('check-pack2', 'input-pack2');
        // toggleInputs('check-pack3', 'input-pack3');
        // toggleInputs('check-pack4', 'input-pack4');

        // toggleInputs('check-d1', 'd1-input');
        // toggleInputs('check-d2', 'd2-input');
        // toggleInputs('check-d3', 'd3-input');
        
        // toggleInputs('check-o1', 'o1-input');
        // toggleInputs('check-o2', 'o2-input');
        // toggleInputs('check-o3', 'o3-input');
        // toggleInputs('check-o4', 'o4-input');

        $('#form_complaint').on('submit', function(event) {
            event.preventDefault();

            var formData = new FormData(this);
            var submitBtn = $("button[type='submit']");
            
            // **Disable the button and show loading**
            submitBtn.prop("disabled", true).html('<i class="fa fa-spinner fa-spin"></i> Submitting...');

            $.ajax({
                url: "{{ route('customer_complaint2.store') }}",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        // Display a Swal success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Saved',
                            text: response.success,
                            timer: 2000,
                            showConfirmButton: false
                        }).then((result) => {
                            $('#form_complaint')[0].reset();
                            window.location.href = "{{ url('customer_service') }}";
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong. Please try again!',
                    });
                },
                complete: function() {
                    // **Re-enable the button after request is complete**
                    submitBtn.prop("disabled", false).html('Submit');
                }
            });
        });
    });
</script>
@endsection
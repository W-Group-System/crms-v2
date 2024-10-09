@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">Shipment Sample Evaluation List</h4>
            <div class="row height d-flex ">
                <div class="col-md-6 mt-2 mb-2">
                    <a href="#" id="copy_prospect_btn" class="btn btn-md btn-outline-info mb-1">Copy</a>
                    <a href="#" class="btn btn-md btn-outline-success mb-1">Excel</a>
                </div>
                <div class="col-md-6 mt-2 mb-2 text-right">
                    <button type="button" class="btn btn-md btn-outline-primary" id="addSseBtn" data-toggle="modal" data-target="#formSampleShipment">New</button>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <span>Show</span>
                    <form method="GET" class="d-inline-block">
                        <select name="number_of_entries" class="form-control" onchange="this.form.submit()">
                            <option value="10" @if($entries == 10) selected @endif>10</option>
                            <option value="25" @if($entries == 25) selected @endif>25</option>
                            <option value="50" @if($entries == 50) selected @endif>50</option>
                            <option value="100" @if($entries == 100) selected @endif>100</option>
                        </select>
                    </form>
                    <span>Entries</span>
                </div>
                <div class="col-lg-6">
                    <form method="GET" class="custom_form mb-3" enctype="multipart/form-data">
                        <div class="row height d-flex justify-content-end align-items-end">
                            <div class="col-lg-10">
                                <div class="search">
                                    <i class="ti ti-search"></i>
                                    <input type="text" class="form-control" placeholder="Search Shipment Sample" name="search" value="{{$search}}"> 
                                    <button class="btn btn-sm btn-info">Search</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div> 
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="spe_table" width="100%">
                    <thead>
                        <tr>
                            <th>Date Submitted</th>
                            <th>SSE #</th>
                            <th>Attention To</th>
                            <th>Raw Material Type</th>
                            <th>Grade</th>
                            <th>Origin</th>
                            <th>Supplier</th>
                            <th>Status</th>
                            <th>Progress</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($data->count() > 0)
                            @foreach($data as $shipment_sample)
                                <tr>
                                    <td>
                                        <a href="{{ url('sse/view/' . $shipment_sample->id) }}" title="View Sample Request">{{ $shipment_sample->DateSubmitted }}</a>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0);" class="edit" data-id="{{ $shipment_sample->id }}" title="Edit Shipment Sample">
                                            {{ $shipment_sample->SseNumber }}
                                        </a>
                                    </td>
                                    <td>{{ $shipment_sample->AttentionTo }}</td>
                                    <td>{{ $shipment_sample->RmType }}</td>
                                    <td>{{ $shipment_sample->Grade }}</td>
                                    <td>{{ $shipment_sample->Origin }}</td>
                                    <td>{{ $shipment_sample->Supplier }}</td>
                                    <td>
                                        @if($shipment_sample->Status == 10)
                                            <div class="badge badge-success">Open</div>
                                        @else
                                            <div class="badge badge-warning">Closed</div>
                                        @endif
                                    </td>
                                    <td></td>
                                </tr>   
                            @endforeach
                        @else
                            <tr>
                                <td colspan="9" align="center">No matching records found</td>
                            </tr>
                        @endif                 
                    </tbody>
                </table>
            </div>
            {!! $data->appends(['search' => $search, 'sort' => request('sort'), 'direction' => request('direction')])->links() !!}
            @php
                $total = $data->total();
                $currentPage = $data->currentPage();
                $perPage = $data->perPage();
                
                $from = ($currentPage - 1) * $perPage + 1;
                $to = min($currentPage * $perPage, $total);
            @endphp
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>Showing {{ $from }} to {{ $to }} of {{ $total }} entries</div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="formSampleShipment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Sample Shipment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" id="form_shipment_sample">
                    <span id="form_result"></span>
                    @csrf
                    <input type="text" name="SseNumber" value="{{ $newSseNo }}">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date Submitted (MM/DD/YYYY):</label>
                                <input type="date" class="form-control" id="DateSubmitted" name="DateSubmitted" value="">
                            </div>
                            <div class="form-group">
                                <label>Raw Material Type:</label>
                                <input type="text" class="form-control" id="RmType" name="RmType" placeholder="Enter Raw Material Type">
                            </div>
                            <div class="form-group">
                                <label>Grade:</label>
                                <input type="text" class="form-control" id="Grade" name="Grade" placeholder="Enter Grade">
                            </div>
                            <div class="form-group">
                                <label>Result:</label>
                                <select class="form-control js-example-basic-single" id="SseResult" name="SseResult" style="position: relative !important" title="Select Result">
                                    <option value="" disabled selected>Select Result</option>
                                    <option value="1">Old alternative product/ supplier</option>
                                    <option value="2">New Product WITHOUT SPE Result</option>
                                    <option value="3">First shipment with SPE result.</option>
                                </select>
                            </div>
                            <div class="form-group" id="otherResult" style="display: none;">
                                <input type="text" class="form-control" id="ResultSpeNo" name="ResultSpeNo" placeholder="Enter SPE #">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Attention To:</label>
                                <select id="AttentionTo" name="AttentionTo" class="form-control js-example-basic-single">
                                    <option disabled selected value>Select REF Code</option>
                                    @foreach ($refCode as $key=>$code)
                                        <option value="{{$key}}" @if(old('RefCode') == $key) selected @endif>{{$code}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Product Code:</label>
                                <input type="text" class="form-control" id="ProductCode" name="ProductCode" placeholder="Enter Product Code">
                            </div>
                            <div class="form-group">
                                <label>Origin:</label>
                                <input type="text" class="form-control" id="Origin" name="Origin" placeholder="Enter Origin">
                            </div>
                            <div class="form-group">
                                <label>Supplier:</label>
                                <input type="text" class="form-control" id="Supplier" name="Supplier" placeholder="Enter Supplier">
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
                                <input type="text" class="form-control" id="PoNumber" name="PoNumber" placeholder="Enter Po Number">
                            </div>
                            <div class="form-group">
                                <label>Product ordered is:</label>
                                <select class="form-control js-example-basic-single" id="ProductOrdered" name="ProductOrdered" style="position: relative !important" title="Select Product Ordered">
                                    <option value="" disabled selected>Select Product Ordered</option>
                                    <option value="1">For Shipment by supplier</option>
                                    <option value="2">In transit to Manila or Plant</option>
                                    <option value="3">Delivered to plant & on stock</option>
                                    <option value="4">Shipped out to buyer</option>
                                    <option value="5">Others</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Quantity:</label>
                                <input type="text" class="form-control" id="Quantity" name="Quantity" placeholder="Enter Quantity">
                            </div>
                            <div class="form-group">
                                <label>Ordered:</label>
                                <input type="text" class="form-control" id="Ordered" name="Ordered" placeholder="Enter Ordered">
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
                                <input type="text" class="form-control" id="Buyer" name="Buyer" placeholder="Enter Buyer">
                            </div>
                            <div class="form-group">
                                <label>Buyer's PO #:</label>
                                <input type="text" class="form-control" id="BuyersPo" name="BuyersPo" placeholder="Enter Buyer's Po">
                            </div>
                            <div class="form-group">
                                <label>Instruction to Lab:</label>
                                <textarea type="text" class="form-control" id="Instruction" name="Instruction" placeholder="Enter Instruction" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Sales Agreement #:</label>
                                <input type="text" class="form-control" id="SalesAgreement" name="SalesAgreement" placeholder="Enter Sales Agreement">
                            </div>
                            <div class="form-group">
                                <label>Product Declared as:</label>
                                <input type="text" class="form-control" id="ProductDeclared" name="ProductDeclared" placeholder="Enter Product Declared">
                            </div>
                            <div class="form-group">
                                <label>Lot Numbers on bags:</label>
                                <input type="text" class="form-control" id="LnBags" name="LnBags" placeholder="Enter Lot Number on Bags">
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
                                <input class="form-check-input" type="radio" name="SampleType" id="SampleType" value="Pre-ship sample">
                                <label class="form-check-label">Pre-ship sample</label>
                                <input class="form-check-input" type="radio" name="SampleType" id="SampleType" value="Co-ship sample">
                                <label class="form-check-label">Co-ship sample</label>
                                <input class="form-check-input" type="radio" name="SampleType" id="SampleType" value="Complete samples">
                                <label class="form-check-label">Complete samples</label>
                                <input class="form-check-input" type="radio" name="SampleType" id="SampleType" value="Partial samples. More samples to follow">
                                <label class="form-check-label">Partial samples. More samples to follow</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" id="lotNoContainer">
                                <label>No of pack:</label>
                                <div class="input-group">         
                                    <input type="text" class="form-control" id="LotNumber" name="LotNumber[]" placeholder="Enter Lot Number">
                                    <button class="btn btn-sm btn-primary addRowBtn1" style="border-radius: 0px;" type="button">+</button>
                                </div>
                                <input type="text" class="form-control" id="QtyRepresented" name="QtyRepresented[]" placeholder="Enter Qty Represented">
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
                    <div class="modal-footer">
                        <input type="hidden" name="action" id="action" value="Save">
                        <input type="hidden" name="hidden_id" id="hidden_id">
                        <input type="hidden" id="deletedFiles" name="deletedFiles">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" name="action_button" id="action_button" class="btn btn-outline-success" value="Save">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<style>
    .is-invalid {
        border: 1px solid red;
    }
    #Name {
        width: 393px !important;
    }
</style>
<script>
    $(document).ready(function() {

        $('.table').tablesorter({
            theme: "bootstrap"
        })
        
        $('#SseResult').on('change', function() {
            var selectedValue = $(this).val(); 
            if (selectedValue == "3") {
                $('#otherResult').show(); 
            } else {
                $('#otherResult').hide(); 
            }
        });

        $(document).on('click', '.addRowBtn1', function() {
            var newRow = $('<div class="form-group" style="margin-top: 10px">' +
                        '<div class="input-group">' +         
                            '<input type="text" class="form-control" id="LotNumber" name="LotNumber[]" placeholder="Enter Lot Number">' +
                           '<button class="btn btn-sm btn-danger removeRowBtn1" style="border-radius: 0px;" type="button">-</button>' +
                        '</div>' +
                        '<input type="text" class="form-control" id="QtyRepresented" name="QtyRepresented[]" placeholder="Enter Qty Represented">' +
                    '</div>');

            // Append the new row to the container where addresses are listed
            $('#lotNoContainer').append(newRow);
        });

        $(document).on('click', '.removeRowBtn1', function() {
            $(this).closest('.form-group').remove();
        });

        $(document).on('click', '.addRowBtn2', function() {
            var newRow = $('<div class="form-group" style="margin-top: 10px">' +
                        '<div class="input-group">' +         
                            '<select class="form-control js-example-basic-single" name="Name[]" id="Name" title="Select Attachment Name">' +
                                '<option value="" disabled selected>Select Attachment Name</option>' +
                                '<option value="Sample">Sample</option>' +
                                '<option value="Specifications">Specifications</option>' +
                                '<option value="COA">COA</option>' +
                                '<option value="Recipe">Recipe</option>' +
                            '</select>' +
                           '<button class="btn btn-sm btn-danger removeRowBtn2" style="border-radius: 0px;" type="button">-</button>' +
                        '</div>' +
                        '<input type="file" class="form-control" id="Path" name="Path[]">' +
                    '</div>');

            // Append the new row to the container where addresses are listed
            $('#attachmentsContainer').append(newRow);

             // Reinitialize select2 for the new row
            $('.js-example-basic-single').select2();
        });

        $(document).on('click', '.removeRowBtn2', function() {
            $(this).closest('.form-group').remove();
        });

        $('#addSseBtn').click(function(){
            $('#formSampleShipment').modal('show'); 
            $('.modal-title').text("Add New Shipment Sample"); 
            $('#form_result').html(''); 
            $('#Sample Shipment')[0].reset(); 
            $('#action_button').val("Save"); 
            $('#action').val("Save");
            $('#hidden_id').val(''); 
        });
        
        $('#form_shipment_sample').on('submit', function(event){
            event.preventDefault();
            var action_url = '';

            if($('#action').val() == 'Save') {
                action_url = "{{ route('shipment_sample.store') }}";
            } else if ($('#action').val() == 'Edit') {
                var id = $('#hidden_id').val();  
                action_url = "{{ route('update_shipment_sample', ':id') }}".replace(':id', id);  
            }

            $.ajax({
                url: action_url,
                method: "POST",  
                data: new FormData(this),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')  
                },
                contentType: false,
                cache: false,
                processData: false,
                dataType: "json",
                success: function(data) {
                    var html = '';
                    if(data.errors) {
                        html = '<div class="alert alert-danger">';
                        for(var count = 0; count < data.errors.length; count++) {
                            html += '<p>' + data.errors[count] + '</p>';
                        }
                        html += '</div>';
                        $('#formSampleShipment').scrollTop(0);
                        $('#form_result').html(html);
                    }
                    if (data.success) {
                        // Use SweetAlert2 for the success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: data.success,
                            timer: 1500, // Auto-close after 2 seconds
                            showConfirmButton: false
                        }).then(() => {
                            $('#form_shipment_sample')[0].reset();
                            $('#formSampleShipment').modal('hide');
                            location.reload();
                            $('#form_result').empty(); 
                        });
                    }
                }
            });
        });

        $(document).on('click', '.edit', function() {
            var id = $(this).data('id');
            $('#hidden_id').val(id);
            $('#action').val('Edit');

            $.ajax({
                url: "{{ route('edit_shipment_sample', ':id') }}".replace(':id', id),
                dataType: "json",
                success: function(data) {
                    $('#RmType').val(data.data.RmType);
                    $('#DateSubmitted').val(data.data.DateSubmitted);
                    $('#AttentionTo').val(data.data.AttentionTo).trigger('change');
                    $('#ProductCode').val(data.data.ProductCode);
                    $('#Quantity').val(data.data.Quantity);
                    $('#Supplier').val(data.data.Supplier).trigger('change');
                    $('#SseResult').val(data.data.SseResult).trigger('change');
                    $('#Grade').val(data.data.Grade);
                    $('#Origin').val(data.data.Origin);
                    $('#ResultSpeNo').val(data.data.ResultSpeNo);
                    $('#PoNumber').val(data.data.PoNumber);
                    $('#Ordered').val(data.data.Ordered);
                    $('#Buyer').val(data.data.Buyer);
                    $('#BuyersPo').val(data.data.BuyersPo);
                    $('#SalesAgreement').val(data.data.SalesAgreement);
                    $('#ProductDeclared').val(data.data.ProductDeclared);
                    $('#Instruction').val(data.data.Instruction);
                    $('#LnBags').val(data.data.LnBags);
                    $('#hidden_id').val(data.data.id);    
                    $('#attachmentsContainer .form-group').remove();

                    // Add all attachments dynamically
                    $.each(data.shipment_attachments, function(index, attachment) {
                        var attachmentRow = `
                            <div class="form-group attachment-row" style="margin-top: 10px" data-id="${attachment.id}">
                                <div class="input-group">
                                    <select class="form-control js-example-basic-single" name="Name[]" title="Select Attachment Name">
                                        <option value="Sample" ${attachment.name == 'Sample' ? 'selected' : ''}>Sample</option>
                                        <option value="Specifications" ${attachment.name == 'Specifications' ? 'selected' : ''}>Specifications</option>
                                        <option value="COA" ${attachment.name == 'COA' ? 'selected' : ''}>COA</option>
                                        <option value="Recipe" ${attachment.name == 'Recipe' ? 'selected' : ''}>Recipe</option>
                                    </select>
                                    <button class="btn btn-sm btn-danger removeRowBtn" style="border-radius: 0px;" type="button">-</button>
                                </div>
                                <input type="hidden" name="FileId[]" value="${attachment.id}">
                                <input type="file" class="form-control" name="Path[]">
                                <a href="{{ url('storage/${attachment.path}') }}" target="_blank">${attachment.path}</a>
                            </div>
                        `;
                        $('#attachmentsContainer').append(attachmentRow);
                    });

                    $('.modal-title').text("Edit Shipment Sample");
                    $('#action_button').val("Update");
                    $('#action').val("Edit");
                    $('#formSampleShipment').modal('show');
                }
            });
        });

    });

</script>
@endsection
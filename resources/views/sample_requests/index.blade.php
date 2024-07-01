@extends('layouts.header')
@section('content')
<style>
    .form-header {
    align-items: center;
}

.header-label {
    padding: 50px 0px;
    font-weight: bold;
}

.form-divider {
    flex-grow: 1;
    border: none;
    border-top: 1px solid black;
}
</style>
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            Sample Request List
            <button type="button" class="btn btn-md btn-primary" name="add_sample_request" id="add_sample_request">Add Sample Request</button>
            </h4>
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="sample_request_table">
                    <thead>
                        <tr>
                            <th>SRF #</th>
                            <th>Date Requested</th>
                            <th>Date Required</th>
                            <th>Client Name</th>
                            <th>Application</th>
                            <th>Status</th>
                            <th>Progress</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="formSampleRequest" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Region</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_sample_request" enctype="multipart/form-data" action="{{ route('sample_request.store') }}">
                    <span id="form_result"></span>
                    @csrf
                    <?php
                     $now = date('Y-m-d\TH:i');
                     $today = date('Y-m-d');
                    ?>
                    <div class="form-header">
                        <span class="header-label">Request Details</span>
                        <hr class="form-divider">
                    </div>
                    <div class="row">    
                    <div class="col-md-6">
                    <div class="form-group">
                        <label for="DateRequested">Date Requested (MM/DD/YYYY Hour Min):</label>
                        <input type="datetime-local" class="form-control" id="DateRequested" name="DateRequested" value="<?php echo $now; ?>" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="DateRequired">Date Required (MM/DD/YYYY):</label>
                        <input type="date" class="form-control" id="DateRequired" name="DateRequired" value="<?php echo $today; ?>" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="DateStarted">Date Started (MM/DD/YYYY):</label>
                        <input type="date" class="form-control" id="DateStarted" name="DateStarted" value="<?php echo $today; ?>" placeholder="">
                    </div>
                    <div class="form-group">
                        <label>Primary Sales Person:</label>
                        <select class="form-control js-example-basic-single" name="PrimarySalesPerson" id="PrimarySalesPerson" style="position: relative !important" title="Select PrimarySalesPerson" >
                            <option value="" disabled selected>Primary Sales Person</option>
                            @foreach ($salesPersons as $salesPerson)
                                <option value="{{ $salesPerson->user_id }}" >{{ $salesPerson->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Secondary Sales Person:</label>
                        <select class="form-control js-example-basic-single" name="SecondarySalesPerson" id="SecondarySalesPerson" style="position: relative !important" title="Select SecondarySalesPerson" >
                            <option value="" disabled selected>Secondary Sales Person</option>
                            @foreach ($salesPersons as $salesPerson)
                                <option value="{{ $salesPerson->user_id }}" >{{ $salesPerson->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="SoNumber">SO Number</label>
                        <input type="text" class="form-control" id="SoNumber" name="SoNumber" placeholder="Enter SO Number">
                    </div>
                    <div class="form-group">
                        <label>REF Code:</label>
                        <select class="form-control js-example-basic-single" name="RefCode" id="RefCode" style="position: relative !important" title="Select Ref Code">
                            <option value="" disabled selected>Select REF Code</option>
                            <option value="1">RND</option>
                            <option value="2">QCD</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Type:</label>
                        <select class="form-control js-example-basic-single" name="SrfType" id="SrfType" style="position: relative !important" title="Select Type">
                            <option value="" disabled selected>Select Type</option>
                            <option value="1">Regular</option>
                            <option value="2">PSS</option>
                            <option value="3">CSS</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Client:</label>
                        <select class="form-control js-example-basic-single" name="ClientId" id="ClientId" style="position: relative !important" title="Select ClientId" onchange="generateUniqueId()">
                            <option value="" disabled selected>Select Client</option>
                            @foreach ($clients as $client)
                            <option value="{{ $client->id }}" data-type="{{ $client->Type }}">{{ $client->Name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Contact:</label>
                        <select class="form-control js-example-basic-single" name="ClientContactId" id="ClientContactId" style="position: relative !important" title="Select ClientContacId">
                            <option value="" disabled selected>Select Contact</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="Remarks">Remarks (Internal)</label>
                        <textarea class="form-control" id="Remarks" name="Remarks" placeholder="Enter Remarks"></textarea>
                    </div>
                   
                    <div class="form-group" hidden >
                        <label for="SrfNumber">Unique ID:</label>
                        <input type="text" class="form-control" id="SrfNumber" name="SrfNumber" readonly>
                    </div>
                </div>
            </div>
            <div class="modal-footer"></div>
            <div class="form-header">
                <span class="header-label">Product</span>
                <hr class="form-divider">
            </div>
            <div class="row form_request_product" >  
                <div class="col-lg-12">
                    <button type="button" class="btn btn-danger deleteRowBtn" hidden style="float: right;">Delete Row</button>
                </div>  
            <div class="col-md-6">
                <div class="form-group">
                    <label>Product Type:</label>
                    <select class="form-control js-example-basic-single" name="ProductType[]" id="ProductType" style="position: relative !important" title="Select Product Type">
                        <option value="" disabled selected>Select Product Type</option>
                        <option value="1">Pure</option>
                        <option value="2">Blend</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Application:</label>
                    <select class="form-control js-example-basic-single" name="ApplicationId[]" id="ApplicationId" style="position: relative !important" title="Select Application" >
                        <option value="" disabled selected>Select Application</option>
                        @foreach ($productApplications as $application)
                            <option value="{{ $application->id }}" >{{ $application->Name }}</option>
                        @endforeach
                    </select>
                </div>
            <div class="form-group">
                <label for="ProductCode">Product Code:</label>
                <input type="text" class="form-control" id="ProductCode" name="ProductCode[]" placeholder="Enter Product Code">
            </div>
            <div class="form-group">
                <label for="ProductDescription">Product Description:</label>
                <textarea class="form-control" id="ProductDescription" name="ProductDescription[]" placeholder="Enter Product Description" rows="8"></textarea>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="NumberOfPackages">Number Of Packages</label>
                <input type="number" class="form-control" id="NumberOfPackages" name="NumberOfPackages[]" value="0">
            </div>
            <div class="row">
                <div class="col-md-7">
                    <div class="form-group">
                        <label for="Quantity">Quantity</label>
                        <input type="number" class="form-control" id="Quantity" name="Quantity[]" value="0">
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label>Unit</label>
                        <select class="form-control js-example-basic-single" name="UnitOfMeasure[]" id="UnitOfMeasure" style="position: relative !important" title="Select Unit">
                            <option value="1">Grams</option>
                            <option value="2">Kilograms</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group" >
                <label for="Label">Label:</label>
                <input type="text" class="form-control" id="Label" name="Label[]">
            </div>
            <div class="form-group" >
                <label for="RpeNumber">RPE Number:</label>
                <input type="text" class="form-control" id="RpeNumber" name="RpeNumber[]">
            </div>
            <div class="form-group" >
                <label for="CrrNumber">CRR Number:</label>
                <input type="text" class="form-control" id="CrrNumber" name="CrrNumber[]">
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label for="RemarksProduct">Remarks</label>
                <textarea class="form-control" id="RemarksProduct" name="RemarksProduct[]" placeholder="Enter Remarks"></textarea>
            </div>
        </div>

    </div>
    <button type="button" class="btn btn-primary" id="addProductRowBtn">Add Row</button>

    <div class="modal-footer product-footer"></div>
    <div class="form-header">
        <span class="header-label">Dispatch Details</span>
        <hr class="form-divider">
    </div>
    <div class="row" >
    <div class="col-md-6">
        <div class="form-group">
            <label for="Courier">Courier:</label>
            <input type="text" class="form-control" id="Courier" name="Courier" placeholder="Enter Courier">
        </div>
        <div class="form-group">
            <label for="AwbNumber">AWB Number:</label>
            <input type="text" class="form-control" id="AwbNumber" name="AwbNumber" placeholder="Enter AWB Number">
        </div>
        <div class="form-group">
            <label for="DateDispatched">Date Dispatched (MM/DD/YYYY):</label>
            <input type="date" class="form-control" id="DateDispatched" name="DateDispatched"  placeholder="Enter Date Dispatched">
        </div>
        <div class="form-group">
            <label for="DateSampleReceived">Date Sample Received (MM/DD/YYYY):</label>
            <input type="date" class="form-control" id="DateSampleReceived" name="DateSampleReceived"  placeholder="Enter Sample Received">
        </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label for="DeliveryRemarks">Delivery Remarks</label>
        <textarea class="form-control" id="DeliveryRemarks" name="DeliveryRemarks" placeholder="Enter Delivery Remarks"></textarea>
    </div>
    <div class="form-group">
        <label for="Note">Notes</label>
        <textarea class="form-control" id="Note" name="Note" placeholder="Enter Delivery Notes"></textarea>
    </div>
</div>
</div>
                    <div class="modal-footer">

                        {{-- <input type="hidden" name="action" id="action" value="Save">
                        <input type="hidden" name="hidden_id" id="hidden_id"> --}}
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="submit"  class="btn btn-success" value="Save">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script> 

<script>
    $(document).ready(function(){
        $('#sample_request_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('sample_request.index') }}"
            },
            columns: [
                {
                    data: 'SrfNumber',
                    name: 'SrfNumber'
                },
                {
                    data: 'DateRequested',
                    name: 'DateRequested'
                },
                {
                    data: 'DateRequired',
                    name: 'DateRequired'
                },
                {
                    data: 'client.Name',
                    name: 'client.Name'
                },
                {
                    data: 'applications.Name',
                    name: 'applications.Name'
                },
                {
                    data: 'Status',
                    name: 'Status'
                },
                {
                    data: 'Progress',
                    name: 'Progress'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false
                }
            ]
        });
    });

    function generateUniqueId() {
        const clientSelect = document.getElementById('ClientId');
        const clientId = clientSelect.value;
        const clientType = clientSelect.options[clientSelect.selectedIndex].getAttribute('data-type');
        const dateRequested = document.getElementById('DateRequested').value;
        const year = new Date(dateRequested).getFullYear().toString().slice(-2);
        let clientCode = clientType == 1 ? 'LS' : 'IS';

        fetch(`sample_get-last-increment-f/${year}/${clientCode}`)
            .then(response => response.json())
            .then(data => {
                const lastIncrement = data.lastIncrement;
                const increment = ('000' + (parseInt(lastIncrement) + 1)).slice(-4);
                const uniqueId = `SRF-${clientCode}-${year}-${increment}`;
                document.getElementById('SrfNumber').value = uniqueId;
            });
    }
    
        // Add new form row
    $('#addProductRowBtn').click(function() {
        var newRow = $('.form_request_product').first().clone();

        newRow.find('input').each(function() {
            $(this).val('');
        });

        newRow.find('select').removeClass('select2-hidden-accessible').next('.select2-container').remove();

        newRow.insertBefore('.product-footer');

        newRow.find('.js-example-basic-single').select2();

        newRow.find('.deleteRowBtn').removeAttr('hidden');

        newRow.find('.deleteRowBtn').click(function() {
            $(this).closest('.form_request_product').remove();
        });
    });


    $(document).ready(function() {
        $('#ClientId').on('change', function() {
            var clientId = $(this).val();
            if(clientId) {
                $.ajax({
                    url: '{{ url("sample_contacts-by-client-f") }}/' + clientId,
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
            } else {
                $('#ClientContactId').empty();
            }
        });
    });
    $('#add_sample_request').click(function(){
            $('#formSampleRequest').modal('show');
            $('.modal-title').text("Add Sample Request");
        });
</script>
@endsection
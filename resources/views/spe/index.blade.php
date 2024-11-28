@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">Supplier Product Evaluation List</h4>
            <div class="row height d-flex ">
                <div class="col-md-6 mt-2 mb-2">
                    <a href="#" id="copy_prospect_btn" class="btn btn-md btn-outline-info mb-1">Copy</a>
                    <a href="#" class="btn btn-md btn-outline-success mb-1">Excel</a>
                </div>
                @if(auth()->user()->role->type == 'PRD')
                    <div class="col-md-6 mt-2 mb-2 text-right">
                        <button type="button" class="btn btn-md btn-outline-primary" id="addSpeBtn" data-toggle="modal" data-target="#formSupplierProduct">New</button>
                    </div>
                @endif
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
                                    <input type="text" class="form-control" placeholder="Search Supplier Product" name="search" value="{{$search}}"> 
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
                            <th>Action</th>
                            <th>Date Requested</th>
                            <th>SPE #</th>
                            <th>Attention To</th>
                            <th>Product Name</th>
                            <th>Suplier/ Trader Name</th>
                            <th>Deadline</th>
                            <th>Status</th>
                            <th>Progress</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($data->count() > 0)
                            @foreach($data as $supplier_products)
                                <tr>
                                    <td align="center">
                                        @if(auth()->user()->role->type == 'PRD' && auth()->id() == $supplier_products->PreparedBy)
                                            <a href="javascript:void(0);"  class="edit btn btn-sm btn-outline-warning" data-id="{{ $supplier_products->id }}" title="Edit Supplier Product"><i class="ti-pencil"></i></a>
                                        @else 
                                            <a href="#" style="pointer-events: none;" class="edit btn btn-sm btn-outline-warning" data-id="{{ $supplier_products->id }}" title="Edit Supplier Product"><i class="ti-pencil"></i></a>
                                        @endif
                                    </td>
                                    <td>{{ $supplier_products->DateRequested }}</td>
                                    <td>
                                        <a href="{{ url('spe/view/' . $supplier_products->id) }}" title="View Sample Request">{{ $supplier_products->SpeNumber }}</a>
                                    </td>
                                    <td>{{ $supplier_products->AttentionTo }}</td>
                                    <td>{{ $supplier_products->ProductName }}</td>
                                    <td>{{ $supplier_products->suppliers->Name }}</td>
                                    <td>{{ $supplier_products->Deadline }}</td>
                                    <td>
                                        @if($supplier_products->Status == 10)
                                            <div class="badge badge-success">Open</div>
                                        @else
                                            <div class="badge badge-warning">Closed</div>
                                        @endif
                                    </td>
                                    <td>{{ $supplier_products->progress->name }}</td>
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

<div class="modal fade" id="formSupplierProduct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Supplier Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" id="form_supplier_product">
                    <span id="form_result"></span>
                    @csrf
                    <?php
                        $today = date('Y-m-d');
                    ?>
                    <input type="hidden" name="SpeNumber" value="{{ $newSpeNo }}">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date Requested (DD/MM/YYYY):</label>
                                <input type="date" class="form-control DateRequested" id="DateRequested"  name="DateRequested" value="{{  old('DateRequested', $today) }}" readonly>
                            </div>
                            <div class="form-group">
                                <label>Deadline (DD/MM/YYYY):</label>
                                <input type="date" class="form-control" id="Deadline" name="Deadline">
                            </div>
                            <div class="form-group">
                                <label>Product Name:</label>
                                <input type="text" class="form-control" id="ProductName" name="ProductName" placeholder="Enter Product Name">
                            </div>
                            <div class="form-group">
                                <label>Supplier/ Trader Name:</label>
                                <select class="form-control js-example-basic-single" id="Supplier" name="Supplier" style="position: relative !important" title="Select ClientId">
                                    <option value="" disabled selected>Select Client</option>
                                    @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->Id }}">{{ $supplier->Name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Origin:</label>
                                <input type="text" class="form-control" id="Origin" name="Origin" placeholder="Enter Origin">
                            </div>
                            <div class="form-group">
                                <label>Price:</label>
                                <input type="text" class="form-control" id="Price" name="Price" placeholder="Enter Price">
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
                                <label>Manufacturer of Sample:</label>
                                <input type="text" class="form-control" id="Manufacturer" name="Manufacturer" placeholder="Enter Manufacturer">
                            </div>
                            <div class="form-group">
                                <label>Quantity:</label>
                                <input type="text" class="form-control" id="Quantity" name="Quantity" placeholder="Enter Quantity">
                            </div>
                            <div class="form-group">
                                <label>Product Application:</label>
                                <input type="text" class="form-control" id="ProductApplication" name="ProductApplication" placeholder="Enter Product Application">
                            </div>
                            <div class="form-group">
                                <label>Lot No./ Batch No.:</label>
                                <input type="text" class="form-control" id="LotNo" name="LotNo" placeholder="Enter Lot No./ Batch No.">
                            </div>
                            <div class="form-group">
                                <label>Instruction to Laboratory:</label>
                                <select class="form-control js-example-basic-multiple" id="Instruction" name="Instruction[]" style="position: relative !important" multiple>
                                    <option value="Physical Chemical Testing">Physical Chemical Testing</option>
                                    <option value="Microbiological Testing">Microbiological Testing</option>
                                    <option value="Mesh Analysis">Mesh Analysis</option>
                                </select>
                            </div>
                            @if(auth()->user()->role->type == 'RND' || auth()->user()->role->type == 'QCD-WHI' || auth()->user()->role->type == 'QCD-PBI' || auth()->user()->role->type == 'QCD-MRDC' || auth()->user()->role->type == 'QCD-CCC')
                                <div class="form-group">
                                    <label>Disposition:</label>
                                    <select class="form-control js-example-basic-single" id="Disposition" name="Disposition" style="position: relative !important" title="Select Disposition">
                                        <option value="" disabled selected>Select Disposition</option>
                                        <option value="1">Almost an exact match with the current product. The Sample works with direct replacement in the application.</option>
                                        <option value="2">Has higher quality than the existing raw materials. Needs dilution or lower proportion in product applications.</option>
                                        <option value="3">Has lower quality than the existing product. Needs higher proportion in product applications.</option>
                                        <option value="4">Cannot be fully evaluated. The company does not have a testing capability</option>
                                        <option value="5">Rejected. Does not pass the critical parameters of the test</option>
                                    </select>
                                </div>
                            @endif
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
        width: 383px !important;
    }
</style>

<script>
    $(document).ready(function() {

        $('.table').tablesorter({
            theme: "bootstrap"
        })

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

        $("#formSupplierProduct").on('hidden.bs.modal', function() {
            $("[name='AttentionTo']").val(null).trigger('change');
            $("[name='Supplier']").val(null).trigger('change');
            $("[name='Instruction[]']").val(null).trigger('change');
        })

        $('#addSpeBtn').click(function(){
            $('#formSupplierProduct').modal('show'); 
            $('.modal-title').text("Add New Supplier Product"); 
            $('#form_result').html(''); 
            $('#form_supplier_product')[0].reset(); 
            $('#action_button').val("Save"); 
            $('#action').val("Save");
            $('#hidden_id').val(''); 
        });

        $('#form_supplier_product').on('submit', function(event){
            event.preventDefault();
            var action_url = '';

            if($('#action').val() == 'Save') {
                action_url = "{{ route('supplier_product.store') }}";
            } else if ($('#action').val() == 'Edit') {
                var id = $('#hidden_id').val();  
                action_url = "{{ route('update_spe', ':id') }}".replace(':id', id);  
            }

            $.ajax({
                url: action_url,
                method: "POST",  // You can use 'PUT' if you're following RESTful convention
                data: new FormData(this),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')  // Include the CSRF token
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
                        $('#formSupplierProduct').scrollTop(0);
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
                            $('#form_supplier_product')[0].reset();
                            $('#formSupplierProduct').modal('hide');
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
                url: "{{ route('edit_spe', ':id') }}".replace(':id', id),
                dataType: "json",
                success: function(data) {
                    $('#ProductName').val(data.data.ProductName);
                    $('#DateRequested').val(data.data.DateRequested);
                    $('#AttentionTo').val(data.data.AttentionTo).trigger('change');
                    $('#Deadline').val(data.data.Deadline);
                    $('#Manufacturer').val(data.data.Manufacturer);
                    $('#Quantity').val(data.data.Quantity);
                    $('#Supplier').val(data.data.Supplier).trigger('change');
                    $('#ProductApplication').val(data.data.ProductApplication);
                    $('#Origin').val(data.data.Origin);
                    $('#LotNo').val(data.data.LotNo);
                    $('#Price').val(data.data.Price);
                    $('#Instruction').val(data.instructions).trigger('change');
                    $('#hidden_id').val(data.data.id);    
                    $('#attachmentsContainer .form-group').remove();

                    // Add all attachments dynamically
                    $.each(data.attachments, function(index, attachment) {
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

                    $('.modal-title').text("Edit Supplier Product");
                    $('#action_button').val("Update");
                    $('#action').val("Edit");
                    $('#formSupplierProduct').modal('show');
                }
            });
        });

        // Handle file deletion
        $(document).on('click', '.removeRowBtn', function() {
            var attachmentRow = $(this).closest('.attachment-row');
            var fileId = attachmentRow.data('id');

            if (fileId) {
                // Store the file ID in a hidden input to delete it later
                var deletedFiles = $('#deletedFiles').val();
                deletedFiles = deletedFiles ? deletedFiles.split(',') : [];
                deletedFiles.push(fileId);
                $('#deletedFiles').val(deletedFiles.join(','));
            }

            // Remove the file row from the form
            attachmentRow.remove();
        });


    });
</script>
@endsection
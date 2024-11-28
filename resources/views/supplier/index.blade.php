    @extends('layouts.header')
    @section('content')
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title d-flex justify-content-between align-items-center">Supplier List</h4>
                <div class="row height d-flex ">
                    <div class="col-md-6 mt-2 mb-2">
                        <a href="#" id="copy_prospect_btn" class="btn btn-md btn-outline-info mb-1">Copy</a>
                        <a href="{{url('export_prospect_client')}}" class="btn btn-md btn-outline-success mb-1">Excel</a>
                    </div>
                    <div class="col-md-6 mt-2 mb-2 text-right">
                        <button type="button" class="btn btn-md btn-outline-primary"  name="add_supplier" id="add_supplier">New</button></a>
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
                                        <input type="text" class="form-control" placeholder="Search Supplier" name="search" value="{{$search}}"> 
                                        <button class="btn btn-sm btn-info">Search</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div> 
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="supplier_table" width="100%">
                        <thead>
                            <tr>
                                <!-- <th></th> -->
                                <th>Supplier Name</th>
                                <th>Products/ Services</th>
                                <th>Contact Person</th>
                                <th>Mobile No.</th>
                                <th>Terms</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($data->count() > 0)
                                @foreach($data as $supplier_data)
                                <tr>
                                    <!-- <td>
                                        <button type="button" class="btn btn-sm btn-danger delete" data-id="{{ $supplier_data->Id }}" title='Delete Issue Category'>
                                            <i class="ti-trash"></i> 
                                        </button>
                                    </td> -->
                                    <td>
                                        <a href="javascript:void(0);" class="edit" data-id="{{ $supplier_data->Id }}" title="Edit Supplier">
                                            {{ optional($supplier_data)->Name }}
                                        </a>
                                    </td>
                                    <td>{{ $supplier_data->Products }}</td>
                                    <td>
                                        @foreach($supplier_data->supplier_contacts as $contact)
                                            {{ $contact->ContactPerson }}@if (!$loop->last)/ @endif
                                        @endforeach
                                    </td>
                                    <td>{{ $supplier_data->MobileNo }}.</td>
                                    <td>{{ optional($supplier_data->payment_terms)->Name}}</td>
                                    <td>
                                        @if($supplier_data->Status == 1)
                                            <div class="badge badge-success">Active</div>
                                        @else
                                            <div class="badge badge-danger">Inactive</div>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
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
    <div class="modal fade" id="formSupplier" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New Supplier</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" enctype="multipart/form-data" id="form_supplier" action="" >
                        <span id="form_result"></span>
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Supplier Name</label>
                                    <input type="text" class="form-control" id="Name" name="Name" placeholder="Enter Supplier Name">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Products/ Services</label>
                                    <input type="text" class="form-control" id="Products" name="Products" placeholder="Enter Products/ Services">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Producer/ Distributor</label>
                                    <input type="text" class="form-control" id="Distributor" name="Distributor" placeholder="Enter Producer/ Distributor">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Origin</label>
                                    <input type="text" class="form-control" id="Origin" name="Origin" placeholder="Enter Origin">
                                </div>
                            </div>
                            <div class="col-lg-6" id="contactContainer">
                                <div class="form-group">
                                    <label>Contact Person</label>
                                    <div class="input-group">                                
                                        <input type="text" class="form-control" id="ContactPerson" name="ContactPerson[]" placeholder="Enter Contact Person">
                                        <button class="btn btn-sm btn-primary addRowBtn" style="border-radius: 0px;" type="button">+</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Address</label>
                                    <textarea type="text" class="form-control" id="Address" name="Address" placeholder="Enter Address" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Tel No.</label>
                                    <input type="text" class="form-control" id="TelNo" name="TelNo" placeholder="Enter Telephone No.">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Fax No.</label>
                                    <input type="text" class="form-control" id="FaxNo" name="FaxNo" placeholder="Enter Fax No.">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Mobile No.</label>
                                    <input type="text" class="form-control" id="MobileNo" name="MobileNo" placeholder="Enter Mobile No.">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Primary Email</label>
                                    <input type="email" class="form-control" id="Email" name="Email" placeholder="Enter Email Address">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Secondary Email</label>
                                    <input type="email" class="form-control" id="Email2" name="Email2" placeholder="Enter Email Address">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Terms</label>
                                    <select class="form-control js-example-basic-single" name="Terms" id="Terms" style="position: relative !important" title="Select Payment Term" >
                                        <option value="" disabled selected>Select Payment Term</option>
                                        @foreach($payment_terms as $payment_term)
                                            <option value="{{ $payment_term->id }}">{{ $payment_term->Name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" name="Status" value="1">
                            <div class="modal-footer col-lg-12">
                                <input type="hidden" name="action" id="action" value="Save">
                                <input type="hidden" name="hidden_id" id="hidden_id">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <input type="submit" name="action_button" id="action_button" class="btn btn-success" value="Save">
                            </div>
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
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).on('click', '.addRowBtn', function() {
            var newRow = $('<div class="form-group">' +
                            '<label>Contact Person</label>' +
                            '<div class="input-group">' +
                                '<input type="text" class="form-control" id="ContactPerson"  name="ContactPerson[]" placeholder="Enter Contact Person">' +
                                '<button class="btn btn-sm btn-danger removeRowBtn"  style="border-radius: 0px;" type="button">-</button>' +
                            '</div>' +
                        '</div>');
            $('#contactContainer').append(newRow);
        });

        $(document).on('click', '.removeRowBtn', function() {
            $(this).closest('.form-group').remove();
        });

        $(document).ready(function(){
            $('.table').tablesorter({
                theme: "bootstrap"
            })

            // Clear errors when the modal is shown
            $('#add_supplier').click(function(){
                $('#formSupplier').modal('show');
                $('.modal-title').text("Add Supplier");
                $('#form_result').html(''); // Clear previous validation errors
                $('#form_supplier')[0].reset(); // Clear form fields
                $('.form-control').removeClass('is-invalid'); // Remove the is-invalid class
            });

            $('#form_supplier').on('submit', function(event){
                event.preventDefault();
                var action_url = '';

                if($('#action').val() == 'Save') {
                    action_url = "{{ route('supplier.store') }}";
                } else if($('#action').val() == 'Edit') {
                    action_url = "{{ route('update_supplier', ':Id') }}".replace(':Id', $('#hidden_id').val());
                }

                $.ajax({
                    url: action_url,
                    method: "POST",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: "json",
                    success: function(data) {
                        var html = '';
                        // Remove previous error classes
                        $('.form-control').removeClass('is-invalid');
                        $('#form_result').html(''); // Clear previous error messages

                        if(data.errors) {
                            html = '<div class="alert alert-danger">';
                            // Loop through the errors
                            $.each(data.errors, function(key, value) {
                                html += '<p>' + value + '</p>';
                                // Add the is-invalid class to the relevant input field
                                $('#' + key).addClass('is-invalid');
                            });
                            html += '</div>';
                            $('#formSupplier').scrollTop(0);
                            $('#form_result').html(html);
                        }
                        if (data.success) {
                            // Use SweetAlert2 for the success message
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: data.success,
                                timer: 1500, // Auto-close after 1.5 seconds
                                showConfirmButton: false
                            }).then(() => {
                                $('#form_supplier')[0].reset();
                                $('#formSupplier').modal('hide');
                                location.reload();
                                $('#form_result').empty();
                            });
                        }
                    }
                });
            });

            $(document).on('click', '.edit', function() {
                var id = $(this).data('id');
                $.ajax({
                    url: "{{ route('edit_supplier', ':Id') }}".replace(':Id', id),
                    dataType: "json",
                    success: function(data) {
                        $('#Name').val(data.data.Name);
                        $('#Products').val(data.data.Products);
                        $('#Distributor').val(data.data.Distributor);
                        $('#Origin').val(data.data.Origin);
                        $('#TelNo').val(data.data.TelNo);
                        $('#FaxNo').val(data.data.FaxNo);
                        $('#MobileNo').val(data.data.MobileNo);
                        $('#Email').val(data.data.Email);
                        $('#Email2').val(data.data.Email2);
                        $('#Address').val(data.data.Address);
                        $('#Terms').val(data.data.Terms).trigger('change');
                        $('#hidden_id').val(data.data.Id);
                        $('#action_button').val("Update");
                        $('#action').val("Edit");
                        $('.modal-title').text("Edit Supplier");
                        $('#formSupplier').modal('show');
                        
                        // Clear existing contact person inputs
                        $('#contactContainer').empty();

                        // Loop through the contacts and create input fields
                        data.data.supplier_contacts.forEach(function(contact) {
                            $('#contactContainer').append(`
                                <div class="form-group">
                                    <label>Contact Person</label>
                                    <div class="input-group">                                
                                        <input type="text" class="form-control" name="ContactPerson[]" value="${contact.ContactPerson}" placeholder="Enter Contact Person">
                                        <button class="btn btn-sm btn-danger removeRowBtn" style="border-radius: 0px;" type="button">-</button>
                                        <button class="btn btn-sm btn-primary addContactBtn" style="border-radius: 0px;" type="button">+</button>
                                    </div>
                                </div>
                            `);
                        });
                    }
                });
            });

            // Add new contact person input
            $(document).on('click', '.addContactBtn', function() {
                $('#contactContainer').append(`
                    <div class="form-group">
                        <label>Contact Person</label>
                        <div class="input-group">                                
                            <input type="text" class="form-control" name="ContactPerson[]" placeholder="Enter Contact Person">
                            <button class="btn btn-sm btn-danger removeRowBtn" style="border-radius: 0px;" type="button">-</button>
                        </div>
                    </div>
                `);
            });

            // Remove contact person input
            $(document).on('click', '.removeRowBtn', function() {
                $(this).closest('.form-group').remove();
            });
            

            $(document).on('click', '.delete', function() {
                var id = $(this).data('id'); // Get the supplier ID

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, inactive it!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('inactivate_supplier', ':id') }}".replace(':id', id), // Replace placeholder with actual ID
                            method: "POST",
                            dataType: "json",
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content') // CSRF token is mandatory for POST requests
                            },
                            success: function(data) {
                                Swal.fire({
                                    title: 'Inactive!',
                                    text: 'The supplier has been inactivated.',
                                    icon: 'success',
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    location.reload(); // Reload the page if necessary
                                });
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'An error occurred while inactivating the supplier.',
                                    icon: 'error'
                                });
                            }
                        });
                    }
                });
            });
        });
        
    </script>
@endsection
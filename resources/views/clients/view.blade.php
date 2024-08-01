@extends('layouts.header')
@section('content')
<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">View Client Details
                <div align="right">
                    <a href="{{ session('last_client_page', url('/client')) }}" class="btn btn-md btn-secondary">
                        <i class="icon-arrow-left"></i>&nbsp;Back
                    </a>
                    <a href="{{ url('/edit_client/' . $data->id) }}" class="btn btn-md btn-primary"><i class="ti ti-pencil"></i>&nbsp;Update</a>
                    <!-- <button type="button" class="btn btn-primary" title="Update Client" href="{{ url('/edit_client/' . $data->id) }}">
                        <i class="ti ti-pencil"></i>&nbsp;Update
                    </button> -->
                    <button type="button" class="prospectClient btn btn-warning" title="Prospect File" data-id="{{ $data->id }}">
                        <i class="ti ti-control-record"></i>&nbsp;Prospect
                    </button>
                    <button type="button" class="activateClient btn btn-success" title="Activate Client" data-id="{{ $data->id }}">
                        <i class="ti ti-check-box"></i>&nbsp;Activate
                    </button>
                    <button type="button" class="archivedClient btn btn-danger" title="Archived Client" data-id="{{ $data->id }}">
                        <i class="ti ti-archive"></i>&nbsp;Archive
                    </button>
                </div>
            </h4>
            <form class="form-horizontal" id="form_client" enctype="multipart/form-data" action="{{ url('update_client/'.$data->id) }}">
                <span id="form_result"></span>
                @csrf
                <div class="col-md-12">
                    <div class="form-group row mb-2" style="margin-top: 2em">
                        <label class="col-sm-3 col-form-label"><b>Buyer Code</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->BuyerCode }}</label>
                        </div>
                        <label class="col-sm-3 col-form-label"><b>Primary Account Manager</b></label>
                        <div class="col-sm-3">
                            <label>{{ $primaryAccountManager->full_name ?? 'No Primary Account Manager' }}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label class="col-sm-3 col-form-label"><b>SAP Code</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->SapCode ?? 'N/A'}}</label>
                        </div>
                        <label class="col-sm-3 col-form-label"><b>Secondary Account Manager</b></label>
                        <div class="col-sm-3">
                            <label>{{ $secondaryAccountManager->full_name ?? 'No Secondary Account Manager' }}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label class="col-sm-3 col-form-label"><b>Company Name</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->Name ?? 'N/A' }}</label>
                        </div>
                        <label class="col-sm-3 col-form-label"><b>Trade Name</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->TradeName ?? 'N/A' }}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label class="col-sm-3 col-form-label"><b>TIN</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->TaxIdentificationNumber ?? 'N/A '}}</label>
                        </div>
                        <label class="col-sm-3 col-form-label"><b>Telephone</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->TelephoneNumber ?? 'N/A' }}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label class="col-sm-3 col-form-label"><b>Payment Term</b></label>
                        <div class="col-sm-3">
                            <label>{{ $payment_terms->Name ?? 'N/A' }}</label>
                        </div>
                        <label class="col-sm-3 col-form-label"><b>FAX</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->FaxNumber ?? 'N/A' }}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label class="col-sm-3 col-form-label"><b>Type</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->Type == '1' ? 'Local' : 'International'}}</label>
                        </div>
                        <label class="col-sm-3 col-form-label"><b>Website</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->Website ?? 'N/A' }}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label class="col-sm-3 col-form-label"><b>Region</b></label>
                        <div class="col-sm-3">
                            <label>{{ $regions->Name ?? 'N/A' }}</label>
                        </div>
                        <label class="col-sm-3 col-form-label"><b>Email Address</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->Email ?? 'N/A' }}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label class="col-sm-3 col-form-label"><b>Country</b></label>
                        <div class="col-sm-3">
                            <label>{{ $countries->Name ?? 'N/A' }}</label>
                        </div>
                        <label class="col-sm-3 col-form-label"><b>Source</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->Source ?? 'N/A' }}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label class="col-sm-3 col-form-label"><b>Area</b></label>
                        <div class="col-sm-3">
                            <label>{{ $areas->Name ?? 'N/A' }}</label>
                        </div>
                        <label class="col-sm-3 col-form-label"><b>Business Type</b></label>
                        <div class="col-sm-3">
                            <label>{{ $business_types->Name ?? 'N/A' }}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label class="col-sm-3 col-form-label"><b>Industry</b></label>
                        <div class="col-sm-3">
                            <label>{{ $industries->Name ?? 'N/A' }}</label>
                        </div>
                        @if($addresses->isNotEmpty())
                            @foreach($addresses as $address)
                            <label class="col-sm-3 col-form-label"><b>{{ $address->AddressType }}</b></label>
                            <div class="col-sm-3">
                                <label>{{ $address->Address }}</label>
                            </div>
                            @endforeach
                        @else
                            <label class="col-sm-3 col-form-label"><b>Address</b></label>
                            <div class="col-sm-3">
                                <label>No Address Available</label>
                            </div>
                        @endif
                    </div>
                </div>
            </form>
            <ul class="nav nav-tabs viewTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="contacts-tab" data-toggle="tab" href="#contacts" role="tab" aria-controls="contacts" aria-selected="true">Contacts</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="files-tab" data-toggle="tab" href="#files" role="tab" aria-controls="files" aria-selected="false">Files</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="transactions-tab" data-toggle="tab" href="#transactions" role="tab" aria-controls="transactions" aria-selected="false">Transactions</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="activities-tab" data-toggle="tab" href="#activities" role="tab" aria-controls="activities" aria-selected="false">Activities</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="collection-tab" data-toggle="tab" href="#collection" role="tab" aria-controls="collection" aria-selected="false">Collection</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="productFiles-tab" data-toggle="tab" href="#productFiles" role="tab" aria-controls="productFiles" aria-selected="false">Product Files</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="transactionFiles-tab" data-toggle="tab" href="#transactionFiles" role="tab" aria-controls="transactionFiles" aria-selected="false">Transaction Files</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="contacts" role="tabpanel" aria-labelledby="contacts-tab">
                    <div class="form-group row mb-2">
                        <div class="col-md-6">
                            <button class="btn btn-success mb-2" type="button">Export</button>
                        </div>
                        <div class="col-md-6" align="right">
                            <button class="btn btn-primary mb-2" type="button" data-toggle="modal" data-target="#contactsModal">Add Contacts</button>
                        </div>
                    </div>
                    @include('clients.add_contacts')
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTable" width="100%">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>Name</th>
                                    <th>Designation</th>
                                    <th>Birthday</th>
                                    <th>Telephone</th>
                                    <th>Telephone 2</th>
                                    <th>Mobile</th>
                                    <th>Mobile 2</th>
                                    <th>Email</th>
                                    <th>Skype</th>
                                    <th>Viber</th>
                                    <th>WhatsApp</th>
                                    <th>Facebook</th>
                                    <th>LinkedIn</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data->contacts as $contact)
                                    <tr>
                                        <td>
                                            <button type="button" class="btn btn-warning btn-sm" title="Edit Client" data-toggle="modal" data-target="#edit_contact-{{ $contact->id }}">
                                                <i class="ti-pencil"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm deleteContact" title="Delete Client" data-id="{{ $contact->id }}">
                                                <i class="ti-trash"></i>
                                            </button>
                                        </td>
                                        <td>{{ $contact->ContactName }}</td>
                                        <td>{{ $contact->Designation ?? 'N/A' }}</td>
                                        <td>{{ $contact->Birthday ?? 'N/A'}}</td>
                                        <td>{{ $contact->PrimaryTelephone ?? 'N/A' }}</td>
                                        <td>{{ $contact->SecondaryTelephone ?? 'N/A' }}</td>
                                        <td>{{ $contact->PrimaryMobile ?? 'N/A' }}</td>
                                        <td>{{ $contact->SecondaryMobile ?? 'N/A'}}</td>
                                        <td>{{ $contact->EmailAddress ?? 'N/A' }}</td>
                                        <td>{{ $contact->Skype ?? 'N/A'}}</td>
                                        <td>{{ $contact->Viber ?? 'N/A'}}</td>
                                        <td>{{ $contact->WhatsApp ?? 'N/A'}}</td>
                                        <td>{{ $contact->Facebook ?? 'N/A'}}</td>
                                        <td>{{ $contact->LinkedIn ?? 'N/A'}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @foreach ($data->contacts as $contact)
                        @include('clients.edit_contacts')
                    @endforeach
                </div>
                <div class="tab-pane fade" id="files" role="tabpanel" aria-labelledby="files-tab">
                    <div class="form-group row mb-2">
                        <div class="col-md-6">
                            <button class="btn btn-success mb-2" type="button">Export</button>
                        </div>
                        <div class="col-md-6" align="right">
                            <button class="btn btn-primary mb-2" type="button" data-toggle="modal" data-target="#filesModal">Add Files</button>
                        </div>
                    </div>
                    @include('clients.add_files')
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTable" width="100%">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>Name</th>
                                    <th>File</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data->files as $file)
                                <tr>
                                    <td>
                                        <button type="button" class="btn btn-warning btn-sm" title="Edit File" data-toggle="modal" data-target="#edit_file-{{ $file->id }}">
                                            <i class="ti-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm deleteFile" title="Delete File" data-id="{{ $file->id }}">
                                            <i class="ti-trash"></i>
                                        </button>
                                    </td>
                                    <td>{{ $file->FileName}}</td>
                                    <td><a href="{{ url($file->Path) }}" target="_blank" download>{{ $file->Path }}</a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @foreach ($data->files as $file)
                        @include('clients.edit_files')
                    @endforeach
                </div>
                <div class="tab-pane fade" id="transactions" role="tabpanel" aria-labelledby="transactions-tab">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTable" width="100%">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Transaction Number</th>
                                </tr>
                            </thead>
                            <tbody>
                               
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="activities" role="tabpanel" aria-labelledby="activities-tab">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTable" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Schedule Date</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data->activities as $activity)
                                    <tr>
                                        <td>{{ $activity->ActivityNumber ?? 'N/A' }}</td>
                                        <td>{{ $activity->ScheduleFrom ?? 'N/A' }}</td>
                                        <td>{{ $activity->Title ?? 'N/A' }}</td>
                                        <td>{{ $activity->Status == 10 ? 'Open' : 'Closed' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div align="right" class="mt-3">
                <a href="{{ url('/client') }}" class="btn btn-secondary">Close</a>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<style>
    #form_product {
        padding: 20px 20px;
    }
    .viewTab .nav-link {
        padding: 15px;
    }
</style>
<script>
    $(document).ready(function() {
        $('.dataTable').DataTable();
        
        $('#form_client').on('submit', function(event) {
            event.preventDefault();

            $.ajax({
                url: "{{ url ('update_client/'.$data->id) }}",
                method: "POST",
                data: $(this).serialize(),
                dataType: "json",
                success: function(data) {
                    if (data.errors) {
                        var errorHtml = '<div class="alert alert-danger"><ul>';
                        $.each(data.errors, function(key, value) {
                            errorHtml += '<li>' + value + '</li>';
                        });
                        errorHtml += '</ul></div>';
                        $('#form_result').html(errorHtml).show();
                        $('html, body').animate({
                            scrollTop: $('#form_result').offset().top
                        }, 1000);
                    }
                    if (data.success) {
                        $('#form_result').html('<div class="alert alert-success">' + data.success + '</div>').show();
                        setTimeout(function(){
                            $('#form_result').hide();
                        }, 3000);
                        $('html, body').animate({
                            scrollTop: $('#form_client').offset().top
                        }, 1000);
                    }
                }
            });
        });

        // Contacts Tab
        $(".deleteContact").on('click', function() {
            var contactId = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "{{ url('delete_contact') }}/" + contactId,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 2000
                            }).then(function() {
                                location.reload();
                            });
                        },
                        error: function(response) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.responseJSON.error
                            });
                        }
                    });
                }
            });
        });

        // File Tab
        $(".deleteFile").on('click', function() {
            var fileId = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "{{ url('delete_file') }}/" + fileId,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 2000
                            }).then(function() {
                                location.reload();
                            });
                        },
                        error: function(response) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.responseJSON.error
                            });
                        }
                    });
                }
            });
        });

        // Activate 
        $(".activateClient").on('click', function() {
            var clientId = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "You want to activate this client!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, confirmed it!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "{{ url('activate_client') }}/" + clientId,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(function() {
                                location.reload();
                            });
                        }
                    });
                }
            });
        });

        // Prospect
        $(".prospectClient").on('click', function() {
            var clientId = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "You want to pursue this prospect client!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, confirmed it!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "{{ url('prospect_client') }}/" + clientId,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 2000
                            }).then(function() {
                                location.reload();
                            });
                        },
                        error: function(response) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.responseJSON.error
                            });
                        }
                    });
                }
            });
        });

        // Archived
        $(".archivedClient").on('click', function() {
            var clientId = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "You want to archive this client!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, confirmed it!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "{{ url('archived_client') }}/" + clientId,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(function() {
                                location.reload();
                            });
                        }
                    });
                }
            });
        });
    });

    $('#table_address thead').on('click', '.addRow', function(){
        var tr = '<tr>' +
            '<td><a style="padding: 10px 20px" href="javascript:;" class="btn btn-danger deleteRow">-</a></td>'+
            '<td><input type="text" name="AddressType[]" id="AddressType" class="form-control" placeholder="Enter Address Type"></td>'+
            '<td><input type="text" name="Address[]" id="Address" class="form-control adjust" placeholder="Enter Address"></td>'+
        '</tr>';

        $('tbody').append(tr);
    });

    $('#table_address tbody').on('click', '.deleteRow', function(){
        $(this).parent().parent().remove();
    });
    

</script>
@endsection

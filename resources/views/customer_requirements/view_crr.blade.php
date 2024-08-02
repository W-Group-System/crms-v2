@extends('layouts.header')
@section('content')

<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">View Client Details
                <div align="right">
                    <a href="{{ url('/customer_requirement') }}" class="btn btn-md btn-secondary">
                        <i class="icon-arrow-left"></i>&nbsp;Back
                    </a>
                    <button type="button" class="btn btn-warning" title="Update" data-toggle="modal" data-target="#updateCrr-{{$crr->id}}">
                        <i class="ti ti-pencil"></i>&nbsp;Update
                    </button>
                </div>
            </h4>
            <div class="col-md-12">
                <label><strong>Customer Details</strong></label>
                <hr style="margin-top: 0px; color: black; border-top-color: black;">

                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>Client :</b></label>
                    <div class="col-sm-3">
                        <label>{{$crr->client->Name}}</label>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>Client Trade Name :</b></label>
                    <div class="col-sm-3">
                        <label></label>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>Region :</b></label>
                    <div class="col-sm-3">
                        <label>{{$crr->client->clientregion->Name}}</label>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>Country :</b></label>
                    <div class="col-sm-3">
                        <label>{{$crr->client->clientcountry->Name}}</label>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <label><strong>Request Details</strong></label>
                <hr style="margin-top: 0px; color: black; border-top-color: black;">

                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>CRR # :</b></label>
                    <div class="col-sm-3">
                        <label>{{$crr->CrrNumber}}</label>
                    </div>
                    <label class="col-sm-3 col-form-label"><b>Primary Sales Person :</b></label>
                    <div class="col-sm-3">
                        <label>{{$crr->primarySales->full_name}}</label>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>Date Created :</b></label>
                    <div class="col-sm-3">
                        <label>{{date('M d Y', strtotime($crr->DateCreated))}}</label>
                    </div>
                    <label class="col-sm-3 col-form-label"><b>Secondary Sales Person :</b></label>
                    <div class="col-sm-3">
                        <label>{{$crr->secondarySales->full_name}}</label>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>Priority :</b></label>
                    <div class="col-sm-3">
                        <label>
                            @if($crr->Priority == 1)
                            Low
                            @elseif($crr->Priority == 3)
                            Medium
                            @elseif($crr->Priority == 5)
                            High
                            @endif
                        </label>
                    </div>
                    <label class="col-sm-3 col-form-label"><b>Status :</b></label>
                    <div class="col-sm-3">
                        <label>
                            @if($crr->Status == 10)
                            Open
                            @elseif($crr->Status == 30)
                            Closed
                            @endif
                        </label>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>Due Date :</b></label>
                    <div class="col-sm-3">
                        <label>{{date('M d Y', strtotime($crr->DueDate))}}</label>
                    </div>
                    <label class="col-sm-3 col-form-label"><b>Progress :</b></label>
                    <div class="col-sm-3">
                        <label>{{$crr->progressStatus->name}}</label>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>Application : </b></label>
                    <div class="col-sm-3">
                        <label>{{$crr->product_application->Name}}</label>
                    </div>
                    <label class="col-sm-3 col-form-label"><b>Nature of Request :</b></label>
                    <div class="col-sm-3">
                        
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>Competitor :</b></label>
                    <div class="col-sm-3">
                        <label></label>
                    </div>
                    <label class="col-sm-3 col-form-label"><b>REF CRR Number :</b></label>
                    <div class="col-sm-3">
                        <label>{{$crr->Competitor}}</label>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>Competitor Price :</b></label>
                    <div class="col-sm-3">
                        <label>{{$crr->CompetitorPrice}}</label>
                    </div>
                    <label class="col-sm-3 col-form-label"><b>REF RPE Number :</b></label>
                    <div class="col-sm-3">
                        <label>{{$crr->RefRpeNumber}}</label>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>Potential Volume :</b></label>
                    <div class="col-sm-3">
                        <label>{{$crr->PotentialVolume}}</label>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>Target Price :</b></label>
                    <div class="col-sm-3">
                        <label>{{$crr->TargetPrice}}</label>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>Details of Requirement :</b></label>
                    <div class="col-sm-3">
                        <label>{{$crr->DetailsOfRequirement}}</label>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <label><strong>Approver Remarks</strong></label>
                <hr style="margin-top: 0px; color: black; border-top-color: black;">
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>Sample :</b></label>
                </div>
            </div>
            <div class="col-md-12">
                <label><strong>Recommendation</strong></label>
                <hr style="margin-top: 0px; color: black; border-top-color: black;">

                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>DDW Number : </b></label>
                    <div class="col-sm-3">
                        <label>@if($crr->DdwNumber != null){{$crr->DdwNumber}}@else N/A @endif</label>
                    </div>
                    <label class="col-sm-3 col-form-label"><b>Date Received :</b></label>
                    <div class="col-sm-3">
                        <label>{{date('M d Y', strtotime($crr->DateReceived))}}</label>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>Recommendation : </b></label>
                    <div class="col-sm-3">
                        <label>{{$crr->Recommendation}}</label>
                    </div>
                    <label class="col-sm-3 col-form-label"><b>Date Completed :</b></label>
                    <div class="col-sm-3">
                        <label>{{date('M d Y', strtotime($crr->DateCompleted))}}</label>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>Days Late : </b></label>
                    <div class="col-sm-3">
                        <label></label>
                    </div>
                </div>
            </div>
            <ul class="nav nav-tabs viewTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="supplementary_details-tab" data-toggle="tab" href="#supplementary_details" role="tab" aria-controls="supplementary_details" aria-selected="true">Supplementary Details</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="assigned_r&d_personnel-tab" data-toggle="tab" href="#assigned_r&d_personnel" role="tab" aria-controls="assigned_r&d_personnel" aria-selected="false">Assigned R&D Personnel</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="activities-tab" data-toggle="tab" href="#activities" role="tab" aria-controls="activities" aria-selected="false">Activities</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="files-tab" data-toggle="tab" href="#files" role="tab" aria-controls="files" aria-selected="false">Files</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="history-tab" data-toggle="tab" href="#history" role="tab" aria-controls="history" aria-selected="false">History Logs</a>
                </li>
            </ul>
            {{-- <div class="tab-content" id="myTabContent">
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
                        <table class="table table-striped table-bordered table-hover" width="100%">
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
                                @if($data->contacts->count() > 0)
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
                                @else
                                    <tr>
                                        <td colspan="14" class="text-center">No matching records found</td>
                                    </tr>
                                @endif
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
                        <table class="table table-striped table-bordered table-hover" width="100%">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>Name</th>
                                    <th>File</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($data->files->count() > 0)
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
                                @else
                                    <tr>
                                        <td colspan="3" class="text-center">No matching records found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    @foreach ($data->files as $file)
                        @include('clients.edit_files')
                    @endforeach
                </div>
                <div class="tab-pane fade" id="transactions" role="tabpanel" aria-labelledby="transactions-tab">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" width="100%">
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
            </div>
            <div align="right" class="mt-3">
                <a href="{{ url('/client') }}" class="btn btn-secondary">Close</a>
            </div> --}}
        </div>
    </div>
</div>
@include('customer_requirements.update')

{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
    

</script> --}}
@endsection

@extends('layouts.header')
@section('content')
<div class="col-12 grid-margin stretch-card">
    <div class="card border border-1 border-primary rounded-0">
        <div class="card-header bg-primary rounded-0">
            <p class="m-0 font-weight-bold text-white">Client Details</p>
        </div>
        <div class="card-body">
            {{-- <h4 class="card-title d-flex justify-content-between align-items-center">View Client Details --}}
                <div align="right">
                    <a href="{{ session('last_client_page') }}" class="btn btn-md btn-outline-secondary">
                        <i class="icon-arrow-left"></i>&nbsp;Back
                    </a>
                    @if(checkIfInGroupV2($data->PrimaryAccountManagerId, $data->SecondaryAccountManagerId, auth()->user()->id))
                    <a href="{{ url('/edit_client/' . $data->id) }}" class="btn btn-md btn-outline-warning"><i class="ti ti-pencil"></i>&nbsp;Update</a>
                    @endif
                    <!-- <button type="button" class="btn btn-primary" title="Update Client" href="{{ url('/edit_client/' . $data->id) }}">
                        <i class="ti ti-pencil"></i>&nbsp;Update
                    </button> -->
                    @if($data->Status != '1')
                    <button type="button" class="prospectClient btn btn-outline-warning" title="Prospect File" data-id="{{ $data->id }}">
                        <i class="ti ti-control-record"></i>&nbsp;Prospect
                    </button>
                    @endif
                    @if($data->Status != '2' && $data->Status != '5')
                    <button type="button" class="activateClient btn btn-outline-success" title="Activate Client" data-id="{{ $data->id }}">
                        <i class="ti ti-check-box"></i>&nbsp;Activate
                    </button>
                    @endif
                    @if($data->Status != '5')
                    <button type="button" class="archivedClient btn btn-outline-danger" title="Archived Client" data-id="{{ $data->id }}">
                        <i class="ti ti-archive"></i>&nbsp;Archive
                    </button>
                    @endif
                </div>
            {{-- </h4> --}}
            <form class="form-horizontal" id="form_client" enctype="multipart/form-data" action="{{ url('update_client/'.$data->id) }}">
                <span id="form_result"></span>
                @csrf
                {{-- <div class="col-md-12">
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
                        <label class="col-sm-3 col-form-label"><b>Primary Email Address</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->Email ?? 'N/A' }}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label class="col-sm-3 col-form-label"><b>Area</b></label>
                        <div class="col-sm-3">
                            <label>{{ $areas->Name ?? 'N/A' }}</label>
                        </div>
                        <label class="col-sm-3 col-form-label"><b>Second Email Address</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->Email2 ?? 'N/A' }}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label"><b>Source</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->Source ?? 'N/A' }}</label>
                        </div>
                        <label class="col-sm-3 col-form-label"><b>Third Email Address</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->Email3 ?? 'N/A' }}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label class="col-sm-3 col-form-label"><b>Country</b></label>
                        <div class="col-sm-3">
                            <label>{{ $countries->Name ?? 'N/A' }}</label>
                        </div>
                        <label class="col-sm-3 col-form-label"><b>Business Type</b></label>
                        <div class="col-sm-3">
                            <label>{{ $business_types->Name ?? 'N/A' }}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label class="col-sm-3 col-form-label mb-2"><b>Industry</b></label>
                        <div class="col-sm-3 mb-2">
                            <label>{{ $industries->Name ?? 'N/A' }}</label>
                        </div>
                        @if($addresses->isNotEmpty())
                            @foreach($addresses as $address)
                            <label class="col-sm-3 col-form-label mb-2"><b>{{ $address->AddressType }}</b></label>
                            <div class="col-sm-3 mb-2">
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
                </div> --}}
                <div class="col-md-12 mt-2">
                    <div class="row">
                        <div class="col-sm-6 col-md-2 text-right mb-0"><p class="m-0"><b>Buyer Code :</b></p></div>
                        <div class="col-sm-6 col-md-4">
                            <p class="m-0">{{ $data->BuyerCode }}</p>
                        </div>
                        <div class="col-sm-6 col-md-2 text-right mb-0"><p class="m-0"><b>Primary Account Manager :</b></p></div>
                        <div class="col-sm-6 col-md-4">
                            <p class="m-0">{{ $primaryAccountManager->full_name ?? 'No Primary Account Manager' }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-md-2 text-right mb-0"><p class="m-0"><b>SAP Code :</b></p></div>
                        <div class="col-sm-6 col-md-4">
                            <p class="m-0">{{ $data->SapCode ?? 'N/A'}}</p>
                        </div>
                        <div class="col-sm-6 col-md-2 text-right mb-0"><p class="m-0"><b>Secondary Account Manager :</b></p></div>
                        <div class="col-sm-6 col-md-4">
                            <p class="m-0">{{ $secondaryAccountManager->full_name ?? 'No Secondary Account Manager' }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-md-2 text-right mb-0"><p class="m-0"><b>Company Name :</b></p></div>
                        <div class="col-sm-6 col-md-4">
                            <p class="m-0">{{ $data->Name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-sm-6 col-md-2 text-right mb-0"><p class="m-0"><b>Trade Name :</b></p></div>
                        <div class="col-sm-6 col-md-4">
                            <p class="m-0">{{ $data->TradeName ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-md-2 mb-0 text-right"><p class="m-0"><b>TIN :</b></p></div>
                        <div class="col-sm-6 col-md-4">
                            <p class="m-0">{{ $data->TaxIdentificationNumber ?? 'N/A '}}</p>
                        </div>
                        <div class="col-sm-6 col-md-2 mb-0 text-right"><p class="m-0"><b>Telephone :</b></p></div>
                        <div class="col-sm-6 col-md-4">
                            <p class="m-0">{{ $data->TelephoneNumber ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-md-2 text-right mb-0"><p class="m-0"><b>Payment Term :</b></p></div>
                        <div class="col-sm-6 col-md-4">
                            <p class="m-0">{{ $payment_terms->Name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-sm-6 col-md-2 text-right mb-0"><p class="m-0"><b>FAX :</b></p></div>
                        <div class="col-sm-6 col-md-4">
                            <p class="m-0">{{ $data->FaxNumber ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-md-2 text-right mb-0"><p class="m-0"><b>Type :</b></p></div>
                        <div class="col-sm-6 col-md-4">
                            <p class="m-0">{{ $data->Type == '1' ? 'Local' : 'International'}}</p>
                        </div>
                        <label class="col-sm-6 col-md-2 text-right mb-0"><p class="m-0"><b>Website :</b></p></label>
                        <div class="col-sm-6 col-md-4">
                            <p class="m-0">{{ $data->Website ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-md-2 text-right mb-0"><p class="m-0"><b>Region :</b></p></div>
                        <div class="col-sm-6 col-md-4">
                            <p class="m-0">{{ $regions->Name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-sm-6 col-md-2 text-right mb-0"><p class="m-0"><b>Primary Email Address :</b></p></div>
                        <div class="col-sm-6 col-md-4">
                            <p class="m-0">{{ $data->Email ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-md-2 text-right mb-0"><p class="m-0"><b>Area :</b></p></div>
                        <div class="col-sm-6 col-md-4">
                            <p class="m-0">{{ $areas->Name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-sm-6 col-md-2 text-right mb-0"><p class="m-0"><b>Second Email Address :</b></p></div>
                        <div class="col-sm-6 col-md-4">
                            <p class="m-0">{{ $data->Email2 ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-md-2 text-right mb-0"><p class="m-0"><b>Source :</b></p></div>
                        <div class="col-sm-6 col-md-4">
                            <p class="m-0">{{ $data->Source ?? 'N/A' }}</p>
                        </div>
                        <div class="col-sm-6 col-md-2 text-right mb-0"><p class="m-0"><b>Third Email Address :</b></p></div>
                        <div class="col-sm-6 col-md-4">
                            <p class="m-0">{{ $data->Email3 ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-md-2 text-right mb-0"><p class="m-0"><b>Country :</b></p></div>
                        <div class="col-sm-6 col-md-4">
                            <p class="m-0">{{ $countries->Name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-sm-6 col-md-2 text-right mb-0"><p class="m-0"><b>Business Type :</b></p></div>
                        <div class="col-sm-6 col-md-4">
                            <p class="m-0">{{ $business_types->Name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-6 col-md-2 mb-2 text-right"><p class="m-0"><b>Industry :</b></p></div>
                        <div class="col-sm-6 col-md-4">
                            <p class="m-0">{{ $industries->Name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-sm-6 col-md-2 mb-2 text-right"><p class="m-0"><b>Client Address :</b></p></div>
                        <div class="col-sm-6 col-md-4">
                            @if($addresses->isNotEmpty())
                                @foreach($addresses as $address)
                                <div class="row">
                                    <div class="col-md-12"><p class="m-0">{{ $address->AddressType }}</p></div>
                                    <div class="col-md-12">
                                        <p class="m-0">{{ $address->Address }}</p>
                                    </div>
                                </div>
                                @endforeach
                            {{-- @else
                                <label class="col-sm-3 col-form-label"><b>Address</b></label>
                                <div class="col-sm-3">
                                    <label>No Address Available</label>
                                </div> --}}
                            @endif
                        </div>
                    </div>
                </div>
            </form>
            <ul class="nav nav-tabs viewTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active p-2" id="contacts-tab" data-toggle="tab" href="#contacts" role="tab" aria-controls="contacts" aria-selected="true">Contacts</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link p-2" id="files-tab" data-toggle="tab" href="#files" role="tab" aria-controls="files" aria-selected="false">Files</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link p-2" id="transactions-tab" data-toggle="tab" href="#transactions" role="tab" aria-controls="transactions" aria-selected="false">Transactions</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link p-2" id="activities-tab" data-toggle="tab" href="#activities" role="tab" aria-controls="activities" aria-selected="false">Activities</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link p-2" id="collection-tab" data-toggle="tab" href="#collection" role="tab" aria-controls="collection" aria-selected="false">Collection</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link p-2" id="productFiles-tab" data-toggle="tab" href="#productFiles" role="tab" aria-controls="productFiles" aria-selected="false">Product Files</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link p-2" id="transactionFiles-tab" data-toggle="tab" href="#transactionFiles" role="tab" aria-controls="transactionFiles" aria-selected="false">Transaction Files</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="contacts" role="tabpanel" aria-labelledby="contacts-tab">
                    <div class="col-md-12" align="right">
                        <button class="btn btn-outline-primary mb-2" type="button" data-toggle="modal" data-target="#contactsModal">New</button>
                    </div>
                    @include('clients.add_contacts')
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="contact-table" width="100%">
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
                                            <button type="button" class="btn btn-outline-warning btn-sm" title="Edit Client" data-toggle="modal" data-target="#edit_contact-{{ $contact->id }}">
                                                <i class="ti-pencil"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger btn-sm deleteContact" title="Delete Client" data-id="{{ $contact->id }}">
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
                    <div class="col-md-12" align="right">
                        <button class="btn btn-outline-primary mb-2" type="button" data-toggle="modal" data-target="#filesModal">New</button>
                    </div>
                    @include('clients.add_files')
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="files-table" width="100%">
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
                        <table class="table table-striped table-bordered table-hover" id="transaction-table" width="100%">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Transaction Number</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($srfClients->isNotEmpty())
                                    @foreach ($srfClients as $srfClient)
                                        <tr>
                                            <td>{{ $srfClient->DateRequested }}</td>
                                            <td>Sample Request</td>
                                            <td>
                                                <a href="{{ url('samplerequest/view/'.$srfClient->Id.'/'.$srfClient->SrfNumber) }}" target="_blank">
                                                    {{ $srfClient->SrfNumber }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                                @if($crrClients->isNotEmpty())
                                    @foreach ($crrClients as $crrClient)
                                        <tr>
                                            <td>{{ $crrClient->DateCreated }}</td>
                                            <td>Customer Requirement</td>
                                            <td>
                                                <a href="{{ url('view_customer_requirement/'.$crrClient->id.'/'.$crrClient->CrrNumber) }}" target="_blank">
                                                    {{ $crrClient->CrrNumber }}
                                                </a>
                                            </td>                                            
                                        </tr>
                                    @endforeach
                                @endif

                                @if($rpeClients->isNotEmpty())
                                    @foreach ($rpeClients as $rpeClient)
                                        <tr>
                                            <td>{{ $rpeClient->DateCreated }}</td>
                                            <td>Request Product Evaluation</td>
                                            <td>
                                                <a href="{{ url('product_evaluation/view/'.$rpeClient->id.'/'.$rpeClient->RpeNumber) }}" target="_blank">
                                                    {{ $rpeClient->RpeNumber }}
                                                </a>
                                            </td>   
                                        </tr>
                                    @endforeach
                                @endif

                                @if($prfClients->isNotEmpty())
                                    @foreach ($prfClients as $prfClient)
                                        <tr>
                                            <td>{{ $prfClient->DateRequested }}</td>
                                            <td>Price Request</td>
                                            <td>
                                                <a href="{{ url('price_monitoring_local/view/'.$prfClient->id.'/'.$prfClient->PrfNumber) }}" target="_blank">
                                                    {{ $prfClient->PrfNumber }}
                                                </a>
                                            </td>   
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="activities" role="tabpanel" aria-labelledby="activities-tab">
                    <div class="form-group">
                        <label>Show : </label>
                        <label class="checkbox-inline">
                            <input checked="checked" id="IsShowOpen" name="IsShowOpen" type="checkbox" value="true"> Open
                        </label>
                        <label class="checkbox-inline">
                            <input checked="checked" id="IsShowClosed" name="IsShowClosed" type="checkbox" value="true"> Closed
                        </label>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="activities-table" width="100%">
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
                                    <tr class="{{ $activity->Status == 10 ? 'open' : 'closed' }}">
                                        <td>
                                            <a href="{{ url('view_activity/'.$activity->id) }}" target="_blank">
                                                {{ $activity->ActivityNumber ?? 'N/A'}}
                                            </a>
                                        </td>
                                        <td>{{ $activity->ScheduleFrom ?? 'N/A' }}</td>
                                        <td>{{ $activity->Title ?? 'N/A' }}</td>
                                        <td>{{ $activity->Status == 10 ? 'Open' : 'Closed' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="tab-pane fade" id="collection" role="tabpanel" aria-labelledby="collection-tab">
                    <input type="date" class="form-control col-md-3">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="collection-table" width="100%">
                            <thead>
                                <tr>
                                    <th>SAP Code</th>
                                    <th>Client</th>
                                    <th>Ref Date(Y-M-D)</th>
                                    <th>Document</th>
                                    <th>Due Date(Y-M-D)</th>
                                    <th>Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                               
                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="tab-pane fade" id="productFiles" role="tabpanel" aria-labelledby="productFiles-tab">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="productFiles-table" width="100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Product</th>
                                    <th>File</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($productFiles as $productFile)
                                    <tr>
                                        <td>{{ $productFile->Name ?? 'N/A' }}</td>
                                        <td>{{ $productFile->Description ?? 'N/A' }}</td>
                                        <td>
                                        {{-- <a href="{{ url('view_product/'.$productFile->product->id) }}" target="_blank"> --}}
                                        <a href="{{ $productFile->product ? url('view_product/'.$productFile->product->id) : '#' }}" target="_blank">
                                            {{ $productFile->product->code ?? 'N/A' }}
                                        </a>
                                        </td>
                                        <td>
                                            <a href="{{ asset($productFile->Path) }}" target="_blank">
                                                Download File
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="transactionFiles" role="tabpanel" aria-labelledby="transactionFiles-tab">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="transactionFiles-table" width="100%">
                            <thead>
                                <tr>
                                    <th width="35">Name</th>
                                    <th width="35">Transaction Number</th>
                                    <th width="30">File</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($crrClientFiles->isNotEmpty())
                                    @foreach ($crrClientFiles as $crrFile)
                                        @php
                                            $customerRequirement = $customerRequirements->firstWhere('id', $crrFile->CustomerRequirementId);
                                        @endphp
                                        <tr>
                                            <td>{{ $crrFile->Name }}</td>
                                            <td>{{ $customerRequirement->CrrNumber ?? 'N/A' }}</td>
                                            <td>
                                                <a href="{{ asset($crrFile->Path) }}" target="_blank">
                                                   Download File
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if($srfClientFiles->isNotEmpty())
                                    @foreach ($srfClientFiles as $srfFile)
                                        @php
                                            $sampleRequest = $sampleRequests->firstWhere('Id', $srfFile->SampleRequestId);
                                        @endphp
                                        <tr>
                                            <td>{{ $srfFile->Name }}</td>
                                            <td>{{ $sampleRequest->SrfNumber ?? 'N/A' }}</td>
                                            <td>
                                                <a href="{{ asset($srfFile->Path) }}" target="_blank">
                                                    Download File
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if($rpeClientFiles->isNotEmpty())
                                    @foreach ($rpeClientFiles as $rpeFile)
                                        @php
                                            $productEvaluation = $productEvaluations->firstWhere('id', $rpeFile->RequestProductEvaluationId);
                                        @endphp
                                        <tr>
                                            <td>{{ $rpeFile->Name }}</td>
                                            <td>{{ $productEvaluation->RpeNumber ?? 'N/A' }}</td>
                                            <td>
                                                <a href="{{ asset($rpeFile->Path) }}" target="_blank">
                                                   Download File
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div align="right" class="mt-3">
                <a href="{{ session('last_client_page') }}" class="btn btn-secondary">Close</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap4.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.bootstrap4.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
<style>
    #form_product {
        padding: 20px 20px;
    }
    .viewTab .nav-link {
        padding: 15px;
    }
    .swal-wide{
        width:400px !important;
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

        // Activate 
        $(".activateClient").on('click', function() {
            var clientId = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Yes',
                cancelButtonColor: '#d33',
                cancelButtonText: "No",
                customClass: 'swal-wide',
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
                // text: "You want to pursue this prospect client!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                // confirmButtonText: 'Yes, confirmed it!',
                confirmButtonText: 'Yes',
                cancelButtonColor: '#d33',
                cancelButtonText: "No",
                customClass: 'swal-wide',
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
                // text: "You want to archive this client!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                // cancelButtonColor: '#d33',
                // confirmButtonText: 'Yes, confirmed it!',
                confirmButtonText: 'Yes',
                cancelButtonColor: '#d33',
                cancelButtonText: "No",
                customClass: 'swal-wide',
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

        // $('.dataTable').DataTable();
        function filterActivities() {
            var showOpen = $('#IsShowOpen').is(':checked');
            var showClosed = $('#IsShowClosed').is(':checked');

            $('#activities-table tbody tr').each(function() {
                var row = $(this);
                if (row.hasClass('open') && showOpen) {
                    row.show();
                } else if (row.hasClass('closed') && showClosed) {
                    row.show();
                } else {
                    row.hide();
                }
            });
        }

        $('#IsShowOpen, #IsShowClosed').change(function() {
            filterActivities();
        });

        // Initial filter on page load
        filterActivities();
    });

    function initializeDataTable(selector, filename, title) {
        new DataTable(selector, {
            destroy: true, // Destroy and re-initialize DataTable on each call
            pageLength: 25,
            layout: {
                topStart: {
                    buttons: [
                        'copy',
                        {
                            extend: 'excel',
                            text: 'Export to Excel',
                            filename: filename, // Set the custom file name
                            title: title // Set the custom title
                        }
                    ]
                }
            }
        });
    }

    // Initialize all DataTables with the corresponding configurations
    initializeDataTable('#contact-table', 'Contacts', 'Contacts');
    initializeDataTable('#files-table', 'Files', 'Files');
    initializeDataTable('#transaction-table', 'Transactions', 'Transactions');
    initializeDataTable('#activities-table', 'Activities', 'Activities');
    initializeDataTable('#collection-table', 'Collection', 'Collection');
    initializeDataTable('#productFiles-table', 'Product Files', 'Product Files');
    initializeDataTable('#transactionFiles-table', 'Transaction Files', 'Transaction Files');

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

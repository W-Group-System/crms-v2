@extends('layouts.header')
@section('content')
<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">View Client Details
            <a href="{{ url('/client') }}" class="btn btn-md btn-light"><i class="icon-arrow-left"></i>&nbsp;Back</a>
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
                    {{-- <div class="col-lg-12">
                        <table class="table table-striped mb-5" id="table_address">
                            <thead>
                                <tr>
                                    <th style="vertical-align: middle" width="30%">Address Type</th>
                                    <th style="vertical-align: middle" width="70%">Address</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if($addresses->isNotEmpty())
                                @foreach($addresses as $address)
                                    <tr>
                                        <td><p>{{ $address->AddressType }}</p></td>
                                        <td><p>{{ $address->Address }}</p></td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="2" align="center">No Address Available</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div> --}}
                <div align="right">
                    <a href="{{ url('/client') }}" class="btn btn-light">Close</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

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
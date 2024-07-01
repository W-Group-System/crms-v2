@extends('layouts.header')
@section('content')
<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">View Client Details</h4>
            <form class="forms-sample" id="form_client" enctype="multipart/form-data" action="{{ url('update_client/'.$data->id) }}">
                <span id="form_result"></span>
                @csrf
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label><b>Buyer Code</b></label>
                            <p>{{ $data->BuyerCode }}</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label><b>Primary Account Manager</b></label>
                            <p>{{ $primaryAccountManager->full_name ?? 'No Primary Account Manager' }}</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label><b>SAP Code</b></label>
                            <p>{{ $data->SapCode ?? 'N/A'}}</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label><b>Secondary Account Manager</b></label>
                            <p>{{ $secondaryAccountManager->full_name ?? 'No Secondary Account Manager' }}</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label><b>Company Name</b></label>
                            <p>{{ $data->Name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label><b>Trade Name</b></label>
                            <p>{{ $data->TradeName ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label><b>TIN</b></label>
                            <p>{{ $data->TaxIdentificationNumber ?? 'N/A '}}</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label><b>Telephone</b></label>
                            <p>{{ $data->TelephoneNumber ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label><b>Payment Term</b></label>
                            <p>{{ $payment_terms->Name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label><b>FAX</b></label>
                            <p>{{ $data->FaxNumber ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label><b>Type</b></label>
                            <p>{{ $data->Type == '1' ? 'Local' : 'International'}}</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label><b>Website</b></label>
                            <p>{{ $data->FaxNumber ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label><b>Region</b></label>
                            <p>{{ $regions->Name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label><b>Email Address</b></label>
                            <p>{{ $data->Email ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label><b>Country</b></label>
                            <p>{{ $countries->Name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label><b>Source</b></label>
                            <p>{{ $data->Source ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label><b>Area</b></label>
                            <p>{{ $areas->Name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label><b>Business Type</b></label>
                            <p>{{ $business_types->Name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label><b>Industry</b></label>
                            <p>{{ $industries->Name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label><b>Status</b></label>
                            <p>
                                @if($data->Status == '2')
                                    Current
                                @elseif($data->Status == '1')
                                    Prospect
                                @else
                                    Archived
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-12">
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
                </div>
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
@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            Currency Exchange Rates List
            <button type="button" class="btn btn-md btn-primary" name="add_currency_exchange" id="add_currency_exchange">Add Currency Exchange Rates</button>
            </h4>
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered" id="currency_exchange_table" width="100%">
                    <thead>
                        <tr>
                            <th width="20%">Action</th>
                            <th width="20%">Effective Date</th>
                            <th width="20%">From Currency</th>
                            <th width="20%">To Currency</th>
                            <th width="20%">Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($currency_exchanges as $currency)
                            <tr>
                                <td></td>
                                <td>{{date('M d, Y', strtotime($currency->EffectiveDate))}}</td>
                                <td>{{$currency->FromCurrency->Name}}</td>
                                <td>{{$currency->ToCurrency->Name}}</td>
                                <td>{{$currency->ExchangeRate}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="formCurrencyExchange" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Currency Exchange Rate</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_currency_exchange" enctype="multipart/form-data" action="{{ route('currency_exchange.store') }}">
                    <span id="form_result"></span>
                    @csrf
                    <div class="form-group">
                        <label>Effective Date</label>
                        <input type="date" class="form-control" id="EffectiveDate" name="EffectiveDate">
                    </div>
                    <div class="form-group">
                        <label>From Currency</label>
                        <select class="form-control js-example-basic-single" name="FromCurrencyId" id="from_currency" style="position: relative !important" title="Select Currency">
                            <option value="" disabled selected>Select Currency</option>
                            @foreach($currencies as $currency)
                                <option value="{{ $currency->id }}">{{ $currency->Name }}</option>
                            @endforeach
                        </select>
                    </div>  
                    <div class="form-group">
                        <label>To Currency</label>
                        <select class="form-control js-example-basic-single" id="to_currency" name="ToCurrencyId"  style="position: relative !important" title="Select Currency">
                            <option value="" disabled selected>Select Currency</option>
                            @foreach($currencies as $currency)
                                <option value="{{ $currency->id }}">{{ $currency->Name }}</option>
                            @endforeach
                        </select>
                    </div>    
                    <div class="form-group">
                        <label>Rate</label>
                        <input type="text" class="form-control" id="ExchangeRate" name="ExchangeRate" placeholder="Enter Rate">
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="action" id="action" value="Save">
                        <input type="hidden" name="hidden_id" id="hidden_id">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" name="action_button" id="action_button" class="btn btn-success" value="Save">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Region</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 20px">
                <h5 style="margin: 0">Are you sure you want to delete this data?</h5>
            </div>
            <div class="modal-footer" style="padding: 0.6875rem">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" name="delete_currency_exchange" id="delete_currency_exchange" class="btn btn-danger">Yes</button>
            </div>
        </div>
    </div>
</div>

{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script> 

<script>
    $(document).ready(function(){
        $('#currency_exchange_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('currency_exchange.index') }}"
            },
            columns: [
                {
                    data: 'EffectiveDate',
                    name: 'EffectiveDate'
                },
                {
                    data: 'from_currency.Name',
                    name: 'from_currency.Name',
                },
                {
                    data: 'to_currency.Name',
                    name: 'to_currency.Name',
                },
                {
                    data: 'ExchangeRate',
                    name: 'ExchangeRate',
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false
                }
            ],
            columnDefs: [
                {
                    targets: [1, 2], // Target the Description column
                    render: function(data, type, row) {
                        return '<div style="white-space: break-spaces; width: 100%;">' + data + '</div>';
                    }
                }
            ]
        });
        
        $('#add_currency_exchange').click(function(){
            $('#formCurrencyExchange').modal('show');
            $('.modal-title').text("Add Currency Exchange Rate");
        });

        function removeErrorMessage() {
            $('#form_result').empty();
        }

        $('#form_currency_exchange').on('submit', function(event){
            event.preventDefault();
            
            if($('#action').val() == 'Save') {
                $.ajax({
                    url: "{{ route('currency_exchange.store') }}",
                    method: "POST",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: "json",
                    success: function(data) {
                        var html = '';
                        if (data.errors) {
                            html = '<div class="alert alert-danger">';
                            for (var count = 0; count < data.errors.length; count++) {
                                html += '<p>' + data.errors[count] + '</p>';
                            }
                            html += '</div>';
                        }
                        if (data.success) {
                            html = '<div class="alert alert-success">' + data.success + '</div>';
                            $('#form_currency_exchange')[0].reset();
                            $('#from_currency').val('').trigger('change'); // Reset select inputs
                            $('#to_currency').val('').trigger('change'); // Reset select inputs
                            setTimeout(function() {
                                $('#formCurrencyExchange').modal('hide');
                            }, 2000);
                            $('#currency_exchange_table').DataTable().ajax.reload();
                            setTimeout(function() {
                                $('#form_result').empty();
                            }, 2000);
                        }
                        $('#form_result').html(html);
                    }
                });
            }
            if($('#action').val() == 'Edit')
            {
                var formData = new FormData(this);
                formData.append('id', $('#hidden_id').val());
                $.ajax({
                    url: "{{ route('update_currency_exchange', ':id') }}".replace(':id', $('#hidden_id').val()),
                    method: "POST",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: "json",
                    success:function(data)
                    {
                        var html = '';
                        if(data.errors)
                        {
                            html = '<div class="alert alert-danger">';
                            for(var count = 0; count < data.errors.length; count++)
                            {
                                html += '<p>' + data.errors[count] + '</p>';
                            }
                            html += '</div>';
                        }
                        if(data.success)
                        {
                            html = '<div class="alert alert-success">' + data.success + '</div>';
                            $('#form_currency_exchange')[0].reset();
                            $('#from_currency').val('').trigger('change'); // Reset select inputs
                            $('#to_currency').val('').trigger('change'); // Reset select inputs
                            setTimeout(function(){
                                $('#formCurrencyExchange').modal('hide');
                            }, 1000);
                            $('#currency_exchange_table').DataTable().ajax.reload();
                            setTimeout(function(){
                                $('#form_result').empty(); 
                            }, 1000); 
                        }
                        $('#form_result').html(html);
                    }
                });
            }
        });

        $(document).on('click', '.edit', function(){
            var id = $(this).attr('id');
            console.log(id);
            $('#form_result').html('');
            $.ajax({
                url: "{{ route('edit_currency_exchange', ['id' => '_id_']) }}".replace('_id_', id),
                dataType: "json",
                success: function(html){
                    $('#EffectiveDate').val(html.data.EffectiveDate);
                    $('#from_currency').val(html.data.FromCurrencyId).trigger('change');
                    $('#to_currency').val(html.data.ToCurrencyId).trigger('change');
                    $('#ExchangeRate').val(html.data.ExchangeRate);
                    $('#hidden_id').val(html.data.id);
                    $('.modal-title').text("Edit Currency Exchange Rate");
                    $('#action_button').val("Update");
                    $('#action').val("Edit");
                    $('#formCurrencyExchange').modal('show');
                }
            });
        });

        var currency_exchange_id;
        $(document).on('click', '.delete', function(){
            currency_exchange_id = $(this).attr('id');
            $('#confirmModal').modal('show');
            $('.modal-title').text("Delete Currency Exchange Rates");
        }); 

        $('#delete_currency_exchange').click(function(){
            $.ajax({
                url: "{{ url('delete_currency_exchange') }}/" + currency_exchange_id, 
                method: "GET",
                beforeSend:function(){
                    $('#yes_button').text('Deleting...');
                },
                success:function(data)
                {
                    setTimeout(function(){
                        $('#confirmModal').modal('hide');
                        $('#currency_exchange_table').DataTable().ajax.reload();
                    }, 2000);
                }
            })
        });
    });
</script> --}}
@endsection
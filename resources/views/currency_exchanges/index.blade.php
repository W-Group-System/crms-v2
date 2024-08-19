@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            Currency Exchange Rates List
            <button type="button" class="btn btn-md btn-primary addExchangeRates" data-toggle="modal" data-target="#addExchangeRates">Add Currency Exchange Rates</button>
            </h4>
            @include('currency_exchanges.new_exchange_rate')

            <form method="GET" class="custom_form mb-3" enctype="multipart/form-data">
                <div class="row height d-flex justify-content-end align-items-end">
                    <div class="col-md-3">
                        <div class="search">
                            <i class="ti ti-search"></i>
                            <input type="text" class="form-control" placeholder="Search Currency Exhange Rates" name="search" value="{{$search}}"> 
                            <button class="btn btn-sm btn-info">Search</button>
                        </div>
                    </div>
                </div>
            </form>
            @include('components.error')
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover table-bordered" id="currency_exchange_table" width="100%">
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
                                <td>
                                    <button type="button" class="btn btn-sm btn-warning editBtn" title="Edit" data-toggle="modal" data-target="#editExchangeRate-{{$currency->id}}" data-id="{{$currency->id}}">
                                        <i class="ti-pencil"></i>
                                    </button>
                                    <form method="POST" class="deleteCurrencyExchangeRateForm d-inline-block" action="{{url('delete_currency_exchange/'.$currency->id)}}">
                                        @csrf

                                        <button type="button" class="btn btn-sm btn-danger deleteBtn">
                                            <i class="ti-trash"></i>
                                        </button>
                                    </form>
                                </td>
                                <td>{{date('M d, Y', strtotime($currency->EffectiveDate))}}</td>
                                <td>
                                    @if($currency->FromCurrency)
                                    {{$currency->FromCurrency->Name}}
                                    @endif
                                </td>
                                <td>
                                    @if($currency->ToCurrency)
                                    {{$currency->ToCurrency->Name}}
                                    @endif
                                </td>
                                <td>{{$currency->ExchangeRate}}</td>
                            </tr>

                            <input type="hidden" class="currency_id" value="{{$currency->id}}">
                            @include('currency_exchanges.edit_exchange_rate')
                        @endforeach
                    </tbody>
                </table>

                {!! $currency_exchanges->appends(['search' => $search])->links() !!}

                @php
                    $total = $currency_exchanges->total();
                    $currentPage = $currency_exchanges->currentPage();
                    $perPage = $currency_exchanges->perPage();
                    
                    $from = ($currentPage - 1) * $perPage + 1;
                    $to = min($currentPage * $perPage, $total);
                @endphp

                <p  class="mt-3">{{"Showing {$from} to {$to} of {$total} entries"}}</p>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.deleteBtn').on('click', function() {
            var form = $(this).closest('form');

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        })

        $('#addExchangeRates').on('hidden.bs.modal', function(){
            $('[name="from_currency"]').val(null).trigger('change')
            $('[name="to_currency"]').val(null).trigger('change')
            $('[name="rate"]').val('')
        })

        $('.addExchangeRates').on('click', function() {
            $('[name="from_currency"]').val(null).trigger('change')
            $('[name="to_currency"]').val(null).trigger('change')
            $('[name="rate"]').val('')
        })
        
        $('.editBtn').on('click', function() {
            var id = $(this).data('id');

            $.ajax({
                type: "GET", 
                url: "{{url('edit_currency_exchange')}}",
                data: {
                    id: id
                },
                
                success: function(res) {
                    $('[name="effective_date"]').val(res.EffectiveDate)
                    $('[name="from_currency"]').val(res.FromCurrency).trigger('change')
                    $('[name="to_currency"]').val(res.ToCurrency).trigger('change')
                    $('[name="rate"]').val(res.ExchangeRate)
                }
            })
            
        })
    })
</script>
@endsection
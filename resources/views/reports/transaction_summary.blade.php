@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            Summary of Transactions/Activities
            </h4>
            <div class="row height d-flex ">
                <div class="col-md-6 mt-2 mb-2">
                    <a href="#" id="copy_transaction_btn" class="btn btn-md btn-info" style="margin-top: 2em">Copy</a>
                    <a href="#" id="excel_btn" class="btn btn-md btn-success" style="margin-top: 2em">Excel</a>
                </div>
                <div class="col-md-3 mt-2 mb-2">
                    <label for="from">From (DD/MM/YYYY):</label>
                    <input type="date" class="form-control" name="from" value="{{ request('from') }}">
                </div>
                <div class="col-md-3 mt-2 mb-2">
                    <label for="to">To (DD/MM/YYYY):</label>
                    <input type="date" class="form-control" name="to" value="{{ request('to') }}">
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-lg-6">
                    <span>Show</span>
                    <form method="GET" class="d-inline-block">
                        <select name="number_of_entries" class="form-control">
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
                            <div class="col-lg-9">
                                <div class="search">
                                    <i class="ti ti-search"></i>
                                    <input type="text" class="form-control" placeholder="Search Transactions/Activities" name="search" value=""> 
                                    <button class="btn btn-sm btn-info">Search</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="transaction_table" width="100%">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Transaction Number</th>
                            <th>BDE</th>
                            <th>Client</th>
                            <th>Date Created</th>
                            <th>Due Date</th>
                            <th>Details</th>
                            <th>Result</th>
                            <th>Status</th>
                            <th>Progress</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaction_data as $transaction)
                            <tr>
                                <td>{{ $transaction->type }}</td>
                                <td>{{ $transaction->transaction_number }}</td>
                                <td>{{ $transaction->bde }}</td>
                                <td>{{ $transaction->client }}</td>
                                <td>{{ date('Y-m-d', strtotime($transaction->date_created)) }}</td>
                                <td>{{ $transaction->due_date }}</td>
                                <td>{{ $transaction->details }}</td>
                                <td>{{ $transaction->result ?? 'N/A' }}</td>
                                <td>{{ $transaction->status }}</td>
                                <td>{{ $transaction->progress }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {!! $transaction_data->appends(['search' => $search, 'sort' => request('sort'), 'direction' => request('direction')])->links() !!}
            @php
                $total = $transaction_data->total();
                $currentPage = $transaction_data->currentPage();
                $perPage = $transaction_data->perPage();
                
                $from = ($currentPage - 1) * $perPage + 1;
                $to = min($currentPage * $perPage, $total);
            @endphp
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>Showing {{ $from }} to {{ $to }} of {{ $total }} entries</div>
            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<script>
    $(document).ready(function() {
        $("[name='number_of_entries']").on('change', function() {
            var form = $(this).closest('form');
            form.submit();
        });
    });
</script>
@endsection
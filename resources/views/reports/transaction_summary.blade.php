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
                <form class="form-inline col-md-6" action="{{ route('reports.transaction_activity') }}" method="GET">
                    <div class="col-md-6 mt-2 mb-2">
                        <label style="align-items: start;justify-content: left;">From (DD/MM/YYYY):</label>
                        <input type="date" class="form-control" name="from" id="from" value="{{ $from }}" onchange="this.form.submit();" style="width: 100%;">
                    </div>
                    <div class="col-md-6 mt-2 mb-2">
                        <label style="align-items: start;justify-content: left;">To (DD/MM/YYYY):</label>
                        <input type="date" class="form-control" name="to" id="to" value="{{ $to }}" onchange="this.form.submit();" style="width: 100%;">
                    </div>
                </form>                
            </div>
            <div class="row mt-2">
                <div class="col-lg-6">
                    <span>Show</span>
                    <form method="GET" class="d-inline-block">
                        <input type="hidden" name="from" value="{{ $from }}">
                        <input type="hidden" name="to" value="{{ $to }}">
                        <select name="number_of_entries" class="form-control" onchange="this.form.submit();">
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
                        <input type="hidden" name="from" value="{{ $from }}">
                        <input type="hidden" name="to" value="{{ $to }}">
                        <div class="row height d-flex justify-content-end align-items-end">
                            <div class="col-lg-9">
                                <div class="search">
                                    <i class="ti ti-search"></i>
                                    <input type="text" class="form-control" placeholder="Search Transactions/Activities" name="search" value="{{ $search }}"> 
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
                        <th><a href="{{ route('reports.transaction_activity', array_merge(request()->query(), ['sort' => 'type', 'direction' => $direction == 'asc' ? 'desc' : 'asc'])) }}">Type</a></th>
        <th><a href="{{ route('reports.transaction_activity', array_merge(request()->query(), ['sort' => 'transaction_number', 'direction' => $direction == 'asc' ? 'desc' : 'asc'])) }}">Transaction Number</a></th>
        <th><a href="{{ route('reports.transaction_activity', array_merge(request()->query(), ['sort' => 'bde', 'direction' => $direction == 'asc' ? 'desc' : 'asc'])) }}">BDE</a></th>
        <th><a href="{{ route('reports.transaction_activity', array_merge(request()->query(), ['sort' => 'client', 'direction' => $direction == 'asc' ? 'desc' : 'asc'])) }}">Client</a></th>
        <th><a href="{{ route('reports.transaction_activity', array_merge(request()->query(), ['sort' => 'date_created', 'direction' => $direction == 'asc' ? 'desc' : 'asc'])) }}">Date Created</a></th>
        <th><a href="{{ route('reports.transaction_activity', array_merge(request()->query(), ['sort' => 'due_date', 'direction' => $direction == 'asc' ? 'desc' : 'asc'])) }}">Due Date</a></th>
        <th><a href="{{ route('reports.transaction_activity', array_merge(request()->query(), ['sort' => 'details', 'direction' => $direction == 'asc' ? 'desc' : 'asc'])) }}">Details</a></th>
        <th><a href="{{ route('reports.transaction_activity', array_merge(request()->query(), ['sort' => 'result', 'direction' => $direction == 'asc' ? 'desc' : 'asc'])) }}">Result</a></th>
        <th><a href="{{ route('reports.transaction_activity', array_merge(request()->query(), ['sort' => 'status', 'direction' => $direction == 'asc' ? 'desc' : 'asc'])) }}">Status</a></th>
        <th><a href="{{ route('reports.transaction_activity', array_merge(request()->query(), ['sort' => 'progress', 'direction' => $direction == 'asc' ? 'desc' : 'asc'])) }}">Progress</a></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($transaction_data->count() > 0)
                            @foreach($transaction_data as $transaction)
                                <tr>
                                    <td>{{ $transaction->type }}</td>
                                    <td>{{ $transaction->transaction_number }}</td>
                                    <td>{{ $transaction->bde }}</td>
                                    <td>{{ $transaction->client }}</td>
                                    <td>{{ date('Y-m-d', strtotime($transaction->date_created)) }}</td>
                                    <td>{{ date('Y-m-d', strtotime($transaction->due_date)) }}</td>
                                    <td>{{ $transaction->details }}</td>
                                    <td>{{ $transaction->result ?? 'N/A' }}</td>
                                    <td>{{ $transaction->status }}</td>
                                    <td>{{ $transaction->progress }}</td>
                                </tr>
                            @endforeach
                        @else 
                            <tr>
                                <td colspan="10" class="text-center">No matching records found</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            {{ $transaction_data->appends(['from' => $from, 'to' => $to, 'search' => $search, 'sort' => $sort, 'direction' => $direction])->links() }}
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
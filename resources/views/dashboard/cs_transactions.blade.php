@extends('layouts.header')
@section('title', 'Transaction - CRMS')
@section('content')
<div class="col-lg-12 grid-stretch-margin">
    <div class="card border border-1 border-primary rounded-0">
        
        <div class="card-header bg-primary">
            <p class="m-0 font-weight-bold text-white">Open Customer Service</p>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6">
                    <span>Show</span>
                    <form method="GET" class="d-inline-block" onsubmit="show()" id="entriesForm">
                        <select name="entries" class="form-control" onchange="changeEntries()">
                            <option value="10" @if($entries == 10) selected @endif>10</option>
                            <option value="25" @if($entries == 25) selected @endif>25</option>
                            <option value="50" @if($entries == 50) selected @endif>50</option>
                            <option value="100" @if($entries == 100) selected @endif>100</option>
                        </select>
                    </form>
                    <span>Entries</span>
                </div>
                <div class="col-lg-6">
                    <form method="GET" class="custom_form mb-3" enctype="multipart/form-data" onsubmit="show()">
                        <div class="row height d-flex justify-content-end align-items-end">
                            <div class="col-md-10">
                                <div class="search">
                                    <i class="ti ti-search"></i>
                                    <input type="text" class="form-control" placeholder="Search" name="search" value="{{$search}}"> 
                                    <button class="btn btn-sm btn-info">Search</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" id="forApprovalTable" >
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Company Name</th>
                            <th>Contact Name</th>
                            <th>Email</th>
                            <th>Concerned Department</th>
                            <th>Received By</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($paginatedResults) > 0)
                            @foreach ($paginatedResults as $transaction)
                                @php
                                    $id = "";
                                    $cs_number = "";
                                    $date_created = "";
                                    $company_name = "";
                                    $contact_name = "";
                                    $email = "";
                                    $concerned = "";
                                    $received = "";
                                    $status = "";
                                    if (str_contains($transaction->CsNumber, 'CSR'))
                                    {
                                        $id           = $transaction->id;
                                        $cs_number    = $transaction->CsNumber;
                                        $date_created = $transaction->created_at;
                                        $company_name = $transaction->CompanyName;
                                        $contact_name = $transaction->ContactName;
                                        $email        = $transaction->Email;
                                        $concerned    = optional($transaction->concerned)->Name;
                                        $received     = optional($transaction->users)->full_name;
                                        $status       = $transaction->Status;
                                    }
                                    
                                    if (str_contains($transaction->CcNumber, 'CCF'))
                                    {
                                        $id           = $transaction->id;
                                        $cs_number    = $transaction->CcNumber;
                                        $date_created = $transaction->created_at;
                                        $company_name = $transaction->CompanyName;
                                        $contact_name = $transaction->ContactName;
                                        $email        = $transaction->Email;
                                        $concerned    = optional($transaction->concerned)->Name;
                                        $received     = optional($transaction->users)->full_name;
                                        $status       = $transaction->Status;
                                    }
                                    
                                @endphp
                                <tr>
                                    <td class="{{ is_null($transaction->users) ? 'text-danger-bold' : '' }}">
                                        @if(str_contains($transaction->CsNumber, 'CSR'))
                                        <a href="{{url('customer_satisfaction/view/'.$id.'')}}">
                                        @elseif(str_contains($transaction->CcNumber, 'CCF'))
                                        <a href="{{url('customer_complaint/view/'.$id.'')}}">
                                        @endif
                                            {{$cs_number}}
                                        </a>
                                    </td>
                                    <td class="{{ is_null($transaction->users) ? 'text-danger-bold' : '' }}">{{ date('M. d, Y', strtotime($date_created)) }}</td>
                                    <td class="{{ is_null($transaction->users) ? 'text-danger-bold' : '' }}">
                                        <a>
                                            {{ $company_name }}
                                        </a>
                                    </td>
                                    <td class="{{ is_null($transaction->users) ? 'text-danger-bold' : '' }}">{{$contact_name}}</td>
                                    <td class="{{ is_null($transaction->users) ? 'text-danger-bold' : '' }}">{{$email}}</td>
                                    <td class="{{ is_null($transaction->users) ? 'text-danger-bold' : '' }}">{{ $concerned ?? 'N/A' }}</td>
                                    <td class="{{ is_null($transaction->users) ? 'text-danger-bold' : '' }}">{{ $received ?? 'N/A' }}</td>
                                    <td>
                                        @if($status == 10)
                                        <span class="badge badge-success">Open</span>
                                        @elseif($status == 30)
                                        <span class="badge badge-warning">Closed</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="text-center" colspan="8">No data available.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            {!! $paginatedResults->links() !!}
            @php
                $total = $paginatedResults->total();
                $currentPage = $paginatedResults->currentPage();
                $perPage = $paginatedResults->perPage();

                $from = ($currentPage - 1) * $perPage + 1;
                $to = min($currentPage * $perPage, $total);
            @endphp
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>Showing {{ $from }} to {{ $to }} of {{ $total }} entries</div>
            </div>
        </div>
    </div>
</div>

<style>
    .text-danger-bold {
        font-weight: bold;
    }
</style>
<script>
    function changeEntries()
    {
        document.getElementById('entriesForm').submit()
    }

    $(document).ready(function() {
        $(".table").tablesorter({
            theme : "bootstrap",
        })
    })

</script>
@endsection
@extends('layouts.header')
@section('title', 'Transaction - CRMS')
@section('content')
<div class="col-lg-12 grid-stretch-margin">
    <div class="card border border-1 border-primary rounded-0">
        
        <div class="card-header bg-primary">
            <p class="m-0 font-weight-bold text-white">Closed Transactions</p>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <a href="#" id="copy_btn" class="btn btn-md btn-outline-info">Copy</a>
                <form method="GET" action="{{url('export-close-transaction')}}" class="d-inline-block">
                    {{-- <input type="hidden" name="open" value="{{$open}}">
                    <input type="hidden" name="close" value="{{$close}}"> --}}
                    <button type="submit" class="btn btn-outline-success">Export</button>
                </form>
            </div>

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
                            <th>Date Created (Y-M-D)</th>
                            <th>Due Date (Y-M-D)</th>
                            <th>Client Name</th>
                            <th>Application</th>
                            <th>Analyst</th>
                            <th>Date Completed</th>
                            <th>Status</th>
                            <th>Progress</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($paginatedResults) > 0)
                            @foreach ($paginatedResults as $transaction)
                                @php
                                    $id = "";
                                    $transaction_number = "";
                                    $date_created = "";
                                    $due_date = "";
                                    $client_name = "";
                                    $client_id = "";
                                    $application = "";
                                    $date_completed = "";
                                    $analyst = "";
                                    $status = "";
                                    $progress = "";
                                    if (str_contains($transaction->CrrNumber, 'CRR'))
                                    {
                                        if ($transaction->crr_personnels != null)
                                        {
                                            if ($transaction->crr_personnels->crrPersonnelByUserId != null)
                                            {
                                                $analyst = $transaction->crr_personnels->crrPersonnelByUserId->full_name;
                                            }
                                            elseif($transaction->crr_personnels->crrPersonnelById != null)
                                            {
                                                $analyst = $transaction->crr_personnels->crrPersonnelById->full_name;
                                            }
                                        }
                                        
                                        $id = $transaction->id;
                                        $transaction_number = $transaction->CrrNumber;
                                        $date_created = $transaction->DateCreated;
                                        $due_date = $transaction->DueDate;
                                        $client_name = optional($transaction->client)->Name;
                                        $client_id = optional($transaction->client)->id;
                                        $application = optional($transaction->product_application)->Name;
                                        $date_completed = $transaction->DateCompleted;
                                        $status = $transaction->Status;
                                        // $analyst = optional($transaction->crr_personnels)->full_name;
                                        $progress = $transaction->Progress;
                                    }
                                    
                                    if (str_contains($transaction->RpeNumber, 'RPE'))
                                    {
                                        if ($transaction->rpe_personnels != null)
                                        {
                                            if ($transaction->rpe_personnels->assignedPersonnel != null)
                                            {
                                                $analyst = $transaction->rpe_personnels->assignedPersonnel->full_name;
                                            }
                                            elseif($transaction->rpe_personnels->userId != null)
                                            {
                                                $analyst = $transaction->rpe_personnels->userId->full_name;
                                            }
                                        }

                                        $id = $transaction->id;
                                        $transaction_number = $transaction->RpeNumber;
                                        $date_created = $transaction->created_at;
                                        $due_date = $transaction->DueDate;
                                        $client_name = optional($transaction->client)->Name;
                                        $client_id = optional($transaction->client)->id;
                                        $application = optional($transaction->product_application)->Name;
                                        $date_completed = $transaction->DateCompleted;
                                        $status = $transaction->Status;
                                        $progress = $transaction->Progress;
                                    }
                                    if (str_contains($transaction->SrfNumber, 'SRF'))
                                    {
                                        if ($transaction->srf_personnel != null)
                                        {
                                            if ($transaction->srf_personnel->assignedPersonnel != null)
                                            {
                                                $analyst = $transaction->srf_personnel->assignedPersonnel->full_name;
                                            }
                                            elseif($transaction->srf_personnel->userId != null)
                                            {
                                                $analyst = $transaction->srf_personnel->userId->full_name;
                                            }
                                        }

                                        $id = $transaction->Id;
                                        $transaction_number = $transaction->SrfNumber;
                                        $date_created = $transaction->created_at;
                                        $due_date = $transaction->DateRequired;
                                        $client_name = optional($transaction->client)->Name;
                                        $client_id = optional($transaction->client)->id;
                                        $application = optional($transaction->productApplicationsId)->Name;
                                        $date_completed = $transaction->DateCompleted;
                                        $status = $transaction->Status;
                                        $progress = $transaction->Progress;
                                    }
                                @endphp
                                <tr>
                                    <td>
                                        @if(str_contains($transaction->CrrNumber, 'CRR'))
                                        <a href="{{url('view_customer_requirement/'.$id.'/'.$transaction_number.'/?origin=close_transactions')}}">
                                        @elseif(str_contains($transaction->RpeNumber, 'RPE'))
                                        <a href="{{url('product_evaluation/view/'.$id.'/'.$transaction_number.'/?origin=close_transactions')}}">
                                        @elseif(str_contains($transaction->SrfNumber, 'SRF'))
                                        <a href="{{url('samplerequest/view/'.$id.'/'.$transaction_number.'/?origin=close_transactions')}}">
                                        @endif
                                            {{$transaction_number}}
                                        </a>
                                    </td>
                                    <td>{{$date_created}}</td>
                                    <td>{{$due_date}}</td>
                                    <td>
                                        <a href="{{url('view_client/'.$client_id)}}">
                                            {{$client_name}}
                                        </a>
                                    </td>
                                    <td>{{$application}}</td>
                                    <td>{{$analyst}}</td>
                                    <td>{{ $date_completed }}</td>
                                    <td>
                                        @if($status == 10)
                                        <span class="badge badge-success">Open</span>
                                        @elseif($status == 30)
                                        <span class="badge badge-warning">Closed</span>
                                        @endif
                                    </td>
                                    <td>{{transactionProgressName($progress)}}</td>
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
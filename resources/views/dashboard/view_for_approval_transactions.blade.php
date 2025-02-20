@extends('layouts.header')

@section('content')
<div class="col-lg-12 grid-stretch-margin">
    <div class="card border border-1 border-primary rounded-0">
        
        <div class="card-header bg-primary">
            <p class="m-0 font-weight-bold text-white">For Approval Transactions</p>
        </div>
        <div class="card-body">
            {{-- <div class="row">
                <div class="col-lg-6">
                    <span>Show</span>
                    <form method="GET" class="d-inline-block" onsubmit="show()">
                        <select name="entries" class="form-control">
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
                        @if($status)
                        <input type="hidden" name="status" value="{{$status}}">
                        @elseif($open || $close)
                        <input type="hidden" name="open" value="{{$open}}">
                        <input type="hidden" name="close" value="{{$close}}">
                        @elseif($progress)
                        <input type="hidden" name="progress" value="{{$progress}}">
                        @endif
        
                        <div class="row height d-flex justify-content-end align-items-end">
                            <div class="col-md-10">
                                <div class="search">
                                    <i class="ti ti-search"></i>
                                    <input type="text" class="form-control" placeholder="Search" name="search" > 
                                    <button class="btn btn-sm btn-info">Search</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div> --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" id="forApprovalTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date Created (Y-M-D)</th>
                            <th>Due Date (Y-M-D)</th>
                            <th>Client Name</th>
                            <th>Application</th>
                            <th>Status</th>
                            <th>Progress</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($forApprovalTransactionsArray != null)
                            @foreach ($forApprovalTransactionsArray as $key=>$transactions)
                                @foreach ($transactions as $transaction) 
                                    @php
                                        $id = "";
                                        $transaction_number = "";
                                        $date_created = "";
                                        $due_date = "";
                                        $client_name = "";
                                        $application = "";
                                        $status = "";
                                        $progress = "";
                                        
                                        if ($key == 'crr')
                                        {
                                            $id = $transaction->id;
                                            $transaction_number = $transaction->CrrNumber;
                                            $date_created = $transaction->DateCreated;
                                            $due_date = $transaction->DueDate;
                                            $client_name = $transaction->client->Name;
                                            $application = $transaction->product_application->Name;
                                            $status = $transaction->Status;
                                            $progress = $transaction->Progress;
                                        }
                                        if ($key == 'rpe')
                                        {
                                            $id = $transaction->id;
                                            $transaction_number = $transaction->RpeNumber;
                                            $date_created = $transaction->created_at;
                                            $due_date = $transaction->DueDate;
                                            $client_name = $transaction->client->Name;
                                            $application = $transaction->product_application->Name;
                                            $status = $transaction->Status;
                                            $progress = $transaction->Progress;
                                        }
                                        if ($key == 'srf')
                                        {
                                            $application = [];
                                            foreach($transaction->requestProducts as $sampleRequestProduct)
                                            {
                                                $application[] = $sampleRequestProduct->productApplicationsId->Name;
                                            }

                                            $id = $transaction->Id;
                                            $transaction_number = $transaction->SrfNumber;
                                            $date_created = $transaction->created_at;
                                            $due_date = $transaction->DateRequired;
                                            $client_name = $transaction->client->Name;
                                            // $application =  implode('<br>', $application);
                                            $status = $transaction->Status;
                                            $progress = $transaction->Progress;
                                        }
                                        if ($key == 'prf')
                                        {
                                            $id = $transaction->id;
                                            $transaction_number = $transaction->PrfNumber;
                                            $date_created = $transaction->created_at;
                                            $due_date = $transaction->DateRequired;
                                            $client_name = $transaction->client->Name;
                                            $application = optional($transaction->productApplicationsId)->Name;
                                            $status = $transaction->Status;
                                            $progress = $transaction->Progress;
                                        }
                                        if ($key == 'cs')
                                        {
                                            $id = $transaction->id;
                                            $transaction_number = $transaction->CsNumber;
                                            $date_created = $transaction->created_at;
                                            $due_date = $transaction->DateRequired;
                                            $client_name = $transaction->CompanyName;
                                            $application = optional($transaction->productApplicationsId)->Name;
                                            $status = $transaction->Status;
                                            $progress = $transaction->Progress;
                                        }

                                        if ($key == 'cc')
                                        {
                                            $id = $transaction->id;
                                            $transaction_number = $transaction->CcNumber;
                                            $date_created = $transaction->created_at;
                                            $due_date = $transaction->DateRequired;
                                            $client_name = $transaction->CompanyName;
                                            $application = optional($transaction->productApplicationsId)->Name;
                                            $status = $transaction->Status;
                                            $progress = $transaction->Progress;
                                        }
                                    @endphp
                                    <tr>
                                        <td>
                                            @if($key == 'crr')
                                            <a href="{{url('view_customer_requirement/'.$id.'/'.$transaction_number.'/?origin=for_approval')}}">
                                            @elseif($key == 'rpe')
                                            <a href="{{url('product_evaluation/view/'.$id.'/'.$transaction_number.'/?origin=for_approval')}}">
                                            @elseif($key == 'srf')
                                            <a href="{{url('samplerequest/view/'.$id.'/'.$transaction_number.'/?origin=for_approval')}}">
                                            @elseif($key == 'prf')
                                            <a href="{{url('price_monitoring_local/view/'.$id.'/'.$transaction_number.'/?origin=for_approval')}}">
                                            @elseif($key == 'cs')
                                            <a href="{{url('customer_satisfaction/view/'.$id.'')}}">
                                            @elseif($key == 'cc')
                                            <a href="{{url('customer_complaint/view/'.$id.'')}}">
                                            @endif
                                                {{$transaction_number}}
                                            </a>
                                        </td>
                                        <td>{{date('Y-m-d',strtotime($date_created))}}</td>
                                        <td>{{$due_date ?? 'N/A'}}</td>
                                        <td>{{$client_name}}</td>
                                        <td>
                                            @if($key == 'srf')
                                            {!! implode('<br>', $application) !!}
                                            @else
                                            {{$application}}
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-success">Open</span>
                                        </td>
                                        <td>Sales Approval</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="text-center">No data available.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
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
<script>
    $(document).ready(function() {
        $("#forApprovalTable").DataTable({
            stateSave: true,
            ordering: false,
            responsive: true,
            processing: false,
            serverSide: false,
            pageLength: 10,
            buttons: [
                'copy', 'excel'
            ],
        })
    })
</script>
@endsection
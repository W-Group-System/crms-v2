@extends('layouts.header')
@section('content')
<style>
    .form-header {
    align-items: center;
}

.header-label {
    padding: 50px 0px;
    font-weight: bold;
}

.form-divider {
    flex-grow: 1;
    border: none;
    border-top: 1px solid black;
}
</style>
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card border border-1 border-primary rounded-0">
        <div class="card-header bg-primary text-white rounded-0">
            <p class="m-0 font-weight-bold">Local Sample Request List</p>
        </div>
        <div class="card-body">
            {{-- <h4 class="card-title d-flex justify-content-between align-items-center">
            
            </h4> --}}
            <div class="form-group">
                <form method="GET" >
                    <label>Show : </label>
                    <label class="checkbox-inline">
                        <input name="open" class="sample_request_status" type="checkbox" value="10" @if($open == 10) checked @endif> Open
                    </label>
                    <label class="checkbox-inline">
                        <input name="close" class="sample_request_status" type="checkbox" value="30" @if($close == 30) checked @endif> Closed
                    </label>
                    <button type="submit" class="btn btn-sm btn-primary">Filter Status</button>
                </form>
            </div>
            <div class="mb-3">
                <a href="#" id="copy_btn" class="btn btn-md btn-info">Copy</a>
                <form method="GET" action="{{url('sample_request_export')}}" class="d-inline-block">
    
                    <input type="hidden" name="open" value="{{$open}}">
                    <input type="hidden" name="close" value="{{$close}}">
                    <input type="hidden" name="srfType" value="Local">
                    
                    <button type="submit" class="btn btn-success">Export</button>
                </form>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <span>Show</span>
                    <form method="GET" class="d-inline-block">
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
                <form method="GET" class="custom_form mb-3" enctype="multipart/form-data">
                    <div class="row height d-flex justify-content-end align-items-end">
                        <div class="col-md-8">
                            <div class="search">
                                <i class="ti ti-search"></i>
                                <input type="text" class="form-control" placeholder="Search Sample Request" name="search" value="{{$search}}"> 
                                <button class="btn btn-sm btn-info">Search</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="sample_request_table">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>SRF # </th>
                            <th>Date Requested</th>
                            <th>Date Required</th>
                            <th>Client Name</th>
                            <th>Application </th>
                            <th>Status</th>
                            <th>Progress </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sampleRequests as $srf)
                        <tr>
                                <td>
                                    @if(authCheckIfItsCustomerService(auth()->user()->role->id))
                                    <button type="button" id="editSrf{{ $srf->Id }}" class="btn btn-sm btn-warning btn-outline"
                                        data-target="#edit{{ $srf->Id }}" data-toggle="modal" title='Edit SRF'>
                                        <i class="ti-pencil"></i>
                                    </button>
                                @endif
                                </td>
                                <td><a href="{{ url('samplerequest/view/' . $srf->Id.'/'.$srf->SrfNumber.'/?origin=cs_local') }}" title="View Sample Request">{{ $srf->SrfNumber }}</a></td>
                                <td>{{ !empty($srf->DateRequested) ? date('m/d/Y H:i' , strtotime($srf->DateRequested)) : '00/00/0000' }}</td>
                                <td>{{ !empty($srf->DateRequired) ? date('m/d/Y', strtotime($srf->DateRequired)) : '00/00/0000' }}</td>
                                <td>{{ optional($srf->client)->Name }}</td>
                                <td>
                                    @foreach ($srf->requestProducts as $product)
                                        {{ optional($product->productApplicationsId)->Name }}<br>
                                    @endforeach
                                </td>
                               <td>
                                    @if($srf->Status == 10)
                                        <span class="badge badge-success">Open</span>
                                    @elseif($srf->Status == 30)
                                        <span class="badge badge-warning">Closed</span>
                                    @else
                                        {{ $srf->Status }}
                                    @endif
                                </td>
                                <td>{{ optional($srf->progressStatus)->name }}</td>
                                
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            {!! $sampleRequests->appends(['search' => $search,'open' => $open])->links() !!}
            @php
                $total = $sampleRequests->total();
                $currentPage = $sampleRequests->currentPage();
                $perPage = $sampleRequests->perPage();

                $from = ($currentPage - 1) * $perPage + 1;
                $to = min($currentPage * $perPage, $total);
            @endphp
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>Showing {{ $from }} to {{ $to }} of {{ $total }} entries</div>
            </div>
            {{-- @elseif (auth()->user()->role->type == 'IS')
            <div class="table-responsive" >
                <table class="table table-striped table-bordered table-hover" id="sample_request_table" width="100%">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>SRF #</th>
                            <th>Date Requested</th>
                            <th>Date Required</th>
                            <th>Ref Code</th>
                            <th>Type</th>
                            <th>Client Name</th>
                            <th>Region</th>
                            <th>Country</th>
                            <th>Primary Sales Person</th>
                            <th>Index</th>
                            <th>Number of Packages</th>
                            <th>Quantity</th>
                            <th>Product Code</th>
                            <th>Product Label</th>
                            <th>Application</th>
                            <th>Description</th>
                            <th>RPE No.</th>
                            <th>CRR No.</th>
                            <th>Date Sample Received</th>
                            <th>Date Dispatched</th>
                            <th>Status</th>
                            <th>Progress</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sampleRequests as $srf)
                            <tr>
                                <td align="center">
                                    <a href="{{ url('samplerequest/view/' . $srf->Id) }}" class="btn btn-sm btn-info btn-outline" title="View Request"><i class="ti-eye"></i></a>
                                    <button type="button" id="editSrf{{ $srf->Id }}" class="btn btn-sm btn-warning btn-outline"
                                        data-target="#edit{{ $srf->Id }}" data-toggle="modal" title='Edit SRF'>
                                        <i class="ti-pencil"></i>
                                    </button>
                                </td>
                                <td>{{ $srf->SrfNumber }}</td>
                                <td>{{ !empty($srf->DateRequested) ? date('m/d/Y H:i', strtotime($srf->DateRequested)) : '00/00/0000' }}</td>
                                <td>{{ !empty($srf->DateRequired) ? date('m/d/Y', strtotime($srf->DateRequired)) : '00/00/0000' }}</td>
                                <td>
                                    @if($srf->RefCode == 1)
                                        RND
                                    @elseif($srf->RefCode == 2)
                                        QCD
                                    @else
                                        {{ $srf->RefCode }}
                                    @endif
                                </td>
                                <td>
                                    @if($srf->SrfType == 1)
                                        Regular
                                    @elseif($srf->SrfType == 2)
                                        PSS
                                    @elseif($srf->SrfType == 3)
                                        CSS
                                    @else
                                        {{ $srf->SrfType }}
                                    @endif
                                </td>
                                <td>{{ optional($srf->client)->Name }}</td>
                                <td>{{ optional(optional($srf->client)->clientregion)->Name }}</td>
                                <td>{{ optional(optional($srf->client)->clientcountry)->Name }}</td>
                                <td>
                                    @if($srf->primarySalesPerson)
                                        {{$srf->primarySalesPerson->full_name}}
                                    @elseif($srf->primarySalesById)
                                        {{$srf->primarySalesById->full_name}}
                                    @endif
                                </td>
                                <td>
                                    @foreach ($srf->requestProducts as $product)
                                        {{ $product->ProductIndex }}<br>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($srf->requestProducts as $product)
                                        {{ $product->NumberOfPackages }}<br>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($srf->requestProducts as $product)
                                        {{ $product->Quantity }}<br>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($srf->requestProducts as $product)
                                        {{ $product->ProductCode }}<br>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($srf->requestProducts as $product)
                                        {{ $product->Label }}<br>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($srf->requestProducts as $product)
                                        {{ optional($product->productApplicationsId)->Name }}<br>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($srf->requestProducts as $product)
                                        {{ $product->ProductDescription }}<br>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($srf->requestProducts as $product)
                                        {{ $product->RpeNumber }}<br>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($srf->requestProducts as $product)
                                        {{ $product->CrrNumber }}<br>
                                    @endforeach
                                </td>                                
                                <td>{{  date('m/d/y', strtotime($srf->DateSampleReceived)) }}</td>
                                <td>{{  date('m/d/y', strtotime($srf->DateDispatched)) }}</td>
                                <td>
                                    @if($srf->Status == 10)
                                        Open
                                    @elseif($srf->Status == 30)
                                        Closed
                                    @else
                                        {{ $srf->Status }}
                                    @endif
                                </td>
                                <td>{{ optional($srf->progressStatus)->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            
                {!! $products->appends(['search' => $search])->links() !!}
            
                @php
                    $total = $products->total();
                    $currentPage = $products->currentPage();
                    $perPage = $products->perPage();
            
                    $from = ($currentPage - 1) * $perPage + 1;
                    $to = min($currentPage * $perPage, $total);
                @endphp
            
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>Showing {{ $from }} to {{ $to }} of {{ $total }} entries</div>
                </div>
            </div>
            @endif  --}}
            
        </div>
    </div>
</div>
<script src="{{ asset('js/sweetalert2.min.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
   
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: "{{ session('success') }}",
            confirmButtonText: 'OK'
        });
    @endif

    $(".table").tablesorter({
            theme : "bootstrap",
        })

    $("[name='entries']").on('change', function() {
            $(this).closest('form').submit()
        })
});

    $(document).ready(function(){
        $('#copy_btn').click(function() {
            var tableData = '';

            $('#sample_request_table thead tr').each(function(rowIndex, tr) {
                $(tr).find('th').each(function(cellIndex, th) {
                    tableData += $(th).text().trim() + '\t';
                });
                tableData += '\n';
            });

            $('#sample_request_table tbody tr').each(function(rowIndex, tr) {
                $(tr).find('td').each(function(cellIndex, td) {
                    tableData += $(td).text().trim() + '\t';
                });
                tableData += '\n';
            });

            var tempTextArea = $('<textarea>');
            $('body').append(tempTextArea);
            tempTextArea.val(tableData).select();
            document.execCommand('copy');
            tempTextArea.remove();

            Swal.fire({
                icon: 'success',
                title: 'Copied!',
                text: 'Table data has been copied to the clipboard.',
                timer: 1500,
                showConfirmButton: false
            });
        });
    })
</script>
@foreach ($sampleRequests as $srf)
@include('customer_service.edit')
@endforeach
@endsection


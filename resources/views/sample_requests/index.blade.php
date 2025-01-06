@extends('layouts.header')
@section('css')
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
@endsection
@section('content')

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card border border-1 border-primary rounded-0" style="max-height: 80vh;">
        <div class="card-header bg-primary rounded-0 font-weight-bold text-white">
            Sample Request
        </div>
        <div class="card-body" style="overflow: auto;">
            <div class="card-title d-flex justify-content-between align-items-center">
                <form method="GET" onsubmit="show()">
                    <label>Show : </label>
                    <label class="checkbox-inline">
                        <input name="open" class="sample_request_status" type="checkbox" value="10" @if($open == 10) checked @endif> Open
                    </label>
                    <label class="checkbox-inline">
                        <input name="close" class="sample_request_status" type="checkbox" value="30" @if($close == 30) checked @endif> Closed
                    </label>
                    <button type="submit" class="btn btn-sm btn-primary">Filter Status</button>
                </form>
                {{-- Sample Request List --}}
                @if(checkRolesIfHaveCreate('Sample Request Form', auth()->user()->department_id, auth()->user()->role_id) == "yes")
                    <button type="button" class="btn btn-md btn-outline-primary" id="addSrfBtn" data-toggle="modal" data-target="#formSampleRequest">New</button>
                @endif
            </div>
            <div class="mb-3">
                <a href="#" id="copy_btn" class="btn btn-md btn-outline-info">Copy</a>
                <form method="GET" action="{{url('sample_request_export')}}" class="d-inline-block">
                    <input type="hidden" name="open" value="{{$open}}">
                    <input type="hidden" name="close" value="{{$close}}">
                    <button type="submit" class="btn btn-outline-success">Export</button>
                </form>
            </div>
            <div class="row">
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
                        <div class="row height d-flex justify-content-end align-items-end">
                            <div class="col-md-10">
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
            
            @if(auth()->user()->role->type == 'LS')
                <div class="table-responsive" style="overflow: auto; height: 80vh;">
                    <table class="table table-striped table-bordered table-hover" id="sample_request_table">
                        <thead>
                            <tr>
                                <!-- <th>Action</th> -->
                                <th>SRF # </th>
                                <th>Ref Code</th>
                                <th>Date Requested</th>
                                <th>Date Required</th>
                                <th>Client Name</th>
                                <th>Application </th>
                                <th>Status</th>
                                <th>Progress </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($sampleRequests) > 0)
                                @foreach ($sampleRequests as $srf)
                                <tr>
                                    <!-- <td align="center">
                                        <a href="{{ url('samplerequest/view/' . $srf->Id) }}" class="btn btn-sm btn-outline-info" title="View Request"><i class="ti-eye"></i></a>
                                        @php
                                            $user = auth()->user();
                                        @endphp
                                        <button type="button" id="editSrf{{ $srf->Id }}" class="btn btn-sm btn-outline-warning"
                                            data-target="#edit{{ $srf->Id }}" data-toggle="modal" title='Edit SRF' @if($user->id != $srf->PrimarySalesPersonId && $user->user_id != $srf->PrimarySalesPersonId) disabled @endif>
                                            <i class="ti-pencil"></i>
                                        </button>    
                                    </td> -->
                                    <td>
                                        @if(request('progress') && request('open'))
                                        <a href="{{ url('samplerequest/view/' . $srf->Id.'/'.$srf->SrfNumber.'/?origin=for_approval') }}" title="View Sample Request">{{ $srf->SrfNumber }}</a>
                                        @else 
                                        <a href="{{ url('samplerequest/view/' . $srf->Id.'/'.$srf->SrfNumber) }}" title="View Sample Request">{{ $srf->SrfNumber }}</a>
                                        @endif
                                    </td>
                                    <td>
                                        @if($srf->RefCode == 1)
                                            RND
                                        @elseif($srf->RefCode == 2)
                                            QCD-WHI
                                        @elseif($srf->RefCode == 3)
                                            QCD-PBI
                                        @elseif($srf->RefCode == 4)
                                            QCD-MRDC
                                        @elseif($srf->RefCode == 5)
                                            QCD-CCC
                                        @endif
                                    </td>
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
                                            <div class="badge badge-success">Open</div>
                                        @elseif($srf->Status == 30)
                                            <div class="badge badge-warning">Closed</div>
                                        @elseif($srf->Status == 50)
                                            <div class="badge badge-danger">Cancelled</div>
                                        @endif
                                    </td>
                                    <td>{{ optional($srf->progressStatus)->name }}</td>
                                </tr>
                                @endforeach
                            @else 
                                <tr>
                                    <td colspan="8" class="text-center">No data available</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    <!-- {!! $sampleRequests->appends(['search' => $search, 'open' => $open, 'close' => $close])->links() !!} -->
                    {{ $sampleRequests->appends(request()->query())->links() }}
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
                </div>
            @elseif (auth()->user()->role->type == 'IS')
                <div class="table-responsive" style="overflow: auto; height: 80vh;">
                    <table class="table table-striped table-bordered table-hover" id="sample_request_table" width="100%">
                        <thead>
                            <tr>
                                <!-- <th>Action</th> -->
                                <th>SRF #</th>
                                <th>Ref Code</th>
                                <th>Date Requested</th>
                                <th>Date Required</th>
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
                                <th>Is Returned?</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($sampleRequests) > 0)
                                @foreach ($sampleRequests as $srf)
                                    <tr>
                                        <!-- <td align="center">
                                            <a href="{{ url('samplerequest/view/' . $srf->Id) }}" class="btn btn-sm btn-outline-info" title="View Request"><i class="ti-eye"></i></a>
                                            <button type="button" id="editSrf{{ $srf->Id }}" class="btn btn-sm btn-outline-warning"
                                                data-target="#edit{{ $srf->Id }}" data-toggle="modal" title='Edit SRF'>
                                                <i class="ti-pencil"></i>
                                            </button>
                                        </td> -->
                                        <td>
                                            @if(request('progress') && request('open'))
                                            <a href="{{ url('samplerequest/view/' . $srf->Id.'/'.$srf->SrfNumber.'/?origin=for_approval') }}" title="View Sample Request">{{ $srf->SrfNumber }}</a>
                                            @else
                                            <a href="{{ url('samplerequest/view/' . $srf->Id.'/'.$srf->SrfNumber) }}" title="View Sample Request">{{ $srf->SrfNumber }}</a>
                                            @endif
                                        </td>
                                        {{-- <td>{{ !empty($srf->DateRequested) ? date('m/d/Y H:i', strtotime($srf->DateRequested)) : '00/00/0000' }}</td>
                                        <td>{{ !empty($srf->DateRequired) ? date('m/d/Y', strtotime($srf->DateRequired)) : '00/00/0000' }}</td> --}}
                                        <td>
                                            @if($srf->RefCode == 1)
                                                RND
                                            @elseif($srf->RefCode == 2)
                                                QCD-WHI
                                            @elseif($srf->RefCode == 3)
                                                QCD-PBI
                                            @elseif($srf->RefCode == 4)
                                                QCD-MRDC
                                            @elseif($srf->RefCode == 5)
                                                QCD-CCC
                                            @endif
                                        </td>
                                        <td>{{ !empty($srf->DateRequested) ? date('m/d/Y H:i', strtotime($srf->DateRequested)) : '00/00/0000' }}</td>
                                        <td>{{ !empty($srf->DateRequired) ? date('m/d/Y', strtotime($srf->DateRequired)) : '00/00/0000' }}</td>
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
                                            {{-- {{ $srf->primarySalesPerson->full_name ?? 'N/A' }} --}}
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
                                        <td>{{ $srf->DateSampleReceived ? date('m/d/y', strtotime($srf->DateSampleReceived)) : 'NA' }}</td>
                                        <td>{{ $srf->DateDispatched ? date('m/d/y', strtotime($srf->DateDispatched)) : 'NA' }}</td>

                                        <td>
                                            @if($srf->Status == 10)
                                                <div class="badge badge-success">Open</div>
                                            @elseif($srf->Status == 30)
                                                <div class="badge badge-warning">Closed</div>
                                            @else
                                                <div class="badge badge-danger">Cancelled</div>
                                            @endif
                                        </td>
                                        <td>{{ optional($srf->progressStatus)->name }}</td>
                                        <td>
                                            @if($srf->ReturnToSales == 1)
                                                Yes
                                            @else
                                                No 
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else 
                                <tr>
                                    <td colspan="22" class="text-center">No data available</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    <!-- {!! $products->appends(['search' => $search, 'open' => $open, 'close' => $close])->links() !!}
                    @php
                        $total = $products->total();
                        $currentPage = $products->currentPage();
                        $perPage = $products->perPage();
                
                        $from = ($currentPage - 1) * $perPage + 1;
                        $to = min($currentPage * $perPage, $total);
                    @endphp
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>Showing {{ $from }} to {{ $to }} of {{ $total }} entries</div>
                    </div> -->
                    <!-- {!! $sampleRequests->appends(['search' => $search, 'open' => $open, 'close' => $close])->links() !!} -->
                    {{ $sampleRequests->appends(request()->query())->links() }}
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
                </div>
            @else
                <div class="table-responsive" style="overflow: auto; height: 80vh;">
                    <table class="table table-striped table-bordered table-hover" id="sample_request_table">
                        <thead>
                            <tr>
                                <!-- <th>Action</th> -->
                                <th>SRF #</th>
                                <th>Ref Code</th>
                                <th>Date Requested</th>
                                <th>Date Required</th>
                                <th>Client Name</th>
                                <th>Application</th>
                                <th>Status</th>
                                <th>Progress</th>
                                <th>Is Returned?</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sampleRequests as $srf)
                            <tr>
                                <!-- <td align="center">
                                    <a href="{{ url('samplerequest/view/' . $srf->Id) }}" class="btn btn-sm btn-outline-info" title="View Request"><i class="ti-eye"></i></a>
                                    <button type="button" id="editSrf{{ $srf->Id }}" class="btn btn-sm btn-outline-warning"
                                        data-target="#edit{{ $srf->Id }}" data-toggle="modal" title='Edit SRF'>
                                        <i class="ti-pencil"></i>
                                    </button>    
                                </td> -->
                                <td><a href="{{ url('samplerequest/view/' . $srf->Id.'/'.$srf->SrfNumber) }}"  title="View Sample Request">{{ $srf->SrfNumber }}</a></td>
                                <td>
                                    @if($srf->RefCode == 1)
                                        RND
                                    @elseif($srf->RefCode == 2)
                                        QCD-WHI
                                    @elseif($srf->RefCode == 3)
                                        QCD-PBI
                                    @elseif($srf->RefCode == 4)
                                        QCD-MRDC
                                    @elseif($srf->RefCode == 5)
                                        QCD-CCC
                                    @endif
                                </td>
                                <td>{{ !empty($srf->DateRequested) ? date('m/d/Y H:i' , strtotime($srf->DateRequested)) : '00/00/0000' }}</td>
                                <td>{{ !empty($srf->DateRequired) ? date('m/d/Y', strtotime($srf->DateRequired)) : '00/00/0000' }}</td>
                                <td>
                                    <a href="{{url('view_client/'.optional($srf->client)->id)}}">
                                        {{ optional($srf->client)->Name }}
                                    </a>
                                </td>
                                <td>
                                    @foreach ($srf->requestProducts as $product)
                                        {{ optional($product->productApplicationsId)->Name }}<br>
                                    @endforeach
                                </td>
                                <td>
                                    @if($srf->Status == 10)
                                        <div class="badge badge-success">Open</div>
                                    @elseif($srf->Status == 30)
                                        <div class="badge badge-warning">Closed</div>
                                    @else
                                        <div class="badge badge-danger">Cancelled</div>
                                    @endif
                                </td>
                                <td>{{ optional($srf->progressStatus)->name }}</td>
                                <td>
                                    @if($srf->ReturnToSales == 1)
                                        Yes
                                    @else
                                        No 
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <!-- $sampleRequests->appends(['search' => $search, 'open' => $open, 'close' => $close])->links() !!} -->
                </div>
                {{ $sampleRequests->appends(request()->query())->links() }}
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
            @endif 
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

        // $("#addSrfBtn").on('click', function() {
        //     var primarySales = $('[name="PrimarySalesPersonId"]').val();
            
        //     refreshSecondaryApprovers(primarySales)
        // })

        // $('.editBtn').on('click', function() {
        //     var primarySales = $(this).data('primarysales')
        //     var secondarySales = $(this).data('secondarysales');

        //     console.log(primarySales);
            
        //     $.ajax({
        //         type: "POST",
        //         url: "{{url('refresh_user_approvers')}}",
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         data: {
        //             ps: primarySales,
        //         },
        //         success: function(data)
        //         {
        //             setTimeout(() => {
        //                 $('[name="SecondarySalesPersonId"]').html(data) 
        //                 // $('[name="SecondarySalesPersonId"]').val(secondarySales) 
        //             }, 500);
        //         }
        //     })
        // })

        // $('[name="PrimarySalesPersonId"]').on('change', function() {
        //     var primarySales = $(this).val();

        //     refreshSecondaryApprovers(primarySales)
        // })

        // function refreshSecondaryApprovers(primarySales)
        // {
        //     $.ajax({
        //         type: "POST",
        //         url: "{{url('refresh_user_approvers')}}",
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         data: {
        //             ps: primarySales,
        //         },
        //         success: function(data)
        //         {
        //             setTimeout(() => {
        //                 $('[name="SecondarySalesPersonId"]').html(data) 
        //             }, 500);
        //         }
        //     })
        // }
    })
</script>
@include('sample_requests.create_srf')

{{-- @foreach ($sampleRequests as $srf)
@foreach ($srf->requestProducts as $product)
@include('sample_requests.edit')
@endforeach  
@endforeach   --}}

{{-- @if(auth()->user()->role->type == 'LS') --}}
{{-- @foreach ($sampleRequests as $srf)
@include('sample_requests.edit')
@endforeach --}}
{{-- @elseif ((auth()->user()->role->type == 'IS'))
@foreach ($products as $product)
@include('sample_requests.edit')
@endforeach --}}
{{-- @endif --}}


@endsection


@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card rounded-0 border border-1 border-primary" style="max-height: 80vh;">
        <div class="card-header bg-primary text-white font-weight-bold rounded-0">
            Customer Requirement List
        </div>
        <div class="card-body" style=" overflow:auto;">
            @include('components.error')
            @if(checkRolesIfHaveCreate('Customer Requirement', auth()->user()->department_id, auth()->user()->role_id) == "yes")
            <h4 class="card-title d-flex justify-content-between align-items-center">
            {{-- Customer Requirement List --}}
            <button type="button" class="btn btn-md btn-outline-primary" id="addCustomerRequirement" data-toggle="modal" data-target="#AddCustomerRequirement">New</button>
            </h4>
            @else
            {{-- <h4 class="card-title d-flex justify-content-between align-items-center">Customer Requirement List</h4> --}}
            @endif

            <div class="form-group">
                <form method="GET" onsubmit="show()">
                    <label>Show : </label>
                    <label class="checkbox-inline">
                        <input name="{{$open ? 'open' : 'status'}}" class="activity_status" type="checkbox" value="10" @if($open == 10 || $status == 10) checked @endif> Open
                    </label>
                    <label class="checkbox-inline">
                        <input name="{{$close ? 'close' : 'status'}}" class="activity_status" type="checkbox" value="30" @if($close == 30 || $status == 30) checked @endif> Closed
                    </label>
                    <button type="submit" class="btn btn-sm btn-primary">Filter Status</button>
                </form>
            </div>

            <div class="mb-3">
                <a href="#" id="copy_btn" class="btn btn-md btn-outline-info">Copy</a>
                <form method="GET" action="{{url('customer_requirement_export')}}" class="d-inline-block">
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
                                    <input type="text" class="form-control" placeholder="Search Customer Requirement" name="search" value="{{$search}}"> 
                                    <button class="btn btn-sm btn-info">Search</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="table-responsive" >
                <table class="table table-striped table-bordered table-hover" id="customer_requirement_table" width="100%" >
                    @if(auth()->user()->role->type == "LS" || auth()->user()->role->type == "RND" || auth()->user()->role->type == "QCD-MRDC" || auth()->user()->role->type == "QCD-PBI" || auth()->user()->role->type == "QCD-WHI" || auth()->user()->role->type == "QCD-CCC" || auth()->user()->role->type == "ITD")
                    <thead>
                        <tr>
                            <!-- <th>Action</th> -->
                            <th>CRR #
                                <!-- <a href="{{ route('customer_requirement.index', [
                                    'sort' => 'CrrNumber', 
                                    'direction' => request('sort') == 'CrrNumber' && request('direction') == 'asc' ? 'desc' : 'asc'
                                ]) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'CrrNumber' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a> -->
                            </th>
                            <th>Ref Code</th>
                            <th>Date Created
                                <!-- <a href="{{ route('customer_requirement.index', [
                                    'sort' => 'DateCreated', 
                                    'direction' => request('sort') == 'DateCreated' && request('direction') == 'asc' ? 'desc' : 'asc'
                                ]) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'DateCreated' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a> -->
                            </th>
                            <th>Due Date
                                <!-- <a href="{{ route('customer_requirement.index', [
                                    'sort' => 'DueDate', 
                                    'direction' => request('sort') == 'DueDate' && request('direction') == 'asc' ? 'desc' : 'asc'
                                ]) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'DueDate' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a> -->
                            </th>
                            <th>Client Name
                                <!-- <a href="{{ route('customer_requirement.index', [
                                    'sort' => 'ClientId', 
                                    'direction' => request('sort') == 'ClientId' && request('direction') == 'asc' ? 'desc' : 'asc'
                                ]) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'ClientId' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a> -->
                            </th>
                            <th>Application
                                <!-- <a href="{{ route('customer_requirement.index', [
                                    'sort' => 'ApplicationId', 
                                    'direction' => request('sort') == 'ApplicationId' && request('direction') == 'asc' ? 'desc' : 'asc'
                                ]) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'ApplicationId' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a> -->
                            </th>
                            <th>Recommendation
                                <!-- <a href="{{ route('customer_requirement.index', [
                                    'sort' => 'Recommendation', 
                                    'direction' => request('sort') == 'Recommendation' && request('direction') == 'asc' ? 'desc' : 'asc'
                                ]) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'Recommendation' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a> -->
                            </th>
                            <th>Status
                                <!-- <a href="{{ route('customer_requirement.index', [
                                    'sort' => 'Status', 
                                    'direction' => request('sort') == 'Status' && request('direction') == 'asc' ? 'desc' : 'asc'
                                ]) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'Status' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a> -->
                            </th>
                            <th>Progress
                                <!-- <a href="{{ route('customer_requirement.index', [
                                    'sort' => 'Progress', 
                                    'direction' => request('sort') == 'Progress' && request('direction') == 'asc' ? 'desc' : 'asc'
                                ]) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'Progress' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a> -->
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($customer_requirements) > 0)
                        @foreach ($customer_requirements as $customerRequirement)
                        <tr>
                            <!-- <td>
                                <a href="{{url('view_customer_requirement/'.$customerRequirement->id)}}" class="btn btn-sm btn-info" title="View Customer Requirements">
                                    <i class="ti-eye"></i>
                                </a>
                                @php
                                    $user = auth()->user();
                                @endphp
                                <button type="button" class="btn btn-sm btn-warning editBtn" data-primarysales="{{$customerRequirement->PrimarySalesPersonId}}" data-secondarysales="{{$customerRequirement->SecondarySalesPersonId}}"
                                    data-target="#editCrr-{{ $customerRequirement->id }}" data-toggle="modal" title='Edit' @if($user->id != $customerRequirement->PrimarySalesPersonId && $user->user_id != $customerRequirement->PrimarySalesPersonId) disabled @endif>
                                    <i class="ti-pencil"></i>
                                </button>  
                                <form method="POST" action="{{url('delete_crr/'.$customerRequirement->id)}}" class="d-inline-block" onsubmit="show()">
                                    @csrf
                                    <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{ $customerRequirement->Id }}" @if($user->id != $customerRequirement->PrimarySalesPersonId && $user->user_id != $customerRequirement->PrimarySalesPersonId) disabled @endif>
                                        <i class="ti-trash"></i>
                                    </button>
                                </form>
                            </td> -->
                            <td>
                                {{-- <a href="{{url('view_customer_requirement/'.$customerRequirement->id)}}" title="View Customer Requirements">{{ optional($customerRequirement)->CrrNumber }}</a> --}}
                                <a href="{{url('view_customer_requirement/'.$customerRequirement->id.'/'.$customerRequirement->CrrNumber)}}" title="View Customer Requirements">
                                    {{$customerRequirement->CrrNumber}}
                                </a>
                            </td>
                            <td>
                                @if($customerRequirement->RefCode != null)
                                {{$customerRequirement->RefCode}}
                                @else
                                RND
                                @endif
                            </td>
                            <td>
                                {{date('M d Y', strtotime($customerRequirement->DateCreated))}}
                            </td>
                            <td>
                                @if($customerRequirement->DueDate != null)
                                {{ date('M d Y', strtotime($customerRequirement->DueDate)) }}
                                @else
                                N/A
                                @endif
                            </td>
                            <td>{{ optional($customerRequirement->client)->Name }}</td>
                            <td>{{ optional($customerRequirement->product_application)->Name }}</td>
                            <td style="white-space: break-spaces; width: 100%;">
                                @if($customerRequirement->Recommendation != null)
                                {{ $customerRequirement->Recommendation }}
                                @else
                                N/A
                                @endif
                            </td>
                            <td>
                                @if($customerRequirement->Status == 10)
                                    <div class="badge badge-success">Open</div>
                                @elseif($customerRequirement->Status == 30)
                                    <div class="badge badge-warning">Closed</div>
                                @elseif($customerRequirement->Status == 50)
                                    <div class="badge badge-danger">Cancelled</div>
                                @endif
                            </td>
                            <td>{{ optional($customerRequirement->progressStatus)->name }}</td>
                            
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="9" class="text-center">No data available.</td>
                        </tr>
                        @endif
                    </tbody>
                    @elseif(auth()->user()->role->type == "IS")
                    <thead>
                        <tr>
                            <!-- <th>Action</th> -->
                            <th>CRR #
                                <!-- <a href="{{ route('customer_requirement.index', [
                                    'sort' => 'CrrNumber', 
                                    'direction' => request('sort') == 'CrrNumber' && request('direction') == 'asc' ? 'desc' : 'asc'
                                ]) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'CrrNumber' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a> -->
                            </th>
                            <th>Ref Code</th>
                            <th>Date Created
                                <!-- <a href="{{ route('customer_requirement.index', [
                                    'sort' => 'DateCreated', 
                                    'direction' => request('sort') == 'DateCreated' && request('direction') == 'asc' ? 'desc' : 'asc'
                                ]) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'DateCreated' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a> -->
                            </th>
                            <th>Due Date
                                <!-- <a href="{{ route('customer_requirement.index', [
                                    'sort' => 'DueDate', 
                                    'direction' => request('sort') == 'DueDate' && request('direction') == 'asc' ? 'desc' : 'asc'
                                ]) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'DueDate' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a> -->
                            </th>
                            <th>Client Name
                                <!-- <a href="{{ route('customer_requirement.index', [
                                    'sort' => 'ClientId', 
                                    'direction' => request('sort') == 'ClientId' && request('direction') == 'asc' ? 'desc' : 'asc'
                                ]) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'ClientId' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a>
                            </th> -->
                            <th>Application
                                <!-- <a href="{{ route('customer_requirement.index', [
                                    'sort' => 'ApplicationId', 
                                    'direction' => request('sort') == 'ApplicationId' && request('direction') == 'asc' ? 'desc' : 'asc'
                                ]) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'ApplicationId' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a> -->
                            </th>
                            <th>Competitor
                                <!-- <a href="{{ route('customer_requirement.index', [
                                    'sort' => 'Competitor', 
                                    'direction' => request('sort') == 'Competitor' && request('direction') == 'asc' ? 'desc' : 'asc'
                                ]) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'Competitor' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a> -->
                            </th>
                            <th>Primary Sales Person
                                <!-- <a href="{{ route('customer_requirement.index', [
                                    'sort' => 'PrimarySalesPersonId', 
                                    'direction' => request('sort') == 'PrimarySalesPersonId' && request('direction') == 'asc' ? 'desc' : 'asc'
                                ]) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'PrimarySalesPersonId' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a> -->
                            </th>
                            <th>Details of Requirement
                                <!-- <a href="{{ route('customer_requirement.index', [
                                    'sort' => 'DetailsOfRequirement', 
                                    'direction' => request('sort') == 'DetailsOfRequirement' && request('direction') == 'asc' ? 'desc' : 'asc'
                                ]) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'DetailsOfRequirement' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a> -->
                            </th>
                            <th>Recommendation
                                <!-- <a href="{{ route('customer_requirement.index', [
                                    'sort' => 'Recommendation', 
                                    'direction' => request('sort') == 'Recommendation' && request('direction') == 'asc' ? 'desc' : 'asc'
                                ]) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'Recommendation' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a> -->
                            </th>
                            <th>Date Received
                                <!-- <a href="{{ route('customer_requirement.index', [
                                    'sort' => 'DateReceived', 
                                    'direction' => request('sort') == 'DateReceived' && request('direction') == 'asc' ? 'desc' : 'asc'
                                ]) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'DateReceived' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a> -->
                            </th>
                            <th>Days Late
                                <!-- <a href="{{ route('customer_requirement.index', [
                                    'sort' => 'DueDate', 
                                    'direction' => request('sort') == 'DueDate' && request('direction') == 'asc' ? 'desc' : 'asc'
                                ]) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'DueDate' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a> -->
                            </th>
                            <th>Nature of Request
                                <!-- <a href="{{ route('customer_requirement.index', [
                                    'sort' => 'NatureRequestId', 
                                    'direction' => request('sort') == 'NatureRequestId' && request('direction') == 'asc' ? 'desc' : 'asc'
                                ]) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'NatureRequestId' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a> -->
                            </th>
                            <th>Status
                                <!-- <a href="{{ route('customer_requirement.index', [
                                    'sort' => 'Status', 
                                    'direction' => request('sort') == 'Status' && request('direction') == 'asc' ? 'desc' : 'asc'
                                ]) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'Status' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a> -->
                            </th>
                            <th>Progress
                                <!-- <a href="{{ route('customer_requirement.index', [
                                    'sort' => 'Progress', 
                                    'direction' => request('sort') == 'Progress' && request('direction') == 'asc' ? 'desc' : 'asc'
                                ]) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'Progress' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a> -->
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($customer_requirements) > 0)
                        @foreach ($customer_requirements as $customerRequirement)
                        <tr>
                            <!-- <td>
                                <a href="{{url('view_customer_requirement/'.$customerRequirement->id)}}" class="btn btn-sm btn-info" title="View Customer Requirements">
                                    <i class="ti-eye"></i>
                                </a>
                                @php
                                    $user = auth()->user();
                                @endphp
                                <button type="button" class="btn btn-sm btn-warning editBtn" data-primarysales="{{$customerRequirement->PrimarySalesPersonId}}" data-secondarysales="{{$customerRequirement->SecondarySalesPersonId}}"
                                    data-target="#editCrr-{{ $customerRequirement->id }}" data-toggle="modal" title='Edit' @if($user->id != $customerRequirement->PrimarySalesPersonId && $user->user_id != $customerRequirement->PrimarySalesPersonId) disabled @endif>
                                    <i class="ti-pencil"></i>
                                </button>  
                                <form method="POST" action="{{url('delete_crr/'.$customerRequirement->id)}}" class="d-inline-block" onsubmit="show()">
                                    @csrf
                                    <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{ $customerRequirement->Id }}" @if($user->id != $customerRequirement->PrimarySalesPersonId && $user->user_id != $customerRequirement->PrimarySalesPersonId) disabled @endif>
                                        <i class="ti-trash"></i>
                                    </button>
                                </form>
                            </td> -->
                            <td>
                                <a href="{{url('view_customer_requirement/'.$customerRequirement->id.'/'.$customerRequirement->CrrNumber)}}" title="View Customer Requirements">
                                    {{$customerRequirement->CrrNumber}}
                                </a>
                            </td>
                            <td>
                                @if($customerRequirement->RefCode != null)
                                {{$customerRequirement->RefCode}}
                                @else
                                RND
                                @endif
                            </td>
                            <td>
                                {{date('M d Y', strtotime($customerRequirement->DateCreated))}}
                            </td>
                            <td>{{ date('M d Y', strtotime($customerRequirement->DueDate)) }}</td>
                            <td>{{ optional($customerRequirement->client)->Name }}</td>
                            <td>{{ optional($customerRequirement->product_application)->Name }}</td>
                            <td>{{$customerRequirement->Competitor}}</td>
                            <td>
                                @if($customerRequirement->primarySales)
                                    {{$customerRequirement->primarySales->full_name}}
                                @elseif($customerRequirement->primarySalesById)
                                    {{$customerRequirement->primarySalesById->full_name}}
                                @endif
                            </td>
                            <td>{!! nl2br(e($customerRequirement->DetailsOfRequirement)) !!}</td>
                            <td style="white-space: break-spaces; width: 100%;">{{ $customerRequirement->Recommendation }}</td>
                            <td>
                                @if($customerRequirement->DateReceived)
                                {{date('M d Y', strtotime($customerRequirement->DateReceived))}}
                                @else
                                No date received
                                @endif
                            </td>
                            <td>
                                @if($customerRequirement->DueDate)
                                {{date('M d Y', strtotime($customerRequirement->DueDate))}}
                                @else
                                No due date
                                @endif
                            </td>
                            <td>
                                @foreach ($customerRequirement->crrNature as $crr_nature)
                                    <small>{{optional($crr_nature->natureOfRequest)->Name}}</small><br>
                                @endforeach
                            </td>
                            <td>
                                @if($customerRequirement->Status == 10)
                                    <div class="badge badge-success">Open</div>
                                @elseif($customerRequirement->Status == 30)
                                    <div class="badge badge-warning">Closed</div>
                                @elseif($customerRequirement->Status == 50)
                                    <div class="badge badge-danger">Cancelled</div>
                                @endif
                            </td>
                            <td>{{ optional($customerRequirement->progressStatus)->name }}</td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="15" class="text-center">No data available.</td>
                        </tr>
                        @endif
                    </tbody>
                    @endif
                </table>
                <!-- {!! $customer_requirements->appends(['search' => $search, 'open' => $open, 'close' => $close])->links() !!} -->
            </div>
            {{ $customer_requirements->appends(request()->query())->links() }}
            @php
                $total = $customer_requirements->total();
                $currentPage = $customer_requirements->currentPage();
                $perPage = $customer_requirements->perPage();

                $from = ($currentPage - 1) * $perPage + 1;
                $to = min($currentPage * $perPage, $total);
            @endphp
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>Showing {{ $from }} to {{ $to }} of {{ $total }} entries</div>
            </div>
        </div>
    </div>
</div>

@include('customer_requirements.create')
@foreach ($customer_requirements as $customerRequirement)
@include('customer_requirements.edit_crr')
@endforeach

<script src="{{ asset('js/sweetalert2.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.natureRequestSelect').select2({
            width: "85%"
        });

        $(".table").tablesorter({
            theme : "bootstrap",
        })

        $('.addRow').on('click', function() {
            var newRow = `
                <div class="input-group mb-3">
                    <select class="form-control natureRequestSelect" name="NatureOfRequestId[]" required>
                        <option value="" disabled selected>Select Nature of Request</option>
                        @foreach($nature_requests as $nature_request)
                            <option value="{{ $nature_request->id }}">{{ $nature_request->Name }}</option>
                        @endforeach
                    </select>
                    <div class="input-group-append">
                        <button type="button" class="btn btn-danger removeRow">-</button>
                    </div>
                </div>
            `;

            $('.natureOfRequestContainer').append(newRow);
            $('.natureRequestSelect').select2();
        });

        $(document).on('click', '.removeRow', function() {
            if ($('.natureOfRequestContainer .input-group').length > 1)
            {
                $(this).closest('.input-group').remove();
            }
        });

        $('#copy_btn').click(function() {
            var tableData = '';

            $('#customer_requirement_table thead tr').each(function(rowIndex, tr) {
                $(tr).find('th').each(function(cellIndex, th) {
                    tableData += $(th).text().trim() + '\t';
                });
                tableData += '\n';
            });

            $('#customer_requirement_table tbody tr').each(function(rowIndex, tr) {
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

        $('.delete-btn').on('click', function() {
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
                    form.submit()
                }
            });
        })

        $("[name='entries']").on('change', function() {
            $(this).closest('form').submit()
        })

        // $('.deleteBtn').on('click', function() {
        //     console.log('asdad');
            
        // })

        $("#addCustomerRequirement").on('click', function() {
            var primarySales = $('[name="PrimarySalesPersonId"]').val();
            
            refreshSecondaryApprovers(primarySales)
        })

        // $('.editBtn').on('click', function() {
        //     var primarySales = $(this).data('primarysales')
        //     var secondarySales = $(this).data('secondarysales');
            
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

        $('[name="PrimarySalesPersonId"]').on('change', function() {
            var primarySales = $(this).val();

            refreshSecondaryApprovers(primarySales)
        })

        function refreshSecondaryApprovers(primarySales)
        {
            $.ajax({
                type: "POST",
                url: "{{url('refresh_crr_secondary_sales_person')}}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    ps: primarySales,
                },
                success: function(data)
                {
                    setTimeout(() => {
                        $('[name="SecondarySalesPersonId"]').html(data) 
                    }, 500);
                }
            })
        }
    })
</script>
@endsection


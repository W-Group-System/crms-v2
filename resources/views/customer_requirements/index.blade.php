@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            Customer Requirement List
            <button type="button" class="btn btn-md btn-primary" name="add_customer_requirement" data-toggle="modal" data-target="#AddCustomerRequirement" class="btn btn-md btn-primary">Add Customer Requirement</button>
            </h4>

            <div class="form-group">
                <form method="GET" >
                    <label>Show : </label>
                    <label class="checkbox-inline">
                        <input name="open" class="activity_status" type="checkbox" value="10" @if($open == 10) checked @endif> Open
                    </label>
                    <label class="checkbox-inline">
                        <input name="close" class="activity_status" type="checkbox" value="30" @if($close == 30) checked @endif> Closed
                    </label>
                    <button type="submit" class="btn btn-sm btn-primary">Filter Status</button>
                </form>
            </div>

            <div class="row height d-flex mb-3">
                <div class="col-md-5 mt-2">
                    <a href="#" id="copy_btn" class="btn btn-md btn-info">Copy</a>
                    
                    <form method="GET" action="{{url('customer_requirement_export')}}" class="d-inline-block">

                        <input type="hidden" name="open" value="{{$open}}">
                        <input type="hidden" name="close" value="{{$close}}">
                        
                        <button type="submit" class="btn btn-success">Export</button>
                    </form>
                </div>

                <div class="offset-md-2 col-md-5 mt-2">
                    <form method="GET">
                        <div class="search">
                            <i class="ti ti-search"></i>
                            <input type="text" class="form-control" placeholder="Search Customer Requirement" name="search" value="{{$search}}"> 
                            <button type="submit" class="btn btn-sm btn-info">Search</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="customer_requirement_table" width="100%">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>CRR #
                                <a href="{{ route('customer_requirement.index', [
                                    'sort' => 'CrrNumber', 
                                    'direction' => request('sort') == 'CrrNumber' && request('direction') == 'asc' ? 'desc' : 'asc'
                                ]) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'CrrNumber' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a>
                            </th>
                            <th>Date Created
                                <a href="{{ route('customer_requirement.index', [
                                    'sort' => 'DateCreated', 
                                    'direction' => request('sort') == 'DateCreated' && request('direction') == 'asc' ? 'desc' : 'asc'
                                ]) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'DateCreated' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a>
                            </th>
                            <th>Due Date
                                <a href="{{ route('customer_requirement.index', [
                                    'sort' => 'DueDate', 
                                    'direction' => request('sort') == 'DueDate' && request('direction') == 'asc' ? 'desc' : 'asc'
                                ]) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'DueDate' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a>
                            </th>
                            <th>Client Name
                                <a href="{{ route('customer_requirement.index', [
                                    'sort' => 'ClientId', 
                                    'direction' => request('sort') == 'ClientId' && request('direction') == 'asc' ? 'desc' : 'asc'
                                ]) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'ClientId' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a>
                            </th>
                            <th>Application
                                <a href="{{ route('customer_requirement.index', [
                                    'sort' => 'ApplicationId', 
                                    'direction' => request('sort') == 'ApplicationId' && request('direction') == 'asc' ? 'desc' : 'asc'
                                ]) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'ApplicationId' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a>
                            </th>
                            <th>Recommendation
                                <a href="{{ route('customer_requirement.index', [
                                    'sort' => 'Recommendation', 
                                    'direction' => request('sort') == 'Recommendation' && request('direction') == 'asc' ? 'desc' : 'asc'
                                ]) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'Recommendation' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a>
                            </th>
                            <th>Status
                                <a href="{{ route('customer_requirement.index', [
                                    'sort' => 'Status', 
                                    'direction' => request('sort') == 'Status' && request('direction') == 'asc' ? 'desc' : 'asc'
                                ]) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'Status' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a>
                            </th>
                            <th>Progress
                                <a href="{{ route('customer_requirement.index', [
                                    'sort' => 'Progress', 
                                    'direction' => request('sort') == 'Progress' && request('direction') == 'asc' ? 'desc' : 'asc'
                                ]) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'Progress' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customer_requirements as $customerRequirement)
                        <tr>
                            <td>
                                <a href="{{url('view_customer_requirement/'.$customerRequirement->id)}}" class="btn btn-sm btn-info" title="View Customer Requirements">
                                    <i class="ti-eye"></i>
                                </a>
                                @if(auth()->user()->id == $customerRequirement->PrimarySalesPersonId || auth()->user()->user_id == $customerRequirement->PrimarySalesPersonId)
                                <button type="button" class="btn btn-sm btn-warning"
                                    data-target="#editCrr-{{ $customerRequirement->id }}" data-toggle="modal" title='Edit'>
                                    <i class="ti-pencil"></i>
                                </button>  
                                <form method="POST" action="{{url('delete_crr/'.$customerRequirement->id)}}" class="d-inline-block">
                                    @csrf
                                    <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{ $customerRequirement->Id }}" title='Delete Base Price'>
                                        <i class="ti-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                            <td>{{ optional($customerRequirement)->CrrNumber }}</td>
                            <td>{{ $customerRequirement->CreatedDate }}</td>
                            <td>{{ $customerRequirement->DueDate }}</td>
                            <td>{{ optional($customerRequirement->client)->Name }}</td>
                            <td>{{ optional($customerRequirement->product_application)->Name }}</td>
                            <td style="white-space: break-spaces; width: 100%;">{{ $customerRequirement->Recommendation }}</td>
                            <td>
                                @if($customerRequirement->Status == 10)
                                        Open
                                    @elseif($customerRequirement->Status == 30)
                                        Closed
                                    @else
                                        {{ $customerRequirement->Status }}
                                    @endif
                            </td>
                            <td>{{ optional($customerRequirement->progressStatus)->name }}</td>
                            
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {!! $customer_requirements->appends(['search' => $search, 'open' => $open, 'close' => $close])->links() !!}
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
</div>

@include('customer_requirements.create')
@foreach ($customer_requirements as $customerRequirement)
@include('customer_requirements.edit_crr')
@endforeach

<script src="{{ asset('js/sweetalert2.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.natureRequestSelect').select2({
            width: "92%"
        });

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
            $(this).closest('.input-group').remove();
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
    })
</script>
@endsection


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
            
            <div class="mb-3">
                <button class="btn btn-info">Copy</button>

                <form method="GET" action="{{url('customer_requirement_export')}}" class="d-inline-block">

                    <input type="hidden" name="open" value="{{$open}}">
                    <input type="hidden" name="close" value="{{$close}}">
                    
                    <button type="submit" class="btn btn-success">Export</button>
                </form>
            
            </div>

            <form method="GET" class="custom_form mb-3" enctype="multipart/form-data">
                <div class="row height d-flex justify-content-end align-items-end">
                    <div class="col-md-5">
                        <div class="search">
                            <i class="ti ti-search"></i>
                            <input type="text" class="form-control" placeholder="Search Customer Requirement" name="search" value="{{$search}}"> 
                            <button class="btn btn-sm btn-info">Search</button>
                        </div>
                    </div>
                </div>
            </form>
            
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="customer_requirement_table" width="100%">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>CRR #</th>
                            <th>Date Created</th>
                            <th>Due Date</th>
                            <th>Client Name</th>
                            <th>Application</th>
                            <th>Recommendation</th>
                            <th>Status</th>
                            <th>Progress</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( $customer_requirements as $customerRequirement)
                        <tr>
                            <td>
                                <a href="{{url('view_customer_requirement/'.$customerRequirement->id)}}" class="btn btn-sm btn-info" title="View Customer Requirements">
                                    <i class="ti-eye"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-warning"
                                    data-target="#editCrr-{{ $customerRequirement->id }}" data-toggle="modal" title='Edit'>
                                    <i class="ti-pencil"></i>
                                </button>  
                                <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{ $customerRequirement->Id }}" title='Delete Base Price'>
                                    <i class="ti-trash"></i>
                                </button>
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
                            @include('customer_requirements.edit_crr')
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

<script src="{{ asset('js/sweetalert2.min.js') }}"></script>
<script>
    $('.addRow').on('click', function() {
        var newRow = `
            <div class="input-group mb-3">
                <select class="form-control js-example-basic-single" name="NatureOfRequestId[]" required>
                    <option value="" disabled selected>Select Nature of Request</option>
                    @foreach($nature_requests as $nature_request)
                        <option value="{{ $nature_request->id }}">{{ $nature_request->Name }}</option>
                    @endforeach
                </select>
                <div class="input-group-append">
                    <button type="button" class="btn btn-danger removeRow">-</button>
                </div>
            </div>`;

        $('.natureOfRequestContainer').append(newRow);
        $('.js-example-basic-single').select2();
    });

    $(document).on('click', '.removeRow', function() {
        $(this).closest('.input-group').remove();
    });
</script>
@endsection


@extends('layouts.header')
@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card" style="border: 1px solid #337ab7; border-radius:0;">
            @if($status == 10)
            <div class="card-header text-white" style="background-color:#337ab7; font-weight:bold; border-radius:0;">
                Open Transactions
            </div>
            @elseif($status == 30)
            <div class="card-header text-white" style="background-color:#337ab7; font-weight:bold; border-radius:0;">
                Close Transactions
            </div>
            @elseif($status == 50)
            <div class="card-header text-white" style="background-color:#337ab7; font-weight:bold; border-radius:0;">
                Cancelled Transactions
            </div>
            @endif
            <div class="card-body">
                <div class="mb-3">
                    <a href="#" id="copy_btn" class="btn btn-md btn-outline-info">Copy</a>
                    <form method="GET" action="{{url('customer_requirement_export')}}" class="d-inline-block">
                        {{-- <input type="hidden" name="open" value="{{$open}}"> --}}
                        {{-- <input type="hidden" name="close" value="{{$close}}"> --}}
                        <button type="submit" class="btn btn-outline-success">Export</button>
                    </form>
                </div>

                <div class="row mb-3">
                    <div class="col-lg-6">
                        <span>Show</span>
                        <form method="GET" class="d-inline-block" onsubmit="show()">
                            <input type="hidden" name="status" value="{{ request()->get('status', '10') }}">

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
                        <form method="GET" action="{{url()->current()}}" class="custom_form mb-3" enctype="multipart/form-data" onsubmit="show()">
                            <input type="hidden" name="status" value="{{ request()->get('status', '10') }}">

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

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date Created</th>
                                <th>Due Date</th>
                                <th>Client Name</th>
                                <th>Application</th>
                                <th>Analyst</th>
                                <th>Status</th>
                                <th>Progress</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($customer_requirement) > 0)
                            @foreach ($customer_requirement as $crr)
                                <tr>
                                    <td>
                                        <a href="{{url('view_customer_requirement/'.$crr->id.'/'.$crr->CrrNumber)}}">{{$crr->CrrNumber}}</a>
                                    </td>
                                    <td>{{date('Y-m-d', strtotime($crr->DateCreated))}}</td>
                                    <td>{{date('Y-m-d', strtotime($crr->DueDate))}}</td>
                                    <td>{{$crr->client->Name}}</td>
                                    <td>{{$crr->product_application->Name}}</td>
                                    <td>
                                        @foreach ($crr->crrPersonnel as $personnel)
                                            @if($personnel->crrPersonnelByUserId != null)
                                            {{$personnel->crrPersonnelByUserId->full_name}}
                                            @else
                                            {{optional($personnel->crrPersonnelById)->full_name}}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @if($crr->Status == 10)
                                        <span class="badge badge-success">Open</span>
                                        @elseif($crr->Status == 30)
                                        <span class="badge badge-warning">Close</span>
                                        @elseif($crr->Status == 50)
                                        <span class="badge badge-danger">Cancelled</span>
                                        @endif
                                    </td>
                                    <td>{{$crr->progressStatus->name}}</td>
                                </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="8" class="text-center">No data available.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>

                    {{-- {!! $customer_requirement->appends(['status' => $status, 'search' => $search, 'entries' => $entries])->links() !!} --}}
                    {!! $customer_requirement->appends(request()->query()) !!}
                    @php
                        $total = $customer_requirement->total();
                        $currentPage = $customer_requirement->currentPage();
                        $perPage = $customer_requirement->perPage();
        
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


    <script>
        $("[name='entries']").on('change', function() {
            $(this).closest('form').submit()
        })
    </script>
@endsection
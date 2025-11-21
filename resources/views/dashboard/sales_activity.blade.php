@extends('layouts.header')
@section('title', 'Activity - CRMS')
@section('content')
<div class="col-lg-12 grid-stretch-margin">
    <div class="card border border-1 border-primary rounded-0">
        
        <div class="card-header bg-primary">
            <p class="m-0 font-weight-bold text-white">Open Activities</p>
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
                            <th>Schedule Name</th>
                            <th>Client</th>
                            <th>Title</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($activities) > 0)
                            @foreach ($activities as $activity)
                                <tr>
                                    <td>
                                        <a href="{{url('view_activity/'.$activity->id)}}">
                                            {{$activity->ActivityNumber}}
                                        </a>
                                    </td>
                                    <td>{{$activity->ScheduleFrom}}</td>
                                    <td>
                                        <a href="{{url('view_client/'.$activity->client->id)}}">
                                            {{$activity->client->Name}}
                                        </a>
                                    </td>
                                    <td>{{$activity->Title}}</td>
                                    <td>
                                        @if($activity->Status == 10)
                                        <span class="badge badge-success">Open</span>
                                        @else
                                        <span class="badge badge-danger">Closed</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center">No data available.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            {!! $activities->links() !!}
            @php
                $total = $activities->total();
                $currentPage = $activities->currentPage();
                $perPage = $activities->perPage();

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
    document.addEventListener('DOMContentLoaded', function () {
   
        $(".table").tablesorter({
            theme : "bootstrap",
        })
    });
</script>
@endsection
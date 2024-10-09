@extends('layouts.header')
@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title d-flex justify-content-between align-items-center">Logs</h4>
                <div class="row">
                    <div class="col-lg-6">
                    </div>
                    <div class="col-lg-6">
                        <form method="GET" class="custom_form mb-3" enctype="multipart/form-data" onsubmit="show()">
                            <div class="row height d-flex justify-content-end align-items-end">
                                <div class="col-md-8">
                                    <div class="search">
                                        <i class="ti ti-search"></i>
                                        <input type="text" class="form-control" placeholder="Search Logs" name="search" value="{{$search}}"> 
                                        <button class="btn btn-sm btn-info">Search</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="table-resposive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>UserType</th>
                                <th>User</th>
                                <th>Event</th>
                                <th>Model</th>
                                <th>Old Values</th>
                                <th>New Values</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($audits as $audit)
                                <tr>
                                    <td>{{$audit->user_type}}</td>
                                    <td>{{$audit->user->full_name}}</td>
                                    <td>{{$audit->event}}</td>
                                    <td>{{$audit->auditable_type}}</td>
                                    <td title="{{$audit->old_values}}">{{substr($audit->old_values,0,10)}}</td>
                                    <td title="{{$audit->new_values}}">{{substr($audit->new_values,0,10)}}</td>
                                    <td>{{$audit->created_at}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {!! $audits->appends(['search' => $search])->links() !!}
                    @php
                        $total = $audits->total();
                        $currentPage = $audits->currentPage();
                        $perPage = $audits->perPage();
                        
                        $from = ($currentPage - 1) * $perPage + 1;
                        $to = min($currentPage * $perPage, $total);
                    @endphp

                    <p  class="mt-3">{{"Showing {$from} to {$to} of {$total} entries"}}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
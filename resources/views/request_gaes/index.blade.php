@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            Price Request GAE List
            <button type="button" class="btn btn-md btn-primary" data-toggle="modal" data-target="#formRequestGAE">Add Price Request GAE</button>
            </h4>
            <form method="GET" class="custom_form mb-3" enctype="multipart/form-data">
                <div class="row height d-flex justify-content-end align-items-end">
                    <div class="col-md-3">
                        <div class="search">
                            <i class="ti ti-search"></i>
                            <input type="text" class="form-control" placeholder="Search Price Request" name="search" value="{{$search}}"> 
                            <button class="btn btn-sm btn-info">Search</button>
                        </div>
                    </div>
                </div>
            </form>
            <table class="table table-striped table-bordered table-hover" id="request_gae_table" width="100%">
                <thead>
                    <tr>
                        <th width="20%">Action</th>
                        <th width="40%">Expense Name</th>
                        <th width="40%">Cost</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($paymentTerms as $pt)
                        <tr>
                            <td>
                                <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editRequestGae-{{$pt->id}}">
                                    <i class="ti-pencil"></i>
                                </button>

                                <form action="{{url('delete_request_gae/'.$pt->id)}}" class="d-inline-block" method="post">
                                    @csrf

                                    <button type="button" class="btn btn-sm btn-danger deleteBtn">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </form>
                            </td>
                            <td>{{$pt->ExpenseName}}</td>
                            <td>{{$pt->Cost}}</td>
                        </tr>

                        @include('request_gaes.edit_request_gaes')
                    @endforeach
                </tbody>
            </table>

            {!! $paymentTerms->appends(['search' => $search])->links() !!}

            @php
                $total = $paymentTerms->total();
                $currentPage = $paymentTerms->currentPage();
                $perPage = $paymentTerms->perPage();
                
                $from = ($currentPage - 1) * $perPage + 1;
                $to = min($currentPage * $perPage, $total);
            @endphp

            <p  class="mt-3">{{"Showing {$from} to {$to} of {$total} entries"}}</p>
        </div>
    </div>
</div>
<div class="modal fade" id="formRequestGAE" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Price Request GAE</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_request_gae" action="{{url('new_request_gae')}}">
                    @csrf
                    <div class="form-group">
                        <label for="name">Expense Name</label>
                        <input type="text" class="form-control" id="ExpenseName" name="ExpenseName" placeholder="Enter Expense Name" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Cost</label>
                        <input type="text" class="form-control" id="Cost" name="Cost" placeholder="Enter Cost" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" >Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.deleteBtn').on('click', function() {
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
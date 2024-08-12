@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            Fixed Cost List
            <button type="button" class="btn btn-md btn-primary addBtn" data-toggle="modal" data-target="#formFixedCost">Add Fixed Cost</button>
            </h4>
            <form method="GET" class="custom_form mb-3" enctype="multipart/form-data">
                <div class="row height d-flex justify-content-end align-items-end">
                    <div class="col-md-3">
                        <div class="search">
                            <i class="ti ti-search"></i>
                            <input type="text" class="form-control" placeholder="Search Price Fixed Cost" name="search" value="{{$search}}"> 
                            <button class="btn btn-sm btn-info">Search</button>
                        </div>
                    </div>
                </div>
            </form>
            <table class="table table-striped table-bordered table-hover" id="fixed_cost" width="100%">
                <thead>
                    <tr>
                        <th width="15%">Action</th>
                        <th width="20%">Effective Date</th>
                        <th width="20%">Created By</th>
                        <th width="15%">Direct Labor</th>
                        <th width="15%">Factory Overhead</th>
                        <th width="15%">Delivery Cost</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($price_fixed_cost as $pfc)
                        <tr>
                            <td>
                                <button type="button" class="btn btn-sm btn-warning editBtn" title="Edit" data-toggle="modal" data-target="#editPriceRequestFixed-{{$pfc->id}}" data-id="{{$pfc->id}}">
                                    <i class="ti-pencil"></i>
                                </button>
                                <form method="POST" class="d-inline-block" action="{{url('delete_fixed_cost/'.$pfc->id)}}">
                                    @csrf

                                    <button type="button" class="btn btn-sm btn-danger deleteBtn">
                                        <i class="ti-trash"></i>
                                    </button>
                                </form>
                            </td>
                            <td>{{$pfc->EffectiveDate}}</td>
                            <td>
                                @if($pfc->user)
                                {{$pfc->user->full_name}}
                                @endif

                                @if($pfc->userById)
                                {{$pfc->userById->full_name}}
                                @endif
                            </td>
                            <td>{{$pfc->DirectLabor}}</td>
                            <td>{{$pfc->FactoryOverhead}}</td>
                            <td>{{$pfc->DeliveryCost}}</td>
                        </tr>

                        @include('price_request_fixed.edit_price_request_fixed')
                    @endforeach
                </tbody>
            </table>

            {!! $price_fixed_cost->appends(['search' => $search])->links() !!}

            @php
                    $total = $price_fixed_cost->total();
                    $currentPage = $price_fixed_cost->currentPage();
                    $perPage = $price_fixed_cost->perPage();
                    
                    $from = ($currentPage - 1) * $perPage + 1;
                    $to = min($currentPage * $perPage, $total);
                @endphp

                <p  class="mt-3">{{"Showing {$from} to {$to} of {$total} entries"}}</p>
        </div>
    </div>
</div>
<div class="modal fade" id="formFixedCost" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Request Fixed Cost</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_fixed_cost" enctype="multipart/form-data" action="{{ url('new_fixed_cost') }}">
                    @csrf
                    <div class="form-group">
                        <label>Effective Date</label>
                        <input type="date" class="form-control" id="EffectiveDate" name="EffectiveDate" value="{{date('Y-m-d')}}" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Direct Labor</label>
                        <input type="number" class="form-control" id="DirectLabor" name="DirectLabor" step=".01" placeholder="Enter Direct Labor" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Factory Overhead</label>
                        <input type="number" class="form-control" id="FactoryOverhead" name="FactoryOverhead" step=".01" placeholder="Enter Factory Overhead" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Delivery Cost</label>
                        <input type="number" class="form-control" id="DeliveryCost" name="DeliveryCost" step=".01" placeholder="Enter Delivery Cost" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save</button>
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
                    form.submit();
                }
            });
        })

        $('#formFixedCost').on('hidden.bs.modal', function(){
            $('[name="DirectLabor"]').val('')
            $('[name="FactoryOverhead"]').val('')
            $('[name="DeliveryCost"]').val('')
        })

        $('.editBtn').on('click', function() {
            var id = $(this).data('id');

            $.ajax({
                type: "GET", 
                url: "{{url('edit_fixed_cost')}}",
                data: {
                    id: id
                },
                success: function(res) {
                    $('[name="EffectiveDate"]').val(res.EffectiveDate)
                    $('[name="DirectLabor"]').val(res.DirectLabor)
                    $('[name="FactoryOverhead"]').val(res.FactoryOverhead)
                    $('[name="DeliveryCost"]').val(res.DeliveryCost)
                }
            })
            
        })

        $('.addBtn').on('click', function() {
            $('[name="DirectLabor"]').val('')
            $('[name="FactoryOverhead"]').val('')
            $('[name="DeliveryCost"]').val('')
        })
    })
</script>
@endsection 
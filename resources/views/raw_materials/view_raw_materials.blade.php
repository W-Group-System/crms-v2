{{-- <div class="modal fade" id="viewProducts-{{$rm->id}}">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">View Products</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h4><strong>Material: </strong>{{ $rm->Name }}</h4>
                <h4><strong>Description: </strong> {{$rm->Description}}</h4>

                <table class="table-bordered table-hover table-striped table mt-5 view_raw_materials_table">
                    <thead>
                        <tr>
                            <th>Products</th>
                            <th>Percentage (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($rm->productMaterialCompositions) > 0)
                            @foreach ($rm->productMaterialCompositions as $pmc)
                                <tr>
                                    <td>
                                        @if($pmc->products)
                                            <a href="{{url('view_product/'.$pmc->products->id)}}">{{isset($pmc->products)?$pmc->products->code:''}}</a>
                                        @endif
                                    </td>
                                    <td>{{$pmc->Percentage}}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="2">No data available.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div> --}}


@extends('layouts.header')
@section('content')

<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6"> 
                    <h4 class="card-title d-flex justify-content-between align-items-center" style="margin-top: 10px">View Raw Material Details</h4>
                </div>
                <div class="col-lg-6" align="right">
                    <a href="{{ url('raw_material') }}" class="btn btn-md btn-secondary"><i class="icon-arrow-left"></i>&nbsp;Back</a>
                </div>
            </div>
            <form class="form-horizontal" id="form_product" enctype="multipart/form-data">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><b>Code:</b></label>
                    <label class="col-sm-3 col-form-label">{{$raw_materials->Name}}</label>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><b>Description:</b></label>
                    <label class="col-sm-3 col-form-label">{{$raw_materials->Description}}</label>
                </div>
            </form>
            <ul class="nav nav-tabs" id="productTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="products-tab" data-toggle="tab" href="#products" role="tab" aria-controls="products" aria-selected="true">Products</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade active show" id="products" role="tabpanel" aria-labelledby="materials-tab">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover tables">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($raw_materials->productMaterialCompositions as $data)
                                    <tr>
                                        <td>
                                            @if($data->products)
                                                <a href="{{url('view_product/'.$data->products->id)}}">{{isset($data->products)?$data->products->code:''}}</a>
                                            @endif
                                        </td>
                                        <td>{{$data->Percentage}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Region</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 20px">
                <h5 style="margin: 0">Are you sure you want to submit?</h5>
            </div>
            <div class="modal-footer" style="padding: 0.6875rem">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" name="yes_button" id="yes_button" class="btn btn-success">Yes</button>
            </div>
        </div>
    </div>
</div>
<style>
    #form_product {
        padding: 20px 20px;
    }
    .form-group {
        margin-bottom: 0px;
    }
    #productTab .nav-link {
        padding: 15px;
    }
</style>

<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap4.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.bootstrap4.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>

<script>
    $(document).ready(function() {
        
        new DataTable('.tables', {
            destroy: true,
            processing: true,
            pageLength: 10,
            ordering: false,
            layout: {
                topStart: {
                    buttons: [
                        'copy',
                        {
                            extend: 'excel',
                            text: 'Export to Excel',
                        }
                    ]
                }
            }
        });
    });
</script>
@endsection
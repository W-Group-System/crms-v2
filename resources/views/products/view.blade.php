@extends('layouts.header')
@section('content')

<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6"> 
                    <h4 class="card-title d-flex justify-content-between align-items-center" style="margin-top: 10px">View Product Details</h4>
                </div>
                <div class="col-lg-6" align="right">
                    <a href="{{ url('/current_products') }}" class="btn btn-md btn-light"><i class="icon-arrow-left"></i>&nbsp;Back</a>

                    <form method="POST" action="{{url('add_to_archive_products')}}" class="d-inline-block">
                        {{csrf_field()}}

                        <input type="hidden" name="id" value="{{$data->id}}">
                        
                        <button type="submit" class="btn btn-md btn-primary submit_approval" name="action" value="Archive" title="Submit to archive products">Archive</button>
                    </form>
                   
                </div>
            </div>
            @php
                use App\Helpers\Helpers;
                
                $rmc = Helpers::rmc($data->productMaterialComposition, $data->id);
                $identicalComposition = Helpers::identicalComposition($data->productMaterialComposition, $data->id);
                $customerRequirements = Helpers::customerRequirements($data->code);
            @endphp
            <form class="form-horizontal" id="form_product" enctype="multipart/form-data">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><b>DDW Number:</b></label>
                    <label class="col-sm-3 col-form-label">{{ $data->ddw_number }}</label>
                    <label class="offset-sm-2 col-sm-2 col-form-label"><b>Raw Materials Cost:</b></label>
                    <label class="col-sm-2 col-form-label"><strong>USD</strong> {{number_format($rmc, 2)}}</label>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><b>Code:</b></label>
                    <label class="col-sm-3 col-form-label">{{ $data->code }}</label>
                    <label class="offset-sm-2 col-sm-2 col-form-label"><b></b></label>
                    <label class="col-sm-2 col-form-label"><strong>EUR</strong> {{Helpers::usdToEur($rmc)}}</label>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><b>Type:</b></label>
                    <label class="col-sm-3 col-form-label">{{ $data->type == 1 ? 'Pure' : 'Blend' }}</label>
                    <label class="offset-sm-2 col-sm-2 col-form-label"><b></b></label>
                    <label class="col-sm-2 col-form-label"><strong>PHP</strong> {{Helpers::usdToPhp($rmc)}}</label>
                </div>
                <div class="form-group row" style="margin-top: 20px">
                    <label class="col-sm-2 col-form-label"><b>Reference Number:</b></label>
                    <label class="col-sm-3 col-form-label">{{ $data->reference_no }}</label>
                    <label class="offset-sm-2 col-sm-2 col-form-label"><b></b></label>
                    <label class="col-sm-2 col-form-label"></label>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><b>Product Origin:</b></label>
                    <label class="col-sm-3 col-form-label">{{ $data->product_origin }}</label>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><b>Application:</b></label>
                    <label class="col-sm-3 col-form-label">{{ $product_applications ? $product_applications->Name : 'N/A' }}</label>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><b>Subcategory:</b></label>
                    <label class="col-sm-3 col-form-label">{{ $product_subcategories ? $product_subcategories->Name : 'N/A' }}</label>
                </div>
                <div class="form-group row" style="margin-top: 20px">
                    <label class="col-sm-2 col-form-label"><b>Created By:</b></label>
                    <label class="col-sm-3 col-form-label">{{ $userAccounts->full_name }}</label>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><b>Date Created:</b></label>
                    <label class="col-sm-3 col-form-label">{{ $data->created_at->format('Y-m-d') }}</label>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><b>Approved By:</b></label>
                    <label class="col-sm-3 col-form-label">{{ $approveUsers->full_name }}</label>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><b>Date Approved:</b></label>
                    <label class="col-sm-3 col-form-label">{{ $data->date_approved }}</label>
                </div>
                <div class="form-group row" style="margin-top: 20px">
                    <label class="col-sm-2 col-form-label"><b>Status:</b></label>
                    @php
                        $statusLabels = [
                            1 => 'Draft',
                            2 => 'New',
                            4 => 'Current',
                            5 => 'Archived',
                        ];
                    @endphp
                    <label class="col-sm-3 col-form-label"> {{ $statusLabels[$data->status] ?? 'N/A' }}</label>
                </div>
            </form>
            <ul class="nav nav-tabs" id="productTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link" id="materials-tab" data-toggle="tab" href="#materials" role="tab" aria-controls="materials" aria-selected="true">Materials</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="specifications-tab" data-toggle="tab" href="#specifications" role="tab" aria-controls="specifications" aria-selected="false">Specifications</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pds-tab" data-toggle="tab" href="#pds" role="tab" aria-controls="pds" aria-selected="false">PDS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="files-tab" data-toggle="tab" href="#files" role="tab" aria-controls="files" aria-selected="true">Files</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " id="rmc-tab" data-toggle="tab" href="#rmc" role="tab" aria-controls="rmc" aria-selected="false">Historical RMC</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" id="client-tab" data-toggle="tab" href="#client" role="tab" aria-controls="client" aria-selected="false">Client Transaction</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " id="identical-tab" data-toggle="tab" href="#identical" role="tab" aria-controls="identical" aria-selected="false">Identical Composition</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="historycal-tab" data-toggle="tab" href="#historicalLogs" role="tab" aria-controls="historicalLogs" aria-selected="false">Historical Logs</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade " id="materials" role="tabpanel" aria-labelledby="materials-tab">
                    @include('components.error')
                    <form method="POST" action="{{url('update_raw_materials/'.$data->id)}}">
                        {{csrf_field()}}

                        <div class="col-lg-12" align="right">
                            <button type="submit" class="btn btn-md btn-primary submit_approval">Update</button>
                        </div>
    
                        <button type="button" class="btn btn-sm btn-success mb-4" id="addBtn">
                            <i class="ti-plus"></i>
                        </button>

                        <table class="table table-striped table-bordered table-hover" id="material_table" width="100%">
                            <tbody class="tbodyRawMaterials">
                                @foreach ($data->productMaterialComposition as $pmc)
                                    <tr>
                                        <td>
                                            <select name="raw_materials[]" class="form-control js-example-basic-single required" style="width: 100%" required>
                                                <option value="">- Raw Materials -</option>
                                                @foreach ($rawMaterials as $rm)
                                                    <option value="{{$rm->id}}" @if($pmc->MaterialId == $rm->id) selected @endif>{{$rm->Name}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="percent[]" id="percent" class="form-control" placeholder="%" value="{{$pmc->Percentage}}" max="100" required>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-danger removeRawMat" type="button">
                                                <i class="ti-minus"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </form>
                </div>
                <div class="tab-pane fade" id="specifications" role="tabpanel" aria-labelledby="specifications-tab">
                    @include('components.error')
                    <div class="col-lg-12" align="right">
                        <button type="button" class="btn btn-md btn-primary submit_approval mb-2" data-toggle="modal" data-target="#specification">Add</button>
                        <button class="btn btn-warning btn-md mb-2" type="button" data-toggle="modal" data-target="#updateAll" title="Update All">
                            Update All
                        </button>
                    </div>
                    @include('products.add_specification')

                    @include('products.edit_all_product_specification')
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover tables" id="specification_table" width="100%">
                            <thead>
                                <tr>
                                    <th>Parameter</th>
                                    <th>Specification</th>
                                    <th>Testing Condition</th>
                                    <th>Remarks</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($data->productSpecification)
                                    @foreach ($data->productSpecification as $ps)
                                        <tr>
                                            <td>{{$ps->Parameter}}</td>
                                            <td>{{$ps->Specification}}</td>
                                            <td>{{$ps->TestingCondition}}</td>
                                            <td>{{$ps->Remarks}}</td>
                                            <td>
                                                <button class="btn btn-sm btn-warning" type="button" data-toggle="modal" data-target="#specification-{{$ps->Id}}" title="Update">
                                                    <i class="ti-pencil"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>

                    @foreach ($data->productSpecification as $ps)
                        @include('products.edit_specification')
                    @endforeach
                </div>
                <div class="tab-pane fade" id="pds" role="tabpanel" aria-labelledby="pds-tab">
                    <div class="col-lg-12" align="right">
                        <button type="button" class="btn btn-md btn-primary submit_approval mb-2" data-toggle="modal" data-target="#pdsModal">Add</button>
                    </div>
                    @include('products.add_pds')
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover tables" id="specification_table" width="100%">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Control Number</th>
                                    <th>Company</th>
                                    <th>Date Issued</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($data->productDataSheet)
                                    <tr>
                                        <td>{{$data->code}}</td>
                                        <td>{{$data->productDataSheet->ControlNumber}}</td>
                                        <td>@if($data->productDataSheet->clients){{$data->productDataSheet->clients->Name}}@endif</td>
                                        <td>{{date('M d, Y', strtotime($data->productDataSheet->DateIssued))}}</td>
                                        <td>
                                            <button class="btn btn-sm btn-warning" type="button" data-toggle="modal" data-target="#pdsModal-{{$data->productDataSheet->Id}}">
                                                <i class="ti-pencil"></i>
                                            </button>
                                            <a href="{{url('view_details/'.$data->productDataSheet->Id)}}" class="btn btn-info btn-sm" title="View Details" target="_blank">
                                                <i class="ti-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    @if($data->productDataSheet)
                        @include('products.edit_pds')
                    @endif
                </div>
                <div class="tab-pane fade" id="files" role="tabpanel" aria-labelledby="files-tab">
                    <div class="col-lg-12" align="right">
                        <button type="button" class="btn btn-md btn-primary submit_approval mb-2" data-toggle="modal" data-target="#file">Add</button>
                        <button type="button" class="btn btn-md btn-warning submit_approval mb-2" data-toggle="modal" data-target="#updateAllFiles">Update All</button>
                    </div>
                    @include('products.add_file')
                    @include('products.edit_all_product_files')
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover tables" id="specification_table" width="100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Client</th>
                                    <th>File</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                @if($data->productFiles)
                                    @foreach ($data->productFiles as $pf)
                                        <tr>
                                            <td>{{$pf->Name}}</td>
                                            <td>
                                                @if($pf->IsConfidential == 0)
                                                    {{$pf->Description}}
                                                @endif
                                            </td>
                                            <td>
                                                @if($pf->IsConfidential == 0)
                                                    @if($pf->client)
                                                        {{$pf->client->Name}}
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                @if($pf->IsConfidential == 0)
                                                <a href="{{$pf->Path}}" class="btn btn-sm btn-info" target="_blank">
                                                    <i class="ti-eye"></i>
                                                </a>
                                                @elseif($pf->IsConfidential == 1)
                                                <a href="{{$pf->Path}}" class="btn btn-sm btn-info" target="_blank">
                                                    <i class="mdi mdi-eye-off-outline"></i>
                                                </a>
                                                @endif
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-warning" type="button" data-toggle="modal" data-target="#file-{{$pf->Id}}">
                                                    <i class="ti-pencil"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>

                    @foreach ($data->productFiles as $pf)
                        @include('products.edit_file')
                    @endforeach
                </div>
                <div class="tab-pane fade " id="rmc" role="tabpanel" aria-labelledby="rmc-tab">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered table-striped tables" width="100%">
                            <thead>
                                <tr>
                                    <th>Effective Date</th>
                                    <th>RMC (USD)</th>
                                    <th>RMC (EUR)</th>
                                    <th>RMC (PHP)</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade active show" id="client" role="tabpanel" aria-labelledby="client-tab">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped tables">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Transaction</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($customerRequirements)
                                    @foreach ($customerRequirements as $cr)
                                        <tr>
                                            <td>Customer Requirement</td>
                                            <td>{{$cr->CrrNumber}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if($data->productRps)
                                    @foreach ($data->productRps as $rps)
                                        <tr>
                                            <td>Request Product Evaluation</td>
                                            <td>{{$rps->RpeNumber}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if($data->sampleRequestProduct)
                                    @foreach ($data->sampleRequestProduct as $item)
                                        <tr>
                                            <td>Sample Request</td>
                                            <td>
                                                <a href="{{url('samplerequest/view/'.$item->Id)}}" target="_blank">
                                                    {{$item->sampleRequest->SrfNumber}}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade " id="identical" role="tabpanel" aria-labelledby="identical-tab">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover tables" width="100%">
                            <thead>
                                <tr>
                                    <th>DDW Number</th>
                                    <th>Code</th>
                                    <th>Created By</th>
                                    <th>Date Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($identicalComposition as $ic)
                                    <tr>
                                        <td>{{$ic->products->ddw_number}}</td>
                                        <td>
                                            @if($ic->products->status == 1)
                                                <a href="{{url('view_draft_product/'.$ic->products->id)}}">{{$ic->products->code}}</a>
                                            @elseif($ic->products->status == 2)
                                                <a href="{{url('view_new_product/'.$ic->products->id)}}">{{$ic->products->code}}</a>
                                            @elseif($ic->products->status == 4)
                                                <a href="{{url('view_product/'.$ic->products->id)}}">{{$ic->products->code}}</a>
                                            @elseif($ic->products->status == 5)
                                                <a href="{{url('view_archive_products/'.$ic->products->id)}}">{{$ic->products->code}}</a>
                                            @endif
                                        </td>
                                        <td>
                                            @if($ic->products->userByUserId)
                                                {{$ic->products->userByUserId->full_name}}
                                            @else
                                                {{$ic->products->userById->full_name}}
                                            @endif
                                        </td>
                                        <td>{{date('M d, Y', strtotime($ic->products->created_at))}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="historicalLogs" role="tabpanel" aria-labelledby="identical-tab">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered tables" width="100%">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Name</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data->productEventLogs as $logs)
                                    <tr>
                                        <td>{{date('M d Y', strtotime($logs->TimeStamp))}}</td>
                                        <td>{{$logs->userByUserId->full_name}}</td>
                                        <td>{{$logs->Details}}</td>
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
            ordering: false
        });

        $("#addBtn").on('click', function() {
            
            var newRow = `
                <tr>
                    <td>
                        <select name="raw_materials[]" class="form-control js-example-basic-single required" style="width: 100%" required>
                            <option value="">- Raw Materials -</option>
                            @foreach ($rawMaterials as $rm)
                                <option value="{{$rm->id}}">{{$rm->Name}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" name="percent[]" id="percent" class="form-control" placeholder="%" max="100" required>
                    </td>
                    <td>
                        <button class="btn btn-danger btn-sm removeRawMat" type="button">
                            <i class="ti-minus"></i>
                        </button>
                    </td>
                </tr>
            `;
            
            var row = $(newRow);
            $(".tbodyRawMaterials").append(row);
            row.find('.js-example-basic-single').select2();

        });

        $(document).on('click', '.removeRawMat', function()
        {
            // $('.tbodyRawMaterials').children().last().remove();
            $(this).closest('tr').remove()
        })

        $(".addPotentialBenefit").on('click', function() {

            var newRow = `
                <div class="row">
                    <div class="col-lg-10">
                        <input type="text" name="potentialBenefit[]" class="form-control form-control-sm mb-2">
                    </div>
                    <div class="col-lg-2">
                        <button class="btn btn-sm btn-danger removePotentialBenefit" type="button">
                            <i class="ti-minus"></i>
                        </button>
                    </div>
                </div>
            `

            $('.potentialBenefitContainer').append(newRow);
        })

        $(document).on('click', '.removePotentialBenefit', function()
        {
            // $('.potentialBenefitContainer').children().last().remove();
            $(this).closest('.row').remove();
        })

        $(".addPca").on('click', function() {

            var newRow = `
                <div class="row">
                    <div class="col-lg-3">
                        <input type="text" name="pcaParameter[]" placeholder="Enter parameter" class="form-control form-control-sm mb-2" required>
                    </div>
                    <div class="col-lg-3">
                        <input type="text" name="pcaValue[]" placeholder="Enter value" class="form-control form-control-sm mb-2" required>
                    </div>
                    <div class="col-lg-3">
                        <input type="text" name="pcaRemark[]" placeholder="Enter remark" class="form-control form-control-sm mb-2" required>
                    </div>
                    <div class="col-lg-3">
                        <button class="btn btn-sm btn-danger removePca" type="button">
                            <i class="ti-minus"></i>
                        </button>
                    </div>
                </div>
            `

            $('.pcaContainer').append(newRow);
        })

        $(document).on('click', '.removePca', function()
        {
            // $('.pcaContainer').children().last().remove();
            $(this).closest('.row').remove();
        })

        $(".addMa").on('click', function() {

            var newRow = `
                <div class="row">
                    <div class="col-lg-3">
                        <input type="text" name="maParameter[]" placeholder="Enter parameter" class="form-control form-control-sm mb-2" required>
                    </div>
                    <div class="col-lg-3">
                        <input type="text" name="maValue[]" placeholder="Enter value" class="form-control form-control-sm mb-2" required>
                    </div>
                    <div class="col-lg-3">
                        <input type="text" name="maRemark[]" placeholder="Enter remark" class="form-control form-control-sm mb-2" required>
                    </div>
                    <div class="col-lg-3">
                        <button class="btn btn-sm btn-danger removeMa" type="button">
                            <i class="ti-minus"></i>
                        </button>
                    </div>
                </div>
            `

            $('.maContainer').append(newRow);
        })

        $(document).on('click', '.removeMa', function()
        {
            // $('.maContainer').children().last().remove();
            $(this).closest('.row').remove();
        })

        $(".addHeavyMetals").on('click', function() {

            var newRow = `
                <div class="row">
                    <div class="col-lg-4">
                        <input type="text" name="heavyMetalsParameter[]" placeholder="Enter parameter" class="form-control form-control-sm mb-2" required>
                    </div>
                    <div class="col-lg-4">
                        <input type="text" name="heavyMetalsValue[]" placeholder="Enter value" class="form-control form-control-sm mb-2" required>
                    </div>
                    <div class="col-lg-4">
                        <button class="btn btn-sm btn-danger removeHeavyMetals" type="button">
                            <i class="ti-minus"></i>
                        </button>
                    </div>
                </div>
            `

            $('.heavyMetalsContainer').append(newRow);
        })

        $(document).on('click', '.removeHeavyMetals', function()
        {
            // $('.heavyMetalsContainer').children().last().remove();
            $(this).closest('.row').remove();
        })
        
        $(".addNutritionalInfo").on('click', function() {

            var newRow = `
                <div class="row">
                    <div class="col-lg-4">
                        <input type="text" name="nutrionalInfoParameter[]" placeholder="Enter parameter" class="form-control form-control-sm mb-2" required>
                    </div>
                    <div class="col-lg-4">
                        <input type="text" name="nutrionalInfoValue[]" placeholder="Enter value" class="form-control form-control-sm mb-2" required>
                    </div>
                    <div class="col-lg-4">
                        <button class="btn btn-sm btn-danger removeNutritionalInfo" type="button">
                            <i class="ti-minus"></i>
                        </button>
                    </div>
                </div>
            `

            $('.nutrionalInfoContainer').append(newRow);
        })

        $(document).on('click', '.removeNutritionalInfo', function()
        {
            // $('.nutrionalInfoContainer').children().last().remove();
            $(this).closest('.row').remove();
        })

        $(".addAllergens").on('click', function() 
        {
            var newRow = `
                <div class="row">
                    <div class="col-lg-4">
                        <input type="text" name="allergensParameter[]" placeholder="Enter parameter" class="form-control form-control-sm mb-2" required>
                    </div>
                    <div class="col-lg-4">
                        <input type="checkbox" name="isAllergen[]" class="form-control form-control-sm">
                    </div>
                    <div class="col-lg-4">
                        <button class="btn btn-sm btn-danger removeAllergens" type="button">
                            <i class="ti-minus"></i>
                        </button>
                    </div>
                </div>
            `

            $('.allergensContainer').append(newRow);
        })

        $(document).on('click', '.removeAllergens', function()
        {
            // $('.allergensContainer').children().last().remove();
            $(this).closest('.row').remove()
        })

        $(".addBtnSpecification").on('click', function()
        {
            var newRow = `
                <fieldset class="border border-primary p-3 mb-3">
                    <div class="row">
                        <div class="col-lg-6">
                            <label>Parameter :</label>
                            <input type="text" name="parameter[]" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-lg-6">
                            <label>Testing Condition :</label>
                            <input type="text" name="testing_condition[]" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-lg-6">
                            <label>Specification :</label>
                            <input type="text" name="specification[]" class="form-control form-control-sm" required> 
                        </div>
                        <div class="col-lg-6">
                            <label>Remarks :</label>
                            <input type="text" name="remarks[]" class="form-control form-control-sm">
                        </div>
                    </div>
                </fieldset>
            `

            $('.specification-container').append(newRow)
        })

        $("#removeBtnSpecification").on('click', function()
        {
            $('.specification-container').children().last().remove();
            
        })

        $('input[type="file"]').on('change', function(e) {
            var filename = e.target.files[0].name;

            $("#filename").val(filename);
        })

        $(".addBtnFiles").on('click', function()
        {
            var newRow = `
                <fieldset class="border border-primary p-3 mb-3">
                    <div class="row">
                        <div class="col-lg-6">
                            <label>Name :</label>
                            <input type="text" name="name[]" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-lg-6">
                            <label>Client :</label>
                            <select name="client[]" class="js-example-basic-single form-control form-control-sm" required>
                                <option value="">-Client-</option>
                                @foreach ($client as $c)
                                    <option value="{{$c->id}}">{{$c->Name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label>Description :</label>
                            <input type="text" name="description[]" class="form-control form-control-sm" required> 
                        </div>
                        <div class="col-lg-6">
                            <label>Is Confidential :</label>
                            <input type="checkbox" name="is_confidential[]"> 
                        </div>
                        <div class="col-lg-6">
                            <label>File :</label>
                            <input type="file" name="files[]" id="file" class="form-control form-control-sm" required>
                            <input type="hidden" name="files[]">
                        </div>
                    </div>
                </fieldset>
            `

            var row = $(newRow);
            $('.product_files_container').append(row)
            row.find('.js-example-basic-single').select2();
        })

        $(".removeBtnFiles").on('click', function()
        {
            $('.product_files_container').children().last().remove();
            
        })
    });
</script>
@endsection
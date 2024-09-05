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
                    <a href="{{ url('/draft_products') }}" class="btn btn-md btn-light"><i class="icon-arrow-left"></i>&nbsp;Back</a>

                    {{-- <form method="POST" class="d-inline-block" id="archiveForm">
                        {{csrf_field()}}

                        <input type="hidden" name="id" value="{{$data->id}}">
                        <button type="submit" class="btn btn-md btn-secondary submit_approval" name="action" value="New" title="Submit to archive products">Archive</button>
                    </form> --}}

                    <form method="POST" action="{{url('/add_to_new_products')}}" class="d-inline-block">
                        {{csrf_field()}}

                        <input type="hidden" name="id" value="{{$data->id}}">
                        <button type="button" class="btn btn-md btn-primary" id="moveToNew" name="action" value="New">Move to New</button>
                    </form>
                </div>
            </div>
            @php
                $rmc = rmc($data->productMaterialComposition, $data->id);
                $identicalComposition = identicalComposition($data->productMaterialComposition, $data->id);
                $customerRequirements = customerRequirements($data->code);
                $productRps = productRps($data->code);
            @endphp
            <div class="row">
                <div class="col-md-2">
                    <p class="mb-0"><b>DDW Number:</b></p>
                </div>
                <div class="col-md-3">
                    @if($data->ddw_number != null)
                    <p class="mb-0">{{$data->ddw_number}}</p>
                    @else
                    <p>N/A</p>
                    @endif
                </div>
                <div class="col-md-2">
                    <p class="mb-0"><b>Raw Materials Cost:</b></p>
                </div>
                <div class="col-md-3">
                    <p class="mb-0"><strong>USD</strong> {{number_format($rmc, 2)}}</p>
                    <p class="mb-0"><strong>EUR</strong> {{usdToEur($rmc)}}</p>
                    <p class="mb-0"><strong>PHP</strong> {{usdToPhp($rmc)}}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <p class="mb-0"><b>Code:</b></p>
                </div>
                <div class="col-md-3">
                    <p class="mb-0">{{ $data->code }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <p class="mb-0"><b>Type:</b></p>
                </div>
                <div class="col-md-3">
                    @if($data->type == 1)
                    <p class="mb-0">Pure</p>
                    @else
                    <p class="mb-0">Blend</p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <p class="mb-0"><b>Reference Number:</b></p>
                </div>
                <div class="col-md-3">
                    <p class="mb-0">{{ $data->reference_no }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <p class="mb-0"><b>Product Origin:</b></p>
                </div>
                <div class="col-md-3">
                    <p class="mb-0">{{ $data->product_origin }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <p class="mb-0"><b>Application:</b></p>
                </div>
                <div class="col-md-3">
                    <p class="mb-0">{{ $product_applications ? $product_applications->Name : 'N/A' }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <p class="mb-0"><b>Subcategory:</b></p>
                </div>
                <div class="col-md-3">
                    <p class="mb-0">{{ $product_subcategories ? $product_subcategories->Name : 'N/A' }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"><p class="mb-0"><b>Created By:</b></p></div>
                <div class="col-md-3"><p class="mb-0">{{ $userAccounts->full_name }}</p></div>
            </div>
            <div class="row">
                <div class="col-sm-2 col-form-label"><p class="mb-0"><b>Date Created:</b></p></div>
                <div class="col-sm-3 col-form-label"><p class="mb-0">{{ $data->created_at->format('Y-m-d') }}</p></div>
            </div>
            <div class="row">
                <div class="col-md-2"><p class="mb-0"><b>Approved By:</b></p></div>
                <div class="col-md-3"><p class="mb-0">{{ $approveUsers->full_name ?? '' }}</p></div>
            </div>
            <div class="row">
                <div class="col-md-2 col-form-label"><p class="mb-0"><b>Date Approved:</b></p></div>
                <div class="col-md-3 col-form-label"><p class="mb-0">{{ $data->date_approved != null ? $data->date_approved : 'N/A' }}</p></div>
            </div>
            <div class="row mb-5">
                <div class="col-md-2"><p class="mb-0"><b>Status:</b></p></div>
                @php
                    $statusLabels = [
                        1 => 'Draft',
                        2 => 'New',
                        4 => 'Current',
                        5 => 'Archived',
                    ];
                @endphp
                <div class="col-md-3"><p class="mb-0"> {{ $statusLabels[$data->status] ?? 'N/A' }}</p></div>
            </div>
            <ul class="nav nav-tabs" id="productTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active p-2" id="materials-tab" data-toggle="tab" href="#materials" role="tab" aria-controls="materials" aria-selected="true">Materials</a>
                </li>
                {{-- <li class="nav-item">
                    <a class="nav-link" id="specifications-tab" data-toggle="tab" href="#specifications" role="tab" aria-controls="specifications" aria-selected="false">Specifications</a>
                </li> --}}
                <li class="nav-item">
                    <a class="nav-link p-2" id="pds-tab" data-toggle="tab" href="#pds" role="tab" aria-controls="pds" aria-selected="false">PDS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link p-2" id="files-tab" data-toggle="tab" href="#files" role="tab" aria-controls="files" aria-selected="true">Files</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link p-2" id="rmc-tab" data-toggle="tab" href="#rmc" role="tab" aria-controls="rmc" aria-selected="false">Historical RMC</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link p-2" id="client-tab" data-toggle="tab" href="#client" role="tab" aria-controls="client" aria-selected="false">Client Transaction</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link p-2" id="identical-tab" data-toggle="tab" href="#identical" role="tab" aria-controls="identical" aria-selected="false">Identical Composition</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link p-2" id="historycal-tab" data-toggle="tab" href="#historicalLogs" role="tab" aria-controls="historicalLogs" aria-selected="false">Historical Logs</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade active show" id="materials" role="tabpanel" aria-labelledby="materials-tab">
                    @include('components.error')
                    <div class="col-lg-12" align="right">
                        <button type="submit" class="btn btn-md btn-primary submit_approval mb-3" data-toggle="modal" data-target="#rawMaterials{{$data->id}}">Update</button>
                    </div>
    
                    {{-- <button type="button" class="btn btn-sm btn-success mb-4" id="addBtn">
                        <i class="ti-plus"></i>
                    </button>

                    <table class="table table-striped table-bordered table-hoverr" id="material_table" width="100%">
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
                    </table> --}}

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover tables" width="100%">
                            <thead>
                                <tr>
                                    <th>Materials</th>
                                    <th>Percentage</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($data->productMaterialComposition as $pmc)
                                <tr>
                                    <td>
                                        {{$pmc->rawMaterials->Name}}
                                    </td>
                                    <td>
                                        {{$pmc->Percentage}}%
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- <div class="tab-pane fade" id="specifications" role="tabpanel" aria-labelledby="specifications-tab">
                    @include('components.error')
                    <div class="col-lg-12" align="right">
                        <button type="button" class="btn btn-md btn-primary submit_approval mb-2" data-toggle="modal" data-target="#specification">New</button>
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
                                                <form action="{{url('delete_specification/'.$ps->Id)}}" method="post" id="productSpecificationForm" class="d-inline-block productSpecificationForm" title="Delete">
                                                    {{csrf_field()}}
                                                    <button type="button" class="btn btn-sm btn-danger deleteProductSpecification" title="Delete">
                                                        <i class="ti-trash"></i>
                                                    </button>
                                                </form>
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
                </div> --}}
                <div class="tab-pane fade" id="pds" role="tabpanel" aria-labelledby="pds-tab">
                    <div class="col-lg-12" align="right">
                        <button type="button" class="btn btn-md btn-primary submit_approval mb-2" data-toggle="modal" data-target="#pdsModal">New</button>
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
                                            <form action="{{url('delete_pds/'.$data->productDataSheet->Id)}}" method="post" class="d-inline-block" title="Delete">
                                                {{csrf_field()}}

                                                <button type="button" class="btn btn-sm btn-danger deletePds" title="Delete">
                                                    <i class="ti-trash"></i>
                                                </button>
                                            </form>
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
                        <button type="button" class="btn btn-md btn-primary submit_approval mb-2" data-toggle="modal" data-target="#file">New</button>
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
                                                {{$pf->Description}}
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
                                                <a href="{{url($pf->Path)}}" class="btn btn-sm btn-info" target="_blank">
                                                    <i class="ti-eye"></i>
                                                </a>
                                                @elseif($pf->IsConfidential == 1)
                                                <a href="{{url($pf->Path)}}" class="btn btn-sm btn-info" target="_blank">
                                                    <i class="mdi mdi-eye-off-outline"></i>
                                                </a>
                                                @endif
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-warning" type="button" data-toggle="modal" data-target="#file-{{$pf->Id}}">
                                                    <i class="ti-pencil"></i>
                                                </button>
                                                <form action="{{url('delete_product_files/'.$pf->Id)}}" method="post" class="d-inline-block" title="Delete">
                                                    {{csrf_field()}}
    
                                                    <button type="button" class="btn btn-sm btn-danger deleteProductFiles" title="Delete">
                                                        <i class="ti-trash"></i>
                                                    </button>
                                                </form>
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
                                <tbody>
                                </tbody>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="client" role="tabpanel" aria-labelledby="client-tab">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped tables" width="100%">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Transaction</th>
                                    <th>Disposition Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($customerRequirements)
                                    @foreach ($customerRequirements as $cr)
                                        <tr>
                                            <td>Customer Requirement</td>
                                            <td>
                                                <a href="{{url('view_customer_requirement/'.$cr->id)}}" target="_blank">
                                                    {{$cr->CrrNumber}}
                                                </a>
                                            </td>
                                            <td>N/A</td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if($productRps)
                                    @foreach ($productRps as $rps)
                                        <tr>
                                            <td>Request Product Evaluation</td>
                                            <td>
                                                <a href="{{url('product_evaluation/view/'.$rps->id)}}" target="_blank">
                                                    {{$rps->RpeNumber}}
                                                </a>
                                            </td>
                                            <td>N/A</td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if($data->sampleRequestProduct)
                                    @foreach ($data->sampleRequestProduct as $item)
                                        <tr>
                                            <td>Sample Request</td>
                                            <td>
                                                <a href="{{url('samplerequest/view/'.$item->Id)}}" target="_blank">
                                                    {{optional($item->sampleRequest)->SrfNumber}}
                                                </a>
                                            </td>
                                            <td>
                                                @if($item->DispositionRejectionDescription == null)
                                                N/A
                                                @else
                                                {{$item->DispositionRejectionDescription}}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="identical" role="tabpanel" aria-labelledby="identical-tab">
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
                                        <td>@if($ic->products){{$ic->products->ddw_number}}@endif</td>
                                        <td>
                                            @if($ic->products)
                                                @if(($ic->products)->status == 1)
                                                    <a href="{{url('view_draft_product/'.$ic->products->id)}}">{{$ic->products->code}}</a>
                                                @elseif($ic->products->status == 2)
                                                    <a href="{{url('view_new_product/'.$ic->products->id)}}">{{$ic->products->code}}</a>
                                                @elseif($ic->products->status == 4)
                                                    <a href="{{url('view_product/'.$ic->products->id)}}">{{$ic->products->code}}</a>
                                                @elseif($ic->products->status == 5)
                                                    <a href="{{url('view_archive_products/'.$ic->products->id)}}">{{$ic->products->code}}</a>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if($ic->products)
                                                @if($ic->products->userByUserId)
                                                    {{$ic->products->userByUserId->full_name}} 
                                                @else
                                                    {{$ic->products->userById->full_name}}
                                                @endif
                                            @endif
                                        </td>
                                        <td>{{date('M d, Y', strtotime(optional($ic->products)->created_at))}}</td>
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
                                @if(count($data->productEventLogs) > 0)
                                    @foreach ($data->productEventLogs as $logs)
                                        <tr>
                                            <td>{{date('M d Y', strtotime($logs->TimeStamp))}}</td>
                                            <td>{{optional($logs->userByUserId)->full_name}}</td>
                                            <td>{{$logs->Details}}</td>
                                        </tr>
                                    @endforeach
                                @endif
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

<div class="modal fade" id="rawMaterials{{$data->id}}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Edit Raw Materials</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{url('update_raw_materials/'.$data->id)}}" id="materialsForm">
                {{csrf_field()}}
                
                <div class="modal-body">
                    <div class="card border border-1 border-primary rounded-0 rounded-bottom" style="height: 70vh; overflow-y:auto;">
                        <div class="card-header bg-primary text-white">
                            Raw Materials Percentage
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <span id="totalPercentage">{{$percentage ?? 0.00}}</span>
        
                                <table class="table table-striped table-bordered table-hover" id="rawMaterialsTable">
                                    <thead>
                                        <tr>
                                            <th>Materials</th>
                                            <th>Percentage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $percentage = 0;
                                        @endphp
        
                                        @foreach ($rawMaterials as $rm)
                                        <tr>
                                            <td>{{$rm->Name}}</td>
                                            <td>
                                                <input type="hidden" name="raw_materials[]" value="{{$rm->id}}">
        
                                                @php
                                                    $composition_found = false;
                                                @endphp
        
                                                @foreach ($data->productMaterialComposition as $rawMats)
                                                    @if($rawMats->MaterialId == $rm->id)
                                                        <input type="number" name="percentage[]" class="form-control percentageVal" value="{{$rawMats->Percentage}}">
                                                        @php
                                                            $composition_found = true;
                                                            $percentage += $rawMats->Percentage;
                                                        @endphp
                                                        @break
                                                    @endif
                                                @endforeach
        
                                                @if(!$composition_found)
                                                <input type="number" name="percentage[]" class="form-control percentageVal">
                                                @endif
        
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
        
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer" style="padding: 0.6875rem">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap4.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.bootstrap4.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
<script>
    $(document).ready(function() {
        var percentage = {!! json_encode($percentage) !!}
        document.getElementById('totalPercentage').innerText = percentage

        new DataTable('.tables', {
            destroy: false,
            processing: true,
            pageLength: 10,
            ordering: false
        });

        var rawMatsTable = $("#rawMaterialsTable").DataTable({
            destroy: false,
            processing: true,
            ordering: false,
            paginate: false
        })

        $(".percentageVal").on('change', function() {
            var total = 0;
            
            $('.percentageVal').each(function() {
                var value = $(this).val();
                value = parseFloat(value) || 0;
                total += value;
            })
            
            $("#totalPercentage").text(total)
        })

        $("#materialsForm").on('submit', function(e) {
            e.preventDefault()

            var formData = $(this).serializeArray()
            var totalPercentage = parseFloat($('#totalPercentage').text())
            var message = "";
            var action = $(this).attr('action')

            if(totalPercentage > 100)
            {
                message = "Error because its above 100 percent"
            }

            if (totalPercentage < 100)
            {
                message = "Error because its less than 100 percent"
            }

            if (totalPercentage > 100 || totalPercentage < 100)
            {
                Swal.fire({
                    icon: "error",
                    title: message
                })
            }
            else
            {
                $.ajax({
                    type: "POST",
                    url: action,
                    data: formData,
                    success: function()
                    {
                        Swal.fire({
                            icon: "success",
                            title: "Successfully Saved",
                        })

                        location.reload()
                    }
                })
            }

        })

        // $("#addBtn").on('click', function() {
            
        //     var newRow = `
        //         <tr>
        //             <td>
        //                 <select name="raw_materials[]" class="form-control js-example-basic-single required" style="width: 100%" required>
        //                     <option value="">- Raw Materials -</option>
        //                     @foreach ($rawMaterials as $rm)
        //                         <option value="{{$rm->id}}">{{$rm->Name}}</option>
        //                     @endforeach
        //                 </select>
        //             </td>
        //             <td>
        //                 <input type="number" name="percent[]" id="percent" class="form-control" placeholder="%" max="100" required>
        //             </td>
        //             <td>
        //                 <button class="btn btn-danger btn-sm removeRawMat" type="button">
        //                     <i class="ti-minus"></i>
        //                 </button>
        //             </td>
        //         </tr>
        //     `;
            
        //     var row = $(newRow);
        //     $(".tbodyRawMaterials").append(row);
        //     row.find('.js-example-basic-single').select2();

        // });

        // $(document).on('click', '.removeRawMat', function()
        // {
        //     // $('.tbodyRawMaterials').children().last().remove();
        //     $(this).closest('tr').remove()
        // })

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
        
        $(document).on('change', '[name="files[]"]', function(e) {
            var filename = e.target.files[0].name;

            $(this).closest('.row').find('[name="name[]"]').val(filename);
        })

        $(".addBtnFiles").on('click', function()
        {
            var newRow = `
                <div class="row">
                    <div class="col-lg-10">
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
                                    <textarea name="description[]" class="form-control" cols="30" rows="10"></textarea>
                                </div>
                                <div class="col-lg-6">
                                    <label>Is Confidential :</label>
                                    <input type="checkbox" name="is_confidential[]"> 
                                </div>
                                <div class="col-lg-6">
                                    <label>File :</label>
                                    <input type="file" name="files[]" id="file" class="form-control form-control-sm" >
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-lg-2">
                        <button class="btn btn-sm btn-danger mb-3 removeBtnFiles" type="button" >
                            <i class="ti-minus"></i>
                        </button>
                    </div>
                </div>
            `

            var row = $(newRow);
            $('.product_files_container').append(row)
            row.find('.js-example-basic-single').select2();
        })

        $(document).on('click', '.removeBtnFiles', function()
        {
            $(this).closest('.row').remove();
        })

        $("#archiveForm").on('submit', function(e) {
            e.preventDefault();

            var formData = $(this).serializeArray();

            Swal.fire({
                title: "Are you sure you want to archive?",
                text: "You won't be able to undo this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#28a745",
                cancelButtonColor: "#dc3545",
                confirmButtonText: "Yes, archive it!"
                }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "{{url('add_to_archive_products')}}",
                        data: formData,
                        success: function(res) 
                        {
                            Swal.fire({
                                title: "Moved!",
                                text: "The product has been moved to archived",
                                icon: "success"
                            }).then((result) => {
                                window.location.href = "{{url('archived_products')}}"
                            });
                        }
                    })
                }
            });
        })

        $('.deleteProductSpecification').on('click', function() {

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

        $('.deletePds').on('click', function() {

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

        $('.deleteProductFiles').on('click', function() {

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

        $('#moveToNew').on('click', function() {
            var form = $(this).closest('form');

            Swal.fire({
                title: "Are you sure?",
                // text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Move to New"
                }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        })
    });
</script>
@endsection
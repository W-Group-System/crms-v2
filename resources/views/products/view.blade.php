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
                    <button type="button" class="btn btn-md btn-primary submit_approval" name="submit_approval" id="">Submit</button>
                </div>
            </div>
            <form class="form-horizontal" id="form_product" enctype="multipart/form-data">
                {{-- <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><b>DDW Number:</b></label>
                    <label class="col-sm-3 col-form-label">{{ $data->ddw_number }}</label>
                    <label class="offset-sm-2 col-sm-2 col-form-label"><b>Raw Materials:</b></label>
                    <label class="col-sm-3 col-form-label"></label>
                </div> --}}
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><b>Code:</b></label>
                    <label class="col-sm-3 col-form-label">{{ $data->code }}</label>
                    <label class="offset-sm-2 col-sm-2 col-form-label"><b></b></label>
                    <label class="col-sm-2 col-form-label"></label>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><b>Type:</b></label>
                    <label class="col-sm-3 col-form-label">{{ $data->type == 1 ? 'Pure' : 'Blend' }}</label>
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
                    <a class="nav-link active" id="materials-tab" data-toggle="tab" href="#materials" role="tab" aria-controls="materials" aria-selected="true">Materials</a>
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
                    <a class="nav-link" id="rmc-tab" data-toggle="tab" href="#rmc" role="tab" aria-controls="rmc" aria-selected="false">Historical RMC</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="client-tab" data-toggle="tab" href="#client" role="tab" aria-controls="client" aria-selected="false">Client Transaction</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="identical-tab" data-toggle="tab" href="#identical" role="tab" aria-controls="identical" aria-selected="false">Identical Composition</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="identical-tab" data-toggle="tab" href="#identical" role="tab" aria-controls="identical" aria-selected="false">Historical Logs</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="materials" role="tabpanel" aria-labelledby="materials-tab">
                    <form method="POST" action="{{url('update_raw_materials/'.$data->id)}}">
                        {{csrf_field()}}

                        <div class="col-lg-12" align="right">
                            <button type="submit" class="btn btn-md btn-primary submit_approval">Update</button>
                        </div>
    
                        <button type="button" class="btn btn-sm btn-success mb-4" id="addBtn">
                            <i class="ti-plus"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger mb-4" id="removeBtn">
                            <i class="ti-minus"></i>
                        </button>
    
                        <table class="table table-striped table-bordered table-hover" id="material_table" width="100%">
                            <tbody class="tbodyRawMaterials">
                                @foreach ($data->product_raw_materials as $prm)
                                    <tr>
                                        <td>
                                            <select name="raw_materials[]" class="form-control js-example-basic-single required" style="width: 100%" required>
                                                <option value="">- Raw Materials -</option>
                                                @foreach ($rawMaterials as $rm)
                                                    <option value="{{$rm->id}}" @if($prm->raw_material_id == $rm->id) selected @endif>{{$rm->Name}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="percent[]" id="percent" class="form-control" placeholder="%" value="{{$prm->percent}}" max="100" required>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </form>
                </div>
                <div class="tab-pane fade" id="specifications" role="tabpanel" aria-labelledby="specifications-tab">
                    <div class="col-lg-12" align="right">
                        <button type="button" class="btn btn-md btn-primary submit_approval" name="submit_approval" id="">Add</button>
                    </div>
                    <table class="table table-striped table-hover" id="specification_table" width="100%">
                        <thead>
                            <tr>
                                <th>Parameter</th>
                                <th>Specification</th>
                                <th>Testing Condition</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="tab-pane fade" id="pds" role="tabpanel" aria-labelledby="pds-tab">...</div>
                <div class="tab-pane fade" id="files" role="tabpanel" aria-labelledby="files-tab"></div>
                <div class="tab-pane fade" id="rmc" role="tabpanel" aria-labelledby="rmc-tab">...</div>
                <div class="tab-pane fade" id="client" role="tabpanel" aria-labelledby="client-tab">...</div>
                <div class="tab-pane fade" id="identical" role="tabpanel" aria-labelledby="identical-tab">...</div>
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

{{-- <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap4.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.bootstrap4.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script> --}}

{{-- <script>
    $(document).ready(function(){
        $(document).on('click', '.submit_approval', function(){
            product_id = $(this).attr('id');
            $('#confirmModal').modal('show');
            $('.modal-title').text("Submit for Approval");
        });

        dataTableInstance = new DataTable('#material_table', {
            destroy: true, // Destroy and re-initialize DataTable on each call
            processing: true,
            serverSide: true, // Ensure server-side processing is enabled
            pageLength: 25,
            layout: {
                topStart: {
                    buttons: [
                        'copy',
                        {
                            extend: 'excel',
                            text: 'Export to Excel',
                            filename: 'Material', // Set the custom file name
                            title: 'Material' // Set the custom title
                        }
                    ]
                }
            },
            ajax: {
                url: "{{ route('activities.index') }}"
            },
            columns: [
                {
                    data: 'ActivityNumber',
                    name: 'ActivityNumber'
                },
                {
                    data: 'ScheduleFrom',
                    name: 'ScheduleFrom',
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false
                }
            ],
            columnDefs: [
                {
                    targets: [0, 1], // Target the Title column
                    render: function(data, type, row) {
                        return '<div style="white-space: break-spaces; width: 100%;">' + data + '</div>';
                    }
                }
            ]
        });
    });   
</script> --}}
<script>
    $(document).ready(function() {

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
                </tr>
            `;
            
            var row = $(newRow);
            $(".tbodyRawMaterials").append(row);
            row.find('.js-example-basic-single').select2();

        });

        $("#removeBtn").on('click', function()
        {
            $('.tbodyRawMaterials').children().last().remove();
        })
    });
</script>
@endsection
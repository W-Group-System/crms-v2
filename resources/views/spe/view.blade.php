@extends('layouts.header')
@section('content')
<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6"> 
                    <h4 class="card-title d-flex justify-content-between align-items-center" style="margin-top: 10px">View Supplier Product Evaluation</h4>
                </div>
                <div class="col-lg-6" align="right">
                    @if(url()->previous() == url()->current())
                    <a href="{{ url('supplier_product') }}" class="btn btn-md btn-outline-secondary">
                        <i class="icon-arrow-left"></i>&nbsp;Back
                    </a> 
                    @else
                    <a href="{{ url()->previous() ?: url('/supplier_product') }}" class="btn btn-md btn-outline-secondary">
                        <i class="icon-arrow-left"></i>&nbsp;Back
                    </a> 
                    @endif
                    <form action="{{ url('spe_approved/' . $data->id) }}" class="d-inline-block" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-success approvedBtn">
                            <i class="ti-check">&nbsp;</i>Approved
                        </button>
                    </form>
                    <button type="button" class="btn btn-md btn-outline-warning">Update</button>
                    <button type="button" class="btn btn-outline-warning" data-toggle="modal" data-target="#disposition">Disposition</button>
                </div>
                <div class="col-md-12">
                    <div class="form-group row mb-0" style="margin-top: 2em">
                        <label class="col-sm-3 col-form-label text-right"><b>Date Requested (MM/DD/YYYY):</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->DateRequested }}</label>
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Attention To:</b></label>
                        <div class="col-sm-3">
                            <label>
                                @if($data->AttentionTo == 'RND')
                                    RND
                                @elseif($data->AttentionTo == 'QCD-WHI')
                                    QCD-WHI 
                                @elseif($data->AttentionTo == 'QCD-PBI')
                                    QCD-PBI
                                @elseif($data->AttentionTo == 'QCD-MRDC')
                                    QCD-MRDC
                                @else 
                                    QCD-CCC
                                @endif
                            </label>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <label class="col-sm-3 col-form-label text-right"><b>Deadline (MM/DD/YYYY):</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->Deadline}}</label>
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Manufacturer of Sample:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->Manufacturer ?? 'N/A'}}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <label class="col-sm-3 col-form-label text-right"><b>Product Name:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->ProductName}}</label>
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Quantity:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->Quantity ?? 'N/A'}}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <label class="col-sm-3 col-form-label text-right"><b>Supplier/ Trader Name:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->suppliers->Name}}</label>
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Product Application:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->ProductApplication ?? 'N/A'}}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <label class="col-sm-3 col-form-label text-right"><b>Origin:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->Origin ?? 'N/A'}}</label>
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Lot No./ Batch No.:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->LotNo ?? 'N/A'}}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label text-right"><b>Price:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->Price ?? 'N/A'}}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <label class="col-sm-3 col-form-label text-right"><b>Instruction to Laboratory:</b></label>
                        <div class="col-sm-3">
                            @if($data->supplier_instruction && count($data->supplier_instruction) > 0)
                                @foreach($data->supplier_instruction as $instruction)
                                    <label>{{ $instruction->Instruction }}</label><br>
                                @endforeach
                            @else
                                <label>No Instructions Available</label>
                            @endif
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Status:</b></label>
                        <div class="col-sm-3">
                            <label>
                                @if($data->Status == 10)
                                    Open
                                @else
                                    Closed
                                @endif
                            </label>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <label class="col-sm-3 col-form-label text-right"><b>Prepared By:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->prepared_by->full_name }}</label>
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Progress:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->progress->name }}</label>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label text-right"><b>Approved By:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->approved_by->full_name }}</label>
                        </div>
                        <!-- <label class="col-sm-3 col-form-label text-right"><b>Progress:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->progress->name }}</label>
                        </div> -->
                    </div>
                    <!-- <div class="form-group row mb-0">
                        <label class="col-sm-3 col-form-label text-right"><b>Attachments:</b></label>
                        <div class="col-sm-3">
                            @if($data->attachments && count($data->attachments) > 0)
                                @foreach($data->attachments as $file)
                                    <label>
                                        {{ $file->Name }} <br>
                                        <a href="{{ asset('storage/' . $file->Path) }}" target="_blank">{{ $file->Path }}</a>
                                    </label>
                                    <br>
                                @endforeach
                            @else
                                <label>No Attachments Available</label>
                            @endif
                        </div>
                        <label class="col-sm-3 col-form-label text-right"><b>Disposition:</b></label>
                        <div class="col-sm-3">
                            <label>{{ $data->Disposition ?? ''}}</label>
                        </div>
                    </div> -->
                    <table class="table table-bordered">
                        <thead>
                            <th width="40%">Attachment Name</th>
                            <th width="60%">File</th>
                        </thead>
                        <tbody>
                            @if($data->attachments && count($data->attachments) > 0)
                                @foreach($data->attachments as $file)
                                <tr>
                                    <td>{{ $file->Name }}</td>
                                    <td><a href="{{ asset('storage/' . $file->Path) }}" target="_blank">{{ $file->Path }}</a></td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="2">No data Available</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div align="right" class="mt-3">
                <a href="{{ url('supplier_product') }}" class="btn btn-outline-secondary">Close</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="disposition" tabindex="-1" role="dialog" aria-labelledby="dispositionModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dispositionModal">Disposition</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="updateDisposition" method="POST" action="{{ url('update_disposition/' . $data->id) }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <select class="form-control js-example-basic-multiple" name="Disposition[]" style="position: relative !important" multiple>
                                <option value="1">Almost an exact match with the current product. The sample works with direct replacement in the application</option>
                                <option value="2">Has higher quality than the existing raw materials. Needs dilution or lower proportion in product application</option>
                                <option value="3">Has lower quality than the existing product. Needs higher proportion in the product applications</option>
                                <option value="4">Cannot be fully evaluated. The company does not have the testing capability</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer mt-3">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-outline-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('.approvedBtn').on('click', function (e) {
            e.preventDefault(); // Prevent default form submission

            var button = $(this);
            var form = button.closest('form');
            var actionUrl = form.attr('action'); // Get form action URL

            // Disable the button to prevent multiple clicks
            button.prop('disabled', true);

            $.ajax({
                url: actionUrl,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: form.serialize(),
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: "Success",
                            text: response.message,
                            icon: "success",
                            timer: 1500,
                            showConfirmButton: false
                        }).then(function () {
                            window.location.reload(); // Reload the page
                        });
                    }
                }
            });
        });
    });
</script>
@endsection
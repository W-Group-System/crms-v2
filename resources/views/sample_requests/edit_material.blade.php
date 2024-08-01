<div class="modal fade" id="editSrfMaterial{{ $SrfMaterial->Id }}" tabindex="-1" role="dialog" aria-labelledby="Material Update" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="Material">Edit Raw Material</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_sample_request" enctype="multipart/form-data" action="{{ url('UpdateRawMaterial/edit/'.$SrfMaterial->Id) }}">
                    @csrf
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>Material</label>
                            <select class="form-control js-example-basic-single" name="RawMaterial"  style="position: relative !important" title="Select RND Personnel" >
                                <option value="" disabled selected>Raw Material</option>
                                @foreach ($rawMaterials as $rawMaterial)
                                    <option value="{{ $rawMaterial->id }}" @if ($SrfMaterial->MaterialId == $rawMaterial->id) selected @endif>{{ $rawMaterial->Name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>Lot Number</label>
                            <input type="text" class="form-control" name="LotNumber" value="{{ $SrfMaterial->LotNumber }}">
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>Remarks</label>
                            <input type="text" class="form-control" name="Remarks" value="{{ $SrfMaterial->Remarks }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

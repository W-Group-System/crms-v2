<div class="modal fade" id="addRawMaterial" tabindex="-1" role="dialog" aria-labelledby="addSrfSuplementaryInfoLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addSrfSuplementaryLabel">SRF Raw Material</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method="POST" action="{{ url('srfRawMaterial') }}" onsubmit="show()" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-card">
                        <div class="row mb-2">
                            <div class="col-lg-12 mt-1">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Material</label>
                                        <select class="form-control js-example-basic-single" name="RawMaterial" style="position: relative !important" title="Select Material" required>
                                            <option value="" disabled selected>Select Material</option>
                                            @foreach($rawMaterials as $rawMaterial)
                                                <option value="{{ $rawMaterial->id }}">{{ $rawMaterial->Name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="hidden" class="form-control" name="SampleRequestId" value="{{ $sampleRequest->Id }}">
                                </div>
                            </div>
                        </div>
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

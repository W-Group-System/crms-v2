<div class="modal fade" id="addSrfPersonnel" tabindex="-1" role="dialog" aria-labelledby="addSrfSuplementaryInfoLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addSrfSuplementaryLabel">Assign R&D Personnel</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method="POST" action="{{ url('assignSrfPersonnel') }}" onsubmit="show()" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-card">
                        <div class="row mb-2">
                            <div class="col-lg-12 mt-1">
                                <div class="form-group">
                                    <label>R&D Personnel:</label>
                                    <select class="form-control js-example-basic-single" name="RndPersonnel" id="RndPersonnel" style="position: relative !important" title="Select RND Personnel" >
                                        <option value="" disabled selected>R&D Personnel</option>
                                        @foreach ($rndPersonnel as $personnel)
                                            <option value="{{ $personnel->id }}" >{{ $personnel->full_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="hidden" class="form-control" name="srf_id" value="{{ $sampleRequest->Id }}">
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

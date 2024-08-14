<div class="modal fade" id="addRpeSuplementary" tabindex="-1" role="dialog" aria-labelledby="addRpeSuplementaryInfoLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addRpeSuplementaryLabel">Supplementary Details</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method="POST" action="{{ url('addRpeSupplementary') }}" onsubmit="show()" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-card">
                        <div class="row mb-2">
                            <div class="col-lg-12 mt-1">
                                <div class="form-group">
                                    <label for="SoNumber">Supplementary Detail:</label>
                                    <input type="text" class="form-control" name="details_of_request" placeholder="Add Supplementary Detail">
                                </div>
                                <div class="form-group">
                                    <input type="hidden" class="form-control" name="rpe_id" value="{{ $requestEvaluation->id }}">
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

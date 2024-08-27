<div class="modal fade" id="addRpeSuplementary" tabindex="-1" role="dialog" aria-labelledby="addRpeSuplementaryInfoLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addRpeSuplementaryLabel">Add Supplementary Details</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method="POST" action="{{ url('addRpeSupplementary') }}">
                @csrf

                <input type="hidden" name="rpe_id" value="{{$requestEvaluation->id}}">
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Supplementary Detail:</label>
                                <textarea class="form-control" name="details_of_request" placeholder="Add Supplementary Detail" cols="30" rows="10"></textarea>
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

<div class="modal fade" id="updateRnd{{$requestEvaluation->id}}" tabindex="-1" role="dialog" aria-labelledby="closeModal" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Update Request Product Evaluation</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
            <div class="modal-body">
                <form method="POST" action="{{ url('product_evaluation/edit/'.$requestEvaluation->id) }}">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>DDW Number</label>
                                <input type="text" name="ddw_number" class="form-control" placeholder="Enter DDW Number" value="{{$requestEvaluation->DdwNumber}}" required>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Date Received</label>
                                <input type="date" name="date_received" class="form-control" value="{{$requestEvaluation->DateReceived}}">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>RPE Recommendation</label>
                                <textarea name="rpe_recommendation" class="form-control" cols="30" rows="10">{{$requestEvaluation->RpeResult}}</textarea>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Date Started</label>
                                <input type="date" name="date_started" class="form-control" value="{{$requestEvaluation->DateStarted}}">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Date Completed</label>
                                <input type="date" name="date_completed" class="form-control" value="{{$requestEvaluation->DateCompleted}}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="action" value="update_rnd">Update</button>
                    </div>
                </form>
            </div>
		</div>
	</div>
</div>
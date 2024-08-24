<div class="modal fade" id="updateRnd{{$requestEvaluation->id}}" tabindex="-1" role="dialog" aria-labelledby="closeModal" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Add Close Remarks</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
            <div class="modal-body">
                <form method="POST" action="{{ url('/'.$requestEvaluation->id) }}">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>DDW Number</label>
                                <input type="text" name="ddw_number" class="form-control" placeholder="Enter DDW Number" required>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Date Received</label>
                                <input type="date" name="date_received" class="form-control" min="{{date('Y-m-d')}}" required>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>RPE Recommendation</label>
                                <textarea name="rpe_recommendation" class="form-control" cols="30" rows="10"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Date Started</label>
                                <input type="date" name="date_started" class="form-control" min="{{date('Y-m-d')}}" required>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Date Completed</label>
                                <input type="date" name="date_completed" class="form-control" min="{{date('Y-m-d')}}" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="submit"  class="btn btn-success" value="Save">
                    </div>
                </form>
            </div>
		</div>
	</div>
</div>
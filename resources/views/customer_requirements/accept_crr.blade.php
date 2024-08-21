<div class="modal fade" id="acceptModal{{$crr->id}}" tabindex="-1" role="dialog" aria-labelledby="cancelModal" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Accept</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
            <div class="modal-body">
                <form method="POST" action="{{ url('accept_crr/'.$crr->id) }}">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Remarks</label>
                                <textarea name="accept_remarks" class="form-control" cols="50" rows="10" placeholder="Enter remarks" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-info" name="action" value="approved_to_{{$crr->RefCode}}">Approve to {{$crr->RefCode}}</button>
                        <button type="submit" class="btn btn-success" name="action" value="approved_to_sales">Approve to Sales</button>
                    </div>
                </form>
            </div>
		</div>
	</div>
</div>
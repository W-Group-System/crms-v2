<div class="modal fade" id="addSupplementary" tabindex="-1" role="dialog" aria-labelledby="cancelModal" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Add Supplementary Details</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" action="{{ url('add_supplementary') }}" onsubmit="show()">
                    @csrf
                    <input type="hidden" name="customer_requirement_id" value="{{$crr->id}}">
                    <input type="hidden" name="user_id" value="{{auth()->user()->id}}">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Details</label>
                                <textarea name="details" class="form-control" cols="50" rows="10" placeholder="Enter supplementary details" required></textarea>
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
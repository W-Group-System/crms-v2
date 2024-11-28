<div class="modal fade" id="addSsePersonnel" tabindex="-1" role="dialog" aria-labelledby="cancelModal" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Add Personnel</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
            <div class="modal-body">
                <form method="POST" action="{{ url('add_sse_personnel') }}" onsubmit="show()">
                    @csrf
                    <input type="hidden" name="sse_id" value="{{$data->id}}">
                    <input type="hidden" name="user_id" value="{{auth()->user()->id}}">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>SSE Personnel:</label>
                                <select name="sse_personnel" class="form-control js-example-basic-single" required>
                                    <option disabled selected value>Select Personnel</option>
                                    @foreach ($rnd_personnel as $rnd)
                                        <option value="{{$rnd->id}}">{{$rnd->full_name}}</option>
                                    @endforeach
                                </select>
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
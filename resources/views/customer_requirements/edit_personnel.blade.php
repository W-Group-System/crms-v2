<div class="modal fade" id="editPersonnel{{$personnel->Id}}" tabindex="-1" role="dialog" aria-labelledby="cancelModal" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Edit Personnel</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
            <div class="modal-body">
                <form method="POST" action="{{ url('update_personnel/'.$personnel->Id) }}">
                    @csrf
                    <input type="hidden" name="customer_requirement_id" value="{{$crr->id}}">
                    <input type="hidden" name="user_id" value="{{auth()->user()->id}}">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>CRR Personnel :</label>
                                <select name="personnel" class="form-control js-example-basic-single">
                                    <option disabled selected value>-Select personnel-</option>
                                    @foreach ($rnd_personnel as $rnd)
                                        <option value="{{$rnd->id}}" @if($rnd->id == $personnel->PersonnelUserId) selected @endif>{{$rnd->full_name}}</option>
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
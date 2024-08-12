<div class="modal fade" id="updateCrr-{{$crr->id}}" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addCustomerRequirentLabel">Edit Customer Requiremnt</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
            <div class="modal-body">
                <form method="POST" action="{{url('update_crr/'.$crr->id)}}">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>DDW Number :</label>
                                <input type="text" name="ddw_number" class="form-control" value="{{$crr->DdwNumber}}">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Date Received :</label>
                                <input type="date" name="date_received" class="form-control" value="{{$crr->DateReceived}}">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Recommendation :</label>
                                <textarea name="recommendation" cols="30" rows="10" class="form-control">{{$crr->Recommendation}}</textarea>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="name">Due Date :</label>
                                <input type="date" class="form-control" name="due_date" value="{{$crr->DueDate}}">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="name">Progress</label>
                                <select name="progress" class="js-example-basic-single form-control">
                                    <option value=""></option>
                                    <option value="30" @if($crr->Progress == 30) selected @endif>R&D Manager</option>
                                    <option value="35" @if($crr->Progress == 35) selected @endif>R&D Received</option>
                                    <option value="40" @if($crr->Progress == 40) selected @endif>R&D Assigned</option>
                                    <option value="50" @if($crr->Progress == 50) selected @endif>R&D Ongoing</option>
                                    <option value="55" @if($crr->Progress == 55) selected @endif>R&D Pending</option>
                                    <option value="57" @if($crr->Progress == 57) selected @endif>R&D Initial Review</option>
                                    <option value="58" @if($crr->Progress == 58) selected @endif>R&D Final Review</option>
                                    <option value="60" @if($crr->Progress == 60) selected @endif>R&D Completed</option>
                                    <option value="70" @if($crr->Progress == 70) selected @endif>Sales Accepted</option>
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

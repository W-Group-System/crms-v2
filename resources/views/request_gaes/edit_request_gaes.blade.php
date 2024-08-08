<div class="modal fade" id="editRequestGae-{{$pt->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Price Request GAE</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_request_gae" action="{{url('update_request_gae/' . $pt->id)}}">
                    @csrf
                    <div class="form-group">
                        <label for="name">Expense Name</label>
                        <input type="text" class="form-control" id="ExpenseName" name="ExpenseName" placeholder="Enter Expense Name" value="{{$pt->ExpenseName}}" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Cost</label>
                        <input type="text" class="form-control" id="Cost" name="Cost" placeholder="Enter Cost" value="{{$pt->Cost}}" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" >Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
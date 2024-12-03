<div class="modal" id="return{{$crr->id}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Return</h5>
            </div>
            <form method="POST" action="{{url('start_crr/'.$crr->id)}}" onsubmit="show()">
                @csrf 

                <div class="modal-body">
                    Remarks
                    <textarea name="return_to_specialist_remarks" class="form-control" cols="30" rows="10" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success" name="action" value="return_to_specialist">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
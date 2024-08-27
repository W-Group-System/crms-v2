<div class="modal fade" id="approvePrf{{ $price_monitorings->id }}" tabindex="-1" role="dialog" aria-labelledby="PRF Approval" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="PRFApproval">Approve Sample Request</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" action="{{ url('ApprovePrf/'.$price_monitorings->id) }}">
                    @csrf
                    <div class="form-group">
                        <label for="ApprovalRemark">Comment/Remark</label>
                        <input type="text" class="form-control" name="Remarks" placeholder="Enter Approval Remarks">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="submitbutton" class="btn btn-success">Approve</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

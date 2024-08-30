<div class="modal fade" id="approveRpe{{ $requestEvaluation->id }}" tabindex="-1" role="dialog" aria-labelledby="SRF Approval" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="SRFApproval">Approve Request Product Evaluation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_sample_request" enctype="multipart/form-data" action="{{ url('ApproveRpe/'.$requestEvaluation->id) }}">
                    @csrf
                    <div class="form-group">
                        <label for="ApprovalRemark">Comment/Remark</label>
                        <input type="text" class="form-control" name="Remarks" placeholder="Enter Approval Remarks">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="submitbutton" value="Approve to R&D" class="btn btn-success">Approve to R&D</button>
                        <button type="submit" name="submitbutton" value="Approve to QCD" class="btn btn-success">Approve to QCD</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

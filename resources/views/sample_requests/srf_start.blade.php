<div class="modal fade" id="startSrf{{ $sampleRequest->Id }}" tabindex="-1" role="dialog" aria-labelledby="SRF Start" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="SRFStart">Start Sample Request</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_sample_request" enctype="multipart/form-data" action="{{ url('StartSrf/'.$sampleRequest->Id) }}">
                    @csrf
                    <div class="form-group">
                        <label >Do You Want to Proceed?</label>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" value="Start" class="btn btn-success">Start</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

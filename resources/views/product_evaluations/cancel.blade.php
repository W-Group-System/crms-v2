<div class="modal fade" id="cancelRpe{{ $requestEvaluation->id }}" tabindex="-1" role="dialog" aria-labelledby="RPE Close" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="RPEClose">Cancel Request Product Evaluation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_price_request" enctype="multipart/form-data" action="{{ url('CancelRpe/'.$requestEvaluation->id) }}">
                    @csrf
                    <div class="form-group"><h3>Are you sure?</h3></div>
                   
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" value="Receive" class="btn btn-success">OK</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

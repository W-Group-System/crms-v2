<div class="modal fade" id="closePrf{{ $price_monitorings->id }}" tabindex="-1" role="dialog" aria-labelledby="PRF Close" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="PRFClose">Close Price Request</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_price_request" enctype="multipart/form-data" action="{{ url('ClosePrf/'.$price_monitorings->id) }}">
                    @csrf
                    <div class="form-group"><h3>Are you sure?</h3></div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>Is Accepted?</label>
                            <input type="checkbox" name="IsAccepted">
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label >Buyers Ref Code</label>
                            <input type="text" class="form-control" name="BuyersRefCode" placeholder="Enter Buyers Ref Code">
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label >Disposition Remarks</label>
                            <input type="text" class="form-control" name="DispositionRemarks" placeholder="Enter Disposition Remarks">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" value="Receive" class="btn btn-success">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

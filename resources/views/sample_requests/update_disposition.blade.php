<div class="modal fade" id="updateDisposition{{ $sampleRequest->Id }}" tabindex="-1" role="dialog" aria-labelledby="Update Disposition" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="UpdateDisposition">Update Disposition</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST"  enctype="multipart/form-data" action="{{ url('editDisposition/'.$sampleRequest->Id) }}">
                    @csrf
                    @foreach ($sampleRequest->requestProducts as $product)
                    <div class="form-group">
                        <label>SRF Index</label>
                        <input class="form-control" value="{{ $sampleRequest->SrfNumber}}-{{ $product->ProductIndex }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Disposition:</label>
                        <select class="form-control js-example-basic-single" name="Disposition[{{ $product->id }}]" style="position: relative !important" title="Select Disposition">
                            <option value="0" {{  $product->Disposition == "0" ? 'selected' : '' }}>Select Disposition</option>
                            <option value="1" {{  $product->Disposition == "1" ? 'selected' : '' }}>No feedback</option>
                            <option value="10" {{  $product->Disposition == "10" ? 'selected' : '' }}>Accepted</option>
                            <option value="20" {{  $product->Disposition == "20" ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="form-group">
                                <label>Disposition Remarks</label>
                                <textarea class="form-control" name="DispositionRejectionDescription[{{ $product->id }}]" placeholder="Enter Disposition Remarks">{{ $product->DispositionRejectionDescription }}</textarea>
                            </div>
                    @endforeach
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" value="Receive" class="btn btn-success">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

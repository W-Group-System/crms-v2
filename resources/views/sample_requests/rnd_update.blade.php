<div class="modal fade" id="rndUpdate{{ $sampleRequest->Id }}" tabindex="-1" role="dialog" aria-labelledby="SRF Pause" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rndUpdate">Update Sample Request</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_sample_request" enctype="multipart/form-data" action="{{ url('RndUpdate/'.$sampleRequest->Id) }}">
                    @csrf
                    <div class="form-group">
                        <label>Progress:</label>
                        <select class="form-control js-example-basic-single" name="Progress" style="position: relative !important" title="Progress" required>
                            <option value="" disabled selected>Progress</option>
                            @foreach ($srfProgress as $Progress)
                                <option value="{{ $Progress->id }}" @if ($sampleRequest->Progress == $Progress->id) selected @endif>{{ $Progress->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" value="Receive" class="btn btn-success">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

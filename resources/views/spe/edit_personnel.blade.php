<div class="modal fade" id="editPersonnel{{$personnel->id}}" tabindex="-1" role="dialog" aria-labelledby="cancelModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Personnel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ url('update_spe_personnel/'.$personnel->id) }}" onsubmit="showLoader()">
                    @csrf
                    <input type="hidden" name="spe_id" value="{{ $data->id }}">
                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="personnel-select">SPE Personnel:</label>
                                <select id="personnel-select" name="spe_personnel" class="form-control js-example-basic-single">
                                    <option disabled selected value>Select Personnel</option>
                                    @foreach ($rnd_personnel as $rnd)
                                        <option value="{{ $rnd->id }}" 
                                            @if($rnd->id == $personnel->SpePersonnel || $rnd->user_id == $personnel->SpePersonnel) 
                                                selected 
                                            @endif>
                                            {{ $rnd->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
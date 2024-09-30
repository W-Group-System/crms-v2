<div class="modal fade" id="editRpePersonnel{{ $Personnel->Id }}" tabindex="-1" role="dialog" aria-labelledby="Personnel Update" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="Personnel">Edit R&D Personnel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_sample_request" action="{{ url('UpdateAssignedRpePersonnel/'.$Personnel->Id) }}" onsubmit="show()">
                    @csrf
                    <div class="form-group">
                        <label>R&D Personnel:</label>
                        <select class="form-control js-example-basic-single" name="RndPersonnel"  style="position: relative !important" title="Select RND Personnel" >
                            <option value="" disabled selected>R&D Personnel</option>
                            @foreach ($rndPersonnel as $personnel)
                                <option value="{{ $personnel->id }}" @if ($Personnel->PersonnelUserId == $personnel->user_id || $Personnel->PersonnelUserId == $personnel->id) selected @endif>{{ $personnel->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

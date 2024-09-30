<div class="modal fade" id="editRpeSupplementary{{ $supplementary->Id }}" tabindex="-1" role="dialog" aria-labelledby="Supplementary Update" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="SupplementaryUpdate">Edit Supplemenrtary Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_sample_request" enctype="multipart/form-data" action="{{ url('UpdateRpeSupplementary/'.$supplementary->Id) }}" onsubmit="show()">
                    @csrf
                    <div class="form-group">
                        <label >Supplementary Details</label>
                        {{-- <input type="text" class="form-control" name="details_of_request" value="{{ optional($supplementary)->DetailsOfRequest }}"> --}}
                        <textarea name="details_of_request" class="form-control" cols="30" rows="10">{{$supplementary->DetailsOfRequest}}</textarea>
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

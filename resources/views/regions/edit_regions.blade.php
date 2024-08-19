<div class="modal fade" id="updateFormRegion{{$reg->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Region</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" class="update_form_region" action="{{ url('update_region/'.$reg->id) }}">
                    <span id="update_form_result"></span>
                    @csrf
                    <div class="form-group">
                        <label>Type</label>
                        <select class="form-control js-example-basic-single" name="Type" style="position: relative !important" title="Select Type">
                            <option value="" disabled selected>Select Type</option>
                            <option value="1">Local</option>
                            <option value="2">International</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">Region</label>
                        <input type="text" class="form-control" id="Name" name="Name" placeholder="Enter Region">
                    </div>
                    <div class="form-group">
                        <label for="name">Description</label>
                        <input type="text" class="form-control" id="Description" name="Description" placeholder="Enter Description">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" >Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
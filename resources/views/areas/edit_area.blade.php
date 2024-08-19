<div class="modal fade" id="edit{{$area->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Area</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" class="update_form_area" action="{{ url('update_area/'.$area->id) }}">
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
                        <label>Region</label>
                        <select class="form-control js-example-basic-single" name="RegionId" style="position: relative !important" title="Select Company">
                            <option value="" disabled selected>Select Region</option>
                            @foreach($regions as $region)
                                <option value="{{ $region->id }}">{{ $region->Name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">Area</label>
                        <input type="text" class="form-control" id="Name" name="Name" placeholder="Enter Area Name">
                    </div>
                    <div class="form-group">
                        <label for="name">Description</label>
                        <input type="text" class="form-control" id="Description" name="Description" placeholder="Enter Description">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" name="action_button" id="action_button" class="btn btn-success" value="Update">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
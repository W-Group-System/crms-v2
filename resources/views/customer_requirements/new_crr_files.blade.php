<div class="modal fade" id="addCrrFiles">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Files</h5>
            </div>
            <form method="POST" action="{{url('add_crr_file')}}" enctype="multipart/form-data">
                @csrf 
                <input type="hidden" name="customer_requirements_id" value="{{$crr->id}}">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <label>Name :</label>
                            <input type="text" name="file_name" class="form-control crrFileName" placeholder="Enter name" required>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <div class="form-group">
                                <label>Is Confidential :</label>
                                <input type="checkbox" name="is_confidential" checked>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <div class="form-group">
                                <label>Is For Review :</label>
                                <input type="checkbox" name="is_for_review" checked>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label>Browser File :</label>
                            <input type="file" name="crr_file" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="padding: 0.6875rem">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
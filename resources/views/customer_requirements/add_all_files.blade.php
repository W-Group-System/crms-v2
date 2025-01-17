<div class="modal fade" id="uploadMultipleFiles" tabindex="-1" role="dialog" aria-labelledby="cancelModal" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Upload Multiple Files</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
            <form method="POST" action="{{ url('multipleUploadFiles') }}" onsubmit="show()" enctype="multipart/form-data" onsubmit="show()">
                @csrf
                <input type="hidden" name="customer_requirement_id" value="{{$crr->id}}">

                <div class="modal-body pb-1">
                    <div id="multipleFilesContainer">
                        <div class="row fileNameRow">
                            <div class="col-lg-12 mb-3 ">
                                <label>Name :</label>
                                <input type="text" name="file_name[]" class="form-control crrFileName" placeholder="Enter name" required>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <div class="form-group">
                                    <label>Is Confidential :</label>
                                    <input type="checkbox" name="is_confidential[]">
                                </div>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <div class="form-group">
                                    <label>Is For Review :</label>
                                    <input type="checkbox" name="is_for_review[]">
                                </div>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <label>Browser File :</label>
                                <input type="file" name="crr_file[]" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5">
                        <hr>
                        <button type="button" class="btn btn-success btn-sm mb-3 rounded-0" id="addMultipleFilesBtn">
                            <i class="ti-plus"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-sm mb-3 rounded-0" id="closeMultipleFilesBtn">
                            <i class="ti-trash"></i>
                        </button>
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
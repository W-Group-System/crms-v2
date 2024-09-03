<div class="modal fade" id="uploadMultipleFiles" tabindex="-1" role="dialog" aria-labelledby="cancelModal" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
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

                <div class="modal-body">
                    <button type="button" class="btn btn-success btn-sm mb-3 addMultipleFilesBtn">
                        <i class="ti-plus"></i>
                    </button>
                    <div id="multipleFilesContainer">
                        <div class="row">
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
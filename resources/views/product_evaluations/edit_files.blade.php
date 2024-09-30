<div class="modal fade" id="editRpeFile{{ $fileupload->Id }}" tabindex="-1" role="dialog" aria-labelledby="RPE File Update" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rpeFile">Edit RPE File</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" action="{{ url('updateRpeFile/'.$fileupload->Id) }}" onsubmit="show()">
                    @csrf
                    <div>
                        <div class="form-group">
                            <label for="name"><b>Name</b></label>
                            <input type="text" name="name" id="fileName" class="form-control" value="{{ $fileupload->Name }}">
                        </div>
                        @if(authCheckIfItsRnd(auth()->user()->department_id))
                            @if(authCheckIfItsRndStaff(auth()->user()->role))
                                <div class="col-lg-12 mb-3">
                                    <div class="form-group">
                                        <label>Is Confidential :</label>
                                        <input type="checkbox" name="is_confidential" @if($fileupload->IsConfidential == 1) checked @endif disabled>
                                    </div>
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <div class="form-group">
                                        <label>Is For Review :</label>
                                        <input type="checkbox" name="is_for_review" @if($fileupload->IsForReview == 1) checked @endif disabled>
                                    </div>
                                </div>
                            @else
                                <div class="col-lg-12 mb-3">
                                    <div class="form-group">
                                        <label>Is Confidential :</label>
                                        <input type="checkbox" name="is_confidential" @if($fileupload->IsConfidential == 1) checked @endif>
                                    </div>
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <div class="form-group">
                                        <label>Is For Review :</label>
                                        <input type="checkbox" name="is_for_review" @if($fileupload->IsForReview == 1) checked @endif>
                                    </div>
                                </div>
                            @endif
                        @endif
                        <div class="form-group">
                            <label for="rpe_file"><b>Browse Files</b></label>
                            <input type="file" class="form-control file" name="rpe_file">
                        </div>
                        <div class="form-group">
                            <input type="hidden" class="form-control" name="rpe_id" value="{{ $requestEvaluation->id }}">
                        </div>
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
<script>
     $(document).ready(function() {
        $(document).on('change', '.file', function() {
            var filename = $(this).val().split('\\').pop();
            $('#fileName').val(filename);
        });
    });
</script>
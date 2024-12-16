<div class="modal fade" id="editSpeFile{{ $file->id }}" tabindex="-1" role="dialog" aria-labelledby="SSE File Update" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="srfFile">Edit SPE File</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" action="{{ url('update_spe_file/'.$file->id) }}">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="fileNameSpe{{ $file->id }}" class="form-control" value="{{ $file->Name }}">
                        </div>
                        <!-- Is Confidential -->
                        @if(authCheckIfItsRnd(auth()->user()->department_id))
                            @if(authCheckIfItsRndStaff(auth()->user()->role))
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Is Confidential :</label>
                                        <input type="checkbox" name="is_confidential" @if($file->IsConfidential == 1) checked @endif>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Is For Review :</label>
                                        <input type="checkbox" name="is_for_review" @if($file->IsForReview == 1) checked @endif>
                                    </div>
                                </div>
                            @else
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Is Confidential :</label>
                                        <input type="checkbox" name="is_confidential" @if($file->IsConfidential == 1) checked @endif>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Is For Review :</label>
                                        <input type="checkbox" name="is_for_review" @if($file->IsForReview == 1) checked @endif>
                                    </div>
                                </div>
                            @endif
                        @endif

                        <!-- File Input -->
                        <div class="col-lg-12 mb-3">
                            <label for="spe_file">Browse Files</label>
                            <input type="file" class="form-control spefile" id="spe_file{{ $file->id }}" name="spe_file">
                        </div>
                        <div class="col-lg-12">
                            <input type="hidden" class="form-control" name="spe_id" value="{{ $file->SpeId }}"> 
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
    $(document).ready(function () {
        $(document).on('change', '.spefile', function () {
            var filename = $(this).val().split('\\').pop(); 
            var modalId = $(this).closest('.modal').attr('id'); 
            $('#' + modalId).find('input[id^="fileNameSpe"]').val(filename);
        });
    });
</script>
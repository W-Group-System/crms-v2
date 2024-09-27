<div class="modal fade" id="uploadFile" tabindex="-1" role="dialog" aria-labelledby="SRFPause" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="SRFPause">Upload SRF File</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_sample_request" enctype="multipart/form-data" action="{{ url('uploadsrfFiles') }}">
                    @csrf
                    <div class="srf-file">
                        <div class="row">
                            <div class="col-lg-12 mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name[]" class="form-control" id="name" placeholder="">
                            </div>
                            @if(authCheckIfItsRnd(auth()->user()->department_id))
                                @if(authCheckIfItsRndStaff(auth()->user()->role))
                                    <div class="col-lg-12 mb-3">
                                        <div class="form-group">
                                            <label>Is Confidential :</label>
                                            <input type="checkbox" name="is_confidential" checked disabled>
                                            <input type="hidden" name="is_confidential" value="1">
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <div class="form-group">
                                            <label>Is For Review :</label>
                                            <input type="checkbox" name="is_for_review" checked disabled>
                                            <input type="hidden" name="is_for_review" value="1">
                                        </div>
                                    </div>
                                @else
                                    <div class="col-lg-12 mb-3">
                                        <div class="form-group">
                                            <label>Is Confidential :</label>
                                            <input type="checkbox" name="is_confidential">
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <div class="form-group">
                                            <label>Is For Review :</label>
                                            <input type="checkbox" name="is_for_review">
                                        </div>
                                    </div>
                                @endif
                            @endif
                            <div class="col-lg-12 mb-3">
                                <label for="srf_file">Browse Files</label>
                                <input type="file" class="form-control" id="srf_file" name="srf_file[]" multiple>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <input type="hidden" class="form-control" name="srf_id" value="{{ $sampleRequest->Id }}">
                            </div>
                            <div class="col-lg-12 mb-3">
                                <button type="button" class="btn btn-sm btn-primary addSrfFile"><i class="ti-plus"></i></button>
                                <button type="button" class="btn btn-sm btn-danger deleteRowBtn" hidden><i class="ti-trash"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" value="Receive" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        function addSrfFileForm() {
            var newProductForm = `
            <div class="srf-file">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name[]" class="form-control" placeholder="">
                </div>
                @if(authCheckIfItsRnd(auth()->user()->department_id))
                                @if(authCheckIfItsRndStaff(auth()->user()->role))
                                    <div class="col-lg-12 mb-3">
                                        <div class="form-group">
                                            <label>Is Confidential :</label>
                                            <input type="checkbox" name="is_confidential" checked disabled>
                                            <input type="hidden" name="is_confidential" value="1">
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <div class="form-group">
                                            <label>Is For Review :</label>
                                            <input type="checkbox" name="is_for_review" checked disabled>
                                            <input type="hidden" name="is_for_review" value="1">
                                        </div>
                                    </div>
                                @else
                                    <div class="col-lg-12 mb-3">
                                        <div class="form-group">
                                            <label>Is Confidential :</label>
                                            <input type="checkbox" name="is_confidential">
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <div class="form-group">
                                            <label>Is For Review :</label>
                                            <input type="checkbox" name="is_for_review">
                                        </div>
                                    </div>
                                @endif
                            @endif
                <div class="form-group">
                    <label for="srf_file">Browse Files</label>
                    <input type="file" class="form-control" name="srf_file[]" multiple>
                </div>
                <div class="form-group">
                    <input type="hidden" class="form-control" name="srf_id" value="{{ $sampleRequest->Id }}">
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-sm btn-primary addSrfFile"><i class="ti-plus"></i></button>
                    <button type="button" class="btn btn-sm btn-danger deleteRowBtn"><i class="ti-trash"></i></button>
                </div>
            </div>`;
    
            $('.srf-file').last().find('.addSrfFile').hide();
            $('.srf-file').last().find('.deleteRowBtn').show();
            $('.srf-file').last().after(newProductForm);
        }
    
        $(document).on('click', '.addSrfFile', function() {
            addSrfFileForm();
        });
    
        $(document).on('click', '.deleteRowBtn', function() {
            var currentRow = $(this).closest('.srf-file');
            
            if ($('.srf-file').length > 1) {
                if ($('.srf-file').last().is(currentRow)) {
                    currentRow.prev().find('.addSrfFile').show();
                    currentRow.prev().find('.deleteRowBtn').show();
                }
                currentRow.remove();
            }
            
            if ($('.srf-file').length === 1) {
                $('.srf-file').find('.addSrfFile').show();
                $('.srf-file').find('.deleteRowBtn').hide();
            }
        });
    
        $(document).on('change', 'input[type="file"]', function() {
            var filename = $(this).val().split('\\').pop();
            $(this).closest('.srf-file').find('input[name="name[]"]').val(filename);
        });
    
        if ($('.srf-file').length === 1) {
            $('.srf-file').find('.deleteRowBtn').hide();
        }
    });
    </script>
    
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
                <form method="POST" id="form_sample_request" enctype="multipart/form-data" action="{{ url('srfFiles') }}">
                    @csrf
                    <div class="srf-file">
                        <div class="form-group">
                            <label for="name"><b>Name</b></label>
                            <input type="text" name="name[]" class="form-control" id="name" placeholder="">
                        </div>
                        <div class="form-group">
                            <label for="srf_file"><b>Browse Files</b></label>
                            <input type="file" class="form-control" id="srf_file" name="srf_file[]" multiple>
                        </div>
                        <div class="form-group">
                            <input type="hidden" class="form-control" name="srf_id" value="{{ $sampleRequest->Id }}">
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-sm btn-primary addSrfFile"><i class="ti-plus"></i></button>
                            <button type="button" class="btn btn-sm btn-danger deleteRowBtn" hidden><i class="ti-trash"></i></button>
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
                    <label for="name"><b>Name</b></label>
                    <input type="text" name="name[]" class="form-control" placeholder="">
                </div>
                <div class="form-group">
                    <label for="srf_file"><b>Browse Files</b></label>
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
    
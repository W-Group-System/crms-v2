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
                <form method="POST" enctype="multipart/form-data" action="{{ url('rpeFiles') }}">
                    @csrf
                    <div class="rpe-file">
                        <div class="form-group">
                            <label for="name"><b>Name</b></label>
                            <input type="text" name="name[]" class="form-control" id="name" placeholder="">
                        </div>
                        <div class="form-group">
                            <label for=""><b>Browse Files</b></label>
                            <input type="file" class="form-control" id="rpe_id" name="rpe_file[]" multiple>
                        </div>
                        <div class="form-group">
                            <input type="hidden" class="form-control" name="rpe_id" value="{{ $requestEvaluation->id }}">
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-sm btn-primary addRpeFile"><i class="ti-plus"></i></button>
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
        function addRpeFileForm() {
            var newProductForm = `
            <div class="rpe-file">
                <div class="form-group">
                    <label for="name"><b>Name</b></label>
                    <input type="text" name="name[]" class="form-control" placeholder="">
                </div>
                <div class="form-group">
                    <label for="rpe_file"><b>Browse Files</b></label>
                    <input type="file" class="form-control" name="rpe_file[]" multiple>
                </div>
                <div class="form-group">
                    <input type="hidden" class="form-control" name="rpe_id" value="{{ $requestEvaluation->id }}">
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-sm btn-primary addRpeFile"><i class="ti-plus"></i></button>
                    <button type="button" class="btn btn-sm btn-danger deleteRowBtn"><i class="ti-trash"></i></button>
                </div>
            </div>`;
    
            $('.rpe-file').last().find('.addRpeFile').hide();
            $('.rpe-file').last().find('.deleteRowBtn').show();
            $('.rpe-file').last().after(newProductForm);
        }
    
        $(document).on('click', '.addRpeFile', function() {
            addRpeFileForm();
        });
    
        $(document).on('click', '.deleteRowBtn', function() {
            var currentRow = $(this).closest('.rpe-file');
            
            if ($('.rpe-file').length > 1) {
                if ($('.rpe-file').last().is(currentRow)) {
                    currentRow.prev().find('.addRpeFile').show();
                    currentRow.prev().find('.deleteRowBtn').show();
                }
                currentRow.remove();
            }
            
            if ($('.rpe-file').length === 1) {
                $('.rpe-file').find('.addRpeFile').show();
                $('.rpe-file').find('.deleteRowBtn').hide();
            }
        });
    
        $(document).on('change', 'input[type="file"]', function() {
            var filename = $(this).val().split('\\').pop();
            $(this).closest('.rpe-file').find('input[name="name[]"]').val(filename);
        });
    
        if ($('.rpe-file').length === 1) {
            $('.rpe-file').find('.deleteRowBtn').hide();
        }
    });
    </script>
    
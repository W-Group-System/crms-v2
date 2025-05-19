<div class="modal fade" id="uploadPrfFile" tabindex="-1" role="dialog" aria-labelledby="PRF File" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="Prf File">Upload PRF File</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_price_request" enctype="multipart/form-data" action="{{ url('/prfFilesUpload') }}" onsubmit="show()">
                    @csrf
                    <div class="prf-file">
                        <div class="form-group">
                            <label for="name"><b>Name</b></label>
                            <input type="text" name="name[]" class="form-control" id="name" placeholder="">
                        </div>
                        <div class="form-group">
                            <label ><b>Browse Files</b></label>
                            <input type="file" class="form-control" id="prf_file" name="prf_file[]">
                        </div>
                        <div class="form-group">
                            <input type="hidden" class="form-control" name="prf_id" value="{{ $price_monitorings->id }}">
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-sm btn-primary addPrfFile"><i class="ti-plus"></i></button>
                            <button type="button" class="btn btn-sm btn-danger deleteRowBtn" hidden><i class="ti-trash"></i></button>
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
</div>

<script>
    $(document).ready(function() {
        function addPrfFileForm() {
            var newProductForm = `
            <div class="prf-file">
                <div class="form-group">
                    <label for="name"><b>Name</b></label>
                    <input type="text" name="name[]" class="form-control" placeholder="">
                </div>
                <div class="form-group">
                    <label for="prf_file"><b>Browse Files</b></label>
                    <input type="file" class="form-control" name="prf_file[]">
                </div>
                <div class="form-group">
                    <input type="hidden" class="form-control" name="prf_id" value="{{ $price_monitorings->id }}">
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-sm btn-primary addPrfFile"><i class="ti-plus"></i></button>
                    <button type="button" class="btn btn-sm btn-danger deleteRowBtn"><i class="ti-trash"></i></button>
                </div>
            </div>`;
    
            $('.prf-file').last().find('.addPrfFile').hide();
            $('.prf-file').last().find('.deleteRowBtn').show();
            $('.prf-file').last().after(newProductForm);
        }
    
        $(document).on('click', '.addPrfFile', function() {
            addPrfFileForm();
        });
    
        $(document).on('click', '.deleteRowBtn', function() {
            var currentRow = $(this).closest('.prf-file');
            
            if ($('.prf-file').length > 1) {
                if ($('.prf-file').last().is(currentRow)) {
                    currentRow.prev().find('.addPrfFile').show();
                    currentRow.prev().find('.deleteRowBtn').show();
                }
                currentRow.remove();
            }
            
            if ($('.prf-file').length === 1) {
                $('.prf-file').find('.addPrfFile').show();
                $('.prf-file').find('.deleteRowBtn').hide();
            }
        });
    
        $(document).on('change', 'input[type="file"]', function() {
            var filename = $(this).val().split('\\').pop();
            $(this).closest('.prf-file').find('input[name="name[]"]').val(filename);
        });
    
        if ($('.prf-file').length === 1) {
            $('.prf-file').find('.deleteRowBtn').hide();
        }

        
        $('#uploadPrfFile').on('hidden.bs.modal', function () {
        $('#form_price_request')[0].reset(); 
        $('.prf-file').not(':first').remove(); 
        $('.prf-file').find('.addPrfFile').show(); 
        $('.prf-file').find('.deleteRowBtn').hide(); 
    });
    });
    </script>
    
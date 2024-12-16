
<div class="modal fade" id="addSseAttachments" tabindex="-1" role="dialog" aria-labelledby="cancelModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload SSE File</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_sse_file" enctype="multipart/form-data" action="{{ url('uploadSseFiles') }}">
                    @csrf
                    <div class="sse_file">
                        <div class="row">
                            <div class="col-lg-12 mb-3">
                                <!-- <label for="name">Name</label> -->
                                <input type="hidden" name="name[]" class="form-control" id="name" placeholder="Enter Name">
                            </div>
                            @if(authCheckIfItsRnd(auth()->user()->department_id))
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Is Confidential:</label>
                                        <input type="hidden" name="is_confidential[0]" value="0"> <!-- Indexed name -->
                                        <input type="checkbox" name="is_confidential[0]" value="1"> <!-- Indexed name -->
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Is For Review:</label>
                                        <input type="hidden" name="is_for_review[0]" value="0"> <!-- Indexed name -->
                                        <input type="checkbox" name="is_for_review[0]" value="1"> <!-- Indexed name -->
                                    </div>
                                </div>
                            @endif
                            <div class="col-lg-12 mb-3">
                                <label for="sse_file">Browse Files</label>
                                <input type="file" class="form-control" id="sse_file" name="sse_file[]">
                            </div>
                            <div class="col-lg-12">
                                <input type="hidden" class="form-control" name="sse_id" value="{{ $data->id }}">
                            </div>
                            <div class="col-lg-12">
                                <button type="button" class="btn btn-sm btn-primary addSseFile"><i class="ti-plus"></i></button>
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
        function addSseForm() {
            const rowIndex = $('.sse_file').length; // Get the current number of rows to set a unique index

            const newProductForm = `
                <div class="sse_file">
                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <input type="hidden" name="name[]" class="form-control" placeholder="Enter Name">
                        </div>
                        @if(authCheckIfItsRnd(auth()->user()->department_id))
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Is Confidential:</label>
                                    <input type="hidden" name="is_confidential[${rowIndex}]" value="0">
                                    <input type="checkbox" name="is_confidential[${rowIndex}]" value="1">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Is For Review:</label>
                                    <input type="hidden" name="is_for_review[${rowIndex}]" value="0">
                                    <input type="checkbox" name="is_for_review[${rowIndex}]" value="1">
                                </div>
                            </div>
                        @endif
                        <div class="col-lg-12 mb-3">
                            <label for="sse_file">Browse Files</label>
                            <input type="file" class="form-control" name="sse_file[${rowIndex}]">
                        </div>
                        <div class="col-lg-12">
                            <input type="hidden" class="form-control" name="sse_id" value="{{ $data->id }}">
                        </div>
                        <div class="col-lg-12">
                            <button type="button" class="btn btn-sm btn-primary addSseFile"><i class="ti-plus"></i></button>
                            <button type="button" class="btn btn-sm btn-danger deleteRowBtn" hidden><i class="ti-trash"></i></button>
                        </div>
                    </div>
                </div>`;

            $('.sse_file').last().find('.addSseFile').hide();
            $('.sse_file').last().find('.deleteRowBtn').show();
            $('.sse_file').last().after(newProductForm);
        }
    
        $(document).on('click', '.addSseFile', function() {
            addSseForm();
        });
    
        $(document).on('click', '.deleteRowBtn', function() {
            var currentRow = $(this).closest('.sse_file');
            
            if ($('.sse_file').length > 1) {
                if ($('.sse_file').last().is(currentRow)) {
                    currentRow.prev().find('.addSseFile').show();
                    currentRow.prev().find('.deleteRowBtn').show();
                }
                currentRow.remove();
            }
            
            if ($('.sse_file').length === 1) {
                $('.sse_file').find('.addSseFile').show();
                $('.sse_file').find('.deleteRowBtn').hide();
            }
        });
    
        $(document).on('change', 'input[type="file"]', function() {
            var filename = $(this).val().split('\\').pop();
            $(this).closest('.sse_file').find('input[name="name[]"]').val(filename);
        });
    
        if ($('.sse_file').length === 1) {
            $('.sse_file').find('.deleteRowBtn').hide();
        }
    });
</script>
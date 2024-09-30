<div class="modal fade" id="editPrfFile{{ $fileupload->Id }}" tabindex="-1" role="dialog" aria-labelledby="PRF File Update" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="prfFile">Edit PRF File</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_price_request" enctype="multipart/form-data" action="{{ url('updatePrfFile/'.$fileupload->Id) }}" onsubmit="show()">
                    @csrf
                    <div>
                        <div class="form-group">
                            <label for="name"><b>Name</b></label>
                            <input type="text" name="name" id="prfFileName" class="form-control" value="{{ $fileupload->Name }}">
                        </div>
                        <div class="form-group">
                            <label for="prf_file"><b>Browse Files</b></label>
                            <input type="file" class="form-control file" name="prf_file">
                        </div>
                        <div class="form-group">
                            <input type="hidden" class="form-control" name="prf_id" value="{{ $price_monitorings->id }}">
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
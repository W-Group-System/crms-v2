<div class="modal fade" id="filesModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Files</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addFilesForm" method="POST" action="{{ url('add_files') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="ClientId" value="{{ $data->id }}">
                    <div id="file-rows">
                        <div class="form-group">
                            <label for="FileName">Name</label>
                            <input type="text" class="form-control" name="FileName[]" placeholder="Enter Name">
                        </div>
                        <div class="form-group">
                            <label for="Path">Path</label>
                            <input type="file" class="form-control" name="Path[]" placeholder="Enter Description">
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary addRowBtn">Add Row</button>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    $(document).ready(function() {
        $('.addRowBtn').click(function() {
            var newRow = $('<div class="form-group-container">'+
                                '<div class="form-group col-md-12" align="right">' +
                                    '<button type="button" class="btn btn-danger deleteRowBtn">Delete Row</button>' +
                                '</div>' +
                                '<div class="form-group">'+
                                    '<label for="FileName">Name</label>'+
                                    '<input type="text" class="form-control" name="FileName[]" placeholder="Enter Name">'+
                                '</div>'+
                                '<div class="form-group">'+
                                    '<label for="Path">Path</label>'+
                                    '<input type="file" class="form-control" name="Path[]" placeholder="Enter Description">'+
                                '</div>'+
                            '</div>');
            newRow.appendTo('#file-rows');

            // Attach the delete event to the new row's delete button
            newRow.find('.deleteRowBtn').click(function() {
                $(this).closest('.form-group-container').remove();
            });
        });

        $('#addFilesForm').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Success!',
                            icon: 'success',
                            text: response.success,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.reload(); // Optionally reload the page
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: response.error,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(response) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'There was an error processing your request.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
    });
</script>

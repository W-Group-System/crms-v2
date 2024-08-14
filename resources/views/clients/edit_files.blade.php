@foreach ($data->files as $file)
<div class="modal fade" id="edit_file-{{ $file->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit File</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="fileFormEdit-{{ $file->id }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="ClientId" value="{{ $data->id }}">
                    <div id="file-rows">
                        <div class="form-group">
                            <label for="FileName">Name</label>
                            <input type="text" class="form-control" name="FileName" placeholder="Enter Name" value="{{ $file->FileName }}" required>
                        </div>
                        <div class="form-group">
                            <label for="Path">Path</label>
                            <input type="file" class="form-control" name="Path">
                            @if($file->Path)
                                <a href="{{ url($file->Path) }}" target="_blank" download>{{ $file->Path }}</a>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#fileFormEdit-{{ $file->id }}').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var formData = new FormData(this);
            var url = "{{ url('edit_file', ['id' => $file->id]) }}"; // Correct URL with file ID

            $.ajax({
                type: "POST",
                url: url,
                data: formData, // Pass FormData object
                processData: false, // Prevent jQuery from automatically processing data
                contentType: false, 
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.success,
                        showConfirmButton: false,
                        timer: 2000
                    }).then(function() {
                        location.reload();
                    });
                },
                error: function(response) {
                    let errorMessage = response.responseJSON.error;
                    if (typeof errorMessage === 'object') {
                        errorMessage = Object.values(errorMessage).join(', ');
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage,
                    });
                }
            });
        });
    });
</script>
@endforeach

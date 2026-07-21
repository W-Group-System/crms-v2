<div class="modal fade" id="remarks{{$data->id}}" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Noted By</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="salesNotedByForm" method="POST" action="{{url('cc_noted/' . $data->id)}}" enctype="multipart/form-data" onsubmit="show()">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Remarks</label>
                                <textarea class="form-control" name="remarks" id="remarks" cols="30" rows="10"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer mt-3">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-outline-primary" id="btnNotedBySubmit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#salesNotedByForm').submit(function (e) { 
            e.preventDefault();
            var actionUrl = $(this).attr('action'); 
            console.log(actionUrl);
            
            var btn = $('#btnNotedBySubmit');
            btn.prop('disabled', true).text('Processing...');
            

            $.ajax({
                url: actionUrl,
                type: 'POST',
                data: $(this).serialize(), 
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: "Noted",
                            text: response.message,
                            icon: "success",
                            showConfirmButton: false,
                            customClass: 'swal-wide',
                            timer: 1500
                        }).then(function () {
                            window.location.reload(); 
                        });
                    }
                }
            }); 
        });
    });
</script>

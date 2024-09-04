<div class="modal fade" id="editSalesFiles{{$file->Id}}" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Edit Sales Files</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form method="POST" action="{{url('update_sales_files/'.$file->Id)}}" enctype="multipart/form-data" onsubmit="show()">
                {{csrf_field()}}
                
                <div class="modal-body" style="padding: 20px">
                    <div class="row">
                        <div class="col-lg-12">
                            <label>Upload File</label>
                            <input type="file" name="file" id="file" class="form-control form-control-sm">
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="padding: 0.6875rem">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" id="yes_button" class="btn btn-success">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
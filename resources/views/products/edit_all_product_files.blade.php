<div class="modal fade" id="updateAllFiles" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Update All Files</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{url('update_all_files')}}" enctype="multipart/form-data" onsubmit="show()">
                {{csrf_field()}}
                <input type="hidden" name="product_id" value="{{$data->id}}">
                <div class="modal-body" style="padding: 20px">
                    <div class="product_files_container ">
                        
                    </div>
                    <button class="btn btn-sm btn-success mb-3 addBtnFiles" type="button">
                        <i class="ti-plus"></i>
                    </button>  
                </div>
                <div class="modal-footer" style="padding: 0.6875rem">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-outline-success">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
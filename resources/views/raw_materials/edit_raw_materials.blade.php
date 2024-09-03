<div class="modal fade" id="editRawMaterials{{$rm->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Raw Material</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_raw_material" action="{{url('raw_materials_update/'.$rm->id)}}" onsubmit="show()">
                    
                    @csrf
                    <div class="form-group">
                        <label for="name">Material</label>
                        <input type="text" class="form-control" id="Name" name="Name" placeholder="Enter Material" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Description</label>
                        <textarea type="text" rows="3" class="form-control" id="Description" name="Description" placeholder="Enter Description" required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-success" value="Save">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="formProductSubcategories-{{$sub->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Product Subcategories</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_product_subcategories" enctype="multipart/form-data" action="{{ url('update_product_subcategories/'.$sub->id) }}">
                    <span id="form_result"></span>
                    @csrf
                    <div class="form-group">
                        <label>Application</label>
                        <select class="form-control js-example-basic-single" name="ProductApplicationId" style="position: relative !important" title="Select Type" required>
                            <option value="" disabled selected>Select Application</option>
                            @foreach($productapp as $productapps)
                                <option value="{{ $productapps->id }}" {{$productapps->id == $sub->ProductApplicationId ? 'selected' : ''}}>{{ $productapps->Name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">Subcategory</label>
                        <input type="text" class="form-control" id="Name" name="Name" placeholder="Enter Subcategory" value="{{$sub->Name}}" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Description</label>
                        <input type="text" class="form-control" id="Description" name="Description" placeholder="Enter Description" value="{{$sub->Description}}" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" id="action_button" class="btn btn-success" value="Save">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
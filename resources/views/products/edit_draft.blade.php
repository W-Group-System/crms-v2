<div class="modal fade" id="formProduct-{{$p->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit New Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" class="edit_form_product" action="{{url('update_product/'.$p->id)}}">
                    <span id="update_form_result"></span>
                    @csrf
                    <div class="form-group">
                        <label for="name">DDW Number</label>
                        <input type="text" class="form-control" id="ddw_number" name="ddw_number" placeholder="Enter DDW Number" value="{{$p->ddw_number}}" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Product Code</label>
                        <input type="text" class="form-control" id="code" name="code" placeholder="Enter Product Code" value="{{$p->code}}" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Reference Number</label>
                        <input type="text" class="form-control" id="reference_no" name="reference_no" placeholder="Enter Reference Number" value="{{$p->reference_no}}" required>
                    </div>
                    <div class="form-group">
                        <label>Type</label>
                        <select class="form-control js-example-basic-single" name="type" style="position: relative !important" title="Select Type" required>
                            <option value="" disabled selected>Select Type</option>
                            <option value="1" @if($p->type == 1) selected @endif>Pure</option>
                            <option value="2" @if($p->type == 2) selected @endif>Blend</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Application</label>
                        <select class="form-control js-example-basic-single" name="application_id" style="position: relative !important" title="Select Application" required>
                            <option value="" disabled selected>Select Application</option>
                            @foreach($product_applications as $product_application)
                                <option value="{{ $product_application->id }}" @if($product_application->id == $p->application_id)selected @endif>{{ $product_application->Name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Application Subcategory</label>
                        <select class="form-control js-example-basic-single" name="application_subcategory_id" style="position: relative !important" title="Select Subcategory">
                            <option value="" disabled selected>Select Subcategory</option>
                            @foreach($product_subcategories as $product_subcategory)
                                <option value="{{ $product_subcategory->id }}" @if($product_subcategory->id == $p->application_subcategory_id) selected @endif>{{ $product_subcategory->Name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">Product Origin</label>
                        <input type="text" class="form-control" id="product_origin" name="product_origin" placeholder="Enter Product Origin" value="{{$p->product_origin}}">
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="action" id="action" value="Save">
                        <input type="hidden" name="hidden_id" id="hidden_id">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" name="action_button" id="action_button" class="btn btn-outline-success" value="Save">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
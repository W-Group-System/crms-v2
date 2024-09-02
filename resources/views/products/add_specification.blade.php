<div class="modal fade" id="specification" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Add Specification</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{url('add_specification')}}">
                {{csrf_field()}}
                
                <input type="hidden" name="product_id" value="{{$data->id}}">
                <div class="modal-body" style="padding: 20px">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group mb-2">
                                <label>Parameter</label>
                                <input type="text" name="parameter" class="form-control form-control-sm" placeholder="Enter parameter" required>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group mb-2">
                                <label>Specification</label>
                                <input type="text" name="specification" class="form-control form-control-sm" placeholder="Enter specification" required>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group mb-2">
                                <label>Testing Condition</label>
                                <input type="text" name="testing_condition" class="form-control form-control-sm" placeholder="Enter testing condition" required>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group mb-2">
                                <label>Remarks</label>
                                <textarea name="remarks" class="form-control form-control-sm" cols="30" rows="10" placeholder="Enter remarks" required></textarea>
                            </div>
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
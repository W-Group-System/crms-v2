<div class="modal fade" id="updateAll" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Update All Specification</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{url('update_all_product_specification')}}">
                {{csrf_field()}}
                <input type="hidden" name="product_id" value="{{$data->id}}">
                <div class="modal-body" style="padding: 20px">
                    <button class="btn btn-sm btn-success mb-3 addBtnSpecification" type="button">
                        <i class="ti-plus"></i>
                    </button>
                    <button class="btn btn-sm btn-danger mb-3" type="button" id="removeBtnSpecification">
                        <i class="ti-minus"></i>
                    </button>
                    
                    <div class="specification-container ">
                        @foreach ($data->productSpecification as $ps)
                            <fieldset class="border border-primary p-3 mb-3">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label>Parameter :</label>
                                        <input type="text" name="parameter[]" class="form-control form-control-sm" value="{{$ps->Parameter}}" required>
                                    </div>
                                    <div class="col-lg-6">
                                        <label>Testing Condition :</label>
                                        <input type="text" name="testing_condition[]" class="form-control form-control-sm" value="{{$ps->TestingCondition}}" required>
                                    </div>
                                    <div class="col-lg-6">
                                        <label>Specification :</label>
                                        <input type="text" name="specification[]" class="form-control form-control-sm" value="{{$ps->Specification}}" required> 
                                    </div>
                                    <div class="col-lg-6">
                                        <label>Remarks :</label>
                                        <input type="text" name="remarks[]" class="form-control form-control-sm" value="{{$ps->Remarks}}">
                                    </div>
                                </div>
                            </fieldset>
                        @endforeach
                    </div>
                    
                </div>
                <div class="modal-footer" style="padding: 0.6875rem">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
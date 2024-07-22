<div class="modal fade" id="specification-{{$ps->Id}}" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Edit Specification</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{url('edit_specification/'.$ps->Id)}}">
                {{csrf_field()}}
                
                <input type="hidden" name="product_id" value="{{$ps->Id}}">
                <div class="modal-body" style="padding: 20px">
                    <div class="row">
                        <div class="col-lg-12">
                            Parameter :
                            <input type="text" name="parameter" class="form-control form-control-sm" placeholder="Enter parameter" value="{{$ps->Parameter}}" required>
                        </div>
                        <div class="col-lg-12">
                            Specification :
                            <input type="text" name="specification" class="form-control form-control-sm" placeholder="Enter specification" value="{{$ps->Specification}}" required>
                        </div>
                        <div class="col-lg-12">
                            Testing Condition :
                            <input type="text" name="testing_condition" class="form-control form-control-sm" placeholder="Enter testing condition" value="{{$ps->TestingCondition}}" required>
                        </div>
                        <div class="col-lg-12">
                            Remarks :
                            <textarea name="remarks" class="form-control form-control-sm" cols="30" rows="10" placeholder="Enter remarks" required>{{$ps->Remarks}}</textarea>
                        </div>
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
<div class="modal fade" id="pdsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Add PDS</h5>
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
                            Company :
                            <select data-placeholder="Choose company" name="company" class="js-example-basic-single form-control form-control-sm">
                                <option value="">-Company-</option>
                                @foreach ($client as $c)
                                    <option value="{{$c->id}}">{{$c->Name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-12">
                            Control Number :
                            <input type="text" name="specification" class="form-control form-control-sm" placeholder="Enter specification" required>
                        </div>
                        <div class="col-lg-12">
                            Testing Condition :
                            <input type="text" name="testing_condition" class="form-control form-control-sm" placeholder="Enter testing condition" required>
                        </div>
                        <div class="col-lg-12">
                            Remarks :
                            <textarea name="remarks" class="form-control form-control-sm" cols="30" rows="10" placeholder="Enter remarks" required></textarea>
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
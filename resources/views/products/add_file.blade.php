<div class="modal fade" id="file" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Add File</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{url('add_product_files')}}" enctype="multipart/form-data">
                {{csrf_field()}}
                
                <input type="hidden" name="product_id" value="{{$data->id}}">
                <div class="modal-body" style="padding: 20px">
                    <div class="row">
                        <div class="col-lg-12">
                            Name :
                            <input type="text" name="name" id="filename" class="form-control form-control-sm" placeholder="Enter name" required>
                        </div>
                        <div class="col-lg-12">
                            Description :
                            <input type="text" name="description" class="form-control form-control-sm" placeholder="Enter description" required>
                        </div>
                        <div class="col-lg-12">
                            Client :
                            <select name="client" class="js-example-basic-single form-control form-control-sm" required>
                                <option value="">-Client-</option>
                                @foreach ($client as $c)
                                    <option value="{{$c->id}}">{{$c->Name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-12">
                            Is Confidential :
                            <input type="checkbox" name="is_confidential">
                        </div>
                        <div class="col-lg-12">
                            Upload a file :
                            <input type="file" name="file" id="file" class="form-control form-control-sm" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="padding: 0.6875rem">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
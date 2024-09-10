<div class="modal fade" id="file" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Add File</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{url('add_product_files')}}" enctype="multipart/form-data" onsubmit="show()">
                {{csrf_field()}}
                
                <input type="hidden" name="product_id" value="{{$data->id}}">
                <div class="modal-body" style="padding: 20px">
                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <label>Name</label>
                            <input type="text" name="name" id="filename" class="form-control form-control-sm" placeholder="Enter name" required>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label>Description</label>
                            {{-- <input type="text" name="description" class="form-control form-control-sm" placeholder="Enter description" required> --}}
                            <textarea name="description" class="form-control" cols="30" rows="10" placeholder="Enter description" required></textarea>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label>Client</label>
                            <select name="client" class="js-example-basic-single form-control form-control-sm">
                                <option value="">-Client-</option>
                                @foreach ($client as $c)
                                    <option value="{{$c->id}}">{{$c->Name}}</option>
                                @endforeach
                            </select>
                        </div>
                        @if(auth()->user()->role->name == "Staff L2" || auth()->user()->role->name == "Department Admin")
                        <div class="col-lg-12 mb-3">
                            <label>Is Confidential</label>
                            <input type="checkbox" name="is_confidential">
                        </div>
                        @else
                        <div class="col-lg-12 mb-3">
                            <label>Is Confidential</label>
                            <input type="checkbox" name="is_confidential" checked readonly>
                        </div>
                        @endif
                        <div class="col-lg-12 mb-3">
                            <label>Upload a file</label>
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
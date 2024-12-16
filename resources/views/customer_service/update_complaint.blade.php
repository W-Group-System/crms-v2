<div class="modal fade" id="update{{$data->id}}">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Complaint</h5>
            </div>
            <form method="POST" action="{{url('cc_upload/'.$data->id)}}" enctype="multipart/form-data" onsubmit="show()">
                @csrf 

                <div class="modal-body">
                    <div class="form-group mb-2">
                        Upload Attachments
                        <input type="file" name="file[]" class="form-control" multiple required>
                    </div>
                    <div class="form-group mb-2">
                        Department
                        <select data-placeholder="Select department" name="department" class="form-control js-example-basic-single">
                            <option value=""></option>
                            @foreach ($concern_department as $key=>$ccdept)
                            <option value="{{$key}}" @if($key == $data->Department) selected @endif>{{$ccdept}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
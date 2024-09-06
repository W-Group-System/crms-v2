<div class="modal fade" id="updateAllFiles" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Update All Files</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{url('update_all_files')}}" enctype="multipart/form-data" onsubmit="show()">
                {{csrf_field()}}
                <input type="hidden" name="product_id" value="{{$data->id}}">
                <div class="modal-body" style="padding: 20px">
                    <button class="btn btn-sm btn-success mb-3 addBtnFiles" type="button">
                        <i class="ti-plus"></i>
                    </button>
                    <div class="product_files_container ">
                        @foreach ($data->productFiles as $pf)
                            <div class="row">
                                <div class="col-lg-10">
                                    <fieldset class="border border-primary p-3 mb-3">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <label>Name :</label>
                                                <input type="text" name="name[]" class="form-control form-control-sm" value="{{$pf->Name}}" required>
                                            </div>
                                            <div class="col-lg-6">
                                                <label>Client :</label>
                                                <select name="client[]" class="js-example-basic-single form-control form-control-sm" required>
                                                    <option value="">-Client-</option>
                                                    @foreach ($client as $c)
                                                        <option value="{{$c->id}}" @if($c->id == $pf->ClientId) selected @endif>{{$c->Name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-6">
                                                <label>Description :</label>
                                                {{-- <input type="text" name="description[]" class="form-control form-control-sm" value="{{$pf->Description}}" required>  --}}
                                                <textarea name="description[]" class="form-control" cols="30" rows="10">{{$pf->Description}}</textarea>
                                            </div>
                                            <div class="col-lg-6">
                                                <label>Is Confidential :</label>
                                                <input type="checkbox" name="is_confidential[]" @if($pf->IsConfidential == 1) checked @endif> 
                                            </div>
                                            <div class="col-lg-6">
                                                <label>File :</label>
                                                <input type="file" name="files[]" id="file" class="form-control form-control-sm" required>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-lg-2">
                                    <button class="btn btn-sm btn-danger mb-3 removeBtnFiles" type="button" >
                                        <i class="ti-minus"></i>
                                    </button>
                                </div>
                            </div>
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
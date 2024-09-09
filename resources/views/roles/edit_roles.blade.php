<div class="modal fade" id="editRole-{{$role->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Role</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_role" enctype="multipart/form-data" action="{{url('update_role/'.$role->id)}}" onsubmit="show()">
                    @csrf
                    <div class="form-group">
                        <label for="department">Department</label>
                        <select name="department" class="js-example-basic-single form-control departmentSelectOption">
                            <option value="">-Department-</option>
                            @foreach ($department as $dpt)
                                <option value="{{$dpt->id}}" @if($dpt->id == $role->department_id) selected @endif>{{$dpt->department_code.' - '.$dpt->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">Name</label>
                        {{-- <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="{{$role->name}}" required> --}}
                        <select class="form-control js-example-basic-single" name="name" required>
                            <option disabled selected value>-Select Name-</option>
                            <option value="Staff L1" @if($role->name == "Staff L1") selected @endif>Staff L1</option>
                            <option value="Staff L2" @if($role->name == "Staff L2") selected @endif>Staff L2</option>
                            <option value="Department Admin" @if($role->name == "Department Admin") selected @endif>Department Admin</option>
                            <option value="Accounting" @if($role->name == "Accounting") selected @endif>Accounting</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">Description</label>
                        <input type="text" class="form-control" id="description" name="description" placeholder="Enter Description" value="{{$role->description}}" required>
                    </div>
                    <div class="type-container">
                        @if($role->department_id == 5 || $role->department_id == 38)
                        <div class="form-group">
                            <label for="name">Type</label>
                            <select name="type" class="form-control js-example-basic-single form-control">
                                <option disabled selected value>Select Type</option>
                                <option value="LS" @if($role->type == 'LS') selected @endif>Local Sales</option>
                                <option value="IS" @if($role->type == 'IS') selected @endif>International Sales</option>
                            </select>
                        </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-success" value="Save">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
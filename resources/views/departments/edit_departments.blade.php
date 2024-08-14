<div class="modal fade" id="editDepartment-{{$dept->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Department</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>               
            </div>
            <div class="modal-body">
                <form method="POST" class="update_form_department" enctype="multipart/form-data" action="{{ url('update_department/'.$dept->id) }}">
                    <div class="alert"></div>
                    @csrf
                    <div class="form-group">
                        <label>Company</label>
                        <select class="form-control js-example-basic-single" name="company_id" style="position: relative !important" title="Select Company" required>
                            <option value="" disabled selected>Select Company</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">Code</label>
                        <input type="text" class="form-control" id="code" name="code" placeholder="Enter Code" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Description</label>
                        <input type="text" class="form-control" id="description" name="description" placeholder="Enter Description" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="changePassword-{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Change Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_user" action="{{url('user_change_password/'.$user->id)}}">
                    @csrf
                    <div class="form-group" id="formPasword">
                        <label for="name">Password</label>
                        <input type="password" class="form-control" name="password" placeholder="Enter Password" required>
                    </div>
                    <div class="form-group" id="formPasword">
                        <label for="name">Confirm Password</label>
                        <input type="password" class="form-control" name="password_confirmation" placeholder="Enter Password" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
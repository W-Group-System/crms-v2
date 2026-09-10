<div class="modal fade"
     id="deleteRole-{{ $type->id }}"
     tabindex="-1"
     role="dialog"
     aria-labelledby="deleteRoleLabel-{{ $type->id }}"
     aria-hidden="true">

    <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title"
                    id="deleteRoleLabel-{{ $type->id }}">
                    Delete Role Type
                </h5>

                <button type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <form method="POST"
                  action="{{ route('delete_type', $type->id) }}">

                @csrf
                @method('DELETE')

                <div class="modal-body">

                    <p>
                        Are you sure you want to delete this role type?
                    </p>

                    <div class="form-group">
                        <label>Role Type</label>

                        <input type="text"
                               class="form-control"
                               value="{{ $type->roleType }}"
                               readonly>
                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit"
                            class="btn btn-danger">
                        Delete
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
<div class="modal fade" id="editRole-{{ $type->id }}" tabindex="-1" role="dialog" aria-labelledby="editRoleLabel-{{ $type->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="editRoleLabel-{{ $type->id }}">
                    Edit Role Type
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="POST" action="{{ route('update_type', $type->id) }}">
                @csrf
                @method('PUT')

                <div class="modal-body">
                    <div class="form-group">
                        <label>Role Type</label>
                        
                       <input type="text"
                        class="form-control {{ $errors->has('roleType') && session('edit_modal_id') == $type->id ? 'is-invalid' : '' }}"
                        name="roleType"
                        value="{{ session('edit_modal_id') == $type->id ? old('roleType') : $type->roleType }}"
                        placeholder="Enter role type"
                        required>

                        <!-- Only show error if it belongs to this specific modal -->
                        @if ($errors->has('roleType') && session('edit_modal_id') == $type->id)
                            <div class="invalid-feedback">
                                {{ $errors->first('roleType') }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Update
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

@if (session('edit_modal_id') == $type->id)
<script>
    $(document).ready(function () {
        $('#editRole-{{ $type->id }}').modal('show');
    });
</script>
@endif
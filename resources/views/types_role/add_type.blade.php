<div class="modal fade" id="formType" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Type Role</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="POST" action="{{ route('add_type') }}">
                @csrf

                <div class="modal-body">
                    <div id="roleTypeContainer">
                        @if(old('roleType') && is_array(old('roleType')))
                            @foreach(old('roleType') as $index => $oldValue)
                                <div class="form-row role-type-row mb-2">
                                    <div class="col">
                                        <label>Role Type</label>
                                        <input type="text"
                                               class="form-control {{ $errors->has('roleType.' . $index) ? 'is-invalid' : '' }}"
                                               name="roleType[]"
                                               value="{{ $oldValue }}"
                                               placeholder="Enter role type"
                                               required>

                                        @if ($errors->has('roleType.' . $index))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('roleType.' . $index) }}
                                            </div>
                                        @endif
                                    </div>

                                    <div class="col-auto d-flex align-items-end">
                                        <button type="button"
                                                class="btn btn-danger remove-row"
                                                style="{{ count(old('roleType')) === 1 ? 'display: none;' : '' }}">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <!-- Single row-->
                            <div class="form-row role-type-row mb-2">
                                <div class="col">
                                    <label>Role Type</label>
                                    <input type="text"
                                           class="form-control"
                                           name="roleType[]"
                                           placeholder="Enter role type"
                                           required>
                                </div>

                                <div class="col-auto d-flex align-items-end">
                                    <button type="button"
                                            class="btn btn-danger remove-row"
                                            style="display: none;">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>

                    <button type="button" id="addRow" class="btn btn-success btn-sm mt-2">
                        <i class="fas fa-plus"></i> Add Row
                    </button>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Save
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<!-- Auto-reopen Add Modal if validation fails -->
@if (session('show_add_modal'))
<script>
    $(document).ready(function () {
        $('#formType').modal('show');
    });
</script>
@endif

<script>
    $(document).ready(function () {
        // Add new row
        $('#addRow').click(function () {
            let row = `
                <div class="form-row role-type-row mb-2">
                    <div class="col">
                        <input type="text"
                               class="form-control"
                               name="roleType[]"
                               placeholder="Enter role type"
                               required>
                    </div>

                    <div class="col-auto">
                        <button type="button"
                                class="btn btn-danger remove-row">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>
                </div>
            `;

            $('#roleTypeContainer').append(row);
            updateRemoveButtons();
        });

        // Remove row
        $(document).on('click', '.remove-row', function () {
            $(this).closest('.role-type-row').remove();
            updateRemoveButtons();
        });

        // Hide remove button when only one row remains
        function updateRemoveButtons() {
            let rows = $('.role-type-row');

            if (rows.length === 1) {
                rows.find('.remove-row').hide();
            } else {
                rows.find('.remove-row').show();
            }
        }
    });
</script>
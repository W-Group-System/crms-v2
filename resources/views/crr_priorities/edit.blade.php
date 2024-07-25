<div class="modal fade" id="editcrrPriority{{ $crrPriority->id }}" tabindex="-1" role="dialog" aria-labelledby="editCrrPriority" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editCrrPriorityLabel">CRR Priority</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method="POST" action="{{ url('update_crr_priority/' . $crrPriority->id ) }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                        <span id="form_result"></span>
                        @csrf
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="Name" name="Name" value="{{ $crrPriority->Name }}">
                        </div>
                        <div class="form-group">
                            <label for="name">Description</label>
                            <input type="text" class="form-control" id="Description" name="Description" value="{{ $crrPriority->Description }}">
                        </div>
                        <div class="form-group">
                            <label for="name">Day(s)</label>
                            <input type="text" class="form-control" id="Days" name="Days" value="{{ $crrPriority->Days }}">
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
<script src="{{ asset('js/sweetalert2.min.js') }}"></script>
<script>
     @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "{{ session('error') }}",
                confirmButtonText: 'OK'
            });
        @elseif(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: "{{ session('success') }}",
                confirmButtonText: 'OK'
            });
        @endif
</script>
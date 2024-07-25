<div class="modal fade" id="AddCrrPriority" tabindex="-1" role="dialog" aria-labelledby="addCategorization" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addCategorizationLabel">Categorization</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method="POST" action="{{ route('crr_priority.store') }}" onsubmit="show()" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                        <span id="form_result"></span>
                        @csrf
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="Name" name="Name" placeholder="Enter Name">
                        </div>
                        <div class="form-group">
                            <label for="name">Description</label>
                            <input type="text" class="form-control" id="Description" name="Description" placeholder="Enter Description">
                        </div>
                        <div class="form-group">
                            <label for="name">Day(s)</label>
                            <input type="text" class="form-control" id="Days" name="Days" placeholder="Enter Days">
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
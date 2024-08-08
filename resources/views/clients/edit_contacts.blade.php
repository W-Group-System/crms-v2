@foreach ($data->contacts as $contact)
<div class="modal fade" id="edit_contact-{{ $contact->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Contacts</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="contactFormEdit-{{ $contact->id }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="CompanyId" value="{{$data->id}}">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Name</label>
                            <input type="text" class="form-control" name="ContactName" placeholder="Enter Name" value="{{ $contact->ContactName }}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Designation</label>
                            <input type="text" class="form-control" name="Designation" placeholder="Enter Designation" value="{{ $contact->Designation }}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Birthday</label>
                            <input type="date" class="form-control" name="Birthday" value="{{ $contact->Birthday }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Telephone</label>
                            <input type="text" class="form-control" name="PrimaryTelephone" placeholder="Enter Primary Telephone" value="{{ $contact->PrimaryTelephone }}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Telephone 2</label>
                            <input type="text" class="form-control" name="SecondaryTelephone" placeholder="Enter Secondary Telephone" value="{{ $contact->SecondaryTelephone }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Mobile</label>
                            <input type="text" class="form-control" name="PrimaryMobile" placeholder="Enter Primary Mobile" value="{{ $contact->PrimaryMobile }}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Mobile 2</label>
                            <input type="text" class="form-control" name="SecondaryMobile" placeholder="Enter Secondary Mobile" value="{{ $contact->SecondaryMobile }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Email Address</label>
                            <input type="text" class="form-control" name="EmailAddress" placeholder="Enter Email Address" value="{{ $contact->EmailAddress }}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Skype</label>
                            <input type="text" class="form-control" name="Skype" placeholder="Enter Skype" value="{{ $contact->Skype }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Viber</label>
                            <input type="text" class="form-control" name="Viber" placeholder="Enter Viber" value="{{ $contact->Viber }}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>WhatsApp</label>
                            <input type="text" class="form-control" name="WhatsApp" placeholder="Enter WhatsApp" value="{{ $contact->WhatsApp }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Facebook</label>
                            <input type="text" class="form-control" name="Facebook" placeholder="Enter Facebook" value="{{ $contact->Facebook }}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>LinkedIn</label>
                            <input type="text" class="form-control" name="LinkedIn" placeholder="Enter LinkedIn" value="{{ $contact->LinkedIn }}">
                        </div>
                    </div>
                    <!-- Repeat for other fields as needed -->
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-danger deleteContact" title="Delete Client" data-id="{{ $contact->id }}">
                            <i class="ti-trash"></i>
                        </button>
                        <div>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $('#contactFormEdit-{{ $contact->id }}').submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var formData = new FormData(this);
        var url = "{{ url('edit_contact/' . $contact->id) }}"; // Correct URL with contact ID

        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.success,
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    $('#edit_contact-{{ $contact->id }}').modal('hide'); // Close the correct modal
                    location.reload();
                });
            },
            error: function(response) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.responseJSON.error.join(', ')
                });
            }
        });
    });
</script>
@endforeach

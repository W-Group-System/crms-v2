<div class="modal fade" id="contactsModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Contacts</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="contactForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="CompanyId" value="{{$data->id}}">
                    <div id="contactFieldsContainer">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Name</label>
                                <input type="text" class="form-control" name="ContactName[]" placeholder="Enter Name" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Designation</label>
                                <input type="text" class="form-control" name="Designation[]" placeholder="Enter Designation">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Birthday</label>
                                <input type="date" class="form-control" name="Birthday[]">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Telephone</label>
                                <input type="text" class="form-control" name="PrimaryTelephone[]" placeholder="Enter Primary Telephone">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Telephone 2</label>
                                <input type="text" class="form-control" name="SecondaryTelephone[]" placeholder="Enter Secondary Telephone">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Mobile</label>
                                <input type="text" class="form-control" name="PrimaryMobile[]" placeholder="Enter Primary Mobile">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Mobile 2</label>
                                <input type="text" class="form-control" name="SecondaryMobile[]" placeholder="Enter Secondary Mobile">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Email Address</label>
                                <input type="text" class="form-control" name="EmailAddress[]" placeholder="Enter Email Address">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Skype</label>
                                <input type="text" class="form-control" name="Skype[]" placeholder="Enter Skype">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Viber</label>
                                <input type="text" class="form-control" name="Viber[]" placeholder="Enter Viber">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>WhatsApp</label>
                                <input type="text" class="form-control" name="WhatsApp[]" placeholder="Enter WhatsApp">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Facebook</label>
                                <input type="text" class="form-control" name="Facebook[]" placeholder="Enter Facebook">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>LinkedIn</label>
                                <input type="text" class="form-control" name="LinkedIn[]" placeholder="Enter LinkedIn">
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary" id="addRowBtn">Add Row</button>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $('#addRowBtn').click(function() {
        var newRow = $('<div class="form-row form-group-container">' +
                            '<div class="form-group col-md-12" align="right">' +
                                '<button type="button" class="btn btn-danger deleteRowBtn">Delete Row</button>' +
                            '</div>' +
                            '<div class="form-group col-md-6">' +
                                '<label>Name</label>' +
                                '<input type="text" class="form-control" name="ContactName[]" placeholder="Enter Name" required>' +
                            '</div>' +
                            '<div class="form-group col-md-6">' +
                                '<label>Designation</label>' +
                                '<input type="text" class="form-control" name="Designation[]" placeholder="Enter Designation">' +
                            '</div>' +
                            '<div class="form-group col-md-6">' +
                                '<label>Birthday</label>' +
                                '<input type="date" class="form-control" name="Birthday[]">' +
                            '</div>' +
                            '<div class="form-group col-md-6">' +
                                '<label>Telephone</label>' +
                                '<input type="text" class="form-control" name="PrimaryTelephone[]" placeholder="Enter Primary Telephone">' +
                            '</div>' +
                            '<div class="form-group col-md-6">' +
                                '<label>Telephone 2</label>' +
                                '<input type="text" class="form-control" name="SecondaryTelephone[]" placeholder="Enter Secondary Telephone">' +
                            '</div>' +
                            '<div class="form-group col-md-6">' +
                                '<label>Mobile</label>' +
                                '<input type="text" class="form-control" name="PrimaryMobile[]" placeholder="Enter Primary Mobile">' +
                            '</div>' +
                            '<div class="form-group col-md-6">' +
                                '<label>Mobile 2</label>' +
                                '<input type="text" class="form-control" name="SecondaryMobile[]" placeholder="Enter Secondary Mobile">' +
                            '</div>' +
                            '<div class="form-group col-md-6">' +
                                '<label>Email Address</label>' +
                                '<input type="text" class="form-control" name="EmailAddress[]" placeholder="Enter Email Address">' +
                            '</div>' +
                            '<div class="form-group col-md-6">' +
                                '<label>Skype</label>' +
                                '<input type="text" class="form-control" name="Skype[]" placeholder="Enter Skype">' +
                            '</div>' +
                            '<div class="form-group col-md-6">' +
                                '<label>Viber</label>' +
                                '<input type="text" class="form-control" name="Viber[]" placeholder="Enter Viber">' +
                            '</div>' +
                            '<div class="form-group col-md-6">' +
                                '<label>WhatsApp</label>' +
                                '<input type="text" class="form-control" name="WhatsApp[]" placeholder="Enter WhatsApp">' +
                            '</div>' +
                            '<div class="form-group col-md-6">' +
                                '<label>Facebook</label>' +
                                '<input type="text" class="form-control" name="Facebook[]" placeholder="Enter Facebook">' +
                            '</div>' +
                            '<div class="form-group col-md-6">' +
                                '<label>LinkedIn</label>' +
                                '<input type="text" class="form-control" name="LinkedIn[]" placeholder="Enter LinkedIn">' +
                            '</div>' +
                        '</div>');

        newRow.insertBefore('#addRowBtn');

        
        // Attach the delete event to the new row's delete button
        newRow.find('.deleteRowBtn').click(function() {
            $(this).closest('.form-group-container').remove();
        });
    });

    $('#contactForm').submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var url = "{{ url('new_contact') }}";

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
                    $('#contactsModal').modal('hide');
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

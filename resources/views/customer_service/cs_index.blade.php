@extends('layouts.cs_header')
@section('content')
<!-- <div class="col-12 text-center">
    <img src="{{asset('images/whi.png')}}" style="width: 180px;" class="mt-3">
    <h2 class="header_h2 mt-2">Customer Service Application</h2>
</div> -->
<link href="{{ asset('css/filepond.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<div class="container">
    <div class="row justify-content-center">
        <img src="{{asset('images/whi.png')}}" style="width: 180px;" class="mt-3 mb-3">
        <div class="col-md-12">
            <div class="wrapper">
                <div class="row">
                    <div class="col-md-3">
                        <div class="dbox w-100 text-center">
                            <div class="icon d-flex align-items-center justify-content-center">
                                <span class="fa fa-map-marker"></span>
                            </div>
                            <div class="text">
                                <p><span>Address:</span>&nbsp;26th Floor, W Building, Fifth Avenue, Bonifacio Global City, Taguig City, Philippines 1634</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="dbox w-100 text-center">
                            <div class="icon d-flex align-items-center justify-content-center">
                                <span class="fa fa-phone"></span>
                            </div>
                            <div class="text">
                                <p><span>Phone:</span>&nbsp;(+632) 8856 3838</a></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="dbox w-100 text-center">
                            <div class="icon d-flex align-items-center justify-content-center">
                                <span class="fa fa-paper-plane"></span>
                            </div>
                            <div class="text">
                                <p><span>Email:</span> <a href="mailto:marketing@rico.com.ph">marketing@rico.com.ph</a></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="dbox w-100 text-center">
                            <div class="icon d-flex align-items-center justify-content-center">
                                <span class="fa fa-globe"></span>
                            </div>
                            <div class="text">
                                <p><span>Website</span> <a href="https://whydrocolloids.com/" target="_blank">www.whydrocolloids.com</a></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row no-gutters" style="background: #f7f9f9;">
                    <div class="col-md-7">
                        <div class="contact-wrap w-100 p-md-5 p-4">
                            <h3 class="mb-4">Leave us a message</h3>
                            <!-- <div id="form-message-warning" class="mb-4"></div> 
                            <div id="form-message-success" class="mb-4">
                            Your message was sent, thank you!
                            </div> -->
                            <b>What would you like to contact us about today?</b>
                            <p class="mt-3" style="font-weight: 600;color: #428bca;"><i>Select an option below</i></p>
                            <p class="gap-1 text-center">
                                <a href="#" id="btn_complaint" class="btn btn-outline-primary" role="button"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>&nbsp;Customer Complaint</a>
                                <a href="#" id="btn_satisfaction" class="btn btn-outline-primary" role="button"><i class="fa fa-smile-o" aria-hidden="true"></i>&nbsp;Customer Satisfaction</a>
                            </p>
                            <div id="form_satisfaction_container" style="display: none;">
                                <form id="form_satisfaction" method="POST" enctype="multipart/form-data" onsubmit="show()">
                                    @csrf
                                    <input type="hidden" name="CsNumber" value="{{ $newCsNo }}"> 
                                    <input type="hidden" name="Status" value="10">
                                    <input type="hidden" name="Progress" value="10">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="label">Customer Name</label>
                                                <input type="text" class="form-control" name="ContactName" id="ContactName" placeholder="Enter Customer Name" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6"> 
                                            <div class="form-group">
                                                <label class="label">Company Name</label>
                                                <input type="text" class="form-control" name="CompanyName" id="CompanyName" placeholder="Enter Company Name" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6"> 
                                            <div class="form-group">
                                                <label class="label">Contact Number</label>
                                                <input type="text" class="form-control" name="ContactNumber" id="ContactNumber" placeholder="Enter Contact Number">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="label">Email Address</label>
                                                <input type="email" class="form-control" name="Email" id="Email" placeholder="Enter Email Address" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6"> 
                                            <div class="form-group">
                                                <label class="label">Feedback Category</label>
                                                <select class="form-control js-example-basic-single" name="Category" id="Category" title="Select Category" required>
                                                    <option value="" disabled selected>Select Category</option>
                                                    @foreach($category as $data)
                                                        <option value="{{ $data->id }}" {{ old('Category') == $data->id ? 'selected' : '' }}>{{ $data->Name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="label">Attachments</label>
                                                <input type="file" class="form-control attachments" name="Path[]" id="Path" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                                            </div>
                                        </div> -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="label">Attachments</label>
                                                <input
                                                    type="file"
                                                    class="filepond"
                                                    name="Path[]"
                                                    id="Path"
                                                    multiple
                                                    accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="label" for="#">Customer Feedback</label>
                                                <textarea class="form-control" rows="5" name="Description" placeholder="Enter Description" required>{{ old('Description') }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12" align="right">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div id="form_complaint_container" style="display: none;">
                                <form id="form_complaint" method="POST" enctype="multipart/form-data" onsubmit="show()">
                                    @csrf
                                    <input type="hidden" name="CcNumber" value="{{ $newCcNo }}">
                                    <input type="hidden" name="Status" value="10">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="label">Customer Name</label>
                                                <input type="text" class="form-control" name="ContactName" id="ContactName" placeholder="Enter Customer Name" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6"> 
                                            <div class="form-group">
                                                <label class="label">Company Name</label>
                                                <input type="text" class="form-control" name="CompanyName" id="CompanyName" placeholder="Enter Company Name" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12"> 
                                            <div class="form-group">
                                                <label class="label">Address</label>
                                                <input type="text" class="form-control" name="Address" id="Address" placeholder="Enter Address">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="label">Email Address</label>
                                                <input type="email" class="form-control" name="Email" id="Email" placeholder="Enter Email Address" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="label">Telephone</label>
                                                <input type="text" class="form-control" name="Telephone" id="Telephone" placeholder="Enter Telephone">
                                            </div>
                                        </div>
                                        <div class="col-md-6"> 
                                            <div class="form-group">
                                                <label class="label">Country</label>
                                                <select class="form-control js-example-basic-single" name="Country" id="Country" title="Select Country">
                                                    <option value="" disabled selected>Select Country</option>
                                                    @foreach($countries as $data)
                                                        <option value="{{ $data->id }}" >{{ $data->Name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="label">Attachments</label>
                                                <input type="file" class="form-control attachments" name="Path[]" id="Path" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                                            </div>
                                        </div> -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="label">Attachments</label>
                                                <input
                                                    type="file"
                                                    class="filepond"
                                                    name="Path[]"
                                                    id="Path2"
                                                    multiple
                                                    accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="label" for="#">Customer Remarks</label>
                                                <textarea type="text" class="form-control" name="CustomerRemarks" id="CustomerRemarks" placeholder="Enter Customer Remarks" rows="5" required></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12" align="right">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 d-flex align-items-stretch">
                        <div class="info-wrap w-100 p-5 img" style="background-image: url(images/Seaweed_farm.jpg); background-size: cover;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- <div class="row text-center" style="margin-top: 5em">
    <div class="col-6 text-right">
        <a href="{{ url('/customer_complaint2') }}">
            <div class="btn button">Customer Complaint</div>  
        </a>
    </div>
    <div class="col-6 text-left">
        <a href="{{ url('/customer_satisfaction') }}">
            <div class="btn button">Customer Satisfaction</div>
        </a>
    </div>
</div> -->

<style>
@media (min-width: 576px) {
.container {
    max-width: 540px; } 
}
@media (min-width: 768px) {
.container {
    max-width: 720px; } 
}
@media (min-width: 992px) {
.container {
    max-width: 960px; } 
}
@media (min-width: 1200px) {
.container {
    max-width: 1140px; } 
}
.dbox {
  width: 100%;
  margin-bottom: 25px; 
}
@media (max-width: 767.98px) {
    .dbox {
        margin-bottom: 25px !important;
        padding: 0 20px; 
    } 
}
.dbox p span {
    font-weight: 600;
    color: #000; 
}
.dbox .icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #091d87;
    margin: 0 auto;
    margin-bottom: 20px; 
}
.dbox .icon span {
    font-size: 20px;
    color: #fff; 
}
.dbox .text {
    width: 100%; 
}

</style>
<script src="https://unpkg.com/filepond/dist/filepond.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const complaintBtn = document.getElementById("btn_complaint");
        const satisfactionBtn = document.getElementById("btn_satisfaction");

        const complaintForm = document.getElementById("form_complaint_container");
        const satisfactionForm = document.getElementById("form_satisfaction_container");

        // Default show one form (optional)
        // satisfactionForm.style.display = "block";

        complaintBtn.addEventListener("click", function (e) {
            e.preventDefault();
            complaintForm.style.display = "block";
            satisfactionForm.style.display = "none";

            // Optional: add active class to highlight button
            complaintBtn.classList.add("active");
            satisfactionBtn.classList.remove("active");
        });

        satisfactionBtn.addEventListener("click", function (e) {
            e.preventDefault();
            satisfactionForm.style.display = "block";
            complaintForm.style.display = "none";

            satisfactionBtn.classList.add("active");
            complaintBtn.classList.remove("active");
        });
    });

    $('#form_satisfaction').on('submit', function(event) {
        event.preventDefault();

        var formData = new FormData(this);
        var submitBtn = $("button[type='submit']");
        
        // **Disable the button and show loading**
        submitBtn.prop("disabled", true).html('<i class="fa fa-spinner fa-spin"></i> Submitting...');

        $.ajax({
            url: "{{ route('customer_satisfaction.store') }}",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('input[name="CsNumber"]').val(response.newCsNo);
                if (response.success) {
                    // Display a Swal success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Saved',
                        text: response.success,
                        timer: 2000,
                        showConfirmButton: false,
                    }).then(() => {
                        $('#form_satisfaction')[0].reset();
                        location.reload();
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Something went wrong. Please try again!',
                });
            },
            complete: function() {
                // **Re-enable the button after request is complete**
                submitBtn.prop("disabled", false).html('Submit');
            }
        });
    });

    $('#form_complaint').on('submit', function(event) {
        event.preventDefault();

        var formData = new FormData(this);
        var submitBtn = $("button[type='submit']");
        
        // **Disable the button and show loading**
        submitBtn.prop("disabled", true).html('<i class="fa fa-spinner fa-spin"></i> Submitting...');

        $.ajax({
            url: "{{ route('customer_complaint2.store') }}",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    // Display a Swal success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Saved',
                        text: response.success,
                        timer: 2000,
                        showConfirmButton: false
                    }).then((result) => {
                        $('#form_complaint')[0].reset();
                        location.reload();
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Something went wrong. Please try again!',
                });
            },
            complete: function() {
                // **Re-enable the button after request is complete**
                submitBtn.prop("disabled", false).html('Submit');
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        // Register plugins
        FilePond.registerPlugin(
            // FilePondPluginFileValidateType,
            FilePondPluginFileValidateSize,
            FilePondPluginImagePreview
        );

        // Create FilePond instance
        const pond = FilePond.create(document.querySelector('#Path'), {
            allowMultiple: true,
            maxFileSize: '10MB',

            server: {
                process: {
                    url: '{{ url("/upload-temp") }}',
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    onload: (response) => {
                        // return the file name only (so it becomes the Path[] value)
                        return JSON.parse(response).id;
                    }
                },
                revert: {
                    url: '{{ url("/upload-revert") }}',
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                }
            }
        });

        const pond2 = FilePond.create(document.querySelector('#Path2'), {
            allowMultiple: true,
            maxFileSize: '10MB',

            server: {
                process: {
                    url: '{{ url("/upload-temp-cc") }}',
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    onload: (response) => {
                        // return the file name only (so it becomes the Path[] value)
                        return JSON.parse(response).id;
                    }
                },
                revert: {
                    url: '{{ url("/upload-revert-cc") }}',
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                }
            }
        });
    });
</script>

@endsection
<!-- <!doctype html>
<html lang="en">
  <head>
  	<title>Contact Form 03</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,700' rel='stylesheet' type='text/css'>

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	
	<link rel="stylesheet" href="css/style.css">

	</head>
	<body>
	<section class="ftco-section">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-6 text-center mb-5">
					<h2 class="heading-section">Contact Form #03</h2>
				</div>
			</div>
			<div class="row justify-content-center">
				<div class="col-md-12">
					<div class="wrapper">
						<div class="row mb-5">
							<div class="col-md-3">
								<div class="dbox w-100 text-center">
			        		<div class="icon d-flex align-items-center justify-content-center">
			        			<span class="fa fa-map-marker"></span>
			        		</div>
			        		<div class="text">
				            <p><span>Address:</span> 198 West 21th Street, Suite 721 New York NY 10016</p>
				          </div>
			          </div>
							</div>
							<div class="col-md-3">
								<div class="dbox w-100 text-center">
			        		<div class="icon d-flex align-items-center justify-content-center">
			        			<span class="fa fa-phone"></span>
			        		</div>
			        		<div class="text">
				            <p><span>Phone:</span> <a href="tel://1234567920">+ 1235 2355 98</a></p>
				          </div>
			          </div>
							</div>
							<div class="col-md-3">
								<div class="dbox w-100 text-center">
			        		<div class="icon d-flex align-items-center justify-content-center">
			        			<span class="fa fa-paper-plane"></span>
			        		</div>
			        		<div class="text">
				            <p><span>Email:</span> <a href="mailto:info@yoursite.com">info@yoursite.com</a></p>
				          </div>
			          </div>
							</div>
							<div class="col-md-3">
								<div class="dbox w-100 text-center">
			        		<div class="icon d-flex align-items-center justify-content-center">
			        			<span class="fa fa-globe"></span>
			        		</div>
			        		<div class="text">
				            <p><span>Website</span> <a href="#">yoursite.com</a></p>
				          </div>
			          </div>
							</div>
						</div>
						<div class="row no-gutters">
							<div class="col-md-7">
								<div class="contact-wrap w-100 p-md-5 p-4">
									<h3 class="mb-4">Contact Us</h3>
									<div id="form-message-warning" class="mb-4"></div> 
				      		<div id="form-message-success" class="mb-4">
				            Your message was sent, thank you!
				      		</div>
									<form method="POST" id="contactForm" name="contactForm" class="contactForm">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label class="label">Full Name</label>
													<input type="text" class="form-control" name="name" id="name" placeholder="Name">
												</div>
											</div>
											<div class="col-md-6"> 
												<div class="form-group">
													<label class="label">Email Address</label>
													<input type="email" class="form-control" name="email" id="email" placeholder="Email">
												</div>
											</div>
											<div class="col-md-12">
												<div class="form-group">
													<label class="label" for="subject">Subject</label>
													<input type="text" class="form-control" name="subject" id="subject" placeholder="Subject">
												</div>
											</div>
											<div class="col-md-12">
												<div class="form-group">
													<label class="label" for="#">Message</label>
													<textarea name="message" class="form-control" id="message" cols="30" rows="4" placeholder="Message"></textarea>
												</div>
											</div>
											<div class="col-md-12">
												<div class="form-group">
													<input type="submit" value="Send Message" class="btn btn-primary">
													<div class="submitting"></div>
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
							<div class="col-md-5 d-flex align-items-stretch">
								<div class="info-wrap w-100 p-5 img" style="background-image: url(images/Seaweed_farm.jpg); background-size: cover;">
			          </div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<script src="js/jquery.min.js"></script>
  <script src="js/popper.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/jquery.validate.min.js"></script>
  <script src="js/main.js"></script>

	</body>
</html> -->


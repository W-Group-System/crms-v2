<div class="modal fade" id="AddProductEvaluation" tabindex="-1" role="dialog" aria-labelledby="addCustomerRequirement" aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addCustomerRequirentLabel">Add Request Product Evaluation</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" action="{{ url('new_product_evaluation') }}">
                    @csrf
                    <div class="row">
                        {{-- <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Date Created (DD/MM/YYYY) - Hour Minute</label>
                                <input type="datetime-local" class="form-control CreatedDate" name="CreatedDate" readonly>
                            </div>
                        </div> --}}
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Project Name</label>
                                <select class="form-control js-example-basic-single" name="ProjectNameId" style="position: relative !important" title="Select Client" required>
                                    <option value="" disabled selected>Select Project Name</option>
                                    @foreach($project_names as $projectName)
                                        <option value="{{ $projectName->id }}">{{ $projectName->Name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Priority</label>
                                <select class="form-control js-example-basic-single" name="Priority" id="Priority" style="position: relative !important" title="Select Priority">
                                    <option value="" disabled selected>Select Priority</option>
                                    <option value="3">Second Priority</option>
                                    <option value="5">First Priority</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Application</label>
                                <select class="form-control js-example-basic-single" name="ApplicationId" id="ApplicationId" style="position: relative !important" title="Select Application" required>
                                    <option value="" disabled selected>Select Application</option>
                                    @foreach($product_applications as $product_application)
                                        <option value="{{ $product_application->id }}">{{ $product_application->Name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Due Date</label>
                                <input type="date" class="form-control DueDate" name="DueDate" min="{{date('Y-m-d')}}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-sm-8" style="padding-right: 0px">
                                    <div class="form-group">
                                        <label>Potential Volume</label>
                                        <input type="number" class="form-control" id="PotentialVolume" name="PotentialVolume" value="0">
                                    </div>
                                </div>
                                <div class="col-sm-4" style="padding-left: 0px">
                                    <div class="form-group">
                                        <label>Unit</label>
                                        <select class="form-control js-example-basic-single" name="UnitOfMeasureId" id="UnitOfMeasureId" style="position: relative !important" title="Select Unit">
                                            <option value="" disabled selected>Select Unit</option>
                                            <option value="1">Grams</option>
                                            <option value="2">Kilograms</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Primary Sales Person</label>
                                @if(auth()->user()->role->name == "Staff L2" || auth()->user()->role->name == "Department Admin")
                                <select class="form-control js-example-basic-single" name="PrimarySalesPersonId" id="PrimarySalesPersonId" style="position: relative !important" title="Select Sales Person">
                                    <option value="" disabled selected>Select Sales Person</option>
                                    @foreach($primarySalesPersons as $user)
                                        <option value="{{ $user->user_id }}" @if(auth()->user()->id == $user->id) selected @endif>{{ $user->full_name }}</option>
                                    @endforeach
                                </select>
                                @else
                                <input type="hidden" name="PrimarySalesPersonId" value="{{auth()->user()->user_id}}">
                                <input type="text" class="form-control" value="{{auth()->user()->full_name}}" disabled>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-sm-8" style="padding-right: 0px">
                                    <div class="form-group">
                                        <label>Target Price</label>
                                        <input type="number" class="form-control" id="TargetRawPrice" name="TargetRawPrice" value="0">
                                    </div>
                                </div>
                                <div class="col-sm-4" style="padding-left: 0px">
                                    <div class="form-group">
                                        <label>Currency</label>
                                        <select class="form-control js-example-basic-single" name="CurrencyId" id="CurrencyId" style="position: relative !important" title="Select Currency">
                                            <option value="" disabled selected>Select Currency</option>
                                            @foreach($price_currencies as $price_currency)
                                                <option value="{{ $price_currency->id }}">{{ $price_currency->Name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Secondary Sales Person</label>
                                <select class="form-control js-example-basic-single" name="SecondarySalesPerson" style="position: relative !important" title="Select Sales Person">
                                    <option value="" disabled selected>Select Sales Person</option>
                                    @foreach($secondarySalesPersons as $user)
                                        <option value="{{ $user->user_id }}">{{ $user->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Sample Name</label>
                                <input type="text" class="form-control" id="SampleName" name="SampleName" placeholder="Enter Sample Name">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Attention To:</label>
                                <input type="hidden" class="form-control" name="AttentionTo"  value="1">
                                <input type="text" class="form-control" value="RND" readonly>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Manufacturer</label>
                                <input type="text" class="form-control" id="Manufacturer" name="Manufacturer" placeholder="Enter Manufacturer">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Client</label>
                                <select class="form-control js-example-basic-single" name="ClientId" id="ClientId" style="position: relative !important" title="Select Client" required>
                                    <option value="" disabled selected>Select Client</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}">{{ $client->Name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Supplier</label>
                                <input type="text" class="form-control" id="Supplier" name="Supplier" placeholder="Enter Supplier">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Objective for RPE Project</label>
                                <textarea class="form-control" id="Objective" name="ObjectiveForRpeProject" placeholder="Enter Objective" rows="4"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="submit"  class="btn btn-success" value="Save">
                    </div>
                </form>
            </div>
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

        document.addEventListener('DOMContentLoaded', function() {
        var validityDateInput = document.querySelector('.CreatedDate');
        var dateRequestedInput = document.querySelector('.DueDate');



        var now = new Date();
        var date = now.toISOString().split('T')[0];
        var time = now.toTimeString().split(' ')[0]; 

        var todayWithTime = date + 'T' + time;

        validityDateInput.setAttribute('min', todayWithTime);
        validityDateInput.value = todayWithTime;
        dateRequestedInput.setAttribute('min', date);

    });
</script>
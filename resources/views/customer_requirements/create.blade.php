<div class="modal fade" id="AddCustomerRequirement" tabindex="-1" role="dialog" aria-labelledby="addCustomerRequirement" aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addCustomerRequirentLabel">Add New Customer Requiremnt</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" action="{{ url('new_customer_requirement') }}">
                    @csrf
                    <div class="row">
                        {{-- <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Date Created (DD/MM/YYYY) - Hour Minute</label>
                                <input type="datetime-local" class="form-control" id="CreatedDate" name="CreatedDate" required>
                            </div>
                        </div> --}}
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
                                <label>Priority</label>
                                <select class="form-control js-example-basic-single" name="Priority" id="Priority" style="position: relative !important" title="Select Priority">
                                    <option value="" disabled selected>Select Priority</option>
                                    <option value="1">Low</option>
                                    <option value="3">Medium</option>
                                    <option value="5">High</option>
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
                                <input type="date" class="form-control" id="DueDate" name="DueDate" min="{{date('Y-m-d')}}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-sm-8" style="padding-right: 0px">
                                    <div class="form-group">
                                        <label>Potential Volume</label>
                                        <input type="number" step=".01" class="form-control" id="PotentialVolume" name="PotentialVolume" value="0">
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
                                {{-- <select class="form-control js-example-basic-single" name="PrimarySalesPersonId" id="PrimarySalesPersonId" style="position: relative !important" title="Select Sales Person">
                                    <option value="" disabled selected>Select Sales Person</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" @if($user->id == auth()->user()->id) selected @endif>{{ $user->full_name }}</option>
                                    @endforeach
                                </select> --}}
                                <input type="text" name="PrimarySalesPersonId" class="form-control" value="{{auth()->user()->full_name}}" readonly>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-sm-8" style="padding-right: 0px">
                                    <div class="form-group">
                                        <label>Target Price</label>
                                        <input type="number" step=".01" class="form-control" id="TargetPrice" name="TargetPrice" value="0">
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
                                <select class="form-control js-example-basic-single" name="SecondarySalesPersonId" id="SecondarySalesPersonId" style="position: relative !important" title="Select Sales Person">
                                    <option value="" disabled selected>Select Sales Person</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Competitor</label>
                                <input type="text" class="form-control" id="Competitor" name="Competitor" placeholder="Enter Competitor">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Competitor Price</label>
                                <input type="number" step=".01" class="form-control" id="CompetitorPrice" name="CompetitorPrice" value="0">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <label for="name">Nature of Request</label>
                            <button type="button" class="btn btn-primary btn-sm addRow">+</button>

                            <div class="natureOfRequestContainer">
                                {{-- <div class="input-group mb-3">
                                    <select class="form-control js-example-basic-single" name="NatureOfRequestId[]" required>
                                        <option value="" disabled selected>Select Nature of Request</option>
                                        @foreach($nature_requests as $nature_request)
                                            <option value="{{ $nature_request->id }}">{{ $nature_request->Name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-primary addRow">+</button>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">REF Code</label>
                                <select name="RefCode" class="form-control js-example-basic-single" required>
                                    <option disabled selected value>Select REF Code</option>
                                    @foreach ($refCode as $key=>$code)
                                        <option value="{{$key}}">{{$code}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">REF CRR Number</label>
                                <input type="text" class="form-control" id="RefCrrNumber" name="RefCrrNumber" placeholder="Enter CRR Number">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">REF RPE Number</label>
                                <input type="text" class="form-control" id="RefRpeNumber" name="RefRpeNumber" placeholder="Enter RPE Number">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="name">Details of Requirement</label>
                                <textarea type="text" class="form-control" id="DetailsOfRequirement" name="DetailsOfRequirement" placeholder="Enter Details of Requirement" rows="7"></textarea>
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
{{-- <script src="{{ asset('js/sweetalert2.min.js') }}"></script>
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
</script> --}}
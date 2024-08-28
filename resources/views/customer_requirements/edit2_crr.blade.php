<div class="modal fade" id="editCrr{{$crr->id}}" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addCustomerRequirentLabel">Edit Customer Requirement</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" action="{{url('update_customer_requirement/'.$crr->id)}}">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <label>Client</label>
                            <div class="form-group">
                                <select class="form-control js-example-basic-single" name="ClientId" style="position: relative !important" title="Select Client" required>
                                    <option value="" disabled selected>Select Client</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" @if($client->id == $crr->ClientId) selected @endif>{{ $client->Name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Priority</label>
                                <select class="form-control js-example-basic-single" name="Priority" style="position: relative !important" title="Select Priority">
                                    <option value="" disabled selected>Select Priority</option>
                                    <option value="1" @if($crr->Priority == 1) selected @endif>Low</option>
                                    <option value="3" @if($crr->Priority == 3) selected @endif>Medium</option>
                                    <option value="5" @if($crr->Priority == 5) selected @endif>High</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Application</label>
                                <select class="form-control js-example-basic-single" name="ApplicationId" style="position: relative !important" title="Select Application" required>
                                    <option value="" disabled selected>Select Application</option>
                                    @foreach($product_applications as $product_application)
                                        <option value="{{ $product_application->id }}" @if($product_application->id == $crr->ApplicationId) selected @endif>{{ $product_application->Name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Due Date</label>
                                <input type="date" class="form-control" id="DueDate" name="DueDate" value="{{$crr->DueDate}}" min="{{date('Y-m-d')}}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-sm-8" style="padding-right: 0px">
                                    <div class="form-group">
                                        <label>Potential Volume</label>
                                        <input type="number" step=".01" class="form-control" id="PotentialVolume" name="PotentialVolume" value="{{$crr->PotentialVolume}}">
                                    </div>
                                </div>
                                <div class="col-sm-4" style="padding-left: 0px">
                                    <div class="form-group">
                                        <label>Unit</label>
                                        <select class="form-control js-example-basic-single" name="UnitOfMeasureId" style="position: relative !important" title="Select Unit">
                                            <option value="" disabled selected>Select Unit</option>
                                            <option value="1" @if($crr->UnitOfMeasureId == 1) selected @endif>Grams</option>
                                            <option value="2" @if($crr->UnitOfMeasureId == 2) selected @endif>Kilograms</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Primary Sales Person</label>
                                <select class="form-control js-example-basic-single" name="PrimarySalesPersonId" style="position: relative !important" title="Select Sales Person">
                                    <option value="" disabled selected>Select Sales Person</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" @if($user->user_id == $crr->PrimarySalesPersonId || $user->id == $crr->PrimarySalesPersonId) selected @endif>{{ $user->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-sm-8" style="padding-right: 0px">
                                    <div class="form-group">
                                        <label>Target Price</label>
                                        <input type="text" class="form-control" id="TargetPrice" name="TargetPrice" value="{{$crr->TargetPrice}}">
                                    </div>
                                </div>
                                <div class="col-sm-4" style="padding-left: 0px">
                                    <div class="form-group">
                                        <label>Currency</label>
                                        <select class="form-control js-example-basic-single" name="CurrencyId" style="position: relative !important" title="Select Currency">
                                            <option value="" disabled selected>Select Currency</option>
                                            @foreach($price_currencies as $price_currency)
                                                <option value="{{ $price_currency->id }}" @if($price_currency->id == $crr->CurrencyId) selected @endif>{{ $price_currency->Name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Secondary Sales Person</label>
                                <select class="form-control js-example-basic-single" name="SecondarySalesPersonId" style="position: relative !important" title="Select Sales Person">
                                    <option value="" disabled selected>Select Sales Person</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" @if($user->user_id == $crr->SecondarySalesPersonId || $user->id == $crr->SecondarySalesPersonId) selected @endif>{{ $user->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Competitor</label>
                                <input type="text" class="form-control" id="Competitor" name="Competitor" placeholder="Enter Competitor" value="{{$crr->Competitor}}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Competitor Price</label>
                                <input type="text" class="form-control" id="CompetitorPrice" name="CompetitorPrice" value="{{$crr->CompetitorPrice}}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <label for="name">Nature of Request</label>
                            <button type="button" class="btn btn-sm btn-primary addRow">+</button>
                            <div class="natureOfRequestContainer">
                                @foreach ($crr->crrNature as $data)
                                <div class="input-group mb-3">
                                    <select class="form-control natureRequestSelect" name="NatureOfRequestId[]" required>
                                        <option value="" disabled selected>Select Nature of Request</option>
                                        @foreach($nature_requests as $nature_request)
                                            <option value="{{ $nature_request->id }}" @if($data->NatureOfRequestId == $nature_request->id) selected @endif>{{ $nature_request->Name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-danger removeRow">-</button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">REF CRR Number</label>
                                <input type="text" class="form-control" id="RefCrrNumber" name="RefCrrNumber" placeholder="Enter CRR Number" value="{{$crr->RefCrrNumber}}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">REF RPE Number</label>
                                <input type="text" class="form-control" id="RefRpeNumber" name="RefRpeNumber" placeholder="Enter RPE Number" value="{{$crr->RefRpeNumber}}">
                            </div>
                        </div>
                        {{-- <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">Status</label>
                                <select class="form-control js-example-basic-single" name="Status" required>
                                    <option value="" disabled selected>Select Status</option>
                                    <option value="10" @if($crr->Status == 10) selected @endif>Open</option>
                                    <option value="30" @if($crr->Status == 30) selected @endif>Closed</option>
                                    <option value="50" @if($crr->Status == 50) selected @endif>Cancelled</option>
                                </select>
                            </div>
                        </div> --}}
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">REF Code</label>
                                <select name="RefCode" class="form-control js-example-basic-single" required>
                                    <option disabled selected value>Select REF Code</option>
                                    @foreach ($refCode as $key=>$code)
                                        <option value="{{$key}}" @if($key == $crr->RefCode) selected @endif>{{$code}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="name">Details of Requirement</label>
                                <textarea type="text" class="form-control" id="DetailsOfRequirement" name="DetailsOfRequirement" placeholder="Enter Details of Requirement" rows="7">{!! nl2br(e($crr->DetailsOfRequirement)) !!}</textarea>
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

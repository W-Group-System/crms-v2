<div class="modal fade" id="editCrr-{{$customerRequirement->id}}" tabindex="-1" role="dialog" aria-labelledby="addCustomerRequirement" aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addCustomerRequirentLabel">Edit Customer Requirement</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" action="{{url('update_customer_requirement/'.$customerRequirement->id)}}" onsubmit="show()">
                    @csrf
                    <div class="row">
                        {{-- <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Date Created (DD/MM/YYYY) - Hour Minute</label>
                                <input type="datetime-local" class="form-control" name="CreatedDate" value="{{$customerRequirement->DateCreated}}" required>
                            </div>
                        </div> --}}
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Client</label>
                                <select class="form-control js-example-basic-single" name="ClientId" style="position: relative !important" title="Select Client" required>
                                    <option value="" disabled selected>Select Client</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" @if($client->id == $customerRequirement->ClientId) selected @endif>{{ $client->Name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Priority</label>
                                <select class="form-control js-example-basic-single" name="Priority" style="position: relative !important" title="Select Priority">
                                    <option value="" disabled selected>Select Priority</option>
                                    <option value="1" @if($customerRequirement->Priority == 1) selected @endif>Low</option>
                                    <option value="3" @if($customerRequirement->Priority == 3) selected @endif>Medium</option>
                                    <option value="5" @if($customerRequirement->Priority == 5) selected @endif>High</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Application</label>
                                <select class="form-control js-example-basic-single" name="ApplicationId" style="position: relative !important" title="Select Application" required>
                                    <option value="" disabled selected>Select Application</option>
                                    @foreach($product_applications as $product_application)
                                        <option value="{{ $product_application->id }}" @if($product_application->id == $customerRequirement->ApplicationId) selected @endif>{{ $product_application->Name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Due Date</label>
                                <input type="date" class="form-control" id="DueDate" name="DueDate" value="{{$customerRequirement->DueDate}}" min="{{date('Y-m-d')}}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-sm-8" style="padding-right: 0px">
                                    <div class="form-group">
                                        <label>Potential Volume</label>
                                        <input type="number" step=".01" class="form-control" id="PotentialVolume" name="PotentialVolume" value="{{$customerRequirement->PotentialVolume}}">
                                    </div>
                                </div>
                                <div class="col-sm-4" style="padding-left: 0px">
                                    <div class="form-group">
                                        <label>Unit</label>
                                        <select class="form-control js-example-basic-single" name="UnitOfMeasureId" style="position: relative !important" title="Select Unit">
                                            <option value="" disabled selected>Select Unit</option>
                                            @foreach ($unitOfMeasure as $unit)
                                                <option value="{{$unit->Id}}" @if($unit->Id == $customerRequirement->UnitOfMeasureId) selected @endif>{{$unit->Name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Primary Sales Person</label>
                                
                                @if(auth()->user()->role->name == "Staff L1")
                                <input type="hidden" name="PrimarySalesPersonId" value="{{auth()->user()->id}}">
                                <input type="text" class="form-control" value="{{auth()->user()->full_name}}" readonly>
                                @elseif (auth()->user()->role->name == "Staff L2" || auth()->user()->role->name == "Department Admin")
                                @php
                                    $subordinates = getUserApprover(auth()->user()->getSalesApprover);
                                @endphp
                                <select class="form-control js-example-basic-single" name="PrimarySalesPersonId" style="position: relative !important" title="Select Sales Person">
                                    <option value="" disabled selected>Select Sales Person</option>
                                    @foreach($subordinates as $user)
                                        <option value="{{ $user->id }}" @if($user->user_id == $customerRequirement->PrimarySalesPersonId || $user->id == $customerRequirement->PrimarySalesPersonId) selected @endif>{{ $user->full_name }}</option>
                                    @endforeach
                                </select>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-sm-8" style="padding-right: 0px">
                                    <div class="form-group">
                                        <label>Target Price</label>
                                        <input type="text" class="form-control" id="TargetPrice" name="TargetPrice" value="{{$customerRequirement->TargetPrice}}">
                                    </div>
                                </div>
                                <div class="col-sm-4" style="padding-left: 0px">
                                    <div class="form-group">
                                        <label>Currency</label>
                                        <select class="form-control js-example-basic-single" name="CurrencyId" style="position: relative !important" title="Select Currency">
                                            <option value="" disabled selected>Select Currency</option>
                                            @foreach($price_currencies as $price_currency)
                                                <option value="{{ $price_currency->id }}" @if($price_currency->id == $customerRequirement->CurrencyId) selected @endif>{{ $price_currency->Name }}</option>
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
                                        <option value="{{ $user->id }}" @if($user->user_id == $customerRequirement->SecondarySalesPersonId || $user->id == $customerRequirement->SecondarySalesPersonId) selected @endif>{{ $user->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Competitor</label>
                                <input type="text" class="form-control" id="Competitor" name="Competitor" placeholder="Enter Competitor" value="{{$customerRequirement->Competitor}}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Competitor Price</label>
                                <input type="text" class="form-control" id="CompetitorPrice" name="CompetitorPrice" value="{{$customerRequirement->CompetitorPrice}}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <label for="name">Nature of Request</label>
                            <button type="button" class="btn btn-sm btn-primary addRow">+</button>
                            <div class="natureOfRequestContainer">
                                @foreach ($customerRequirement->crrNature as $data)
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
                                <label for="">REF Code</label>
                                <select name="RefCode" class="form-control js-example-basic-single" required>
                                    <option disabled selected value>Select REF Code</option>
                                    @foreach ($refCode as $key=>$code)
                                        <option value="{{$key}}" @if($key == $customerRequirement->RefCode) selected @endif>{{$code}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">REF CRR Number</label>
                                <input type="text" class="form-control" id="RefCrrNumber" name="RefCrrNumber" placeholder="Enter CRR Number" value="{{$customerRequirement->RefCrrNumber}}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">REF RPE Number</label>
                                <input type="text" class="form-control" id="RefRpeNumber" name="RefRpeNumber" placeholder="Enter RPE Number" value="{{$customerRequirement->RefRpeNumber}}">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="name">Details of Requirement</label>
                                <textarea type="text" class="form-control" id="DetailsOfRequirement" name="DetailsOfRequirement" placeholder="Enter Details of Requirement" rows="7">{!! nl2br(e($customerRequirement->DetailsOfRequirement)) !!}</textarea>
                            </div>
                        </div>
                        {{-- <div class="col-lg-6">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="Status" class="form-control js-example-basic-single">
                                    <option disabled selected value>Select Status</option>
                                    <option value="10" @if($customerRequirement->Status == 10) selected @endif>Open</option>
                                    <option value="30" @if($customerRequirement->Status == 30) selected @endif>Closed</option>
                                    <option value="50" @if($customerRequirement->Status == 50) selected @endif>Cancelled</option>
                                </select>
                            </div>
                        </div> --}}
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

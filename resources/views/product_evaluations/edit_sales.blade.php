<div class="modal fade" id="editRpe{{ $requestEvaluation->id }}" tabindex="-1" role="dialog" aria-labelledby="editrequestEvaluation" aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editrequestEvaluationLabel">Product Evaluation</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
                <form method="POST" enctype="multipart/form-data" action="{{ url('product_evaluation/edit/'. $requestEvaluation->id) }}" onsubmit="show()">
                    @csrf
                    <div class="row">
                        {{-- <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Date Created (DD/MM/YYYY) - Hour Minute</label>
                                <input type="datetime" class="form-control" name="CreatedDate" value="{{ !empty($requestEvaluation->CreatedDate) ? date('m/d/y H:i', strtotime($requestEvaluation->CreatedDate)) : '' }}" readonly>
                            </div>
                        </div> --}}
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Project Name</label>
                                <select class="form-control js-example-basic-single" name="ProjectNameId" style="position: relative !important" title="Select Project Name" required>
                                    <option value="" disabled selected>Select Project Name</option>
                                    @foreach($project_names as $projectName)
                                        <option value="{{ $projectName->id }}" @if ( $requestEvaluation->ProjectNameId == $projectName->id) selected @endif>{{ $projectName->Name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Priority</label>
                                <select class="form-control js-example-basic-single" name="Priority" style="position: relative !important" title="Select Priority">
                                    <option value="" disabled selected>Select Priority</option>
                                    <option value="1" @if ( $requestEvaluation->Priority == "1") selected @endif>IC Application</option>
                                    <option value="3" @if ( $requestEvaluation->Priority == "3") selected @endif>Second Priority</option>
                                    <option value="5" @if ( $requestEvaluation->Priority == "5") selected @endif>First Priority</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Application</label>
                                <select class="form-control js-example-basic-single" name="ApplicationId" style="position: relative !important" title="Select Application" required>
                                    <option value="" disabled selected>Select Application</option>
                                    @foreach($product_applications as $product_application)
                                        <option value="{{ $product_application->id }}" @if ( $requestEvaluation->ApplicationId == $product_application->id) selected @endif>{{ $product_application->Name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Due Date</label>
                                <input type="date" class="form-control DueDate{{ $requestEvaluation->DueDate }}" name="DueDate" value="{{ !empty($requestEvaluation->DueDate) ? date('Y-m-d', strtotime($requestEvaluation->DueDate)) : '' }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-sm-8" style="padding-right: 0px">
                                    <div class="form-group">
                                        <label>Potential Volume</label>
                                        <input type="number" step=".01" class="form-control" name="PotentialVolume" value="{{ $requestEvaluation->PotentialVolume }}">
                                    </div>
                                </div>
                                <div class="col-sm-4" style="padding-left: 0px">
                                    <div class="form-group">
                                        <label>Unit</label>
                                        <select class="form-control js-example-basic-single" name="UnitOfMeasureId" style="position: relative !important" title="Select Unit">
                                            <option value="" disabled selected>Select Unit</option>
                                            <option value="1" @if ( $requestEvaluation->UnitOfMeasureId == "1") selected @endif>Grams</option>
                                            <option value="2" @if ( $requestEvaluation->UnitOfMeasureId == "2") selected @endif>Kilograms</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Primary Sales Person</label>
                                {{-- @if(auth()->user()->role->name == "Staff L1")
                                <input type="hidden" name="PrimarySalesPersonId" value="{{auth()->user()->id}}">
                                <input type="text" class="form-control" value="{{auth()->user()->full_name}}" readonly>
                                @elseif (auth()->user()->role->name == "Staff L2" || auth()->user()->role->name == "Department Admin")
                                @php
                                    $subordinates = getUserApprover(auth()->user()->getSalesApprover);
                                @endphp
                                <select class="form-control js-example-basic-single" name="PrimarySalesPersonId" style="position: relative !important" title="Select Sales Person">
                                    <option value="" disabled selected>Select Sales Person</option>
                                    @foreach($subordinates as $user)
                                        <option value="{{ $user->id }}" @if($user->user_id == $requestEvaluation->PrimarySalesPersonId || $user->id == $requestEvaluation->PrimarySalesPersonId) selected @endif>{{ $user->full_name }}</option>
                                    @endforeach
                                </select>
                                @endif --}}
                                {{-- @php
                                    $primary_sales = "";
                                    if ($requestEvaluation->primarySalesPersonById == null)
                                    {
                                        $primary_sales = $requestEvaluation->primarySalesPerson;
                                    }
                                    else
                                    {
                                        $primary_sales = $requestEvaluation->primarySalesPersonById;
                                    }
                                @endphp
                                @if(auth()->user()->role->name == 'Staff L1')
                                    <input type="hidden" name="PrimarySalesPersonId" value="{{$primary_sales->id}}">
                                    <input type="text" class="form-control" value="{{$primary_sales->full_name}}" readonly>
                                @else
                                    <select class="form-control js-example-basic-single" name="PrimarySalesPersonId" style="position: relative !important" title="Select Sales Person" required>
                                        <option value="" disabled selected>Select Sales Person</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" @if($user->id == $requestEvaluation->PrimarySalesPersonId || $user->user_id == $requestEvaluation->PrimarySalesPersonId) selected @endif>{{ $user->full_name }}</option>
                                        @endforeach
                                    </select> 
                                @endif --}}
                                <select class="form-control js-example-basic-single" name="PrimarySalesPersonId" style="position: relative !important" title="Select Sales Person" required>
                                    <option value="" disabled selected>Select Sales Person</option>
                                    @foreach($currentUser->groupSales as $group_sales)
                                        @php
                                            $user = $group_sales->user;
                                        @endphp
                                        <option value="{{ $user->id }}" @if($user->id == $requestEvaluation->PrimarySalesPersonId || $user->user_id == $requestEvaluation->PrimarySalesPersonId) selected @endif>{{ $user->full_name }}</option>
                                    @endforeach
                                </select> 
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-sm-8" style="padding-right: 0px">
                                    <div class="form-group">
                                        <label>Target Price</label>
                                        <input type="number" step=".01" class="form-control"  name="TargetRawPrice" value="{{ $requestEvaluation->TargetRawPrice }}">
                                    </div>
                                </div>
                                <div class="col-sm-4" style="padding-left: 0px">
                                    <div class="form-group">
                                        <label>Currency</label>
                                        <select class="form-control js-example-basic-single" name="CurrencyId" style="position: relative !important" title="Select Currency">
                                            <option value="" disabled selected>Select Currency</option>
                                            @foreach($price_currencies as $price_currency)
                                                <option value="{{ $price_currency->id }}" @if ( $requestEvaluation->CurrencyId == $price_currency->id) selected @endif>{{ $price_currency->Name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Secondary Sales Person</label>
                                {{-- <select class="form-control js-example-basic-single" name="SecondarySalesPersonId" style="position: relative !important" title="Select Sales Person">
                                    <option value="" disabled selected>Select Sales Person</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" @if($user->user_id == $requestEvaluation->SecondarySalesPersonId || $user->id == $requestEvaluation->SecondarySalesPersonId) selected @endif>{{ $user->full_name }}</option>
                                        <option value="{{ $user->user_id }}" @if ( $requestEvaluation->SecondarySalesPersonId == $user->user_id) selected @endif>{{ $user->full_name }}</option>
                                    @endforeach
                                </select> --}}
                                {{-- @php
                                    $secondary_sales = "";
                                    if ($requestEvaluation->secondarySalesPersonById == null)
                                    {
                                        $secondary_sales = $requestEvaluation->secondarySalesPerson;
                                    }
                                    else
                                    {
                                        $secondary_sales = $requestEvaluation->secondarySalesPersonById;
                                    }
                                @endphp
                                @if($requestEvaluation->SecondarySalesPersonId == auth()->user()->id || $requestEvaluation->SecondarySalesPersonId == auth()->user()->user_id)
                                <input type="hidden" name="SecondarySalesPersonId" value="{{$secondary_sales->id}}">
                                <input type="text" class="form-control" value="{{$secondary_sales->full_name}}" readonly>
                                @else
                                <select class="form-control js-example-basic-single" name="SecondarySalesPersonId" style="position: relative !important" title="Select Sales Person" required>
                                    <option value="" disabled selected>Select Sales Person</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" @if($user->id == $requestEvaluation->SecondarySalesPersonId || $user->user_id == $requestEvaluation->SecondarySalesPersonId) selected @endif>{{ $user->full_name }}</option>
                                    @endforeach
                                </select> 
                                @endif --}}
                                <select class="form-control js-example-basic-single" name="SecondarySalesPersonId" style="position: relative !important" title="Select Sales Person" required>
                                    <option value="" disabled selected>Select Sales Person</option>
                                    @foreach($currentUser->groupSales as $group_sales)
                                        @php
                                            $user = $group_sales->user;
                                        @endphp
                                        <option value="{{ $user->id }}" @if($user->id == $requestEvaluation->SecondarySalesPersonId || $user->user_id == $requestEvaluation->SecondarySalesPersonId) selected @endif>{{ $user->full_name }}</option>
                                    @endforeach
                                </select> 
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Sample Name</label>
                                <input type="text" class="form-control" name="SampleName" value="{{ $requestEvaluation->SampleName }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Attention To:</label>
                                <select class="form-control js-example-basic-single" name="AttentionTo" style="position: relative !important" title="Select Attention To">
                                    <option value="" disabled selected>Select</option>
                                    <option value="1" @if ( $requestEvaluation->AttentionTo == "1") selected @endif>RND</option>
                                    <option value="2" @if ( $requestEvaluation->AttentionTo == "2") selected @endif>QCD</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Manufacturer</label>
                                <input type="text" class="form-control" id="Manufacturer" name="Manufacturer" placeholder="Enter Manufacturer" value="{{$requestEvaluation->Manufacturer}}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Supplier</label>
                                <input type="text" class="form-control" name="Supplier" value="{{ $requestEvaluation->Supplier }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Client</label>
                                <select class="form-control js-example-basic-single" name="ClientId" style="position: relative !important" title="Select Client">
                                    <option value="" disabled selected>Select Client</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" @if ( $requestEvaluation->ClientId == $client->id) selected @endif>{{ $client->Name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">RPE Reference Number</label>
                                <input type="text" class="form-control" id="RpeReferenceNumber" name="RpeReferenceNumber" placeholder="Enter Rpe Reference Number" value="{{ $requestEvaluation->RpeReferenceNumber }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Objective for RPE Project</label>
                                <textarea type="text" class="form-control" name="ObjectiveForRpeProject">{{ $requestEvaluation->ObjectiveForRpeProject }}</textarea>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Upload Files</label>
                                <input type="file" name="SalesRpeFile[]" class="form-control" multiple>
                            </div>
                        </div>
                        {{-- <div class="col-lg-6"></div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Status :</label>
                                <select class="form-control js-example-basic-single" name="Status" style="position: relative !important" title="Select Status">
                                    <option value="" disabled selected>Select</option>
                                    <option value="10" @if ( $requestEvaluation->Status == "10") selected @endif>Open</option>
                                    <option value="30" @if ( $requestEvaluation->Status == "30") selected @endif>Close</option>
                                    <option value="50" @if ( $requestEvaluation->Status == "50") selected @endif>Cancelled</option>
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
        var dueDateInput = document.querySelector('.DueDate{{ $requestEvaluation->DueDate }}');
        var storedDate = '{{ !empty($requestEvaluation->DueDate) ? date('Y-m-d', strtotime($requestEvaluation->DueDate)) : '' }}';
        var today = new Date().toISOString().split('T')[0];

        if (storedDate) {
            dueDateInput.setAttribute('min', storedDate);
        } else {
            dueDateInput.setAttribute('min', today);
        }
    });
</script>
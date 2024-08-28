<div class="modal fade" id="editRpe{{ $productEvaluation->id }}" tabindex="-1" role="dialog" aria-labelledby="editproductEvaluation" aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editproductEvaluationLabel">Product Evaluation</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
                <form method="POST" enctype="multipart/form-data" action="{{ url('product_evaluation/edit/'. $productEvaluation->id) }}">
                    @csrf
                    <div class="row">
                        {{-- <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Date Created (DD/MM/YYYY) - Hour Minute</label>
                                <input type="datetime" class="form-control" name="CreatedDate" value="{{ !empty($productEvaluation->CreatedDate) ? date('m/d/y H:i', strtotime($productEvaluation->CreatedDate)) : '' }}" readonly>
                            </div>
                        </div> --}}
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Project Name</label>
                                <select class="form-control js-example-basic-single" name="ProjectNameId" style="position: relative !important" title="Select Project Name" required>
                                    <option value="" disabled selected>Select Project Name</option>
                                    @foreach($project_names as $projectName)
                                        <option value="{{ $projectName->id }}" @if ( $productEvaluation->ProjectNameId == $projectName->id) selected @endif>{{ $projectName->Name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Priority</label>
                                <select class="form-control js-example-basic-single" name="Priority" style="position: relative !important" title="Select Priority">
                                    <option value="" disabled selected>Select Priority</option>
                                    <option value="1" @if ( $productEvaluation->Priority == "1") selected @endif>IC Application</option>
                                    <option value="3" @if ( $productEvaluation->Priority == "3") selected @endif>Second Priority</option>
                                    <option value="5" @if ( $productEvaluation->Priority == "5") selected @endif>First Priority</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Application</label>
                                <select class="form-control js-example-basic-single" name="ApplicationId" style="position: relative !important" title="Select Application" required>
                                    <option value="" disabled selected>Select Application</option>
                                    @foreach($product_applications as $product_application)
                                        <option value="{{ $product_application->id }}" @if ( $productEvaluation->ApplicationId == $product_application->id) selected @endif>{{ $product_application->Name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Due Date</label>
                                <input type="date" class="form-control DueDate{{ $productEvaluation->DueDate }}" name="DueDate" value="{{ !empty($productEvaluation->DueDate) ? date('Y-m-d', strtotime($productEvaluation->DueDate)) : '' }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-sm-8" style="padding-right: 0px">
                                    <div class="form-group">
                                        <label>Potential Volume</label>
                                        <input type="number" class="form-control" name="PotentialVolume" value="{{ $productEvaluation->PotentialVolume }}">
                                    </div>
                                </div>
                                <div class="col-sm-4" style="padding-left: 0px">
                                    <div class="form-group">
                                        <label>Unit</label>
                                        <select class="form-control js-example-basic-single" name="UnitOfMeasureId" style="position: relative !important" title="Select Unit">
                                            <option value="" disabled selected>Select Unit</option>
                                            <option value="1" @if ( $productEvaluation->UnitOfMeasureId == "1") selected @endif>Grams</option>
                                            <option value="2" @if ( $productEvaluation->UnitOfMeasureId == "2") selected @endif>Kilograms</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Primary Sales Person</label>
                                {{-- <select class="form-control js-example-basic-single" name="PrimarySalesPersonId" style="position: relative !important" title="Select Sales Person">
                                    <option value="" disabled selected>Select Sales Person</option>
                                    @foreach($primarySalesPersons as $user)
                                        <option value="{{ $user->user_id }}" @if ( $productEvaluation->PrimarySalesPersonId == $user->user_id) selected @endif>{{ $user->full_name }}</option>
                                    @endforeach
                                </select> --}}
                                <input type="hidden" name="PrimarySalesPersonId" value="{{auth()->user()->id}}">
                                <input type="text" class="form-control" value="{{auth()->user()->full_name}}" disabled>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-sm-8" style="padding-right: 0px">
                                    <div class="form-group">
                                        <label>Target Price</label>
                                        <input type="number" class="form-control"  name="TargetRawPrice" value="{{ $productEvaluation->TargetRawPrice }}">
                                    </div>
                                </div>
                                <div class="col-sm-4" style="padding-left: 0px">
                                    <div class="form-group">
                                        <label>Currency</label>
                                        <select class="form-control js-example-basic-single" name="CurrencyId" style="position: relative !important" title="Select Currency">
                                            <option value="" disabled selected>Select Currency</option>
                                            @foreach($price_currencies as $price_currency)
                                                <option value="{{ $price_currency->id }}" @if ( $productEvaluation->CurrencyId == $price_currency->id) selected @endif>{{ $price_currency->Name }}</option>
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
                                    @foreach($secondarySalesPersons as $user)
                                        <option value="{{ $user->id }}" @if ( $productEvaluation->SecondarySalesPersonId == $user->user_id || $productEvaluation->SecondarySalesPersonId == $user->id) selected @endif>{{ $user->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Sample Name</label>
                                <input type="text" class="form-control" name="SampleName" value="{{ $productEvaluation->SampleName }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Attention To:</label>
                                <select class="form-control js-example-basic-single" name="AttentionTo" style="position: relative !important" title="Select Attention To">
                                    <option value="" disabled selected>Select</option>
                                    <option value="1" @if ( $productEvaluation->AttentionTo == "1") selected @endif>RND</option>
                                    <option value="2" @if ( $productEvaluation->AttentionTo == "2") selected @endif>QCD</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Manufacturer</label>
                                <input type="text" class="form-control" id="Manufacturer" name="Manufacturer" placeholder="Enter Manufacturer" value="{{$productEvaluation->Manufacturer}}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Supplier</label>
                                <input type="text" class="form-control" name="Supplier" value="{{ $productEvaluation->Supplier }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Client</label>
                                <select class="form-control js-example-basic-single" name="ClientId" style="position: relative !important" title="Select Client">
                                    <option value="" disabled selected>Select Client</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" @if ( $productEvaluation->ClientId == $client->id) selected @endif>{{ $client->Name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Objective for RPE Project</label>
                                <textarea type="text" class="form-control" name="ObjectiveForRpeProject">{{ $productEvaluation->ObjectiveForRpeProject }}</textarea>
                            </div>
                        </div>
                        {{-- <div class="col-lg-6"></div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Status :</label>
                                <select class="form-control js-example-basic-single" name="Status" style="position: relative !important" title="Select Status">
                                    <option value="" disabled selected>Select</option>
                                    <option value="10" @if ( $productEvaluation->Status == "10") selected @endif>Open</option>
                                    <option value="30" @if ( $productEvaluation->Status == "30") selected @endif>Close</option>
                                    <option value="50" @if ( $productEvaluation->Status == "50") selected @endif>Cancelled</option>
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
        var dueDateInput = document.querySelector('.DueDate{{ $productEvaluation->DueDate }}');
        var storedDate = '{{ !empty($productEvaluation->DieDate) ? date('Y-m-d', strtotime($productEvaluation->DieDate)) : '' }}';
        var today = new Date().toISOString().split('T')[0];

        if (storedDate) {
            dueDateInput.setAttribute('min', storedDate);
        } else {
            dueDateInput.setAttribute('min', today);
        }
    });
</script>
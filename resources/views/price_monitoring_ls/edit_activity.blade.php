<div class="modal fade" id="editPrfActivity{{ $activity->id }}" tabindex="-1" role="dialog" aria-labelledby="PRF Activity" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="Prf Acitivity">Edit Activity</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_price_request" enctype="multipart/form-data" action="{{ url('update_activity/'. $activity->id) }}">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Type</label>
                                <select class="form-control form-control-sm js-example-basic-single" name="Type" style="position: relative !important" title="Select Type" required>
                                    <option value="" disabled selected>Select Type</option>
                                    <option value="10" @if ($activity->Type == "10") selected @endif>Task</option>
                                    <option value="20" @if ($activity->Type == "20") selected @endif>Call</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Related To</label>
                                <select class="form-control form-control-sm js-example-basic-single" name="RelatedTo" style="position: relative !important" title="Select Type" required>
                                    <option value="" disabled selected>Select Related Entry Type</option>
                                    <option value="10" @if ($activity->RelatedTo == "10") selected @endif>Customer Requirement</option>
                                    <option value="20" @if ($activity->RelatedTo == "20") selected @endif>Request Product Evaluation</option>
                                    <option value="30" @if ($activity->RelatedTo == "30") selected @endif>Sample Request</option>
                                    <option value="35" @if ($activity->RelatedTo == "35") selected @endif>Price Request</option>
                                    <option value="40" @if ($activity->RelatedTo == "40") selected @endif>Complaint</option>
                                    <option value="50" @if ($activity->RelatedTo == "50") selected @endif>Feedback</option>
                                    <option value="60" @if ($activity->RelatedTo == "60") selected @endif>Collection</option>
                                    <option value="70" @if ($activity->RelatedTo == "70") selected @endif>Account Targeting</option>
                                    <option value="91" @if ($activity->RelatedTo == "91") selected @endif>Follow-up Sample/Projects</option>
                                    <option value="92" @if ($activity->RelatedTo == "92") selected @endif>Sample Dispatch</option>
                                    <option value="93" @if ($activity->RelatedTo == "93") selected @endif>Technical Presentation</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Client</label>
                                <input type="hidden" class="form-control form-control-sm ClientId" name="ClientId" value="{{ $price_monitorings->client->id }}" />
                                <input type="text" class="form-control form-control-sm" value="{{$price_monitorings->client->Name }}" readonly>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Transaction Number</label>
                                <input type="text" class="form-control form-control-sm" name="TransactionNumber" value="{{ $price_monitorings->PrfNumber }}" readonly>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Contact</label>
                                <select class="form-control form-control-sm js-example-basic-single ClientContactId" name="ClientContactId" id="ActivityClientContactId{{ $activity->id }}" style="position: relative !important" title="Select Contact" required>
                                    <option value="" disabled selected>Select Contact</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Schedule</label>
                                <input type="date" class="form-control form-control-sm ScheduleFrom" id="ScheduleFrom" name="ScheduleFrom" required value="{{ $activity->ScheduleFrom }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Primary Responsible</label>
                                <input type="hidden" name="PrimarySalesPersonId" value="{{ $price_monitorings->primarySalesPerson->id }}" />
                                <input type="text" class="form-control form-control-sm" value="{{ optional($price_monitorings->primarySalesPerson)->full_name }}" readonly>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Due Date</label>
                                <input type="date" class="form-control ScheduleTo" id="ScheduleTo" name="ScheduleTo" required value="{{ $activity->ScheduleTo }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Secondary Responsible</label>
                                <input type="hidden" name="PrimarySalesPersonId" value="{{ $price_monitorings->secondarySalesPerson->id }}" />
                                <input type="text" class="form-control form-control-sm" value="{{ optional($price_monitorings->secondarySalesPerson)->full_name }}" readonly>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" class="form-control form-control-sm" id="Title" name="Title" placeholder="Enter Title" required value="{{ $activity->Title }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Attachments</label>
                                <input type="file" class="form-control form-control-sm" name="path[]" >
                                <small><b style="color:red">Note:</b> The file must be a type of: jpg, jpeg, png, pdf, doc, docx.</small>
                                <div class="col-sm-9">
                                    <ul id="fileList"></ul>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-lg-6 edit-status" style="display: none;">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control form-control-sm js-example-basic-single" name="Status" id="Status" style="position: relative !important" title="Select Type" required>
                                    <option value="" disabled selected>Select Status</option>
                                    <option value="10">Open</option>
                                    <option value="20">Closed</option>
                                </select>
                            </div>
                        </div> --}}
                        {{-- <div class="col-lg-6 edit-status" style="display: none;">
                            <div class="form-group">
                                <label>Date Closed</label>
                                <input type="date" class="form-control form-control-sm" id="DateClosed" name="DateClosed" required>
                            </div>
                        </div> --}}
                        <div class="col-lg-6">
                            <div class="form-group">
                            <label for="Description" class="form-label">Description</label>
                            <textarea class="form-control form-control-sm" name="Description" rows="3" placeholder="Enter Description" required>{{ $activity->Description }}</textarea>
                            </div>
                        </div>
                        <div class="col-lg-6"></div>
                        <div class="col-lg-6">
                            <div class="form-group">
                            <label class="form-label">Action/Response</label>
                            <input class="form-control form-control-sm" name="Response" placeholder="Enter Response" required value="{{ $activity->Response }}">
                            </div>
                        </div>
                        <div class="col-lg-6"></div>
                        <div class="col-lg-6">
                            <div class="form-group">
                            <label class="form-label">Date Closed (MM/DD/YYYY)</label>
                            <input type="date" class="form-control form-control-sm" name="DateClosed" placeholder="Enter Date Closed" required value="{{ $activity->DateClosed }}">
                            </div>
                        </div>
                        <div class="col-lg-6"></div>
                        <div class="col-lg-6">
                            <div class="form-group">
                            <label class="form-label"> Status</label>
                            <select class="form-control form-control-sm js-example-basic-single" name="Status"  style="position: relative !important" title="Select Type" required>
                                <option value="" disabled selected>Select Status</option>
                                <option value="10" @if($price_monitorings->Status == 10) selected @endif>Open</option>
                                <option value="20" @if($price_monitorings->Status == 20) selected @endif>Closed</option>
                            </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {;
        var activityId = '{{ $activity->id }}';
        var clientIdSelector = '.ActivityClient' + activityId;
        var contactIdSelector = '.ClientContactId';

        var storedClientId = $(clientIdSelector).val();

        if(storedClientId) {
            $.ajax({
                url: '{{ url("client-contact") }}/' + storedClientId,
                type: "GET",
                dataType: "json",
                success:function(data) {
                    $(contactIdSelector).empty();
                    $(contactIdSelector).append('<option value="" disabled>Select Contact</option>');
                    $.each(data, function(key, value) {
                        $(contactIdSelector).append('<option value="'+ key +'">'+ value +'</option>');
                    });

                    var storedClientContactId = '{{ $activity->ClientContactId }}';
                    if(storedClientContactId) {
                        $(contactIdSelector).val(storedClientContactId);
                    }
                }
            });
        }

        $(clientIdSelector).change(function() {
        var clientId = $(this).val();
        if(clientId) {
            $.ajax({
                url: '{{ url("client-contact") }}/' + clientId,
                type: "GET",
                dataType: "json",
                success:function(data) {
                    $(contactIdSelector).empty();
                    $(contactIdSelector).append('<option value="" disabled selected>Select Contact</option>');
                    $.each(data, function(key, value) {
                        $(contactIdSelector).append('<option value="'+ key +'">'+ value +'</option>');
                    });
                }
            });
        } else {
            $(contactIdSelector).empty();
        }
    });
    });

    // document.addEventListener('DOMContentLoaded', function() {
    //     var scheduleInput = document.querySelector('.ScheduleFrom');
    //     var DueDateInput = document.querySelector('.ScheduleTo');

    //     var today = new Date().toISOString().split('T')[0];

    //         scheduleInput.setAttribute('min', today);
    //         DueDateInput.setAttribute('min', today);
    // });
</script>
    
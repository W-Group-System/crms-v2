<div class="modal fade" id="viewBase{{ $currentBase->Id }}" tabindex="-1" role="dialog" aria-labelledby="viewCurrentBasePrice" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="CurrentBasePrice">Current Base Price</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        Material Historical Price
                    </div>
                    <div class="card-body bg-light">
                        <p class="mb-0"><strong>USD {{ $currentBase->Price }}</strong></p>
                        <p class="mb-0">Approved By: {{ optional($currentBase->userApproved)->full_name }}</p>
                        <p class="mb-0">Date: {{ date('m/d/Y', strtotime($currentBase->EffectiveDate)) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

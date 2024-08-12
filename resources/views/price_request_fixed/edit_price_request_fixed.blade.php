<div class="modal fade" id="editPriceRequestFixed-{{$pfc->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Request Fixed Cost</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_fixed_cost" enctype="multipart/form-data" action="{{ url('update_fixed_cost/' . $pfc->id) }}">
                    @csrf
                    <div class="form-group">
                        <label>Effective Date</label>
                        <input type="date" class="form-control" id="EffectiveDate" name="EffectiveDate" value="{{date('Y-m-d')}}" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Direct Labor</label>
                        <input type="number" step=".01" class="form-control" id="DirectLabor" name="DirectLabor" placeholder="Enter Direct Labor" value="{{$pfc->DirectLabor}}" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Factory Overhead</label>
                        <input type="number" step=".01" class="form-control" id="FactoryOverhead" name="FactoryOverhead" placeholder="Enter Factory Overhead" value="{{$pfc->FactoryOverhead}}" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Delivery Cost</label>
                        <input type="number" step=".01" class="form-control" id="DeliveryCost" name="DeliveryCost" placeholder="Enter Delivery Cost" value="{{$pfc->DeliveryCost}}" required>
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

<div class="modal fade" id="editBase{{ $newBase->Id }}" tabindex="-1" role="dialog" aria-labelledby="EditBasePrice" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="EditBase">Edit New Material Base Price</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" action="{{ url('editNewBase/' .$newBase->Id) }}">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-striped" id="materialBasePrice">
                            <thead>
                                <tr>
                                    <th>Material</th>
                                    <th>Currency</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <input type="hidden" name="BasePriceId" value="{{ $newBase->Id }}">
                                        <select class="form-control js-example-basic-single" name="Material" style="position: relative !important" title="Select Material" >
                                            <option value="" disabled selected>Select Material</option>
                                            @foreach ($rawMaterials as $material)
                                                <option value="{{ $material->id }}" @if ( $newBase->MaterialId == $material->id ) selected @endif>{{ $material->Name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control js-example-basic-single" name="Currency" style="position: relative !important" title="Select Currency" >
                                            <option value="" disabled selected>Select Currency</option>
                                            @foreach ($productCurrency as $currency)
                                                <option value="{{ $currency->id }}" @if ( $newBase->CurrencyId == $currency->id ) selected @endif>{{ $currency->Name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" name="Price" value="{{  $newBase->Price }}"  placeholder="" >
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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


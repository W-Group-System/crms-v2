<div class="modal fade" id="editAllNewBasePrice" tabindex="-1" role="dialog" aria-labelledby="EditNewBasePrice" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="NewBasePrice">Edit New Material Base Price</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" action="{{ url('/editAllNewBasePrice') }}">
                    @csrf
                    <div class="table-responsive">
                        <div style="overflow-y: scroll; height: 50vh;">
                            <table class="table table-striped" id="materialBasePrice">
                                <thead>
                                    <tr>
                                        <th>Material</th>
                                        <th>Currency</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($allNewbasePrice as $BasePrice)
                                    <tr>
                                        <td>
                                            <input type="hidden" name="BasePriceId[]" value="{{ $BasePrice->Id }}">
                                            <select class="form-control js-example-basic-single" name="Material[]" style="position: relative !important" title="Select Material" >
                                                <option value="" disabled selected>Select Material</option>
                                                @foreach ($rawMaterials as $material)
                                                    <option value="{{ $material->id }}" @if ($BasePrice->MaterialId == $material->id) selected @endif>{{ $material->Name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control js-example-basic-single" name="Currency[]" style="position: relative !important" title="Select Currency" disabled>
                                                <option value="" disabled selected>Select Currency</option>
                                                @foreach ($productCurrency as $currency)
                                                    <option value="{{ $currency->id }}" @if ($BasePrice->CurrencyId == $currency->id) selected @endif>{{ $currency->Name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" step=".01" class="form-control" name="Price[]" value="{{ $BasePrice->Price }}" placeholder="">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        @php
                            $total = $newBasePrice->total();
                            $currentPage = $newBasePrice->currentPage();
                            $perPage = $newBasePrice->perPage();
                            
                            $from = ($currentPage - 1) * $perPage + 1;
                            $to = min($currentPage * $perPage, $total);
                        @endphp
                        <div class="d-flex justify-content-between align-items-center w-100 mt-3">
                            <div>Showing {{ $from }} to {{ $to }} of {{ $total }} entries</div>
                            <div>
                                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                                <input type="submit" class="btn btn-outline-success" value="Save">
                            </div>
                        </div>
                    </div>
                </form>                
            </div>
        </div>
    </div>
</div>


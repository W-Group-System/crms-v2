<div class="modal fade" id="createNewBasePrice" tabindex="-1" role="dialog" aria-labelledby="NewBasePrice" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="NewBasePrice">New Material Base Price</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" action="{{ url('/newBasePrice') }}">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-striped" id="materialBasePrice">
                            <thead>
                                <tr>
                                    <th><a href="javascript:;" class="btn btn-primary" id="addRow">+</a></th>
                                    <th>Material</th>
                                    <th>Currency</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody class="newMaterialBasePrice">
                                <tr>
                                    <td><a href="javascript:;" class="btn btn-danger deleteRow">-</a></td>
                                    <td>
                                        <select class="form-control js-example-basic-single" name="Material[]"  style="position: relative !important" title="Select Material" required>
                                            <option value="" disabled selected>Select Material</option>
                                            @foreach ($rawMaterials as $material)
                                                <option value="{{ $material->id }}" >{{ $material->Name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control js-example-basic-single" name="CurrencyReadonly" style="position: relative !important" title="Currency" disabled>
                                            @foreach ($productCurrency as $currency)
                                                <option value="{{ $currency->id }}" {{ $currency->Name == 'USD' ? 'selected' : '' }}>
                                                    {{ $currency->Name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="Currency[]" value="{{ $productCurrency->firstWhere('Name', 'USD')->id ?? '' }}">
                                    </td>
                                    <td>
                                        <input type="number" step=".01" class="form-control" name="Price[]" value="0" placeholder="" required>
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

<script>
    $(document).ready(function() {
        function initSelect2() {
            $('.js-example-basic-single').select2();
        }
        
        initSelect2();
    
        $("#addRow").on('click', function() {
            var newRow = `<tr>
                <td><a href="javascript:;" class="btn btn-danger deleteRow">-</a></td>
                <td>
                    <select class="form-control js-example-basic-single" name="Material[]" title="Select Material" >
                        <option value="" disabled selected>Select Material</option>
                        @foreach ($rawMaterials as $material)
                            <option value="{{ $material->id }}">{{ $material->Name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select class="form-control js-example-basic-single" name="Currency[]" title="Select Currency" >
                        <option value="" disabled selected>Select Currency</option>
                        @foreach ($productCurrency as $currency)
                            <option value="{{ $currency->id }}">{{ $currency->Name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="number" step=".01" class="form-control" name="Price[]" value="0" placeholder="">
                </td>
            </tr>`;
            var row = $(newRow);
            $(".newMaterialBasePrice").append(row);
            initSelect2(); 
        });
    
        $('.newMaterialBasePrice').on('click', '.deleteRow', function(){
            $(this).closest('tr').remove();
        });
    });
</script>


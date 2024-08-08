<div class="modal fade" id="editExchangeRate-{{$currency->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Exchange Rates</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_product" action="{{url('update_currency_exchange/'.$currency->id)}}">
                    @csrf
                    <div class="form-group">
                        <label for="name">Effective Date :</label>
                        <input type="date" class="form-control" name="effective_date" value="{{$currency->EffectiveDate}}" required>
                    </div>
                    <div class="form-group">
                        <label for="name">From Currency :</label>
                        <select name="from_currency" class="form-control js-example-basic-single" required>
                            <option value="">-From Currency-</option>
                            @foreach ($currencies as $c)
                                <option value="{{$c->id}}" @if($c->id == $currency->FromCurrencyId) selected @endif>{{$c->Name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">To Currency :</label>
                        <select name="to_currency" class="form-control js-example-basic-single" required>
                            <option value="">-From Currency-</option>
                            @foreach ($currencies as $c)
                                <option value="{{$c->id}}"  @if($c->id == $currency->ToCurrencyId) selected @endif>{{$c->Name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">Rate :</label>
                        <input type="text" name="rate" class="form-control" value="{{$currency->ExchangeRate}}" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Update</button>
                    </div> 
                </form>
            </div>
        </div>
    </div>
</div>
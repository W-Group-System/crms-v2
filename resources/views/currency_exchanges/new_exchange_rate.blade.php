<div class="modal fade" id="addExchangeRates" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Exchange Rates</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_product" action="{{url('new_currency_exchange')}}">
                    @csrf
                    <div class="form-group">
                        <label for="name">Effective Date :</label>
                        <input type="date" class="form-control" name="effective_date" value="{{date('Y-m-d')}}" required>
                    </div>
                    <div class="form-group">
                        <label for="name">From Currency :</label>
                        <select name="from_currency" class="form-control js-example-basic-single" required>
                            <option value="">-From Currency-</option>
                            @foreach ($currencies as $c)
                                <option value="{{$c->id}}">{{$c->Name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">To Currency :</label>
                        <select name="to_currency" class="form-control js-example-basic-single" required>
                            <option value="">-To Currency-</option>
                            @foreach ($currencies as $c)
                                <option value="{{$c->id}}">{{$c->Name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">Rate :</label>
                        <input type="number" name="rate" class="form-control" step=".01" placeholder="0.00" required>
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
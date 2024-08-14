<?php

namespace App\Http\Controllers;
use App\CurrencyExchange;
use App\PriceCurrency;
use Validator;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class CurrencyExchangeController extends Controller
{
    // List
    public function index(Request $request)
    {   
        $currency_exchanges = CurrencyExchange::with(['fromCurrency', 'toCurrency'])
            ->when($request->search, function($query)use($request) {
                $query->where('ExchangeRate', 'LIKE', '%'.$request->search.'%')
                ->orWhereHas('fromCurrency', function($query)use($request) {
                    $query->where('Name', 'LIKE', '%'.$request->search.'%');
                });
                
            })
            ->latest()
            ->paginate(10);

        $currencies = PriceCurrency::get();
        $search = $request->search;
        
        return view('currency_exchanges.index', compact('currency_exchanges', 'currencies', 'search')); 
    }

    // Store
    public function store(Request $request)
    {
        $request->validate([
            'rate' => 'numeric',
            'from_currency' => 'different:to_currency',
        ]);

        $currencyExhange = new CurrencyExchange;
        $currencyExhange->EffectiveDate = $request->effective_date;
        $currencyExhange->FromCurrencyId = $request->from_currency;
        $currencyExhange->ToCurrencyId = $request->to_currency;
        $currencyExhange->ExchangeRate = $request->rate;
        $currencyExhange->save();

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back();
    }

    // Update
    public function update(Request $request, $id)
    {
        $request->validate([
            'rate' => 'numeric',
            'from_currency' => 'different:to_currency',
        ]);

        $currencyExhange = CurrencyExchange::findOrFail($id);
        $currencyExhange->EffectiveDate = $request->effective_date;
        $currencyExhange->FromCurrencyId = $request->from_currency;
        $currencyExhange->ToCurrencyId = $request->to_currency;
        $currencyExhange->ExchangeRate = $request->rate;
        $currencyExhange->save();

        Alert::success('Successfully Update')->persistent('Dismiss');
        return back();
    }

    // Delete
    public function delete($id)
    {
        $data = CurrencyExchange::findOrFail($id);
        $data->delete();

        Alert::success('Successfully Deleted')->persistent('Dismiss');
        return back();
    }

    public function edit(Request $request)
    {
        $currency = CurrencyExchange::findOrFail($request->id);

        return array(
            'EffectiveDate' => $currency->EffectiveDate,
            'FromCurrency' => $currency->FromCurrencyId,
            'ToCurrency' => $currency->ToCurrencyId,
            'ExchangeRate' => $currency->ExchangeRate
        );
    }
}
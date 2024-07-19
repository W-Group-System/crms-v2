<?php

namespace App\Http\Controllers;
use App\CurrencyExchange;
use App\PriceCurrency;
use Validator;
use Illuminate\Http\Request;

class CurrencyExchangeController extends Controller
{
    // List
    public function index()
    {   
        $currency_exchanges = CurrencyExchange::with(['fromCurrency', 'toCurrency'])->latest()->paginate(10);
        $currencies = PriceCurrency::get();
        
        return view('currency_exchanges.index', compact('currency_exchanges', 'currencies')); 
    }

    // Store
    public function store(Request $request)
    {
        $rules = [
            'EffectiveDate'     => 'required|date',
            // 'FromCurrencyId'    => 'required|exists:pricecurrencies,id',
            // 'ToCurrencyId'      => 'required|exists:pricecurrencies,id',
            'ExchangeRate'      => 'required|numeric'
        ];

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = [
            'EffectiveDate'  => $request->EffectiveDate,
            'FromCurrencyId' => $request->FromCurrencyId,
            'ToCurrencyId'   => $request->ToCurrencyId,
            'ExchangeRate'   => $request->ExchangeRate
        ];
        CurrencyExchange::create($form_data);

        return response()->json(['success' => 'Data Added Successfully.']);
    }

    // Edit
    public function edit($id)
    {
        if(request()->ajax())
        {
            $data = CurrencyExchange::findOrFail($id);
            return response()->json(['data' => $data]);
        }
    }

    // Update
    public function update(Request $request, $id)
    {
        $rules = [
            'EffectiveDate'     => 'required|date',
            // 'FromCurrencyId'    => 'required|exists:pricecurrencies,id',
            // 'ToCurrencyId'      => 'required|exists:pricecurrencies,id',
            'ExchangeRate'      => 'required|numeric'
        ];

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = [
            'EffectiveDate'  => $request->EffectiveDate,
            'FromCurrencyId' => $request->FromCurrencyId,
            'ToCurrencyId'   => $request->ToCurrencyId,
            'ExchangeRate'   => $request->ExchangeRate
        ];
        CurrencyExchange::whereId($id)->update($form_data);

        return response()->json(['success' => 'Data is Successfully Updated.']);
    }

    // Delete
    public function delete($id)
    {
        $data = CurrencyExchange::findOrFail($id);
        $data->delete();
    }
}
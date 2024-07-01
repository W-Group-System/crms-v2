<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class CurrencyExchange extends Model
{
    use SoftDeletes;
    protected $table = "currencyexchangerates";
    protected $fillable = [
        'EffectiveDate', 'FromCurrencyId', 'ToCurrencyId', 'ExchangeRate'
    ];

    public function fromCurrency()
    {
        return $this->belongsTo(PriceCurrency::class, 'FromCurrencyId', 'id');
    }

    public function toCurrency()
    {
        return $this->belongsTo(PriceCurrency::class, 'ToCurrencyId', 'id');
    }

}

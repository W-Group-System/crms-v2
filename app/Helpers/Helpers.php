<?php

namespace App\Helpers;

use App\BasePrice;
use App\CurrencyExchange;
use Illuminate\Support\Facades\DB;

class Helpers {
    public static function rmc($productRawMaterials)
    {
        $getMaterialId = $productRawMaterials->pluck('MaterialId')->toArray();
        
        $basePrice = BasePrice::whereIn('MaterialId', $getMaterialId)
            ->whereIn('Id', function ($query) {
            $query->select(DB::raw('MAX(Id)'))
                ->from('productmaterialbaseprices')
                ->where('status', 3)
                ->groupBy('MaterialId');
            })
            ->orderBy('EffectiveDate', 'desc')
            ->pluck('Price');

        $getPercent = $productRawMaterials->map(function($item, $key) 
        {
            $num = $item['Percentage'] / 100;

            return $num;
        });

        $multiply = $basePrice->map(function($item, $key)use($getPercent) 
        {
            $num = $item * $getPercent[$key];

            return round($num, 2);
        });

        return $multiply->sum();
    }

    public static function usdToEur($cost)
    {
        $currencyExchangeRates = CurrencyExchange::whereHas('fromCurrency', function($q)
            {
                $q->where('FromCurrencyId', 2)->where('ToCurrencyId', 1);
            })
            ->first();

        $eur = $currencyExchangeRates->ExchangeRate * $cost;

        return round($eur, 2);
    }

    public static function usdToPhp($cost)
    {
        $currencyExchangeRates = CurrencyExchange::whereHas('fromCurrency', function($q)
            {
                $q->where('FromCurrencyId', 2)->where('ToCurrencyId', 3);
            })
            ->first();

        $php = $currencyExchangeRates->ExchangeRate * $cost;

        return round($php, 2);
    }
}
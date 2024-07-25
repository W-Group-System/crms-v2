<?php

namespace App\Helpers;

use App\BasePrice;
use App\CurrencyExchange;
use App\ProductMaterialsComposition;
use Illuminate\Support\Facades\DB;

class Helpers {
    public static function rmc($productRawMaterials, $id)
    {
        $getMaterialId = $productRawMaterials->pluck('MaterialId')->toArray();

        $productComposition = ProductMaterialsComposition::where('ProductId', $id)
            ->whereIn('MaterialId', $getMaterialId)
            ->orderBy('MaterialId', 'asc')
            ->pluck('Percentage');

        $basePrice = BasePrice::whereIn('MaterialId', $getMaterialId)
            ->orderBy('MaterialId', 'asc')
            ->orderBy('EffectiveDate', 'desc')
            ->get()
            ->groupBy('MaterialId')
            ->map(function($item) {
                return $item->first();
            })
            ->pluck('Price');
        
        $getPercent = $productComposition->map(function($item, $key) 
        {
            $num = $item / 100;

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
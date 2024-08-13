<?php
use App\BasePrice;
use App\CurrencyExchange;
use App\CustomerRequirement;
use App\Product;
use App\ProductMaterialsComposition;
use App\UserAccessModule;
use App\RequestProductEvaluation;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

function rmc($productRawMaterials, $id)
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

function usdToEur($cost)
{
    $currencyExchangeRates = CurrencyExchange::whereHas('fromCurrency', function($q)
        {
            $q->where('FromCurrencyId', 2)->where('ToCurrencyId', 1);
        })
        ->first();

    $eur = $currencyExchangeRates->ExchangeRate * $cost;

    return round($eur, 2);
}

function usdToPhp($cost)
{
    $currencyExchangeRates = CurrencyExchange::whereHas('fromCurrency', function($q)
        {
            $q->where('FromCurrencyId', 2)->where('ToCurrencyId', 3);
        })
        ->first();

    $php = $currencyExchangeRates->ExchangeRate * $cost;

    return round($php, 2);
}

function identicalComposition($materials, $product_id)
{
    $getMaterialId = $materials->pluck('MaterialId')->toArray();
    $getPercentage = $materials->pluck('Percentage')->toArray();

    $providedComposition = array_map(null, $getMaterialId, $getPercentage);
    
    $matchingProductIds = ProductMaterialsComposition::select('ProductId')
        ->where(function ($q) use ($providedComposition) {
            foreach ($providedComposition as $composition) {
                $q->orWhere(function ($q) use ($composition) {
                    $q->where('MaterialId', $composition[0])
                    ->where('Percentage', $composition[1]);
                });
            }
        })
        ->groupBy('ProductId')
        ->having(DB::raw("COUNT(*)"), count($providedComposition))
        ->pluck('ProductId');

    $matchingProducts = ProductMaterialsComposition::select('ProductId')
        ->whereIn('ProductId', $matchingProductIds)
        ->where('ProductId', '!=', $product_id)
        ->groupBy('ProductId')
        ->get();

    return $matchingProducts;
}

function customerRequirements($product)
{
    $customerRequirement = CustomerRequirement::where('Recommendation', "LIKE", "%".$product."%")->get();

    return $customerRequirement;
}

function getProductIdByCode($code)
{
    $product = Product::where('code', $code)->first();
    
    return $product ? $product->id : null;
}

function viewModule($module, $department, $role)
{
    $user_access = UserAccessModule::where('module_name', $module)->where('department_id', $department)->where('role_id', $role)->first();

    if ($user_access != null)
    {
        if ($user_access->view != null)
            return "yes";
        {
            return "no";
        }
    }
}
function getRpeIdByNumber($number)
{
    $rpe = RequestProductEvaluation::where('RpeNumber', $number)->first();
    
    return $rpe ? $rpe->id : null;
}
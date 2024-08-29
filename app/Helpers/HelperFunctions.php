<?php
use App\BasePrice;
use App\CurrencyExchange;
use App\CustomerRequirement;
use App\Product;
use App\ProductMaterialsComposition;
use App\UserAccessModule;
use App\RequestProductEvaluation;
use App\SalesApprovers;
use App\User;
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

    if ($currencyExchangeRates != null){

        $eur = $currencyExchangeRates->ExchangeRate * $cost;

        return round($eur, 2);
    }
    
}

// function usdToPhp($cost)
// {
//     $currencyExchangeRates = CurrencyExchange::whereHas('fromCurrency', function($q)
//         {
//             $q->where('FromCurrencyId', 2)->where('ToCurrencyId', 3);
//         })
//         ->first();

//         if ($currencyExchangeRates != null){
//             $php = $currencyExchangeRates->ExchangeRate * $cost;

//             return round($php, 2);
//         } 

   
// }

function usdToPhp($cost)
{
    $currencyExchangeRates = CurrencyExchange::whereHas('fromCurrency', function($q) {
        $q->where('FromCurrencyId', 2)->where('ToCurrencyId', 3);
    })
    ->first();

    $exchangeRate = ($currencyExchangeRates != null) ? $currencyExchangeRates->ExchangeRate : 1;

    $php = $exchangeRate * $cost;

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

function getCrrIdByNumber($number)
{
    $crr = CustomerRequirement::where('CrrNumber', $number)->first();
    
    return $crr ? $crr->id : null;
}

function productRps($code)
{
    $rpe = RequestProductEvaluation::where('RpeResult', 'LIKE', '%'.$code.'%')->get();

    return $rpe;
}
function linkToRpe($rpeNumber)
{
    $rpe = RequestProductEvaluation::where('RpeNumber', $rpeNumber)->first();
    
    if($rpe != null)
    {
        return $rpe->id;
    }
}

function checkRolesIfHaveApprove($module, $department, $role)
{
    $user_access = UserAccessModule::where('module_name', $module)->where('department_id', $department)->where('role_id', $role)->first();
    
    if ($user_access != null)
    {
        if ($user_access->approve != null)
        {
            return "yes";
        }
        
        return "no";
    }
}

// function checkIfHaveActivities($role)
// {
//     if (($role->department_id == 5 || $role->department_id == 38) && $role->name == "Department Admin")
//     {
//         return "yes";
//     }
    
//     return "no";
// }

function checkIfHaveFiles($role)
{
    if (($role->department_id == 5 || $role->department_id == 38 || $role->department_id == 15) && ($role->name == "Department Admin" || $role->name == "Staff L2"))
    {
        return "yes";
    }
    
    return "no";
}

function checkIfItsManagerOrSupervisor($role)
{
    if (($role->department_id == 5 || $role->department_id == 38 || $role->department_id == 15 || $role->department_id == 42) && ($role->name == "Department Admin" || $role->name == "Staff - L2"))
    {
        return "yes";
    }
    
    return "no";
}

function checkIfItsApprover($user_id, $primary_sales_person, $type)
{
    if ($type == "CRR")
    {
        $user = User::where('id', $primary_sales_person)->orWhere('user_id', $primary_sales_person)->first();

        $salesApprovers = SalesApprovers::where('SalesApproverId', $user_id)->where('UserId', $user->id)->first();
        
        if ($salesApprovers != null)
        {
            return "yes";
        }
    }
    if ($type == "SRF")
    {
        $user = User::where('user_id', $primary_sales_person)->first();

        $salesApprovers = SalesApprovers::where('SalesApproverId', $user_id)->where('UserId', $user->id)->first();
        
        if ($salesApprovers != null)
        {
            return "yes";
        }
    }
    if ($type == "PRF")
    {
        $user = User::where('user_id', $primary_sales_person)->first();

        $salesApprovers = SalesApprovers::where('SalesApproverId', $user_id)->where('UserId', $user->id)->first();
        
        if ($salesApprovers != null)
        {
            return "yes";
        }
    }

    return "no";
}

function checkRolesIfHaveCreate($module, $department, $role)
{
    $user_access = UserAccessModule::where('module_name', $module)->where('department_id', $department)->where('role_id', $role)->first();
    
    if ($user_access != null)
    {
        if ($user_access->create != null)
        {
            return "yes";
        }
        
        return "no";
    }
}

function authCheckIfItsSales($department)
{
    if ($department == 5 || $department == 38)
    {
        return true;
    }
    
    return false;
}

function authCheckIfItsRnd($department)
{
    if ($department == 15)
    {
        return true;
    }
    
    return false;
}

function authCheckIfItsRndStaff($role)
{
    if (($role->department_id == 15 || $role->department_id == 42) && $role->name == "Staff L1")
    {
        return true;
    }
    
    return false;
}

function rndPersonnel($personnel, $user_id)
{
    $p = $personnel->pluck('PersonnelUserId')->toArray();
    
    return collect($p)->contains($user_id);
}

function linkToCrr($crrNumber)
{
    $crr = CustomerRequirement::where('CrrNumber', $crrNumber)->first();
    
    if($crr != null)
    {
        return $crr->id;
    }
}

function checkIfItsSalesDept($department)
{
    if ($department == 5 || $department == 38)
    {
        return true;
    }

    return false;
}

function rndManager($role)
{
    if ($role->department_id == 15 || $role->id == 14 || $role->department_id == 42)
    {
        return true;
    }
    // dd($role);
    return false;
}

function getUserApprover($approver)
{
    $user = User::whereIn('id', ($approver->pluck('UserId')))->orWhere('id', auth()->user()->id)->get();
    
    return $user;
}
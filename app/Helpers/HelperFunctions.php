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
use App\UserEventLogs;
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
        ->orderBy('EffectiveDate', 'desc')
        ->first();

    if ($currencyExchangeRates != null){

        $eur = $currencyExchangeRates->ExchangeRate * $cost;

        return $eur;
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
    ->orderBy('EffectiveDate', 'desc')
    ->first();

    $exchangeRate = ($currencyExchangeRates != null) ? $currencyExchangeRates->ExchangeRate : 1;

    $php = $exchangeRate * $cost;

    return $php;
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
    $product = Product::where('code', $code)->where('status', 4)->first();
    
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
    if (($role->department_id == 5 || $role->department_id == 38 || $role->department_id == 15) && ($role->name == "Department Admin" || $role->name == "Staff L2" || $role->name == "Staff L1"))
    {
        return "yes";
    }
    
    return "no";
}

function checkIfItsManagerOrSupervisor($role)
{
    if (($role->department_id == 5 || $role->department_id == 38 || $role->department_id == 15 || $role->department_id == 42) && ($role->name == "Department Admin" || $role->name == "Staff L2"))
    {
        return "yes";
    }
    
    return "no";
}

function checkIfItsSalesManager($role)
{
    if (($role->department_id == 5 || $role->department_id == 38) && ($role->name == "Department Admin"))
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

function authCheckIfItsSalesManager($role)
{
    if ($role == 21)
    {
        return true;
    }
    
    return false;
}

function authCheckIfItsRnd($department)
{
    if ($department == 15 || $department == 42)
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
    if (($role->department_id == 15 || $role->id == 14 || $role->department_id == 42) &&( $role->name == "Department Admin" || $role->name == "Staff L2"))
    {
        return true;
    }
    // dd($role);
    return false;
}

function getUserApprover($approver)
{
    $user = User::whereIn('id', ($approver->pluck('UserId')->toArray()))->orWhere('id', auth()->user()->id)->get();
    
    return $user;
}
function getHistoricalPrices($materialId) {
    return BasePrice::where('MaterialId', $materialId)
        ->orderBy('EffectiveDate', 'desc')
        ->get();
}
function authCheckIfItsCustomerService($role)
{
    if ($role == 11)
    {
        return true;
    }
    
    return false;
}

function physioChemicalAnalysis()
{
    $physio_chemical_analysis = [
        [
            'Parameter' => 'Loss on Drying',
            'Value' => 'Max 12.0%',
            'Remarks' => 'at 105°C to constant weight; WI-QCD-32',
        ],
        [
            'Parameter' => 'Sulfate',
            'Value' => '15-40%',
            'Remarks' => 'As SO4̄²,dried basis',
        ],
        [
            'Parameter' => 'Total Ash',
            'Value' => '15-40%',
            'Remarks' => 'Dried Basis',
        ],
        [
            'Parameter' => 'Acid-insoluble Ash',
            'Value' => 'Max 1%',
            'Remarks' => '',
        ],
        [
            'Parameter' => 'Acid-insoluble Matter',
            'Value' => '8-15%',
            'Remarks' => '',
        ],
        [
            'Parameter' => 'Residual Solvent',
            'Value' => 'Max 0.1%',
            'Remarks' => 'Ethanol, isopropanol or methanol',
        ],
        [
            'Parameter' => 'Low Molecular Weight Carrageenan (mol. wt. fraction <50 kDa)',
            'Value' => 'Max 5%',
            'Remarks' => 'As SO4̄²,dried basis',
        ],
        [
            'Parameter' => 'pH',
            'Value' => '',
            'Remarks' => 'WI-08-02i (1.5%); WI-08-02ii (1.0%); WI-R&D-73',
        ],
        [
            'Parameter' => 'Water Viscosity',
            'Value' => '',
            'Remarks' => 'WI-08-03i (1.5%); WI-08-03ii (1.0%); WI-R&D-74',
        ],
        [
            'Parameter' => 'Milk Viscosity',
            'Value' => '',
            'Remarks' => 'WI-08-10x; WI-R&D-77',
        ],
        [
            'Parameter' => 'Water Gel Strength',
            'Value' => '',
            'Remarks' => 'WI-08-04i; WI-R&D-38',
        ],
        [
            'Parameter' => 'Potassium Gel Strength',
            'Value' => '',
            'Remarks' => 'WI-08-05i; WI-R&D-62 ',
        ],
        [
            'Parameter' => 'Brine Gel Strength',
            'Value' => '',
            'Remarks' => 'WI-08-06i; WI-R&D-56',
        ],
        [
            'Parameter' => 'Milk Gel Strength',
            'Value' => '',
            'Remarks' => 'WI-08-07i; WI-R&D-33',
        ],
        [
            'Parameter' => 'Calcium Gel Strength',
            'Value' => '',
            'Remarks' => 'WI-08-08i; WI-R&D-15',
        ],
        [
            'Parameter' => 'Dessert Gel Strength',
            'Value' => '',
            'Remarks' => 'WI-08-04vi; WI-R&D-39',
        ],
    ];

    return collect($physio_chemical_analysis)->map(function($item) {
        return (object) $item;
    });
}

function microbiologicalAnalysis()
{
    $microbiological_analysis = [
        [
            'Parameter' => 'Total Plate Count',
            'Value' => 'Max 5,000CFU/g',
            'Remarks' => '35°C for 48 hours incubation; WI-QCD-24',
        ],
        [
            'Parameter' => 'Yeast and Molds',
            'Value' => 'Max 300 CFU/g',
            'Remarks' => '35°C for 48 hours incubation; WI-QCD-26',
        ],
        [
            'Parameter' => 'Salmonella',
            'Value' => 'Absent in 10g',
            'Remarks' => '35°C for 48 hours incubation; WI-QCD-21',
        ],
        [
            'Parameter' => 'E. coli',
            'Value' => 'Absent in 5g',
            'Remarks' => '35°C for 48 hours incubation; WI-QCD-10',
        ],
    ];

    return collect($microbiological_analysis)->map(function($item) {
        return (object) $item;
    });
}

function heavyMetals()
{
    $heavy_metals = [
        [
            'Parameter' => 'Lead (Pb)',
            'Value' => 'Max 5 ppm',
        ],
        [
            'Parameter' => 'Arsenic (As)',
            'Value' => 'Max 3 ppm',
        ],
        [
            'Parameter' => 'Mercury (Hg)',
            'Value' => 'Max 1 ppm',
        ],
        [
            'Parameter' => 'Cadmium (Cd)',
            'Value' => 'Max 2 ppm',
        ],
    ];

    return collect($heavy_metals)->map(function($item) {
        return (object) $item;
    });
}

function nutrionalInformation()
{
    $nutrional_information = [
        [
            'Parameter' => 'Energy',
            'Value' => '288 (kcal)',
        ],
        [
            'Parameter' => 'Fat',
            'Value' => '0 g',
        ],
        [
            'Parameter' => 'Carbohydrates',
            'Value' => '71 g',
        ],
        [
            'Parameter' => 'Fiber, insoluble',
            'Value' => '4 g',
        ],
        [
            'Parameter' => 'Fiber, soluble',
            'Value' => '67 g',
        ],
        [
            'Parameter' => 'Protein',
            'Value' => '1 g',
        ],
        [
            'Parameter' => 'Sodium',
            'Value' => '0.5 g',
        ],
        [
            'Parameter' => 'Potassium',
            'Value' => '4.5 g',
        ],
        [
            'Parameter' => 'Vitamin D',
            'Value' => '0 g',
        ],
    ];

    return collect($nutrional_information)->map(function($item) {
        return (object) $item;
    });
}

function allergens()
{
    $nutrional_information = [
        [
            'Parameter' => 'Cereal containing gluten',
            'IsAllergen' => '',
        ],
        [
            'Parameter' => 'Crustaceans',
            'IsAllergen' => '',
        ],
        [
            'Parameter' => 'Eggs',
            'IsAllergen' => '',
        ],
        [
            'Parameter' => 'Fish',
            'IsAllergen' => '',
        ],
        [
            'Parameter' => 'Soy beans',
            'IsAllergen' => '',
        ],
        [
            'Parameter' => 'Milk (incl. Lactose)',
            'IsAllergen' => '',
        ],
        [
            'Parameter' => 'Nuts',
            'IsAllergen' => '',
        ],
        [
            'Parameter' => 'Celery',
            'IsAllergen' => '',
        ],
        [
            'Parameter' => 'Mustard',
            'IsAllergen' => '',
        ],
        [
            'Parameter' => 'Sesame Seeds',
            'IsAllergen' => '',
        ],
        [
            'Parameter' => 'Sulphur dioxide and sulphites (>10 mg/kg)',
            'IsAllergen' => '',
        ],
        [
            'Parameter' => 'Lupin',
            'IsAllergen' => '',
        ],
        [
            'Parameter' => 'Mollusc',
            'IsAllergen' => '',
        ],
    ];

    return collect($nutrional_information)->map(function($item) {
        return (object) $item;
    });
}

function productManagementLogs($action, $product_code)
{
    $userEventLogs = new UserEventLogs;
    $userEventLogs->timestamp = date('Y-m-d h:i:s');
    $userEventLogs->UserId = auth()->user()->id;
    $userEventLogs->Value = $product_code;
    if ($action == "create")
    {
        $userEventLogs->Details = "Create new product entry.";
    }
    if ($action == "update")
    {
        $userEventLogs->Details = "Update product entry.";
    }
    if ($action == "update_raw_materials")
    {
        $userEventLogs->Details = "Update product material composition entry.";
    }
    if ($action == "create_files")
    {
        $userEventLogs->Details = "Create new product file entry.";
    }
    if ($action == "update_files")
    {
        $userEventLogs->Details = "Update product file entry.";
    }
    if ($action == "move_to_new")
    {
        $userEventLogs->Details = "Submit product entry for approval.";
    }
    if ($action == "move_to_current")
    {
        $userEventLogs->Details = "Approved product entry.";
    }
    if ($action == "archive")
    {
        $userEventLogs->Details = "	Archived product entry.";
    }
    if ($action == "add_to_draft")
    {
        $userEventLogs->Details = "Product has been moved to draft.";
    }
    if ($action == "reject")
    {
        $userEventLogs->Details = "Rejected product entry.";
    }

    $userEventLogs->save();
}

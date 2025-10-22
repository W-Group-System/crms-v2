<?php
use App\BasePrice;
use App\CurrencyExchange;
use App\CustomerRequirement;
use App\GroupSales;
use App\Product;
use App\ProductMaterialsComposition;
use App\UserAccessModule;
use App\RequestProductEvaluation;
use App\SalesApprovers;
use App\SrfProgress;
use App\TransactionLogs;
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

        return $num;
    });

    return $multiply->sum();
}

function getLatestEffectiveDate($productRawMaterials, $id)
{
    $getMaterialId = $productRawMaterials->pluck('MaterialId')->toArray();

    $effective_date = BasePrice::whereIn('MaterialId', $getMaterialId)
        ->orderBy('id', 'desc')
        ->first();

    return $effective_date->EffectiveDate;
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
    $product = Product::where('code', $code)->first();
    
    return $product;
}

function getProductIdByCodeSrf($code)
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
    if (($role->department_id == 5 || $role->department_id == 38 || $role->department_id == 15) && ($role->name == "Department Admin" || $role->name == "Staff L2" || $role->name == "Staff L1"))
    {
        return "yes";
    }
    
    return "no";
}

function checkIfItsManagerOrSupervisor($role)
{
    if (($role->type == 'RND' || $role->type == 'QCD-WHI' || $role->type == 'QCD-MRDC' || $role->type == 'QCD-PBI' || $role->type == 'QCD-CCC' || $role->type == 'LS' || $role->type == 'IS') && ($role->name == "Department Admin" || $role->name == "Staff L2"))
    {
        return "yes";
    }
    
    return "no";
}
function checkIfItsAnalyst($role)
{
    if (($role->type == 'RND' || $role->type == 'QCD-WHI' || $role->type == 'QCD-MRDC' || $role->type == 'QCD-PBI' || $role->type == 'QCD-CCC' || $role->type == 'LS' || $role->type == 'IS') && ($role->name == "Staff L1"))
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
        // $user = User::where('user_id', $primary_sales_person)->first();
        $user = User::where('id', $primary_sales_person)->orWhere('user_id', $primary_sales_person)->first();

        $salesApprovers = SalesApprovers::where('SalesApproverId', $user_id)->where('UserId', $user->id)->first();
        
        if ($salesApprovers != null)
        {
            return "yes";
        }
    }
    if ($type == "PRF")
    {
        $user = User::where('id', $primary_sales_person)->orWhere('user_id', $primary_sales_person)->first();

        $salesApprovers = SalesApprovers::where('SalesApproverId', $user_id)->where('UserId', $user->id)->last();
        // dd($salesApprovers);
        if ($salesApprovers != null)
        {
            return "yes";
        }
    }

    return "no";
}

function checkIfItsApprover2($user_id, $primary_sales_person, $secondary_sales_person, $type)
{
    if ($type == "PRF") {
        $primary_sales_person = strtolower(trim($primary_sales_person));

        $primaryUser = User::whereRaw('LOWER(TRIM(user_id)) = ?', [ $primary_sales_person ])
                           ->orWhereRaw('LOWER(TRIM(id)) = ?', [ $primary_sales_person ])
                           ->first();
        
                           
        $salesApprovers = SalesApprovers::where('SalesApproverId', $user_id)
                                         ->where('UserId', $primaryUser->id)
                                         ->first();

        if ($salesApprovers != null) {
            if ($user_id == $secondary_sales_person || auth()->user()->user_id == $secondary_sales_person) {
                return "yes"; 
            }
        }
    }
    if ($type == "SRF") {
        $primary_sales_person = strtolower(trim($primary_sales_person));

        $primaryUser = User::whereRaw('LOWER(TRIM(user_id)) = ?', [ $primary_sales_person ])
                           ->orWhereRaw('LOWER(TRIM(id)) = ?', [ $primary_sales_person ])
                           ->first();
        
                           
        $salesApprovers = SalesApprovers::where('SalesApproverId', $user_id)
                                         ->where('UserId', $primaryUser->id)
                                         ->first();

        if ($salesApprovers != null) {
            if ($user_id == $secondary_sales_person || auth()->user()->user_id == $secondary_sales_person) {
                return "yes"; 
            }
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
    if ($role == 10)
    {
        return true;
    }
    
    return false;
}

function authCheckIfItsRnd($department)
{
    if ($department == 15 || $department == 42 || $department == 20)
    {
        return true;
    }
    
    return false;
}

function authCheckIfItsRndStaff($role)
{
    if (($role->department_id == 15 || $role->department_id == 42 ) && $role->name == "Staff L1")
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
    // if (($role->department_id == 15 || $role->id == 14 || $role->department_id == 42 || $role->department_id == 79 || $role->department_id == 20) &&( $role->name == "Department Admin" || $role->name == "Staff L2"))
    // {
    //     return true;
    // }
    // dd($role);

    if (($role->type == 'RND' || str_contains($role->type, 'QCD')) && ($role->name == 'Department Admin' || $role->name == "Staff L2"))
    {
        return true;
    }

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

function primarySalesApprover($primary_sales,$user_login)
{
    // $user_data = User::where('user_id', $primary_sales)->orWhere('id', $primary_sales)->first(); 

    $user_data = User::whereRaw('LOWER(TRIM(user_id)) = ?', [ $primary_sales ])
                           ->orWhereRaw('LOWER(TRIM(id)) = ?', [ $primary_sales ])
                           ->first();

    if ($user_data != null)
    {
        $user_id = $user_data->id;
        $sales_approvers = SalesApprovers::where('SalesApproverId', $user_login)->where('UserId', $user_id)->get();
    }
    else
    {
        $sales_approvers = SalesApprovers::where('SalesApproverId', $user_login)->where('UserId', $primary_sales)->get();
    }
    
    if ($sales_approvers->isNotEmpty())
    {
        return true;
    }

    return false;
}

function srfPrimarySalesApprover($user_id, $primary_sales_person, $secondary_sales_person)
{
    $primary_sales_person = strtolower(trim($primary_sales_person));

    $primaryUser = User::whereRaw('LOWER(TRIM(user_id)) = ?', [ $primary_sales_person ])
                           ->orWhereRaw('LOWER(TRIM(id)) = ?', [ $primary_sales_person ])
                           ->first();

    $currentUser = User::whereRaw('LOWER(TRIM(user_id)) = ?', [ $user_id ])
                           ->orWhereRaw('LOWER(TRIM(id)) = ?', [ $user_id ])
                           ->first();

    $salesApprovers = SalesApprovers::where('SalesApproverId', $currentUser->id)
                                         ->where('UserId', $primaryUser->id)
                                         ->first();
    
    if ($salesApprovers != null) {
       
            return "true"; 
        
    }

    return false;
}

function srfSecondary($user_id, $primary_sales_person, $secondary_sales_person)
{
    // $primary_sales_person = strtolower(trim($primary_sales_person));

    // $primaryUser = User::whereRaw('LOWER(TRIM(user_id)) = ?', [ $primary_sales_person ])
    //                        ->orWhereRaw('LOWER(TRIM(id)) = ?', [ $primary_sales_person ])
    //                        ->first();

    // $currentUser = User::whereRaw('LOWER(TRIM(user_id)) = ?', [ $user_id ])
    //                        ->orWhereRaw('LOWER(TRIM(id)) = ?', [ $user_id ])
    //                        ->first();

    // $salesApprovers = SalesApprovers::where('SalesApproverId', $currentUser->id)
    //                                      ->where('UserId', $primaryUser->id)
    //                                      ->first();
    
    // if ($salesApprovers != null) {
        if ($user_id == $secondary_sales_person || auth()->user()->user_id == $secondary_sales_person ) {
            return "true"; 
        // }
    }

    return false;
}
function prfPrimarySalesApprover($user_id, $primary_sales_person, $secondary_sales_person)
{
    $primary_sales_person = strtolower(trim($primary_sales_person));

    $primaryUser = User::whereRaw('LOWER(TRIM(user_id)) = ?', [ $primary_sales_person ])
                           ->orWhereRaw('LOWER(TRIM(id)) = ?', [ $primary_sales_person ])
                           ->first();

    $currentUser = User::whereRaw('LOWER(TRIM(user_id)) = ?', [ $user_id ])
                           ->orWhereRaw('LOWER(TRIM(id)) = ?', [ $user_id ])
                           ->first();

    $salesApprovers = SalesApprovers::where('SalesApproverId', $currentUser->id)
                                         ->where('UserId', $primaryUser->id)
                                         ->first();
    
    if ($salesApprovers != null) {
       
            return "true"; 
        
    }

    return false;
}

function prfSecondary($user_id, $primary_sales_person, $secondary_sales_person)
{
    
        if ($user_id == $secondary_sales_person || auth()->user()->user_id == $secondary_sales_person ) {
            return "true";
    }

    return false;
}

function crrHistoryLogs($action, $crr)
{
    $transaction_logs = new TransactionLogs;
    $transaction_logs->Type = 10;
    $transaction_logs->TransactionId = $crr;
    $transaction_logs->ActionDate = date('Y-m-d h:i:s');
    $transaction_logs->UserId = auth()->user()->id;

    if ($action == 'create')
    {
        $transaction_logs->Details = "Create new customer requirement entry.";
    }
    if ($action == 'update')
    {
        $transaction_logs->Details = "Update customer requirement entry.";
    }
    if ($action == 'close')
    {
        $transaction_logs->Details = "Close customer requirement entry.";
    }
    if ($action == 'open')
    {
        $transaction_logs->Details = "Open customer requirement entry.";
    }
    if ($action == 'cancel')
    {
        $transaction_logs->Details = "Cancel customer requirement entry.";
    }
    if ($action == 'approve')
    {
        $transaction_logs->Details = "Approve customer requirement entry.";
    }
    if ($action == 'return_to_sales')
    {
        $transaction_logs->Details = "Return customer requirement entry.";
    }
    if ($action == 'received')
    {
        $transaction_logs->Details = "Received customer requirement entry.";
    }
    if ($action == 'add_supplementary')
    {
        $transaction_logs->Details = "Add new customer requirement supplementary details entry.";
    }
    if ($action == 'update_supplementary')
    {
        $transaction_logs->Details = "Update customer requirement supplementary details entry.";
    }
    if ($action == 'delete_supplementary')
    {
        $transaction_logs->Details = "Delete customer requirement supplementary details entry.";
    }
    if ($action == 'add_personnel')
    {
        $transaction_logs->Details = "Add new customer requirement personnel entry.";
    }
    if ($action == 'update_personnel')
    {
        $transaction_logs->Details = "Update assigned customer requirement personnel entry.";
    }
    if ($action == 'delete_personnel')
    {
        $transaction_logs->Details = "Delete customer requirement personnel entry.";
    }
    if ($action == 'start')
    {
        $transaction_logs->Details = "Start customer requirement transaction.";
    }
    if ($action == 'pause')
    {
        $transaction_logs->Details = "Pause customer requirement transaction.";
    }
    if ($action == 'submit_initial' || $action == 'submit_final')
    {
        $transaction_logs->Details = "Submit customer requirement transaction for review.";
    }
    if ($action == 'return_to_specialist')
    {
        $transaction_logs->Details = "Return customer requirement transaction.";
    }
    if ($action == 'complete')
    {
        $transaction_logs->Details = "Complete customer requirement transaction.";
    }
    if ($action == 'sales_accepted')
    {
        $transaction_logs->Details = "Accept customer requirement transaction.";
    }
    if ($action == 'add_files')
    {
        $transaction_logs->Details = "Add new customer requirement file entry.";
    }
    if ($action == 'update_files')
    {
        $transaction_logs->Details = "Update customer requirement file entry.";
    }
    if ($action == 'delete_files')
    {
        $transaction_logs->Details = "Delete customer requirement file entry.";
    }

    $transaction_logs->save();
}

function rpeHistoryLogs($action, $rpe)
{
    $transaction_logs = new TransactionLogs;
    $transaction_logs->Type = 20;
    $transaction_logs->TransactionId = $rpe;
    $transaction_logs->ActionDate = date('Y-m-d h:i:s');
    $transaction_logs->UserId = auth()->user()->id;

    if ($action == 'create')
    {
        $transaction_logs->Details = "Create new product evaluation entry.";
    }
    if ($action == 'update')
    {
        $transaction_logs->Details = "Update product evaluation entry.";
    }
    if ($action == 'close')
    {
        $transaction_logs->Details = "Close product evaluation entry.";
    }
    if ($action == 'open')
    {
        $transaction_logs->Details = "Open product evaluation entry.";
    }
    if ($action == 'cancel')
    {
        $transaction_logs->Details = "Cancel product evaluation entry.";
    }
    if ($action == 'approve')
    {
        $transaction_logs->Details = "Approve product evaluation entry.";
    }
    if ($action == 'return_to_sales')
    {
        $transaction_logs->Details = "Return product evaluation entry.";
    }
    if ($action == 'received')
    {
        $transaction_logs->Details = "Received product evaluation entry.";
    }
    if ($action == 'add_supplementary')
    {
        $transaction_logs->Details = "Add new product evaluation supplementary details entry.";
    }
    if ($action == 'update_supplementary')
    {
        $transaction_logs->Details = "Update product evaluation supplementary details entry.";
    }
    if ($action == 'delete_supplementary')
    {
        $transaction_logs->Details = "Delete product evaluation supplementary details entry.";
    }
    if ($action == 'add_personnel')
    {
        $transaction_logs->Details = "Add new product evaluation personnel entry.";
    }
    if ($action == 'update_personnel')
    {
        $transaction_logs->Details = "Update assigned product evaluation personnel entry.";
    }
    if ($action == 'delete_personnel')
    {
        $transaction_logs->Details = "Delete product evaluation personnel entry.";
    }
    if ($action == 'start')
    {
        $transaction_logs->Details = "Start product evaluation transaction.";
    }
    if ($action == 'pause')
    {
        $transaction_logs->Details = "Pause product evaluation transaction.";
    }
    if ($action == 'submit_initial' || $action == 'submit_final')
    {
        $transaction_logs->Details = "Submit product evaluation transaction for review.";
    }
    if ($action == 'return_to_specialist')
    {
        $transaction_logs->Details = "Return product evaluation transaction.";
    }
    if ($action == 'complete')
    {
        $transaction_logs->Details = "Complete product evaluation transaction.";
    }
    if ($action == 'sales_accepted')
    {
        $transaction_logs->Details = "Accept product evaluation transaction.";
    }
    if ($action == 'add_files')
    {
        $transaction_logs->Details = "Add new product evaluation file entry.";
    }
    if ($action == 'update_files')
    {
        $transaction_logs->Details = "Update product evaluation file entry.";
    }
    if ($action == 'delete_files')
    {
        $transaction_logs->Details = "Delete product evaluation file entry.";
    }

    $transaction_logs->save();
}
function srfHistoryLogs($action, $srf)
{
    $transaction_logs = new TransactionLogs;
    $transaction_logs->Type = 30;
    $transaction_logs->TransactionId = $srf;
    $transaction_logs->ActionDate = date('Y-m-d h:i:s');
    $transaction_logs->UserId = auth()->user()->id;

    if ($action == 'create')
    {
        $transaction_logs->Details = "Create new sample request entry.";
    }
    if ($action == 'update')
    {
        $transaction_logs->Details = "Update sample request entry.";
    }
    if ($action == 'close')
    {
        $transaction_logs->Details = "Close sample request entry.";
    }
    if ($action == 'open')
    {
        $transaction_logs->Details = "Open sample request entry.";
    }
    if ($action == 'cancel')
    {
        $transaction_logs->Details = "Cancel sample request entry.";
    }
    if ($action == 'approve')
    {
        $transaction_logs->Details = "Approve sample request entry.";
    }
    if ($action == 'return_to_sales')
    {
        $transaction_logs->Details = "Return sample request entry.";
    }
    if ($action == 'received')
    {
        $transaction_logs->Details = "Received sample request entry.";
    }
    if ($action == 'add_supplementary')
    {
        $transaction_logs->Details = "Add new sample request supplementary details entry.";
    }
    if ($action == 'update_supplementary')
    {
        $transaction_logs->Details = "Update sample request supplementary details entry.";
    }
    if ($action == 'delete_supplementary')
    {
        $transaction_logs->Details = "Delete sample request supplementary details entry.";
    }
    if ($action == 'add_personnel')
    {
        $transaction_logs->Details = "Add new sample request personnel entry.";
    }
    if ($action == 'update_personnel')
    {
        $transaction_logs->Details = "Update assigned sample request personnel entry.";
    }
    if ($action == 'delete_personnel')
    {
        $transaction_logs->Details = "Delete sample request personnel entry.";
    }
    if ($action == 'start')
    {
        $transaction_logs->Details = "Start sample request transaction.";
    }
    if ($action == 'pause')
    {
        $transaction_logs->Details = "Pause sample request transaction.";
    }
    if ($action == 'submit_initial' || $action == 'submit_final')
    {
        $transaction_logs->Details = "Submit sample request transaction for review.";
    }
    if ($action == 'return_to_specialist')
    {
        $transaction_logs->Details = "Return sample request transaction.";
    }
    if ($action == 'complete')
    {
        $transaction_logs->Details = "Complete sample request transaction.";
    }
    if ($action == 'sales_accepted')
    {
        $transaction_logs->Details = "Accept sample request transaction.";
    }
    if ($action == 'add_files')
    {
        $transaction_logs->Details = "Add new sample request file entry.";
    }
    if ($action == 'update_files')
    {
        $transaction_logs->Details = "Update sample request file entry.";
    }
    if ($action == 'delete_files')
    {
        $transaction_logs->Details = "Delete sample request file entry.";
    }
    if ($action == 'add_raw_mats')
    {
        $transaction_logs->Details = "Create sample request raw materials entry.";
    }
    if ($action == 'edit_raw_mats')
    {
        $transaction_logs->Details = "Edit sample request raw materials entry.";
    }
    if ($action == 'delete_raw_mats')
    {
        $transaction_logs->Details = "Delete sample request raw materials entry.";
    }
    if ($action == 'sales_initial_quantity')
    {
        $transaction_logs->Details = "Sales Initial Approved sample request raw materials entry.";
    }

    $transaction_logs->save();
}

function historyRmc($product_material_composition, $product_id)
{
    // $percentage = $product_material_composition->sortBy('MaterialId')->pluck('Percentage');
    $material_id = $product_material_composition->sortBy('MaterialId')->pluck('MaterialId')->toArray();
    $product_material_composition = ProductMaterialsComposition::whereIn('MaterialId', $material_id)
        ->where('ProductId', $product_id)
        // ->orderBy('MaterialId', 'asc')
        ->get();
    $final_id = $product_material_composition->sortByDesc('Percentage')->first();
    $final_id_d = $product_material_composition->sortBy('Percentage')->first();
    
    $basePrice = BasePrice::whereIn('MaterialId', $material_id)
        ->where(function($q) {
            $q->where('IsDeleted', 0)->orWhere('IsDeleted', null);
        })
        ->where('Status', 3)
        ->orderBy('EffectiveDate', 'asc')
        ->get();
    // $rmc_array = [];
    $dateUsdMap = [];


    $materials_all = [];
    foreach($material_id as $mat)
    {
        $object = new stdClass;
        $object->usd = 0;
        $materials_all[$mat] = $object;
    }
    foreach ($basePrice as $record) {
        $henry = new stdClass;
        $date = substr($record["EffectiveDate"], 0, 10); // Extract the date part (YYYY-MM-DD)
        if (!isset($dateUsdMap[$date])) {
            $dateUsdMap[$date] = [];
        }
        $percent =  ($product_material_composition->where('MaterialId',$record["MaterialId"])->first())->Percentage;
        $henry->MaterialId = $record["MaterialId"];
        $henry->percent = $percent/100;
        $henry->price = $record["Price"];
        $henry->usd = $record["Price"]*$henry->percent;
        
        $dateUsdMap[$date][] = $henry;  // Add usd value to the date
    }

    return array('materials' => $materials_all, 'result' => $dateUsdMap);
}

function checkIfItsUserId($secondarySale)
{
    $secondaryTrim = strtolower(trim($secondarySale));

    $currentUser = User::whereRaw('LOWER(TRIM(user_id)) = ?', [$secondaryTrim] )
                           ->first();
    
    if ($currentUser != null) {
        return "true"; 
    }

    return false;
}

function checkIfInGroup($primary_sales, $auth_user)
{
    // $user = User::where('id', $primary_sales)->orWhere('user_id', $primary_sales)->first();

    $user = User::whereRaw('LOWER(TRIM(user_id)) = ?', [ $primary_sales ])
                           ->orWhereRaw('LOWER(TRIM(id)) = ?', [ $primary_sales ])
                           ->first();
    
    $group_sales_list = GroupSales::where('user_id', $user->id)->pluck('members')->toArray();
    
    return collect($group_sales_list)->contains($auth_user);
}

function usdToRMC($cost,$effecttiveDate,$currency)
{
    $currencyExchangeRates = CurrencyExchange::where('EffectiveDate','<=',$effecttiveDate)
        ->where('ToCurrencyId', $currency)
        ->orderBy('EffectiveDate', 'desc')
        ->first();

    if ($currencyExchangeRates != null){

        $eur = $currencyExchangeRates->ExchangeRate * $cost;

        return $eur;
    }
}

function speHistoryLogs($action, $spe)
{
    $transaction_logs = new TransactionLogs;
    $transaction_logs->Type = 40;
    $transaction_logs->TransactionId = $spe;
    $transaction_logs->ActionDate = now();
    $transaction_logs->UserId = auth()->user()->id;

    if ($action == 'create')
    {
        $transaction_logs->Details = "Create new supplier product evaluation entry.";
    }
    if ($action == 'update')
    {
        $transaction_logs->Details = "Update supplier product evaluation entry.";
    }
    if ($action == 'accepted')
    {
        $transaction_logs->Details = "Accepted supplier product evaluation entry.";
    }
    if ($action == 'rejected')
    {
        $transaction_logs->Details = "Rejected supplier product evaluation entry.";
    }
    if ($action == 'approved')
    {
        $transaction_logs->Details = "Approved supplier product evaluation entry.";
    }
    if ($action == 'reconfirmatory')
    {
        $transaction_logs->Details = "Reconfirmatory supplier product evaluation entry.";
    }
    if ($action == 'received')
    {
        $transaction_logs->Details = "Received supplier product evaluation entry.";
    }
    if ($action == 'add_personnel')
    {
        $transaction_logs->Details = "Add new supplier product evaluation personnel entry.";
    }
    if ($action == 'update_personnel')
    {
        $transaction_logs->Details = "Update assigned customer requirement personnel entry.";
    }
    if ($action == 'start')
    {
        $transaction_logs->Details = "Start supplier product evaluation transaction.";
    }
    if ($action == 'disposition')
    {
        $transaction_logs->Details = "Update disposition supplier product evaluation entry.";
    }
    if ($action == 'complete')
    {
        $transaction_logs->Details = "Complete supplier product evaluation transaction.";
    }
    if ($action == 'submit')
    {
        $transaction_logs->Details = "Submit supplier product evaluation transaction.";
    }
    if ($action == 'sales_accepted')
    {
        $transaction_logs->Details = "Accept customer requirement transaction.";
    }
    if ($action == 'closed')
    {
        $transaction_logs->Details = "Closed supplier product evaluation entry.";
    }
    if ($action == 'returned')
    {
        $transaction_logs->Details = "Return supplier product evaluation entry.";
    }

    $transaction_logs->save();
}

function sseHistoryLogs($action, $spe)
{
    $transaction_logs = new TransactionLogs;
    $transaction_logs->Type = 60;
    $transaction_logs->TransactionId = $spe;
    $transaction_logs->ActionDate = now();
    $transaction_logs->UserId = auth()->user()->id;

    if ($action == 'create')
    {
        $transaction_logs->Details = "Create new shipment sample evaluation entry.";
    }
    if ($action == 'update')
    {
        $transaction_logs->Details = "Update shipment sample evaluation entry.";
    }
    if ($action == 'approved')
    {
        $transaction_logs->Details = "Approved shipment sample evaluation entry.";
    }
    if ($action == 'received')
    {
        $transaction_logs->Details = "Received shipment sample evaluation entry.";
    }
    if ($action == 'start')
    {
        $transaction_logs->Details = "Start shipment sample evaluation transaction.";
    }
    if ($action == 'sample')
    {
        $transaction_logs->Details = "Sample shipment sample evaluation transaction.";
    }
    if ($action == 'add_personnel')
    {
        $transaction_logs->Details = "Add new shipment sample evaluation personnel entry.";
    }
    if ($action == 'update_personnel')
    {
        $transaction_logs->Details = "Update assigned shipment sample evaluation personnel entry.";
    }
    if ($action == 'disposition')
    {
        $transaction_logs->Details = "Disposition shipment sample evaluation entry.";
    }
    if ($action == 'complete')
    {
        $transaction_logs->Details = "Completed shipment sample evaluation transaction.";
    }
    if ($action == 'submit')
    {
        $transaction_logs->Details = "Submit shipment sample evaluation transaction.";
    }
    if ($action == 'accepted')
    {
        $transaction_logs->Details = "Accepted shipment sample evaluation entry.";
    }
    if ($action == 'closed')
    {
        $transaction_logs->Details = "Closed shipment sample evaluation entry.";
    }

    $transaction_logs->save();
}

function checkIfInGroupV2($primary, $secondary, $auth_user)
{
    $primary_id = User::where('user_id', $primary)->first();
    $secondary_id = User::where('user_id', $secondary)->first();

    $is_manager = User::with('role')->whereHas('role', function($q) {
            $q->where('type', 'IS')->where('description','Department Admin');
        })
        ->pluck('id')
        ->toArray();
    
    if (!empty($primary_id) && !empty($secondary_id))
    {
        $group_sales_list = GroupSales::where('user_id', $auth_user)->whereNotIn('members',[$is_manager])->pluck('members')->toArray();
        
        if (in_array($primary_id->id, $group_sales_list) || in_array($secondary_id->id, $group_sales_list))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    else 
    {
        $primary_id = User::where('id', $primary)->first();
        $secondary_id = User::where('id', $secondary)->first();

        $group_sales_list = GroupSales::where('user_id', $auth_user)->whereNotIn('members',[$is_manager])->pluck('members')->toArray();
        if (in_array($primary_id->id, $group_sales_list) || in_array($secondary_id->id, $group_sales_list))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
}

function transactionProgressName($progress)
{
    $progress = SrfProgress::where('id', $progress)->first();

    if ($progress != null)
    {
        return $progress->name;
    }
}

function latestConversion($cost,$currency)
{
    $currencyExchangeRates = CurrencyExchange::where('ToCurrencyId', $currency)
        ->orderBy('EffectiveDate', 'desc')
        ->first();

    return $cost * $currencyExchangeRates->ExchangeRate;
}

function customRound($value)
 {
    $value = floor($value * 1000) / 1000; 
    return round($value, 2);
}
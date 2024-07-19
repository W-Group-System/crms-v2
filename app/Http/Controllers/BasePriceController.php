<?php

namespace App\Http\Controllers;
use App\BasePrice;
use App\PriceCurrency;
use App\RawMaterial;
use Illuminate\Http\Request;

class BasePriceController extends Controller
{
    public function index(Request $request)
    {   
        $search = $request->input('search');

        $currentBasePrice = BasePrice::with(['productMaterial', 'userApproved'])
        ->where(function ($query) use ($search) {
            $query->whereHas('productMaterial', function ($q) use ($search) {
                    $q->where('Name', 'LIKE', '%' . $search . '%');
                })
                ->orWhere('Price', 'LIKE', '%' . $search . '%')
                ->orWhereHas('userApproved', function ($q) use ($search) {
                    $q->where('full_name', 'LIKE', '%' . $search . '%');
                })
                ->orWhere('EffectiveDate', 'LIKE', '%' . $search . '%');
        })
        ->where('Status', 3)
        ->orderBy('Id', 'desc')
        ->paginate(25);
        
        return view('base_prices.index', compact('currentBasePrice', 'search')); 
    }

    public function newBasePriceIndex(Request $request)
    {   
        $search = $request->input('search');

        $newBasePrice = BasePrice::with(['productMaterial', 'userApproved'])
        ->where(function ($query) use ($search) {
            $query->whereHas('productMaterial', function ($q) use ($search) {
                    $q->where('Name', 'LIKE', '%' . $search . '%');
                })
                ->orWhere('Price', 'LIKE', '%' . $search . '%')
                ->orWhereHas('userCreated', function ($q) use ($search) {
                    $q->where('full_name', 'LIKE', '%' . $search . '%');
                })
                ->orWhere('CreatedDate', 'LIKE', '%' . $search . '%');
        })
        ->where('Status', 1)
        ->orderBy('Id', 'desc')
        ->paginate(25);
        
        $rawMaterials = RawMaterial::all();
        $productCurrency = PriceCurrency::all();

        return view('base_prices.new_basePrice_index', compact('newBasePrice', 'search', 'rawMaterials', 'productCurrency')); 
    }

    public function store(Request $request)
    {
        foreach ($request->input('Material') as $key => $materialId) {
            $existingBasePrice = BasePrice::with('productMaterial')->where('MaterialId', $materialId)->first();
            if (!$existingBasePrice) {
                BasePrice::create([
                    'MaterialId' => $materialId,
                    'Price' => $request->input('Price')[$key],
                    'Status' => 1,
                    'CreatedBy' => auth()->user()->user_id,
                    'CurrencyId' => $request->input('Currency')[$key],
                    'CreatedDate' =>date('Y-m-d, h:i:s')
                ]);
            }else {
                return back()->with('error',  $existingBasePrice->productMaterial->Name . ' already exists.');
            }
           
        }
            return back();
    }

    public function updateBasePrices(Request $request)
    {
            foreach ($request['Material'] as $index => $materialId) {
                $basePriceId = $request['BasePriceId'][$index];
                $currencyId = $request['Currency'][$index];
            $price = $request['Price'][$index];

            $basePrice = BasePrice::find($basePriceId);
            if ($basePrice) {
                $hasChanged = ($basePrice->MaterialId != $materialId) || 
                            ($basePrice->CurrencyId != $currencyId) || 
                            ($basePrice->Price != $price);

                if (!$hasChanged) {
                    continue; 
                }
                $existingBasePrice = BasePrice::with('productMaterial')
                    ->where('MaterialId', $materialId)
                    ->where('id', '!=', $basePriceId)
                    ->first();

                if ($existingBasePrice) {
                    return redirect()->back()->with('error', $existingBasePrice->productMaterial->Name . ' already exists for another BasePrice.');
                }
                $basePrice->MaterialId = $materialId;
                $basePrice->CurrencyId = $currencyId;
                $basePrice->Price = $price;
                $basePrice->ModifiedDate = now();
                $basePrice->save();
            }
        }

        return redirect()->back()->with('success', 'Base prices updated successfully.');
    }


    public function updateBasePrice(Request $request, $id)
    {
        $materialId = $request->input('Material');

        $exists = BasePrice::with('productMaterial')->where('MaterialId', $materialId)
        ->where('id', '!=', $id)
        ->first();
        if ($exists) {
        return redirect()->back()->with('error', $exists->productMaterial->Name . ' already exists for another BasePrice.');
        }
        $newbasePrice = BasePrice::findOrFail($id);
    
        $newbasePrice->MaterialId = $request->input('Material');
        $newbasePrice->Price = $request->input('Price');
        $newbasePrice->CurrencyId = $request->input('Currency');
    
        $newbasePrice->save();
        return redirect()->back()->with('success', 'New Base Price updated successfully');
    }

    public function editApproved($id)
    {
        $approveNewBasePrice = BasePrice::find($id);
        
        if ($approveNewBasePrice) {
            if (request()->status === 'approved') {
                $approveNewBasePrice->Status = 3;
                $approveNewBasePrice->ApprovedBy = auth()->user()->user_id;
                $approveNewBasePrice->EffectiveDate = now();
                $approveNewBasePrice->updated_at = now();
            } elseif (request()->status === 'disapproved') {
                $approveNewBasePrice->Status = 2;
                $approveNewBasePrice->deleted_at = now();
            }

            $approveNewBasePrice->save();
            return response()->json(['message' => 'Base price approved successfully'], 200);
        } else {
            return response()->json(['message' => 'Base price not found'], 404);
        }
    }
}

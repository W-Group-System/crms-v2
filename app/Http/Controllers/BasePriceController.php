<?php

namespace App\Http\Controllers;
use App\BasePrice;
use App\PriceCurrency;
use App\RawMaterial;
use Illuminate\Http\Request;

class BasePriceController extends Controller
{
    // public function index(Request $request)
    // {   
    //     $search = $request->input('search');

    //     $currentBasePrice = BasePrice::with(['productMaterial', 'userApproved'])
    //     ->where(function ($query) use ($search) {
    //         $query->whereHas('productMaterial', function ($q) use ($search) {
    //                 $q->where('Name', 'LIKE', '%' . $search . '%');
    //             })
    //             ->orWhere('Price', 'LIKE', '%' . $search . '%')
    //             ->orWhereHas('userApproved', function ($q) use ($search) {
    //                 $q->where('full_name', 'LIKE', '%' . $search . '%');
    //             })
    //             ->orWhere('EffectiveDate', 'LIKE', '%' . $search . '%');
    //     })
    //     ->where('Status', 3)
    //     ->orderBy('Id', 'desc')
    //     ->paginate(25);
        
    //     return view('base_prices.index', compact('currentBasePrice', 'search')); 
    // }
    public function index(Request $request)
    {
        $search = $request->input('search');

        $latestEffectiveDateSubquery = BasePrice::select('MaterialId', \DB::raw('MAX(EffectiveDate) as LatestEffectiveDate'))
            ->whereNull('deleted_at')  
            ->groupBy('MaterialId');

        $currentBasePrice = BasePrice::with(['productMaterial', 'userApproved'])
            ->joinSub($latestEffectiveDateSubquery, 'latest_dates', function ($join) {
                $join->on('productmaterialbaseprices.MaterialId', '=', 'latest_dates.MaterialId')
                    ->on('productmaterialbaseprices.EffectiveDate', '=', 'latest_dates.LatestEffectiveDate');
            })
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
            ->whereNull('deleted_at') 
            ->orderBy('productmaterialbaseprices.Id', 'desc')
            ->paginate(25);
            
        return view('base_prices.index', compact('currentBasePrice', 'search')); 
    }


    public function newBasePriceIndex(Request $request)
    {   
        $search = $request->input('search');

        $latestDatesSubquery = BasePrice::select('MaterialId', \DB::raw('MAX(CreatedDate) as latest_created_date'))
            ->where('Status', 1)
            ->groupBy('MaterialId');

        $newBasePrice = BasePrice::with(['productMaterial', 'userApproved'])
            ->joinSub($latestDatesSubquery, 'latest_dates', function ($join) {
                $join->on('productmaterialbaseprices.MaterialId', '=', 'latest_dates.MaterialId')
                    ->on('productmaterialbaseprices.CreatedDate', '=', 'latest_dates.latest_created_date');
            })
            ->where(function ($query) use ($search) {
                $query->whereHas('productMaterial', function ($q) use ($search) {
                        $q->where('Name', 'LIKE', '%' . $search . '%');
                    })
                    ->orWhere('Price', 'LIKE', '%' . $search . '%')
                    ->orWhereHas('userApproved', function ($q) use ($search) {
                        $q->where('full_name', 'LIKE', '%' . $search . '%');
                    })
                    ->orWhere('CreatedDate', 'LIKE', '%' . $search . '%');
            })
            ->where('Status', 1)
            ->orderBy('CreatedDate', 'desc') 
            ->paginate(25);

        $rawMaterials = RawMaterial::all();
        $productCurrency = PriceCurrency::all();

        return view('base_prices.new_basePrice_index', compact('newBasePrice', 'search', 'rawMaterials', 'productCurrency')); 
    }


    public function store(Request $request)
    {
        foreach ($request->input('Material') as $key => $materialId) {
            $existingBasePrice = BasePrice::with('productMaterial')
            ->where('MaterialId', $materialId)
            ->where('status', 1)
            ->first();
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
        foreach ($request['BasePriceId'] as $index => $basePriceId) {
            $materialId = $request['Material'][$index];
            $price = $request['Price'][$index];
            $basePrice = BasePrice::find($basePriceId);
            if ($basePrice) {
                if ($basePrice->MaterialId != $materialId) {
                    $existingBasePrice = BasePrice::with('productMaterial')
                        ->where('MaterialId', $materialId)
                        ->where('id', '!=', $basePriceId)
                        ->first();
    
                    if ($existingBasePrice) {
                        return redirect()->back()->with('error', $existingBasePrice->productMaterial->Name . ' already exists for another BasePrice.');
                    }
                    $basePrice->MaterialId = $materialId;
                }
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
        ->where('Status', "1")
        ->where('id', '!=', $id)
        ->first();
        if ($exists) {
        return redirect()->back()->with('error', $exists->productMaterial->Name . ' already exists for another BasePrice.');
        }
        $newbasePrice = BasePrice::findOrFail($id);
    
        $newbasePrice->MaterialId = $request->input('Material');
        $newbasePrice->Price = $request->input('Price');
        // $newbasePrice->CurrencyId = $request->input('Currency');
    
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

    public function destroy($id)
    {
        try {
            $basePrice = BasePrice::findOrFail($id); 
            $basePrice->delete();  
            return response()->json(['success' => true, 'message' => 'Base price deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete base price.'], 500);
        }
    }
}

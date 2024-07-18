<?php

namespace App\Http\Controllers;
use App\RawMaterial;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class RawMaterialController extends Controller
{
    // List
    public function index(Request $request)
    {   
        $rawMaterials = RawMaterial::with(['productMaterialCompositions.products'])
            ->when($request->search, function($q)use($request) {
                $q->where('Name', 'LIKE', '%'.$request->search.'%')->orWhere('Description', "LIKE", "%".$request->search."%");
            })
            ->orderBy('id', 'desc')
            ->paginate(10);
        
        return view('raw_materials.index',
            array(
                'search' => $request->search,
                'rawMaterials' => $rawMaterials
            )
        ); 
    }

    public function add(Request $request)
    {
        $rawMaterial = new RawMaterial;
        $rawMaterial->Name = $request->Name;
        $rawMaterial->Description = $request->Description;
        $rawMaterial->status = "Active";
        $rawMaterial->save();

        Alert::success('Sucessfully Saved')->persistent('Dismiss');
        return back();
    }

    public function deactivate(Request $request)
    {
        $rawMaterial = RawMaterial::findOrFail($request->id);
        $rawMaterial->status = "Inactive";
        $rawMaterial->save();
        
        Alert::success('Sucessfully Deactivate')->persistent('Dismiss');
        return back();
    }

    public function activate(Request $request)
    {
        $rawMaterial = RawMaterial::findOrFail($request->id);
        $rawMaterial->status = "Active";
        $rawMaterial->save();
        
        Alert::success('Sucessfully Activate')->persistent('Dismiss');
        return back();
    }

    // public function getRawMaterialsProducts(Request $request)
    // {
    //     $rawMaterial = RawMaterial::with('product_raw_materials.products')->findOrFail($request->id);
        
    //     if ($rawMaterial->product_raw_materials != null)
    //     {
    //         return response()->json([
    //             'status' => 1,
    //             'products' => $rawMaterial->product_raw_materials->products->product_origin,
    //             'percentage' => $rawMaterial->product_raw_materials->percent
    //         ]);
    //     }
    // }
}

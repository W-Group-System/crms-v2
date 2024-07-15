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
        if(request()->ajax())
        {
            $search = $request->input('search.value');

            if ($search != null)
            {
                RawMaterial::where('Name', 'LIKE', '%'.$search.'%')->orWhere('Description', 'LIKE', '%'.$search.'%');
            }
            
            return datatables()->of(RawMaterial::orderBy('id', 'desc')->get())
                    ->addColumn('action', function($data){
                        $buttons = '
                            <button type="button" name="view" class="view btn-table btn btn-success viewModal" title="View" data-id="'.$data->id.'">
                                <i class="ti-eye"></i>
                            </button>
                        ';

                        if ($data->status == "Active")
                        {
                            $buttons.= '
                                <button type="button" class="delete btn-table btn btn-danger deactivate" title="Deactivate" data-id="'.$data->id.'">
                                    <i class="ti-trash"></i>
                                </button>
                            ';
                        }
                        else
                        {
                            $buttons.= '
                                <button type="button" class="btn btn-sm btn-info activate" title="Activate" data-id="'.$data->id.'">
                                    <i class="ti-check"></i>
                                </button>
                            ';
                        }

                        return $buttons;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        
        return view('raw_materials.index'); 
    }

    public function add(Request $request)
    {
        $rawMaterial = new RawMaterial;
        $rawMaterial->Name = $request->Name;
        $rawMaterial->Description = $request->Description;
        $rawMaterial->status = "Active";
        $rawMaterial->save();

        return response()->json([
            'message' => 'Successfully Saved.',
            'status' => 1
        ]);
    }

    public function deactivate(Request $request)
    {
        $rawMaterial = RawMaterial::findOrFail($request->id);
        $rawMaterial->status = "Inactive";
        $rawMaterial->save();
        
        // Alert::success('Sucessfully Deactivate')->persistent('Dismiss');
        // return back();
    }

    public function activate(Request $request)
    {
        $rawMaterial = RawMaterial::findOrFail($request->id);
        $rawMaterial->status = "Active";
        $rawMaterial->save();
        
        // Alert::success('Sucessfully Activate')->persistent('Dismiss');
        // return back();
    }

    public function getRawMaterialsProducts(Request $request)
    {
        $rawMaterial = RawMaterial::with('product_raw_materials.products')->findOrFail($request->id);
        
        if ($rawMaterial->product_raw_materials != null)
        {
            return response()->json([
                'status' => 1,
                'products' => $rawMaterial->product_raw_materials->products->product_origin,
                'percentage' => $rawMaterial->product_raw_materials->percent
            ]);
        }
    }
}

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
        // dd($request->all());
        // if(request()->ajax())
        // {
        //     return datatables()->of(RawMaterial::orderBy('id', 'desc')->get())
        //             ->addColumn('action', function($data){
        //                 $buttons = '<button type="button" name="view" id="'.$data->id.'" class="view btn-table btn btn-success"><i class="ti-eye"></i></button>';
        //                 $buttons .= '&nbsp;&nbsp;';
        //                 $buttons .= '<button type="button" name="edit" id="'.$data->id.'" class="edit btn-table btn btn-primary"><i class="ti-pencil"></i></button>';
        //                 $buttons .= '&nbsp;&nbsp;';
        //                 $buttons .= '<button type="button" name="delete" id="'.$data->id.'" class="delete btn-table btn btn-danger"><i class="ti-trash"></i></button>';
        //                 return $buttons;
        //             })
        //             ->rawColumns(['action'])
        //             ->make(true);
        // }
        $rawMaterial = RawMaterial::with(['product_raw_materials'])
            ->when(!empty($request->search), function($q)use($request){
                $q->where('name', 'like', "%".$request->search."%");
            })
            ->orderBy('id', 'DESC')
            ->paginate(10);

        return view('raw_materials.index', 
            array(
                'rawMaterials' => $rawMaterial,
                'search' => $request->search
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
}

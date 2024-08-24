<?php

namespace App\Http\Controllers;

use App\Exports\RawMaterialsExport;
use App\RawMaterial;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;
use Symfony\Component\Mime\RawMessage;

class RawMaterialController extends Controller
{
    // List
    public function index(Request $request)
    {   
        $fetchAll = $request->input('fetch_all', false);

        $rawMaterials = RawMaterial::with(['productMaterialCompositions.products'])
            ->when($request->search, function($q)use($request) {
                $q->where('Name', 'LIKE', '%'.$request->search.'%')->orWhere('Description', "LIKE", "%".$request->search."%");
            })
            ->orderBy('id', 'desc');

        if($fetchAll)
        {
            $rawMaterials = $rawMaterials->get();

            return response()->json($rawMaterials);
        }
        else
        {
            $rawMaterials = $rawMaterials->paginate($request->entries ?? 10);
            
            return view('raw_materials.index',
                array(
                    'search' => $request->search,
                    'rawMaterials' => $rawMaterials,
                    'entries' => $request->entries
                )
            ); 

        }
    }

    public function add(Request $request)
    {
        $request->validate([
            'Name' => 'unique:productmaterials,Name'
        ]);

        $rawMaterial = new RawMaterial;
        $rawMaterial->Name = $request->Name;
        $rawMaterial->Description = $request->Description;
        // $rawMaterial->status = "Active";
        $rawMaterial->save();

        Alert::success('Sucessfully Saved')->persistent('Dismiss');
        return back();
    }

    // public function deactivate(Request $request)
    // {
    //     $rawMaterial = RawMaterial::findOrFail($request->id);
    //     $rawMaterial->status = "Inactive";
    //     $rawMaterial->save();
        
    //     Alert::success('Sucessfully Deactivate')->persistent('Dismiss');
    //     return back();
    // }

    // public function activate(Request $request)
    // {
    //     $rawMaterial = RawMaterial::findOrFail($request->id);
    //     $rawMaterial->status = "Active";
    //     $rawMaterial->save();
        
    //     Alert::success('Sucessfully Activate')->persistent('Dismiss');
    //     return back();
    // }

    public function edit($id)
    {
        $rawMaterial = RawMaterial::findOrFail($id);
        
        return response()->json($rawMaterial);
    }

    public function update(Request $request, $id)
    {
        $rawMaterial = RawMaterial::findOrFail($id);
        $rawMaterial->Name = $request->Name;
        $rawMaterial->Description = $request->Description;
        $rawMaterial->save();
        
        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }

    public function viewRawMaterials($id)
    {
        $rawMaterial = RawMaterial::findOrFail($id);

        return view('raw_materials.view_raw_materials', array('raw_materials' => $rawMaterial));
    }

    public function delete($id)
    {
        $rawMaterial = RawMaterial::findOrFail($id);
        $rawMaterial->delete();

        Alert::success('Successfully Deleted')->persistent('Dismiss');
        return back();
    }

    public function export()
    {
        return Excel::download(new RawMaterialsExport, 'Raw Materials.xlsx');
    }
}

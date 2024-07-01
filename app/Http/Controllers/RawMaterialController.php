<?php

namespace App\Http\Controllers;
use App\RawMaterial;
use Illuminate\Http\Request;

class RawMaterialController extends Controller
{
    // List
    public function index()
    {   
        if(request()->ajax())
        {
            return datatables()->of(RawMaterial::orderBy('id', 'desc')->get())
                    ->addColumn('action', function($data){
                        $buttons = '<button type="button" name="view" id="'.$data->id.'" class="view btn-table btn btn-success"><i class="ti-eye"></i></button>';
                        $buttons .= '&nbsp;&nbsp;';
                        $buttons .= '<button type="button" name="edit" id="'.$data->id.'" class="edit btn-table btn btn-primary"><i class="ti-pencil"></i></button>';
                        $buttons .= '&nbsp;&nbsp;';
                        $buttons .= '<button type="button" name="delete" id="'.$data->id.'" class="delete btn-table btn btn-danger"><i class="ti-trash"></i></button>';
                        return $buttons;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('raw_materials.index'); 
    }
}

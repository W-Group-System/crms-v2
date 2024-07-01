<?php

namespace App\Http\Controllers;
use App\BasePrice;
use Illuminate\Http\Request;

class BasePriceController extends Controller
{
    public function index()
    {   
        if(request()->ajax())
        // dd(request());
        {
            return datatables()->of(BasePrice::orderBy('Id', 'desc')->get())
                    ->addColumn('action', function($data){
                        $buttons = '<button type="button" name="edit" id="'.$data->Id.'" class="edit btn btn-primary">Edit</button>';
                        $buttons .= '&nbsp;&nbsp;';
                        $buttons .= '<button type="button" name="delete" id="'.$data->Id.'" class="delete btn btn-danger">Delete</button>';
                        return $buttons;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('base_prices.index'); 
    }
}

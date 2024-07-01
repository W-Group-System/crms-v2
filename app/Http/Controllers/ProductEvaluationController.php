<?php

namespace App\Http\Controllers;
use App\ProductEvaluation;
use App\Client;
use App\ProductApplication;
use Illuminate\Http\Request;

class ProductEvaluationController extends Controller
{
    // List
    public function index()
    {   
        $product_evaluations = ProductEvaluation::with(['client', 'product_application'])->orderBy('id', 'desc')->get();
        // dd($product_evaluations);
        $clients = Client::all();
        $product_applications = ProductApplication::all();
        if(request()->ajax())
        {
            return datatables()->of($product_evaluations)
                    ->addColumn('action', function($data){
                        $buttons = '<button type="button" name="edit" id="'.$data->id.'" class="edit btn btn-primary">Edit</button>';
                        $buttons .= '&nbsp;&nbsp;';
                        $buttons .= '<button type="button" name="delete" id="'.$data->id.'" class="delete btn btn-danger">Delete</button>';
                        return $buttons;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('product_evaluations.index', compact('product_evaluations', 'clients', 'product_applications')); 
    }
}

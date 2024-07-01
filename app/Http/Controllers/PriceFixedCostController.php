<?php

namespace App\Http\Controllers;
use App\PriceFixedCost;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PriceFixedCostController extends Controller
{
    // List
    public function index()
    {
        $price_fixed_cost = PriceFixedCost::with('user')->orderBy('id', 'desc')->get()->take(10);
        $users = User::get();
        if (request()->ajax())
        // dd(request()); 
        {
            return datatables()->of($price_fixed_cost)
                ->addColumn('action', function ($data) {
                    $buttons = '<button type="button" name="edit" id="' . $data->id . '" class="edit btn btn-primary">Edit</button>';
                    $buttons .= '&nbsp;&nbsp;';
                    $buttons .= '<button type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger">Delete</button>';
                    return $buttons;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('price_request_fixed.index', compact('price_fixed_cost', 'users'));
    }

    // Store
    public function store(Request $request) 
    {
        $rules = array(
            'EffectiveDate'       =>  'required',
            'DirectLabor'         =>  'required',
            'FactoryOverhead'     =>  'required',
            'DeliveryCost'        =>  'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }
        
        $form_data = array(
            'EffectiveDate'        =>  $request->EffectiveDate,
            'CreatedByUserId'      =>  Auth::user()->username,
            'DirectLabor'          =>  $request->DirectLabor,
            'FactoryOverhead'      =>  $request->FactoryOverhead,
            'DeliveryCost'         =>  $request->DeliveryCost
        );

        PriceFixedCost::create($form_data);

        return response()->json(['success' => 'Data Added Successfully.']);
    }

    // Edit
    public function edit($id)
    {
        if(request()->ajax())
        {
            $data = PriceFixedCost::findOrFail($id);
            return response()->json(['data' => $data]);
        }
    }

    // Update
    public function update(Request $request, $id)
    {
        $rules = array(
            'EffectiveDate'       =>  'required',
            'DirectLabor'         =>  'required',
            'FactoryOverhead'     =>  'required',
            'DeliveryCost'        =>  'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'EffectiveDate'        =>  $request->EffectiveDate,
            'CreatedByUserId'      =>  auth()->user()->username,
            'DirectLabor'          =>  $request->DirectLabor,
            'FactoryOverhead'      =>  $request->FactoryOverhead,
            'DeliveryCost'         =>  $request->DeliveryCost
        );

        PriceFixedCost::whereId($id)->update($form_data);

        return response()->json(['success' => 'Data is Successfully Updated.']);
    }

    // Delete
    public function delete($id)
    {
        $data = PriceFixedCost::findOrFail($id);
        $data->delete();
    }
}

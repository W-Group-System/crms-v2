<?php

namespace App\Http\Controllers;
use App\PriceFixedCost;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class PriceFixedCostController extends Controller
{
    // List
    public function index(Request $request)
    {
        $price_fixed_cost = PriceFixedCost::with('user')
            ->when($request->search, function($query)use($request){
                $query->where('DirectLabor', 'LIKE', '%'.$request->search.'%')
                    ->orWhere('FactoryOverHead', 'LIKE', '%'.$request->search.'%')
                    ->orWhere('DeliveryCost', "LIKE", '%'.$request->search.'%')
                    ->orWhereHas('user', function($query)use($request) {
                        $query->where('full_name', 'LIKE', '%'.$request->search.'%');
                    })
                    ->orWhereHas('userById', function($query)use($request) {
                        $query->where('full_name', 'LIKE', '%'.$request->search.'%');
                    });
            })
            ->latest()
            ->paginate(10);

        $users = User::get();
        $search = $request->search;
        
        return view('price_request_fixed.index', compact('price_fixed_cost', 'users', 'search'));
    }

    // Store
    public function store(Request $request) 
    {
        $pfc = new PriceFixedCost;
        $pfc->EffectiveDate = $request->EffectiveDate;
        $pfc->DirectLabor = $request->DirectLabor;
        $pfc->FactoryOverhead = $request->FactoryOverhead;
        $pfc->DeliveryCost = $request->DeliveryCost;
        $pfc->CreatedByUserId = auth()->user()->id;
        $pfc->save();

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back();
    }

    // Update
    public function update(Request $request, $id)
    {
        $pfc = PriceFixedCost::findOrFail($id);
        $pfc->EffectiveDate = $request->EffectiveDate;
        $pfc->DirectLabor = $request->DirectLabor;
        $pfc->FactoryOverhead = $request->FactoryOverhead;
        $pfc->DeliveryCost = $request->DeliveryCost;
        $pfc->CreatedByUserId = auth()->user()->id;
        $pfc->save();

        Alert::success('Successfully Update')->persistent('Dismiss');
        return back();
    }

    // Delete
    public function delete($id)
    {
        $data = PriceFixedCost::findOrFail($id);
        $data->delete();

        Alert::success('Successfully Delete')->persistent('Dismiss');
        return back();
    }
}

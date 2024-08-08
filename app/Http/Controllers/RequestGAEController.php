<?php

namespace App\Http\Controllers;
use App\RequestGAE;
use Validator;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class RequestGAEController extends Controller
{
    // List
    public function index(Request $request)
    {   
        $paymentTerms = RequestGAE::orderBy('id', 'desc')
            ->when($request->search, function($query)use($request) {
                $query->where('ExpenseName', 'LIKE', '%'.$request->search.'%')->orWhere('Cost', 'LIKE', '%'.$request->search.'%');
            })
            ->paginate(10);

        return view('request_gaes.index',
            array(
                'paymentTerms' => $paymentTerms,
                'search' => $request->search
            )
        ); 
    }

    // Store
    public function store(Request $request) 
    {
        $priceRequestGae = new RequestGAE;
        $priceRequestGae->ExpenseName = $request->ExpenseName;
        $priceRequestGae->Cost = $request->Cost;
        $priceRequestGae->save();

        Alert::success('Successfully Save')->persistent('Dismiss');
        return back();
    }  

    // Update
    public function update(Request $request, $id)
    {
        $priceRequestGae = RequestGAE::findOrFail($id);
        $priceRequestGae->ExpenseName = $request->ExpenseName;
        $priceRequestGae->Cost = $request->Cost;
        $priceRequestGae->save();

        Alert::success('Successfully Update')->persistent('Dismiss');
        return back();
    }

    // Delete
    public function delete($id)
    {
        $paymentTerms = RequestGAE::findOrFail($id);
        $paymentTerms->delete();

        Alert::success('Successfully Delete')->persistent('Dismiss');
        return back();
    }
}

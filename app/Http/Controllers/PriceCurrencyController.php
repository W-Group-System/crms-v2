<?php

namespace App\Http\Controllers;
use App\Exports\PriceCurrencyExport;
use App\PriceCurrency;
use Validator;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PriceCurrencyController extends Controller
{
    // List
    public function index(Request $request)
    {   
       
        $search = $request->input('search');
        $price_currencies = PriceCurrency::where(function ($query) use ($search) {
            $query->where('Name', 'LIKE', '%' . $search . '%')
                  ->orWhere('Description', 'LIKE', '%' . $search . '%');
        })->paginate(10);
        return view('price_currencies.index', compact('search', 'price_currencies')); 
    }

    // Store
    public function store(Request $request) 
    {
        $existing = PriceCurrency::where('Name', $request->Name)->exists();
        if (!$existing) {
            $form_data = array(
                'Name'          =>  $request->Name,
                'Description'   =>  $request->Description
            );
    
            PriceCurrency::create($form_data);
    
            return redirect()->back()->with('success', 'Data Added Successfully.');
        } else {
            return back()->with('error', $request->Name . ' already exists.');
        }
    }

    // Edit
    public function edit($id)
    {
        if(request()->ajax())
        {
            $data = PriceCurrency::findOrFail($id);
            return response()->json(['data' => $data]);
        }
    }

    // Update
    public function update(Request $request, $id)
    {
        $priceCurrency = $request->Name;
        $exists = PriceCurrency::where('Name', $priceCurrency)
        ->where('id', '!=', $id)->first();
        if ($exists){
            return redirect()->back()->with('error', $request->Name . ' already exists.');
        }
        
        $form_data = array(
            'Name'          =>  $request->Name,
            'Description'   =>  $request->Description
        );

        PriceCurrency::whereId($id)->update($form_data);

        return redirect()->back()->with('success', 'Price Currency updated successfully.');
    }

    // Delete
    public function delete($id)
    {
        $data = PriceCurrency::findOrFail($id);
        $data->delete();
    }

    public function exportPriceCurrency()
    {
        return Excel::download(new PriceCurrencyExport, 'Price Currency.xlsx');
    }
}

<?php

namespace App\Http\Controllers;

use App\ShipmentSample;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ShipmentSampleController extends Controller
{
    // List 
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->get('sort', 'Name'); // Default to 'Name' if no sort is specified
        $direction = $request->get('direction', 'asc'); // Default to ascending order
        $fetchAll = $request->input('fetch_all', false); // Get the fetch_all parameter
        $entries = $request->input('number_of_entries', 10); // Default to 10 entries per page
        $refCode = $this->refCode();

        return view('sse.index', compact('search', 'fetchAll', 'entries', 'refCode'));
    }


    // Store 
    public function store(Request $request) 
    {
        $rules = [
            'RmType' => 'required|string|max:255',
            'Supplier' => 'required|string|max:255',
            'SseResult' => 'required|string|max:255',
            'AttentionTo' => 'nullable|string|max:255',
            'ProductCode' => 'nullable|string|max:255',
            'Grade' => 'nullable|string|max:255',
            'Origin' => 'nullable|string|max:255',  
        ];

        $customMessages = [
            'RmType.required' => 'The raw material type is required.',
            'SseResult.required' => 'The result is required.',
            'Supplier.required' => 'The supplier is required.',           
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $supplier_product = new ShipmentSample();
        $supplier_product->SseNumber = $request->SseNumber;
        $supplier_product->DateSubmitted = $request->DateSubmitted;
        $supplier_product->AttentionTo = $request->AttentionTo;
        $supplier_product->RmType = $request->RmType;
        $supplier_product->Grade = $request->Grade;
        $supplier_product->Origin = $request->Origin;
        $supplier_product->Supplier = $request->Supplier;
        $supplier_product->SseResult = $request->SseResult;
        $supplier_product->Origin = $request->Origin;
        $supplier_product->ResultSpeNo = $request->ResultSpeNo;
        $supplier_product->PoNumber = $request->PoNumber;
        $supplier_product->Quantity = $request->Quantity;
        $supplier_product->Status = 10;
        $supplier_product->save();

        return response()->json(['success' => 'Shipment Sample added successfully!']);
    }

    public function refCode()
    {
        return array(
            'RND' => 'RND',
            'QCD-WHI' => 'QCD-WHI',
            'QCD-PBI' => 'QCD-PBI',
            'QCD-MRDC' => 'QCD-MRDC',
            'QCD-CCC' => 'QCD-CCC'
        );
    }
}

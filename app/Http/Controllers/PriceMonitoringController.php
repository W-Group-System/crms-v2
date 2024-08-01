<?php

namespace App\Http\Controllers;

use App\Client;
use App\PaymentTerms;
use App\PriceMonitoring;
use App\Product;
use App\SalesUser;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PriceMonitoringController extends Controller
{
    // List 
    public function index(Request $request)
    {   
        $search = $request->input('search');
        $price_monitorings = PriceMonitoring::with(['client', 'product_application'])
        ->where(function ($query) use ($search){
            $query->where('PrfNumber', 'LIKE', '%' . $search . '%')
            ->orWhere('DateRequested', 'LIKE', '%' . $search . '%')
            ->orWhereHas('client', function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%');
            });
            // ->orWhereHas('product_application', function ($q) use ($search) {
            //     $q->where('name', 'LIKE', '%' . $search . '%');
            // })
            // ->orWhere('RpeResult', 'LIKE', '%' . $search . '%');
        })
        ->orderBy('id', 'desc')->paginate(25);
        $clients = Client::all();
        $users = User::all();
        $products = Product::where('status', '4')->get();
        $payment_terms = PaymentTerms::all();
        return view('price_monitoring.index', compact('price_monitorings','clients','users', 'search', 'products', 'payment_terms')); 
    }

    public function store(Request $request)
    {
        $user = Auth::user(); 
        $salesUser = SalesUser::where('SalesUserId', $user->user_id)->first();
        $type = $salesUser->Type == 2 ? 'IS' : 'LS';
        $year = Carbon::parse($request->input('DateRequested'))->format('y');
        $lastEntry = PriceMonitoring::where('PrfNumber', 'LIKE', "Prf-{$type}-%")
                    ->orderBy('id', 'desc')
                    ->first();
        $lastNumber = $lastEntry ? intval(substr($lastEntry->PrfNumber, -4)) : 0;
        $newIncrement = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        $prfNo = "PrfNumber-{$type}-{$year}-{$newIncrement}";

        $priceMonitoringData = PriceMonitoring::create([
            'PrfNumber' => $prfNo,
            'PrimarySalesPersonId' => $request->input('PrimarySalesPersonId'),
            'SecondarySalesPersonId' => $request->input('SecondarySalesPersonId'),
            'DateRequested' => $request->input('DateRequested'),
            'ClientId' => $request->input('ClientId'),
            'PriceRequestPurpose' => $request->input('PriceRequestPurpose'),
            'ShipmentTerm' => $request->input('ShipmentTerm'),
            'PaymentTermId' => $request->input('PaymentTerm'),
            'OtherCostRequirements' => $request->input('OtherCostRequirement'),
            'Commission' => $request->input('Commision'),
            // 'Country' => $request->input('Country'),
            // 'Region' => $request->input('Region'),
            // 'DueDate' => $request->input('DueDate'),
            // 'ApplicationId' => $request->input('ApplicationId'),
            // 'PotentialVolume' => $request->input('PotentialVolume'),
            // 'TargetRawPrice' => $request->input('TargetRawPrice'),
            // 'ProjectNameId' => $request->input('ProjectNameId'),
            
            // 'Priority' => $request->input('Priority'),
            // 'AttentionTo' => $request->input('AttentionTo'),
            // 'UnitOfMeasureId' => $request->input('UnitOfMeasureId'),
            // 'CurrencyId' => $request->input('CurrencyId'),
            // 'SampleName' => $request->input('SampleName'),
            // 'Supplier' => $request->input('Supplier'),
            // 'ObjectiveForRpeProject' => $request->input('ObjectiveForRpeProject'),
            // 'Status' =>'10',
            // 'Progress' => '10',
            'Type' => $request->input('Type'),
            'QuantityRequired' => $request->input('QuantityRequired'),
            'ProductId' => $request->input('Product'),
            'ProductRmc' => $request->input('Rmc'),
            'IsalesShipmentCost' => $request->input('ShipmentCost'),

        ]);
                    return redirect()->back()->with('success', 'Base prices updated successfully.');
    }
    
    public function getClientDetails($id)
    {
        $client = Client::with('clientregion')->find($id);
        return response()->json([
            'ClientRegionId' => $client->ClientRegionId,
            'RegionName' => $client->clientregion->Name,
            'ClientCountryId' => $client->ClientCountryId,
            'CountryName' => $client->clientcountry->Name
        ]);
    }
}

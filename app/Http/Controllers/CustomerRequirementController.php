<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Validator;
use App\CustomerRequirement;
use App\Client;
use App\User;
use App\PriceCurrency;
use App\NatureRequest;
use App\CrrNature;
use App\ProductApplication;
use App\SalesUser;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class CustomerRequirementController extends Controller
{
    // List
    public function index(Request $request)
    {   
        $search = $request->input('search');
        $customer_requirements = CustomerRequirement::with(['client', 'product_application'])
        ->where(function ($query) use ($search){
            $query->where('CrrNumber', 'LIKE', '%' . $search . '%')
            ->orWhere('CreatedDate', 'LIKE', '%' . $search . '%')
            ->orWhere('DueDate', 'LIKE', '%' . $search . '%')
            ->orWhereHas('client', function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%');
            })
            ->orWhereHas('product_application', function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%');
            })
            ->orWhere('Recommendation', 'LIKE', '%' . $search . '%');
        })
        ->orderBy('id', 'desc')->paginate(10);
        $product_applications = ProductApplication::all();
        $clients = Client::all();
        $users = User::all();
        $price_currencies = PriceCurrency::all();
        $nature_requests = NatureRequest::all();
        return view('customer_requirements.index', compact('customer_requirements', 'clients', 'product_applications', 'users', 'price_currencies', 'nature_requests', 'search')); 
    }

    // Store
    public function store(Request $request)
    {
        $user = Auth::user(); 
        $salesUser = SalesUser::where('SalesUserId', $user->user_id)->first();
        $type = $salesUser->Type == 2 ? 'IS' : 'LS';
        $year = Carbon::parse($request->input('CreatedDate'))->format('y');
        $lastEntry = CustomerRequirement::where('CrrNumber', 'LIKE', "CRR-{$type}-%")
                    ->orderBy('id', 'desc')
                    ->first();
        $lastNumber = $lastEntry ? intval(substr($lastEntry->CrrNumber, -4)) : 0;
        $newIncrement = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        $crrNo = "CRR-{$type}-{$year}-{$newIncrement}";

        $customerRequirementData = CustomerRequirement::create([
            'CrrNumber' => $crrNo,
            'CreatedDate' => $request->input('CreatedDate'),
            'DueDate' => $request->input('DueDate'),
            'ClientId' => $request->input('ClientId'),
            'ApplicationId' => $request->input('ApplicationId'),
            'PotentialVolume' => $request->input('PotentialVolume'),
            'TargetPrice' => $request->input('TargetPrice'),
            'Competitor' => $request->input('Competitor'),
            'PrimarySalesPersonId' => $request->input('PrimarySalesPersonId'),
            'SecondarySalesPersonId' => $request->input('SecondarySalesPersonId'),
            'Priority' => $request->input('Priority'),
            'DetailsOfRequirement' => $request->input('DetailsOfRequirement'),
            'Status' =>'10',
            'UnitOfMeasureId' => $request->input('UnitOfMeasureId'),
            'CurrencyId' => $request->input('CurrencyId'),
            'Progress' => '10',
            'CompetitorPrice' => $request->input('CompetitorPrice'),
            'RefCrrNumber' => $request->input('RefCrrNumber'),
            'RefRpeNumber' => $request->input('RefRpeNumber'),
        ]);
        foreach ($request->input('NatureOfRequestId') as $natureOfRequestId) {
                        CrrNature::create([
                            'CustomerRequirementId' => $customerRequirementData->id,
                            'NatureOfRequestId' => $natureOfRequestId
                        ]);
                    }
        
                    return redirect()->back()->with('success', 'Base prices updated successfully.');
    }

    public function update(Request $request, $id)
    {
        $customerRequirements = CustomerRequirement::findOrFail($id);
        // $customerRequirements->DateCreated = date('Y-m-d');
        $customerRequirements->ClientId = $request->ClientId;
        $customerRequirements->Priority = $request->Priority;
        $customerRequirements->ApplicationId = $request->ApplicationId;
        $customerRequirements->DueDate = $request->DueDate;
        $customerRequirements->PotentialVolume = $request->PotentialVolume;
        $customerRequirements->UnitOfMeasureId = $request->UnitOfMeasureId;
        $customerRequirements->PrimarySalesPersonId = $request->PrimarySalesPersonId;
        $customerRequirements->TargetPrice = $request->TargetPrice;
        $customerRequirements->CurrencyId = $request->CurrencyId;
        $customerRequirements->SecondarySalesPersonId = $request->SecondarySalesPersonId;
        $customerRequirements->Competitor = $request->Competitor;
        $customerRequirements->CompetitorPrice = $request->CompetitorPrice;
        $customerRequirements->RefCrrNumber = $request->RefCrrNumber;
        $customerRequirements->RefRpeNumber = $request->RefRpeNumber;
        $customerRequirements->DetailsOfRequirement = $request->DetailsOfRequirement;
        if($request->has('NatureOfRequestId'))
        {
            $crrNature = CrrNature::where('CustomerRequirementId', $id)->delete();
            foreach($request->NatureOfRequestId as $key=>$natureOfRequestId)
            {
                $crrNature = new CrrNature;
                $crrNature->CustomerRequirementId = $id;
                $crrNature->NatureOfRequestId = $natureOfRequestId;
                $crrNature->save();
            }
        }
        $customerRequirements->save();

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }

    public function view($id)
    {
        $customerRequirement = CustomerRequirement::findOrFail($id);

        return view('customer_requirements.view_crr',
            array(
                'crr' => $customerRequirement
            )
        );
    }
}

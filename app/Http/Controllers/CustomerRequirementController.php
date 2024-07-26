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

class CustomerRequirementController extends Controller
{
    // List
    public function index(Request $request)
    {   
        $search = $request->input('search');
        $customer_requirements = CustomerRequirement::with(['client', 'product_application'])
        ->where(function ($query) use ($search){
            $query->where('CrrNumber', 'LIKE', '%' . $search . '%')
            ->orWhere('DateCreated', 'LIKE', '%' . $search . '%')
            ->orWhere('DueDate', 'LIKE', '%' . $search . '%')
            // ->orWhere('client', 'LIKE', '%' . $search . '%')
            // ->orWhere('product_application', 'LIKE', '%' . $search . '%')
            ->orWhere('Recommendation', 'LIKE', '%' . $search . '%');
        })
        ->orderBy('id', 'desc')->paginate(25);
        $product_applications = ProductApplication::all();
        $clients = Client::all();
        $users = User::all();
        $price_currencies = PriceCurrency::all();
        $nature_requests = NatureRequest::all();
        // if(request()->ajax())
        
        // {
        //     return datatables()->of($customer_requirements)
        //             ->addColumn('action', function($data){
        //                 $buttons = '<button type="button" name="edit" id="'.$data->id.'" class="edit btn btn-primary">Edit</button>';
        //                 $buttons .= '&nbsp;&nbsp;';
        //                 $buttons .= '<button type="button" name="delete" id="'.$data->id.'" class="delete btn btn-danger">Delete</button>';
        //                 return $buttons;
        //             })
        //             ->rawColumns(['action'])
        //             ->make(true);
        // }
        return view('customer_requirements.index', compact('customer_requirements', 'clients', 'product_applications', 'users', 'price_currencies', 'nature_requests', 'search')); 
    }

    // Store
    public function store(Request $request)
    // {
    //     $rules = [
    //         'ClientId'              =>    'required|string',
    //         'ApplicationId'         =>    'required|string',
    //         'CreatedDate'           =>    'nullable|date_format:Y-m-d\TH:i'
    //     ];

    //     $customMessages = [
    //         'ClientId.required'         =>  'The client field is required',
    //         'ApplicationId.required'    =>  'The application field is required'
    //     ];

    //     $error = Validator::make($request->all(), $rules, $customMessages);

    //     if($error->fails())
    //     {
    //         return response()->json(['errors' => $error->errors()->all()]);
    //     }

    //     $formattedDate = $request->has('CreatedDate') ? Carbon::parse($request->CreatedDate)->format('Y-m-d H:i:s') : null;

    //     $customerRequirementData = $request->only([
    //         'CrrNumber', 'ClientId', 'Priority', 'ApplicationId',
    //         'DueDate', 'PotentialVolume', 'UnitOfMeasureId', 'PrimarySalesPersonId', 'TargetPrice',
    //         'CurrencyId', 'SecondarySalesPersonId', 'Competitor', 'CompetitorPrice', 'RefCrrNumber',
    //         'RefRpeNumber', 'DetailsOfRequirement'
    //     ]);

    //     if ($formattedDate) {
    //         $customerRequirementData['CreatedDate'] = $formattedDate;
    //     }
        
    //     $customer_requirement = CustomerRequirement::create($customerRequirementData);
    //     // dd($customer_requirement);

    //     if (is_array($request->NatureOfRequestId)) {
    //         foreach ($request->NatureOfRequestId as $natureOfRequestId) {
    //             CrrNature::create([
    //                 'CustomerRequirementId' => $customer_requirement->id,
    //                 'NatureOfRequestId' => $natureOfRequestId
    //             ]);
    //         }
    //     }

    //     return response()->json(['success' => 'Customer Requirement added successfully.']);
    // }
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
}

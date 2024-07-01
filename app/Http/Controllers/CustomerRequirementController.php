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

class CustomerRequirementController extends Controller
{
    // List
    public function index()
    {   
        $customer_requirements = CustomerRequirement::with(['client', 'product_application'])->orderBy('id', 'desc')->get();
        // dd($customer_requirement);
        $product_applications = ProductApplication::all();
        $clients = Client::all();
        $users = User::all();
        $price_currencies = PriceCurrency::all();
        $nature_requests = NatureRequest::all();
        if(request()->ajax())
        
        {
            return datatables()->of($customer_requirements)
                    ->addColumn('action', function($data){
                        $buttons = '<button type="button" name="edit" id="'.$data->id.'" class="edit btn btn-primary">Edit</button>';
                        $buttons .= '&nbsp;&nbsp;';
                        $buttons .= '<button type="button" name="delete" id="'.$data->id.'" class="delete btn btn-danger">Delete</button>';
                        return $buttons;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('customer_requirements.index', compact('customer_requirements', 'clients', 'product_applications', 'users', 'price_currencies', 'nature_requests')); 
    }

    // Store
    public function store(Request $request)
    {
        $rules = [
            'ClientId'              =>    'required|string',
            'ApplicationId'         =>    'required|string',
            'CreatedDate'           =>    'nullable|date_format:Y-m-d\TH:i'
        ];

        $customMessages = [
            'ClientId.required'         =>  'The client field is required',
            'ApplicationId.required'    =>  'The application field is required'
        ];

        $error = Validator::make($request->all(), $rules, $customMessages);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $formattedDate = $request->has('CreatedDate') ? Carbon::parse($request->CreatedDate)->format('Y-m-d H:i:s') : null;

        $customerRequirementData = $request->only([
            'CrrNumber', 'ClientId', 'Priority', 'ApplicationId',
            'DueDate', 'PotentialVolume', 'UnitOfMeasureId', 'PrimarySalesPersonId', 'TargetPrice',
            'CurrencyId', 'SecondarySalesPersonId', 'Competitor', 'CompetitorPrice', 'RefCrrNumber',
            'RefRpeNumber', 'DetailsOfRequirement'
        ]);

        if ($formattedDate) {
            $customerRequirementData['CreatedDate'] = $formattedDate;
        }
        
        $customer_requirement = CustomerRequirement::create($customerRequirementData);
        // dd($customer_requirement);

        if (is_array($request->NatureOfRequestId)) {
            foreach ($request->NatureOfRequestId as $natureOfRequestId) {
                CrrNature::create([
                    'CustomerRequirementId' => $customer_requirement->id,
                    'NatureOfRequestId' => $natureOfRequestId
                ]);
            }
        }

        return response()->json(['success' => 'Customer Requirement added successfully.']);
    }
}

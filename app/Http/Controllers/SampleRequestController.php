<?php

namespace App\Http\Controllers;
use App\SampleRequest;
use App\Client;
use App\ConcernDepartment;
use App\Contact;
use App\IssueCategory;
use App\ProductApplication;
use App\SampleRequestProduct;
use App\User;
use Illuminate\Http\Request;

class SampleRequestController extends Controller
{
    // List
    public function index()
    {   
        $clients = Client::all();
        $contacts = Contact::all();
        $categories = IssueCategory::all();
        $departments = ConcernDepartment::all(); 
        $productApplications = ProductApplication::all();   
        $salesPersons = User::whereHas('salespersons')->get();
        $sampleRequests = SampleRequest::with(['client','applications'])->get();
        if(request()->ajax())
        {
            return datatables()->of($sampleRequests)
                    ->addColumn('action', function($data){
                        $buttons = '<button type="button" name="edit" id="'.$data->id.'" class="edit btn btn-primary">Edit</button>';
                        $buttons .= '&nbsp;&nbsp;';
                        $buttons .= '<button type="button" name="delete" id="'.$data->id.'" class="delete btn btn-danger">Delete</button>';
                        return $buttons;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('sample_requests.index' , compact('clients', 'contacts', 'categories', 'departments', 'salesPersons', 'productApplications')); 
    }
    public function getSampleContactsByClientF($clientId)
    {
        $contacts = Contact::where('CompanyId', $clientId)->pluck('ContactName', 'id');
        return response()->json($contacts);
    }

    public function getSampleLastIncrementF($year, $clientCode)
{
    $lastUniqueID = SampleRequest::where('SrfNumber', 'like', 'SRF-' . $clientCode . '-' . $year . '-%')
                        ->orderBy('SrfNumber', 'desc')
                        ->first();

    if ($lastUniqueID) {
        $parts = explode('-', $lastUniqueID->SrfNumber);
        $lastIncrement = end($parts);
    } else {
        $lastIncrement = '0000';
    }

    return response()->json(['lastIncrement' => $lastIncrement]);
}

public function store(Request $request)
    {
        // $validatedData = $request->validate([
        //     'DateRequested' => '',
        //     'DateRequired' => '',
        //     'DateStarted' => '',
        //     'PrimarySalesPerson' => '',
        //     'SecondarySalesPerson' => '',
        //     'SoNumber' => '',
        //     'RefCode' => '',
        //     'SrfType' => '',
        //     'ClientId' => '',
        //     'ClientContactId' => '',
        //     'Remarks' => '',
        //     'SrfNumber' => '',
        // ]);

        $samplerequest = SampleRequest::create([
            'SrfNumber' => $request->input('SrfNumber'),
            'DateRequested' => $request->input('DateRequested'),
            'DateRequired' => $request->input('DateRequired'),
            'DateStarted' => $request->input('DateStarted'),
            'PrimarySalesPersonId' => $request->input('PrimarySalesPerson'),
            'SecondarySalesPersonId' => $request->input('SecondarySalesPerson'),
            'SoNumber' => $request->input('SoNumber'),
            'RefCode' => $request->input('RefCode'),
            'Status' => '10',
            'SrfType' => $request->input('SrfType'),
            'ClientId' => $request->input('ClientId'),
            'ContactId' => $request->input('ClientContactId'),
            'InternalRemarks' => $request->input('Remarks'),
            'Courier' => $request->input('Courier'),
            'AwbNumber' => $request->input('AwbNumber'),
            'DateDispatched' => $request->input('DateDispatched'),
            'DateSampleReceived' => $request->input('DateSampleReceived'),
            'DeliveryRemarks' => $request->input('DeliveryRemarks'),
            'Note' => $request->input('Note'),

        ]);
        $maxId = SampleRequestProduct::max('Id');
        foreach ($request->input('ProductType') as $key => $value) {
            SampleRequestProduct::create([
                'Id' => $maxId + $key + 1, 
                'SampleRequestId' => $samplerequest->id,
                'ProductType' => $request->input('ProductType')[$key],
                'ApplicationId' => $request->input('ApplicationId')[$key],
                'ProductCode' => $request->input('ProductCode')[$key],
                'ProductDescription' => $request->input('ProductDescription')[$key],
                'NumberOfPackages' => $request->input('NumberOfPackages')[$key],
                'Quantity' => $request->input('Quantity')[$key],
                'UnitOfMeasureId' => $request->input('UnitOfMeasure')[$key],
                'Label' => $request->input('Label')[$key],
                'RpeNumber' => $request->input('RpeNumber')[$key],
                'CrrNumber' => $request->input('CrrNumber')[$key],
                'Remarks' => $request->input('RemarksProduct')[$key],

            ]);
        }
        return redirect()->route('sample_request.index')->with('success', 'Customer Feedback created successfully.');
    }
}

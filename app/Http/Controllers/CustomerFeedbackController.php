<?php

namespace App\Http\Controllers;
use App\CustomerComplaint;
use App\CustomerFeedback;
use App\Client;
use App\ConcernDepartment;
use App\Contact;
use App\IssueCategory;
use Illuminate\Http\Request;


class CustomerFeedbackController extends Controller
{
    // List 
    // May 28, 2024 Jun Jihad Barroga Modified For Customer Services
    public function index()
    {   
        // $clients = CustomerComplaint::with('client')->get();
        $clients = Client::all();
        $contacts = Contact::all();
        $categories = IssueCategory::all();
        $departments = ConcernDepartment::all();        
       
        $customerFeedbacks = CustomerFeedback::with(['client', 'contacts','departments'])->where('ServiceNumber', 'like', 'FBK%')->get();
        if(request()->ajax())
        {
            return datatables()->of($customerFeedbacks)
                    ->addColumn('action', function($data){
                        $viewUrl = route('customer-feedback.view', $data->id);
                        $buttons = '<button type="button" name="view" id="'.$data->id.'"  data-url="'.$viewUrl.'" class="view btn btn-primary">View</button>';
                        $buttons .= '&nbsp;&nbsp;';
                        $buttons .= '<button type="button" name="edit" id="'.$data->id.'" class="edit btn btn-info">Edit</button>';
                        $buttons .= '&nbsp;&nbsp;';
                        $buttons .= '<button type="button" name="delete" id="'.$data->id.'" class="delete btn btn-danger">Delete</button>';
                        return $buttons;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('customer_feedbacks.index', compact('clients', 'contacts', 'categories', 'departments')); 
    }

    public function getContactsByClientF($clientId)
    {
        $contacts = Contact::where('CompanyId', $clientId)->pluck('ContactName', 'id');
        return response()->json($contacts);
    }
    
    // May 28, 2024 Jun Jihad Barroga Modified For Customer Complaint
    // May 29, 2024 Jun Jihad Barroga Created For Unique Service Number Generation
    public function getLastIncrementF($year, $clientCode)
    {
        $lastUniqueID = CustomerFeedback::where('ServiceNumber', 'like', 'FBK-' . $clientCode . '-' . $year . '-%')
                            ->orderBy('ServiceNumber', 'desc')
                            ->first();

        if ($lastUniqueID) {
            $parts = explode('-', $lastUniqueID->ServiceNumber);
            $lastIncrement = end($parts);
        } else {
            $lastIncrement = '0000';
        }

        return response()->json(['lastIncrement' => $lastIncrement]);
    }

    // May 29, 2024 Jun Jihad Barroga Created For Unique Service Number Generation
    
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'DateReceived' => '',
            'ClientId' => '',
            'ClientContactId' => '',
            'ConcernedDepratment' => '',
            'Title' => '',
            // 'Classification' => '',
            'Description' => '',
            'UniqueID' => '',
        ]);

        $complaint = CustomerFeedback::create([
            'ServiceNumber' => $validatedData['UniqueID'],
            'Type' => '30',
            'DateReceived' => $validatedData['DateReceived'],
            'ClientId' => $validatedData['ClientId'],
            'ClientContactId' => $validatedData['ClientContactId'],
            'Title' => $validatedData['Title'],
            'Description' => $validatedData['Description'],
            'Status' => '10',
            // 'Classification' => $validatedData['Classification'],
            'ConcernedDepartmentId' => $validatedData['ConcernedDepratment'],
        ]);

    
        $clients = Client::all();
        $contacts = Contact::all();
        $categories = IssueCategory::all();
        $departments = ConcernDepartment::all();

        return redirect()->route('customer_feedback.index')->with('success', 'Customer Feedback created successfully.');
    }

    public function view($id)
    {
        $clients = Client::all();
        $customerFeedback = CustomerFeedback::with(['client', 'contacts', 'departments'])->findOrFail($id);
        return view('customer_feedbacks.view', compact('customerFeedback', 'clients'));
    }

    public function edit($id)
    {
        $customerFeedback = CustomerFeedback::findOrFail($id);
        return response()->json($customerFeedback);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'TitleEdit' => '',
            'ClientContactIdEdit' => '',
            'ConcernedDepratmentEdit' => '',
            'DescriptionEdit' => '',
            // 'CLassificationEdit' => '',

        ]);
        CustomerFeedback::whereId($id)->update([
            'Title' => $validatedData['TitleEdit'],
            'ClientContactId' => $validatedData['ClientContactIdEdit'],
            'ConcernedDepartmentId' => $validatedData['ConcernedDepratmentEdit'],
            'Description' => $validatedData['DescriptionEdit'],
        ]);
        return response()->json(['success' => true]);
    }
    public function destroy($id)
    {
        $customerFeedback = CustomerFeedback::findOrFail($id);
        $customerFeedback->delete();

        return response()->json(['success' => true]);
    }
}

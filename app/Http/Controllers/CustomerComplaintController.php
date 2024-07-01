<?php

namespace App\Http\Controllers;
use App\CustomerComplaint;
use App\Client;
use App\ConcernDepartment;
use App\Contact;
use App\IssueCategory;
use Illuminate\Http\Request;


class CustomerComplaintController extends Controller
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
       
        $customerComplaints = CustomerComplaint::with(['client', 'contacts'])->where('ServiceNumber', 'like', 'CPL%')->get();
        if(request()->ajax())
        {
            return datatables()->of($customerComplaints)
                    ->addColumn('action', function($data){
                        $viewUrl = route('customer_complaint.view', $data->id);
                        $buttons = '<button type="button" name="view" id="'.$data->id.'" data-url="'.$viewUrl.'" class="view btn btn-primary">View</button>';
                        $buttons .= '&nbsp;&nbsp;';
                        $buttons .= '<button type="button" name="edit" id="'.$data->id.'" class="edit btn btn-info">Edit</button>';
                        $buttons .= '&nbsp;&nbsp;';
                        $buttons .= '<button type="button" name="delete" id="'.$data->id.'" class="delete btn btn-danger">Delete</button>';
                        return $buttons;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('customer_complaints.index', compact('clients', 'contacts', 'categories', 'departments')); 
    }

    public function getContactsByClient($clientId)
    {
        $contacts = Contact::where('CompanyId', $clientId)->pluck('ContactName', 'id');
        return response()->json($contacts);
    }
    // May 28, 2024 Jun Jihad Barroga Modified For Customer Complaint
    // May 29, 2024 Jun Jihad Barroga Created For Unique Service Number Generation
    public function getLastIncrement($year, $clientCode)
    {
        $lastUniqueID = CustomerComplaint::where('ServiceNumber', 'like', 'CPL-' . $clientCode . '-' . $year . '-%')
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
            'DateReceived' => 'required',
            'ClientId' => 'required',
            'ClientContactId' => 'required',
            'IssueCategory' => 'required',
            'Severity' => 'required',
            'ConcernedDepratment' => 'required',
            'Title' => 'required',
            'Description' => 'required',
            'ETC' => 'required',
            'UniqueID' => 'required',
        ]);

        $complaint = CustomerComplaint::create([
            'ServiceNumber' => $validatedData['UniqueID'],
            'Type' => '20',
            'DateReceived' => $validatedData['DateReceived'],
            'ClientId' => $validatedData['ClientId'],
            'ClientContactId' => $validatedData['ClientContactId'],
            'Title' => $validatedData['Title'],
            'Description' => $validatedData['Description'],
            'Status' => '10',
            'IssueCategoryId' => $validatedData['IssueCategory'],
            'Severity' => $validatedData['Severity'],
            'Etc' => $validatedData['ETC'],
            'ConcernedDepartmentId' => $validatedData['ConcernedDepratment'],
        ]);

        $clients = Client::all();
        $contacts = Contact::all();
        $categories = IssueCategory::all();
        $departments = ConcernDepartment::all();
        // return view('customer_complaints.index', compact('clients', 'contacts', 'categories', 'departments')); 
        return redirect()->route('customer_complaint.index')->with('success', 'Customer Feedback created successfully.');

        }
        public function view($id)
        {
            $clients = Client::all();
            $CustomerComplaint = CustomerComplaint::with(['client', 'contacts', 'departments', 'categories'])->findOrFail($id);
            return view('customer_complaints.view', compact('CustomerComplaint', 'clients'));
        }
        public function edit($id)
        {
            $customerComplaint = CustomerComplaint::findOrFail($id);
            return response()->json($customerComplaint);
        }
        public function update(Request $request, $id)
        {
            $validatedData = $request->validate([
                'TitleEdit' => '',
                'ClientContactIdEdit' => '',
                'ConcernedDepratmentEdit' => '',
                'IssueCategoryEdit' => '',
                'SeverityEdit' => '',
                'DescriptionEdit' => '',
                'ETCEdit' => '',
    
            ]);
            CustomerComplaint::whereId($id)->update([
                    'Title' => $validatedData['TitleEdit'],
                    'ClientContactId' => $validatedData['ClientContactIdEdit'],
                    'ConcernedDepartmentId' => $validatedData['ConcernedDepratmentEdit'],
                    'Description' => $validatedData['DescriptionEdit'],
                    'IssueCategoryId' => $validatedData['IssueCategoryEdit'],
                    'Severity' => $validatedData['SeverityEdit'],
                    'Etc' => $validatedData['ETCEdit'],
    
    
                ]);
                $clients = Client::all();
                $contacts = Contact::all();
                $categories = IssueCategory::all();
                $departments = ConcernDepartment::all();
                return view('customer_complaints.index', compact('clients', 'contacts', 'categories', 'departments')); 
        }
        public function destroy($id)
        {
            $customerComplaint = CustomerComplaint::findOrFail($id);
            $customerComplaint->delete();

            return response()->json(['success' => true]);
        }
}

<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Client;
use App\Contact;
use App\FileActivity;
use App\User;
use Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    // List
    public function index(Request $request)
    {   
        $activities = Activity::with(['client'])
            ->when($request->isShowOpen == 'true' && $request->isShowClosed == 'true', function ($query) {
                return $query; // No filtering needed
            })
            ->when($request->isShowOpen == 'true' && $request->isShowClosed == 'false', function ($query) {
                return $query->where('Status', 10); // Only Open
            })
            ->when($request->isShowOpen == 'false' && $request->isShowClosed == 'true', function ($query) {
                return $query->where('Status', '!=', 10); // Only Closed
            })
            ->orderBy('id', 'desc')
            ->get();

        $clients = Client::all();
        $contacts = Contact::all();
        $users = User::where('is_active', '1')->get();
        $currentUser = Auth::user();

        if ($request->ajax()) {
            return datatables()->of($activities)
                ->addColumn('action', function($data){
                    $buttons = '<a type="button" href="' . route("activity.view", ["id" => $data->id]) . '" name="view" id="' . $data->id . '" class="view btn-table btn btn-success"><i class="ti-eye"></i></a>';
                    $buttons .= '&nbsp;&nbsp;';
                    $buttons .= '<button type="button" name="edit" id="'.$data->id.'" class="edit btn-table btn btn-primary"><i class="ti-pencil"></i></button>';
                    return $buttons;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        
        return view('activities.index', compact('activities', 'clients', 'contacts', 'users', 'currentUser')); 
    }

    // Client Contact 
    public function getContacts($clientId)
    {
        $contacts = Contact::where('CompanyId', $clientId)->pluck('ContactName', 'id');
        return response()->json($contacts);
    }

    // Store
    public function store(Request $request) 
    {
        $rules = array(
            'ClientId'          =>  'required',
            'ClientContactId'   =>  'required',
            'Title'             =>  'required',
            'path.*'            =>  'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048'
        );

        $customMessages = array(
            'ClientId.required'             =>      'The client field is required.',
            'ClientContactId.required'      =>      'The contact field is required.',
            'path.*.mimes'                  =>      'The file must be a type of: jpg, jpeg, png, pdf, doc, docx.'
        );

        $error = Validator::make($request->all(), $rules, $customMessages);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'Type'                          =>  $request->Type,
            'ClientId'                      =>  $request->ClientId,
            'Title'                         =>  $request->Title,
            'ActivityNumber'                =>  $request->ActivityNumber, 
            'ClientContactId'               =>  $request->ClientContactId,  
            'PrimaryResponsibleUserId'      =>  $request->PrimaryResponsibleUserId,
            'SecondaryResponsibleUserId'    =>  $request->SecondaryResponsibleUserId,
            'RelatedTo'                     =>  $request->RelatedTo,
            'TransactionNumber'             =>  $request->TransactionNumber,
            'ScheduleFrom'                  =>  $request->ScheduleFrom, 
            'ScheduleTo'                    =>  $request->ScheduleTo,
            'Description'                   =>  $request->Description,
            'Status'                        =>  '10',
        );

        $activity = Activity::create($form_data);

        if ($request->hasFile('path')) {
            foreach ($request->file('path') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
    
                // Store each file and get the path
                $path = $file->storeAs('uploads', $fileName, 'public');
    
                FileActivity::create([
                    'activity_id' => $activity->id,
                    'path' => $path
                ]);
            }
        }

        return response()->json(['success' => 'Data Added Successfully.']);
    }

    // Edit
    public function edit($id)
    {
        $activity = Activity::find($id);

        if (!$activity) {
            return response()->json(['error' => 'Activity not found.'], 404);
        }

        $files = FileActivity::where('activity_id', $id)->pluck('path')->toArray();
        // Assuming you have relationships defined in your Activity model
        $primaryUser = User::where('id', $activity->PrimaryResponsibleUserId)
                            ->orWhere('user_id', $activity->PrimaryResponsibleUserId)
                            ->first();

        $secondaryUser = User::where('id', $activity->SecondaryResponsibleUserId)
                            ->orWhere('user_id', $activity->SecondaryResponsibleUserId)
                            ->first();

        return response()->json([
            'data' => $activity,
            'files' => $files,
            'primaryUser' => $primaryUser,
            'secondaryUser' => $secondaryUser
        ]);
    }    

    // Get Value of Contact Client
    public function getContactsByClient($clientId)
    {
        $contacts = Contact::where('id', $clientId)->get();
        return response()->json($contacts);
    }
    
    // Update
    public function update(Request $request, $id)
    {
        $rules = array(
            'ClientId'  =>  'required',
            'Title'     =>  'required'
        );
        
        $customMessages = array(
            'ClientId.required'     =>      'The client field is required.'
        );

        $error = Validator::make($request->all(), $rules, $customMessages);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'Type'                          =>  $request->Type,
            'ClientId'                      =>  $request->ClientId,
            'Title'                         =>  $request->Title,
            'ClientContactId'               =>  $request->ClientContactId,  
            'PrimaryResponsibleUserId'      =>  $request->PrimaryResponsibleUserId,
            'SecondaryResponsibleUserId'    =>  $request->SecondaryResponsibleUserId,
            'RelatedTo'                     =>  $request->RelatedTo,
            'TransactionNumber'             =>  $request->TransactionNumber,
            'ScheduleFrom'                  =>  $request->ScheduleFrom, 
            'ScheduleTo'                    =>  $request->ScheduleTo,
            'Description'                   =>  $request->Description,
            'Status'                        =>  $request->Status,
            'DateClosed'                    =>  $request->DateClosed
        );

        Activity::whereId($id)->update($form_data);

        return response()->json(['success' => 'Data is Successfully Updated.']);
    }

    // View
    public function view($id)
    {
        $data = Activity::find($id);
        $users = User::all();

        // Retrieve the contact and handle null case
        $contact = Contact::find($data->ClientContactId);
        $contactName = $contact ? $contact->ContactName : 'N/A';
        $contactEmail = $contact ? $contact->EmailAddress : 'N/A';
        $contactMobile = $contact ? $contact->PrimaryMobile : 'Mobile not found';
        $contactSkype = $contact ? $contact->Skype : 'Skype not found';

        // Retrieve the client and handle null case
        $client = Client::find($data->ClientId);
        $clientName = $client->Name;
        $clientTelephone = $client->TelephoneNumber;

        // Find the primary and secondary responsible users
        $primaryResponsible = $users->firstWhere('user_id', $data->PrimaryResponsibleUserId) ?? $users->firstWhere('id', $data->PrimaryResponsibleUserId);
        $secondaryResponsible = $users->firstWhere('user_id', $data->SecondaryResponsibleUserId) ?? $users->firstWhere('id', $data->SecondaryResponsibleUserId);

        return view('activities.view', compact('data', 'primaryResponsible', 'secondaryResponsible', 'clientName', 'contactName', 'contactEmail', 'clientTelephone', 'contactMobile'));
    }

    // Close
    public function close($id)
    {
        try {
            // Find the activity by ID
            $activity = Activity::findOrFail($id);
            
            // Update the status to 'Closed' (assuming 'Status' is an attribute of Activity model)
            $activity->status = 20; // Assuming 20 represents a closed status
            $activity->DateClosed = Carbon::now();
            
            // Save the updated status
            $activity->save();

            return response()->json([
                'success' => true,
                'message' => 'Activity closed successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to close activity. Error: ' . $e->getMessage()
            ], 500);
        }
    }

    // Open
    public function open($id)
    {
        try {
            // Find the activity by ID
            $activity = Activity::findOrFail($id);
            
            // Update the status to 'Closed' (assuming 'Status' is an attribute of Activity model)
            $activity->status = 10; // Assuming 10 represents a open status
            $activity->DateClosed = null;
            
            // Save the updated status
            $activity->save();

            return response()->json([
                'success' => true,
                'message' => 'Activity opened successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to open activity. Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
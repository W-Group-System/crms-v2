<?php

namespace App\Http\Controllers;
use App\NatureRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class NatureRequestController extends Controller
{
    // List
    public function index(Request $request)
    {   
        $search = $request->input('search');
        $natureRequests = NatureRequest::where(function ($query) use ($search) {
            $query->where('Name', 'LIKE', '%' . $search . '%')
                ->orWhere('Description', 'LIKE', '%' . $search . '%');        
        })
        ->orderBy('id', 'desc')->paginate(25);
        return view('nature_requests.index',  compact('natureRequests', 'search')); 
    }

    // Store
    public function store(Request $request) 
    {
        $existing = NatureRequest::where('Name', $request->Name)->exists();
        if (!$existing) {
            $form_data = array(
                'Name'          =>  $request->Name,
                'Description'   =>  $request->Description
            );
    
            NatureRequest::create($form_data);
    
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
            $data = NatureRequest::findOrFail($id);
            return response()->json(['data' => $data]);
        }
    }

    // Update
    public function update(Request $request, $id)
    {
        $natureRequest = $request->Name;
        $exists = NatureRequest::where('Name', $natureRequest)
        ->where('id', '!=', $id)->first();
        if ($exists){
            return redirect()->back()->with('error', $request->Name . ' already exists.');
        }
        
        $form_data = array(
            'Name'          =>  $request->Name,
            'Description'   =>  $request->Description
        );

        NatureRequest::whereId($id)->update($form_data);
        return redirect()->back()->with('success', 'Nature Request updated successfully.');
    }

    // Delete
    public function delete($id)
    {
        $natureRequest = NatureRequest::findOrFail($id);
        $natureRequest->delete();

        // Optionally, return a response
        return response()->json(['success' => 'Nature of Request has been deleted.']);
    }
}

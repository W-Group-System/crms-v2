<?php

namespace App\Http\Controllers;
use App\ProjectName;
use Validator;
use Illuminate\Http\Request;

class ProjectNameController extends Controller
{
    // List
    public function index(Request $request)
    {   
        $search = $request->input('search');
        $projectNames = ProjectName::where(function ($query) use ($search) {
            $query->where('Name', 'LIKE', '%' . $search . '%')
                ->orWhere('Description', 'LIKE', '%' . $search . '%');        
        })
        ->orderBy('id', 'desc')->paginate(10);
        return view('project_name.index', compact('projectNames', 'search')); 
    }

    // Store
    public function store(Request $request) 
    {
        $existing = ProjectName::where('Name', $request->Name)->exists();
        if (!$existing){
            $form_data = array(
                'Name'          =>  $request->Name,
                'Description'   =>  $request->Description
            );
    
            ProjectName::create($form_data);
            return redirect()->back()->with('success', 'Project Name created successfully.');
        } else {
            return back()->with('error', $request->Name . ' already exists.');
        }
    }

    // Edit
    public function edit($id)
    {
        if(request()->ajax())
        {
            $data = ProjectName::findOrFail($id);
            return response()->json(['data' => $data]);
        }
    }

    // Update
    public function update(Request $request, $id)
    {
        $projectName = $request->Name;
        $exists = ProjectName::where('Name', $projectName)
        ->where('id', '!=', $id)->first();
        if ($exists){
            return redirect()->back()->with('error', $request->Name . ' already exists.');
        }
        $projectName = ProjectName::findOrFail($id);
        $projectName->Name = $request->input('Name');
        $projectName->Description = $request->input('Description');
        $projectName->save();
        return redirect()->back()->with('success', 'Project Name updated successfully');
    }

    // Delete
    public function delete($id)
    {
        $data = ProjectName::findOrFail($id);
        $data->delete();
    }
}

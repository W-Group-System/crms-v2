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
        ->orderBy('id', 'desc')->paginate(25);
        return view('project_name.index', compact('projectNames', 'search')); 
    }

    // Store
    public function store(Request $request) 
    {
        $rules = array(
            'Name'          =>  'required',
            'Description'   =>  'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'Name'          =>  $request->Name,
            'Description'   =>  $request->Description
        );

        ProjectName::create($form_data);

        return redirect()->back()->with('success', 'Project Name created successfully.');
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
         $projectName = ProjectName::findOrFail($id);
            $projectName->Name = $request->input('Name');
            $projectName->Description = $request->input('Description');
            $projectName->save();
        return back();
    }

    // Delete
    public function delete($id)
    {
        $data = ProjectName::findOrFail($id);
        $data->delete();
    }
}

<?php

namespace App\Http\Controllers;

use App\Categorization;
use App\ProjectName;
use Validator;
use Illuminate\Http\Request;

class CategorizationController extends Controller
{
    // List
    public function index(Request $request)
    {   
        $search = $request->input('search');
        $categorizations = Categorization::where(function ($query) use ($search) {
            $query->where('Name', 'LIKE', '%' . $search . '%')
                ->orWhere('Description', 'LIKE', '%' . $search . '%');        
        })
        ->orderBy('id', 'desc')->paginate(25);
        return view('categorizations.index', compact('categorizations', 'search')); 
    }

    // Store
    public function store(Request $request) 
    {
        $existing = Categorization::where('Name', $request->Name)->exists();
        if (!$existing) {
            $form_data = array(
                'Name'          => $request->Name,
                'Description'   => $request->Description
            );
    
            Categorization::create($form_data);
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
        $categorizationName = $request->Name;
        $exists = Categorization::where('Name', $categorizationName)
        ->where('id', '!=', $id)->first();
        if ($exists){
            return redirect()->back()->with('error', $request->Name . ' already exists.');
        }
         $categorization = Categorization::findOrFail($id);
         $categorization->Name = $request->input('Name');
         $categorization->Description = $request->input('Description');
         $categorization->save();
         return redirect()->back()->with('success', 'Cateegorization updated successfully');
    }

    // Delete
    public function delete($id)
    {
        $data = Categorization::findOrFail($id);
        $data->delete();
    }
}

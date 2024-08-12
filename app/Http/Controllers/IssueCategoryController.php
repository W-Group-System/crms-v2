<?php

namespace App\Http\Controllers;
use App\IssueCategory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class IssueCategoryController extends Controller
{
    // List
    public function index(Request $request)
    {   
        $search = $request->input('search');
        $sort = $request->get('sort', 'Name'); // Default to 'Department' if no sort is specified
        $direction = $request->get('direction', 'asc'); // Default to ascending order

        $issuesCategory = IssueCategory::where(function ($query) use ($search) {
                $query->where('Name', 'LIKE', '%' . $search . '%')
                    ->orWhere('Description', 'LIKE', '%' . $search . '%');        
            })
            ->orderBy($sort, $direction) // Order by the specified column and direction
            ->paginate(10);

        return view('issue_categories.index', [
            'search' => $search,
            'issuesCategory' => $issuesCategory,
        ]);
    }

    // Store
    public function store(Request $request) 
    {

        $rules = [
            'Name' => [
                'required',
                Rule::unique('customerserviceissuecategories', 'Name')
                    ->whereNull('deleted_at')
            ],
            'Description' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        // Proceed with saving the new issue category
        $issueCategory = new IssueCategory();
        $issueCategory->Name = $request->Name;
        $issueCategory->Description = $request->Description;
        $issueCategory->save();

        return response()->json(['success' => 'Issue Category added successfully.']);
    }

    // Edit
    public function edit($id)
    {
        if(request()->ajax())
        {
            $data = IssueCategory::findOrFail($id);
            return response()->json(['data' => $data]);
        }
    }

    // Update
    public function update(Request $request, $id)
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

        IssueCategory::whereId($id)->update($form_data);

        return response()->json(['success' => 'Data is Successfully Updated.']);
    }

    // Delete
    public function delete($id)
    {
        try {
            $data = IssueCategory::findOrFail($id);
            $data->delete();

            return response()->json(['success' => 'Issue category deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete issue category.'], 500);
        }
    }
}

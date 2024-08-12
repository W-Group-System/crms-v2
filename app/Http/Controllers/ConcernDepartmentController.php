<?php

namespace App\Http\Controllers;
use App\ConcernDepartment;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class ConcernDepartmentController extends Controller
{
    // List

    public function index(Request $request)
    {
        $search = $request->get('search');
        $sort = $request->get('sort', 'Name'); // Default to 'Department' if no sort is specified
        $direction = $request->get('direction', 'asc'); // Default to ascending order

        // Modify the query to include the sorting logic
        $concernDepartments = ConcernDepartment::where('Name', 'like', '%' . $search . '%')
            ->orWhere('Description', 'like', '%' . $search . '%')
            ->orderBy($sort, $direction) // Order by the specified column and direction
            ->paginate(10); // Adjust the pagination as necessary

            return view('concerned_departments.index', [
                'search' => $search,
                'concern_departments' => $concernDepartments,
            ]); 
    }


    // Store
    public function store(Request $request) 
    {
        // $rules = array(
        //     'Name'          =>  'required',
        //     'Description'   =>  'required'
        // );  
        $rules = [
            'Name' => [
                'required',
                Rule::unique('customerserviceconcerneddepartm', 'Name')
                    ->whereNull('deleted_at')
            ],
            'Description' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $form_data = array(
            'Name'          =>  $request->Name,
            'Description'   =>  $request->Description
        );

        ConcernDepartment::create($form_data);

        return response()->json(['success' => 'Concerned Department added successfully.']);
    }

    // Edit
    public function edit($id)
    {
        if(request()->ajax())
        {
            $data = ConcernDepartment::findOrFail($id);
            return response()->json(['data' => $data]);
        }
    }

    // Update
    public function update(Request $request, $id)
    {
        $rules = [
            'Name' => [
                'required',
                Rule::unique('customerserviceconcerneddepartm', 'Name')
                    ->whereNull('deleted_at')
            ],
            'Description' => 'required',
        ];

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'Name'          =>  $request->Name,
            'Description'   =>  $request->Description
        );

        ConcernDepartment::whereId($id)->update($form_data);

        return response()->json(['success' => 'Data is Successfully Updated.']);
    }

    // Delete
    public function delete($id)
    {
        try {
            $data = ConcernDepartment::findOrFail($id);
            $data->delete();

            return response()->json(['success' => 'Concerned department deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete issue category.'], 500);
        }
    }
}

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
        $search = $request->input('search');
        $sort = $request->get('sort', 'Name'); // Default to 'Name' if no sort is specified
        $direction = $request->get('direction', 'asc'); // Default to ascending order
        $fetchAll = $request->input('fetch_all', false); // Get the fetch_all parameter

        // Ensure sort and direction are valid
        $validSorts = ['Name', 'Description'];
        if (!in_array($sort, $validSorts)) {
            $sort = 'Name';
        }
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        $query = ConcernDepartment::where(function ($query) use ($search) {
                $query->where('Name', 'LIKE', '%' . $search . '%')
                    ->orWhere('Description', 'LIKE', '%' . $search . '%');
            })
            ->orderBy($sort, $direction);

        if ($fetchAll) {
            $concernDepartments = $query->get(); // Fetch all results
            return response()->json($concernDepartments); // Return JSON response for copying
        } else {
            $concernDepartments = $query->paginate(10); // Default pagination
            return view('concerned_departments.index', [
                'search' => $search,
                'concernDepartments' => $concernDepartments,
                'fetchAll' => $fetchAll
            ]);
        }
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

    // Export
    public function exportConcernedDepartment(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->get('sort', 'Name'); // Default to 'Name' if no sort is specified
        $direction = $request->get('direction', 'asc'); // Default to ascending order

        // Define a list of valid columns for sorting
        $validSortColumns = ['Name', 'Description'];

        // Validate sort column
        if (!in_array($sort, $validSortColumns)) {
            $sort = 'Name'; // Default to 'Name' if invalid
        }

        // Fetch all records based on search, sort, and direction
        $concernDepartments = ConcernDepartment::where(function ($query) use ($search) {
                $query->where('Name', 'LIKE', '%' . $search . '%')
                    ->orWhere('Description', 'LIKE', '%' . $search . '%');
            })
            ->orderBy($sort, $direction)
            ->get(); // Fetch all results

        // Convert data to an array format that can be easily handled by JavaScript
        return response()->json($concernDepartments);
    }
}

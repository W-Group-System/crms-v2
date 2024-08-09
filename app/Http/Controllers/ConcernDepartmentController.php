<?php

namespace App\Http\Controllers;
use App\ConcernDepartment;
use Validator;
use Illuminate\Http\Request;

class ConcernDepartmentController extends Controller
{
    // List
    public function index(Request $request)
    {   
        // if(request()->ajax())
        // {
        //     return datatables()->of(ConcernDepartment::query())
        //             ->addColumn('action', function($data){
        //                 $buttons = '<button type="button" name="edit" id="'.$data->id.'" class="edit btn btn-primary">Edit</button>';
        //                 $buttons .= '&nbsp;&nbsp;';
        //                 $buttons .= '<button type="button" name="delete" id="'.$data->id.'" class="delete btn btn-danger">Delete</button>';
        //                 return $buttons;
        //             })
        //             ->rawColumns(['action'])
        //             ->make(true);
        // }
        // return view('concerned_departments.index');
        $search = $request->input('search');
        $concern_departments = ConcernDepartment::where(function ($query) use ($search) {
                            $query->where('Name', 'LIKE', '%' . $search . '%')
                                ->orWhere('Description', 'LIKE', '%' . $search . '%');
                            })
                        ->orderBy('id', 'desc')
                        ->paginate(10);
        
        return view('concerned_departments.index', [
            'search' => $search,
            'concern_departments' => $concern_departments,
        ]); 
    }

    //  Store
    // public function store(Request $request) 
    // {
    //     $rules = array(
    //         'Name'          =>  'required',
    //         'Description'   =>  'required'
    //     );

    //     $error = Validator::make($request->all(), $rules);

    //     if($error->fails())
    //     {
    //         return response()->json(['errors' => $error->errors()->all()]);
    //     }

    //     $form_data = array(
    //         'Name'          =>  $request->Name,
    //         'Description'   =>  $request->Description
    //     );

    //     ConcernDepartment::create($form_data);

    //     return redirect()->back()->with('success', 'New Base Price updated successfully');
    // }

    public function store(Request $request) 
    {
        $existing = ConcernDepartment::where('Name', $request->Name)->exists();
        if (!$existing) {
            $form_data = array(
                'Name'          =>  $request->Name,
                'Description'   =>  $request->Description
            );
    
            ConcernDepartment::create($form_data);
    
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
            $data = ConcernDepartment::findOrFail($id);
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

        ConcernDepartment::whereId($id)->update($form_data);

        return response()->json(['success' => 'Data is Successfully Updated.']);
    }

    // Delete
    public function delete($id)
    {
        $data = ConcernDepartment::findOrFail($id);
        $data->delete();
    }
}

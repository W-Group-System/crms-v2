<?php

namespace App\Http\Controllers;
use App\Role;
use Validator;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    // List
    public function index(Request $request)
    {
        // if(request()->ajax())
        // {
        //     return datatables()->of(Role::latest()->get())
        //             ->addColumn('action', function($data){
        //                 $buttons = '<button type="button" name="edit" id="'.$data->id.'" class="edit btn btn-primary">Edit</button>';
        //                 $buttons .= '&nbsp;&nbsp;';
        //                 $buttons .= '<button type="button" name="delete" id="'.$data->id.'" class="delete btn btn-danger">Delete</button>';
        //                 return $buttons;
        //             })
        //             ->rawColumns(['action'])
        //             ->make(true);
        // }
        // return view('roles.index');
    
        $search = $request->input('search');
    
        $roles = Role::where(function ($query) use ($search) {
                $query->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('description', 'LIKE', '%' . $search . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate(10);
        
        return view('roles.index', [
            'search' => $search,
            'roles' => $roles,
        ]);
    }

    // Create
    public function store(Request $request) 
    {
        $rules = array(
            'name'          =>  'required',
            'description'   =>  'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'name'          =>  $request->name,
            'description'   =>  $request->description
        );

        Role::create($form_data);

        return response()->json(['success' => 'Data Added Successfully.']);
    }
    
    // Edit
    public function edit($id)
    {
        if(request()->ajax())
        {
            $data = Role::findOrFail($id);
            return response()->json(['data' => $data]);
        }
    }

    // Update 
    public function update(Request $request, $id)
    {
        $rules = array(
            'name'          =>  'required',
            'description'   =>  'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'name'          =>  $request->name,
            'description'   =>  $request->description
        );

        Role::whereId($id)->update($form_data);

        return response()->json(['success' => 'Data is Successfully Updated.']);
    }

    // Delete
    public function delete($id)
    {
        $data = Role::findOrFail($id);
        $data->delete();
    }
}

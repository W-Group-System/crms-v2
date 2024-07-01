<?php

namespace App\Http\Controllers;
use App\User;
use App\Role;
use App\Company;
use App\Department;
use Validator;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // List 
    public function index()
    {
        $users = User::with(['company', 'department', 'role'])->orderBy('id', 'desc')->get();
        
        $departments = Department::all();
        $companies = Company::all();
        $roles = Role::get();
        // dd($departments);
        if(request()->ajax())
        {
            return datatables()->of($users)
                    ->addColumn('action', function($data){
                        $buttons = '<button type="button" name="edit" id="'.$data->id.'" class="edit btn btn-primary">Edit</button>';
                        $buttons .= '&nbsp;&nbsp;';
                        // $buttons .= '<button type="button" name="delete" id="'.$data->id.'" class="delete btn btn-danger">Delete</button>';
                        return $buttons;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('users.index', compact('users', 'companies', 'departments', 'roles'));
    }
    
    // Store
    public function store(Request $request) 
    {
        $rules = array(
            'username'      =>  'required|string|max:255',
            'full_name'     =>  'required|string|max:255',
            'password'      =>  'required|string|min:8',
            // 'role'          =>  'required',
            // 'company'       =>  'required',
            // 'department'    =>  'required'
        );


        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'username'      =>  $request->username,
            'full_name'     =>  $request->full_name,
            'password'      =>  bcrypt($request->password),
            'email'         =>  $request->email,
            'role_id'       =>  $request->role_id,
            'company_id'    =>  $request->company_id,
            'is_active'     =>  1,
            'department_id' =>  $request->department_id,
        );

        User::create($form_data);

        return response()->json(['success' => 'Data Added Successfully.']);
    }

    // Edit
    public function edit($id)
    {
        if(request()->ajax())
        {
            $data = User::findOrFail($id);
            return response()->json(['data' => $data]);
        }
    }

    // Update
    public function update(Request $request, $id)
    {
        $rules = array(
            'username'      =>  'required|string|max:255',
            'full_name'     =>  'required|string|max:255',
            // 'password'      =>  'required|string|min:8',
            // 'role'          =>  'required',
            // 'company'       =>  'required',
            // 'department'    =>  'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'username'      =>  $request->username,
            'full_name'     =>  $request->full_name,
            'email'         =>  $request->email,
            'role_id'       =>  $request->role_id,
            'company_id'    =>  $request->company_id,
            'department_id' =>  $request->department_id,
            'is_active'     =>  $request->is_active
        );

        User::whereId($id)->update($form_data);

        return response()->json(['success' => 'Data is Successfully Updated.']);
    }
}

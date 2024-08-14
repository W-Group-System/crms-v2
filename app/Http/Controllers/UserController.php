<?php

namespace App\Http\Controllers;
use App\User;
use App\Role;
use App\Company;
use App\Department;
use App\Exports\UserExport;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    // List 
    public function index(Request $request)
    {
        $departments = Department::all();
        $companies = Company::all();
        $roles = Role::all();
    
        $search = $request->input('search');
    
        $users = User::with(['role', 'company', 'department'])
                ->where(function ($query) use ($search) {
                    $query->where('username', 'LIKE', '%' . $search . '%')
                        ->orWhere('full_name', 'LIKE', '%' . $search . '%')
                        ->orWhereHas('role', function ($q) use ($search) {
                            $q->where('name', 'LIKE', '%' . $search . '%');
                        })
                        ->orWhereHas('company', function ($q) use ($search) {
                            $q->where('name', 'LIKE', '%' . $search . '%');
                        })
                        ->orWhereHas('department', function ($q) use ($search) {
                            $q->where('name', 'LIKE', '%' . $search . '%');
                        })
                        ->orWhere('email', 'LIKE', '%'.$search.'%');
                })
                ->orderBy('id', 'desc')
                ->paginate(10);
    
        return view('users.index', [
            'search' => $search,
            'users' => $users,
            'roles' => $roles,
            'companies' => $companies,
            'departments' => $departments
        ]);
    }
    
    // Store
    public function store(Request $request) 
    {
        $request->validate([
            'password' => 'confirmed|min:6',
            'email' => 'email|unique:users,email',
        ]);

        $user = new User;
        $user->user_id = 'N/A';
        $user->username = $request->username;
        $user->full_name = $request->full_name;
        $user->password = bcrypt($request->password);
        $user->email = $request->email;
        $user->role_id = $request->role_id;
        $user->company_id = $request->company_id;
        $user->department_id = $request->department_id;
        $user->is_active = 1;
        $user->save();

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back();
    }

    // Update
    public function update(Request $request, $id)
    {
        $request->validate([
            'password' => 'confirmed|min:6',
            'email' => 'email|unique:users,email,' . $id
        ]);

        $user = User::findOrFail($id);
        $user->user_id = 'N/A';
        $user->username = $request->username;
        $user->full_name = $request->full_name;
        // $user->password = bcrypt($request->password);
        $user->email = $request->email;
        $user->role_id = $request->role_id;
        $user->company_id = $request->company_id;
        $user->department_id = $request->department_id;
        $user->is_active = $request->is_active;
        $user->save();

        Alert::success('Successfully Update')->persistent('Dismiss');
        return back();
    }

    public function userChangePassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'confirmed|min:6',
        ]);

        $user = User::findOrFail($id);
        $user->password = bcrypt($request->password);
        $user->save();

        Alert::success('Successfully Change Password')->persistent('Dismiss');
        return back();
    }

    public function exportUser()
    {
        return Excel::download(new UserExport, 'User.xlsx');
    }
}

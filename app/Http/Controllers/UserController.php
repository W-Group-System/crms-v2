<?php

namespace App\Http\Controllers;
use App\User;
use App\Role;
use App\Company;
use App\Department;
use App\Exports\UserExport;
use App\RndApprovers;
use App\SalesApprovers;
use App\SecondarySalesPerson;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    // List 
    public function index(Request $request)
    {
        if (auth()->user()->department_id == 1)
        {
            $departments = Department::all();
            $roles = Role::where('status', 'Active')->get();
            $companies = Company::where('status', 'Active')->get();
            $approvers = User::with('salesApproverByUserId', 'salesApproverById')->where('is_active', 1)->get();
            $search = $request->input('search');
            $sales = User::with('secondarySalesPerson')->whereHas('role', function($query) {
                $query->whereIn('type', ['LS', 'IS']);
            })->get();
            
            $users = User::with(['role', 'company', 'department'])
                    ->when($search, function ($query) use ($search) {
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
                    ->latest();

            if ($request->fetch_all)
            {
                $users = $users->get();

                return response()->json($users);
            }
            else
            {
                $users = $users->paginate($request->entries ?? 10);
            
                return view('users.index', [
                    'search' => $search,
                    'users' => $users,
                    'roles' => $roles,
                    'companies' => $companies,
                    'departments' => $departments,
                    'approvers' => $approvers,
                    'entries' => $request->entries,
                    'user_status' => ['1' => 'Active', '0' => 'Inactive'],
                    'sales' => $sales
                ]);
            }
        }
        else
        {
            return redirect('/');
        }
    }
    
    // Store
    public function store(Request $request) 
    {
        // dd($request->all());
        $request->validate([
            'password' => 'confirmed|min:6',
            'email' => 'email|unique:users,email',
        ]);

        $user = new User;
        // $user->user_id = 'N/A';
        $user->username = $request->username;
        $user->full_name = $request->full_name;
        $user->password = bcrypt($request->password);
        $user->email = $request->email;
        $user->role_id = $request->role_id;
        $user->company_id = $request->company_id;
        $user->department_id = $request->department_id;
        $user->is_active = 1;
        $user->save();

        if ($request->has('user_approvers'))
        {
            $approvers = User::whereIn('id', $request->user_approvers)->get();

            if ($request->department_id == 5 || $request->department_id == 38)
            {
                foreach($approvers as $key=>$approver)
                {
                    $salesApprover = new SalesApprovers;
                    $salesApprover->SalesApproverId = $approver->id;
                    $salesApprover->UserId = $user->id;
                    if ($approver->department_id == 5)
                    {
                        $salesApprover->Type = 2;
                    }
                    elseif($approver->department_id == 38)
                    {
                        $salesApprover->Type = 1;
                    }
                    $salesApprover->save();
                }
            }

            if ($request->department_id == 15)
            {
                foreach($approvers as $key=>$approver)
                {
                    $rndApprover = new RndApprovers;
                    $rndApprover->UserId = $user->id;
                    $rndApprover->RndApproverId = $approver->id;
                    $rndApprover->save();
                }
            }

        }

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back();
    }

    // Update
    public function update(Request $request, $id)
    {
        // dd($request->all(), $id);
        $request->validate([
            // 'password' => 'confirmed|min:6',
            'email' => 'email|unique:users,email,' . $id
        ]);

        $user = User::findOrFail($id);
        // $user->user_id = 'N/A';
        $user->username = $request->username;
        $user->full_name = $request->full_name;
        // $user->password = bcrypt($request->password);
        $user->email = $request->email;
        $user->role_id = $request->role_id;
        $user->company_id = $request->company_id;
        $user->department_id = $request->department_id;
        $user->is_active = $request->is_active;
        $user->save();

        if ($request->has('user_approvers'))
        {
            $approvers = User::whereIn('id', $request->user_approvers)->get();
            
            if ($request->department_id == 5 || $request->department_id == 38)
            {
                $salesApprover = SalesApprovers::where('UserId', $id)->delete();

                foreach($approvers as $key=>$approver)
                {
                    $salesApprover = new SalesApprovers;
                    $salesApprover->SalesApproverId = $approver->id;
                    $salesApprover->UserId = $user->id;
                    if ($approver->department_id == 5)
                    {
                        $salesApprover->Type = 2;
                    }
                    elseif($approver->department_id == 38)
                    {
                        $salesApprover->Type = 1;
                    }
                    $salesApprover->save();
                }
            }

            if ($request->department_id == 15)
            {
                $rndApprover = RndApprovers::where('UserId', $id)->delete();
                
                foreach($approvers as $key=>$approver)
                {
                    $rndApprover = new RndApprovers;
                    $rndApprover->UserId = $user->id;
                    $rndApprover->RndApproverId = $approver->id;
                    $rndApprover->save();
                }
            }
        }

        if ($request->has('secondary_sales'))
        {
            // dd($id);
            $secondary_sales = SecondarySalesPerson::where('PrimarySalesPersonId', $id)->delete();
            foreach($request->secondary_sales as $key=>$secondarySales)
            {
                $secondary_sales = new SecondarySalesPerson;
                $secondary_sales->PrimarySalesPersonId = $id;
                $secondary_sales->Type = null;
                $secondary_sales->SecondarySalesPersonId = $secondarySales;
                $secondary_sales->save();
            }
        }

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

    public function edit($id)
    {
        $user = User::findOrFail($id);

        $approvers = SalesApprovers::where('UserId', $id)->pluck('SalesApproverId')->toArray();
        if ($approvers == null)
        {
            $approvers = RndApprovers::where('UserId', $id)->pluck('RndApproverId')->toArray();
        }

        return array(
            'username' => $user->username,
            'full_name' => $user->full_name,
            'email' => $user->email,
            'company_id' => $user->company_id,
            'role_id' => $user->role_id,
            'department_id' => $user->department_id,
            'status' => $user->is_active,
            'approvers' => $approvers
        );
    }
}

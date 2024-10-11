<?php

namespace App\Http\Controllers;

use App\Department;
use App\Role;
use App\UserAccessModule;
use Validator;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class RoleController extends Controller
{
    // List
    public function index(Request $request)
    {
        $search = $request->input('search');
    
        $roles = Role::where(function ($query) use ($search) {
                $query->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('description', 'LIKE', '%' . $search . '%');
            })
            ->when($request->filter_department, function($query)use($request) {
                $query->where('department_id', $request->filter_department);
            })
            ->orderBy('id', 'desc')
            ->paginate($request->entries ?? 10);
        
        $department = Department::get();

        return view('roles.index', [
            'search' => $search,
            'roles' => $roles,
            'department' => $department,
            'entries' => $request->entries
        ]);
    }

    // Create
    public function store(Request $request) 
    {
        $role = new Role;
        $role->name = $request->name;
        $role->description = $request->description;
        $role->department_id = $request->department;
        $role->type = $request->type;
        $role->status = "Active";
        $role->save();

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back();
    }
    
    // Edit
    // public function edit($id)
    // {
    //     if(request()->ajax())
    //     {
    //         $data = Role::findOrFail($id);
    //         return response()->json(['data' => $data]);
    //     }
    // }

    // Update 
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $role->name = $request->name;
        $role->type = $request->type;
        $role->description = $request->description;
        $role->department_id = $request->department;
        $role->save();

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back();
    }

    // Delete
    public function delete(Request $request)
    {
        $data = Role::findOrFail($request->id);
        $data->delete();

        return array('message' => 'Successfully Deleted');
    }

    public function moduleAccess(Request $request, $id)
    {
        $role = Role::with('department', 'access')->where('id', $id)->first();

        return view('roles.module_access',
            array(
                'role' => $role,
                'modules' => $this->module()
            )
        );
    }
    
    public function addModuleAccess(Request $request)
    {
        // dd($request->all());
        foreach($request->module as $key=>$module)
        {
            $access = UserAccessModule::where('role_id', $request->role)->where('department_id', $request->department)->where('module_name', $module)->first();
            if ($access == null)
            {
                $access = new UserAccessModule;
                $access->department_id = $request->department;
                $access->role_id = $request->role;
                $access->module_name = $module;

                if ($module == "Payment Terms")
                {
                    if ($request->has('Payment_Terms'))
                    {
                        $access->edit = $request->Payment_Terms['edit'] ?? null;
                        $access->create = $request->Payment_Terms['create'] ?? null; 
                        $access->update = $request->Payment_Terms['update'] ?? null; 
                        $access->view = $request->Payment_Terms['view'] ?? null; 
                        $access->delete = $request->Payment_Terms['delete'] ?? null; 
                        $access->approve = $request->Payment_Terms['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Products")
                {
                    if ($request->has('Products'))
                    {
                        $access->edit = $request->Products['edit'] ?? null;
                        $access->create = $request->Products['create'] ?? null; 
                        $access->update = $request->Products['update'] ?? null; 
                        $access->view = $request->Products['view'] ?? null; 
                        $access->delete = $request->Products['delete'] ?? null; 
                        $access->approve = $request->Products['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }

                if ($module == "Current Products")
                {
                    if ($request->has('Current_Products'))
                    {
                        $access->edit = $request->Current_Products['edit'] ?? null;
                        $access->create = $request->Current_Products['create'] ?? null; 
                        $access->update = $request->Current_Products['update'] ?? null; 
                        $access->view = $request->Current_Products['view'] ?? null; 
                        $access->delete = $request->Current_Products['delete'] ?? null; 
                        $access->approve = $request->Current_Products['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }

                if ($module == "New Products")
                {
                    if ($request->has('New_Products'))
                    {
                        $access->edit = $request->New_Products['edit'] ?? null;
                        $access->create = $request->New_Products['create'] ?? null; 
                        $access->update = $request->New_Products['update'] ?? null; 
                        $access->view = $request->New_Products['view'] ?? null; 
                        $access->delete = $request->New_Products['delete'] ?? null; 
                        $access->approve = $request->New_Products['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Draft Products")
                {
                    if ($request->has('Draft_Products'))
                    {
                        $access->edit = $request->Draft_Products['edit'] ?? null;
                        $access->create = $request->Draft_Products['create'] ?? null; 
                        $access->update = $request->Draft_Products['update'] ?? null; 
                        $access->view = $request->Draft_Products['view'] ?? null; 
                        $access->delete = $request->Draft_Products['delete'] ?? null; 
                        $access->approve = $request->Draft_Products['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Archived Products")
                {
                    if ($request->has('Archived_Products'))
                    {
                        $access->edit = $request->Archived_Products['edit'] ?? null;
                        $access->create = $request->Archived_Products['create'] ?? null; 
                        $access->update = $request->Archived_Products['update'] ?? null; 
                        $access->view = $request->Archived_Products['view'] ?? null; 
                        $access->delete = $request->Archived_Products['delete'] ?? null; 
                        $access->approve = $request->Archived_Products['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Pricing")
                {
                    if ($request->has('Pricing'))
                    {
                        $access->edit = $request->Pricing['edit'] ?? null;
                        $access->create = $request->Pricing['create'] ?? null; 
                        $access->update = $request->Pricing['update'] ?? null; 
                        $access->view = $request->Pricing['view'] ?? null; 
                        $access->delete = $request->Pricing['delete'] ?? null; 
                        $access->approve = $request->Pricing['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Current Base Price")
                {
                    if ($request->has('Current_Base_Price'))
                    {
                        $access->edit = $request->Current_Base_Price['edit'] ?? null;
                        $access->create = $request->Current_Base_Price['create'] ?? null; 
                        $access->update = $request->Current_Base_Price['update'] ?? null; 
                        $access->view = $request->Current_Base_Price['view'] ?? null; 
                        $access->delete = $request->Current_Base_Price['delete'] ?? null; 
                        $access->approve = $request->Current_Base_Price['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "New Base Price")
                {
                    if ($request->has('New_Base_Price'))
                    {
                        $access->edit = $request->New_Base_Price['edit'] ?? null;
                        $access->create = $request->New_Base_Price['create'] ?? null; 
                        $access->update = $request->New_Base_Price['update'] ?? null; 
                        $access->view = $request->New_Base_Price['view'] ?? null; 
                        $access->delete = $request->New_Base_Price['delete'] ?? null; 
                        $access->approve = $request->New_Base_Price['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Product Setup")
                {
                    if ($request->has('Product_Setup'))
                    {
                        $access->edit = $request->Product_Setup['edit'] ?? null;
                        $access->create = $request->Product_Setup['create'] ?? null; 
                        $access->update = $request->Product_Setup['update'] ?? null; 
                        $access->view = $request->Product_Setup['view'] ?? null; 
                        $access->delete = $request->Product_Setup['delete'] ?? null; 
                        $access->approve = $request->Product_Setup['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Product Application")
                {
                    if ($request->has('Product_Application'))
                    {
                        $access->edit = $request->Product_Application['edit'] ?? null;
                        $access->create = $request->Product_Application['create'] ?? null; 
                        $access->update = $request->Product_Application['update'] ?? null; 
                        $access->view = $request->Product_Application['view'] ?? null; 
                        $access->delete = $request->Product_Application['delete'] ?? null; 
                        $access->approve = $request->Product_Application['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Application Sub Categories")
                {
                    if ($request->has('Application_Sub_Categories'))
                    {
                        $access->edit = $request->Application_Sub_Categories['edit'] ?? null;
                        $access->create = $request->Application_Sub_Categories['create'] ?? null; 
                        $access->update = $request->Application_Sub_Categories['update'] ?? null; 
                        $access->view = $request->Application_Sub_Categories['view'] ?? null; 
                        $access->delete = $request->Application_Sub_Categories['delete'] ?? null; 
                        $access->approve = $request->Application_Sub_Categories['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Raw Materials")
                {
                    if ($request->has('Raw_Materials'))
                    {
                        $access->edit = $request->Raw_Materials['edit'] ?? null;
                        $access->create = $request->Raw_Materials['create'] ?? null; 
                        $access->update = $request->Raw_Materials['update'] ?? null; 
                        $access->view = $request->Raw_Materials['view'] ?? null; 
                        $access->delete = $request->Raw_Materials['delete'] ?? null; 
                        $access->approve = $request->Raw_Materials['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Client Information")
                {
                    if ($request->has('Client_Information'))
                    {
                        $access->edit = $request->Client_Information['edit'] ?? null;
                        $access->create = $request->Client_Information['create'] ?? null; 
                        $access->update = $request->Client_Information['update'] ?? null; 
                        $access->view = $request->Client_Information['view'] ?? null; 
                        $access->delete = $request->Client_Information['delete'] ?? null; 
                        $access->approve = $request->Client_Information['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Current Clients")
                {
                    if ($request->has('Current_Clients'))
                    {
                        $access->edit = $request->Current_Clients['edit'] ?? null;
                        $access->create = $request->Current_Clients['create'] ?? null; 
                        $access->update = $request->Current_Clients['update'] ?? null; 
                        $access->view = $request->Current_Clients['view'] ?? null; 
                        $access->delete = $request->Current_Clients['delete'] ?? null; 
                        $access->approve = $request->Current_Clients['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Prospects Client")
                {
                    if ($request->has('Prospects_Client'))
                    {
                        $access->edit = $request->Prospects_Client['edit'] ?? null;
                        $access->create = $request->Prospects_Client['create'] ?? null; 
                        $access->update = $request->Prospects_Client['update'] ?? null; 
                        $access->view = $request->Prospects_Client['view'] ?? null; 
                        $access->delete = $request->Prospects_Client['delete'] ?? null; 
                        $access->approve = $request->Prospects_Client['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Archived Client")
                {
                    if ($request->has('Archived_Client'))
                    {
                        $access->edit = $request->Archived_Client['edit'] ?? null;
                        $access->create = $request->Archived_Client['create'] ?? null; 
                        $access->update = $request->Archived_Client['update'] ?? null; 
                        $access->view = $request->Archived_Client['view'] ?? null; 
                        $access->delete = $request->Archived_Client['delete'] ?? null; 
                        $access->approve = $request->Archived_Client['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Client Transactions")
                {
                    if ($request->has('Client_Transactions'))
                    {
                        $access->edit = $request->Client_Transactions['edit'] ?? null;
                        $access->create = $request->Client_Transactions['create'] ?? null; 
                        $access->update = $request->Client_Transactions['update'] ?? null; 
                        $access->view = $request->Client_Transactions['view'] ?? null; 
                        $access->delete = $request->Client_Transactions['delete'] ?? null; 
                        $access->approve = $request->Client_Transactions['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Accounting Targeting")
                {
                    if ($request->has('Accounting_Targeting'))
                    {
                        $access->edit = $request->Accounting_Targeting['edit'] ?? null;
                        $access->create = $request->Accounting_Targeting['create'] ?? null; 
                        $access->update = $request->Accounting_Targeting['update'] ?? null; 
                        $access->view = $request->Accounting_Targeting['view'] ?? null; 
                        $access->delete = $request->Accounting_Targeting['delete'] ?? null; 
                        $access->approve = $request->Accounting_Targeting['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Customer Requirement")
                {
                    if ($request->has('Customer_Requirement'))
                    {
                        $access->edit = $request->Customer_Requirement['edit'] ?? null;
                        $access->create = $request->Customer_Requirement['create'] ?? null; 
                        $access->update = $request->Customer_Requirement['update'] ?? null; 
                        $access->view = $request->Customer_Requirement['view'] ?? null; 
                        $access->delete = $request->Customer_Requirement['delete'] ?? null; 
                        $access->approve = $request->Customer_Requirement['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Request for Product Evaluation")
                {
                    if ($request->has('Request_for_Product_Evaluation'))
                    {
                        $access->edit = $request->Request_for_Product_Evaluation['edit'] ?? null;
                        $access->create = $request->Request_for_Product_Evaluation['create'] ?? null; 
                        $access->update = $request->Request_for_Product_Evaluation['update'] ?? null; 
                        $access->view = $request->Request_for_Product_Evaluation['view'] ?? null; 
                        $access->delete = $request->Request_for_Product_Evaluation['delete'] ?? null; 
                        $access->approve = $request->Request_for_Product_Evaluation['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Sample Request Form")
                {
                    if ($request->has('Sample_Request_Form'))
                    {
                        $access->edit = $request->Sample_Request_Form['edit'] ?? null;
                        $access->create = $request->Sample_Request_Form['create'] ?? null; 
                        $access->update = $request->Sample_Request_Form['update'] ?? null; 
                        $access->view = $request->Sample_Request_Form['view'] ?? null; 
                        $access->delete = $request->Sample_Request_Form['delete'] ?? null; 
                        $access->approve = $request->Sample_Request_Form['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }

                if ($module == "Customer Service SRF")
                {
                    if ($request->has('Customer_Service_SRF'))
                    {
                        $access->edit = $request->Customer_Service_SRF['edit'] ?? null;
                        $access->create = $request->Customer_Service_SRF['create'] ?? null; 
                        $access->update = $request->Customer_Service_SRF['update'] ?? null; 
                        $access->view = $request->Customer_Service_SRF['view'] ?? null; 
                        $access->delete = $request->Customer_Service_SRF['delete'] ?? null; 
                        $access->approve = $request->Customer_Service_SRF['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Price Monitoring")
                {
                    if ($request->has('Price_Monitoring'))
                    {
                        $access->edit = $request->Price_Monitoring['edit'] ?? null;
                        $access->create = $request->Price_Monitoring['create'] ?? null; 
                        $access->update = $request->Price_Monitoring['update'] ?? null; 
                        $access->view = $request->Price_Monitoring['view'] ?? null; 
                        $access->delete = $request->Price_Monitoring['delete'] ?? null; 
                        $access->approve = $request->Price_Monitoring['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Client_Transaction_Setup")
                {
                    if ($request->has('Client_Transaction_Setup'))
                    {
                        $access->edit = $request->Client_Transaction_Setup['edit'] ?? null;
                        $access->create = $request->Client_Transaction_Setup['create'] ?? null; 
                        $access->update = $request->Client_Transaction_Setup['update'] ?? null; 
                        $access->view = $request->Client_Transaction_Setup['view'] ?? null; 
                        $access->delete = $request->Client_Transaction_Setup['delete'] ?? null; 
                        $access->approve = $request->Client_Transaction_Setup['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Activities")
                {
                    if ($request->has('Activities'))
                    {
                        $access->edit = $request->Activities['edit'] ?? null;
                        $access->create = $request->Activities['create'] ?? null; 
                        $access->update = $request->Activities['update'] ?? null; 
                        $access->view = $request->Activities['view'] ?? null; 
                        $access->delete = $request->Activities['delete'] ?? null; 
                        $access->approve = $request->Activities['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Report")
                {
                    if ($request->has('Report'))
                    {
                        $access->edit = $request->Report['edit'] ?? null;
                        $access->create = $request->Report['create'] ?? null; 
                        $access->update = $request->Report['update'] ?? null; 
                        $access->view = $request->Report['view'] ?? null; 
                        $access->delete = $request->Report['delete'] ?? null; 
                        $access->approve = $request->Report['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Price Request Summary")
                {
                    if ($request->has('Price_Request_Summary'))
                    {
                        $access->edit = $request->Price_Request_Summary['edit'] ?? null;
                        $access->create = $request->Price_Request_Summary['create'] ?? null; 
                        $access->update = $request->Price_Request_Summary['update'] ?? null; 
                        $access->view = $request->Price_Request_Summary['view'] ?? null; 
                        $access->delete = $request->Price_Request_Summary['delete'] ?? null; 
                        $access->approve = $request->Price_Request_Summary['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Transaction Activity Summary")
                {
                    if ($request->has('Transaction_Activity_Summary'))
                    {
                        $access->edit = $request->Transaction_Activity_Summary['edit'] ?? null;
                        $access->create = $request->Transaction_Activity_Summary['create'] ?? null; 
                        $access->update = $request->Transaction_Activity_Summary['update'] ?? null; 
                        $access->view = $request->Transaction_Activity_Summary['view'] ?? null; 
                        $access->delete = $request->Transaction_Activity_Summary['delete'] ?? null; 
                        $access->approve = $request->Transaction_Activity_Summary['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }

                if ($module == "Accounting")
                {
                    if ($request->has('Accounting'))
                    {
                        $access->edit = $request->Accounting['edit'] ?? null;
                        $access->create = $request->Accounting['create'] ?? null; 
                        $access->update = $request->Accounting['update'] ?? null; 
                        $access->view = $request->Accounting['view'] ?? null; 
                        $access->delete = $request->Accounting['delete'] ?? null; 
                        $access->approve = $request->Accounting['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }

                if ($module == "Currency Exchange Rates")
                {
                    if ($request->has('Currency_Exchange_Rates'))
                    {
                        $access->edit = $request->Currency_Exchange_Rates['edit'] ?? null;
                        $access->create = $request->Currency_Exchange_Rates['create'] ?? null; 
                        $access->update = $request->Currency_Exchange_Rates['update'] ?? null; 
                        $access->view = $request->Currency_Exchange_Rates['view'] ?? null; 
                        $access->delete = $request->Currency_Exchange_Rates['delete'] ?? null; 
                        $access->approve = $request->Currency_Exchange_Rates['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }

                if ($module == "Price Request Fixed Cost")
                {
                    if ($request->has('Price_Request_Fixed_Cost'))
                    {
                        $access->edit = $request->Price_Request_Fixed_Cost['edit'] ?? null;
                        $access->create = $request->Price_Request_Fixed_Cost['create'] ?? null; 
                        $access->update = $request->Price_Request_Fixed_Cost['update'] ?? null; 
                        $access->view = $request->Price_Request_Fixed_Cost['view'] ?? null; 
                        $access->delete = $request->Price_Request_Fixed_Cost['delete'] ?? null; 
                        $access->approve = $request->Price_Request_Fixed_Cost['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }

                if ($module == "Price Request GAE")
                {
                    if ($request->has('Price_Request_GAE'))
                    {
                        $access->edit = $request->Price_Request_GAE['edit'] ?? null;
                        $access->create = $request->Price_Request_GAE['create'] ?? null; 
                        $access->update = $request->Price_Request_GAE['update'] ?? null; 
                        $access->view = $request->Price_Request_GAE['view'] ?? null; 
                        $access->delete = $request->Price_Request_GAE['delete'] ?? null; 
                        $access->approve = $request->Price_Request_GAE['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }

                if ($module == "Supplier Transaction Module")
                {
                    if ($request->has('Supplier_Transaction_Module'))
                    {
                        $access->edit = $request->Supplier_Transaction_Module['edit'] ?? null;
                        $access->create = $request->Supplier_Transaction_Module['create'] ?? null; 
                        $access->update = $request->Supplier_Transaction_Module['update'] ?? null; 
                        $access->view = $request->Supplier_Transaction_Module['view'] ?? null; 
                        $access->delete = $request->Supplier_Transaction_Module['delete'] ?? null; 
                        $access->approve = $request->Supplier_Transaction_Module['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }

                // if ($module == "Supplier Product Evaluation")
                // {
                //     if ($request->has('Supplier_Product_Evaluation'))
                //     {
                //         $access->edit = $request->Supplier_Product_Evaluation['edit'] ?? null;
                //         $access->create = $request->Supplier_Product_Evaluation['create'] ?? null; 
                //         $access->update = $request->Supplier_Product_Evaluation['update'] ?? null; 
                //         $access->view = $request->Supplier_Product_Evaluation['view'] ?? null; 
                //         $access->delete = $request->Supplier_Product_Evaluation['delete'] ?? null; 
                //         $access->approve = $request->Supplier_Product_Evaluation['approve'] ?? null; 
                //     }
                //     else
                //     {
                //         $access->create = null;
                //         $access->edit = null;
                //         $access->update = null; 
                //         $access->view = null; 
                //         $access->delete = null; 
                //         $access->approve = null;
                //     }
                // }

                // if ($module == "Supplier Shipment Evaluation")
                // {
                //     if ($request->has('Supplier_Shipment_Evaluation'))
                //     {
                //         $access->edit = $request->Supplier_Shipment_Evaluation['edit'] ?? null;
                //         $access->create = $request->Supplier_Shipment_Evaluation['create'] ?? null; 
                //         $access->update = $request->Supplier_Shipment_Evaluation['update'] ?? null; 
                //         $access->view = $request->Supplier_Shipment_Evaluation['view'] ?? null; 
                //         $access->delete = $request->Supplier_Shipment_Evaluation['delete'] ?? null; 
                //         $access->approve = $request->Supplier_Shipment_Evaluation['approve'] ?? null; 
                //     }
                //     else
                //     {
                //         $access->create = null;
                //         $access->edit = null;
                //         $access->update = null; 
                //         $access->view = null; 
                //         $access->delete = null; 
                //         $access->approve = null;
                //     }
                // }

                if ($module == "User Setup")
                {
                    if ($request->has('User_Setup'))
                    {
                        $access->edit = $request->User_Setup['edit'] ?? null;
                        $access->create = $request->User_Setup['create'] ?? null; 
                        $access->update = $request->User_Setup['update'] ?? null; 
                        $access->view = $request->User_Setup['view'] ?? null; 
                        $access->delete = $request->User_Setup['delete'] ?? null; 
                        $access->approve = $request->User_Setup['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }

                if ($module == "Categorization")
                {
                    if ($request->has('Categorization'))
                    {
                        $access->edit = $request->Categorization['edit'] ?? null;
                        $access->create = $request->Categorization['create'] ?? null; 
                        $access->update = $request->Categorization['update'] ?? null; 
                        $access->view = $request->Categorization['view'] ?? null; 
                        $access->delete = $request->Categorization['delete'] ?? null; 
                        $access->approve = $request->Categorization['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }

                if ($module == "Project Name")
                {
                    if ($request->has('Project_Name'))
                    {
                        $access->edit = $request->Project_Name['edit'] ?? null;
                        $access->create = $request->Project_Name['create'] ?? null; 
                        $access->update = $request->Project_Name['update'] ?? null; 
                        $access->view = $request->Project_Name['view'] ?? null; 
                        $access->delete = $request->Project_Name['delete'] ?? null; 
                        $access->approve = $request->Project_Name['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Nature of Request")
                {
                    if ($request->has('Nature_of_Request'))
                    {
                        $access->edit = $request->Nature_of_Request['edit'] ?? null;
                        $access->create = $request->Nature_of_Request['create'] ?? null; 
                        $access->update = $request->Nature_of_Request['update'] ?? null; 
                        $access->view = $request->Nature_of_Request['view'] ?? null; 
                        $access->delete = $request->Nature_of_Request['delete'] ?? null; 
                        $access->approve = $request->Nature_of_Request['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "CRR Priority")
                {
                    if ($request->has('CRR_Priority'))
                    {
                        $access->edit = $request->CRR_Priority['edit'] ?? null;
                        $access->create = $request->CRR_Priority['create'] ?? null; 
                        $access->update = $request->CRR_Priority['update'] ?? null; 
                        $access->view = $request->CRR_Priority['view'] ?? null; 
                        $access->delete = $request->CRR_Priority['delete'] ?? null; 
                        $access->approve = $request->CRR_Priority['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Customer Complaints")
                {
                    if ($request->has('Customer_Complaints'))
                    {
                        $access->edit = $request->Customer_Complaints['edit'] ?? null;
                        $access->create = $request->Customer_Complaints['create'] ?? null; 
                        $access->update = $request->Customer_Complaints['update'] ?? null; 
                        $access->view = $request->Customer_Complaints['view'] ?? null; 
                        $access->delete = $request->Customer_Complaints['delete'] ?? null; 
                        $access->approve = $request->Customer_Complaints['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Customer Feedback")
                {
                    if ($request->has('Customer_Feedback'))
                    {
                        $access->edit = $request->Customer_Feedback['edit'] ?? null;
                        $access->create = $request->Customer_Feedback['create'] ?? null; 
                        $access->update = $request->Customer_Feedback['update'] ?? null; 
                        $access->view = $request->Customer_Feedback['view'] ?? null; 
                        $access->delete = $request->Customer_Feedback['delete'] ?? null; 
                        $access->approve = $request->Customer_Feedback['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Issue Category")
                {
                    if ($request->has('Issue_Category'))
                    {
                        $access->edit = $request->Issue_Category['edit'] ?? null;
                        $access->create = $request->Issue_Category['create'] ?? null; 
                        $access->update = $request->Issue_Category['update'] ?? null; 
                        $access->view = $request->Issue_Category['view'] ?? null; 
                        $access->delete = $request->Issue_Category['delete'] ?? null; 
                        $access->approve = $request->Issue_Category['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }

                if ($module == "Concerned Department")
                {
                    if ($request->has('Concerned_Department'))
                    {
                        $access->edit = $request->Concerned_Department['edit'] ?? null;
                        $access->create = $request->Concerned_Department['create'] ?? null; 
                        $access->update = $request->Concerned_Department['update'] ?? null; 
                        $access->view = $request->Concerned_Department['view'] ?? null; 
                        $access->delete = $request->Concerned_Department['delete'] ?? null; 
                        $access->approve = $request->Concerned_Department['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                $access->save();
            }
            else
            {
                $access->department_id = $request->department;
                $access->role_id = $request->role;
                $access->module_name = $module;

                if ($module == "Products")
                {
                    if ($request->has('Products'))
                    {
                        $access->edit = $request->Products['edit'] ?? null;
                        $access->create = $request->Products['create'] ?? null; 
                        $access->update = $request->Products['update'] ?? null; 
                        $access->view = $request->Products['view'] ?? null; 
                        $access->delete = $request->Products['delete'] ?? null; 
                        $access->approve = $request->Products['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }

                if ($module == "Product Management")
                {
                    if ($request->has('Product_Management'))
                    {
                        $access->edit = $request->Product_Management['edit'] ?? null;
                        $access->create = $request->Product_Management['create'] ?? null; 
                        $access->update = $request->Product_Management['update'] ?? null; 
                        $access->view = $request->Product_Management['view'] ?? null; 
                        $access->delete = $request->Product_Management['delete'] ?? null; 
                        $access->approve = $request->Product_Management['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }

                if ($module == "Current Products")
                {
                    if ($request->has('Current_Products'))
                    {
                        $access->edit = $request->Current_Products['edit'] ?? null;
                        $access->create = $request->Current_Products['create'] ?? null; 
                        $access->update = $request->Current_Products['update'] ?? null; 
                        $access->view = $request->Current_Products['view'] ?? null; 
                        $access->delete = $request->Current_Products['delete'] ?? null; 
                        $access->approve = $request->Current_Products['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }

                if ($module == "New Products")
                {
                    if ($request->has('New_Products'))
                    {
                        $access->edit = $request->New_Products['edit'] ?? null;
                        $access->create = $request->New_Products['create'] ?? null; 
                        $access->update = $request->New_Products['update'] ?? null; 
                        $access->view = $request->New_Products['view'] ?? null; 
                        $access->delete = $request->New_Products['delete'] ?? null; 
                        $access->approve = $request->New_Products['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Draft Products")
                {
                    if ($request->has('Draft_Products'))
                    {
                        $access->edit = $request->Draft_Products['edit'] ?? null;
                        $access->create = $request->Draft_Products['create'] ?? null; 
                        $access->update = $request->Draft_Products['update'] ?? null; 
                        $access->view = $request->Draft_Products['view'] ?? null; 
                        $access->delete = $request->Draft_Products['delete'] ?? null; 
                        $access->approve = $request->Draft_Products['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Archived Products")
                {
                    if ($request->has('Archived_Products'))
                    {
                        $access->edit = $request->Archived_Products['edit'] ?? null;
                        $access->create = $request->Archived_Products['create'] ?? null; 
                        $access->update = $request->Archived_Products['update'] ?? null; 
                        $access->view = $request->Archived_Products['view'] ?? null; 
                        $access->delete = $request->Archived_Products['delete'] ?? null; 
                        $access->approve = $request->Archived_Products['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Pricing")
                {
                    if ($request->has('Pricing'))
                    {
                        $access->edit = $request->Pricing['edit'] ?? null;
                        $access->create = $request->Pricing['create'] ?? null; 
                        $access->update = $request->Pricing['update'] ?? null; 
                        $access->view = $request->Pricing['view'] ?? null; 
                        $access->delete = $request->Pricing['delete'] ?? null; 
                        $access->approve = $request->Pricing['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Current Base Price")
                {
                    if ($request->has('Current_Base_Price'))
                    {
                        $access->edit = $request->Current_Base_Price['edit'] ?? null;
                        $access->create = $request->Current_Base_Price['create'] ?? null; 
                        $access->update = $request->Current_Base_Price['update'] ?? null; 
                        $access->view = $request->Current_Base_Price['view'] ?? null; 
                        $access->delete = $request->Current_Base_Price['delete'] ?? null; 
                        $access->approve = $request->Current_Base_Price['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "New Base Price")
                {
                    if ($request->has('New_Base_Price'))
                    {
                        $access->edit = $request->New_Base_Price['edit'] ?? null;
                        $access->create = $request->New_Base_Price['create'] ?? null; 
                        $access->update = $request->New_Base_Price['update'] ?? null; 
                        $access->view = $request->New_Base_Price['view'] ?? null; 
                        $access->delete = $request->New_Base_Price['delete'] ?? null; 
                        $access->approve = $request->New_Base_Price['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Product Setup")
                {
                    if ($request->has('Product_Setup'))
                    {
                        $access->edit = $request->Product_Setup['edit'] ?? null;
                        $access->create = $request->Product_Setup['create'] ?? null; 
                        $access->update = $request->Product_Setup['update'] ?? null; 
                        $access->view = $request->Product_Setup['view'] ?? null; 
                        $access->delete = $request->Product_Setup['delete'] ?? null; 
                        $access->approve = $request->Product_Setup['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Product Application")
                {
                    if ($request->has('Product_Application'))
                    {
                        $access->edit = $request->Product_Application['edit'] ?? null;
                        $access->create = $request->Product_Application['create'] ?? null; 
                        $access->update = $request->Product_Application['update'] ?? null; 
                        $access->view = $request->Product_Application['view'] ?? null; 
                        $access->delete = $request->Product_Application['delete'] ?? null; 
                        $access->approve = $request->Product_Application['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Application Sub Categories")
                {
                    if ($request->has('Application_Sub_Categories'))
                    {
                        $access->edit = $request->Application_Sub_Categories['edit'] ?? null;
                        $access->create = $request->Application_Sub_Categories['create'] ?? null; 
                        $access->update = $request->Application_Sub_Categories['update'] ?? null; 
                        $access->view = $request->Application_Sub_Categories['view'] ?? null; 
                        $access->delete = $request->Application_Sub_Categories['delete'] ?? null; 
                        $access->approve = $request->Application_Sub_Categories['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Raw Materials")
                {
                    if ($request->has('Raw_Materials'))
                    {
                        $access->edit = $request->Raw_Materials['edit'] ?? null;
                        $access->create = $request->Raw_Materials['create'] ?? null; 
                        $access->update = $request->Raw_Materials['update'] ?? null; 
                        $access->view = $request->Raw_Materials['view'] ?? null; 
                        $access->delete = $request->Raw_Materials['delete'] ?? null; 
                        $access->approve = $request->Raw_Materials['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Client Information")
                {
                    if ($request->has('Client_Information'))
                    {
                        $access->edit = $request->Client_Information['edit'] ?? null;
                        $access->create = $request->Client_Information['create'] ?? null; 
                        $access->update = $request->Client_Information['update'] ?? null; 
                        $access->view = $request->Client_Information['view'] ?? null; 
                        $access->delete = $request->Client_Information['delete'] ?? null; 
                        $access->approve = $request->Client_Information['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Current Clients")
                {
                    if ($request->has('Current_Clients'))
                    {
                        $access->edit = $request->Current_Clients['edit'] ?? null;
                        $access->create = $request->Current_Clients['create'] ?? null; 
                        $access->update = $request->Current_Clients['update'] ?? null; 
                        $access->view = $request->Current_Clients['view'] ?? null; 
                        $access->delete = $request->Current_Clients['delete'] ?? null; 
                        $access->approve = $request->Current_Clients['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Prospects Client")
                {
                    if ($request->has('Prospects_Client'))
                    {
                        $access->edit = $request->Prospects_Client['edit'] ?? null;
                        $access->create = $request->Prospects_Client['create'] ?? null; 
                        $access->update = $request->Prospects_Client['update'] ?? null; 
                        $access->view = $request->Prospects_Client['view'] ?? null; 
                        $access->delete = $request->Prospects_Client['delete'] ?? null; 
                        $access->approve = $request->Prospects_Client['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Archived Client")
                {
                    if ($request->has('Archived_Client'))
                    {
                        $access->edit = $request->Archived_Client['edit'] ?? null;
                        $access->create = $request->Archived_Client['create'] ?? null; 
                        $access->update = $request->Archived_Client['update'] ?? null; 
                        $access->view = $request->Archived_Client['view'] ?? null; 
                        $access->delete = $request->Archived_Client['delete'] ?? null; 
                        $access->approve = $request->Archived_Client['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Client Transactions")
                {
                    if ($request->has('Client_Transactions'))
                    {
                        $access->edit = $request->Client_Transactions['edit'] ?? null;
                        $access->create = $request->Client_Transactions['create'] ?? null; 
                        $access->update = $request->Client_Transactions['update'] ?? null; 
                        $access->view = $request->Client_Transactions['view'] ?? null; 
                        $access->delete = $request->Client_Transactions['delete'] ?? null; 
                        $access->approve = $request->Client_Transactions['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Accounting Targeting")
                {
                    if ($request->has('Accounting_Targeting'))
                    {
                        $access->edit = $request->Accounting_Targeting['edit'] ?? null;
                        $access->create = $request->Accounting_Targeting['create'] ?? null; 
                        $access->update = $request->Accounting_Targeting['update'] ?? null; 
                        $access->view = $request->Accounting_Targeting['view'] ?? null; 
                        $access->delete = $request->Accounting_Targeting['delete'] ?? null; 
                        $access->approve = $request->Accounting_Targeting['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Customer Requirement")
                {
                    if ($request->has('Customer_Requirement'))
                    {
                        $access->edit = $request->Customer_Requirement['edit'] ?? null;
                        $access->create = $request->Customer_Requirement['create'] ?? null; 
                        $access->update = $request->Customer_Requirement['update'] ?? null; 
                        $access->view = $request->Customer_Requirement['view'] ?? null; 
                        $access->delete = $request->Customer_Requirement['delete'] ?? null; 
                        $access->approve = $request->Customer_Requirement['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Request for Product Evaluation")
                {
                    if ($request->has('Request_for_Product_Evaluation'))
                    {
                        $access->edit = $request->Request_for_Product_Evaluation['edit'] ?? null;
                        $access->create = $request->Request_for_Product_Evaluation['create'] ?? null; 
                        $access->update = $request->Request_for_Product_Evaluation['update'] ?? null; 
                        $access->view = $request->Request_for_Product_Evaluation['view'] ?? null; 
                        $access->delete = $request->Request_for_Product_Evaluation['delete'] ?? null; 
                        $access->approve = $request->Request_for_Product_Evaluation['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Sample Request Form")
                {
                    if ($request->has('Sample_Request_Form'))
                    {
                        $access->edit = $request->Sample_Request_Form['edit'] ?? null;
                        $access->create = $request->Sample_Request_Form['create'] ?? null; 
                        $access->update = $request->Sample_Request_Form['update'] ?? null; 
                        $access->view = $request->Sample_Request_Form['view'] ?? null; 
                        $access->delete = $request->Sample_Request_Form['delete'] ?? null; 
                        $access->approve = $request->Sample_Request_Form['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                if ($module == "Customer Service SRF")
                {
                    if ($request->has('Customer_Service_SRF'))
                    {
                        $access->edit = $request->Customer_Service_SRF['edit'] ?? null;
                        $access->create = $request->Customer_Service_SRF['create'] ?? null; 
                        $access->update = $request->Customer_Service_SRF['update'] ?? null; 
                        $access->view = $request->Customer_Service_SRF['view'] ?? null; 
                        $access->delete = $request->Customer_Service_SRF['delete'] ?? null; 
                        $access->approve = $request->Customer_Service_SRF['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Price Monitoring")
                {
                    if ($request->has('Price_Monitoring'))
                    {
                        $access->edit = $request->Price_Monitoring['edit'] ?? null;
                        $access->create = $request->Price_Monitoring['create'] ?? null; 
                        $access->update = $request->Price_Monitoring['update'] ?? null; 
                        $access->view = $request->Price_Monitoring['view'] ?? null; 
                        $access->delete = $request->Price_Monitoring['delete'] ?? null; 
                        $access->approve = $request->Price_Monitoring['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Client Transaction Setup")
                {
                    if ($request->has('Client_Transaction_Setup'))
                    {
                        $access->edit = $request->Client_Transaction_Setup['edit'] ?? null;
                        $access->create = $request->Client_Transaction_Setup['create'] ?? null; 
                        $access->update = $request->Client_Transaction_Setup['update'] ?? null; 
                        $access->view = $request->Client_Transaction_Setup['view'] ?? null; 
                        $access->delete = $request->Client_Transaction_Setup['delete'] ?? null; 
                        $access->approve = $request->Client_Transaction_Setup['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Activities")
                {
                    if ($request->has('Activities'))
                    {
                        $access->edit = $request->Activities['edit'] ?? null;
                        $access->create = $request->Activities['create'] ?? null; 
                        $access->update = $request->Activities['update'] ?? null; 
                        $access->view = $request->Activities['view'] ?? null; 
                        $access->delete = $request->Activities['delete'] ?? null; 
                        $access->approve = $request->Activities['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Report")
                {
                    if ($request->has('Report'))
                    {
                        $access->edit = $request->Report['edit'] ?? null;
                        $access->create = $request->Report['create'] ?? null; 
                        $access->update = $request->Report['update'] ?? null; 
                        $access->view = $request->Report['view'] ?? null; 
                        $access->delete = $request->Report['delete'] ?? null; 
                        $access->approve = $request->Report['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Price Request Summary")
                {
                    if ($request->has('Price_Request_Summary'))
                    {
                        $access->edit = $request->Price_Request_Summary['edit'] ?? null;
                        $access->create = $request->Price_Request_Summary['create'] ?? null; 
                        $access->update = $request->Price_Request_Summary['update'] ?? null; 
                        $access->view = $request->Price_Request_Summary['view'] ?? null; 
                        $access->delete = $request->Price_Request_Summary['delete'] ?? null; 
                        $access->approve = $request->Price_Request_Summary['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Transaction Activity Summary")
                {
                    if ($request->has('Transaction_Activity_Summary'))
                    {
                        $access->edit = $request->Transaction_Activity_Summary['edit'] ?? null;
                        $access->create = $request->Transaction_Activity_Summary['create'] ?? null; 
                        $access->update = $request->Transaction_Activity_Summary['update'] ?? null; 
                        $access->view = $request->Transaction_Activity_Summary['view'] ?? null; 
                        $access->delete = $request->Transaction_Activity_Summary['delete'] ?? null; 
                        $access->approve = $request->Transaction_Activity_Summary['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }

                if ($module == "Accounting")
                {
                    if ($request->has('Accounting'))
                    {
                        $access->edit = $request->Accounting['edit'] ?? null;
                        $access->create = $request->Accounting['create'] ?? null; 
                        $access->update = $request->Accounting['update'] ?? null; 
                        $access->view = $request->Accounting['view'] ?? null; 
                        $access->delete = $request->Accounting['delete'] ?? null; 
                        $access->approve = $request->Accounting['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }

                if ($module == "Currency Exchange Rates")
                {
                    if ($request->has('Currency_Exchange_Rates'))
                    {
                        $access->edit = $request->Currency_Exchange_Rates['edit'] ?? null;
                        $access->create = $request->Currency_Exchange_Rates['create'] ?? null; 
                        $access->update = $request->Currency_Exchange_Rates['update'] ?? null; 
                        $access->view = $request->Currency_Exchange_Rates['view'] ?? null; 
                        $access->delete = $request->Currency_Exchange_Rates['delete'] ?? null; 
                        $access->approve = $request->Currency_Exchange_Rates['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }

                if ($module == "Price Request Fixed Cost")
                {
                    if ($request->has('Price_Request_Fixed_Cost'))
                    {
                        $access->edit = $request->Price_Request_Fixed_Cost['edit'] ?? null;
                        $access->create = $request->Price_Request_Fixed_Cost['create'] ?? null; 
                        $access->update = $request->Price_Request_Fixed_Cost['update'] ?? null; 
                        $access->view = $request->Price_Request_Fixed_Cost['view'] ?? null; 
                        $access->delete = $request->Price_Request_Fixed_Cost['delete'] ?? null; 
                        $access->approve = $request->Price_Request_Fixed_Cost['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }

                if ($module == "Price Request GAE")
                {
                    if ($request->has('Price_Request_GAE'))
                    {
                        $access->edit = $request->Price_Request_GAE['edit'] ?? null;
                        $access->create = $request->Price_Request_GAE['create'] ?? null; 
                        $access->update = $request->Price_Request_GAE['update'] ?? null; 
                        $access->view = $request->Price_Request_GAE['view'] ?? null; 
                        $access->delete = $request->Price_Request_GAE['delete'] ?? null; 
                        $access->approve = $request->Price_Request_GAE['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }

                // if ($module == "Supplier Transaction Module")
                // {
                //     if ($request->has('Supplier_Transaction_Module'))
                //     {
                //         $access->edit = $request->Supplier_Transaction_Module['edit'] ?? null;
                //         $access->create = $request->Supplier_Transaction_Module['create'] ?? null; 
                //         $access->update = $request->Supplier_Transaction_Module['update'] ?? null; 
                //         $access->view = $request->Supplier_Transaction_Module['view'] ?? null; 
                //         $access->delete = $request->Supplier_Transaction_Module['delete'] ?? null; 
                //         $access->approve = $request->Supplier_Transaction_Module['approve'] ?? null; 
                //     }
                //     else
                //     {
                //         $access->create = null;
                //         $access->edit = null;
                //         $access->update = null; 
                //         $access->view = null; 
                //         $access->delete = null; 
                //         $access->approve = null;
                //     }
                // }

                if ($module == "Supplier Information")
                {
                    if ($request->has('Supplier_Information'))
                    {
                        $access->edit = $request->Supplier_Information['edit'] ?? null;
                        $access->create = $request->Supplier_Information['create'] ?? null; 
                        $access->update = $request->Supplier_Information['update'] ?? null; 
                        $access->view = $request->Supplier_Information['view'] ?? null; 
                        $access->delete = $request->Supplier_Information['delete'] ?? null; 
                        $access->approve = $request->Supplier_Information['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }

                if ($module == "Supplier Product Evaluation")
                {
                    if ($request->has('Supplier_Product_Evaluation'))
                    {
                        $access->edit = $request->Supplier_Product_Evaluation['edit'] ?? null;
                        $access->create = $request->Supplier_Product_Evaluation['create'] ?? null; 
                        $access->update = $request->Supplier_Product_Evaluation['update'] ?? null; 
                        $access->view = $request->Supplier_Product_Evaluation['view'] ?? null; 
                        $access->delete = $request->Supplier_Product_Evaluation['delete'] ?? null; 
                        $access->approve = $request->Supplier_Product_Evaluation['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }

                if ($module == "Supplier Shipment Evaluation")
                {
                    if ($request->has('Supplier_Shipment_Evaluation'))
                    {
                        $access->edit = $request->Supplier_Shipment_Evaluation['edit'] ?? null;
                        $access->create = $request->Supplier_Shipment_Evaluation['create'] ?? null; 
                        $access->update = $request->Supplier_Shipment_Evaluation['update'] ?? null; 
                        $access->view = $request->Supplier_Shipment_Evaluation['view'] ?? null; 
                        $access->delete = $request->Supplier_Shipment_Evaluation['delete'] ?? null; 
                        $access->approve = $request->Supplier_Shipment_Evaluation['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }

                if ($module == "Categorization")
                {
                    if ($request->has('Categorization'))
                    {
                        $access->edit = $request->Categorization['edit'] ?? null;
                        $access->create = $request->Categorization['create'] ?? null; 
                        $access->update = $request->Categorization['update'] ?? null; 
                        $access->view = $request->Categorization['view'] ?? null; 
                        $access->delete = $request->Categorization['delete'] ?? null; 
                        $access->approve = $request->Categorization['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }

                if ($module == "Project Name")
                {
                    if ($request->has('Project_Name'))
                    {
                        $access->edit = $request->Project_Name['edit'] ?? null;
                        $access->create = $request->Project_Name['create'] ?? null; 
                        $access->update = $request->Project_Name['update'] ?? null; 
                        $access->view = $request->Project_Name['view'] ?? null; 
                        $access->delete = $request->Project_Name['delete'] ?? null; 
                        $access->approve = $request->Project_Name['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Nature of Request")
                {
                    if ($request->has('Nature_of_Request'))
                    {
                        $access->edit = $request->Nature_of_Request['edit'] ?? null;
                        $access->create = $request->Nature_of_Request['create'] ?? null; 
                        $access->update = $request->Nature_of_Request['update'] ?? null; 
                        $access->view = $request->Nature_of_Request['view'] ?? null; 
                        $access->delete = $request->Nature_of_Request['delete'] ?? null; 
                        $access->approve = $request->Nature_of_Request['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "CRR Priority")
                {
                    if ($request->has('CRR_Priority'))
                    {
                        $access->edit = $request->CRR_Priority['edit'] ?? null;
                        $access->create = $request->CRR_Priority['create'] ?? null; 
                        $access->update = $request->CRR_Priority['update'] ?? null; 
                        $access->view = $request->CRR_Priority['view'] ?? null; 
                        $access->delete = $request->CRR_Priority['delete'] ?? null; 
                        $access->approve = $request->CRR_Priority['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Customer Complaints")
                {
                    if ($request->has('Customer_Complaints'))
                    {
                        $access->edit = $request->Customer_Complaints['edit'] ?? null;
                        $access->create = $request->Customer_Complaints['create'] ?? null; 
                        $access->update = $request->Customer_Complaints['update'] ?? null; 
                        $access->view = $request->Customer_Complaints['view'] ?? null; 
                        $access->delete = $request->Customer_Complaints['delete'] ?? null; 
                        $access->approve = $request->Customer_Complaints['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Customer Feedback")
                {
                    if ($request->has('Customer_Feedback'))
                    {
                        $access->edit = $request->Customer_Feedback['edit'] ?? null;
                        $access->create = $request->Customer_Feedback['create'] ?? null; 
                        $access->update = $request->Customer_Feedback['update'] ?? null; 
                        $access->view = $request->Customer_Feedback['view'] ?? null; 
                        $access->delete = $request->Customer_Feedback['delete'] ?? null; 
                        $access->approve = $request->Customer_Feedback['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }
                
                if ($module == "Issue Category")
                {
                    if ($request->has('Issue_Category'))
                    {
                        $access->edit = $request->Issue_Category['edit'] ?? null;
                        $access->create = $request->Issue_Category['create'] ?? null; 
                        $access->update = $request->Issue_Category['update'] ?? null; 
                        $access->view = $request->Issue_Category['view'] ?? null; 
                        $access->delete = $request->Issue_Category['delete'] ?? null; 
                        $access->approve = $request->Issue_Category['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }

                if ($module == "Concerned Department")
                {
                    if ($request->has('Concerned_Department'))
                    {
                        $access->edit = $request->Concerned_Department['edit'] ?? null;
                        $access->create = $request->Concerned_Department['create'] ?? null; 
                        $access->update = $request->Concerned_Department['update'] ?? null; 
                        $access->view = $request->Concerned_Department['view'] ?? null; 
                        $access->delete = $request->Concerned_Department['delete'] ?? null; 
                        $access->approve = $request->Concerned_Department['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }

                if ($module == "Payment Terms")
                {
                    if ($request->has('Payment_Terms'))
                    {
                        $access->edit = $request->Payment_Terms['edit'] ?? null;
                        $access->create = $request->Payment_Terms['create'] ?? null; 
                        $access->update = $request->Payment_Terms['update'] ?? null; 
                        $access->view = $request->Payment_Terms['view'] ?? null; 
                        $access->delete = $request->Payment_Terms['delete'] ?? null; 
                        $access->approve = $request->Payment_Terms['approve'] ?? null; 
                    }
                    else
                    {
                        $access->create = null;
                        $access->edit = null;
                        $access->update = null; 
                        $access->view = null; 
                        $access->delete = null; 
                        $access->approve = null;
                    }
                }

                
                $access->save();
            }
        }

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back();
    }

    public function module()
    {
        return [
            'Products',
            // 'Product Management',
            'Current Products',
            'New Products',
            'Draft Products',
            'Archived Products',
            'Pricing',
            'Current Base Price',
            'New Base Price',
            'Product Setup',
            'Product Application',
            'Application Sub Categories',
            'Raw Materials',
            // 'Client Information',
            'Current Clients',
            'Prospects Client',
            'Archived Client',
            // 'Client Transactions',
            'Accounting Targeting',
            'Customer Requirement',
            'Request for Product Evaluation',
            'Sample Request Form',
            'Customer Service SRF',
            'Price Monitoring',
            // 'Client Transaction Setup',
            'Categorization',
            'Project Name',
            'Nature of Request',
            'CRR Priority',
            // 'Service Management',
            'Customer Complaints',
            'Customer Feedback',
            // 'Service Management Setup',
            'Issue Category',
            'Concerned Department',
            'Activities',
            'Report',
            'Price Request Summary',
            'Transaction Activity Summary',
            'Accounting',
            'Currency Exchange Rates',
            'Price Request Fixed Cost',
            'Price Request GAE',
            // 'Supplier Transaction Module',
            'Supplier Information',
            'Supplier Product Evaluation',
            'Supplier Shipment Evaluation',
            'Payment Terms'
            // 'User Setup'
        ];
    }

    public function editRole($id)
    {
        $role = Role::findOrFail($id);

        return array(
            'department' => $role->department_id,
            'name' => $role->name,
            'description' => $role->description,
        );
    }
    public function deactivate($id)
    {
        $role = Role::findOrFail($id);
        $role->status = "Inactive";
        $role->save();

        Alert::success('Successfully Deactivated')->persistent('Dismiss');
        return back();
    }
    public function activate($id)
    {
        $role = Role::findOrFail($id);
        $role->status = "Active";
        $role->save();

        Alert::success('Successfully Activated')->persistent('Dismiss');
        return back();
    }
}

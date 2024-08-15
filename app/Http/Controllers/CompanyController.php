<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class CompanyController extends Controller
{
    // List
    public function index(Request $request)
    {   
        $company = Company::latest()
            ->when($request->search, function($query)use($request){
                $query->where('code', 'LIKE', '%'.$request->search.'%')
                ->orWhere('name', 'LIKE', '%'.$request->search.'%')
                ->orWhere('description', 'LIKE', '%'.$request->search.'%');
            })
            ->paginate($request->entries ?? 10);

        return view('companies.index',
            array(
                'company' => $company,
                'search' => $request->search,
                'entries' => $request->entries
            )
        ); 
    }
    // Create
    public function store(Request $request) 
    {
        $rules = [
            'code' => 'unique:companies,code',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
        {
            return response()->json(['error' => $validator->errors()->all(), 'status' => 0]);
        }
        else
        {
            $company = new Company;
            $company->code = $request->code;
            $company->name = $request->name;
            $company->description = $request->description;
            $company->status = "Active";
            $company->save();

            return response()->json(['message' => 'Successfully Saved', 'status' => 1]);
        }
    }
    // Edit
    public function edit($id)
    {
        $company = Company::findOrFail($id);

        return array(
            'name' => $company->name,
            'description' => $company->description,
            'code' => $company->code
        );
    }
    // update
    public function update(Request $request, $id)
    {
        $rules = [
            'code' => 'unique:companies,code,' . $id,
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
        {
            return response()->json(['error' => $validator->errors()->all(), 'status' => 0]);
        }
        else
        {
            $company = Company::findOrFail($id);
            $company->code = $request->code;
            $company->name = $request->name;
            $company->description = $request->description;
            $company->save();

            return response()->json(['message' => 'Successfully Saved', 'status' => 1]);
        }
    }
    // delete
    public function delete($id)
    {
        $data = Company::findOrFail($id);
        $data->delete();
    }

    public function activate($id)
    {
        $company = Company::findOrFail($id);
        $company->status = "Active";
        $company->save();

        Alert::success('Successfully Activated')->persistent('Dismiss');
        return back();
    }

    public function deactivate($id)
    {
        $company = Company::findOrFail($id);
        $company->status = "Inactive";
        $company->save();

        Alert::success('Successfully Deactivated')->persistent('Dismiss');
        return back();
    }
}

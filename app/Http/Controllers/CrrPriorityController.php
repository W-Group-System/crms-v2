<?php

namespace App\Http\Controllers;
use App\CrrPriority;
use Validator;
use Illuminate\Http\Request;

class CrrPriorityController extends Controller
{
    // List
    public function index(Request $request)
    {   
        // if(request()->ajax())
        // {
        //     return datatables()->of(CrrPriority::query())
        //             ->addColumn('action', function($data){
        //                 $buttons = '<button type="button" name="edit" id="'.$data->id.'" class="edit btn btn-primary">Edit</button>';
        //                 $buttons .= '&nbsp;&nbsp;';
        //                 $buttons .= '<button type="button" name="delete" id="'.$data->id.'" class="delete btn btn-danger">Delete</button>';
        //                 return $buttons;
        //             })
        //             ->rawColumns(['action'])
        //             ->make(true);
        // }
        $search = $request->input('search');
        $crrPriorities = CrrPriority::where(function ($query) use ($search) {
            $query->where('Name', 'LIKE', '%' . $search . '%')
                ->orWhere('Description', 'LIKE', '%' . $search . '%')        
                ->orWhere('Days', 'LIKE', '%' . $search . '%');        
        })
        ->orderBy('id', 'desc')->paginate(25);
        return view('crr_priorities.index',  compact('crrPriorities', 'search')); 
    }

    // Store
    public function store(Request $request) 
    {
        $rules = array(
            'Name'          =>  'required',
            'Description'   =>  'required',
            'Days'          =>  'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'Name'          =>  $request->Name,
            'Description'   =>  $request->Description,
            'Days'          =>  $request->Days    
        );

        CrrPriority::create($form_data);

        return redirect()->back()->with('success', 'Data Added Successfully.');
    }

    // Edit
    public function edit($id)
    {
        if(request()->ajax())
        {
            $data = CrrPriority::findOrFail($id);
            return response()->json(['data' => $data]);
        }
    }

    // Update
    public function update(Request $request, $id)
    {
        $rules = array(
            'Name'          =>  'required',
            'Description'   =>  'required',
            'Days'          =>  'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'Name'          =>  $request->Name,
            'Description'   =>  $request->Description,
            'Days'          =>  $request->Days
        );

        CrrPriority::whereId($id)->update($form_data);

        return redirect()->back()->with('success', 'CRR Priority updated successfully.');
    }

    // Delete
    public function delete($id)
    {
        $data = CrrPriority::findOrFail($id);
        $data->delete();
    }
}

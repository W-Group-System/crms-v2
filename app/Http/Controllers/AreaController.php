<?php

namespace App\Http\Controllers;
use App\Area;
use App\Region;
use Validator;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class AreaController extends Controller
{
    // List
    public function index(Request $request)
    {
        $areas = Area::with('region')->when($request->search, function($query)use($request) {
                if (strtolower($request->search) == 'international')
                {
                    $query->where('Type', 2);
                }
                elseif(strtolower($request->search) == "local")
                {
                    $query->where('Type', 1);
                }
                else
                {
                    $query->where(function($q)use($request) {
                        $q->where('Name', 'LIKE', '%'.$request->search.'%')
                            ->orWhere('Description', 'LIKE', '%'.$request->search.'%')
                            ->orWhereHas('region', function($region)use($request) {
                                $region->where('Name', 'LIKE', "%".$request->search."%");
                            });
                    });
                }
            })
            ->orderBy('id', 'desc')
            ->paginate($request->entries ?? 10);

        $regions = Region::all();
        $entries = $request->entries;
        $search = $request->search;

        return view('areas.index', compact('areas', 'regions', 'entries', 'search'));
    }

    // Store
    public function store(Request $request) 
    {
        $rules = array(
            'Type'          =>  'required',
            'RegionId'        =>  'required',
            'Name'          =>  'required',
            'Description'   =>  'required'
        );


        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all(), 'status' => 0]);
        }

        $form_data = array(
            'Type'          =>  $request->Type,
            'RegionId'      =>  $request->RegionId,
            'Name'          =>  $request->Name,
            'Description'   =>  $request->Description
        );

        Area::create($form_data);

        return response()->json(['success' => 'Data Added Successfully.', 'status' => 1]);
    }

    // Edit
    public function edit($id)
    {
        $data = Area::findOrFail($id);
        return response()->json(['data' => $data]);
    }

    // Update
    public function update(Request $request, $id)
    {
        $rules = array(
            'Type'          =>  'required',
            // 'Region'        =>  'required',
            'Name'          =>  'required',
            'Description'   =>  'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'Type'          =>  $request->Type,
            'RegionId'      =>  $request->RegionId,
            'Name'          =>  $request->Name,
            'Description'   =>  $request->Description
        );

        Area::whereId($id)->update($form_data);

        return response()->json(['success' => 'Data is Successfully Updated.']);
    }

    // Delete
    public function delete($id)
    {
        $data = Area::findOrFail($id);
        $data->delete();

        Alert::success('Successfully Deleted')->persistent('Dismiss');
        return back();
    }
}
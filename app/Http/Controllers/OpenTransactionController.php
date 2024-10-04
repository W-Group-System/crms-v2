<?php

namespace App\Http\Controllers;

use App\CustomerRequirement;
use App\RequestProductEvaluation;
use App\SampleRequest;
use App\SampleRequestProduct;
use Illuminate\Http\Request;

class OpenTransactionController extends Controller
{
    public function crr(Request $request)
    {
        $status = $request->query('status');
        $search = $request->get('search', '');
        $entries = $request->get('entries', '');

        $customer_requirement = CustomerRequirement::with('client', 'product_application', 'progressStatus', 'crrPersonnel')
            ->where(function($q)use($search) {
                if ($search != null)
                {
                    $q->where('CrrNumber', 'LIKE','%'.$search.'%')
                        ->orWhere('DateCreated', 'LIKE','%'.$search.'%')
                        ->orWhere('DueDate', 'LIKE','%'.$search.'%')
                        ->orWhereHas('client', function($query)use($search){
                            $query->where('Name', $search);
                        })
                        ->orWhereHas('product_application', function($query)use($search){
                            $query->where('Name', $search);
                        })
                        ->orWhereHas('crrPersonnel', function($query)use($search){
                            $query->whereHas('crrPersonnelByUserId', function($q)use($search) {
                                $q->where('full_name', 'LIKE', "%".$search."%");
                            })
                            ->orWhereHas('crrPersonnelById', function($q)use($search) {
                                $q->where('full_name', 'LIKE', "%".$search."%");
                            });
                        })
                        ->orWhere('DateCreated', 'LIKE','%'.$search.'%')
                        ->orWhere('DateCreated', 'LIKE','%'.$search.'%');
                }
            })
            ->where('status', $status)
            ->orderBy('id', 'desc')
            ->paginate($entries ?? 10);

        return view('dashboard.crr_transaction',
            array(
                'customer_requirement' => $customer_requirement,
                'status' => $status,
                'search' => $search,
                'entries' => $entries
            )
        );
    }

    public function rpe(Request $request)
    {
        $status = $request->query('status');
        $search = $request->get('search', '');
        $entries = $request->get('entries', '');

        $request_product_evaluation = RequestProductEvaluation::with('client', 'product_application', 'progressStatus', 'rpePersonnel')
            ->where(function($q)use($search) {
                if ($search != null)
                {
                    $q->where('RpeNumber', 'LIKE','%'.$search.'%')
                        ->orWhere('DateCreated', 'LIKE','%'.$search.'%')
                        ->orWhere('DueDate', 'LIKE','%'.$search.'%')
                        ->orWhereHas('client', function($query)use($search){
                            $query->where('Name', $search);
                        })
                        ->orWhereHas('product_application', function($query)use($search){
                            $query->where('Name', $search);
                        })
                        ->orWhereHas('rpePersonnel', function($query)use($search){
                            $query->whereHas('assignedPersonnel', function($q)use($search) {
                                $q->where('full_name', 'LIKE', "%".$search."%");
                            })
                            ->orWhereHas('userId', function($q)use($search) {
                                $q->where('full_name', 'LIKE', "%".$search."%");
                            });
                        })
                        ->orWhere('DateCreated', 'LIKE','%'.$search.'%')
                        ->orWhere('DateCreated', 'LIKE','%'.$search.'%');
                }
            })
            ->where('status', $status)
            ->orderBy('id', 'desc')
            ->paginate($entries ?? 10);
        
        return view('dashboard.rpe_transaction',
            array(
                'request_product_evaluation' => $request_product_evaluation,
                'status' => $status,
                'search' => $search,
                'entries' => $entries
            )
        );
    }

    public function srf(Request $request)
    {
        $status = $request->query('status');
        $search = $request->get('search', '');
        $entries = $request->get('entries', '');
        
        $sample_request_product = SampleRequest::with('client', 'productApplicationsId', 'progressStatus', 'srfPersonnel')
            ->where(function($q)use($search) {
                if ($search != null)
                {
                    $q->where('SrfNumber', 'LIKE','%'.$search.'%')
                        ->orWhere('DateCreated', 'LIKE','%'.$search.'%')
                        ->orWhere('DueDate', 'LIKE','%'.$search.'%')
                        ->orWhereHas('client', function($query)use($search){
                            $query->where('Name', $search);
                        })
                        ->orWhereHas('productApplicationsId', function($query)use($search){
                            $query->where('Name', $search);
                        })
                        ->orWhereHas('srfPersonnel', function($query)use($search){
                            $query->whereHas('assignedPersonnel', function($q)use($search) {
                                $q->where('full_name', 'LIKE', "%".$search."%");
                            })
                            ->orWhereHas('userId', function($q)use($search) {
                                $q->where('full_name', 'LIKE', "%".$search."%");
                            });
                        })
                        ->orWhere('DateCreated', 'LIKE','%'.$search.'%')
                        ->orWhere('DateCreated', 'LIKE','%'.$search.'%');
                }
            })
            ->where('status', $status)
            ->orderBy('id', 'desc')
            ->paginate($entries ?? 10);
        
        return view('dashboard.srf_transaction',
            array(
                'sample_request_product' => $sample_request_product,
                'status' => $status,
                'search' => $search,
                'entries' => $entries
            )
        );
    }
}

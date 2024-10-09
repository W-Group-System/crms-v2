<?php

namespace App\Http\Controllers;

use App\Audit;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $audits = Audit::where(function($q)use($search){
                $q->whereHas('user', function($u)use($search) {
                    $u->where('full_name', 'LIKE', '%'.$search.'%');
                })
                ->orWhere('created_at', $search);
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('audits.audits', compact('audits', 'search'));
    }
}

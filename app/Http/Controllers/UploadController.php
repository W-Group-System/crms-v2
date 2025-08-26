<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function uploadTemp(Request $request)
    {
        if ($request->hasFile('file')) {   // ✅ must be 'file'
            $file = $request->file('file');
            $fileName = uniqid() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('cs_files', $fileName, 'public');

            return response()->json([
                'id' => $fileName,
                'path' => $path
            ]);
        }
        return response()->json(['error' => 'No file uploaded'], 400);
    }

    public function uploadRevert(Request $request)
    {
        $fileName = $request->getContent(); // FilePond sends the id as raw text
        $path = 'temp/' . $fileName;

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
            return response('', 200);
        }

        return response('', 404);
    }

}

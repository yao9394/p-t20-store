<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function index(Request $request)
    {
        return response()->json($request->user()->files);
    }

    public function download(Request $request, $id)
    {
        $file = File::find($id)->firstOrFail();
        if ($file->user->id != $request->user()->id) {
            return response()->json(['message' => 'You are not the owner of this file!']);
        }

        $path = storage_path().'/'.'app'.'/'.$file->file_name;
        if (file_exists($path)) {
            return response()->download($path);
        }
    }
}

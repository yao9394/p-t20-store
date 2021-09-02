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
        $file = File::where('id', $id)->firstOrFail();
        if ($file->user->id != $request->user()->id) {
            return response()->json(['message' => 'You are not the owner of this file!']);
        }

        $assetPath = Storage::disk('s3')->url('csv/'.$file->file_name);
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=" . basename($assetPath));
        header("Content-Type: text/csv");

        $dfile = Storage::disk('s3')->get('csv/'.$file->file_name);

        $headers = [
            'Content-Type' => 'text/csv', 
            'Content-Description' => 'File Transfer',
            'Content-Disposition' => "attachment; filename={$file->file_name}",
            'filename'=> $file->file_name
        ];
        
        return response($dfile, 200, $headers);
    }
}

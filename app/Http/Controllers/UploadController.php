<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpeg,png,jpg,pdf|max:10240', // 10MB
        ]);

        $file = $request->file('file');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

        // Store on 'do' disk
        $path = $request->file('file')->storePubliclyAs(
            'uploads',
            $filename,
            'do'
        );

        // Generate URL
        // If the 'url' config is set correctly, this should work. 
        // If not, we might need to manually construct it if Storage::url() returns something relative or wrong.
        $url = Storage::disk('do')->url($path);

        return response()->json(['url' => $url]);
    }
}

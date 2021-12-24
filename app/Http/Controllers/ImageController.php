<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([       
            'image'=>'required|mimes:png,jpg,jpeg',
        ]);

        $file = $request->file('image');

        $name = Str::random(10);

        $image_url = Storage::disk('public')->putFileAs('images', $file, $name . '.' . $file->extension());

        return [
            'url' =>  env('APP_URL') . "/storage/{$image_url}"
        ];
    }
}

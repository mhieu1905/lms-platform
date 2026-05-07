<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TemporaryUploadController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $type = $request->input('type', 'general');
            $file = $request->file('image');
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();

            // Delete old image
            $oldPath = $request->session()->get("tmp_image_$type");
            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }

            // Save new image
            $path = $file->storeAs("tmp/$type", $fileName, 'public');
            $request->session()->put("tmp_image_$type", $path);

            return response()->json([
                'path' => Storage::url($path),
                'session_key' => "tmp_image_$type",
            ]);
        }

        return response()->json(['message' => 'No image uploaded'], 400);
    }
}

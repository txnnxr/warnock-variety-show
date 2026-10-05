<?php

namespace App\Http\Controllers;

use App\Models\Show;
use App\Models\ShowPhoto;
use Illuminate\Http\Request;

class ShowPhotoController extends Controller
{
    public function store(Request $request, Show $show)
    {
        $request->validate([
            'photos' => 'required|array|max:30',
            'photos.*' => 'image|max:12288',
            'caption' => 'nullable|string|max:255',
        ], [
            'photos.max' => 'Upload up to 30 photos at a time.',
            'photos.*.image' => 'Only image files can be uploaded.',
            'photos.*.max' => 'Each photo needs to be under 12 MB.',
            'photos.*.uploaded' => 'A photo was too big for the server to accept. Try a smaller one, or fewer at a time.',
        ]);

        $photos = collect($request->file('photos'))->map(fn ($file) => $show->photos()->create([
            'path' => $file->store("shows/{$show->id}", 'public'),
            'caption' => $request->input('caption'),
        ]));

        if ($request->expectsJson()) {
            return response()->json(['photos' => $photos->map->toGallery()]);
        }

        return redirect()->route('shows.show', $show)->with('status', 'Photos uploaded.');
    }

    public function destroy(Request $request, ShowPhoto $photo)
    {
        $show = $photo->show;
        $photo->delete();

        if ($request->expectsJson()) {
            return response()->noContent();
        }

        return redirect()->route('shows.show', $show)->with('status', 'Photo deleted.');
    }
}

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
        ]);

        foreach ($request->file('photos') as $file) {
            $show->photos()->create([
                'path' => $file->store("shows/{$show->id}", 'public'),
                'caption' => $request->input('caption'),
            ]);
        }

        return redirect()->route('shows.show', $show)->with('status', 'Photos uploaded.');
    }

    public function destroy(ShowPhoto $photo)
    {
        $show = $photo->show;
        $photo->delete();

        return redirect()->route('shows.show', $show)->with('status', 'Photo deleted.');
    }
}

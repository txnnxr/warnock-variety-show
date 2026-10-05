<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function index()
    {
        return Guest::all();
    }

    public function store(Request $request)
    {
        return Guest::create($request->validate([
            'person_id' => 'required|exists:people,id',
            'show_id' => 'required|exists:shows,id',
            'plus_one' => 'boolean',
        ]));
    }

    public function show(Guest $guest)
    {
        return $guest;
    }

    public function update(Request $request, Guest $guest)
    {
        $guest->update($request->validate([
            'person_id' => 'sometimes|exists:people,id',
            'show_id' => 'sometimes|exists:shows,id',
            'plus_one' => 'boolean',
        ]));
        return $guest;
    }

    public function destroy(Guest $guest)
    {
        $guest->delete();
        return response()->json(null, 204);
    }
}

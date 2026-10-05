<?php

namespace App\Http\Controllers;

use App\Models\Exhibitor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PerformerController extends Controller
{
    public function index()
    {
        return Exhibitor::all();
    }

    public function store(Request $request)
    {
        return Exhibitor::create($request->validate([
            'person_id' => 'required|exists:people,id',
            'show_id' => 'required|exists:shows,id',
            'exhibition_description' => 'required|string',
            'status' => ['required', Rule::in(['Pending', 'Approved', 'Denied'])],
            'performance_order' => 'required|integer',
            'plus_one' => 'boolean',
        ]));
    }

    public function show(Exhibitor $performer)
    {
        return $performer;
    }

    public function update(Request $request, Exhibitor $performer)
    {
        $performer->update($request->validate([
            'person_id' => 'sometimes|exists:people,id',
            'show_id' => 'sometimes|exists:shows,id',
            'exhibition_description' => 'sometimes|string',
            'status' => ['sometimes', Rule::in(['Pending', 'Approved', 'Denied'])],
            'performance_order' => 'sometimes|integer',
            'plus_one' => 'boolean',
        ]));
        return $performer;
    }

    public function destroy(Exhibitor $performer)
    {
        $performer->delete();
        return response()->json(null, 204);
    }
}

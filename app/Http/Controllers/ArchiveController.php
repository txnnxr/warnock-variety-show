<?php

namespace App\Http\Controllers;

use App\Models\Show;

class ArchiveController extends Controller
{
    public function index()
    {
        $shows = Show::past()
            ->with(['photos', 'lineup'])
            ->orderByDesc('date')
            ->get();

        return view('shows.archive', compact('shows'));
    }
}

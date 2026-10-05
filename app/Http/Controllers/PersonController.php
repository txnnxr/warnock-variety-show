<?php

namespace App\Http\Controllers;

use App\Models\Person;

class PersonController extends Controller
{
    public function index()
    {
        $people = Person::withCount([
            'invites as attended_count' => fn ($query) => $query->where('response_status', 'ATTENDING')->whereHas('show', fn ($show) => $show->past()),
            'exhibitors as performed_count' => fn ($query) => $query->where('status', 'Approved'),
        ])->orderBy('name')->get();

        return view('people.index', compact('people'));
    }

    public function show(Person $person)
    {
        $person->load(['invites.show', 'exhibitors.show', 'submissionApplications.show']);

        return view('people.show', compact('person'));
    }
}

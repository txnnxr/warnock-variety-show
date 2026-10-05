<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

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
        $others = Person::whereKeyNot($person->id)->orderBy('name')->get();

        return view('people.show', compact('person', 'others'));
    }

    /**
     * Fold a duplicate into this person: their invites, applications and
     * performances move over, missing contact details are filled in, and the
     * duplicate is removed.
     */
    public function merge(Request $request, Person $person)
    {
        $validated = $request->validate([
            'duplicate_id' => ['required', Rule::exists('people', 'id')->whereNull('deleted_at'), Rule::notIn([$person->id])],
        ]);

        $duplicate = Person::findOrFail($validated['duplicate_id']);

        DB::transaction(function () use ($person, $duplicate) {
            foreach (['invites', 'submission_applications', 'exhibitors', 'guests'] as $table) {
                DB::table($table)->where('person_id', $duplicate->id)->update(['person_id' => $person->id]);
            }

            $person->email ??= $duplicate->email;
            $person->phone_number ??= $duplicate->phone_number;
            $person->contact_info ??= $duplicate->contact_info;
            $person->save();

            $duplicate->delete();
        });

        return redirect()->route('people.show', $person)->with('status', "Merged {$duplicate->name} into {$person->name}.");
    }
}

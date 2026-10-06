<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PersonController extends Controller
{
    public function index()
    {
        $people = Person::withCount([
            'invites as attended_count' => fn ($query) => $query->where('response_status', 'ATTENDING')->whereHas('show', fn ($show) => $show->past()),
            'exhibitors as performed_count' => fn ($query) => $query->where('status', 'Approved'),
        ])->withCount(['invites', 'submissionApplications'])->orderBy('name')->get();

        // Suggest keeping whoever has the most history, preferring strong
        // matches over first-name hunches; ties go to the oldest record.
        $duplicateGroups = Person::duplicateGroups($people)->map(fn ($group) => $group + [
            'keep' => $group['people']->sortBy([
                fn ($a, $b) => in_array($b->id, $group['strong']) <=> in_array($a->id, $group['strong']),
                fn ($a, $b) => ($b->invites_count + $b->submission_applications_count) <=> ($a->invites_count + $a->submission_applications_count),
                fn ($a, $b) => $a->id <=> $b->id,
            ])->first(),
        ]);

        return view('people.index', compact('people', 'duplicateGroups'));
    }

    public function show(Person $person)
    {
        $person->load(['invites.show', 'exhibitors.show', 'submissionApplications.show']);
        $others = Person::whereKeyNot($person->id)->orderBy('name')->get();
        $notMatching = $person->notMatching();

        return view('people.show', compact('person', 'others', 'notMatching'));
    }

    /**
     * Stop suggesting that people are duplicates. With `person_id`, only
     * that person is split from the rest; without it, none of them match.
     */
    public function markNotMatching(Request $request)
    {
        $validated = $request->validate([
            'person_ids' => ['required', 'array', 'min:2'],
            'person_ids.*' => ['distinct', Rule::exists('people', 'id')->whereNull('deleted_at')],
            'person_id' => ['nullable', Rule::in($request->input('person_ids', []))],
        ]);

        $people = Person::findMany($validated['person_ids']);

        if ($validated['person_id'] ?? null) {
            $people->find($validated['person_id'])->markNotMatching($people);
            $message = $people->find($validated['person_id'])->name.' will no longer be suggested with them.';
        } else {
            $people->each(fn ($person) => $person->markNotMatching($people));
            $message = 'Those people will no longer be suggested as duplicates.';
        }

        return redirect()->route('people.index')->with('status', $message);
    }

    public function forgetNotMatching(Person $person, Person $other)
    {
        $person->forgetNotMatching($other);

        return redirect()->route('people.show', $person)->with('status', "{$other->name} can be suggested as a duplicate again.");
    }

    /**
     * Merge one duplicate into this person, from the person's own page.
     */
    public function merge(Request $request, Person $person)
    {
        $validated = $request->validate([
            'duplicate_id' => ['required', Rule::exists('people', 'id')->whereNull('deleted_at'), Rule::notIn([$person->id])],
        ]);

        $duplicate = Person::findOrFail($validated['duplicate_id']);
        $person->absorb($duplicate);

        return redirect()->route('people.show', $person)->with('status', "Merged {$duplicate->name} into {$person->name}.");
    }

    /**
     * Merge several people into one, from the People list.
     */
    public function mergeMany(Request $request)
    {
        $validated = $request->validate([
            'keep_id' => ['required', Rule::exists('people', 'id')->whereNull('deleted_at')],
            'merge_ids' => ['required', 'array', 'min:1'],
            'merge_ids.*' => ['distinct', Rule::exists('people', 'id')->whereNull('deleted_at'), Rule::notIn([$request->input('keep_id')])],
        ], [
            'merge_ids.required' => 'Choose at least one other person to merge.',
            'merge_ids.*.not_in' => "The person you're keeping can't also be merged away.",
        ]);

        $keep = Person::findOrFail($validated['keep_id']);
        $duplicates = Person::findMany($validated['merge_ids']);

        DB::transaction(fn () => $duplicates->each(fn ($duplicate) => $keep->absorb($duplicate)));

        $count = $duplicates->count();

        return redirect()->route('people.index')->with('status', "Merged {$count} ".Str::plural('record', $count)." into {$keep->name}.");
    }
}

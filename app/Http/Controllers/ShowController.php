<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShowRequest;
use App\Http\Requests\UpdateShowRequest;
use App\Mail\ShowCanceled;
use App\Models\Show;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ShowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        $shows = Show::orderBy('date', 'asc')->get();
        return view('shows.index', compact('shows'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        return view('shows.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreShowRequest  $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function store(StoreShowRequest $request)
    {
        $show = Show::create([
            'name' => $request->name,
            'description' => $request->description,
            'date' => $request->date,
            'max_attendants' => $request->max_attendants,
            'address' => $request->address,
            'count_performers' => $request->boolean('count_performers'),
        ]);

        return redirect('/shows');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Show  $show
     * @return \Illuminate\Http\Response
     */
    public function show(Show $show)
    {
        $show->load(['lineup.person', 'photos']);

        return view('shows.show', compact('show'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Show  $show
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit(Show $show)
    {
        return view('shows.create', compact('show'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateShowRequest  $request
     * @param  \App\Models\Show  $show
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateShowRequest $request, Show $show)
    {
        $show->update([
            'name' => $request->name,
            'description' => $request->description,
            'date' => $request->date,
            'max_attendants' => $request->max_attendants,
            'address' => $request->address,
            'count_performers' => $request->boolean('count_performers'),
        ]);

        $show->promoteWaitlist();

        return redirect('/shows');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Show  $show
     * @return \Illuminate\Http\Response
     */
    public function destroy(Show $show)
    {
        $show->delete();

        return redirect('/shows');
    }

    /**
     * Cancel the show, optionally emailing everyone who hasn't said no.
     */
    public function cancel(Request $request, Show $show)
    {
        $validated = $request->validate(['note' => 'nullable|string|max:1000']);

        $show->update(['canceled' => true]);

        $notified = 0;

        if ($request->boolean('notify')) {
            foreach ($show->interestedGuests() as $invite) {
                Mail::to($invite->email)->send(new ShowCanceled($invite, $validated['note'] ?? null));
                $notified++;
            }
        }

        return redirect()->route('shows.show', $show)->with('status', 'Show canceled.'.($notified ? ' Emailed '.$notified.' '.Str::plural('guest', $notified).'.' : ''));
    }

    public function restore(Show $show)
    {
        $show->update(['canceled' => false]);

        return redirect()->route('shows.show', $show)->with('status', 'Show restored. Guests were not emailed.');
    }
}

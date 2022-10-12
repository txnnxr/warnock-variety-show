<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShowRequest;
use App\Http\Requests\UpdateShowRequest;
use App\Models\Show;

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
        return inertia('Shows/Index', compact('shows'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        return inertia('Shows/Form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreShowRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function store(StoreShowRequest $request)
    {
        $show = Show::create([
            'name' => $request->name,
            'description' => htmlspecialchars($request->description),
            'date' => $request->date,
            'max_attendants' => $request->input('max_attendants', 30),
            'max_talents' => $request->input('max_talents', 10),
            'address' => $request->address,
        ]);

        return redirect('/shows');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Show $show
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function show(Show $show)
    {
        $show->description = htmlspecialchars_decode(nl2br($show->description));
        return inertia('Shows/Show', [
            'show' => $show->append(['attending_invites', 'at_capacity_attendants', 'maybe_invites', 'no_invites', 'pending_invites', 'attending_waitlist_invites', 'at_capacity_talents', 'talent_waitlist_invites', 'attending_talents'])
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Show $show
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function edit(Show $show)
    {
        $formattedShow = $show->toArray();
        $formattedShow['date'] = $show->date->format('Y-m-d\TH:i');

        return inertia('Shows/Form', [
            'show' => $formattedShow
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateShowRequest $request
     * @param \App\Models\Show $show
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateShowRequest $request, Show $show)
    {
        $show->update([
            'name' => $request->name,
            'description' => htmlspecialchars($request->description),
            'date' => $request->date,
            'max_attendants' => $request->input('max_attendants', 30),
            'max_talents' => $request->input('max_talents', 10),
            'address' => $request->address,
        ]);

        return redirect('/shows');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Show $show
     * @return \Illuminate\Http\Response
     */
    public function destroy(Show $show)
    {
        //
    }

    public function togglePublicRsvp(Show $show): \Illuminate\Http\RedirectResponse
    {
        $show->update([
            'public_rsvp_open' => !$show->public_rsvp_open
        ]);

        return redirect()->route('admin.show', ['show' => $show]);
    }

    public function adminDashboard(Show $show){
        $invites = $show->invites;
        return inertia('Shows/AdminDashboard', compact('show', 'invites'));
    }


}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\guestRequestRequest;
use App\Http\Requests\StoreInviteRequest;
use App\Http\Requests\UpdateInviteRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Invite;
use App\Models\Show;
use Illuminate\Support\Str;

class InviteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index(Show $show)
    {
        return view('shows.invites.index', compact('show'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Show $show
     * @param \App\Http\Requests\StoreInviteRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Show $show, StoreInviteRequest $request)
    {
        Invite::create([
            'show_id' => $show->id,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'has_plus_one_option' => $request->has_plus_one_option,
            'key' => Str::uuid(),
        ]);

        return redirect()->action('InviteController@index', ['show' => $show]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invite  $invite
     * @return \Illuminate\Http\Response
     */
    public function show(Invite $invite)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invite  $invite
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit(Invite $invite)
    {
        $invite->update([
            'response_status' => 'PENDING - UPDATE',
        ]);

        return redirect()->action('InviteController@respond', ['show'=> $invite->show, 'key' => $invite->key]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateInviteRequest  $request
     * @param  \App\Models\Invite  $invite
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateInviteRequest $request, Invite $invite)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invite  $invite
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invite $invite)
    {
        //
    }

    public function respond(Show $show, $key)
    {
        if ($show->date->isBefore(Carbon::now())){
            return redirect('/');
        }

        $invite = Invite::where('key', $key)->firstOrFail();

        if (!str_contains($invite->response_status, 'PENDING') && !str_contains($invite->response_status, 'CREATED')) {
            return view('shows.invites.thank-you', compact('invite', 'show'));
        }
        return view('shows.invites.respond', compact('show', 'invite'));
    }

    public function guestThankYou(Invite $invite)
    {
        $show = $invite->show;
        return view('shows.invites.thank-you', compact('invite', 'show'));
    }

    //TODO: this should just be update?
    public function registerResponse(Show $show, $key, Request $request)
    {
        $invite = tap(Invite::where('key', $key)->first(), function ($invite) use ($request){
            $invite->update([
                'response_status' => $request->input('response_status'),
                'talent' => $request->input('talent', 0),
                'talent_write_in' => ($request->input('talent_write_in')),
                'plus_one_status' => $request->input('plus_one_status', false),
                'plus_one_name' => $request->input('plus_one_name', null),
            ]);
        });

        return redirect()->action('InviteController@guestThankYou', ['invite' => $invite]);
    }

    public function generateICS(Invite $invite)
    {
        $invite->generateICS();
    }

    public function markAsSent(Invite $invite)
    {
        $invite->update(['response_status' => 'PENDING - SENT']);
        return redirect()->action('InviteController@index', ['show' => $invite->show]);
    }

    public function markAsOpened(Invite $invite)
    {
        $invite->update(['response_status' => 'PENDING - OPENED']);
    }

    public function guestRequest(Show $show){
        return view('shows.invites.guest-request', compact('show'));
    }

    public function guestRequestSave(guestRequestRequest $request, Show $show)
    {
        $invite = Invite::create([
            'show_id' => $show->id,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'response_status' => $request->response_status,
            'talent' => $request->talent,
            'guest_request' => true,
            'key' => Str::uuid(),
        ]);

        return back();
    }

    public function guestRequestApprove(Invite $invite){
        if (\Auth::user()) {
            $invite->update([
                'guest_request' => false
            ]);
        }

        return redirect()->action('InviteController@index', ['show' => $invite->show]);
    }
}

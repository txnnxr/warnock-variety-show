<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInviteRequest;
use App\Http\Requests\UpdateInviteRequest;
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
            'response_status' => 'PENDING',
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

    //TODO: this should just be show
    public function respond(Show $show, $key)
    {
        $invite = Invite::where('key', $key)->firstOrFail();

        if ($invite->response_status != 'PENDING') {
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
            ]);
        });

//        if(auth()->user()) {
//            return redirect()->action('InviteController@index', ['show' => $show]);
//        } else {
            return redirect()->action('InviteController@guestThankYou', ['invite' => $invite]);
//        }
    }

    public function generateICS(Invite $invite)
    {
        $invite->generateICS();
    }
}

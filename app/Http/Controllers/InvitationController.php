<?php

namespace App\Http\Controllers;

use App\Events\ResponseReceived;
use App\Http\Requests\guestRequestRequest;
use App\Http\Requests\StoreInviteRequest;
use App\Http\Requests\UpdateInviteRequest;
use App\Notifications\InvitationSent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Invite;
use App\Models\Show;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

//TODO: for the love of god rename to InvitationController
class InvitationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index(Show $show)
    {
        return inertia('Shows/AdminDashboard', compact('show'));
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
            'phone' => $request->input('phone'),
            'email' => $request->email,
            'has_plus_one_option' => $request->input('has_plus_one_option', false),
            'key' => Str::uuid(),
        ]);

        return redirect()->route('admin.show', ['show' => $show]);
//        return redirect()->action('InvitationController@index', ['show' => $show]);
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
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function edit(Invite $invite)
    {
        return inertia('Invitation', ['show'=> $invite->show->append(['at_capacity_attendants', 'at_capacity_talents', 'talent_waitlist_invites', 'decoded_html_description']), 'invite' => $invite]);
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

        $invite = Invite::where('show_id', $show->id)->where('key', $key)->firstOrFail();

        if (!str_contains($invite->response_status, 'PENDING') && !str_contains($invite->response_status, 'CREATED')) {
            return view('shows.invites.thank-you', compact('invite', 'show'));
        }
        return view('shows.invites.respond', compact('show', 'invite'));
    }

    public function guestThankYou(Invite $invite)
    {
        $invite->is_on_talent_waitlist = $invite->isOnTalentWaitlist();
        $invite->is_on_attending_waitlist = $invite->isOnAttendingWaitlist();

        $show = $invite->show;
        return inertia('InvitationThankYou', compact('invite', 'show'));
    }

    //TODO: this should just be update? -- no Update should be an admin updating the nature of the invitation

    public function registerResponse(Invite $invite, Request $request)
    {
        $show = $invite->show;
        $phone = $request->input('phone');

        $updateArray = [
            'response_status' => $request->input('response_status'),
            'talent' => $request->input('talent', 0),
            'talent_write_in' => ($request->input('talent_write_in')),
            'plus_one_status' => $request->input('plus_one_status', false),
            'plus_one_name' => $request->input('plus_one_name'),
            'can_notify' => $request->input('can_notify', false),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'can_notify' => $request->input('can_notify', false)
        ];

        if ($show->at_capacity_attendants && $updateArray['response_status'] == 'ATTENDING') {
            $updateArray['attendance_waitlist_priority'] = $show->getNextWaitlistPriority('attendance');
        }
        if ($show->at_capacity_talents && $updateArray['talent'] == 1) {
            $updateArray['talent_waitlist_priority'] = $show->getNextWaitlistPriority('talent');
        }

        $invite->update($updateArray);

        ResponseReceived::dispatch($invite);
        return redirect()->action('InvitationController@guestThankYou', ['invite' => $invite]);
    }

    public function generateICS(Invite $invite)
    {
        $invite->generateICS();
    }

    public function send(Invite $invite)
    {
//        dd($invite);
        $invite->notify(new InvitationSent());
        $invite->update(['response_status' => 'PENDING - SENT']);
//        Event::listen(function (NotificationSent $event) {
//            dd($event);
//        });
        return redirect()->route('admin.show', ['show' => $invite->show]);
    }

//    public function markAsSent(Invite $invite)
//    {
//        $invite->update(['response_status' => 'PENDING - SENT']);
//        return redirect()->action('InvitationController@index', ['show' => $invite->show]);
//    }

//    public function markAsOpened(Invite $invite)
//    {
//        $invite->update(['response_status' => 'PENDING - OPENED']);
//    }

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

        return redirect()->action('InvitationController@index', ['show' => $invite->show]);
    }

    public function guestInvitation(Show $show){

        return inertia('Invitation', compact('show'));
    }
    public function guestInvitationStore(Show $show, StoreInviteRequest $request) {
        $invite = Invite::create([
            'show_id' => $show->id,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'response_status' => $request->input('response_status'),
            'talent' => $request->input('talent', 0),
            'talent_write_in' => ($request->input('talent_write_in')),
            'plus_one_status' => $request->input('plus_one_status', false),
            'plus_one_name' => $request->input('plus_one_name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'can_notify' => $request->input('can_notify', false),
            'key' => Str::uuid(),
        ]);

        ResponseReceived::dispatch($invite);
        return redirect()->action('ShowController@show', compact('show'));
    }
}

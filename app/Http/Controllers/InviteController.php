<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInviteRequest;
use App\Mail\GuestRequestApproved;
use App\Mail\Invitation;
use App\Models\Invite;
use App\Models\Person;
use App\Models\Show;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

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
            'person_id' => Person::resolve("{$request->first_name} {$request->last_name}", $request->email, $request->phone)->id,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'key' => Str::uuid(),
        ]);

        return redirect()->action([InviteController::class, 'index'], ['show' => $show]);
    }

    //TODO: this should just be show
    public function respond(Request $request, Show $show, $key)
    {
        $invite = Invite::where('key', $key)->where('show_id', $show->id)->firstOrFail();

        $responded = ! str_contains($invite->response_status, 'PENDING') && ! str_contains($invite->response_status, 'CREATED');

        if ($responded && ! $request->boolean('change')) {
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
        $invite = Invite::where('key', $key)->where('show_id', $show->id)->firstOrFail();

        $validated = $request->validate([
            'response_status' => ['required', Rule::in([Invite::ATTENDING, Invite::MAYBE, Invite::NO])],
            'talent' => 'boolean',
            'plus_one_status' => 'boolean',
        ]);

        $invite->respond(
            $validated['response_status'],
            $invite->has_plus_one_option ? $request->boolean('plus_one_status') : false,
            $request->boolean('talent'),
        );

        return redirect()->route('invites.thank-you', $invite);
    }

    public function calendar(Invite $invite)
    {
        abort_unless($invite->canSeeAddress(), 404);

        $disposition = preg_match('/(android|iphone|ipad|mobile)/i', (string) request()->userAgent()) ? 'inline' : 'attachment';

        return response($invite->toICS(), 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => $disposition.'; filename="'.Str::slug($invite->show->name).'.ics"',
        ]);
    }

    public function markAsSent(Invite $invite)
    {
        $invite->update(['response_status' => 'PENDING - SENT']);
        return redirect()->action([InviteController::class, 'index'], ['show' => $invite->show]);
    }

    public function markAsOpened(Invite $invite)
    {
        if ($invite->response_status === 'PENDING - SENT') {
            $invite->update(['response_status' => 'PENDING - OPENED']);
        }

        return response()->noContent();
    }

    /**
     * Email an invitation and mark it as sent.
     */
    public function send(Invite $invite)
    {
        abort_unless($invite->email, 422, 'This invite has no email address.');

        Mail::to($invite->email)->send(new Invitation($invite));

        if ($invite->response_status === 'CREATED') {
            $invite->update(['response_status' => 'PENDING - SENT']);
        }

        return back()->with('status', "Invitation sent to {$invite->email}.");
    }

    /**
     * Email every invite that hasn't been sent yet and has an email address.
     */
    public function sendAll(Show $show)
    {
        $invites = $show->invites()->withResponse('CREATED')->whereNotNull('email')->where('email', '!=', '')->get();

        foreach ($invites as $invite) {
            Mail::to($invite->email)->send(new Invitation($invite));
            $invite->update(['response_status' => 'PENDING - SENT']);
        }

        return back()->with('status', "Sent {$invites->count()} invitation(s).");
    }

    public function guestRequest(Show $show){
        return view('shows.invites.guest-request', compact('show'));
    }

    public function guestRequestSave(Request $request, Show $show)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:150',
            'phone' => 'nullable|string|max:20',
            'response_status' => ['required', Rule::in([Invite::ATTENDING, Invite::MAYBE, Invite::NO])],
            'plus_one_status' => 'boolean',
        ]);

        $invite = Invite::create([
            'show_id' => $show->id,
            'person_id' => Person::resolve("{$validated['first_name']} ".($validated['last_name'] ?? ''), $validated['email'] ?? null, $validated['phone'] ?? null)->id,
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'] ?? null,
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'response_status' => 'CREATED',
            'guest_request' => true,
            'key' => Str::uuid(),
        ]);

        $invite->respond($validated['response_status'], $request->boolean('plus_one_status'));

        return redirect()->route('invites.thank-you', $invite);
    }

    public function guestRequestApprove(Invite $invite){
        $invite->approveGuestRequest();

        if ($invite->email && in_array($invite->response_status, [Invite::ATTENDING, Invite::WAITLIST])) {
            Mail::to($invite->email)->send(new GuestRequestApproved($invite));
        }

        return redirect()->action([InviteController::class, 'index'], ['show' => $invite->show]);
    }
}

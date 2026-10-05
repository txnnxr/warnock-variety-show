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

    /**
     * Invite everyone who attended a past show (or any past show), skipping
     * people already invited. Invites start unsent, ready for "Email All".
     */
    public function invitePastGuests(Request $request, Show $show)
    {
        $validated = $request->validate([
            'source' => ['required', function ($attribute, $value, $fail) use ($show) {
                if ($value !== 'all' && ! Show::past()->whereKey($value)->whereKeyNot($show->id)->exists()) {
                    $fail('Choose a past show.');
                }
            }],
        ]);

        $attended = Invite::query()
            ->withResponse(Invite::ATTENDING)
            ->where('guest_request', false)
            ->whereHas('show', fn ($query) => $query->past())
            ->where('show_id', '!=', $show->id)
            ->when($validated['source'] !== 'all', fn ($query) => $query->where('show_id', $validated['source']))
            ->latest('id')
            ->get()
            ->unique(fn (Invite $invite) => $invite->person_id ?? 'email:'.strtolower((string) $invite->email).':'.$invite->full_name);

        $existing = $show->invites()->get();
        $alreadyInvited = fn (Invite $invite) => ($invite->person_id && $existing->contains('person_id', $invite->person_id))
            || ($invite->email && $existing->contains(fn ($other) => strcasecmp((string) $other->email, $invite->email) === 0));

        $added = 0;

        foreach ($attended->reject($alreadyInvited) as $past) {
            Invite::create([
                'show_id' => $show->id,
                'person_id' => $past->person_id,
                'first_name' => $past->first_name,
                'middle_name' => $past->middle_name,
                'last_name' => $past->last_name,
                'email' => $past->email,
                'phone' => $past->phone,
                'key' => Str::uuid(),
            ]);
            $added++;
        }

        $skipped = $attended->count() - $added;

        return back()->with('status', "Added {$added} ".Str::plural('invite', $added).($skipped ? " ({$skipped} already invited)" : '').'. Use Email All Unsent Invites to send them.');
    }

    public function edit(Invite $invite)
    {
        $show = $invite->show;

        return view('shows.invites.edit', compact('invite', 'show'));
    }

    /**
     * Admin edit. Freeing a seat (a no, a dropped plus one) promotes the
     * waitlist; setting someone to attending is allowed even when full.
     */
    public function update(Request $request, Invite $invite)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:150',
            'phone' => 'nullable|string|max:20',
            'response_status' => ['required', Rule::in(array_keys(Invite::STATUSES))],
            'talent' => 'boolean',
            'has_plus_one_option' => 'boolean',
            'plus_one_status' => 'boolean',
        ]);

        $contactChanged = $invite->first_name !== $validated['first_name']
            || $invite->last_name !== ($validated['last_name'] ?? null)
            || $invite->email !== ($validated['email'] ?? null)
            || $invite->phone !== ($validated['phone'] ?? null);

        $invite->fill($validated + [
            'talent' => $request->boolean('talent'),
            'has_plus_one_option' => $request->boolean('has_plus_one_option'),
            'plus_one_status' => $request->boolean('plus_one_status'),
        ]);

        if ($invite->response_status === Invite::WAITLIST) {
            $invite->waitlisted_at ??= now();
        } else {
            $invite->waitlisted_at = null;
        }

        if ($contactChanged) {
            $invite->person_id = Person::resolve("{$invite->first_name} {$invite->last_name}", $invite->email, $invite->phone)->id;
        }

        $invite->save();
        $invite->show->promoteWaitlist();

        return redirect()->route('invites.index', $invite->show)->with('status', "Saved {$invite->full_name}.");
    }

    /**
     * Soft delete: the invite disappears from the site but stays in the
     * database for the show's history.
     */
    public function destroy(Invite $invite)
    {
        $show = $invite->show;
        $name = $invite->full_name;
        $heldSeat = $invite->holdsSeat();

        $invite->delete();

        if ($heldSeat) {
            $show->promoteWaitlist();
        }

        return redirect()->route('invites.index', $show)->with('status', "Removed {$name}'s invite.");
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
        if ($show->canceled) {
            return redirect()->route('shows.show', $show)->with('status', 'This show has been canceled.');
        }

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

    /**
     * The secret guest link: same form, but RSVPs are approved automatically.
     */
    public function join(string $key)
    {
        $show = Show::where('guest_link_key', $key)->firstOrFail();
        $guestLinkKey = $key;

        return view('shows.invites.guest-request', compact('show', 'guestLinkKey'));
    }

    public function resetGuestLink(Show $show)
    {
        $show->resetGuestLink();

        return back()->with('status', 'Guest link reset. The old link no longer auto-approves RSVPs.');
    }

    public function guestRequestSave(Request $request, Show $show)
    {
        if ($show->canceled) {
            return redirect()->route('shows.show', $show)->with('status', 'This show has been canceled.');
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:150',
            'phone' => 'nullable|string|max:20',
            'response_status' => ['required', Rule::in([Invite::ATTENDING, Invite::MAYBE, Invite::NO])],
            'plus_one_status' => 'boolean',
            'guest_link_key' => 'nullable|string',
        ]);

        // RSVPs through the show's secret guest link are approved right away.
        $viaGuestLink = isset($validated['guest_link_key']) && hash_equals((string) $show->guest_link_key, $validated['guest_link_key']);

        $invite = Invite::create([
            'show_id' => $show->id,
            'person_id' => Person::resolve("{$validated['first_name']} ".($validated['last_name'] ?? ''), $validated['email'] ?? null, $validated['phone'] ?? null)->id,
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'] ?? null,
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'response_status' => 'CREATED',
            'guest_request' => ! $viaGuestLink,
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

<?php

namespace App\Http\Controllers;

use App\Mail\LineupAnnouncement;
use App\Mail\ShowRecap;
use App\Models\Show;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ShowEmailController extends Controller
{
    /**
     * Email the lineup to everyone invited who hasn't said no.
     */
    public function announceLineup(Show $show)
    {
        if ($show->isPast() || $show->canceled) {
            return back()->withErrors(['announce' => 'The lineup can only be announced for an upcoming show.']);
        }

        if ($show->lineup()->doesntExist()) {
            return back()->withErrors(['announce' => 'Approve at least one act before announcing the lineup.']);
        }

        $recipients = $show->interestedGuests();

        foreach ($recipients as $invite) {
            Mail::to($invite->email)->send(new LineupAnnouncement($invite));
        }

        $show->update(['lineup_announced_at' => now()]);

        return back()->with('status', 'Lineup sent to '.$recipients->count().' '.Str::plural('guest', $recipients->count()).'.');
    }

    /**
     * Thank confirmed guests after the show, with the lineup and photos.
     */
    public function sendRecap(Show $show)
    {
        if (! $show->isPast() || $show->canceled) {
            return back()->withErrors(['recap' => 'The recap can only be sent after the show.']);
        }

        $recipients = $show->recapRecipients();

        foreach ($recipients as $invite) {
            Mail::to($invite->email)->send(new ShowRecap($invite));
        }

        $show->update(['recap_sent_at' => now()]);

        return back()->with('status', 'Recap sent to '.$recipients->count().' '.Str::plural('guest', $recipients->count()).'.');
    }
}

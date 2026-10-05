<?php

namespace App\Console\Commands;

use App\Mail\MaybeNudge;
use App\Mail\ShowReminder;
use App\Models\Invite;
use App\Models\Show;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendShowReminders extends Command
{
    protected $signature = 'app:send-reminders';

    protected $description = 'Email confirmed guests a reminder the day before a show, and nudge "maybe" guests three days out';

    public function handle(): int
    {
        $reminders = 0;
        $nudges = 0;

        $shows = Show::upcoming()
            ->where('canceled', false)
            ->where('date', '<=', now()->addDays(3))
            ->get();

        foreach ($shows as $show) {
            if ($show->date->lte(now()->addDay())) {
                $show->invites()
                    ->withResponse(Invite::ATTENDING)
                    ->where('guest_request', false)
                    ->whereNull('reminder_sent_at')
                    ->whereNotNull('email')->where('email', '!=', '')
                    ->each(function (Invite $invite) use (&$reminders) {
                        Mail::to($invite->email)->send(new ShowReminder($invite));
                        $invite->update(['reminder_sent_at' => now()]);
                        $reminders++;
                    });
            }

            $show->invites()
                ->withResponse(Invite::MAYBE)
                ->whereNull('nudge_sent_at')
                ->whereNotNull('email')->where('email', '!=', '')
                ->each(function (Invite $invite) use (&$nudges) {
                    Mail::to($invite->email)->send(new MaybeNudge($invite));
                    $invite->update(['nudge_sent_at' => now()]);
                    $nudges++;
                });
        }

        $this->info("Sent {$reminders} reminder(s) and {$nudges} maybe nudge(s).");

        return self::SUCCESS;
    }
}

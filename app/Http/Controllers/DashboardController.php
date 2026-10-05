<?php

namespace App\Http\Controllers;

use App\Models\Invite;
use App\Models\Show;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        if (! $request->user()->can('admin')) {
            return view('dashboard');
        }

        $next = Show::upcoming()->where('canceled', false)->orderBy('date')->first();
        $lastPast = Show::past()->orderByDesc('date')->first();

        return view('dashboard', [
            'next' => $next,
            'todos' => $this->todos($next, $lastPast),
        ]);
    }

    /**
     * What needs the host's attention, most time-sensitive first.
     *
     * @return array<int, array{label: string, url: string, action: string}>
     */
    private function todos(?Show $next, ?Show $lastPast): array
    {
        $todos = [];

        if ($next) {
            $requests = $next->pending_requests->count();
            if ($requests) {
                $todos[] = ['label' => "{$requests} guest ".Str::plural('request', $requests)." waiting for approval", 'url' => route('invites.index', $next), 'action' => 'Review'];
            }

            $unsent = $next->invites()->withResponse('CREATED')->count();
            if ($unsent) {
                $todos[] = ['label' => "{$unsent} ".Str::plural('invite', $unsent)." not sent yet", 'url' => route('invites.index', $next), 'action' => 'Send'];
            }

            $limbo = $next->submissionApplications()->where('approved', false)->whereDoesntHave('exhibitor')->count();
            if ($limbo) {
                $todos[] = ['label' => "{$limbo} ".Str::plural('application', $limbo)." to review", 'url' => "/shows/{$next->id}/submission-applications", 'action' => 'Review'];
            }

            if ($next->lineup()->exists() && ! $next->lineup_announced_at) {
                $todos[] = ['label' => 'The lineup hasn\'t been announced', 'url' => route('lineup.index', $next), 'action' => 'Announce'];
            }
        }

        if ($lastPast && ! $lastPast->recap_sent_at && $lastPast->recapRecipients()->isNotEmpty()) {
            $todos[] = ['label' => "No recap sent for {$lastPast->name}", 'url' => route('shows.show', $lastPast), 'action' => 'Send recap'];
        }

        return $todos;
    }
}

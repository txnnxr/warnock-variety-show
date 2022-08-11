<?php

namespace App\Listeners;

use App\Events\ResponseReceived;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendResponseReceivedNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        \Log::debug('event listening constructed');
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ResponseReceived  $event
     * @return void
     */
    public function handle(ResponseReceived $event)
    {
        if($event->invite->can_notify) {
            $event->invite->notify(new \App\Notifications\ResponseReceived());
        }
    }
}

<?php

namespace App\Listeners;

use App\Events\ResponseUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendResponseUpdatedNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ResponseUpdated  $event
     * @return void
     */
    public function handle(ResponseUpdated $event)
    {
        //
    }
}

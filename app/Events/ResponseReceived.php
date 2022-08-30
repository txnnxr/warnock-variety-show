<?php

namespace App\Events;

use App\Models\Invite;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ResponseReceived
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Invite $invite;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Invite $invite)
    {
        Log::debug('event constructed');
        $this->invite = $invite;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        Log::debug('event fired');
        return new PrivateChannel('channel-name');
    }
}

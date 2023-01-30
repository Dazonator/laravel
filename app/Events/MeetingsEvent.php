<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MeetingsEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $template;
    public $meeting;
    public $user;
    public $updated;

    public function __construct($template, $meeting, $user, $updated = null)
    {
        $this->template = $template;
        $this->meeting = $meeting;
        $this->user = $user;
        $this->updated = $updated;
    }
}

// 'create-meeting', $meeting->toArray(), Auth::user()->toArray(), $meeting->getChanges()

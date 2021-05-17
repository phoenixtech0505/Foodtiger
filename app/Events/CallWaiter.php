<?php

namespace App\Events;

use App\Tables;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CallWaiter implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $table;
    public $msg;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Tables $table, $msg)
    {
        $this->table = $table;
        $this->msg = $msg;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('user.'.$this->table->restaurant->user->id);
    }

    public function broadcastAs()
    {
        return 'callwaiter-event';
    }
}

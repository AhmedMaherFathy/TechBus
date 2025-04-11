<?php

namespace Modules\Driver\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Driver\Models\Driver;

class DriverLocation implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Driver $driver)
    {
        //
    }

    /**
     * Get the channels the event should be broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new channel('drivers'),
        ];
    }

    public function broadcastWith()
    {
        return[
            'driverId' => $this->driver->id,
            'lat' => $this->driver->lat,
            'long' => $this->driver->long,
        ];
    }

    public function broadcastAs()
    {
        return 'drivers';
    }
}

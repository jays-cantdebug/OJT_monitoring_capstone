<?php

namespace App\Events;

use App\Models\GpsPing;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

class GpsPingBroadcast implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(public readonly GpsPing $ping)
    {
    }

    /**
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('dean.live-map.'.$this->ping->user->department->value),
        ];
    }

    public function broadcastAs(): string
    {
        return 'ping.created';
    }

    /**
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'user_id' => $this->ping->user_id,
            'name' => $this->ping->user->name,
            'avatarUrl' => $this->ping->user->avatarUrl(),
            'since' => $this->ping->dtrEntry->time_in->format('g:i A'),
            'latitude' => $this->ping->latitude,
            'longitude' => $this->ping->longitude,
            'recorded_at' => $this->ping->recorded_at->toIso8601String(),
        ];
    }
}

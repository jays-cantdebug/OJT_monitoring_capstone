<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Only Deans may ever view live locations, and only for their own
// department — this is the real authorization gate, enforced server-side
// at /broadcasting/auth. A student's Echo subscribe attempt is rejected
// here, as is a Dean subscribing to another department's channel, not
// just hidden by route middleware or a UI filter.
Broadcast::channel('dean.live-map.{department}', fn (User $user, string $department) => $user->isDean() && $user->department?->value === $department);

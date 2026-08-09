<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Only Deans may ever view live locations — this is the real authorization
// gate, enforced server-side at /broadcasting/auth. A student's Echo
// subscribe attempt is rejected here, not just hidden by route middleware.
Broadcast::channel('dean.live-map', fn (User $user) => $user->isDean());

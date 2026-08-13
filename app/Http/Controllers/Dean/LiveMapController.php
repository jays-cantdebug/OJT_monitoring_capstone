<?php

namespace App\Http\Controllers\Dean;

use App\Http\Controllers\Controller;
use App\Models\User;

class LiveMapController extends Controller
{
    public function index()
    {
        $onDuty = User::where('role', 'student_intern')
            ->whereHas('dtrEntries', fn ($query) => $query->whereNull('time_out'))
            ->get()
            ->map(function (User $student) {
                $entry = $student->openDtrEntry();
                $latestPing = $entry->gpsPings()->latest('recorded_at')->first();

                // Until the first live ping arrives, fall back to the
                // coordinate captured at Time In so the student is still
                // placeable on the map instead of being invisible - just
                // flagged as not-yet-live via hasLivePing so the frontend
                // can style/label it differently from a real-time position.
                return [
                    'userId' => $student->id,
                    'name' => $student->name,
                    'avatarUrl' => $student->avatarUrl(),
                    'since' => $entry->time_in->format('g:i A'),
                    'latitude' => $latestPing?->latitude ?? $entry->time_in_latitude,
                    'longitude' => $latestPing?->longitude ?? $entry->time_in_longitude,
                    'lastPingAt' => $latestPing?->recorded_at?->diffForHumans(),
                    'hasLivePing' => $latestPing !== null,
                ];
            });

        return view('dean.live-map', compact('onDuty'));
    }
}

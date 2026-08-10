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

                return [
                    'userId' => $student->id,
                    'name' => $student->name,
                    'avatarUrl' => $student->avatarUrl(),
                    'since' => $entry->time_in->format('g:i A'),
                    'latitude' => $latestPing?->latitude,
                    'longitude' => $latestPing?->longitude,
                    'lastPingAt' => $latestPing?->recorded_at?->diffForHumans(),
                ];
            });

        return view('dean.live-map', compact('onDuty'));
    }
}

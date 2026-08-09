<?php

namespace App\Http\Controllers\Student;

use App\Events\GpsPingBroadcast;
use App\Http\Controllers\Controller;
use App\Models\GpsPing;
use App\Support\CoordinateRules;
use Illuminate\Broadcasting\BroadcastException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GpsPingController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        $entry = $user->openDtrEntry();

        // The client never supplies which DTR entry a ping belongs to —
        // it's always re-derived here, server-side, from the currently
        // open entry. This is what makes "pings are rejected the instant
        // Time Out happens" airtight rather than trusting the client.
        abort_unless($entry, 422, 'You are not currently on duty.');

        $coordinates = $request->validate(CoordinateRules::rules());

        $ping = GpsPing::create([
            'user_id' => $user->id,
            'dtr_entry_id' => $entry->id,
            'latitude' => $coordinates['latitude'],
            'longitude' => $coordinates['longitude'],
            'recorded_at' => now(),
        ]);

        // The ping is already saved above; a Reverb outage should not fail
        // the request, since the Live Map is a live-tracking convenience,
        // not the source of truth for whether the ping was recorded.
        try {
            broadcast(new GpsPingBroadcast($ping));
        } catch (BroadcastException $e) {
            Log::warning('GPS ping broadcast failed, ping was still saved.', [
                'ping_id' => $ping->id,
                'error' => $e->getMessage(),
            ]);
        }

        return response()->json(['status' => 'ok']);
    }
}

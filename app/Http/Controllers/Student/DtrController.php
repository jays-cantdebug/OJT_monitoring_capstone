<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\DtrEntry;
use App\Support\CoordinateRules;
use App\Support\Geofence;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DtrController extends Controller
{
    public function show(Request $request)
    {
        $openEntry = $request->user()->openDtrEntry();

        return view('student.time', compact('openEntry'));
    }

    public function history(Request $request)
    {
        $entries = $request->user()->dtrEntries()
            ->orderByDesc('time_in')
            ->get();

        $totalSecondsThisMonth = $entries
            ->filter(fn (DtrEntry $entry) => $entry->time_out && $entry->time_in->isCurrentMonth())
            ->sum(fn (DtrEntry $entry) => $entry->durationInSeconds());

        return view('student.attendance', [
            'entries' => $entries,
            'totalHoursThisMonth' => intdiv((int) $totalSecondsThisMonth, 3600),
            'totalMinutesThisMonth' => intdiv((int) $totalSecondsThisMonth % 3600, 60),
        ]);
    }

    public function clockIn(Request $request): RedirectResponse
    {
        $user = $request->user();

        abort_if($user->openDtrEntry(), 422, 'You are already on duty.');

        $coordinates = $request->validate(CoordinateRules::rules());

        // Recorded for the Dean's geofence log only - Time In is never
        // blocked for being outside the company geofence.
        $geofence = Geofence::check($user->studentProfile, $coordinates['latitude'], $coordinates['longitude']);

        DtrEntry::create([
            'user_id' => $user->id,
            'time_in' => now(),
            'time_in_latitude' => $coordinates['latitude'],
            'time_in_longitude' => $coordinates['longitude'],
            'time_in_distance_from_company_m' => $geofence['distance'],
            'time_in_outside_geofence' => $geofence['outside'],
        ]);

        return redirect()->route('student.time')->with('status', 'You have timed in.');
    }

    public function clockOut(Request $request): RedirectResponse
    {
        $user = $request->user();

        $entry = $user->openDtrEntry();

        abort_unless($entry, 422, 'You are not currently on duty.');

        $coordinates = $request->validate(CoordinateRules::rules());

        $entry->update([
            'time_out' => now(),
            'time_out_latitude' => $coordinates['latitude'],
            'time_out_longitude' => $coordinates['longitude'],
        ]);

        return redirect()->route('student.time')->with('status', 'You have timed out.');
    }
}

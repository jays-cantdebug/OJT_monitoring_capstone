<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\DtrEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DtrController extends Controller
{
    private const COORDINATE_RULES = [
        'latitude' => ['required', 'numeric', 'between:-90,90'],
        'longitude' => ['required', 'numeric', 'between:-180,180'],
    ];

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

        $coordinates = $request->validate(self::COORDINATE_RULES);

        DtrEntry::create([
            'user_id' => $user->id,
            'time_in' => now(),
            'time_in_latitude' => $coordinates['latitude'],
            'time_in_longitude' => $coordinates['longitude'],
        ]);

        return redirect()->route('student.time')->with('status', 'You have timed in.');
    }

    public function clockOut(Request $request): RedirectResponse
    {
        $user = $request->user();

        $entry = $user->openDtrEntry();

        abort_unless($entry, 422, 'You are not currently on duty.');

        $coordinates = $request->validate(self::COORDINATE_RULES);

        $entry->update([
            'time_out' => now(),
            'time_out_latitude' => $coordinates['latitude'],
            'time_out_longitude' => $coordinates['longitude'],
        ]);

        return redirect()->route('student.time')->with('status', 'You have timed out.');
    }
}

<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\UpdateInternshipInfoRequest;
use App\Models\DtrEntry;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class InternshipInfoController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        $profile = $user->studentProfile ?? new StudentProfile;
        $openEntry = $user->openDtrEntry();
        $lastKnownLocation = $this->lastKnownLocation($user, $openEntry);

        return view('student.internship-info', compact('profile', 'openEntry', 'lastKnownLocation'));
    }

    /**
     * The map has nothing live to show until the student is on duty and a
     * ping has arrived, so this seeds an initial position: the latest ping
     * on the open entry, falling back to that entry's Time In coordinate,
     * or - if off duty - the most recent completed entry's Time Out
     * coordinate. Null (map falls back to the default city view) only when
     * the student has no DTR history at all yet.
     */
    private function lastKnownLocation(User $user, ?DtrEntry $openEntry): ?array
    {
        if ($openEntry) {
            $latestPing = $openEntry->gpsPings()->latest('recorded_at')->first();

            return [
                'latitude' => $latestPing?->latitude ?? $openEntry->time_in_latitude,
                'longitude' => $latestPing?->longitude ?? $openEntry->time_in_longitude,
            ];
        }

        $lastCompleted = $user->dtrEntries()->whereNotNull('time_out')->latest('time_out')->first();

        if (! $lastCompleted) {
            return null;
        }

        return [
            'latitude' => $lastCompleted->time_out_latitude,
            'longitude' => $lastCompleted->time_out_longitude,
        ];
    }

    public function update(UpdateInternshipInfoRequest $request): RedirectResponse
    {
        StudentProfile::updateOrCreate(
            ['user_id' => $request->user()->id],
            $request->validated(),
        );

        return redirect()->route('student.internship-info')->with('status', 'Internship info updated.');
    }
}

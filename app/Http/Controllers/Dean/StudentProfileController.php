<?php

namespace App\Http\Controllers\Dean;

use App\Enums\AuditAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dean\UpdateStudentRequest;
use App\Models\AuditLog;
use App\Models\DtrEntry;
use App\Models\StudentProfile;
use App\Models\User;
use App\Support\Geofence;
use App\Support\GeofenceLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class StudentProfileController extends Controller
{
    public function show(User $student): View
    {
        abort_unless($this->isManageable($student), 404);

        return $this->showView($student);
    }

    public function edit(User $student): View
    {
        abort_unless($this->isManageable($student), 404);

        $profile = $student->studentProfile ?? new StudentProfile;

        // Shown as faint dots on the pin editor: where a student usually
        // times in is the quickest way for the Dean to find the workplace,
        // but the Dean still places the pin - it's never auto-derived from
        // these, since they're student-reported positions.
        $recentTimeIns = $student->dtrEntries()
            ->latest('time_in')
            ->limit(20)
            ->get(['time_in_latitude', 'time_in_longitude'])
            ->map(fn (DtrEntry $entry) => [(float) $entry->time_in_latitude, (float) $entry->time_in_longitude])
            ->values();

        return view('dean.students.edit', [
            'student' => $student,
            'profile' => $profile,
            'geofence' => $profile->geofencePayload(),
            'geofenceRadius' => $profile->geofence_radius_m ?? Geofence::DEFAULT_RADIUS_M,
            'recentTimeIns' => $recentTimeIns,
        ]);
    }

    public function update(UpdateStudentRequest $request, User $student): RedirectResponse
    {
        abort_unless($this->isManageable($student), 404);

        $validated = $request->validated();
        $profile = $student->studentProfile ?? new StudentProfile(['user_id' => $student->id]);

        $changes = [];

        if ($student->name !== $validated['name']) {
            $changes['name'] = ['from' => $student->name, 'to' => $validated['name']];
        }

        $newVerified = $request->boolean('is_verified');
        if ((bool) $profile->is_verified !== $newVerified) {
            $changes['is_verified'] = ['from' => (bool) $profile->is_verified, 'to' => $newVerified];
        }

        $student->update(['name' => $validated['name']]);

        StudentProfile::updateOrCreate(
            ['user_id' => $student->id],
            ['is_verified' => $newVerified],
        );

        if ($changes !== []) {
            AuditLog::record($request->user(), $student, AuditAction::UpdatedProfile, $changes);
        }

        // Only the edit form's geofence section sends a radius, so a request
        // without one leaves the existing pin alone rather than clearing it.
        if ($request->has('geofence_radius_m')) {
            $this->updateGeofence($request, $student, $profile, $validated);
        }

        return redirect()->route('dean.students.show', $student)->with('status', "{$student->name}'s record was updated.");
    }

    /**
     * Logged as its own audit action (separate from UpdatedProfile) so pin
     * moves are easy to find - they change how every later ping is judged.
     * Pings already recorded keep their original result; see the
     * gps_pings geofence migration.
     *
     * @param  array<string, mixed>  $validated
     */
    private function updateGeofence(UpdateStudentRequest $request, User $student, StudentProfile $profile, array $validated): void
    {
        $newLatitude = $validated['company_latitude'] ?? null;
        $newLongitude = $validated['company_longitude'] ?? null;
        $newRadius = (int) $validated['geofence_radius_m'];

        $formatLocation = fn ($latitude, $longitude) => $latitude === null || $longitude === null
            ? 'none'
            : number_format((float) $latitude, 6).', '.number_format((float) $longitude, 6);

        $changes = [];

        $oldLocation = $formatLocation($profile->company_latitude, $profile->company_longitude);
        $newLocation = $formatLocation($newLatitude, $newLongitude);
        if ($oldLocation !== $newLocation) {
            $changes['company_location'] = ['from' => $oldLocation, 'to' => $newLocation];
        }

        $oldRadius = $profile->geofence_radius_m ?? Geofence::DEFAULT_RADIUS_M;
        if ($oldRadius !== $newRadius) {
            $changes['geofence_radius'] = ['from' => "{$oldRadius} m", 'to' => "{$newRadius} m"];
        }

        if ($changes === []) {
            return;
        }

        StudentProfile::updateOrCreate(
            ['user_id' => $student->id],
            [
                'company_latitude' => $newLatitude,
                'company_longitude' => $newLongitude,
                'geofence_radius_m' => $newRadius,
            ],
        );

        AuditLog::record($request->user(), $student, AuditAction::UpdatedGeofence, $changes);
    }

    public function resetPassword(User $student): View
    {
        abort_unless($this->isManageable($student), 404);

        $password = Str::password(12);

        $student->update(['password' => $password]);

        AuditLog::record(auth()->user(), $student, AuditAction::ResetStudentPassword);

        // Rendered directly rather than flashed through a redirect: a
        // one-time credential like this must never round-trip through a
        // session flash or a URL query string. See StudentAccountController::store().
        return $this->showView($student, [
            'email' => $student->email,
            'password' => $password,
        ]);
    }

    public function destroy(User $student): RedirectResponse
    {
        abort_unless($this->isManageable($student), 404);

        $student->delete();

        AuditLog::record(auth()->user(), $student, AuditAction::DeletedStudentAccount);

        return redirect()->route('dean.students')->with('status', "{$student->name}'s account was deleted.");
    }

    /**
     * A student is only manageable by a Dean if they're a Student Intern in
     * that Dean's own department - blocks a Dean from viewing/editing
     * another department's student by guessing/typing the URL directly,
     * not just filtering them out of the list view.
     */
    private function isManageable(User $student): bool
    {
        return $student->isStudentIntern() && $student->department === auth()->user()->department;
    }

    private function showView(User $student, ?array $resetPassword = null): View
    {
        $profile = $student->studentProfile ?? new StudentProfile;

        $completedEntries = $student->dtrEntries()->whereNotNull('time_out')->get();
        $totalHoursLogged = intdiv($completedEntries->sum(fn (DtrEntry $entry) => $entry->durationInSeconds()), 3600);
        $reportsSubmitted = $student->accomplishmentReports()->count();

        $auditLogs = AuditLog::where('subject_id', $student->id)
            ->with('actor')
            ->latest('created_at')
            ->get();

        return view('dean.students.show', [
            'student' => $student,
            'profile' => $profile,
            'onDuty' => $student->openDtrEntry() !== null,
            'totalHoursLogged' => $totalHoursLogged,
            'reportsSubmitted' => $reportsSubmitted,
            'resetPassword' => $resetPassword,
            'auditLogs' => $auditLogs,
            'geofenceEvents' => GeofenceLog::eventsFor($student),
        ]);
    }
}

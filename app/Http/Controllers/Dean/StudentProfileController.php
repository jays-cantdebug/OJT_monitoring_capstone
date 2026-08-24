<?php

namespace App\Http\Controllers\Dean;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dean\UpdateStudentRequest;
use App\Models\AuditLog;
use App\Models\DtrEntry;
use App\Models\StudentProfile;
use App\Models\User;
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

        return view('dean.students.edit', [
            'student' => $student,
            'profile' => $profile,
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
            AuditLog::create([
                'actor_id' => $request->user()->id,
                'subject_id' => $student->id,
                'action' => 'updated_profile',
                'changes' => $changes,
            ]);
        }

        return redirect()->route('dean.students.show', $student)->with('status', "{$student->name}'s record was updated.");
    }

    public function resetPassword(User $student): View
    {
        abort_unless($this->isManageable($student), 404);

        $password = Str::password(12);

        $student->update(['password' => $password]);

        // Rendered directly rather than flashed through a redirect: a
        // one-time credential like this must never round-trip through a
        // session flash or a URL query string. See StudentAccountController::store().
        return $this->showView($student, [
            'email' => $student->email,
            'password' => $password,
        ]);
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
        ]);
    }
}

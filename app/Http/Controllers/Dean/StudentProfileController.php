<?php

namespace App\Http\Controllers\Dean;

use App\Http\Controllers\Controller;
use App\Models\DtrEntry;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\View\View;

class StudentProfileController extends Controller
{
    public function show(User $student): View
    {
        abort_unless($student->isStudentIntern(), 404);

        return $this->showView($student);
    }

    public function resetPassword(User $student): View
    {
        abort_unless($student->isStudentIntern(), 404);

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

    private function showView(User $student, ?array $resetPassword = null): View
    {
        $profile = $student->studentProfile ?? new StudentProfile;

        $completedEntries = $student->dtrEntries()->whereNotNull('time_out')->get();
        $totalHoursLogged = intdiv($completedEntries->sum(fn (DtrEntry $entry) => $entry->durationInSeconds()), 3600);
        $reportsSubmitted = $student->accomplishmentReports()->count();

        return view('dean.students.show', [
            'student' => $student,
            'profile' => $profile,
            'onDuty' => $student->openDtrEntry() !== null,
            'totalHoursLogged' => $totalHoursLogged,
            'reportsSubmitted' => $reportsSubmitted,
            'resetPassword' => $resetPassword,
        ]);
    }
}

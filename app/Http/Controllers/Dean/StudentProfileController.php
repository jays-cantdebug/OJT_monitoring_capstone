<?php

namespace App\Http\Controllers\Dean;

use App\Http\Controllers\Controller;
use App\Models\DtrEntry;
use App\Models\StudentProfile;
use App\Models\User;

class StudentProfileController extends Controller
{
    public function show(User $student)
    {
        abort_unless($student->isStudentIntern(), 404);

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
        ]);
    }
}

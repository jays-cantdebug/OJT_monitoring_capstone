<?php

namespace App\Support;

use App\Enums\Department;
use App\Models\AccomplishmentReport;
use App\Models\DtrEntry;
use App\Models\User;
use Illuminate\Support\Collection;

/**
 * Aggregate queries over all student_intern users, shared by the Dean
 * Dashboard and Attendance Records pages so the same numbers mean the
 * same thing everywhere they're shown. Every method takes the calling
 * Dean's department explicitly (rather than reading auth() internally)
 * to keep this testable and scoped to "students in my department only."
 */
class StudentMetrics
{
    public static function assignedCount(Department $department): int
    {
        // Excludes pending/rejected self-registered accounts - matches
        // StudentAccountController's Student Interns list, which is scoped
        // to approved accounts only.
        return User::where('role', 'student_intern')->where('status', 'approved')->where('department', $department)->count();
    }

    public static function activeCount(Department $department): int
    {
        return User::where('role', 'student_intern')->where('department', $department)->whereHas('dtrEntries')->count();
    }

    public static function currentlyOnDutyCount(Department $department): int
    {
        return DtrEntry::whereNull('time_out')
            ->whereHas('user', fn ($query) => $query->where('role', 'student_intern')->where('department', $department))
            ->distinct('user_id')
            ->count('user_id');
    }

    public static function presentTodayCount(Department $department): int
    {
        return DtrEntry::whereDate('time_in', today())
            ->whereHas('user', fn ($query) => $query->where('role', 'student_intern')->where('department', $department))
            ->distinct('user_id')
            ->count('user_id');
    }

    /**
     * Assigned students with a DTR entry today, sorted most-recent
     * time-in first. Each student carries only their latest entry from
     * today (a student can clock in/out more than once in a day).
     */
    public static function presentTodayStudents(Department $department): Collection
    {
        return User::where('role', 'student_intern')
            ->where('department', $department)
            ->whereHas('dtrEntries', fn ($query) => $query->whereDate('time_in', today()))
            ->with(['studentProfile', 'dtrEntries' => fn ($query) => $query->whereDate('time_in', today())->latest('time_in')->limit(1)])
            ->get()
            ->sortByDesc(fn (User $student) => $student->dtrEntries->first()?->time_in)
            ->values();
    }

    /**
     * Assigned students with no DTR entry today, sorted so the students who
     * have gone longest without timing in appear first (never-logged-in
     * students first, then oldest last time-in). This is a rough proxy for
     * "absent" — the system has no concept of a required schedule, so it
     * can't distinguish an actual absence from a student who's already
     * completed their hours or is on approved leave.
     */
    public static function absentTodayStudents(Department $department): Collection
    {
        return User::where('role', 'student_intern')
            ->where('department', $department)
            ->whereDoesntHave('dtrEntries', fn ($query) => $query->whereDate('time_in', today()))
            ->with(['studentProfile', 'dtrEntries' => fn ($query) => $query->latest('time_in')->limit(1)])
            ->get()
            ->sortBy([
                fn (User $a, User $b) => ($b->dtrEntries->first() === null) <=> ($a->dtrEntries->first() === null),
                fn (User $a, User $b) => ($a->dtrEntries->first()?->time_in ?? today()) <=> ($b->dtrEntries->first()?->time_in ?? today()),
            ])
            ->values();
    }

    public static function pendingReportsCount(Department $department): int
    {
        return User::where('role', 'student_intern')
            ->where('department', $department)
            ->whereHas('dtrEntries', fn ($query) => $query->whereDate('time_in', today())->whereNotNull('time_out'))
            ->whereDoesntHave('accomplishmentReports', fn ($query) => $query->whereDate('report_date', today()))
            ->count();
    }

    public static function totalHoursLogged(Department $department): int
    {
        return intdiv(self::completedSeconds($department), 3600);
    }

    public static function hoursLoggedToday(Department $department): int
    {
        return intdiv(self::completedSeconds($department, fn ($query) => $query->whereDate('time_in', today())), 3600);
    }

    /**
     * Average, across students with at least one eligible day, of
     * (reports submitted / days with a completed DTR entry).
     */
    public static function avgComplianceRatePercent(Department $department): int
    {
        $students = User::where('role', 'student_intern')->where('department', $department)->get();

        $rates = $students->map(function (User $student) {
            $eligibleDays = $student->dtrEntries()
                ->whereNotNull('time_out')
                ->get()
                ->map(fn (DtrEntry $entry) => $entry->time_in->toDateString())
                ->unique()
                ->count();

            if ($eligibleDays === 0) {
                return null;
            }

            $submittedReports = $student->accomplishmentReports()->count();

            return min($submittedReports / $eligibleDays, 1) * 100;
        })->filter(fn ($rate) => $rate !== null);

        if ($rates->isEmpty()) {
            return 0;
        }

        return (int) round($rates->avg());
    }

    /**
     * Merges the most recent DTR entries and accomplishment reports across
     * all students into one reverse-chronological feed. There's no
     * dedicated activity-log table, so this is derived live from the two
     * event-producing tables that already exist.
     */
    public static function recentActivity(Department $department, int $limit = 5): Collection
    {
        $dtrEvents = DtrEntry::with('user')
            ->whereHas('user', fn ($query) => $query->where('role', 'student_intern')->where('department', $department))
            ->orderByDesc('time_in')
            ->limit($limit)
            ->get()
            ->map(fn (DtrEntry $entry) => [
                'text' => "{$entry->user->name} timed in for their shift.",
                'time' => $entry->time_in,
                'icon' => 'clock',
                'badge' => 'bg-success/10 text-success',
            ]);

        $reportEvents = AccomplishmentReport::with('user')
            ->whereHas('user', fn ($query) => $query->where('role', 'student_intern')->where('department', $department))
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get()
            ->map(fn (AccomplishmentReport $report) => [
                'text' => "{$report->user->name} submitted an accomplishment report.",
                'time' => $report->created_at,
                'icon' => 'document-text',
                'badge' => 'bg-gold/10 text-gold',
            ]);

        return $dtrEvents->concat($reportEvents)
            ->sortByDesc('time')
            ->take($limit)
            ->map(fn (array $event) => [
                'text' => $event['text'],
                'time' => $event['time']->diffForHumans(),
                'icon' => $event['icon'],
                'badge' => $event['badge'],
            ])
            ->values();
    }

    private static function completedSeconds(Department $department, ?\Closure $scope = null): int
    {
        $query = DtrEntry::whereNotNull('time_out')
            ->whereHas('user', fn ($query) => $query->where('role', 'student_intern')->where('department', $department));

        if ($scope) {
            $scope($query);
        }

        return $query->get()->sum(fn (DtrEntry $entry) => $entry->durationInSeconds());
    }
}

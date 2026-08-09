<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\MissedReportNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class NotifyMissedReports extends Command
{
    /**
     * @var string
     */
    protected $signature = 'app:notify-missed-reports';

    /**
     * @var string
     */
    protected $description = 'Notify Deans about students who completed a DTR entry yesterday but did not submit their required daily report';

    public function handle(): void
    {
        $yesterday = today()->subDay();

        $deans = User::where('role', 'dean')->get();

        if ($deans->isEmpty()) {
            return;
        }

        $missedStudents = User::where('role', 'student_intern')
            ->whereHas('dtrEntries', fn ($query) => $query->whereDate('time_in', $yesterday)->whereNotNull('time_out'))
            ->whereDoesntHave('accomplishmentReports', fn ($query) => $query->whereDate('report_date', $yesterday))
            ->get();

        foreach ($missedStudents as $student) {
            Notification::send($deans, new MissedReportNotification($student, $yesterday));
        }

        $this->info("Notified deans about {$missedStudents->count()} missed report(s) for {$yesterday->toDateString()}.");
    }
}

<?php

namespace App\Http\Controllers\Dean;

use App\Http\Controllers\Controller;
use App\Models\AccomplishmentReport;

class AccomplishmentReportController extends Controller
{
    public function index()
    {
        $department = auth()->user()->department;

        $reports = AccomplishmentReport::with('user')
            ->whereHas('user', fn ($query) => $query->where('role', 'student_intern')->where('department', $department))
            ->orderByDesc('report_date')
            ->get()
            ->map(fn (AccomplishmentReport $report) => [
                'name' => $report->user->name,
                'date' => $report->report_date->format('M j, Y'),
                'dateIso' => $report->report_date->toDateString(),
                'activitiesPerformed' => $report->activities_performed,
                'problemsEncountered' => $report->problems_encountered,
                'learningsAcquired' => $report->learnings_acquired,
                'photoUrl' => $report->photoUrl(),
            ]);

        return view('dean.reports', [
            'reports' => $reports,
            'totalCount' => $reports->count(),
            'submittedTodayCount' => $reports->where('dateIso', today()->toDateString())->count(),
        ]);
    }
}

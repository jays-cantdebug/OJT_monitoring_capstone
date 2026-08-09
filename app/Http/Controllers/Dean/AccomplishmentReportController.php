<?php

namespace App\Http\Controllers\Dean;

use App\Http\Controllers\Controller;
use App\Models\AccomplishmentReport;

class AccomplishmentReportController extends Controller
{
    public function index()
    {
        $reports = AccomplishmentReport::with('user')
            ->whereHas('user', fn ($query) => $query->where('role', 'student_intern'))
            ->orderByDesc('report_date')
            ->get()
            ->map(fn (AccomplishmentReport $report) => [
                'name' => $report->user->name,
                'date' => $report->report_date->format('M j, Y'),
                'dateIso' => $report->report_date->toDateString(),
                'summary' => $report->description,
                'photoUrl' => $report->photoUrl(),
            ]);

        return view('dean.reports', [
            'reports' => $reports,
            'totalCount' => $reports->count(),
            'submittedTodayCount' => $reports->where('dateIso', today()->toDateString())->count(),
        ]);
    }
}

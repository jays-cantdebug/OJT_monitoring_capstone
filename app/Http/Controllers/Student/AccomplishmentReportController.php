<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AccomplishmentReport;
use App\Models\User;
use App\Notifications\ReportSubmittedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class AccomplishmentReportController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $reports = $user->accomplishmentReports()
            ->orderByDesc('report_date')
            ->get();

        $todayReport = $reports->first(fn (AccomplishmentReport $report) => $report->report_date->isToday());

        $hasCompletedDtrEntryToday = $user->dtrEntries()
            ->whereDate('time_in', today())
            ->whereNotNull('time_out')
            ->exists();

        return view('student.reports', compact('reports', 'todayReport', 'hasCompletedDtrEntryToday'));
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        $alreadySubmittedToday = $user->accomplishmentReports()
            ->whereDate('report_date', today())
            ->exists();

        abort_if($alreadySubmittedToday, 422, 'You have already submitted a report for today.');

        $hasCompletedDtrEntryToday = $user->dtrEntries()
            ->whereDate('time_in', today())
            ->whereNotNull('time_out')
            ->exists();

        abort_unless($hasCompletedDtrEntryToday, 422, 'Complete your Time In and Time Out for today before submitting a report.');

        $validated = $request->validate([
            'description' => ['required', 'string', 'max:5000'],
            'photo' => ['required', 'image', 'max:5120'],
        ]);

        $photoPath = $request->file('photo')->store('accomplishment-reports', 'public');

        $report = AccomplishmentReport::create([
            'user_id' => $user->id,
            'report_date' => today(),
            'description' => $validated['description'],
            'photo_path' => $photoPath,
        ]);

        $deans = User::where('role', 'dean')->get();
        Notification::send($deans, new ReportSubmittedNotification($report));

        return redirect()->route('student.reports')->with('status', 'Your report was submitted.');
    }
}

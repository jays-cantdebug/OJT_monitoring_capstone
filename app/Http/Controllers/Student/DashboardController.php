<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $openEntry = $user->openDtrEntry();

        $daysLoggedThisMonth = $user->dtrEntries()
            ->whereYear('time_in', now()->year)
            ->whereMonth('time_in', now()->month)
            ->get()
            ->map(fn ($entry) => $entry->time_in->toDateString())
            ->unique()
            ->count();

        $recentEntries = $user->dtrEntries()
            ->orderByDesc('time_in')
            ->limit(3)
            ->get();

        $hasSubmittedReportToday = $user->accomplishmentReports()
            ->whereDate('report_date', today())
            ->exists();

        $hasCompletedDtrEntryToday = $user->dtrEntries()
            ->whereDate('time_in', today())
            ->whereNotNull('time_out')
            ->exists();

        return view('student.dashboard', compact('openEntry', 'daysLoggedThisMonth', 'recentEntries', 'hasSubmittedReportToday', 'hasCompletedDtrEntryToday'));
    }
}

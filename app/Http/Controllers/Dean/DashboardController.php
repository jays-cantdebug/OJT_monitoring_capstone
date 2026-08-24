<?php

namespace App\Http\Controllers\Dean;

use App\Http\Controllers\Controller;
use App\Support\StudentMetrics;

class DashboardController extends Controller
{
    public function index()
    {
        $department = auth()->user()->department;

        return view('dean.dashboard', [
            'assignedCount' => StudentMetrics::assignedCount($department),
            'activeCount' => StudentMetrics::activeCount($department),
            'totalHoursLogged' => StudentMetrics::totalHoursLogged($department),
            'onDutyCount' => StudentMetrics::currentlyOnDutyCount($department),
            'pendingReportsCount' => StudentMetrics::pendingReportsCount($department),
            'avgComplianceRate' => StudentMetrics::avgComplianceRatePercent($department),
            'recentActivity' => StudentMetrics::recentActivity($department),
        ]);
    }
}

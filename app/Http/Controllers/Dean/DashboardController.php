<?php

namespace App\Http\Controllers\Dean;

use App\Http\Controllers\Controller;
use App\Support\StudentMetrics;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dean.dashboard', [
            'assignedCount' => StudentMetrics::assignedCount(),
            'activeCount' => StudentMetrics::activeCount(),
            'totalHoursLogged' => StudentMetrics::totalHoursLogged(),
            'onDutyCount' => StudentMetrics::currentlyOnDutyCount(),
            'pendingReportsCount' => StudentMetrics::pendingReportsCount(),
            'avgComplianceRate' => StudentMetrics::avgComplianceRatePercent(),
            'recentActivity' => StudentMetrics::recentActivity(),
        ]);
    }
}

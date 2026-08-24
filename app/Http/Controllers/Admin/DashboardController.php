<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Department;
use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use App\Support\StudentMetrics;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $departmentRows = collect(Department::cases())->map(fn (Department $department) => [
            'department' => $department,
            'studentCount' => StudentMetrics::assignedCount($department),
            'deanCount' => User::where('role', 'dean')->where('department', $department)->count(),
        ]);

        $recentActivity = AuditLog::with(['actor', 'subject'])
            ->latest('created_at')
            ->limit(10)
            ->get();

        return view('admin.dashboard', [
            'departmentRows' => $departmentRows,
            'totalStudents' => $departmentRows->sum('studentCount'),
            'totalDeans' => $departmentRows->sum('deanCount'),
            'recentActivity' => $recentActivity,
        ]);
    }
}

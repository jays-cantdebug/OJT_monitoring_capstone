<?php

namespace App\Http\Controllers\Dean;

use App\Http\Controllers\Controller;
use App\Models\DtrEntry;
use App\Models\User;
use App\Support\StudentMetrics;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $department = $request->user()->department;

        $entries = DtrEntry::with('user')
            ->whereHas('user', fn ($query) => $query->where('role', 'student_intern')->where('department', $department))
            ->when($request->filled('student_id'), fn ($query) => $query->where('user_id', $request->input('student_id')))
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->whereHas('user', fn ($q) => $q->where('name', 'like', '%'.$request->input('search').'%'));
            })
            ->when($request->filled('date'), fn ($query) => $query->whereDate('time_in', $request->input('date')))
            ->orderByDesc('time_in')
            ->get();

        $students = User::where('role', 'student_intern')->where('department', $department)->orderBy('name')->get(['id', 'name']);

        $assignedCount = StudentMetrics::assignedCount($department);
        $presentTodayCount = StudentMetrics::presentTodayCount($department);
        $presentStudents = StudentMetrics::presentTodayStudents($department);
        $absentStudents = StudentMetrics::absentTodayStudents($department);

        return view('dean.attendance', [
            'entries' => $entries,
            'students' => $students,
            'assignedCount' => $assignedCount,
            'presentTodayCount' => $presentTodayCount,
            'absentTodayCount' => max($assignedCount - $presentTodayCount, 0),
            'presentStudents' => $presentStudents,
            'absentStudents' => $absentStudents,
            'onDutyCount' => StudentMetrics::currentlyOnDutyCount($department),
            'hoursLoggedToday' => StudentMetrics::hoursLoggedToday($department),
            'avgComplianceRate' => StudentMetrics::avgComplianceRatePercent($department),
        ]);
    }
}

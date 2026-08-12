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
        $entries = DtrEntry::with('user')
            ->whereHas('user', fn ($query) => $query->where('role', 'student_intern'))
            ->when($request->filled('student_id'), fn ($query) => $query->where('user_id', $request->input('student_id')))
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->whereHas('user', fn ($q) => $q->where('name', 'like', '%'.$request->input('search').'%'));
            })
            ->when($request->filled('date'), fn ($query) => $query->whereDate('time_in', $request->input('date')))
            ->orderByDesc('time_in')
            ->get();

        $students = User::where('role', 'student_intern')->orderBy('name')->get(['id', 'name']);

        $assignedCount = StudentMetrics::assignedCount();
        $presentTodayCount = StudentMetrics::presentTodayCount();
        $presentStudents = StudentMetrics::presentTodayStudents();
        $absentStudents = StudentMetrics::absentTodayStudents();

        return view('dean.attendance', [
            'entries' => $entries,
            'students' => $students,
            'assignedCount' => $assignedCount,
            'presentTodayCount' => $presentTodayCount,
            'absentTodayCount' => max($assignedCount - $presentTodayCount, 0),
            'presentStudents' => $presentStudents,
            'absentStudents' => $absentStudents,
            'onDutyCount' => StudentMetrics::currentlyOnDutyCount(),
            'hoursLoggedToday' => StudentMetrics::hoursLoggedToday(),
            'avgComplianceRate' => StudentMetrics::avgComplianceRatePercent(),
        ]);
    }
}

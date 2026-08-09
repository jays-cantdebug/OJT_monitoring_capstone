<?php

namespace App\Http\Controllers\Dean;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dean\ExportAttendanceReportRequest;
use App\Models\DtrEntry;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class ReportExportController extends Controller
{
    public function index(ExportAttendanceReportRequest $request)
    {
        $students = User::where('role', 'student_intern')->orderBy('name')->get(['id', 'name']);

        return view('dean.reports-export', [
            'students' => $students,
            ...$this->buildReport($request),
        ]);
    }

    public function download(ExportAttendanceReportRequest $request): Response
    {
        $data = $this->buildReport($request);

        $pdf = Pdf::loadView('pdf.attendance-summary', $data);

        return $pdf->download('attendance-summary-'.now()->format('Y-m-d').'.pdf');
    }

    /**
     * @return array<string, mixed>
     */
    private function buildReport(ExportAttendanceReportRequest $request): array
    {
        $validated = $request->validated();

        $entries = DtrEntry::with('user')
            ->whereHas('user', fn ($query) => $query->where('role', 'student_intern'))
            ->when($validated['student_id'] ?? null, fn ($query, $studentId) => $query->where('user_id', $studentId))
            ->when($validated['date_from'] ?? null, fn ($query, $date) => $query->whereDate('time_in', '>=', $date))
            ->when($validated['date_to'] ?? null, fn ($query, $date) => $query->whereDate('time_in', '<=', $date))
            ->orderByDesc('time_in')
            ->get();

        $totalSeconds = $entries
            ->filter(fn (DtrEntry $entry) => $entry->time_out)
            ->sum(fn (DtrEntry $entry) => $entry->durationInSeconds());

        $studentName = ($validated['student_id'] ?? null)
            ? User::find($validated['student_id'])?->name
            : null;

        $filterLabel = collect([
            $studentName ? "Student: {$studentName}" : 'Student: All Students',
            ($validated['date_from'] ?? null) ? 'From: '.$validated['date_from'] : null,
            ($validated['date_to'] ?? null) ? 'To: '.$validated['date_to'] : null,
        ])->filter()->implode(' | ');

        return [
            'entries' => $entries,
            'totalHours' => intdiv((int) $totalSeconds, 3600),
            'totalMinutes' => intdiv((int) $totalSeconds % 3600, 60),
            'filterLabel' => $filterLabel,
        ];
    }
}

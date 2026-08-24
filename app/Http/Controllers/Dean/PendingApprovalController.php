<?php

namespace App\Http\Controllers\Dean;

use App\Enums\AuditAction;
use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class PendingApprovalController extends Controller
{
    public function index()
    {
        $pendingStudents = User::where('role', 'student_intern')
            ->where('status', 'pending')
            ->where('department', auth()->user()->department)
            ->orderBy('created_at')
            ->get();

        return view('dean.pending-approvals', compact('pendingStudents'));
    }

    public function approve(User $user): RedirectResponse
    {
        abort_unless($user->isStudentIntern() && $user->isPending() && $user->department === auth()->user()->department, 404);

        $user->update(['status' => 'approved']);

        AuditLog::record(auth()->user(), $user, AuditAction::ApprovedStudentAccount, [
            'status' => ['from' => 'pending', 'to' => 'approved'],
        ]);

        return redirect()->route('dean.pending-approvals')
            ->with('status', "{$user->name}'s account was approved.");
    }

    public function reject(User $user): RedirectResponse
    {
        abort_unless($user->isStudentIntern() && $user->isPending() && $user->department === auth()->user()->department, 404);

        $user->update(['status' => 'rejected']);

        AuditLog::record(auth()->user(), $user, AuditAction::RejectedStudentAccount, [
            'status' => ['from' => 'pending', 'to' => 'rejected'],
        ]);

        return redirect()->route('dean.pending-approvals')
            ->with('status', "{$user->name}'s account was rejected.");
    }
}

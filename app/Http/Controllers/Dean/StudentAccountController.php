<?php

namespace App\Http\Controllers\Dean;

use App\Enums\Department;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dean\CreateStudentAccountRequest;
use App\Models\User;
use App\Notifications\StudentAccountCreatedNotification;
use Illuminate\Support\Str;
use Illuminate\View\View;

class StudentAccountController extends Controller
{
    public function index()
    {
        return view('dean.students', [
            'students' => $this->students(auth()->user()->department),
        ]);
    }

    public function create()
    {
        return view('dean.students.create');
    }

    public function store(CreateStudentAccountRequest $request): View
    {
        $password = Str::password(12);

        $student = User::create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'password' => $password,
            'role' => 'student_intern',
            'department' => $request->user()->department,
        ]);

        $student->notify(new StudentAccountCreatedNotification);

        // Rendered directly rather than flashed through a redirect: a
        // one-time credential like this must never round-trip through a
        // session flash (fragile across server/browser timing) or a URL
        // query string (leaks into history/logs). Rendering it straight
        // into this response keeps it server-side and one-shot for real.
        return view('dean.students', [
            'students' => $this->students($request->user()->department),
            'created' => [
                'name' => $student->name,
                'email' => $student->email,
                'password' => $password,
            ],
        ]);
    }

    private function students(Department $department)
    {
        // Self-registered accounts stay off this list until approved (see
        // PendingApprovalController) - this list means "real active
        // interns," not "every account that exists."
        return User::where('role', 'student_intern')
            ->where('status', 'approved')
            ->where('department', $department)
            ->with('studentProfile')
            ->orderBy('name')
            ->get()
            ->map(fn (User $student) => [
                'id' => $student->id,
                'name' => $student->name,
                'avatarUrl' => $student->avatarUrl(),
                'company' => $student->studentProfile?->company_name,
                'onDuty' => $student->openDtrEntry() !== null,
            ]);
    }
}

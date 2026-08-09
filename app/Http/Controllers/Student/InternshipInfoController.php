<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\UpdateInternshipInfoRequest;
use App\Models\StudentProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class InternshipInfoController extends Controller
{
    public function show(Request $request)
    {
        $profile = $request->user()->studentProfile ?? new StudentProfile;

        return view('student.internship-info', compact('profile'));
    }

    public function update(UpdateInternshipInfoRequest $request): RedirectResponse
    {
        StudentProfile::updateOrCreate(
            ['user_id' => $request->user()->id],
            $request->validated(),
        );

        return redirect()->route('student.internship-info')->with('status', 'Internship info updated.');
    }
}

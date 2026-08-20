<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\UpdatePersonalInfoRequest;
use App\Models\StudentProfile;
use Illuminate\Http\RedirectResponse;

class PersonalInfoController extends Controller
{
    public function update(UpdatePersonalInfoRequest $request): RedirectResponse
    {
        StudentProfile::updateOrCreate(
            ['user_id' => $request->user()->id],
            $request->validated(),
        );

        return redirect()->route('student.profile')->with('status', 'Personal information updated.');
    }
}

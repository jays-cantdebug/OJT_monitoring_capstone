<?php

namespace Tests\Feature;

use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonalInfoTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_update_personal_and_guardian_info(): void
    {
        $student = User::factory()->create();

        $response = $this->actingAs($student)->put('/student/personal-info', [
            'personal_email' => 'juan.personal@example.com',
            'address' => '123 Rizal St, Cabadbaran City',
            'parent_name' => 'Pedro Dela Cruz',
            'parent_contact' => '09171234567',
            'guardian_name' => 'Maria Dela Cruz',
            'guardian_contact' => '09179876543',
        ]);

        $response->assertRedirect(route('student.profile'));

        $profile = StudentProfile::where('user_id', $student->id)->first();
        $this->assertNotNull($profile);
        $this->assertSame('juan.personal@example.com', $profile->personal_email);
        $this->assertSame('123 Rizal St, Cabadbaran City', $profile->address);
        $this->assertSame('Pedro Dela Cruz', $profile->parent_name);
        $this->assertSame('09171234567', $profile->parent_contact);
        $this->assertSame('Maria Dela Cruz', $profile->guardian_name);
        $this->assertSame('09179876543', $profile->guardian_contact);
    }

    public function test_dean_sees_students_personal_info_on_the_show_page(): void
    {
        $dean = User::factory()->dean()->create();
        $student = User::factory()->create(['name' => 'Juan Dela Cruz']);
        StudentProfile::create([
            'user_id' => $student->id,
            'guardian_name' => 'Maria Dela Cruz',
        ]);

        $response = $this->actingAs($dean)->get(route('dean.students.show', $student));

        $response->assertOk();
        $response->assertSee('Maria Dela Cruz');
    }
}

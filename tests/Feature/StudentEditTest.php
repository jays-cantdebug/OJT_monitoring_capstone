<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentEditTest extends TestCase
{
    use RefreshDatabase;

    public function test_dean_can_edit_student_name_and_verification_status(): void
    {
        $dean = User::factory()->dean()->create();
        $student = User::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($dean)->put(route('dean.students.update', $student), [
            'name' => 'New Name',
            'is_verified' => '1',
        ]);

        $response->assertRedirect(route('dean.students.show', $student));

        $student->refresh();
        $this->assertSame('New Name', $student->name);
        $this->assertTrue($student->studentProfile->is_verified);
    }

    public function test_editing_a_student_records_an_audit_log_entry_with_the_diff(): void
    {
        $dean = User::factory()->dean()->create();
        $student = User::factory()->create(['name' => 'Old Name']);
        StudentProfile::create(['user_id' => $student->id, 'is_verified' => false]);

        $this->actingAs($dean)->put(route('dean.students.update', $student), [
            'name' => 'New Name',
            'is_verified' => '1',
        ]);

        $this->assertSame(1, AuditLog::count());
        $log = AuditLog::first();
        $this->assertSame($dean->id, $log->actor_id);
        $this->assertSame($student->id, $log->subject_id);
        $this->assertSame('Old Name', $log->changes['name']['from']);
        $this->assertSame('New Name', $log->changes['name']['to']);
        $this->assertFalse($log->changes['is_verified']['from']);
        $this->assertTrue($log->changes['is_verified']['to']);
    }

    public function test_no_audit_log_entry_when_nothing_actually_changed(): void
    {
        $dean = User::factory()->dean()->create();
        $student = User::factory()->create(['name' => 'Same Name']);

        $this->actingAs($dean)->put(route('dean.students.update', $student), [
            'name' => 'Same Name',
        ]);

        $this->assertSame(0, AuditLog::count());
    }

    public function test_student_cannot_edit_another_students_record(): void
    {
        $student = User::factory()->create();
        $otherStudent = User::factory()->create();

        $response = $this->actingAs($student)->get(route('dean.students.edit', $otherStudent));

        $response->assertForbidden();
    }
}

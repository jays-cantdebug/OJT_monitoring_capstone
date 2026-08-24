<?php

namespace Tests\Feature;

use App\Enums\AuditAction;
use App\Enums\Department;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuditLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful_login_is_audit_logged(): void
    {
        $student = User::factory()->create(['password' => bcrypt('password')]);

        $this->post('/login', [
            'email' => $student->email,
            'password' => 'password',
        ]);

        $log = AuditLog::where('subject_id', $student->id)->where('action', AuditAction::LoggedIn->value)->first();
        $this->assertNotNull($log);
        $this->assertSame($student->id, $log->actor_id);
    }

    public function test_pending_login_is_not_audit_logged_as_a_full_login(): void
    {
        $student = User::factory()->pending()->create(['password' => bcrypt('password')]);

        $this->post('/login', [
            'email' => $student->email,
            'password' => 'password',
        ]);

        $this->assertSame(0, AuditLog::where('action', AuditAction::LoggedIn->value)->count());
    }

    public function test_self_registration_is_audit_logged(): void
    {
        $this->post('/register', [
            'name' => 'Juan Dela Cruz',
            'email' => 'juan@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'department' => 'IT',
        ]);

        $student = User::where('email', 'juan@example.com')->first();
        $log = AuditLog::where('subject_id', $student->id)->where('action', AuditAction::SelfRegisteredAccount->value)->first();
        $this->assertNotNull($log);
    }

    public function test_admin_creating_a_dean_is_audit_logged(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post('/admin/deans', [
            'name' => 'Maria Santos',
            'email' => 'maria.dean@example.com',
            'department' => 'CRIM',
        ]);

        $newDean = User::where('email', 'maria.dean@example.com')->first();
        $log = AuditLog::where('subject_id', $newDean->id)->where('action', AuditAction::CreatedDeanAccount->value)->first();
        $this->assertNotNull($log);
        $this->assertSame($admin->id, $log->actor_id);
        $this->assertSame('CRIM', $log->changes['department']['to']);
    }

    public function test_dean_creating_a_student_is_audit_logged(): void
    {
        $dean = User::factory()->dean()->department(Department::HM)->create();

        $this->actingAs($dean)->post('/dean/students', [
            'name' => 'Juan Student',
            'email' => 'juan.student@example.com',
        ]);

        $student = User::where('email', 'juan.student@example.com')->first();
        $log = AuditLog::where('subject_id', $student->id)->where('action', AuditAction::CreatedStudentAccount->value)->first();
        $this->assertNotNull($log);
        $this->assertSame($dean->id, $log->actor_id);
    }

    public function test_approve_and_reject_are_audit_logged(): void
    {
        $dean = User::factory()->dean()->create();
        $pendingA = User::factory()->pending()->create();
        $pendingB = User::factory()->pending()->create();

        $this->actingAs($dean)->post(route('dean.pending-approvals.approve', $pendingA));
        $this->actingAs($dean)->post(route('dean.pending-approvals.reject', $pendingB));

        $this->assertNotNull(AuditLog::where('subject_id', $pendingA->id)->where('action', AuditAction::ApprovedStudentAccount->value)->first());
        $this->assertNotNull(AuditLog::where('subject_id', $pendingB->id)->where('action', AuditAction::RejectedStudentAccount->value)->first());
    }

    public function test_password_reset_is_audit_logged(): void
    {
        $dean = User::factory()->dean()->create();
        $student = User::factory()->create();

        $this->actingAs($dean)->post(route('dean.students.reset-password', $student));

        $log = AuditLog::where('subject_id', $student->id)->where('action', AuditAction::ResetStudentPassword->value)->first();
        $this->assertNotNull($log);
        $this->assertSame($dean->id, $log->actor_id);
    }

    public function test_only_admin_can_view_the_audit_logs_page(): void
    {
        $admin = User::factory()->admin()->create();
        $dean = User::factory()->dean()->create();
        $student = User::factory()->create();

        $this->actingAs($admin)->get('/admin/audit-logs')->assertOk();
        $this->actingAs($dean)->get('/admin/audit-logs')->assertForbidden();
        $this->actingAs($student)->get('/admin/audit-logs')->assertForbidden();
    }

    public function test_admin_dashboard_shows_correct_per_department_counts(): void
    {
        $admin = User::factory()->admin()->create();

        User::factory()->dean()->department(Department::IT)->create();
        User::factory()->dean()->department(Department::IT)->create();
        User::factory()->count(3)->department(Department::IT)->create();

        User::factory()->dean()->department(Department::CRIM)->create();
        User::factory()->count(2)->department(Department::CRIM)->create();

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertOk();
        $response->assertViewHas('departmentRows', function ($rows) {
            $it = $rows->firstWhere('department', Department::IT);
            $crim = $rows->firstWhere('department', Department::CRIM);

            return $it['studentCount'] === 3 && $it['deanCount'] === 2
                && $crim['studentCount'] === 2 && $crim['deanCount'] === 1;
        });
        $response->assertViewHas('totalStudents', 5);
        $response->assertViewHas('totalDeans', 3);
    }
}

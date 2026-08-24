<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SelfRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registering_creates_a_pending_student_account(): void
    {
        $response = $this->post('/register', [
            'name' => 'Juan Dela Cruz',
            'email' => 'juan@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'department' => 'IT',
        ]);

        $response->assertRedirect(route('pending-approval'));

        $user = User::where('email', 'juan@example.com')->first();
        $this->assertNotNull($user);
        $this->assertSame('student_intern', $user->role);
        $this->assertTrue($user->isPending());
    }

    public function test_pending_student_is_redirected_to_pending_approval_on_login(): void
    {
        $student = User::factory()->pending()->create(['password' => bcrypt('password')]);

        $response = $this->post('/login', [
            'email' => $student->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('pending-approval'));
    }

    public function test_pending_student_cannot_reach_role_gated_routes_directly(): void
    {
        $student = User::factory()->pending()->create();

        $response = $this->actingAs($student)->get('/student/dashboard');

        $response->assertRedirect(route('pending-approval'));
    }

    public function test_rejected_student_cannot_log_in(): void
    {
        $student = User::factory()->rejected()->create(['password' => bcrypt('password')]);

        $response = $this->post('/login', [
            'email' => $student->email,
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_dean_can_approve_a_pending_student(): void
    {
        $dean = User::factory()->dean()->create();
        $student = User::factory()->pending()->create();

        $response = $this->actingAs($dean)->post(route('dean.pending-approvals.approve', $student));

        $response->assertRedirect(route('dean.pending-approvals'));
        $this->assertTrue($student->fresh()->isApproved());
    }

    public function test_dean_can_reject_a_pending_student(): void
    {
        $dean = User::factory()->dean()->create();
        $student = User::factory()->pending()->create();

        $response = $this->actingAs($dean)->post(route('dean.pending-approvals.reject', $student));

        $response->assertRedirect(route('dean.pending-approvals'));
        $this->assertTrue($student->fresh()->isRejected());
    }

    public function test_approved_student_can_log_in_and_reach_dashboard(): void
    {
        $student = User::factory()->create(['password' => bcrypt('password')]);

        $response = $this->post('/login', [
            'email' => $student->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('student.dashboard'));
    }

    public function test_dean_created_accounts_are_active_immediately_without_approval(): void
    {
        $dean = User::factory()->dean()->create();

        $response = $this->actingAs($dean)->post('/dean/students', [
            'name' => 'Maria Santos',
            'email' => 'maria@example.com',
        ]);

        $response->assertOk();

        $student = User::where('email', 'maria@example.com')->first();
        $this->assertNotNull($student);
        $this->assertTrue($student->isApproved());
    }

    public function test_pending_students_are_excluded_from_the_student_interns_list(): void
    {
        $dean = User::factory()->dean()->create();
        $approved = User::factory()->create(['name' => 'Approved Student']);
        $pending = User::factory()->pending()->create(['name' => 'Pending Student']);

        $response = $this->actingAs($dean)->get('/dean/students');

        $response->assertSee('Approved Student');
        $response->assertDontSee('Pending Student');
    }
}

<?php

namespace Tests\Feature;

use App\Enums\Department;
use App\Models\User;
use App\Notifications\DeanAccountCreatedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_a_dean_for_any_department(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post('/admin/deans', [
            'name' => 'Maria Santos',
            'email' => 'maria.dean@example.com',
            'department' => 'CRIM',
        ]);

        $response->assertOk();

        $newDean = User::where('email', 'maria.dean@example.com')->first();
        $this->assertNotNull($newDean);
        $this->assertTrue($newDean->isDean());
        $this->assertSame(Department::CRIM, $newDean->department);
    }

    public function test_regular_dean_cannot_view_the_admin_deans_list(): void
    {
        $dean = User::factory()->dean()->create();

        $response = $this->actingAs($dean)->get('/admin/deans');

        $response->assertForbidden();
    }

    public function test_regular_dean_cannot_create_a_dean_account(): void
    {
        $dean = User::factory()->dean()->create();

        $response = $this->actingAs($dean)->post('/admin/deans', [
            'name' => 'Maria Santos',
            'email' => 'maria.dean@example.com',
            'department' => 'CRIM',
        ]);

        $response->assertForbidden();
        $this->assertNull(User::where('email', 'maria.dean@example.com')->first());
    }

    public function test_student_cannot_reach_the_admin_area(): void
    {
        $student = User::factory()->create();

        $response = $this->actingAs($student)->get('/admin/deans');

        $response->assertForbidden();
    }

    public function test_admin_lands_on_the_deans_list_after_login(): void
    {
        $admin = User::factory()->admin()->create(['password' => bcrypt('password')]);

        $response = $this->post('/login', [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.deans'));
    }

    public function test_dean_created_notification_persists_and_is_readable_by_the_new_dean(): void
    {
        $dean = User::factory()->dean()->create();

        $dean->notify(new DeanAccountCreatedNotification);

        $this->assertSame(1, $dean->notifications()->count());
    }
}

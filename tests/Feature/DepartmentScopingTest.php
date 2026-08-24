<?php

namespace Tests\Feature;

use App\Enums\Department;
use App\Models\DtrEntry;
use App\Models\User;
use App\Notifications\MissedReportNotification;
use App\Notifications\ReportSubmittedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DepartmentScopingTest extends TestCase
{
    use RefreshDatabase;

    public function test_dean_created_student_inherits_the_creating_deans_department(): void
    {
        $dean = User::factory()->dean()->department(Department::HM)->create();

        $this->actingAs($dean)->post('/dean/students', [
            'name' => 'Juan Student',
            'email' => 'juan.student@example.com',
        ]);

        $student = User::where('email', 'juan.student@example.com')->first();
        $this->assertSame(Department::HM, $student->department);
    }

    public function test_student_interns_list_is_scoped_to_the_deans_own_department(): void
    {
        $itDean = User::factory()->dean()->department(Department::IT)->create();
        User::factory()->department(Department::IT)->create(['name' => 'IT Student']);
        User::factory()->department(Department::CRIM)->create(['name' => 'CRIM Student']);

        $response = $this->actingAs($itDean)->get('/dean/students');

        $response->assertSee('IT Student');
        $response->assertDontSee('CRIM Student');
    }

    public function test_dean_cannot_view_another_departments_student_by_guessing_the_url(): void
    {
        $itDean = User::factory()->dean()->department(Department::IT)->create();
        $crimStudent = User::factory()->department(Department::CRIM)->create();

        $response = $this->actingAs($itDean)->get(route('dean.students.show', $crimStudent));

        $response->assertNotFound();
    }

    public function test_dean_cannot_edit_another_departments_student_by_guessing_the_url(): void
    {
        $itDean = User::factory()->dean()->department(Department::IT)->create();
        $crimStudent = User::factory()->department(Department::CRIM)->create();

        $response = $this->actingAs($itDean)->put(route('dean.students.update', $crimStudent), [
            'name' => 'Renamed',
        ]);

        $response->assertNotFound();
    }

    public function test_report_submission_only_notifies_the_students_own_department_deans(): void
    {
        Storage::fake('public');
        Notification::fake();

        $itDean = User::factory()->dean()->department(Department::IT)->create();
        $crimDean = User::factory()->dean()->department(Department::CRIM)->create();
        $student = User::factory()->department(Department::IT)->create();

        DtrEntry::create([
            'user_id' => $student->id,
            'time_in' => today()->setTime(8, 0),
            'time_in_latitude' => 8.4542,
            'time_in_longitude' => 124.6319,
            'time_out' => today()->setTime(17, 0),
            'time_out_latitude' => 8.4542,
            'time_out_longitude' => 124.6319,
        ]);

        $this->actingAs($student)->post('/student/reports', [
            'activities_performed' => 'Worked on the monitoring dashboard.',
            'problems_encountered' => 'None today.',
            'learnings_acquired' => 'Learned how the ping pipeline works.',
            'photo' => UploadedFile::fake()->image('proof.jpg'),
        ]);

        Notification::assertSentTo($itDean, ReportSubmittedNotification::class);
        Notification::assertNotSentTo($crimDean, ReportSubmittedNotification::class);
    }

    public function test_missed_report_command_only_notifies_the_students_own_department_deans(): void
    {
        Notification::fake();

        $itDean = User::factory()->dean()->department(Department::IT)->create();
        $crimDean = User::factory()->dean()->department(Department::CRIM)->create();

        $yesterday = today()->subDay();

        $missedStudent = User::factory()->department(Department::IT)->create();
        DtrEntry::create([
            'user_id' => $missedStudent->id,
            'time_in' => $yesterday->copy()->setTime(8, 0),
            'time_in_latitude' => 8.4542,
            'time_in_longitude' => 124.6319,
            'time_out' => $yesterday->copy()->setTime(17, 0),
            'time_out_latitude' => 8.4542,
            'time_out_longitude' => 124.6319,
        ]);

        $this->artisan('app:notify-missed-reports')->assertExitCode(0);

        Notification::assertSentTo($itDean, MissedReportNotification::class);
        Notification::assertNotSentTo($crimDean, MissedReportNotification::class);
    }

    public function test_dean_can_only_authenticate_on_their_own_department_live_map_channel(): void
    {
        // The 'null' broadcaster used everywhere else in tests (see
        // phpunit.xml) never actually runs the routes/channels.php
        // authorization callback - its auth() is a no-op that always
        // succeeds. Point at the real reverb driver (private-channel auth
        // is signed locally against the configured secret; it never
        // contacts the Reverb server) so this test exercises the real
        // authorization gate. routes/channels.php already ran once at
        // boot against the (then-default) 'null' driver, so its channel
        // registrations aren't present on a freshly-resolved 'reverb'
        // driver instance - re-requiring the file registers them there too.
        config([
            'broadcasting.default' => 'reverb',
            'broadcasting.connections.reverb.key' => 'test-key',
            'broadcasting.connections.reverb.secret' => 'test-secret',
            'broadcasting.connections.reverb.app_id' => 'test-app-id',
        ]);
        require base_path('routes/channels.php');

        $itDean = User::factory()->dean()->department(Department::IT)->create();

        $ownChannel = $this->actingAs($itDean)->post('/broadcasting/auth', [
            'channel_name' => 'private-dean.live-map.IT',
            'socket_id' => '1234.5678',
        ]);
        $ownChannel->assertOk();

        $otherChannel = $this->actingAs($itDean)->post('/broadcasting/auth', [
            'channel_name' => 'private-dean.live-map.CRIM',
            'socket_id' => '1234.5678',
        ]);
        $otherChannel->assertForbidden();
    }
}

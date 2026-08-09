<?php

namespace Tests\Feature;

use App\Models\DtrEntry;
use App\Models\User;
use App\Notifications\MissedReportNotification;
use App\Notifications\ReportSubmittedNotification;
use App\Notifications\StudentAccountCreatedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class NotificationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_account_created_notification_persists_and_is_readable_by_the_student(): void
    {
        $student = User::factory()->create();

        $student->notify(new StudentAccountCreatedNotification);

        $this->assertSame(1, $student->notifications()->count());

        $response = $this->actingAs($student)->get('/student/notifications');

        $response->assertOk();
        $items = $response->viewData('items');
        $this->assertCount(1, $items);
        $this->assertTrue($items->first()['unread']);
    }

    public function test_submitting_an_accomplishment_report_notifies_every_dean(): void
    {
        Storage::fake('public');
        Notification::fake();

        $deans = User::factory()->dean()->count(2)->create();
        $student = User::factory()->create();

        DtrEntry::create([
            'user_id' => $student->id,
            'time_in' => today()->setTime(8, 0),
            'time_in_latitude' => 8.4542,
            'time_in_longitude' => 124.6319,
            'time_out' => today()->setTime(17, 0),
            'time_out_latitude' => 8.4542,
            'time_out_longitude' => 124.6319,
        ]);

        $response = $this->actingAs($student)->post('/student/reports', [
            'description' => 'Worked on the monitoring dashboard.',
            'photo' => UploadedFile::fake()->image('proof.jpg'),
        ]);

        $response->assertRedirect(route('student.reports'));

        Notification::assertSentTo($deans, ReportSubmittedNotification::class);
        Notification::assertCount(2);
    }

    public function test_notify_missed_reports_command_only_notifies_for_students_who_actually_missed(): void
    {
        Notification::fake();

        $deans = User::factory()->dean()->create();

        $yesterday = today()->subDay();

        $missedStudent = User::factory()->create(['name' => 'Missed Student']);
        DtrEntry::create([
            'user_id' => $missedStudent->id,
            'time_in' => $yesterday->copy()->setTime(8, 0),
            'time_in_latitude' => 8.4542,
            'time_in_longitude' => 124.6319,
            'time_out' => $yesterday->copy()->setTime(17, 0),
            'time_out_latitude' => 8.4542,
            'time_out_longitude' => 124.6319,
        ]);

        $compliantStudent = User::factory()->create(['name' => 'Compliant Student']);
        DtrEntry::create([
            'user_id' => $compliantStudent->id,
            'time_in' => $yesterday->copy()->setTime(8, 0),
            'time_in_latitude' => 8.4542,
            'time_in_longitude' => 124.6319,
            'time_out' => $yesterday->copy()->setTime(17, 0),
            'time_out_latitude' => 8.4542,
            'time_out_longitude' => 124.6319,
        ]);
        $compliantStudent->accomplishmentReports()->create([
            'report_date' => $yesterday,
            'description' => 'Did the work.',
            'photo_path' => 'accomplishment-reports/fake.jpg',
        ]);

        $this->artisan('app:notify-missed-reports')->assertExitCode(0);

        Notification::assertSentTo($deans, MissedReportNotification::class);
        Notification::assertCount(1);
    }
}

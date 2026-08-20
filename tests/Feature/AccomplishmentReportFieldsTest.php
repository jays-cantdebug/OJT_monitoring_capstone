<?php

namespace Tests\Feature;

use App\Models\AccomplishmentReport;
use App\Models\DtrEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AccomplishmentReportFieldsTest extends TestCase
{
    use RefreshDatabase;

    private function completeDtrEntryToday(User $student): void
    {
        DtrEntry::create([
            'user_id' => $student->id,
            'time_in' => today()->setTime(8, 0),
            'time_in_latitude' => 8.4542,
            'time_in_longitude' => 124.6319,
            'time_out' => today()->setTime(17, 0),
            'time_out_latitude' => 8.4542,
            'time_out_longitude' => 124.6319,
        ]);
    }

    public function test_report_requires_all_three_named_fields(): void
    {
        Storage::fake('public');
        $student = User::factory()->create();
        $this->completeDtrEntryToday($student);

        $response = $this->actingAs($student)->post('/student/reports', [
            'photo' => UploadedFile::fake()->image('proof.jpg'),
        ]);

        $response->assertSessionHasErrors(['activities_performed', 'problems_encountered', 'learnings_acquired']);
        $this->assertSame(0, AccomplishmentReport::count());
    }

    public function test_report_saves_all_three_fields_separately(): void
    {
        Storage::fake('public');
        $student = User::factory()->create();
        $this->completeDtrEntryToday($student);

        $response = $this->actingAs($student)->post('/student/reports', [
            'activities_performed' => 'Built the reporting dashboard.',
            'problems_encountered' => 'Flaky wifi in the afternoon.',
            'learnings_acquired' => 'Learned about Laravel form requests.',
            'photo' => UploadedFile::fake()->image('proof.jpg'),
        ]);

        $response->assertRedirect(route('student.reports'));

        $report = AccomplishmentReport::first();
        $this->assertSame('Built the reporting dashboard.', $report->activities_performed);
        $this->assertSame('Flaky wifi in the afternoon.', $report->problems_encountered);
        $this->assertSame('Learned about Laravel form requests.', $report->learnings_acquired);
    }
}

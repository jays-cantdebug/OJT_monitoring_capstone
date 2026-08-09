<?php

namespace Tests\Feature;

use App\Models\DtrEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportExportTest extends TestCase
{
    use RefreshDatabase;

    private function seedCompletedEntries(): array
    {
        $dean = User::factory()->dean()->create();
        $studentA = User::factory()->create(['name' => 'Student A']);
        $studentB = User::factory()->create(['name' => 'Student B']);

        // Student A: 9h + 4h30m = 13h30m
        DtrEntry::create([
            'user_id' => $studentA->id,
            'time_in' => today()->subDays(3)->setTime(8, 0),
            'time_in_latitude' => 8.4542,
            'time_in_longitude' => 124.6319,
            'time_out' => today()->subDays(3)->setTime(17, 0),
            'time_out_latitude' => 8.4542,
            'time_out_longitude' => 124.6319,
        ]);
        DtrEntry::create([
            'user_id' => $studentA->id,
            'time_in' => today()->subDays(2)->setTime(8, 15),
            'time_in_latitude' => 8.4542,
            'time_in_longitude' => 124.6319,
            'time_out' => today()->subDays(2)->setTime(12, 45),
            'time_out_latitude' => 8.4542,
            'time_out_longitude' => 124.6319,
        ]);

        // Student B: 8h45m
        DtrEntry::create([
            'user_id' => $studentB->id,
            'time_in' => today()->subDays(1)->setTime(7, 45),
            'time_in_latitude' => 8.4542,
            'time_in_longitude' => 124.6319,
            'time_out' => today()->subDays(1)->setTime(16, 30),
            'time_out_latitude' => 8.4542,
            'time_out_longitude' => 124.6319,
        ]);

        return [$dean, $studentA, $studentB];
    }

    public function test_totals_are_computed_correctly_across_all_students(): void
    {
        [$dean] = $this->seedCompletedEntries();

        $response = $this->actingAs($dean)->get('/dean/reports-export');

        $response->assertOk();
        $response->assertViewHas('totalHours', 22);
        $response->assertViewHas('totalMinutes', 15);
    }

    public function test_totals_can_be_filtered_to_a_single_student(): void
    {
        [$dean, $studentA] = $this->seedCompletedEntries();

        $response = $this->actingAs($dean)->get('/dean/reports-export?student_id='.$studentA->id);

        $response->assertOk();
        $response->assertViewHas('totalHours', 13);
        $response->assertViewHas('totalMinutes', 30);
        $response->assertViewHas('entries', fn ($entries) => $entries->every(
            fn (DtrEntry $entry) => $entry->user_id === $studentA->id
        ));
    }

    public function test_download_produces_a_real_pdf(): void
    {
        [$dean] = $this->seedCompletedEntries();

        $response = $this->actingAs($dean)->get('/dean/reports-export/download');

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
        $this->assertStringStartsWith('%PDF', $response->getContent());
    }
}

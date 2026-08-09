<?php

namespace Tests\Feature;

use App\Models\DtrEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DtrClockInOutTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_clock_in(): void
    {
        $student = User::factory()->create();

        $response = $this->actingAs($student)->post('/student/time/clock-in', [
            'latitude' => 8.4542,
            'longitude' => 124.6319,
        ]);

        $response->assertRedirect(route('student.time'));

        $this->assertSame(1, DtrEntry::count());
        $entry = DtrEntry::first();
        $this->assertSame($student->id, $entry->user_id);
        $this->assertNotNull($entry->time_in);
        $this->assertNull($entry->time_out);
    }

    public function test_student_cannot_clock_in_twice_while_already_on_duty(): void
    {
        $student = User::factory()->create();
        DtrEntry::create([
            'user_id' => $student->id,
            'time_in' => now(),
            'time_in_latitude' => 8.4542,
            'time_in_longitude' => 124.6319,
        ]);

        $response = $this->actingAs($student)->post('/student/time/clock-in', [
            'latitude' => 8.4542,
            'longitude' => 124.6319,
        ]);

        $response->assertStatus(422);
        $this->assertSame(1, DtrEntry::count());
    }

    public function test_student_can_clock_out_of_the_open_entry(): void
    {
        $student = User::factory()->create();
        $entry = DtrEntry::create([
            'user_id' => $student->id,
            'time_in' => now()->subHours(4),
            'time_in_latitude' => 8.4542,
            'time_in_longitude' => 124.6319,
        ]);

        $response = $this->actingAs($student)->post('/student/time/clock-out', [
            'latitude' => 8.46,
            'longitude' => 124.64,
        ]);

        $response->assertRedirect(route('student.time'));

        $entry->refresh();
        $this->assertNotNull($entry->time_out);
        $this->assertEqualsWithDelta(4 * 3600, $entry->durationInSeconds(), 5);
    }

    public function test_student_cannot_clock_out_without_an_open_entry(): void
    {
        $student = User::factory()->create();

        $response = $this->actingAs($student)->post('/student/time/clock-out', [
            'latitude' => 8.4542,
            'longitude' => 124.6319,
        ]);

        $response->assertStatus(422);
    }
}

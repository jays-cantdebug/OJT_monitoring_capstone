<?php

namespace Tests\Feature;

use App\Models\DtrEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DtrLocationHistoryPayloadTest extends TestCase
{
    use RefreshDatabase;

    public function test_payload_includes_both_pins_for_a_completed_entry(): void
    {
        $student = User::factory()->create();
        $entry = DtrEntry::create([
            'user_id' => $student->id,
            'time_in' => '2026-08-20 08:00:00',
            'time_in_latitude' => 8.4542,
            'time_in_longitude' => 124.6319,
            'time_out' => '2026-08-20 17:00:00',
            'time_out_latitude' => 9.1236,
            'time_out_longitude' => 125.5350,
        ]);

        $payload = $entry->locationHistoryPayload();

        $this->assertSame('Aug 20, 2026', $payload['date']);
        $this->assertSame(8.4542, $payload['timeIn']['lat']);
        $this->assertSame(124.6319, $payload['timeIn']['lng']);
        $this->assertSame('Time In · 8:00 AM', $payload['timeIn']['label']);
        $this->assertSame(9.1236, $payload['timeOut']['lat']);
        $this->assertSame(125.5350, $payload['timeOut']['lng']);
        $this->assertSame('Time Out · 5:00 PM', $payload['timeOut']['label']);
    }

    public function test_payload_has_null_time_out_for_an_open_entry(): void
    {
        $student = User::factory()->create();
        $entry = DtrEntry::create([
            'user_id' => $student->id,
            'time_in' => '2026-08-20 08:00:00',
            'time_in_latitude' => 8.4542,
            'time_in_longitude' => 124.6319,
        ]);

        $payload = $entry->locationHistoryPayload();

        $this->assertNull($payload['timeOut']);
        $this->assertSame(8.4542, $payload['timeIn']['lat']);
    }
}

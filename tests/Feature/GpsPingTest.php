<?php

namespace Tests\Feature;

use App\Models\DtrEntry;
use App\Models\GpsPing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GpsPingTest extends TestCase
{
    use RefreshDatabase;

    public function test_ping_is_rejected_when_student_is_not_on_duty(): void
    {
        $student = User::factory()->create();

        $response = $this->actingAs($student)->postJson('/student/gps-pings', [
            'latitude' => 8.4542,
            'longitude' => 124.6319,
        ]);

        $response->assertStatus(422);
        $this->assertSame(0, GpsPing::count());
    }

    public function test_ping_is_saved_against_the_open_dtr_entry_when_on_duty(): void
    {
        $student = User::factory()->create();
        $entry = DtrEntry::create([
            'user_id' => $student->id,
            'time_in' => now(),
            'time_in_latitude' => 8.4542,
            'time_in_longitude' => 124.6319,
        ]);

        $response = $this->actingAs($student)->postJson('/student/gps-pings', [
            'latitude' => 8.46,
            'longitude' => 124.64,
        ]);

        $response->assertOk()->assertJson(['status' => 'ok']);

        $this->assertSame(1, GpsPing::count());
        $ping = GpsPing::first();
        $this->assertSame($entry->id, $ping->dtr_entry_id);
        $this->assertSame($student->id, $ping->user_id);
        $this->assertEqualsWithDelta(8.46, $ping->latitude, 0.0001);
    }

    public function test_ping_still_saves_and_request_still_succeeds_when_broadcasting_is_unreachable(): void
    {
        // Points broadcasting at a real driver with a host nothing is
        // listening on, reproducing the exact outage this fallback exists
        // for (see GpsPingController::store) without mocking the broadcaster.
        config([
            'broadcasting.default' => 'reverb',
            'broadcasting.connections.reverb.options.host' => 'localhost',
            'broadcasting.connections.reverb.options.port' => 1,
            'broadcasting.connections.reverb.options.scheme' => 'http',
            'broadcasting.connections.reverb.options.useTLS' => false,
        ]);

        $student = User::factory()->create();
        DtrEntry::create([
            'user_id' => $student->id,
            'time_in' => now(),
            'time_in_latitude' => 8.4542,
            'time_in_longitude' => 124.6319,
        ]);

        $response = $this->actingAs($student)->postJson('/student/gps-pings', [
            'latitude' => 8.46,
            'longitude' => 124.64,
        ]);

        $response->assertOk()->assertJson(['status' => 'ok']);
        $this->assertSame(1, GpsPing::count());
    }
}

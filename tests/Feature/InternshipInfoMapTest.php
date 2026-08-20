<?php

namespace Tests\Feature;

use App\Models\DtrEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InternshipInfoMapTest extends TestCase
{
    use RefreshDatabase;

    public function test_page_loads_with_no_dtr_history(): void
    {
        $student = User::factory()->create();

        $response = $this->actingAs($student)->get('/student/internship-info');

        $response->assertOk();
        $this->assertNull($response->viewData('lastKnownLocation'));
        $this->assertNull($response->viewData('openEntry'));
    }

    public function test_off_duty_uses_last_completed_entrys_time_out_coordinate(): void
    {
        $student = User::factory()->create();
        DtrEntry::create([
            'user_id' => $student->id,
            'time_in' => now()->subHours(5),
            'time_in_latitude' => 8.4542,
            'time_in_longitude' => 124.6319,
            'time_out' => now()->subHours(1),
            'time_out_latitude' => 9.1236,
            'time_out_longitude' => 125.5350,
        ]);

        $response = $this->actingAs($student)->get('/student/internship-info');

        $response->assertOk();
        $location = $response->viewData('lastKnownLocation');
        $this->assertSame(9.1236, $location['latitude']);
        $this->assertSame(125.5350, $location['longitude']);
    }

    public function test_on_duty_uses_open_entrys_time_in_coordinate_when_no_ping_yet(): void
    {
        $student = User::factory()->create();
        DtrEntry::create([
            'user_id' => $student->id,
            'time_in' => now(),
            'time_in_latitude' => 8.4542,
            'time_in_longitude' => 124.6319,
        ]);

        $response = $this->actingAs($student)->get('/student/internship-info');

        $response->assertOk();
        $location = $response->viewData('lastKnownLocation');
        $this->assertSame(8.4542, $location['latitude']);
        $this->assertSame(124.6319, $location['longitude']);
        $this->assertNotNull($response->viewData('openEntry'));
    }
}

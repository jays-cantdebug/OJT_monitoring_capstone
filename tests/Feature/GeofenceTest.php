<?php

namespace Tests\Feature;

use App\Enums\AuditAction;
use App\Enums\Department;
use App\Models\AuditLog;
use App\Models\DtrEntry;
use App\Models\GpsPing;
use App\Models\StudentProfile;
use App\Models\User;
use App\Support\Geofence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GeofenceTest extends TestCase
{
    use RefreshDatabase;

    // Company pin used throughout. 0.001 deg of latitude is ~111 m, so
    // COMPANY_LAT + 0.0005 is ~56 m away (inside a 100 m fence) and
    // COMPANY_LAT + 0.005 is ~556 m away (outside it).
    private const COMPANY_LAT = 9.1236;

    private const COMPANY_LNG = 125.5350;

    private function studentWithPin(int $radius = 100): User
    {
        $student = User::factory()->create();
        StudentProfile::create([
            'user_id' => $student->id,
            'company_latitude' => self::COMPANY_LAT,
            'company_longitude' => self::COMPANY_LNG,
            'geofence_radius_m' => $radius,
        ]);

        return $student;
    }

    private function onDuty(User $student, float $latitude = self::COMPANY_LAT): DtrEntry
    {
        return DtrEntry::create([
            'user_id' => $student->id,
            'time_in' => now(),
            'time_in_latitude' => $latitude,
            'time_in_longitude' => self::COMPANY_LNG,
        ]);
    }

    public function test_distance_calculation_is_accurate(): void
    {
        $distance = Geofence::distanceInMeters(self::COMPANY_LAT, self::COMPANY_LNG, self::COMPANY_LAT + 0.001, self::COMPANY_LNG);

        $this->assertEqualsWithDelta(111.2, $distance, 0.5);
    }

    public function test_ping_inside_the_geofence_is_recorded_as_inside(): void
    {
        $student = $this->studentWithPin();
        $this->onDuty($student);

        $this->actingAs($student)->postJson('/student/gps-pings', [
            'latitude' => self::COMPANY_LAT + 0.0005,
            'longitude' => self::COMPANY_LNG,
        ])->assertOk();

        $ping = GpsPing::first();
        $this->assertFalse($ping->outside_geofence);
        $this->assertEqualsWithDelta(56, $ping->distance_from_company_m, 1);
    }

    public function test_ping_outside_the_geofence_is_still_saved_and_flagged(): void
    {
        $student = $this->studentWithPin();
        $this->onDuty($student);

        $this->actingAs($student)->postJson('/student/gps-pings', [
            'latitude' => self::COMPANY_LAT + 0.005,
            'longitude' => self::COMPANY_LNG,
        ])->assertOk()->assertJson(['status' => 'ok']);

        $ping = GpsPing::first();
        $this->assertTrue($ping->outside_geofence);
        $this->assertEqualsWithDelta(556, $ping->distance_from_company_m, 2);
    }

    public function test_the_students_own_radius_is_used(): void
    {
        $student = $this->studentWithPin(radius: 1000);
        $this->onDuty($student);

        $this->actingAs($student)->postJson('/student/gps-pings', [
            'latitude' => self::COMPANY_LAT + 0.005,
            'longitude' => self::COMPANY_LNG,
        ])->assertOk();

        $this->assertFalse(GpsPing::first()->outside_geofence);
    }

    public function test_ping_is_not_judged_when_no_pin_is_set(): void
    {
        $student = User::factory()->create();
        $this->onDuty($student);

        $this->actingAs($student)->postJson('/student/gps-pings', [
            'latitude' => self::COMPANY_LAT + 0.005,
            'longitude' => self::COMPANY_LNG,
        ])->assertOk();

        $ping = GpsPing::first();
        $this->assertNull($ping->outside_geofence);
        $this->assertNull($ping->distance_from_company_m);
    }

    public function test_time_in_outside_the_geofence_is_allowed_and_flagged(): void
    {
        $student = $this->studentWithPin();

        $this->actingAs($student)->post(route('student.time.clock-in'), [
            'latitude' => self::COMPANY_LAT + 0.005,
            'longitude' => self::COMPANY_LNG,
        ])->assertRedirect(route('student.time'));

        $entry = DtrEntry::first();
        $this->assertNotNull($entry);
        $this->assertTrue($entry->time_in_outside_geofence);
        $this->assertEqualsWithDelta(556, $entry->time_in_distance_from_company_m, 2);
    }

    public function test_moving_the_pin_does_not_rewrite_past_pings(): void
    {
        $student = $this->studentWithPin();
        $this->onDuty($student);

        $this->actingAs($student)->postJson('/student/gps-pings', [
            'latitude' => self::COMPANY_LAT + 0.005,
            'longitude' => self::COMPANY_LNG,
        ]);

        $student->studentProfile->update(['company_latitude' => self::COMPANY_LAT + 0.005]);

        $this->assertTrue(GpsPing::first()->outside_geofence);
    }

    public function test_dean_can_pin_a_company_location_and_it_is_audited(): void
    {
        $dean = User::factory()->dean()->create();
        $student = User::factory()->create();

        $this->actingAs($dean)->put(route('dean.students.update', $student), [
            'name' => $student->name,
            'company_latitude' => self::COMPANY_LAT,
            'company_longitude' => self::COMPANY_LNG,
            'geofence_radius_m' => 250,
        ])->assertRedirect(route('dean.students.show', $student));

        $profile = $student->fresh()->studentProfile;
        $this->assertEqualsWithDelta(self::COMPANY_LAT, (float) $profile->company_latitude, 0.0000001);
        $this->assertSame(250, $profile->geofence_radius_m);

        $log = AuditLog::where('action', AuditAction::UpdatedGeofence)->sole();
        $this->assertSame('none', $log->changes['company_location']['from']);
        $this->assertSame('100 m', $log->changes['geofence_radius']['from']);
        $this->assertSame('250 m', $log->changes['geofence_radius']['to']);
    }

    public function test_dean_can_remove_a_pin(): void
    {
        $dean = User::factory()->dean()->create();
        $student = $this->studentWithPin();

        $this->actingAs($dean)->put(route('dean.students.update', $student), [
            'name' => $student->name,
            'company_latitude' => '',
            'company_longitude' => '',
            'geofence_radius_m' => 100,
        ])->assertSessionHasNoErrors();

        $this->assertFalse($student->fresh()->studentProfile->hasGeofence());
    }

    public function test_saving_without_geofence_fields_leaves_the_pin_alone(): void
    {
        $dean = User::factory()->dean()->create();
        $student = $this->studentWithPin();

        $this->actingAs($dean)->put(route('dean.students.update', $student), [
            'name' => 'Renamed',
        ])->assertSessionHasNoErrors();

        $this->assertTrue($student->fresh()->studentProfile->hasGeofence());
        $this->assertSame(0, AuditLog::where('action', AuditAction::UpdatedGeofence)->count());
    }

    public function test_radius_outside_50m_to_1km_is_rejected(): void
    {
        $dean = User::factory()->dean()->create();
        $student = User::factory()->create();

        foreach ([49, 1001] as $radius) {
            $this->actingAs($dean)->put(route('dean.students.update', $student), [
                'name' => $student->name,
                'company_latitude' => self::COMPANY_LAT,
                'company_longitude' => self::COMPANY_LNG,
                'geofence_radius_m' => $radius,
            ])->assertSessionHasErrors('geofence_radius_m');
        }
    }

    public function test_half_a_pin_is_rejected(): void
    {
        $dean = User::factory()->dean()->create();
        $student = User::factory()->create();

        $this->actingAs($dean)->put(route('dean.students.update', $student), [
            'name' => $student->name,
            'company_latitude' => self::COMPANY_LAT,
            'company_longitude' => '',
            'geofence_radius_m' => 100,
        ])->assertSessionHasErrors('company_longitude');
    }

    public function test_dean_cannot_pin_another_departments_student(): void
    {
        $dean = User::factory()->dean()->create(['department' => Department::IT]);
        $student = User::factory()->create(['department' => Department::CRIM]);

        $this->actingAs($dean)->put(route('dean.students.update', $student), [
            'name' => $student->name,
            'company_latitude' => self::COMPANY_LAT,
            'company_longitude' => self::COMPANY_LNG,
            'geofence_radius_m' => 100,
        ])->assertNotFound();

        $this->assertNull($student->fresh()->studentProfile);
    }

    public function test_consecutive_outside_readings_are_grouped_into_one_event(): void
    {
        $dean = User::factory()->dean()->create();
        $student = $this->studentWithPin();
        $entry = $this->onDuty($student);

        // inside -> outside -> outside -> inside -> outside (still out)
        foreach ([false, true, true, false, true] as $minute => $outside) {
            GpsPing::create([
                'user_id' => $student->id,
                'dtr_entry_id' => $entry->id,
                'latitude' => self::COMPANY_LAT,
                'longitude' => self::COMPANY_LNG,
                'distance_from_company_m' => $outside ? 300 + $minute : 20,
                'outside_geofence' => $outside,
                'recorded_at' => now()->addMinutes($minute),
            ]);
        }

        $events = $this->actingAs($dean)
            ->get(route('dean.students.show', $student))
            ->assertOk()
            ->assertSee('Outside now')
            ->viewData('geofenceEvents');

        $this->assertCount(2, $events);

        // Newest first: the ongoing event, then the one that ended.
        $this->assertSame('ongoing', $events[0]['endedBy']);
        $this->assertSame('returned', $events[1]['endedBy']);
        $this->assertSame(2, $events[1]['readings']);
        $this->assertSame(302, $events[1]['maxDistance']);
    }

    public function test_live_map_includes_geofence_and_falls_back_to_time_in_result(): void
    {
        $dean = User::factory()->dean()->create();
        $student = $this->studentWithPin();
        DtrEntry::create([
            'user_id' => $student->id,
            'time_in' => now(),
            'time_in_latitude' => self::COMPANY_LAT + 0.005,
            'time_in_longitude' => self::COMPANY_LNG,
            'time_in_outside_geofence' => true,
        ]);

        $row = $this->actingAs($dean)->get(route('dean.live-map'))->assertOk()->viewData('onDuty')->first();

        $this->assertSame(100, $row['geofence']['radius']);
        $this->assertTrue($row['outsideGeofence']);
    }

    public function test_student_map_receives_their_geofence(): void
    {
        $student = $this->studentWithPin();

        $geofence = $this->actingAs($student)->get('/student/internship-info')->assertOk()->viewData('geofence');

        $this->assertSame(self::COMPANY_LAT, $geofence['latitude']);
        $this->assertSame(100, $geofence['radius']);
    }
}

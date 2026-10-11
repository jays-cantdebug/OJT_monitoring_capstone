<?php

namespace Tests\Feature;

use App\Enums\Department;
use App\Models\AccomplishmentReport;
use App\Models\DtrEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DailyReportRulesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Pinned here rather than relying on .env so the "today" boundary
        // tests are deterministic.
        config(['app.timezone' => 'Asia/Manila']);
        date_default_timezone_set('Asia/Manila');

        Storage::fake('public');
    }

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

    /**
     * @return array<string, mixed>
     */
    private function reportPayload(array $overrides = []): array
    {
        return array_merge([
            'activities_performed' => 'Built the reporting dashboard.',
            'problems_encountered' => 'Flaky wifi in the afternoon.',
            'learnings_acquired' => 'Learned about Laravel form requests.',
            'photo' => UploadedFile::fake()->image('proof.jpg'),
        ], $overrides);
    }

    public function test_report_for_today_is_accepted(): void
    {
        $student = User::factory()->create();
        $this->completeDtrEntryToday($student);

        $response = $this->actingAs($student)->post('/student/reports', $this->reportPayload([
            'report_date' => today()->toDateString(),
        ]));

        $response->assertRedirect(route('student.reports'));
        $response->assertSessionHasNoErrors();
        $this->assertSame(1, AccomplishmentReport::count());
        $this->assertTrue(AccomplishmentReport::first()->report_date->isSameDay(today()));
    }

    public function test_report_for_a_past_date_is_rejected_and_nothing_is_saved(): void
    {
        $student = User::factory()->create();
        $this->completeDtrEntryToday($student);

        $response = $this->actingAs($student)->post('/student/reports', $this->reportPayload([
            'report_date' => today()->subDay()->toDateString(),
        ]));

        $response->assertSessionHasErrors(['report_date' => "Reports can only be submitted for today's date."]);
        $this->assertSame(0, AccomplishmentReport::count());
    }

    public function test_report_for_a_future_date_is_rejected_and_nothing_is_saved(): void
    {
        $student = User::factory()->create();
        $this->completeDtrEntryToday($student);

        $response = $this->actingAs($student)->post('/student/reports', $this->reportPayload([
            'report_date' => today()->addDay()->toDateString(),
        ]));

        $response->assertSessionHasErrors(['report_date' => "Reports can only be submitted for today's date."]);
        $this->assertSame(0, AccomplishmentReport::count());
    }

    public function test_second_report_on_the_same_day_returns_422(): void
    {
        $student = User::factory()->create();
        $this->completeDtrEntryToday($student);

        $this->actingAs($student)->post('/student/reports', $this->reportPayload())
            ->assertRedirect(route('student.reports'));

        $this->actingAs($student)->post('/student/reports', $this->reportPayload())
            ->assertStatus(422);

        $this->assertSame(1, AccomplishmentReport::count());
    }

    public function test_intern_dashboard_notice_is_shown_before_submitting_and_hidden_after(): void
    {
        $student = User::factory()->create();

        $this->actingAs($student)->get('/student/dashboard')
            ->assertSee("You have not submitted today's daily report.", false)
            ->assertSee('Time In and Time Out first');

        $this->completeDtrEntryToday($student);

        $this->actingAs($student)->get('/student/dashboard')
            ->assertSee("You have not submitted today's daily report.", false)
            ->assertDontSee('Time In and Time Out first');

        $this->actingAs($student)->post('/student/reports', $this->reportPayload());

        $this->actingAs($student)->get('/student/dashboard')
            ->assertDontSee("You have not submitted today's daily report.", false)
            ->assertDontSee('Time In and Time Out first');
    }

    public function test_dean_sees_only_own_department_interns_with_completed_dtr_and_no_report(): void
    {
        $itDean = User::factory()->dean()->department(Department::IT)->create();

        $missing = User::factory()->department(Department::IT)->create(['name' => 'IT Missing Report']);
        $this->completeDtrEntryToday($missing);

        $submitted = User::factory()->department(Department::IT)->create(['name' => 'IT Submitted']);
        $this->completeDtrEntryToday($submitted);
        AccomplishmentReport::create([
            'user_id' => $submitted->id,
            'report_date' => today(),
            'activities_performed' => 'Done.',
            'problems_encountered' => 'None.',
            'learnings_acquired' => 'Plenty.',
            'photo_path' => 'accomplishment-reports/proof.jpg',
        ]);

        User::factory()->department(Department::IT)->create(['name' => 'IT No Shift']);

        $pending = User::factory()->department(Department::IT)->pending()->create(['name' => 'IT Pending']);
        $this->completeDtrEntryToday($pending);

        $otherDepartment = User::factory()->department(Department::CRIM)->create(['name' => 'CRIM Missing Report']);
        $this->completeDtrEntryToday($otherDepartment);

        $response = $this->actingAs($itDean)->get('/dean/dashboard');

        $response->assertOk();
        $response->assertSee('No Report Today (1)');
        $response->assertSee('IT Missing Report');
        $response->assertDontSee('CRIM Missing Report');
        $response->assertViewHas('studentsWithoutReportToday', fn ($students) => $students->pluck('name')->all() === ['IT Missing Report']);
    }

    public function test_today_follows_asia_manila_across_midnight(): void
    {
        $student = User::factory()->create();

        // 23:59:59 in Manila: the report belongs to Oct 11.
        $this->travelTo(Carbon::parse('2026-10-11 23:59:59', 'Asia/Manila'));
        $this->completeDtrEntryToday($student);
        $this->actingAs($student)->post('/student/reports', $this->reportPayload())
            ->assertRedirect(route('student.reports'));
        $this->assertSame('2026-10-11', AccomplishmentReport::first()->report_date->toDateString());

        $this->actingAs($student)->get('/student/dashboard')
            ->assertDontSee("You have not submitted today's daily report.", false);

        // Two seconds later it's Oct 12 in Manila: yesterday's report no
        // longer counts, and Oct 11 is now a past date.
        $this->travelTo(Carbon::parse('2026-10-12 00:00:01', 'Asia/Manila'));

        $this->actingAs($student)->get('/student/dashboard')
            ->assertSee("You have not submitted today's daily report.", false);

        $this->completeDtrEntryToday($student);
        $this->actingAs($student)->post('/student/reports', $this->reportPayload(['report_date' => '2026-10-11']))
            ->assertSessionHasErrors(['report_date' => "Reports can only be submitted for today's date."]);

        $this->actingAs($student)->post('/student/reports', $this->reportPayload(['report_date' => '2026-10-12']))
            ->assertSessionHasNoErrors();
        $this->assertSame(['2026-10-11', '2026-10-12'], AccomplishmentReport::orderBy('report_date')->get()->map(fn ($r) => $r->report_date->toDateString())->all());
    }

    public function test_report_date_uses_manila_date_even_when_utc_is_still_the_previous_day(): void
    {
        $student = User::factory()->create();

        // 16:30 UTC on Oct 11 is 00:30 on Oct 12 in Manila.
        $this->travelTo(Carbon::parse('2026-10-11 16:30:00', 'UTC'));
        $this->completeDtrEntryToday($student);

        $this->actingAs($student)->post('/student/reports', $this->reportPayload())
            ->assertRedirect(route('student.reports'));

        $this->assertSame('2026-10-12', AccomplishmentReport::first()->report_date->toDateString());
    }
}

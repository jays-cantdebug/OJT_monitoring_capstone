<?php

namespace App\Support;

use App\Models\DtrEntry;
use App\Models\GpsPing;
use App\Models\User;
use Illuminate\Support\Collection;

/**
 * Turns the per-ping geofence flags into "events" for the Dean's review:
 * a run of consecutive outside readings within one duty shift becomes a
 * single event (left at / back at / furthest distance), instead of one
 * row per 60-second ping.
 */
class GeofenceLog
{
    /**
     * How many of the most recent shifts with any outside reading to scan.
     */
    private const SHIFT_LIMIT = 30;

    /**
     * @return Collection<int, array{
     *     date: string,
     *     leftAt: \Carbon\CarbonInterface,
     *     backAt: ?\Carbon\CarbonInterface,
     *     endedBy: 'returned'|'time_out'|'ongoing',
     *     startedAtTimeIn: bool,
     *     maxDistance: int,
     *     readings: int,
     * }>
     */
    public static function eventsFor(User $student): Collection
    {
        return $student->dtrEntries()
            ->where(fn ($query) => $query
                ->where('time_in_outside_geofence', true)
                ->orWhereHas('gpsPings', fn ($pings) => $pings->where('outside_geofence', true)))
            ->with(['gpsPings' => fn ($pings) => $pings->whereNotNull('outside_geofence')->orderBy('recorded_at')])
            ->latest('time_in')
            ->limit(self::SHIFT_LIMIT)
            ->get()
            ->flatMap(fn (DtrEntry $entry) => array_reverse(self::eventsForShift($entry)))
            ->values();
    }

    /**
     * Readings with no result (no pin set at the time) were already
     * filtered out by the query above, so they neither start nor end an
     * event.
     *
     * @return array<int, array<string, mixed>>
     */
    private static function eventsForShift(DtrEntry $entry): array
    {
        $readings = $entry->gpsPings->map(fn (GpsPing $ping) => [
            'at' => $ping->recorded_at,
            'outside' => $ping->outside_geofence,
            'distance' => $ping->distance_from_company_m,
            'isTimeIn' => false,
        ]);

        if ($entry->time_in_outside_geofence !== null) {
            $readings->prepend([
                'at' => $entry->time_in,
                'outside' => $entry->time_in_outside_geofence,
                'distance' => $entry->time_in_distance_from_company_m,
                'isTimeIn' => true,
            ]);
        }

        $events = [];
        $current = null;

        foreach ($readings as $reading) {
            if ($reading['outside']) {
                $current ??= [
                    'date' => $entry->time_in->format('M j, Y'),
                    'leftAt' => $reading['at'],
                    'backAt' => null,
                    'endedBy' => 'ongoing',
                    'startedAtTimeIn' => $reading['isTimeIn'],
                    'maxDistance' => 0,
                    'readings' => 0,
                ];
                $current['maxDistance'] = max($current['maxDistance'], (int) $reading['distance']);
                $current['readings']++;
            } elseif ($current) {
                $current['backAt'] = $reading['at'];
                $current['endedBy'] = 'returned';
                $events[] = $current;
                $current = null;
            }
        }

        if ($current) {
            if ($entry->time_out) {
                $current['backAt'] = $entry->time_out;
                $current['endedBy'] = 'time_out';
            }
            $events[] = $current;
        }

        return $events;
    }
}

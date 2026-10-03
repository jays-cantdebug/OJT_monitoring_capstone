<?php

namespace App\Support;

use App\Models\StudentProfile;

/**
 * Log-only geofence check against the Dean-pinned company location. It
 * never blocks anything - callers store its result alongside the ping or
 * Time In it was computed for, so the Dean can review it later.
 */
class Geofence
{
    public const DEFAULT_RADIUS_M = 100;

    public const MIN_RADIUS_M = 50;

    public const MAX_RADIUS_M = 1000;

    private const EARTH_RADIUS_M = 6371000;

    /**
     * Both values are null when the student has no pin set - there's
     * nothing to measure against, which is different from "inside".
     *
     * @return array{distance: ?int, outside: ?bool}
     */
    public static function check(?StudentProfile $profile, float $latitude, float $longitude): array
    {
        if (! $profile?->hasGeofence()) {
            return ['distance' => null, 'outside' => null];
        }

        $distance = (int) round(self::distanceInMeters(
            (float) $profile->company_latitude,
            (float) $profile->company_longitude,
            $latitude,
            $longitude,
        ));

        return ['distance' => $distance, 'outside' => $distance > $profile->geofence_radius_m];
    }

    /**
     * Great-circle (haversine) distance - accurate to well under a meter at
     * the few-hundred-meter scale a geofence works at.
     */
    public static function distanceInMeters(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;

        return 2 * self::EARTH_RADIUS_M * asin(min(1, sqrt($a)));
    }
}

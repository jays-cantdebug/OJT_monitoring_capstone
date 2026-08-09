<?php

namespace App\Support;

/**
 * Shared validation rules for latitude/longitude payloads, used by both
 * DTR clock-in/clock-out and GPS ping submission so the two never drift.
 */
class CoordinateRules
{
    /**
     * @return array<string, array<int, string>>
     */
    public static function rules(): array
    {
        return [
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ];
    }
}

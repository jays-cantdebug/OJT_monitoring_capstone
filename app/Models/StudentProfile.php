<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentProfile extends Model
{
    protected $fillable = [
        'user_id',
        'company_name',
        'company_address',
        'company_latitude',
        'company_longitude',
        'geofence_radius_m',
        'supervisor_name',
        'supervisor_contact',
        'start_date',
        'end_date',
        'personal_email',
        'address',
        'parent_name',
        'parent_contact',
        'guardian_name',
        'guardian_contact',
        'is_verified',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'is_verified' => 'boolean',
            'geofence_radius_m' => 'integer',
        ];
    }

    public function hasGeofence(): bool
    {
        return $this->company_latitude !== null && $this->company_longitude !== null;
    }

    /**
     * Shaped for the Leaflet maps (Dean Live Map, the student's own map,
     * and the Dean's pin editor) - null when no pin is set.
     *
     * @return array{latitude: float, longitude: float, radius: int}|null
     */
    public function geofencePayload(): ?array
    {
        if (! $this->hasGeofence()) {
            return null;
        }

        return [
            'latitude' => (float) $this->company_latitude,
            'longitude' => (float) $this->company_longitude,
            'radius' => $this->geofence_radius_m,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

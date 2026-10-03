<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GpsPing extends Model
{
    protected $fillable = [
        'user_id',
        'dtr_entry_id',
        'latitude',
        'longitude',
        'distance_from_company_m',
        'outside_geofence',
        'recorded_at',
    ];

    protected function casts(): array
    {
        return [
            'recorded_at' => 'datetime',
            'outside_geofence' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dtrEntry(): BelongsTo
    {
        return $this->belongsTo(DtrEntry::class);
    }
}

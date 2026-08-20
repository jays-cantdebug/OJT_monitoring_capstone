<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DtrEntry extends Model
{
    protected $fillable = [
        'user_id',
        'time_in',
        'time_in_latitude',
        'time_in_longitude',
        'time_out',
        'time_out_latitude',
        'time_out_longitude',
    ];

    protected function casts(): array
    {
        return [
            'time_in' => 'datetime',
            'time_out' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function durationInSeconds(): ?int
    {
        if (! $this->time_out) {
            return null;
        }

        return (int) abs($this->time_in->diffInSeconds($this->time_out));
    }

    public function gpsPings(): HasMany
    {
        return $this->hasMany(GpsPing::class);
    }

    /**
     * Shaped for the Attendance History "View Location" map - centralized
     * here so the mobile-card and desktop-table loops in the view don't
     * each duplicate the formatting.
     *
     * @return array<string, mixed>
     */
    public function locationHistoryPayload(): array
    {
        return [
            'date' => $this->time_in->format('M j, Y'),
            'timeIn' => [
                'lat' => (float) $this->time_in_latitude,
                'lng' => (float) $this->time_in_longitude,
                'label' => 'Time In · '.$this->time_in->format('g:i A'),
            ],
            'timeOut' => $this->time_out ? [
                'lat' => (float) $this->time_out_latitude,
                'lng' => (float) $this->time_out_longitude,
                'label' => 'Time Out · '.$this->time_out->format('g:i A'),
            ] : null,
        ];
    }
}

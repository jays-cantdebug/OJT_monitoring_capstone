<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}

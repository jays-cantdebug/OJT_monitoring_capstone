<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccomplishmentReport extends Model
{
    protected $fillable = [
        'user_id',
        'report_date',
        'description',
        'photo_path',
    ];

    protected function casts(): array
    {
        return [
            'report_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function photoUrl(): string
    {
        // See User::avatarUrl() - asset() resolves against the actual
        // request origin instead of the hardcoded APP_URL that
        // Storage::url() bakes in.
        return asset('storage/'.$this->photo_path);
    }
}

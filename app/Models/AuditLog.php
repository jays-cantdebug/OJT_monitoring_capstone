<?php

namespace App\Models;

use App\Enums\AuditAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'actor_id',
        'subject_id',
        'action',
        'changes',
    ];

    protected function casts(): array
    {
        return [
            'action' => AuditAction::class,
            'changes' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(User::class, 'subject_id');
    }

    /**
     * @param  array<string, mixed>  $changes
     */
    public static function record(User $actor, User $subject, AuditAction $action, array $changes = []): self
    {
        return self::create([
            'actor_id' => $actor->id,
            'subject_id' => $subject->id,
            'action' => $action,
            'changes' => $changes,
        ]);
    }
}

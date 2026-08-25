<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Department;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'avatar_path',
        'password',
        'role',
        'status',
        'department',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'department' => Department::class,
        ];
    }

    public function scopeStudentInterns(Builder $query): void
    {
        $query->where('role', 'student_intern');
    }

    public function scopeInDepartment(Builder $query, Department $department): void
    {
        $query->where('department', $department);
    }

    public function isDean(): bool
    {
        return $this->role === 'dean';
    }

    public function isStudentIntern(): bool
    {
        return $this->role === 'student_intern';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * The route each role lands on after login / when hitting '/' - Admin
     * has no dashboard, so its Deans list doubles as its home page.
     */
    public function homeRouteName(): string
    {
        return match (true) {
            $this->isAdmin() => 'admin.dashboard',
            $this->isDean() => 'dean.dashboard',
            default => 'student.dashboard',
        };
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function avatarUrl(): ?string
    {
        // asset() resolves against the current request's actual host/port
        // rather than the hardcoded APP_URL that Storage::url() bakes in -
        // this matters because local dev here runs on a port APP_URL
        // doesn't reflect, and Storage::url() would silently point at the
        // wrong origin.
        return $this->avatar_path ? asset('storage/'.$this->avatar_path) : null;
    }

    public function dtrEntries(): HasMany
    {
        return $this->hasMany(DtrEntry::class);
    }

    public function openDtrEntry(): ?DtrEntry
    {
        return $this->dtrEntries()->whereNull('time_out')->first();
    }

    public function accomplishmentReports(): HasMany
    {
        return $this->hasMany(AccomplishmentReport::class);
    }

    public function studentProfile(): HasOne
    {
        return $this->hasOne(StudentProfile::class);
    }
}

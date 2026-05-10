<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'department_id',
        'role_id',
        'name',
        'last_name',
        'email',
        'password',
        'hire_date',
        'active',
        'must_change_password',
        'email_verified_at',
        'last_login_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'active' => 'boolean',
            'must_change_password' => 'boolean',
        ];
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function timeLogs(): HasMany
    {
        return $this->hasMany(TimeLog::class);
    }

    public function timeLogIssues(): HasMany
    {
        return $this->hasMany(TimeLogIssue::class);
    }

    public function absenceRequests(): HasMany
    {
        return $this->hasMany(AbsenceRequest::class);
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(Approval::class, 'approved_by');
    }

    public function userShifts(): HasMany
    {
        return $this->hasMany(UserShift::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function isAdmin(): bool
    {
        return $this->role?->name === 'admin';
    }

    public function isOwner(): bool
    {
        return $this->role?->name === 'owner';
    }
}

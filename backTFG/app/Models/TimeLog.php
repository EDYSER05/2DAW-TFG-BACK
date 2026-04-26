<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TimeLog extends Model
{
    protected $fillable = ['user_id', 'date', 'check_in', 'check_out'];

    protected function casts(): array
    {
        return [
            'date'      => 'date',
            'check_in'  => 'datetime',
            'check_out' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function issues(): HasMany
    {
        return $this->hasMany(TimeLogIssue::class);
    }
}

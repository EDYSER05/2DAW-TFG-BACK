<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeLogIssue extends Model
{
    protected $fillable = ['time_log_id', 'user_id', 'issue_type_id', 'description', 'resolved'];

    protected function casts(): array
    {
        return [
            'resolved' => 'boolean',
        ];
    }

    public function timeLog(): BelongsTo
    {
        return $this->belongsTo(TimeLog::class);
    }

    public function issueType(): BelongsTo
    {
        return $this->belongsTo(IssueType::class);
    }

    public function reportedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

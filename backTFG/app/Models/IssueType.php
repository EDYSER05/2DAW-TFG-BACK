<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IssueType extends Model
{
    protected $fillable = ['name'];

    public function timeLogIssues(): HasMany
    {
        return $this->hasMany(TimeLogIssue::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Holiday extends Model
{
    protected $fillable = ['date', 'name'];

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_holiday')->withTimestamps();
    }
}

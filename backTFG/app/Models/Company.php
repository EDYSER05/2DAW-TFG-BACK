<?php

namespace App\Models;

use App\Observers\CompanyObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ObservedBy([CompanyObserver::class])]
class Company extends Model
{
    protected $fillable = ['name', 'tax_id', 'address'];

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }

    public function holidays(): BelongsToMany
    {
        return $this->belongsToMany(Holiday::class, 'company_holiday')->withTimestamps();
    }
}

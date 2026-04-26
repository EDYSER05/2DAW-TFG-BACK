<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Attachment extends Model
{
    protected $fillable = ['entity_type', 'entity_id', 'file_url'];

    public function entity(): MorphTo
    {
        return $this->morphTo('entity');
    }
}

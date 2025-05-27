<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class File extends Model
{
    protected $fillable = [
        'name',
        'path',
        'type',
        'user_id'
    ];

    public function owner(): MorphTo
    {
        return $this->morphTo();
    }
}

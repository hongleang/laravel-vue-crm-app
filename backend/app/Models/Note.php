<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = [
        'content',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function owner()
    {
        return $this->morphTo();
    }

    public function createdBy(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $this->user->name,
        );
    }
}

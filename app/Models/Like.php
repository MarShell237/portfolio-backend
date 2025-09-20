<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Like extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'likeable_id',
        'likeable_type',
        'liked_at',
    ];

    public $timestamps = false;

    public function casts(): array
    {
        return [
            'liked_at' => 'datetime',
        ];
    }

    public function liker()
    {
        return $this->belongsTo(User::class, 'liker_id');
    }

    public function likeable(): MorphTo
    {
        return $this->morphTo();
    }
}

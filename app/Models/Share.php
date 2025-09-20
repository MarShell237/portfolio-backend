<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Share extends Model
{
    // /** @use HasFactory<\Database\Factories\ShareFactory> */
    // use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'shareable_type',
        'shareable_id',
        'platform',
        'shared_at',
    ];

    public $timestamps = false;

    public function casts(): array
    {
        return [
            'shared_at' => 'datetime',
        ];
    }

    public function sharer()
    {
        return $this->belongsTo(User::class, 'sharer_id');
    }

    public function shareable()
    {
        return $this->morphTo();
    }
}

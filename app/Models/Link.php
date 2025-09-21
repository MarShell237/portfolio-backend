<?php

namespace App\Models;

use App\Traits\HasFile;
use App\Traits\HasLinks;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Link extends Model
{
    use HasFactory,
        HasFile,
        HasLinks;

    protected $fillable = [
        'linkeable_id',
        'linkeable_type',
        'label',
        'url',
        'description'
    ];

    public function linkeable(): MorphTo
    {
        return $this->morphTo();
    }
}

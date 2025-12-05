<?php

namespace App\Models;

use App\Traits\HasFile;
use App\Traits\HasLinks;
use App\Traits\HasTags;
use App\Traits\Likeable;
use App\Traits\Shareable;
use App\Traits\Commentable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory,
        SoftDeletes,
        HasFile,
        HasLinks,
        HasTags,
        Likeable,
        Shareable,
        Commentable;

    protected $fillable = [
        'title',
        'excerpt',
        'content',
        'estimated_cost',
        'view_count',
        'pinned',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }
}

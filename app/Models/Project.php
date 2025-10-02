<?php

namespace App\Models;

use App\Traits\HasFile;
use App\Traits\HasLinks;
use App\Traits\HasTags;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory,
        SoftDeletes,
        HasFile,
        HasLinks,
        HasTags;

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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

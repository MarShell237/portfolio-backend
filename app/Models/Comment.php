<?php

namespace App\Models;

use App\Traits\CascadesMorphDeletes;
use App\Traits\Commentable;
use App\Traits\HasFile;
use App\Traits\Likeable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Comment extends Model
{
    /** @use HasFactory<\Database\Factories\CommentFactory> */
    use HasFactory,
        HasFile,
        Likeable,
        Commentable,
        CascadesMorphDeletes;

    protected function cascadesMorphDeletes(): array
    {
        return [
            'file',       // from HasFile
            'likes',      // from Likeable
            'comments',   // from Commentable
        ];
    }
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'content',
    ];

    public function commenter()
    {
        return $this->belongsTo(User::class, 'commenter_id');
    }

    public function commentable()
    {
        return $this->morphTo();
    }
}

<?php

namespace App\Models;

use App\Traits\HasFile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory, 
        SoftDeletes,
        HasFile;

    protected $fillable = [
        'title',
        'excerpt',
        'content',
        'estimated_cost',
        'view_count',
        'pinned',
    ];
}

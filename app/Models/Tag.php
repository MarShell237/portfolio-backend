<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasFile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use HasFactory, 
        SoftDeletes,
        HasFile;

    protected $fillable = [
        'name',
        'color',
        'type',
        'description',
    ];
}

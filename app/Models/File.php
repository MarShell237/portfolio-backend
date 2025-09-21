<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'disk',
    ];

    public function casts(): array
    {
        return [
            'saved_at' => 'datetime',
        ];
    }

    public $timestamps = false;
}

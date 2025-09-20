<?php

namespace App\Traits;

use App\Models\Share;

trait Shareable
{
    public function shares()
    {
        return $this->morphMany(Share::class, 'shareable');
    }
}

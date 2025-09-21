<?php

namespace App\Traits;

use App\Models\Link;

trait HasLinks
{
    public function links()
    {
        return $this->morphMany(Link::class, 'linkeable');
    }
}

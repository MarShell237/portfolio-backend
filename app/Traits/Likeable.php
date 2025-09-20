<?php

namespace App\Traits;

use App\Models\Like;

trait Likeable
{
    /**
     * Get all likes for this model.
     */
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }
}

<?php

namespace App\Repositories;

class LikeRepository
{
    public function like($user, string $type, int $id)
    {
        return $user->likes()->create([
            'likeable_type' => $type,
            'likeable_id' => $id,
        ]);
    }

    public function unlike($user, string $type, int $id): bool
    {
        return $user->likes()
            ->where('likeable_type', $type)
            ->where('likeable_id', $id)
            ->delete();
    }

    public function alreadyLiked($user, string $type, int $id): bool
    {
        return $user->likes()
            ->where('likeable_type', $type)
            ->where('likeable_id', $id)
            ->exists();
    }

    public function listLikes($user)
    {
        return $user->likes()->latest('id')->get();
    }
}

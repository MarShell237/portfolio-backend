<?php

namespace App\Repositories;

class ShareRepository
{
    public function alreadyShared($sharer, string $shareableType, int $shareableId)
    {
        return $sharer->shares()
            ->where('shareable_type', $shareableType)
            ->where('shareable_id', $shareableId)
            ->exists();
    }

    public function storeShare($sharer, string $shareableType, int $shareableId, string $platform)
    {
        $sharer->shares()->create([
            'shareable_type' => $shareableType,
            'shareable_id' => $shareableId,
            'platform' => $platform,
        ]);
    }
}

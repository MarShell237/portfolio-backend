<?php

namespace App\Http\Resources;

use App\Helpers\MorphRoute;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LinkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'linkable' => MorphRoute::make($this->linkable_type, $this->linkable_id),
            'label' => $this->label,
            'url' => $this->url,
            'description' => $this->description,
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),

            'icon' => route('files.show', $this->getFileId()),
        ];
    }
}

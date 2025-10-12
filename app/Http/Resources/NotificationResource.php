<?php

namespace App\Http\Resources;

use App\Helpers\MorphRoute;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'notifiable' => MorphRoute::make($this->notifiable_type, $this->notifiable_id),
            'subject' => $this->data['subject'],
            'content' => $this->data['content'],
            'type' => $this->type,
            'read_at' => $this->read_at?->toDateTimeString(),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}

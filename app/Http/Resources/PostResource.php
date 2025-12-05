<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'title' => $this->title,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'reading_time' => $this->reading_time,
            'view_count' => $this->view_count,
            'pinned' => $this->pinned,
            'published_at' => $this->published_at?->toDateTimeString(),
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
            'deleted_at' => $this->deleted_at?->toDateTimeString(),

            'likes_count'           => $this->whenCounted(relationship: 'likes'),
            'comments_count'        => $this->whenCounted('comments'),
            'shares_count'          => $this->whenCounted('shares'),

            'cover_image' => route('files.show', $this->getFileId()),
            'comments' => route('comments.index', [
                'commentableType' => $this->getMorphClass(),
                'commentableId'   => $this->id,
            ]),
        ];
    }
}

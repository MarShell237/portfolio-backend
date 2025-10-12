<?php

namespace App\Http\Resources;

use App\Helpers\MorphRoute;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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

            'content' => $this->content,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),      

            $this->mergeWhen(isset($this->is_liked), [
                'is_liked' => (bool) $this->is_liked,
            ]),

            'likes_count'      => $this->whenCounted('likes'),
            'comments_count'   => $this->whenCounted('comments'),
            
            'commentable' => MorphRoute::make($this->commentable_type, $this->commentable_id),
            'file' => $this->getFileId() ? route('files.show', $this->getFileId()) : null,
            'commenter' => route('users.show', $this->commenter_id),
            'comments' => route('comments.index', [
                'commentableType' => $this->commentable_type,
                'commentableId' => $this->commentable_id,
            ]),
        ];    
    }
}

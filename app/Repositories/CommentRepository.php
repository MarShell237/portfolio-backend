<?php

namespace App\Repositories;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class CommentRepository
{
    public function getCommentsByCommentable(Model $commentable): array
    {
        $query = Comment::where('commentable_type', $commentable->getMorphClass())
            ->where('commentable_id', $commentable->id);

        return $query->select('id')
                    ->latest('id')
                    ->paginate()
                    ->toArray();
    }

    public function createComment(Model $commenter, Model $commentable, string $content): Comment
    {
        $comment = new Comment(['content' => $content]);
        $comment->commenter()->associate($commenter);
        $comment->commentable()->associate($commentable);
        $comment->save();

        return $comment;
    }

    public function updateComment(Comment $comment, string $newContent): bool
    {
        $comment->content = $newContent;
        return $comment->save();
    }

    public function deleteComment(Comment $comment): ?bool
    {
        return $comment->delete();
    }

    public function findCommentById(int $id, ?Model $user = null)
    {
        $query = Comment::withCount(['likes', 'comments'])
            ->where('id', $id);

            
        if ($user) {
            $query->withExists([
                'likes as is_liked' => fn ($q) => $q
                    ->where('liker_id', $user->id),
            ]);
        }
        return $query->first();
    }

    public function handleCommentFile(Comment $comment, ?UploadedFile $file): void
    {
        if ($file) {
            $comment->setFile($file, 'comments');
        }
    }
}

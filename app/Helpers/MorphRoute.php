<?php

namespace App\Helpers;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class MorphRoute
{
    public static function make(string $type, int|string $id): ?string
    {
        return match (class_basename($type)) {
            'project' => route('projects.show', $id),
            'post' => route('posts.show', $id),
            'comment' => route('comments.show', $id),
            'tag' => route('tags.show', $id),
            'user' => route('users.show', $id),

            default => throw new Exception(
                'No matching route found for model type [' . $type . '] in ' . self::class,
                Response::HTTP_NOT_IMPLEMENTED
            ),
        };
    }
}

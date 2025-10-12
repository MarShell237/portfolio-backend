<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Helpers\MorphResolver;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Repositories\CommentRepository;
use App\Repositories\UserRepository;
use App\Traits\Commentable;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends Controller
{
    public function __construct(
        protected CommentRepository $commentRepository,
        protected UserRepository $userRepository
    ) {}

    public function index(string $commentableType, int $commentableId)
    {
        try {
            $commentable = MorphResolver::resolve($commentableType, $commentableId);

            $comments = $this->commentRepository->getCommentsByCommentable($commentable);
            return ApiResponse::ok(
                'Comments retrieved successfully',
                $comments
            );
        } catch (Exception $e) {
            return ApiResponse::anyError($e->getMessage(), $e->getCode());
        }
    }

    public function store(CommentRequest $request, string $commentableType, int $commentableId)
    {
        try {
            $user = $this->userRepository->connected();
            $data = $request->validated();

            $commentable = MorphResolver::resolve($commentableType, $commentableId);

            if (!in_array(Commentable::class, class_uses_recursive(get_class($commentable)))) {
                throw new Exception("The resource type is not commentable.",Response::HTTP_BAD_REQUEST);
            }

            if (!$data['file'] && !$data['content']) {
                throw new Exception("Comment content or file is required.", Response::HTTP_BAD_REQUEST);
            }
            $comment = $this->commentRepository->createComment($user, $commentable, $data['content']);

            $this->commentRepository->handleCommentFile($comment, $data['file']);

            return ApiResponse::ok(
                'Comment created successfully',
                ['data' => new CommentResource($comment)]
            );
        } catch (Exception $e) {
            return ApiResponse::anyError($e->getMessage(), $e->getCode());
        }
    }

    public function show(Comment $comment)
    {
        try {
            $user = $this->userRepository->connected();

            $freshComment = $this->commentRepository->findCommentById($comment->id, $user);

            return ApiResponse::ok(
                'Comment retrieved successfully',
                ['data' => new CommentResource($freshComment)]
            );
        } catch (Exception $e) {
            return ApiResponse::anyError($e->getMessage(), $e->getCode());
        }
    }

    public function update(CommentRequest $request, Comment $comment)
    {
        try {
            $user = $this->userRepository->connected();
            $data = $request->validated();

            if ($comment->commenter_id !== $user->id) {
                throw new Exception("You are not the commenter of this comment.", Response::HTTP_FORBIDDEN);
            }

            if (!$data['file'] && !$data['content']) {
                throw new Exception("Comment content or file is required.", Response::HTTP_BAD_REQUEST);
            }
            $this->commentRepository->updateComment($comment, $data['content']);
            $this->commentRepository->handleCommentFile($comment, $data['file']); 

            return ApiResponse::ok(
                'Comment updated successfully',
                ['data' => new CommentResource($comment->refresh())]
            );
        } catch (Exception $e) {
            return ApiResponse::anyError($e->getMessage(), $e->getCode());
        }
    }

    public function destroy(Comment $comment)
    {
        try {
            $user = $this->userRepository->connected();

            if ($comment->commenter_id !== $user->id) {
                throw new Exception("You are not the commenter of this comment.", Response::HTTP_FORBIDDEN);
            }
            $this->commentRepository->deleteComment($comment);
            return ApiResponse::ok('Comment deleted successfully');
        } catch (Exception $e) {
            return ApiResponse::anyError($e->getMessage(), $e->getCode());
        }
    }
}

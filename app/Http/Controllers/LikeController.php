<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Helpers\MorphResolver;
use App\Helpers\MorphRoute;
use App\Http\Resources\LikeResource;
use App\Models\Like;
use App\Repositories\LikeRepository;
use App\Repositories\UserRepository;
use App\Traits\Likeable;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class LikeController extends Controller
{
    public function __construct(
        private LikeRepository $likeRepository,
        private UserRepository $userRepository
    ) {}

    /**
     * Like or unlike a resource
     *
     * @urlParam likeableType string required The type of the resource (e.g., "post" or "project").
     * @urlParam likeableId int required The ID of the resource to like/unlike.
     */
    public function likeOrUnlike(string $likeableType, int $likeableId):JsonResponse
    {
        $user = $this->userRepository->connected();

        try {
            $likeable = MorphResolver::resolve($likeableType, $likeableId);

            if (!in_array(Likeable::class, class_uses_recursive(get_class($likeable)))) {
                throw new Exception("The resource is not likeable.", Response::HTTP_BAD_REQUEST);
            }

            $type = $likeable->getMorphClass();

            if ($this->likeRepository->alreadyLiked($user, $type, $likeableId)) {

                $this->likeRepository->unlike($user, $type, $likeableId);
                $message =  "Like removed successfully.";
            }else{
                $this->likeRepository->like($user, $type, $likeableId);
                $message =  "Like created successfully.";
            }

            return ApiResponse::ok($message);
        } catch (Exception $e) {
            return ApiResponse::anyError($e->getMessage(), $e->getCode());
        }
    }

    public function index(): JsonResponse
    {
        $likes = $this->userRepository->connected()
                        ->likes()
                        ->select('likeable_type', 'likeable_id')
                        ->latest('id')
                        ->paginate()
                        ->through(fn ($like) => [
                            'likeable' => MorphRoute::make($like->likeable_type, $like->likeable_id),
                        ])
                        ->toArray();

        return ApiResponse::ok(
            'like list retrieved successfully.',
            $likes
        );
    }
}

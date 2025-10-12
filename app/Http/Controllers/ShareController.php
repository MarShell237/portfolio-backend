<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Helpers\MorphResolver;
use App\Repositories\ShareRepository;
use App\Repositories\UserRepository;
use App\Traits\Shareable;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ShareController extends Controller
{
    public function __construct(
        private ShareRepository $shareRepository,
        private UserRepository $userRepository
    ) {}

    public function share(string $shareableType, int $shareableId, string $platform): JsonResponse
    {
        $user = $this->userRepository->connected();

        try {
            $shareable = MorphResolver::resolve($shareableType, $shareableId);

            if (!$shareable || !in_array(Shareable::class, class_uses_recursive(get_class($shareable)))) {
                throw new Exception("The resource is not shareable.", Response::HTTP_BAD_REQUEST);
            }

            $type = $shareable->getMorphClass();

            if ($this->shareRepository->alreadyShared($user, $type, $shareableId)) {
                $message = "already shared";
            }else{   
                $this->shareRepository->storeShare($user, $type, $shareableId, $platform);
                $message = "successfully shared";
            }

            return ApiResponse::ok($message);
        } catch (Exception $e) {
            return ApiResponse::anyError($e->getMessage(), $e->getCode());
        }
    }
}

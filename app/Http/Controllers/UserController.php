<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Repository\UserRepository;

class UserController extends Controller
{
    public function __construct(private UserRepository $userRepository) {}

    private static string $folderPath = 'users/photos';

    public function update (RegisterRequest $request){
        $data = $request->validated();
        $user = $this->userRepository->connected();
        $user->update($data);
        if (isset($data['photo'])) {
            $user->setFile($data['photo'], self::$folderPath);
        }
        return ApiResponse::ok('user updated successfully', ['user' => new UserResource($user)]);
    }

    public function destroy(){
        $this->userRepository->connected()->tokens()->delete();
        $this->userRepository->connected()->delete();
        return ApiResponse::ok('user account soft deleted');
    }
}

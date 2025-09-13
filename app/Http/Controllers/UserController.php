<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Repository\UserRepository;

class UserController extends Controller
{
    public function __construct(private UserRepository $userRepository) {}

    public function update (RegisterRequest $request){
        $user = $this->userRepository->connected();
        $user->update($request->validated());
        return ApiResponse::ok('user updated successfully', ['user' => new UserResource($user)]);
    }

    public function destroy(){
        $this->userRepository->connected()->tokens()->delete();
        $this->userRepository->connected()->delete();
        return ApiResponse::ok('user account soft deleted');
    }
}

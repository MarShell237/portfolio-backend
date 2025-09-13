<?php

namespace App\Http\Controllers;

use App\Enum\UserRole;
use App\Helpers\ApiResponse;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repository\UserRepository;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function __construct(private UserRepository $userRepository) {}

    public function register(RegisterRequest $request){
        $data = $request->validated();
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->assignRole(Role::firstWhere('name', UserRole::VISITOR->value));
        $user->refresh();
        $token = $this->userRepository->generateToken($user, UserRole::VISITOR);
        event(new Registered($user));

        return ApiResponse::created(
            'User registered successfully. Please verify your email address.',
            [
                'token' => $token,
                'user' => new UserResource($user),
            ]
        );
    }
}

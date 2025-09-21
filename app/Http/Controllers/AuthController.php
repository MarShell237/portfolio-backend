<?php

namespace App\Http\Controllers;

use App\Enum\UserRole;
use App\Helpers\ApiResponse;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UserResetPasswordRequest;
use App\Http\Requests\UserSendResetLinkEmailRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class AuthController extends Controller
{
    public function __construct(private UserRepository $userRepository) {}

    private static string $folderPath = 'users/photos';

    public function register(RegisterRequest $request){
        $data = $request->validated();
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->assignRole(Role::firstWhere('name', UserRole::VISITOR->value));

        if (isset($data['photo'])) {
            $user->setFile($data['photo'], self::$folderPath);
        }

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

    public function login(LoginRequest $request){
        try {
            $credentials = $request->validated();

            $user = User::where('email', $credentials['email'])->first();

            if (!$user || !Hash::check($credentials['password'], $user->password)) {
                abort(Response::HTTP_UNAUTHORIZED, 'Invalid credentials.');
            }

            return ApiResponse::ok(
                'user connected',
                [
                    'token' => $this->userRepository->generateToken($user, $user->getRoleNames()->first()),
                    'user' => new UserResource($user),
                ]
            );
        } catch (Throwable $e) {
            return ApiResponse::anyError($e->getMessage(), $e->getCode());
        }
    }

    public function connected(){
        return ApiResponse::ok('success to get user connected', [
            'user' => new UserResource($this->userRepository->connected())
        ]);
    }

    public function logout(){
        $this->userRepository->connected()->tokens()->delete();
        return ApiResponse::ok('user disconnected');
    }


    public function verify(EmailVerificationRequest $request)
    {
        if ($this->userRepository->connected()->hasVerifiedEmail()) {
            return ApiResponse::ok('user email already verified.');
        }

        if ($this->userRepository->connected()->markEmailAsVerified()) {
            event(new Verified($this->userRepository->connected()));
        }

        return ApiResponse::ok('user email verified successfully.');
    }

    public function resend()
    {
        if ($this->userRepository->connected()->hasVerifiedEmail()) {
            return ApiResponse::ok('user email already verified.');
        }

        $this->userRepository->connected()->sendEmailVerificationNotification();

        return ApiResponse::ok('user verification link sent!');
    }


    public function sendResetLinkEmail(UserSendResetLinkEmailRequest $request)
    {
        $data = $request->validated();
        if($this->userRepository->sendResetLink($data['email'])){
            return ApiResponse::ok("success to send reset link password email");
        }else{
            return ApiResponse::badRequest("Failed to send reset link password email");
        }
    }

    public function reset(UserResetPasswordRequest $request)
    {
        if($this->userRepository->resetPassword($request->validated())){
            return ApiResponse::ok("success to reset password");
        }else{
            return ApiResponse::badRequest("Failed to reset password");
        }
    }
}

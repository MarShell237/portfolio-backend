<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class AuthController extends Controller
{
    public function __construct(private UserRepository $userRepository) {}

    public function registerCookie(RegisterRequest $request){
        $user = $this->userRepository->createUser($request->validated(), $request->validated()['photo'] ?? null);
        $rememberMe = $request->validated()['remember_me'] ?? false;
        Auth::guard('web')->login($user, $rememberMe);
        event(new Registered($user));

        return ApiResponse::created(
            'Web user registered successfully. Please verify your email address.',
            [
                'data' => new UserResource($user),
            ]
        );
    }

    public function loginCookie(LoginRequest $request){
        try {
            $credentials = $request->validated();
            $rememberMe = $request->validated()['remember_me'] ?? false;
            unset($credentials['remember_me']);
            if (!Auth::guard('web')->attempt($credentials, $rememberMe)) {
                abort(Response::HTTP_UNAUTHORIZED, 'Invalid credentials.');
            }

            request()->session()->regenerate();
            $user = User::where('email', $credentials['email'])->first();
            return ApiResponse::ok(
                'Web user connected',
                [
                    'data' => new UserResource($user),
                ]
            );
        } catch (Throwable $e) {
            return ApiResponse::anyError($e->getMessage(), $e->getCode());
        }
    }

    public function registerToken(RegisterRequest $request){
        $user = $this->userRepository->createUser($request->validated(), $request->validated()['photo'] ?? null);
        $token = $this->userRepository->generateToken($user, UserRole::VISITOR);
        event(new Registered($user));

        return ApiResponse::created(
            'User registered successfully. Please verify your email address.',
            [
                'token' => $token,
                'data' => new UserResource($user),
            ]
        );
    }

    public function loginToken(LoginRequest $request){
        try {
            $credentials = $request->validated();

            $user = User::where('email', $credentials['email'])->first();

            if (!$user || !Hash::check($credentials['password'], $user->password)) {
                abort(Response::HTTP_UNAUTHORIZED, 'Invalid credentials.');
            }

            return ApiResponse::ok(
                'user connected',
                [
                    'token' => $this->userRepository->generateToken($user, UserRole::VISITOR),
                    'data' => new UserResource($user),
                ]
            );
        } catch (Throwable $e) {
            return ApiResponse::anyError($e->getMessage(), $e->getCode());
        }
    }

    public function connected(){
        $user = $this->userRepository->connected();
        return ApiResponse::ok('success to get user connected', [
            'data' => new UserResource($this->userRepository->connected())
        ]);
    }

    public function logoutCookie(){
        $user = $this->userRepository->connected();

        if ($user) {
            Auth::guard('web')->logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
        }

        return ApiResponse::ok('Web user disconnected');
    }

    public function logoutToken(){
        $user = $this->userRepository->connected();
        if($user){
            $user->tokens()->delete();
        }
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

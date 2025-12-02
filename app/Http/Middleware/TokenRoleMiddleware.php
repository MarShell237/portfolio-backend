<?php

namespace App\Http\Middleware;

use App\Helpers\ApiResponse;
use App\Repositories\UserRepository;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenRoleMiddleware
{
    public function __construct(public UserRepository $userRepository)
    {
        //
    }
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @param  string  ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        foreach ($roles as $role) {
            if ($this->userRepository->connected()->hasRole($role)) {
                return $next($request);
            }
            // if ($request->user()->tokenCan('role:' . $role)) {
            //     return $next($request);
            // }
        }

        return ApiResponse::unauthorized('Not Authorized, insufficient role privileges.');
    }
}

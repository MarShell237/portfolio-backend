<?php

namespace App\Repository;

use App\Enum\UserRole;
use App\Models\User;

class UserRepository
{
    public function generateToken(User $user, UserRole $role): string
    {
        return $user->createToken(config('auth.token_name'), ["role:$role->value"])->plainTextToken;
    }

    public function connected(): User{
        return auth('api')->user();
    }
}

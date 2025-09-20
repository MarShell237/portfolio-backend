<?php

namespace App\Repositories;

use App\Enum\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class UserRepository
{
    public function generateToken(User $user, UserRole $role): string
    {
        return $user->createToken(config('auth.token_name'), ["role:$role->value"])->plainTextToken;
    }

    public function connected(): User{
        return auth('api')->user();
    }

    public function sendResetLink($email)
    {
        $status = Password::broker('users')->sendResetLink(['email' => $email]);

        return  $status === Password::RESET_LINK_SENT;
    }

    public function resetPassword($data)
    {
        $status = Password::broker('users')->reset(
                    $data,
                    function (User $user, $password) {
                        $user->forceFill([
                            'password' => Hash::make($password),
                        ])->save();
                    }
                );  

        return $status === Password::PASSWORD_RESET;
    }
}

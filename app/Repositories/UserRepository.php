<?php

namespace App\Repositories;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class UserRepository
{
    public function createUser(array $data, ?UploadedFile $photo): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        if ($photo) {
            $user->setFile($photo, 'users/photos');
        }

        $user->assignRole(Role::firstWhere('name', UserRole::VISITOR->value));

        return $user->refresh();
    }
    public function generateToken(User $user, UserRole $role): string
    {
        return $user->createToken(config('auth.token_name'), ["role:$role->value"])->plainTextToken;
    }

    public function connected(): ?User{
        return auth('web')->user() ?? auth('api')->user();
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

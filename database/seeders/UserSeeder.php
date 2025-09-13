<?php

namespace Database\Seeders;

use App\Enum\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Marcel J. DJIOFACK',
            'email' => 'marcelj.djiofack@outlook.com',
            'password' => Hash::make('filamentpassword')
        ]);

        $user->assignRole(Role::firstWhere('name', UserRole::ADMIN->value));

        $users = User::factory(5)->create();
        $users->each(function (User $user) {
            $user->assignRole(Role::firstWhere('name', UserRole::VISITOR->value));
        });
    }
}

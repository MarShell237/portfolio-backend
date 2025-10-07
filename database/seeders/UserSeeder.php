<?php

namespace Database\Seeders;

use App\Enum\UserRole;
use App\Models\File;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $me = User::factory()->create([
            'name' => 'Marcel J. DJIOFACK',
            'email' => 'marcelj.djiofack@outlook.com',
            'password' => Hash::make('marcelj.djiofack@outlook.com')
        ]);

        $me->assignRole(Role::firstWhere('name', UserRole::ADMIN->value));
        $path = public_path('assets/images/profil.jpg');
        if (file_exists($path)) {
            $uploadedFile = new UploadedFile(
                $path,
                basename($path),                        // nom original
                mime_content_type($path),              // type mime
                null,                                  // size (null = auto)
                true                                   // test mode
            );

            $me->setFile($uploadedFile, 'photo');
        }

        $users = User::factory(5)->create();
        $users->each(function (User $user) {
            $user->assignRole(Role::firstWhere('name', UserRole::VISITOR->value));
            $user->file()->create(File::factory()->make()->toArray());
        });

        // admin de test
        $admin = User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin')
        ]);

        $admin->assignRole(Role::firstWhere('name', UserRole::ADMIN->value));
    }
}

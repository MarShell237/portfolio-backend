<?php

namespace App\Models;

use App\Enum\UserRole;
use App\Notifications\UserCustomResetPasswordNotification;
use App\Notifications\UserVerifyEmail;
use App\Traits\HasFile;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail, CanResetPassword, FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory,
        Notifiable,
        HasApiTokens,
        HasRoles,
        SoftDeletes,
        HasFile;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasRole(UserRole::ADMIN->value);
    }

    public function getFilamentAvatarUrl(): ?string{
        return $this->getFileUrl();
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new UserVerifyEmail());
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new UserCustomResetPasswordNotification($token));
    }
}

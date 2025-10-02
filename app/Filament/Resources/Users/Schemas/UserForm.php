<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('nom complet')
                    ->required(),
                TextInput::make('email')
                    ->label('Adresse Email')
                    ->email()
                    ->required(),
                DateTimePicker::make('email_verified_at')
                    ->label('email vérifié à'),
            ]);
    }
}

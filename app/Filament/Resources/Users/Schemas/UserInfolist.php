<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                ImageEntry::make('file.file_path')
                    ->label('Photo')
                    ->circular()
                    ->getStateUsing(fn ($record) => $record->getFileUrl())
                    ->defaultImageUrl('https://www.gravatar.com/avatar/?d=mp&f=y'),
                TextEntry::make('name')
                    ->label( 'nom complet'),
                TextEntry::make('roles.name')
                    ->label('Rôle')
                    ->badge()
                    ->separator(', ')
                    ->placeholder('Aucun rôle attribué'),
                TextEntry::make('email')
                    ->label( 'Adresse Email'),
                TextEntry::make('email_verified_at')
                    ->label('email vérifié à')
                    ->dateTime('l d F Y à H:i:s')
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime('l d F Y à H:i:s')
                    ->visible(fn (User $record): bool => $record->trashed()),
                TextEntry::make('created_at')
                    ->label('créé à')
                    ->dateTime('l d F Y à H:i:s')
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label('mis à jour à')
                    ->dateTime('l d F Y à H:i:s')
                    ->placeholder('-'),
            ]);
    }
}

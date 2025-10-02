<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('file.file_path')
                    ->getStateUsing(fn ($record) => $record->getFileUrl())   
                    ->label('Photo')
                    ->circular()
                    ->defaultImageUrl('https://www.gravatar.com/avatar/?d=mp&f=y'),
                TextColumn::make('name')
                    ->label('nom complet')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Adresse Email')
                    ->searchable(),
                TextColumn::make('roles.name')
                    ->label('Rôle')
                    ->badge()
                    ->separator(', ')
                    ->placeholder('Aucun rôle attribué'),
                TextColumn::make('email_verified_at')
                    ->label('email vérifié à')
                    ->dateTime('l d F Y à H:i:s')
                    ->sortable(),
                TextColumn::make('deleted_at')
                    ->label('supprimé à')
                    ->dateTime('l d F Y à H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('créé à')
                    ->dateTime('l d F Y à H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('mis à jour à')
                    ->dateTime('l d F Y à H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                // EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}

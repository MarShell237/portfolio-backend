<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Archiver')
                ->color('info')
                ->modalHeading(fn () => 'Archiver ce ' . UserResource::getModelLabel() . ' ?')
                ->modalDescription(fn () => 'Êtes-vous sûr de vouloir archiver ce ' . UserResource::getModelLabel() . ' ? Il ne sera plus actif.')
                ->modalSubmitActionLabel(fn () => 'Oui, archiver'),
            RestoreAction::make()
                ->label ('Désarchiver'),
        ];
    }
}

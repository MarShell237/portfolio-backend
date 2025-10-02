<?php

namespace App\Filament\Actions;

use Filament\Notifications\Notification;
use Filament\Actions\Action;

class TogglePublishAction
{
    public static function make(): Action{
        return Action::make('togglePublish')
            ->label(fn ($record) => $record->published_at ? 'Dépublier' : 'Publier')
            ->icon(fn ($record) => $record->published_at ? 'heroicon-m-eye-slash' : 'heroicon-m-eye')
            ->color(fn ($record) => $record->published_at ? 'danger' : 'success')
            ->requiresConfirmation()
            ->modalHeading(fn ($record) => $record->published_at
                ? 'Confirmer la dépublication'
                : 'Confirmer la publication')
            ->modalDescription(fn ($record) => $record->published_at
                ? 'Voulez-vous vraiment retirer ce projet de la publication ?'
                : 'Voulez-vous vraiment publier ce projet maintenant ?')
            ->modalSubmitActionLabel(fn ($record) => $record->published_at ? 'Oui, dépublier' : 'Oui, publier')
            ->action(function ($record) {
                if ($record->published_at) {
                    $record->update(['published_at' => null]);

                    Notification::make()
                        ->title('Projet dépublié avec succès')
                        ->warning()
                        ->send();
                } else {
                    $record->update(['published_at' => now()]);

                    Notification::make()
                        ->title('Projet publié avec succès')
                        ->success()
                        ->send();
                }
            });
    }
}

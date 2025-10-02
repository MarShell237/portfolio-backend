<?php

namespace App\Filament\Resources\Tags\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\ImageEntry;
use Filament\Schemas\Schema;

class TagForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('nom')
                    ->required(),
                ImageEntry::make('file.file_path')
                    ->getStateUsing(fn ($record) => $record->getFileUrl())
                    ->visible(fn ($record) => filled($record) && $record->file !== null)
                    ->label('icône existante')
                    ->circular(),
                FileUpload::make('file.file_path')
                    ->label('Icône')
                    ->image()
                    ->directory('tags/icons')
                    ->getUploadedFileNameForStorageUsing(fn ($file) => $file->getClientOriginalName())
                    ->saveUploadedFileUsing(fn ($file, $record) => $record->setFile($file, 'tags/icons'))
                    ->required(fn ($record) => $record === null)
                    ->dehydrated(false),
                ColorPicker::make('color')
                    ->label('couleur')
                    ->required(),
                Textarea::make('description')
                    ->required(),
            ]);
    }
}

<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\ImageEntry;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nom')
                    ->required(),
                ImageEntry::make('file.file_path')
                    ->getStateUsing(fn ($record) => $record->getFileUrl())
                    ->visible(fn ($record) => filled($record) && $record->file !== null)
                    ->label('icône existante')
                    ->circular(),
                FileUpload::make('file.file_path')
                    ->label('Icône')
                    ->image()
                    ->directory('categories/icons')
                    ->getUploadedFileNameForStorageUsing(fn ($file) => $file->getClientOriginalName())
                    ->saveUploadedFileUsing(fn ($file, $record) => $record->setFile($file, 'categories/icons'))
                    ->required(fn ($record) => $record === null)
                    ->dehydrated(false),
                ColorPicker::make('color')
                    ->label('Couleur')
                    ->required(),
                Textarea::make('description')
                    ->label('Description')
                    ->required(),
            ]);
    }
}

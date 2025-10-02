<?php

namespace App\Filament\Resources\Links\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\ImageEntry;
use Filament\Schemas\Schema;

class LinkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('linkable_type')
                    ->required(),
                TextInput::make('linkable_id')
                    ->required()
                    ->numeric(),
                TextInput::make('url')
                    ->url()
                    ->required(),
                TextInput::make('label')
                    ->required(),
                ImageEntry::make('file.file_path')
                    ->getStateUsing(fn ($record) => $record->getFileUrl())   
                    ->label('icône existante')
                    ->circular(),
                FileUpload::make('file.file_path')
                    ->label('nouvelle Icône')
                    ->image()
                    ->directory('links/icons')
                    ->getUploadedFileNameForStorageUsing(fn ($file) => $file->getClientOriginalName())
                    ->saveUploadedFileUsing(fn ($file, $record) => $record->setFile($file, 'links/icons'))
                    ->dehydrated(false),
                TextInput::make('description'),
            ]);
    }
}

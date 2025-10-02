<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Slider;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\ImageEntry;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([                
                ImageEntry::make('file.file_path')
                    ->getStateUsing(fn ($record) => $record->getFileUrl())
                    ->visible(fn ($record) => filled($record) && $record->file !== null)
                    ->label('image de couverture existante')
                    ->circular(),
                FileUpload::make('file.file_path')
                    ->label('image de couverture')
                    ->image()
                    ->directory('projects/images')
                    ->getUploadedFileNameForStorageUsing(fn ($file) => $file->getClientOriginalName())
                    ->saveUploadedFileUsing(fn ($file, $record) => $record->setFile($file, 'projects/images'))
                    ->required(fn ($record) => $record === null)
                    ->dehydrated(false),
                TextInput::make('title')
                    ->label('Titre')
                    ->required(),
                Select::make('category_id')
                    ->label('Catégorie')
                    ->relationship('category', 'name') 
                    ->searchable()                     
                    ->preload()                        
                    ->required()
                    ->native(false),
                Select::make('tags')
                    ->label('Tags')
                    ->multiple()
                    ->relationship('tags', 'name')
                    ->preload()                     
                    ->searchable()                 
                    ->required(),
                Toggle::make('pinned')
                    ->label('Épinglé')
                    ->required(),
                Slider::make('estimated_cost')
                        ->label('coût estimé')
                        ->range(50000, 1000000)
                        ->step(50000)
                        ->tooltips()
                        ->fillTrack()
                        // ->pips(PipsMode::Steps)
                        ->columnSpanFull()
                        ->formatStateUsing(fn ($state) => (int) $state)
                        ->dehydrateStateUsing(fn ($state) => (int) $state)
                        ->required(),
                RichEditor::make('excerpt')
                    ->label('extrait')
                    ->required()
                    ->columnSpanFull(),
                RichEditor::make('content')
                    ->label('Contenu')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}

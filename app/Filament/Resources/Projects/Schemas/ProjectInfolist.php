<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Models\Project;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProjectInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                ImageEntry::make('file.file_path')
                    ->getStateUsing(fn ($record) =>$record->getFileUrl())   
                    ->label('image de existante')
                    ->circular(),
                TextEntry::make('title')
                    ->label('Titre'),
                TextEntry::make('estimated_cost')
                    ->label('coût estimé')
                    ->numeric(),
                TextEntry::make('view_count')
                    ->label('vues')
                    ->numeric(),
                IconEntry::make('pinned')
                    ->label('Épinglé')
                    ->boolean(),
                TextEntry::make('category.name')
                    ->label('Catégorie'),
                TextEntry::make('tags.name')
                    ->label('Tags')
                    ->badge(),
                TextEntry::make('published_at')
                    ->dateTime('l d F Y à H:i:s'),
                TextEntry::make('deleted_at')
                    ->dateTime('l d F Y à H:i:s')
                    ->visible(fn (Project $record): bool => $record->trashed()),
                TextEntry::make('created_at')
                    ->dateTime('l d F Y à H:i:s')
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime('l d F Y à H:i:s')
                    ->placeholder('-'),
                TextEntry::make('excerpt')
                    ->label('Extrait')
                    ->html()
                    ->columnSpanFull(),
                TextEntry::make('content')
                    ->label('Contenu')
                    ->html()
                    ->columnSpanFull(),
            ]);
    }
}

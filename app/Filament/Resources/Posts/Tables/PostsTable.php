<?php

namespace App\Filament\Resources\Posts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('file.file_path')
                    ->label('image de couverture')
                    ->getStateUsing(fn ($record) => $record->getFileUrl()),
                TextColumn::make('title')
                    ->label('Titre')
                    ->searchable(),
                // TextColumn::make('excerpt')
                //     ->searchable(),
                TextColumn::make('reading_time')
                    ->label('temps de lecture')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('view_count')
                    ->label('vues')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('pinned')
                    ->label('Épinglé')
                    ->boolean(),
                TextColumn::make('published_at')
                    ->dateTime('l d F Y à H:i:s')
                    ->sortable(),
                TextColumn::make('tags.name')
                    ->label('Tags')
                    ->sortable()
                    ->searchable()
                    ->badge(),
                TextColumn::make('deleted_at')
                    ->dateTime('l d F Y à H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime('l d F Y à H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime('l d F Y à H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),

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

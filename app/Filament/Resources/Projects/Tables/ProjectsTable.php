<?php

namespace App\Filament\Resources\Projects\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ProjectsTable
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
                //     ->label('extrait')
                //     ->searchable(),
                TextColumn::make('estimated_cost')
                ->label('coût estimé')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('view_count')
                    ->label('vues')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label('Catégorie')
                    ->sortable(),
                TextColumn::make('tags.name')
                    ->label('Tags')
                    ->sortable()
                    ->searchable()
                    ->badge(),
                IconColumn::make('pinned')
                    ->label('Épinglé')
                    ->boolean(),
                TextColumn::make('published_at')
                    ->dateTime('l d F Y à H:i:s')
                    ->sortable(),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
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

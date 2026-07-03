<?php

namespace App\Filament\Resources\CompetitionCategories\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CompetitionCategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Category')
                    ->description(fn ($record): ?string => filled($record->description)
                        ? str($record->description)->limit(60)->toString()
                        : null)
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                TextColumn::make('registered_competitions_count')
                    ->label('Competitions')
                    ->counts('registeredCompetitions')
                    ->badge()
                    ->color('info')
                    ->sortable(),
                TextColumn::make('registrations_count')
                    ->label('Registrations')
                    ->counts('registrations')
                    ->badge()
                    ->color('warning')
                    ->sortable(),
                TextColumn::make('winners_count')
                    ->label('Winners')
                    ->counts('winners')
                    ->badge()
                    ->color('success')
                    ->sortable(),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->defaultSort('name');
    }
}

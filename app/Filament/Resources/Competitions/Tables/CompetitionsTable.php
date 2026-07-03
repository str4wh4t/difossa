<?php

namespace App\Filament\Resources\Competitions\Tables;

use App\Models\Competition;
use App\Models\CompetitionStatus;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CompetitionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('banner_image_thumbnail')
                    ->label('Banner')
                    ->disk('public')
                    ->visibility('public')
                    ->imageWidth('6rem')
                    ->imageHeight('4.5rem')
                    ->extraImgAttributes([
                        'class' => 'rounded-lg object-cover',
                    ]),
                TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status.name')
                    ->label('Status')
                    ->badge()
                    ->color(fn(Competition $record): string => match ($record->status?->slug) {
                        CompetitionStatus::SLUG_DRAFT => 'gray',
                        CompetitionStatus::SLUG_OPEN => 'success',
                        CompetitionStatus::SLUG_CLOSED => 'warning',
                        CompetitionStatus::SLUG_FINISHED => 'info',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('registration_starts_at')
                    ->label('Registration Starts')
                    ->dateTime()
                    ->placeholder('-')
                    ->sortable(),
                TextColumn::make('registration_ends_at')
                    ->label('Registration Ends')
                    ->dateTime()
                    ->placeholder('-')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('competition_status_id')
                    ->label('Status')
                    ->relationship('status', 'name'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->defaultSort('title');
    }
}

<?php

namespace App\Filament\Resources\CompetitionRegistrations\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\IconSize;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CompetitionRegistrationsTable
{
    public static function configure(Table $table, bool $isAdmin): Table
    {
        $columns = [
            TextColumn::make('competition.title')
                ->label('Competition')
                ->searchable()
                ->sortable(),
            TextColumn::make('category.name')
                ->label('Category')
                ->searchable()
                ->sortable(),
            TextColumn::make('article_title')
                ->label('Article Title')
                ->searchable()
                ->limit(40),
            IconColumn::make('article_file')
                ->label('File')
                ->alignCenter()
                ->icon(fn (?string $state) => filled($state) ? Heroicon::Document : null)
                ->color('primary')
                ->size(IconSize::ExtraLarge)
                ->tooltip(fn ($record) => filled($record->article_file) ? 'Download article file' : null)
                ->url(fn ($record) => $record->article_file_download_url),
        ];

        if ($isAdmin) {
            array_splice($columns, 2, 0, [
                TextColumn::make('user.full_name')
                    ->label('Participant')
                    ->placeholder(fn ($record) => $record->user?->name)
                    ->searchable(['full_name', 'name']),
                TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable(),
            ]);
        }

        $columns[] = TextColumn::make('created_at')
            ->label('Submitted At')
            ->dateTime()
            ->sortable();

        $table = $table->columns($columns)->defaultSort('created_at', 'desc');

        if ($isAdmin) {
            $table->filters([
                SelectFilter::make('competition_id')
                    ->label('Competition')
                    ->relationship('competition', 'title'),
                SelectFilter::make('competition_category_id')
                    ->label('Category')
                    ->relationship('category', 'name'),
            ]);
        }

        return $table
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ]);
    }
}

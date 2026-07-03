<?php

namespace App\Filament\Resources\Competitions\Schemas;

use App\Filament\Resources\Competitions\Pages\ViewCompetition;
use App\Models\Competition;
use App\Models\CompetitionStatus;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Support\Icons\Heroicon;

class CompetitionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                ImageEntry::make('banner_image')
                    ->hiddenLabel()
                    ->disk('public')
                    ->imageHeight('24rem')
                    ->alignment(Alignment::Center)
                    ->extraImgAttributes([
                        'class' => 'mx-auto w-full max-w-3xl rounded-xl object-cover',
                    ])
                    ->extraEntryWrapperAttributes(['class' => 'flex justify-center'])
                    ->columnSpanFull()
                    ->visible(fn(Competition $record): bool => filled($record->banner_image)),
                Section::make('Details')
                    ->icon(Heroicon::OutlinedTrophy)
                    ->schema([
                        TextEntry::make('title')
                            ->label('Title'),
                        TextEntry::make('slug')
                            ->label('Slug'),
                        TextEntry::make('description')
                            ->label('Description')
                            ->html()
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Status & Registration')
                    ->icon(Heroicon::OutlinedCalendarDays)
                    ->schema([
                        TextEntry::make('status.name')
                            ->label('Status')
                            ->badge()
                            ->color(fn(Competition $record): string => match ($record->status?->slug) {
                                CompetitionStatus::SLUG_DRAFT => 'gray',
                                CompetitionStatus::SLUG_OPEN => 'success',
                                CompetitionStatus::SLUG_CLOSED => 'warning',
                                CompetitionStatus::SLUG_FINISHED => 'info',
                                default => 'gray',
                            })
                            ->columnSpanFull(),
                        TextEntry::make('registration_starts_at')
                            ->label('Registration Starts')
                            ->dateTime()
                            ->placeholder('No start limit'),
                        TextEntry::make('registration_ends_at')
                            ->label('Registration Ends')
                            ->dateTime()
                            ->placeholder('No end limit'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Categories, Participants & Winners')
                    ->icon(Heroicon::OutlinedUserGroup)
                    ->schema([
                        ViewEntry::make('categories_participants')
                            ->hiddenLabel()
                            ->columnSpanFull()
                            ->extraEntryWrapperAttributes(['class' => 'fi-w-full block'])
                            ->view('filament.resources.competitions.partials.categories-participants')
                            ->viewData(function (Competition $record, ViewCompetition $livewire): array {
                                return [
                                    'competition' => $record,
                                    'page' => $livewire,
                                ];
                            }),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}

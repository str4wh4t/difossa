<?php

namespace App\Filament\Resources\CompetitionCategories\Schemas;

use App\Models\Competition;
use App\Models\CompetitionStatus;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class CompetitionCategoryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Category')
                    ->icon(Heroicon::OutlinedTag)
                    ->schema([
                        TextEntry::make('name')
                            ->label('Name'),
                        TextEntry::make('slug')
                            ->label('Slug')
                            ->copyable(),
                        TextEntry::make('description')
                            ->label('Description')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Usage')
                    ->icon(Heroicon::OutlinedChartBar)
                    ->schema([
                        TextEntry::make('registered_competitions_count')
                            ->label('Competitions')
                            ->badge()
                            ->color('info'),
                        TextEntry::make('registrations_count')
                            ->label('Registrations')
                            ->badge()
                            ->color('warning'),
                        TextEntry::make('winners_count')
                            ->label('Winners')
                            ->badge()
                            ->color('success'),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),
                Section::make('Competitions with Registrations')
                    ->icon(Heroicon::OutlinedTrophy)
                    ->schema([
                        RepeatableEntry::make('registeredCompetitions')
                            ->hiddenLabel()
                            ->placeholder('No competitions have registrations in this category yet.')
                            ->table([
                                TableColumn::make('No'),
                                TableColumn::make('Title'),
                                TableColumn::make('Status'),
                                TableColumn::make('Registration Starts'),
                                TableColumn::make('Registration Ends'),
                            ])
                            ->schema([
                                TextEntry::make('no')
                                    ->hiddenLabel()
                                    ->state(fn (TextEntry $component): int => (int) $component->getContainer()->getStatePath(isAbsolute: false) + 1),
                                TextEntry::make('title'),
                                TextEntry::make('status.name')
                                    ->label('Status')
                                    ->badge()
                                    ->color(fn (Competition $record): string => match ($record->status?->slug) {
                                        CompetitionStatus::SLUG_DRAFT => 'gray',
                                        CompetitionStatus::SLUG_OPEN => 'success',
                                        CompetitionStatus::SLUG_CLOSED => 'warning',
                                        CompetitionStatus::SLUG_FINISHED => 'info',
                                        default => 'gray',
                                    })
                                    ->placeholder('-'),
                                TextEntry::make('registration_starts_at')
                                    ->label('Registration Starts')
                                    ->dateTime()
                                    ->placeholder('-'),
                                TextEntry::make('registration_ends_at')
                                    ->label('Registration Ends')
                                    ->dateTime()
                                    ->placeholder('-'),
                            ])
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}

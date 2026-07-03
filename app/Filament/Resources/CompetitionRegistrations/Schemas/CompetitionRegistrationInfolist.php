<?php

namespace App\Filament\Resources\CompetitionRegistrations\Schemas;

use App\Filament\Resources\Competitions\CompetitionResource;
use App\Models\CompetitionRegistration;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Icons\Heroicon;

class CompetitionRegistrationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Competition')
                    ->icon(Heroicon::OutlinedTrophy)
                    ->schema([
                        TextEntry::make('competition.title')
                            ->label('Competition')
                            ->icon(Heroicon::OutlinedLink)
                            ->iconPosition(IconPosition::After)
                            ->iconColor('primary')
                            ->weight(FontWeight::SemiBold)
                            ->color('primary')
                            ->url(fn (CompetitionRegistration $record): ?string => $record->competition && CompetitionResource::canView($record->competition)
                                ? CompetitionResource::getUrl('view', ['record' => $record->competition])
                                : null),
                        TextEntry::make('category.name')
                            ->label('Category'),
                        TextEntry::make('created_at')
                            ->label('Submitted At')
                            ->dateTime(),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),
                Section::make('Participant')
                    ->icon(Heroicon::OutlinedUser)
                    ->schema([
                        TextEntry::make('user.full_name')
                            ->label('Name')
                            ->placeholder(fn ($record) => $record->user?->name),
                        TextEntry::make('user.email')
                            ->label('Email')
                            ->icon(Heroicon::OutlinedEnvelope),
                        TextEntry::make('user.affiliation')
                            ->label('Affiliation')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Article')
                    ->icon(Heroicon::OutlinedDocumentText)
                    ->schema([
                        TextEntry::make('article_title')
                            ->label('Article Title')
                            ->columnSpanFull(),
                        TextEntry::make('article_summary')
                            ->label('Article Summary')
                            ->html()
                            ->prose()
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('article_file')
                            ->label('Article PDF')
                            ->formatStateUsing(fn (?string $state) => filled($state) ? basename($state) : null)
                            ->icon(Heroicon::Document)
                            ->iconColor('primary')
                            ->url(fn ($record) => $record->article_file_download_url)
                            ->placeholder('-'),
                    ])
                    ->columns(1)
                    ->columnSpanFull(),
                Section::make('Team Members')
                    ->icon(Heroicon::OutlinedUserGroup)
                    ->schema([
                        RepeatableEntry::make('members')
                            ->label('Members')
                            ->placeholder('No team members added.')
                            ->table([
                                TableColumn::make('No'),
                                TableColumn::make('Name'),
                                TableColumn::make('Email'),
                                TableColumn::make('Affiliation'),
                            ])
                            ->schema([
                                TextEntry::make('no')
                                    ->hiddenLabel()
                                    ->state(fn (TextEntry $component): int => (int) $component->getContainer()->getStatePath(isAbsolute: false) + 1),
                                TextEntry::make('name'),
                                TextEntry::make('email'),
                                TextEntry::make('affiliation'),
                            ])
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}

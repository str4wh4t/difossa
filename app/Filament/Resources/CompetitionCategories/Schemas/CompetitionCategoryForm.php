<?php

namespace App\Filament\Resources\CompetitionCategories\Schemas;

use App\Models\CompetitionCategory;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class CompetitionCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Grid::make([
                    'default' => 1,
                    'lg' => 3,
                ])
                    ->schema([
                        Section::make('Category Details')
                            ->description('Define a category that participants can select when registering.')
                            ->icon(Heroicon::OutlinedTag)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Name')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('e.g. Undergraduate')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state ?? '')))
                                    ->columnSpanFull(),
                                TextInput::make('slug')
                                    ->label('Slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->helperText('Auto-generated from the name. Used as a unique identifier.')
                                    ->columnSpanFull(),
                                Textarea::make('description')
                                    ->label('Description')
                                    ->rows(5)
                                    ->placeholder('Describe who this category is for and any eligibility notes...')
                                    ->columnSpanFull(),
                            ])
                            ->columns(1)
                            ->columnSpan([
                                'default' => 'full',
                                'lg' => 2,
                            ]),
                        Section::make('Competitions with Registrations')
                            ->description('Competitions that have at least one registration in this category.')
                            ->icon(Heroicon::OutlinedTrophy)
                            ->schema([
                                Placeholder::make('assigned_competitions')
                                    ->label('Competitions')
                                    ->content(fn (?CompetitionCategory $record): string | HtmlString => self::formatAssignedCompetitions($record))
                                    ->html(fn (?CompetitionCategory $record): bool => filled($record) && (
                                        $record->relationLoaded('registeredCompetitions')
                                            ? $record->registeredCompetitions->isNotEmpty()
                                            : $record->registeredCompetitions()->exists()
                                    ))
                                    ->columnSpanFull(),
                            ])
                            ->columns(1)
                            ->columnSpan([
                                'default' => 'full',
                                'lg' => 1,
                            ]),
                    ]),
            ]);
    }

    protected static function formatAssignedCompetitions(?CompetitionCategory $record): string | HtmlString
    {
        if (! $record) {
            return 'No competitions have registrations in this category yet.';
        }

        $titles = $record->relationLoaded('registeredCompetitions')
            ? $record->registeredCompetitions->sortBy('title')->pluck('title')
            : $record->registeredCompetitions()->orderBy('title')->pluck('title');

        if ($titles->isEmpty()) {
            return 'No competitions have registrations in this category yet.';
        }

        $items = $titles
            ->map(fn (string $title): string => '<li>'.e($title).'</li>')
            ->implode('');

        return new HtmlString('<ul class="list-disc space-y-1 ps-4">'.$items.'</ul>');
    }
}

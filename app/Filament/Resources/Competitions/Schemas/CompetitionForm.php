<?php

namespace App\Filament\Resources\Competitions\Schemas;

use App\Models\CompetitionStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;

class CompetitionForm
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
                        Section::make('Details')
                            ->description('Basic information shown to participants.')
                            ->icon(Heroicon::OutlinedTrophy)
                            ->schema([
                                TextInput::make('title')
                                    ->label('Title')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('e.g. National Research Competition 2026')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state ?? '')))
                                    ->columnSpanFull(),
                                TextInput::make('slug')
                                    ->label('Slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->helperText('Auto-generated from the title. Used in URLs.')
                                    ->columnSpanFull(),
                                RichEditor::make('description')
                                    ->label('Description')
                                    ->placeholder('Describe the competition, rules, and eligibility...')
                                    ->fileAttachmentsDirectory('competitions/descriptions')
                                    ->columnSpanFull()
                                    ->extraInputAttributes([
                                        'style' => 'min-height: 12rem;',
                                    ]),
                                FileUpload::make('banner_image')
                                    ->label('Banner Image')
                                    ->helperText('Recommended size: 1280×720 pixels. Only JPG and JPEG files are accepted.')
                                    ->image()
                                    ->acceptedFileTypes(['image/jpeg'])
                                    ->imageEditor()
                                    ->imagePreviewHeight('200')
                                    ->disk('public')
                                    ->directory('competitions')
                                    ->visibility('public')
                                    ->columnSpanFull(),
                            ])
                            ->columns(1)
                            ->columnSpan([
                                'default' => 'full',
                                'lg' => 2,
                            ]),
                        Group::make()
                            ->schema([
                                Section::make('Status')
                                    ->description('Control whether participants can register.')
                                    ->icon(Heroicon::OutlinedSignal)
                                    ->schema([
                                        Select::make('competition_status_id')
                                            ->label('Status')
                                            ->relationship('status', 'name')
                                            ->required()
                                            ->default(fn () => CompetitionStatus::idForSlug(CompetitionStatus::SLUG_DRAFT))
                                            ->native(false)
                                            ->preload()
                                            ->searchable()
                                            ->helperText('Open allows registration when the registration window is active.')
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(1),
                                Section::make('Registration Window')
                                    ->description('Optional start and end dates for registration.')
                                    ->icon(Heroicon::OutlinedCalendarDays)
                                    ->schema([
                                        DateTimePicker::make('registration_starts_at')
                                            ->label('Registration Starts')
                                            ->seconds(false)
                                            ->native(false)
                                            ->placeholder('No start limit')
                                            ->columnSpanFull(),
                                        DateTimePicker::make('registration_ends_at')
                                            ->label('Registration Ends')
                                            ->seconds(false)
                                            ->native(false)
                                            ->placeholder('No end limit')
                                            ->after('registration_starts_at')
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(1),
                            ])
                            ->columnSpan([
                                'default' => 'full',
                                'lg' => 1,
                            ]),
                    ]),
            ]);
    }
}

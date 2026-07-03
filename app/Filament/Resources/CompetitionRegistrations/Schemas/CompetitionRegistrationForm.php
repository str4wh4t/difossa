<?php

namespace App\Filament\Resources\CompetitionRegistrations\Schemas;

use App\Models\Competition;
use App\Models\CompetitionCategory;
use App\Models\CompetitionRegistration;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class CompetitionRegistrationForm
{
    public static function configure(Schema $schema, bool $isAdmin = false): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Competition')
                    ->description(fn(): string => request()->has('competition_id')
                        ? 'Competition and category are pre-selected from the registration link.'
                        : 'Choose the competition and category you want to register for.')
                    ->icon(Heroicon::OutlinedTrophy)
                    ->schema([
                        Select::make('competition_id')
                            ->label('Competition')
                            ->options(fn() => Competition::query()
                                ->openForRegistration()
                                ->pluck('title', 'id')
                                ->all())
                            ->searchable()
                            ->required()
                            ->live()
                            ->native(false)
                            ->preload()
                            ->afterStateUpdated(fn(callable $set) => $set('competition_category_id', null))
                            ->disabled(fn() => ! $isAdmin && request()->has('competition_id'))
                            ->dehydrated()
                            ->columnSpanFull(),
                        Select::make('competition_category_id')
                            ->label('Category')
                            ->options(function (Get $get) use ($isAdmin): array {
                                $competitionId = $get('competition_id');

                                if (blank($competitionId)) {
                                    return [];
                                }

                                $query = CompetitionCategory::query();

                                if (! $isAdmin && filled($competitionId)) {
                                    $user = self::authenticatedUser();

                                    if ($user) {
                                        $registeredIds = $user
                                            ->competitionRegistrations()
                                            ->where('competition_id', $competitionId)
                                            ->pluck('competition_category_id');

                                        $query->whereNotIn('id', $registeredIds);
                                    }
                                }

                                return $query->pluck('name', 'id')->all();
                            })
                            ->searchable()
                            ->required()
                            ->native(false)
                            ->preload()
                            ->disabled(fn() => ! $isAdmin && request()->has('competition_category_id'))
                            ->dehydrated()
                            ->helperText('You can register once per category in each competition.')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull()
                    ->visibleOn('create'),
                Section::make('Article')
                    ->description('Submit your research article details and PDF file.')
                    ->icon(Heroicon::OutlinedDocumentText)
                    ->schema([
                        TextInput::make('article_title')
                            ->label('Article Title')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Enter the full title of your article')
                            ->columnSpanFull(),
                        RichEditor::make('article_summary')
                            ->label('Article Summary')
                            ->required()
                            ->placeholder('Provide a brief summary of your research...')
                            ->helperText('A short abstract describing your article.')
                            ->fileAttachmentsDirectory('competition-registrations/summaries')
                            ->columnSpanFull()
                            ->extraInputAttributes([
                                'style' => 'min-height: 12rem;',
                            ]),
                        FileUpload::make('article_file')
                            ->label('Article PDF')
                            ->helperText('PDF only. Maximum file size: 2 MB.')
                            ->disk('public')
                            ->directory('competition-registrations')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(10240)
                            ->required(fn(?CompetitionRegistration $record) => $record === null)
                            ->downloadable()
                            ->openable()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                Section::make('Team Members')
                    ->description('Add all co-authors or team members for this submission.')
                    ->icon(Heroicon::OutlinedUserGroup)
                    ->schema([
                        Repeater::make('members')
                            ->relationship()
                            ->label('Members')
                            ->addActionLabel('Add team member')
                            ->itemLabel(fn(array $state): ?string => filled($state['name'] ?? null)
                                ? $state['name']
                                : 'New member')
                            ->collapsible()
                            ->cloneable()
                            ->schema([
                                TextInput::make('name')
                                    ->label('Name')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Full name'),
                                TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->required()
                                    ->maxLength(255)
                                    ->prefixIcon(Heroicon::OutlinedEnvelope),
                                TextInput::make('affiliation')
                                    ->label('Affiliation')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('e.g. Diponegoro University')
                                    ->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->minItems(1)
                            ->defaultItems(1)
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    protected static function authenticatedUser(): ?User
    {
        $user = Filament::auth()->user();

        return $user instanceof User ? $user : null;
    }
}

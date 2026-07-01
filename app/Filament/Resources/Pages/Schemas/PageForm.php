<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;

class PageForm
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
                        Section::make('Page')
                            ->description('Write the main content for this page.')
                            ->icon(Heroicon::OutlinedDocumentText)
                            ->schema([
                                TextInput::make('title')
                                    ->label('Title')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state ?? '')))
                                    ->columnSpanFull(),
                                TextInput::make('slug')
                                    ->prefix('/')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->columnSpanFull(),
                                RichEditor::make('content')
                                    ->label('Content')
                                    ->placeholder('Start writing your page...')
                                    ->fileAttachmentsDirectory('pages/attachments')
                                    ->columnSpanFull()
                                    ->extraInputAttributes([
                                        'style' => 'min-height: 28rem;',
                                    ]),
                            ])
                            ->columns(1)
                            ->columnSpan([
                                'default' => 'full',
                                'lg' => 2,
                            ]),
                        Group::make()
                            ->schema([
                                Section::make('Publication')
                                    ->icon(Heroicon::OutlinedCalendarDays)
                                    ->schema([
                                        DateTimePicker::make('published_at')
                                            ->label('Published At')
                                            ->native(false),
                                    ])
                                    ->columns(1),
                                Section::make('SEO')
                                    ->icon(Heroicon::OutlinedMagnifyingGlass)
                                    ->schema([
                                        TextInput::make('meta_title')
                                            ->label('Meta Title')
                                            ->maxLength(255),
                                        Textarea::make('meta_description')
                                            ->label('Meta Description')
                                            ->rows(4)
                                            ->columnSpanFull(),
                                    ])
                                    ->collapsed()
                                    ->columns(1),
                            ])
                            ->columnSpan([
                                'default' => 'full',
                                'lg' => 1,
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}

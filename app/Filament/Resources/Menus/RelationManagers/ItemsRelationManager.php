<?php

namespace App\Filament\Resources\Menus\RelationManagers;

use App\Models\Page;
use App\Models\Post;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'allItems';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('label')
                    ->label('Label')
                    ->required()
                    ->maxLength(255),
                Select::make('parent_id')
                    ->label('Parent')
                    ->options(function (): array {
                        return $this->getOwnerRecord()
                            ->allItems()
                            ->pluck('label', 'id')
                            ->all();
                    })
                    ->searchable()
                    ->nullable(),
                Select::make('linkable_type')
                    ->label('Link Type')
                    ->options([
                        '' => 'Custom URL',
                        Post::class => 'Post',
                        Page::class => 'Page',
                    ])
                    ->live()
                    ->afterStateUpdated(fn (callable $set) => $set('linkable_id', null))
                    ->dehydrateStateUsing(fn (?string $state) => filled($state) ? $state : null),
                TextInput::make('url')
                    ->label('URL')
                    ->maxLength(255)
                    ->visible(fn (Get $get): bool => blank($get('linkable_type')))
                    ->dehydrated(fn (Get $get): bool => blank($get('linkable_type'))),
                Select::make('linkable_id')
                    ->label(fn (Get $get): string => match ($get('linkable_type')) {
                        Post::class => 'Post',
                        Page::class => 'Page',
                        default => 'Content',
                    })
                    ->options(fn (Get $get): array => match ($get('linkable_type')) {
                        Post::class => Post::query()->pluck('title', 'id')->all(),
                        Page::class => Page::query()->pluck('title', 'id')->all(),
                        default => [],
                    })
                    ->searchable()
                    ->visible(fn (Get $get): bool => filled($get('linkable_type')))
                    ->required(fn (Get $get): bool => filled($get('linkable_type')))
                    ->dehydrated(fn (Get $get): bool => filled($get('linkable_type'))),
                TextInput::make('sort_order')
                    ->label('Sort Order')
                    ->numeric()
                    ->default(0)
                    ->required(),
                Select::make('target')
                    ->options([
                        '_self' => 'Same tab',
                        '_blank' => 'New tab',
                    ])
                    ->default('_self')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('label')
                    ->label('Label')
                    ->searchable(),
                TextColumn::make('parent.label')
                    ->label('Parent')
                    ->placeholder('-'),
                TextColumn::make('url')
                    ->label('URL')
                    ->placeholder('-')
                    ->toggleable(),
                TextColumn::make('linkable.title')
                    ->label('Content')
                    ->placeholder('-'),
                TextColumn::make('sort_order')
                    ->label('Sort Order')
                    ->sortable(),
                TextColumn::make('target')
                    ->label('Target')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order')
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}

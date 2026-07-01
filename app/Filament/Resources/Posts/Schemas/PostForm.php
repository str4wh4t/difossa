<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Models\PostStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;

class PostForm
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
                        Section::make('Post')
                            ->description('Write the main content for this post.')
                            ->icon(Heroicon::OutlinedPencilSquare)
                            ->schema([
                                TextInput::make('title')
                                    ->label('Title')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state ?? '')))
                                    ->columnSpanFull(),
                                TextInput::make('slug')
                                    ->prefix('/blog/')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->columnSpanFull(),
                                Textarea::make('excerpt')
                                    ->label('Excerpt')
                                    ->rows(3)
                                    ->placeholder('A short summary shown in post listings.')
                                    ->columnSpanFull(),
                                RichEditor::make('content')
                                    ->label('Content')
                                    ->placeholder('Start writing your post...')
                                    ->fileAttachmentsDirectory('posts/attachments')
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
                                        Select::make('post_status_id')
                                            ->label('Status')
                                            ->relationship('status', 'name')
                                            ->required()
                                            ->default(fn () => PostStatus::query()->where('slug', 'draft')->value('id'))
                                            ->live()
                                            ->afterStateUpdated(function (Set $set, ?int $state): void {
                                                $slug = PostStatus::query()->find($state)?->slug;

                                                if ($slug !== 'published') {
                                                    $set('published_at', null);
                                                }
                                            }),
                                        DateTimePicker::make('published_at')
                                            ->label('Published At')
                                            ->native(false)
                                            ->visible(fn (Get $get): bool => PostStatus::query()->find($get('post_status_id'))?->slug === 'published'),
                                        Toggle::make('is_sticky')
                                            ->label('Sticky Post')
                                            ->helperText('Show in the home slider and as featured posts on the blog page.')
                                            ->default(false),
                                        FileUpload::make('featured_image')
                                            ->label('Featured Image')
                                            ->helperText('Recommended size: 1280×720 pixels. Only JPG and JPEG files are accepted.')
                                            ->image()
                                            ->acceptedFileTypes(['image/jpeg'])
                                            ->imageEditor()
                                            ->imagePreviewHeight('200')
                                            ->disk('public')
                                            ->directory('posts')
                                            ->visibility('public')
                                            ->columnSpanFull(),
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

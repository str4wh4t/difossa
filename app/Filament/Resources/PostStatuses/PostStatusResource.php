<?php

namespace App\Filament\Resources\PostStatuses;

use App\Filament\Resources\PostStatuses\Pages\CreatePostStatus;
use App\Filament\Resources\PostStatuses\Pages\EditPostStatus;
use App\Filament\Resources\PostStatuses\Pages\ListPostStatuses;
use App\Filament\Resources\PostStatuses\Schemas\PostStatusForm;
use App\Filament\Resources\PostStatuses\Tables\PostStatusesTable;
use App\Models\PostStatus;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PostStatusResource extends Resource
{
    protected static ?string $model = PostStatus::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static string|UnitEnum|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 4;

    protected static ?string $modelLabel = 'post status';

    protected static ?string $pluralModelLabel = 'post statuses';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return PostStatusForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PostStatusesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPostStatuses::route('/'),
            'create' => CreatePostStatus::route('/create'),
            'edit' => EditPostStatus::route('/{record}/edit'),
        ];
    }
}

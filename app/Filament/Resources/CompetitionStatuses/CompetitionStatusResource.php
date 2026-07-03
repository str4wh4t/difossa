<?php

namespace App\Filament\Resources\CompetitionStatuses;

use App\Filament\Resources\CompetitionStatuses\Pages\CreateCompetitionStatus;
use App\Filament\Resources\CompetitionStatuses\Pages\EditCompetitionStatus;
use App\Filament\Resources\CompetitionStatuses\Pages\ListCompetitionStatuses;
use App\Filament\Resources\CompetitionStatuses\Schemas\CompetitionStatusForm;
use App\Filament\Resources\CompetitionStatuses\Tables\CompetitionStatusesTable;
use App\Models\CompetitionStatus;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CompetitionStatusResource extends Resource
{
    protected static ?string $model = CompetitionStatus::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAdjustmentsHorizontal;

    protected static string|UnitEnum|null $navigationGroup = 'Competitions';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'status';

    protected static ?string $pluralModelLabel = 'statuses';

    protected static ?string $navigationLabel = 'Statuses';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return CompetitionStatusForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CompetitionStatusesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCompetitionStatuses::route('/'),
            'create' => CreateCompetitionStatus::route('/create'),
            'edit' => EditCompetitionStatus::route('/{record}/edit'),
        ];
    }
}

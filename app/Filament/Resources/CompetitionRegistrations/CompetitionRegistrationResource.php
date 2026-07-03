<?php

namespace App\Filament\Resources\CompetitionRegistrations;

use App\Filament\Resources\CompetitionRegistrations\Pages\CreateCompetitionRegistration;
use App\Filament\Resources\CompetitionRegistrations\Pages\EditCompetitionRegistration;
use App\Filament\Resources\CompetitionRegistrations\Pages\ListCompetitionRegistrations;
use App\Filament\Resources\CompetitionRegistrations\Pages\ViewCompetitionRegistration;
use App\Filament\Resources\CompetitionRegistrations\Schemas\CompetitionRegistrationForm;
use App\Filament\Resources\CompetitionRegistrations\Schemas\CompetitionRegistrationInfolist;
use App\Filament\Resources\CompetitionRegistrations\Tables\CompetitionRegistrationsTable;
use App\Models\CompetitionRegistration;
use App\Models\User;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class CompetitionRegistrationResource extends Resource
{
    protected static ?string $model = CompetitionRegistration::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static string|UnitEnum|null $navigationGroup = 'Competitions';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'registration';

    protected static ?string $pluralModelLabel = 'registrations';

    protected static ?string $recordTitleAttribute = 'article_title';

    public static function getNavigationSort(): ?int
    {
        if (self::authenticatedUser()?->canManageCompetitions()) {
            return 2;
        }

        return static::$navigationSort;
    }

    public static function form(Schema $schema): Schema
    {
        return CompetitionRegistrationForm::configure(
            $schema,
            isAdmin: self::authenticatedUser()?->canManageCompetitions() ?? false,
        );
    }

    public static function infolist(Schema $schema): Schema
    {
        return CompetitionRegistrationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CompetitionRegistrationsTable::configure(
            $table,
            isAdmin: self::authenticatedUser()?->canManageCompetitions() ?? false,
        );
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCompetitionRegistrations::route('/'),
            'create' => CreateCompetitionRegistration::route('/create'),
            'view' => ViewCompetitionRegistration::route('/{record}'),
            'edit' => EditCompetitionRegistration::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $user = self::authenticatedUser();

        if ($user && ! $user->canManageCompetitions()) {
            $query->where('user_id', $user->id);
        }

        return $query;
    }

    public static function canCreate(): bool
    {
        return parent::canCreate();
    }

    protected static function authenticatedUser(): ?User
    {
        $user = Filament::auth()->user();

        return $user instanceof User ? $user : null;
    }
}

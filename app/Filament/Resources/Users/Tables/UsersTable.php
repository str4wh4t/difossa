<?php

namespace App\Filament\Resources\Users\Tables;

use App\Filament\Resources\Users\Schemas\UserForm;
use App\Models\User;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use STS\FilamentImpersonate\Actions\Impersonate;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('full_name')
                    ->label('Full Name')
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('roles.name')
                    ->label('Role')
                    ->badge()
                    ->formatStateUsing(fn(?string $state): string => $state
                        ? UserForm::formatRoleLabel($state)
                        : '-')
                    ->color(fn(?string $state): string => match ($state) {
                        User::ROLE_SUPER_ADMIN => 'danger',
                        User::ROLE_ADMIN => 'warning',
                        User::ROLE_PARTICIPANT => 'info',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('affiliation')
                    ->label('Affiliation')
                    ->placeholder('-')
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('roles')
                    ->label('Role')
                    ->relationship(
                        'roles',
                        'name',
                        fn($query) => $query->where('name', '!=', User::ROLE_SUPER_ADMIN),
                    ),
            ])
            ->recordActions([
                Impersonate::make()
                    ->redirectTo(fn() => Filament::getPanel('admin')->getUrl()),
                EditAction::make(),
            ])
            ->defaultSort('name');
    }
}

<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Spatie\Permission\Models\Role;

class UserForm
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
                        Section::make('Profile')
                            ->description('Public profile details used for competition registration and display.')
                            ->icon(Heroicon::OutlinedUserCircle)
                            ->schema([
                                TextInput::make('full_name')
                                    ->label('Full Name')
                                    ->maxLength(255)
                                    ->placeholder('e.g. John Doe')
                                    ->columnSpanFull(),
                                TextInput::make('affiliation')
                                    ->label('Affiliation')
                                    ->maxLength(255)
                                    ->placeholder('e.g. Diponegoro University')
                                    ->helperText('Organization or institution the user represents.')
                                    ->columnSpanFull(),
                                TextInput::make('google_scholar_url')
                                    ->label('Google Scholar URL')
                                    ->url()
                                    ->maxLength(255)
                                    ->placeholder('https://scholar.google.com/citations?user=...')
                                    ->prefixIcon(Heroicon::OutlinedLink)
                                    ->columnSpanFull(),
                            ])
                            ->columns(1)
                            ->columnSpan([
                                'default' => 'full',
                                'lg' => 2,
                            ]),
                        Group::make()
                            ->schema([
                                Section::make('Account')
                                    ->description('Login credentials and display name.')
                                    ->icon(Heroicon::OutlinedIdentification)
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Display Name')
                                            ->required()
                                            ->maxLength(255)
                                            ->placeholder('Short name shown in the panel')
                                            ->columnSpanFull(),
                                        TextInput::make('email')
                                            ->label('Email Address')
                                            ->email()
                                            ->required()
                                            ->maxLength(255)
                                            ->unique(ignoreRecord: true)
                                            ->prefixIcon(Heroicon::OutlinedEnvelope)
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(1),
                                Section::make('Access')
                                    ->description('Assign one role to control panel permissions.')
                                    ->icon(Heroicon::OutlinedShieldCheck)
                                    ->schema([
                                        Select::make('roles')
                                            ->label('Role')
                                            ->relationship(
                                                name: 'roles',
                                                titleAttribute: 'name',
                                                modifyQueryUsing: fn ($query) => $query->orderBy('name'),
                                            )
                                            ->getOptionLabelFromRecordUsing(
                                                fn (Role $role): string => self::formatRoleLabel($role->name),
                                            )
                                            ->required()
                                            ->preload()
                                            ->searchable()
                                            ->native(false)
                                            ->default(fn () => Role::query()
                                                ->where('name', User::ROLE_PARTICIPANT)
                                                ->value('id'))
                                            ->helperText('Super Admin can manage users and roles. Admin manages content and competitions. Participant can register for competitions.')
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

    public static function formatRoleLabel(string $role): string
    {
        return match ($role) {
            User::ROLE_SUPER_ADMIN => 'Super Admin',
            User::ROLE_ADMIN => 'Admin',
            User::ROLE_PARTICIPANT => 'Participant',
            default => ucfirst(str_replace('_', ' ', $role)),
        };
    }
}

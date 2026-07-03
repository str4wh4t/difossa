<?php

namespace App\Filament\Pages;

use App\Models\User;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use UnitEnum;

class EditProfile extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserCircle;

    protected static ?string $navigationLabel = 'My Profile';

    protected static string|UnitEnum|null $navigationGroup = 'Access';

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'my-profile';

    protected static ?string $title = 'My Profile';

    protected Width|string|null $maxContentWidth = Width::Full;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $user = $this->authenticatedUser();

        if ($user) {
            $this->form->fill($user->only([
                'name',
                'full_name',
                'affiliation',
                'google_scholar_url',
                'email',
            ]));
        }
    }

    public function form(Schema $schema): Schema
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
                            ->description('Public details shown on competition registrations.')
                            ->icon(Heroicon::OutlinedUserCircle)
                            ->schema([
                                TextInput::make('full_name')
                                    ->label('Full Name')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('e.g. John Doe')
                                    ->columnSpanFull(),
                                TextInput::make('affiliation')
                                    ->label('Affiliation')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('e.g. Diponegoro University')
                                    ->helperText('Organization or institution you represent.')
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
                                    ->description('Login email and display name in the panel.')
                                    ->icon(Heroicon::OutlinedIdentification)
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Display Name')
                                            ->disabled()
                                            ->dehydrated(false)
                                            ->maxLength(255)
                                            ->helperText('Display name is managed by an administrator.')
                                            ->columnSpanFull(),
                                        TextInput::make('email')
                                            ->label('Email Address')
                                            ->email()
                                            ->required()
                                            ->maxLength(255)
                                            ->prefixIcon(Heroicon::OutlinedEnvelope)
                                            ->unique(User::class, ignorable: fn () => $this->authenticatedUser())
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(1),
                                Section::make('Security')
                                    ->description('Leave password fields blank to keep your current password.')
                                    ->icon(Heroicon::OutlinedKey)
                                    ->schema([
                                        TextInput::make('password')
                                            ->label('New Password')
                                            ->password()
                                            ->revealable(filament()->arePasswordsRevealable())
                                            ->rule(Password::default())
                                            ->autocomplete('new-password')
                                            ->dehydrated(fn (?string $state): bool => filled($state))
                                            ->dehydrateStateUsing(fn (?string $state): ?string => filled($state) ? Hash::make($state) : null)
                                            ->live(debounce: 500)
                                            ->same('passwordConfirmation')
                                            ->columnSpanFull(),
                                        TextInput::make('passwordConfirmation')
                                            ->label('Confirm New Password')
                                            ->password()
                                            ->revealable(filament()->arePasswordsRevealable())
                                            ->autocomplete('new-password')
                                            ->dehydrated(false)
                                            ->required(fn (Get $get): bool => filled($get('password')))
                                            ->visible(fn (Get $get): bool => filled($get('password')))
                                            ->columnSpanFull(),
                                        TextInput::make('currentPassword')
                                            ->label('Current Password')
                                            ->password()
                                            ->revealable(filament()->arePasswordsRevealable())
                                            ->autocomplete('current-password')
                                            ->currentPassword(guard: Filament::getAuthGuard())
                                            ->dehydrated(false)
                                            ->required(fn (Get $get): bool => filled($get('password')))
                                            ->visible(fn (Get $get): bool => filled($get('password')))
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(1),
                            ])
                            ->columnSpan([
                                'default' => 'full',
                                'lg' => 1,
                            ]),
                    ]),
            ])
            ->model(fn () => $this->authenticatedUser())
            ->statePath('data');
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([
                    EmbeddedSchema::make('form'),
                ])
                    ->id('form')
                    ->livewireSubmitHandler('save')
                    ->footer([
                        Actions::make([
                            Action::make('save')
                                ->label('Save changes')
                                ->submit('save')
                                ->keyBindings(['mod+s']),
                        ])
                            ->alignment(Alignment::Start)
                            ->sticky()
                            ->key('form-actions'),
                    ]),
            ]);
    }

    public function save(): void
    {
        $user = $this->authenticatedUser();

        if (! $user) {
            return;
        }

        $user->update($this->form->getState());

        Notification::make()
            ->title('Profile updated')
            ->body($user->hasParticipantProfile()
                ? 'Your profile is complete and ready for competition registration.'
                : 'Please fill in your full name and affiliation to register for competitions.')
            ->success()
            ->send();
    }

    public function getTitle(): string|Htmlable
    {
        return static::$title ?? 'My Profile';
    }

    public function getSubheading(): string|Htmlable|null
    {
        $user = $this->authenticatedUser();

        if (! $user) {
            return null;
        }

        if (! $user->hasParticipantProfile()) {
            return 'Complete your full name and affiliation to register for competitions.';
        }

        return 'Keep your profile up to date for competition registration.';
    }

    public static function canAccess(): bool
    {
        return Filament::auth()->check();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Filament::auth()->check();
    }

    protected function authenticatedUser(): ?User
    {
        $user = Filament::auth()->user();

        return $user instanceof User ? $user : null;
    }
}

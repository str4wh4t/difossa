<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;
use Illuminate\Support\Facades\Hash;
use STS\FilamentImpersonate\Actions\Impersonate;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    public const DEFAULT_PASSWORD = '12345678';

    protected Width|string|null $maxContentWidth = Width::Full;

    protected function getHeaderActions(): array
    {
        return [
            Impersonate::make()
                ->record($this->getRecord())
                ->redirectTo(fn () => Filament::getPanel('admin')->getUrl()),
            Action::make('resetPassword')
                ->label('Reset Password')
                ->icon('heroicon-o-key')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Reset password?')
                ->modalDescription('The user password will be reset to the default value.')
                ->action(function (): void {
                    $this->record->update([
                        'password' => Hash::make(self::DEFAULT_PASSWORD),
                    ]);

                    Notification::make()
                        ->title('Password reset')
                        ->body('Password has been reset to '.self::DEFAULT_PASSWORD.'.')
                        ->success()
                        ->send();
                }),
            DeleteAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Pages\Auth;

use Filament\Actions\Action;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Facades\Filament;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\RenderHook;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\Support\Htmlable;
use MarcoGermani87\FilamentCaptcha\Forms\Components\CaptchaField;

class Login extends BaseLogin
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
                CaptchaField::make('captcha'),
            ]);
    }

    public function content(Schema $schema): Schema
    {
        $components = [
            RenderHook::make(PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE),
            $this->getFormContentComponent(),
            $this->getMultiFactorChallengeFormContentComponent(),
        ];

        if (Filament::hasRegistration()) {
            $components[] = Actions::make([
                $this->getSignUpAction(),
            ])->fullWidth();
        }

        $components[] = Actions::make([
            $this->getBackToHomeAction(),
        ])->fullWidth();

        $components[] = RenderHook::make(PanelsRenderHook::AUTH_LOGIN_FORM_AFTER);

        return $schema->components($components);
    }

    public function getSubheading(): string | Htmlable | null
    {
        if (filled($this->userUndertakingMultiFactorAuthentication)) {
            return parent::getSubheading();
        }

        return null;
    }

    protected function getSignUpAction(): Action
    {
        return Action::make('register')
            ->label('Register')
            ->color('info')
            ->url(Filament::getRegistrationUrl());
    }

    protected function getBackToHomeAction(): Action
    {
        return Action::make('backToHome')
            ->label('Back to home page')
            ->color('gray')
            ->icon(Heroicon::OutlinedHome)
            ->url(route('home'));
    }
}

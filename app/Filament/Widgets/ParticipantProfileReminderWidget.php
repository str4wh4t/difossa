<?php

namespace App\Filament\Widgets;

use App\Filament\Pages\EditProfile;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Widgets\Widget;

class ParticipantProfileReminderWidget extends Widget
{
    protected static ?int $sort = -1;

    protected int|string|array $columnSpan = 'full';

    protected string $view = 'filament.widgets.participant-profile-reminder';

    public function getEditProfileUrl(): string
    {
        return EditProfile::getUrl();
    }

    public static function canView(): bool
    {
        $user = Filament::auth()->user();

        if (! $user instanceof User) {
            return false;
        }

        return $user->isParticipant() && ! $user->hasParticipantProfile();
    }
}

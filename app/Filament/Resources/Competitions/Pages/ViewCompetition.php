<?php

namespace App\Filament\Resources\Competitions\Pages;

use App\Filament\Resources\CompetitionRegistrations\CompetitionRegistrationResource;
use App\Filament\Resources\Competitions\CompetitionResource;
use App\Filament\Resources\Competitions\Concerns\ManagesCategoryWinners;
use App\Models\Competition;
use App\Models\CompetitionCategory;
use App\Models\CompetitionRegistration;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\Width;

class ViewCompetition extends ViewRecord
{
    use ManagesCategoryWinners;

    protected static string $resource = CompetitionResource::class;

    protected Width|string|null $maxContentWidth = Width::Full;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function resolveRecord(int | string $key): Competition
    {
        return parent::resolveRecord($key)->load([
            'status',
            'registrations.user',
            'registrations.category',
            'registrations.members',
        ]);
    }

    /**
     * @return array{url: string, disabled: bool, label: string, visible: bool}
     */
    public function getCategoryRegisterAction(Competition $competition, CompetitionCategory $category): array
    {
        $user = $this->authenticatedUser();

        if (! $user || ! $competition->isOpenForRegistration()) {
            return [
                'url' => '#',
                'disabled' => true,
                'label' => 'Register',
                'visible' => false,
            ];
        }

        if (! $user->can('Create:CompetitionRegistration')) {
            return [
                'url' => '#',
                'disabled' => true,
                'label' => 'Register',
                'visible' => false,
            ];
        }

        $profileComplete = $user->hasParticipantProfile();

        if ($user->isParticipant() && ! $profileComplete) {
            return [
                'url' => '#',
                'disabled' => true,
                'label' => 'Register',
                'visible' => true,
            ];
        }

        if (! CompetitionRegistrationResource::canCreate()) {
            return [
                'url' => '#',
                'disabled' => true,
                'label' => 'Register',
                'visible' => false,
            ];
        }

        $alreadyRegistered = CompetitionRegistration::query()
            ->where('user_id', $user->id)
            ->where('competition_id', $competition->id)
            ->where('competition_category_id', $category->id)
            ->exists();

        return [
            'url' => CompetitionRegistrationResource::getUrl('create', [
                'competition_id' => $competition->id,
                'competition_category_id' => $category->id,
            ]),
            'disabled' => $alreadyRegistered,
            'label' => $alreadyRegistered ? 'Registered' : 'Register',
            'visible' => true,
        ];
    }

    public function getCompetition(): Competition
    {
        return $this->getRecord();
    }

    public function canViewParticipantsList(): bool
    {
        return $this->authenticatedUser()?->canManageCompetitions() ?? false;
    }

    protected function authenticatedUser(): ?User
    {
        $user = Filament::auth()->user();

        return $user instanceof User ? $user : null;
    }
}

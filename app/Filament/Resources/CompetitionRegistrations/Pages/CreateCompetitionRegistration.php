<?php

namespace App\Filament\Resources\CompetitionRegistrations\Pages;

use App\Filament\Pages\EditProfile;
use App\Filament\Resources\CompetitionRegistrations\CompetitionRegistrationResource;
use App\Models\Competition;
use App\Models\CompetitionCategory;
use App\Models\User;
use App\Services\CompetitionRegistrationService;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;
use Illuminate\Validation\ValidationException;

class CreateCompetitionRegistration extends CreateRecord
{
    protected static string $resource = CompetitionRegistrationResource::class;

    protected Width|string|null $maxContentWidth = Width::Full;

    public function mount(): void
    {
        $user = Filament::auth()->user();

        if ($user instanceof User && ! $user->canManageCompetitions() && ! $user->hasParticipantProfile()) {
            Notification::make()
                ->warning()
                ->title('Complete your profile')
                ->body('Please set your full name and affiliation before registering for a competition.')
                ->send();

            $this->redirect(EditProfile::getUrl());

            return;
        }

        parent::mount();

        $competitionId = request()->query('competition_id');
        $categoryId = request()->query('competition_category_id');

        if ($competitionId) {
            $this->form->fill([
                'competition_id' => (int) $competitionId,
                'competition_category_id' => $categoryId ? (int) $categoryId : null,
            ]);
        }
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Filament::auth()->user();

        if (! $user instanceof User) {
            throw ValidationException::withMessages([
                'profile' => 'You must be logged in to register.',
            ]);
        }

        $service = app(CompetitionRegistrationService::class);

        if (! $user->hasParticipantProfile()) {
            throw ValidationException::withMessages([
                'profile' => 'Please complete your full name and affiliation in your profile before registering.',
            ]);
        }

        $competition = Competition::query()->findOrFail($data['competition_id']);
        $category = CompetitionCategory::query()->findOrFail($data['competition_category_id']);
        $service->assertUserCanRegister($user, $competition, $category);

        $data['user_id'] = $user->id;

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return CompetitionRegistrationResource::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Registration submitted')
            ->body('Your competition registration has been submitted successfully.');
    }
}

<?php

namespace App\Filament\Resources\Competitions\Concerns;

use App\Models\Competition;
use App\Models\CompetitionCategory;
use App\Models\CompetitionCategoryWinner;
use App\Models\CompetitionRegistration;
use App\Services\CompetitionRegistrationService;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

trait ManagesCategoryWinners
{
    public function canManageWinners(): bool
    {
        $user = $this->authenticatedUser();

        return ($user?->canManageCompetitions() ?? false)
            && $this->getRecord()->isFinished();
    }

    /**
     * @return Collection<int, CompetitionCategoryWinner>
     */
    public function getCategoryWinners(Competition $competition, CompetitionCategory $category): Collection
    {
        return CompetitionCategoryWinner::query()
            ->where('competition_category_id', $category->id)
            ->whereHas(
                'registration',
                fn ($query) => $query->where('competition_id', $competition->id),
            )
            ->with(['registration.user'])
            ->orderBy('rank')
            ->get();
    }

    public function assignCategoryWinnerAction(): Action
    {
        return Action::make('assignCategoryWinner')
            ->label('Assign winner')
            ->icon(Heroicon::OutlinedTrophy)
            ->modalHeading('Assign winner')
            ->modalSubmitActionLabel('Assign')
            ->schema([
                TextInput::make('rank')
                    ->label('Rank')
                    ->numeric()
                    ->required()
                    ->minValue(1),
                Select::make('competition_registration_id')
                    ->label('Registration')
                    ->options(function (): array {
                        $categoryId = (int) ($this->getMountedAction()?->getArguments()['categoryId'] ?? 0);

                        if ($categoryId === 0) {
                            return [];
                        }

                        return $this->getWinnerRegistrationOptions($categoryId);
                    })
                    ->searchable()
                    ->required(),
            ])
            ->action(function (array $data, array $arguments): void {
                $this->handleAssignWinner($data, $arguments);
            });
    }

    public function editCategoryWinnerAction(): Action
    {
        return Action::make('editCategoryWinner')
            ->label('Edit')
            ->icon(Heroicon::OutlinedPencilSquare)
            ->modalHeading('Edit winner')
            ->modalSubmitActionLabel('Save')
            ->schema([
                TextInput::make('rank')
                    ->label('Rank')
                    ->numeric()
                    ->required()
                    ->minValue(1),
            ])
            ->fillForm(function (array $arguments): array {
                $winner = CompetitionCategoryWinner::query()->find($arguments['winnerId']);

                return [
                    'rank' => $winner?->rank,
                ];
            })
            ->action(function (array $data, array $arguments): void {
                $this->handleEditWinner($data, $arguments);
            });
    }

    public function deleteCategoryWinnerAction(): Action
    {
        return Action::make('deleteCategoryWinner')
            ->label('Remove')
            ->icon(Heroicon::OutlinedTrash)
            ->color('danger')
            ->modalHeading('Remove winner')
            ->modalDescription('Are you sure you want to remove this winner assignment?')
            ->requiresConfirmation()
            ->action(function (array $arguments): void {
                $this->handleDeleteWinner($arguments);
            });
    }

    /**
     * @return array<int, string>
     */
    public function getWinnerRegistrationOptions(int $categoryId): array
    {
        /** @var Competition $competition */
        $competition = $this->getRecord();

        return CompetitionRegistration::query()
            ->where('competition_id', $competition->id)
            ->where('competition_category_id', $categoryId)
            ->whereDoesntHave('winner')
            ->with('user')
            ->orderBy('article_title')
            ->get()
            ->mapWithKeys(function (CompetitionRegistration $registration): array {
                $participant = $registration->user?->full_name
                    ?? $registration->user?->name
                    ?? 'Unknown';

                return [
                    $registration->id => sprintf('%s (%s)', $registration->article_title, $participant),
                ];
            })
            ->all();
    }

    protected function handleAssignWinner(array $data, array $arguments): void
    {
        $this->assertCanManageWinnersAction();

        $category = CompetitionCategory::query()->findOrFail($arguments['categoryId']);

        app(CompetitionRegistrationService::class)->assertRegistrationBelongsToCategory(
            $this->getRecord(),
            $category,
            (int) $data['competition_registration_id'],
        );

        CompetitionCategoryWinner::query()->create([
            'competition_category_id' => $category->id,
            'competition_registration_id' => $data['competition_registration_id'],
            'rank' => $data['rank'],
        ]);

        Notification::make()
            ->title('Winner assigned')
            ->success()
            ->send();
    }

    protected function handleEditWinner(array $data, array $arguments): void
    {
        $this->assertCanManageWinnersAction();

        $winner = CompetitionCategoryWinner::query()->findOrFail($arguments['winnerId']);

        $winner->update([
            'rank' => $data['rank'],
        ]);

        Notification::make()
            ->title('Winner updated')
            ->success()
            ->send();
    }

    protected function handleDeleteWinner(array $arguments): void
    {
        $this->assertCanManageWinnersAction();

        CompetitionCategoryWinner::query()
            ->whereKey($arguments['winnerId'])
            ->delete();

        Notification::make()
            ->title('Winner removed')
            ->success()
            ->send();
    }

    protected function assertCanManageWinnersAction(): void
    {
        if (! $this->canManageWinners()) {
            throw ValidationException::withMessages([
                'winner' => 'Winners can only be managed when the competition status is Finished.',
            ]);
        }

        app(CompetitionRegistrationService::class)->assertCanAssignWinner($this->getRecord());
    }
}

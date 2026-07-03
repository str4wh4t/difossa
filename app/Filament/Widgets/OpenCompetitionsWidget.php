<?php

namespace App\Filament\Widgets;

use App\Filament\Concerns\ConfiguresCompetitionGridList;
use App\Filament\Resources\CompetitionRegistrations\CompetitionRegistrationResource;
use App\Filament\Resources\Competitions\CompetitionResource;
use App\Models\Competition;
use Filament\Facades\Filament;
use Filament\Widgets\Widget;
use Illuminate\Support\Collection;
use Wezlo\FilamentGridList\GridListConfiguration;

class OpenCompetitionsWidget extends Widget
{
    use ConfiguresCompetitionGridList;

    protected static ?int $sort = 0;

    protected int|string|array $columnSpan = 'full';

    protected string $view = 'filament.widgets.open-competitions';

    protected ?GridListConfiguration $gridListConfiguration = null;

    /**
     * @return Collection<int, Competition>
     */
    public function getCompetitions(): Collection
    {
        return Competition::query()
            ->openForRegistration()
            ->with('status')
            ->orderBy('registration_ends_at')
            ->get();
    }

    public function getGridListConfiguration(): GridListConfiguration
    {
        if ($this->gridListConfiguration === null) {
            $this->gridListConfiguration = $this->configureCompetitionGridList(GridListConfiguration::make())
                ->recordUrl(fn (Competition $record): string => $this->canRegister()
                    ? CompetitionRegistrationResource::getUrl('create', ['competition_id' => $record->id])
                    : CompetitionResource::getUrl('view', ['record' => $record]));
        }

        return $this->gridListConfiguration;
    }

    public function resolveRecordUrl(Competition $record): ?string
    {
        return $this->getGridListConfiguration()->getRecordUrl($record);
    }

    public function canRegister(): bool
    {
        return CompetitionRegistrationResource::canCreate();
    }

    public static function canView(): bool
    {
        return Filament::auth()->check();
    }
}

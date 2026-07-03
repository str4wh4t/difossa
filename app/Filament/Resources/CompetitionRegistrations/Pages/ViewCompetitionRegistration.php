<?php

namespace App\Filament\Resources\CompetitionRegistrations\Pages;

use App\Filament\Resources\CompetitionRegistrations\CompetitionRegistrationResource;
use App\Models\CompetitionRegistration;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\Width;

class ViewCompetitionRegistration extends ViewRecord
{
    protected static string $resource = CompetitionRegistrationResource::class;

    protected Width|string|null $maxContentWidth = Width::Full;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function resolveRecord(int | string $key): CompetitionRegistration
    {
        return parent::resolveRecord($key)->load([
            'competition.status',
            'category',
            'user',
            'members',
        ]);
    }
}

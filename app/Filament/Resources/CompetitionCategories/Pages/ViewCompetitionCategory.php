<?php

namespace App\Filament\Resources\CompetitionCategories\Pages;

use App\Filament\Resources\CompetitionCategories\CompetitionCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\Width;

class ViewCompetitionCategory extends ViewRecord
{
    protected static string $resource = CompetitionCategoryResource::class;

    protected Width|string|null $maxContentWidth = Width::Full;

    protected function resolveRecord(int | string $key): \Illuminate\Database\Eloquent\Model
    {
        return parent::resolveRecord($key)->loadCount([
            'registeredCompetitions',
            'registrations',
            'winners',
        ])->load([
            'registeredCompetitions.status',
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make(),
        ];
    }
}

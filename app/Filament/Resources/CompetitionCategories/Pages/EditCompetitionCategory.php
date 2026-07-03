<?php

namespace App\Filament\Resources\CompetitionCategories\Pages;

use App\Filament\Resources\CompetitionCategories\CompetitionCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditCompetitionCategory extends EditRecord
{
    protected static string $resource = CompetitionCategoryResource::class;

    protected Width|string|null $maxContentWidth = Width::Full;

    protected function resolveRecord(int | string $key): \Illuminate\Database\Eloquent\Model
    {
        return parent::resolveRecord($key)->load('registeredCompetitions');
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

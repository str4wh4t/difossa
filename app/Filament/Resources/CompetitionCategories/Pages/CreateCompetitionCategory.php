<?php

namespace App\Filament\Resources\CompetitionCategories\Pages;

use App\Filament\Resources\CompetitionCategories\CompetitionCategoryResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateCompetitionCategory extends CreateRecord
{
    protected static string $resource = CompetitionCategoryResource::class;

    protected Width|string|null $maxContentWidth = Width::Full;
}

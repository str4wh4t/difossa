<?php

namespace App\Filament\Resources\CompetitionStatuses\Pages;

use App\Filament\Resources\CompetitionStatuses\CompetitionStatusResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCompetitionStatuses extends ListRecords
{
    protected static string $resource = CompetitionStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

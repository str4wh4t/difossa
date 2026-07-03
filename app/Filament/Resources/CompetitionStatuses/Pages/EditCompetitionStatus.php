<?php

namespace App\Filament\Resources\CompetitionStatuses\Pages;

use App\Filament\Resources\CompetitionStatuses\CompetitionStatusResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCompetitionStatus extends EditRecord
{
    protected static string $resource = CompetitionStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

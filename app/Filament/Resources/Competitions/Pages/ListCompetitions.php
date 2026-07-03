<?php

namespace App\Filament\Resources\Competitions\Pages;

use App\Filament\Concerns\ConfiguresCompetitionGridList;
use App\Filament\Resources\Competitions\CompetitionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Wezlo\FilamentGridList\Concerns\HasGridList;
use Wezlo\FilamentGridList\GridListConfiguration;

class ListCompetitions extends ListRecords
{
    use ConfiguresCompetitionGridList;
    use HasGridList;

    protected static string $resource = CompetitionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function gridList(GridListConfiguration $config): GridListConfiguration
    {
        return $this->configureCompetitionGridList($config)
            ->recordsPerPage(12)
            ->recordsPerPageOptions([12, 24, 48])
            ->recordUrl(fn ($record) => CompetitionResource::getUrl('view', ['record' => $record]));
    }
}

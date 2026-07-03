<?php

namespace App\Filament\Concerns;

use Wezlo\FilamentGridList\GridListConfiguration;

trait ConfiguresCompetitionGridList
{
    protected function configureCompetitionGridList(GridListConfiguration $config): GridListConfiguration
    {
        return $config
            ->gridColumns(['default' => 1, 'sm' => 2, 'xl' => 3])
            ->gap(5)
            ->selectable(false)
            ->cardView('filament.components.competition-grid-card');
    }
}

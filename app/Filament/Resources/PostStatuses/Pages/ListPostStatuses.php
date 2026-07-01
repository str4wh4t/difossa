<?php

namespace App\Filament\Resources\PostStatuses\Pages;

use App\Filament\Resources\PostStatuses\PostStatusResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPostStatuses extends ListRecords
{
    protected static string $resource = PostStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\PostStatuses\Pages;

use App\Filament\Resources\PostStatuses\PostStatusResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPostStatus extends EditRecord
{
    protected static string $resource = PostStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

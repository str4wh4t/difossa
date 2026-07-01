<?php

namespace App\Filament\Resources\PostStatuses\Pages;

use App\Filament\Resources\PostStatuses\PostStatusResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePostStatus extends CreateRecord
{
    protected static string $resource = PostStatusResource::class;
}

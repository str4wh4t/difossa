<?php

namespace Database\Seeders;

use App\Models\PostStatus;
use Illuminate\Database\Seeder;

class PostStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ['name' => 'Draft', 'slug' => 'draft'],
            ['name' => 'Pending', 'slug' => 'pending'],
            ['name' => 'Published', 'slug' => 'published'],
            ['name' => 'Archived', 'slug' => 'archived'],
        ];

        foreach ($statuses as $status) {
            PostStatus::query()->updateOrCreate(
                ['slug' => $status['slug']],
                $status,
            );
        }
    }
}

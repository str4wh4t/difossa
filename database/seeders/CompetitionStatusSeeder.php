<?php

namespace Database\Seeders;

use App\Models\CompetitionStatus;
use Illuminate\Database\Seeder;

class CompetitionStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ['name' => 'Draft', 'slug' => CompetitionStatus::SLUG_DRAFT],
            ['name' => 'Open', 'slug' => CompetitionStatus::SLUG_OPEN],
            ['name' => 'Closed', 'slug' => CompetitionStatus::SLUG_CLOSED],
            ['name' => 'Finished', 'slug' => CompetitionStatus::SLUG_FINISHED],
        ];

        foreach ($statuses as $status) {
            CompetitionStatus::query()->updateOrCreate(
                ['slug' => $status['slug']],
                $status,
            );
        }
    }
}

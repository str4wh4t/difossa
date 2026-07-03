<?php

namespace Database\Seeders;

use App\Models\Competition;
use App\Models\CompetitionCategory;
use App\Models\CompetitionStatus;
use Illuminate\Database\Seeder;

class CompetitionSeeder extends Seeder
{
    public function run(): void
    {
        $competition = Competition::query()->updateOrCreate(
            ['slug' => 'difossa-research-competition-2026'],
            [
                'title' => 'Difossa Research Competition 2026',
                'description' => 'Submit your research article and compete across multiple categories.',
                'competition_status_id' => CompetitionStatus::idForSlug(CompetitionStatus::SLUG_OPEN),
                'registration_starts_at' => now()->subDay(),
                'registration_ends_at' => now()->addMonths(2),
            ],
        );

        $undergraduate = CompetitionCategory::query()->updateOrCreate(
            ['slug' => 'undergraduate'],
            [
                'name' => 'Undergraduate',
                'description' => 'For undergraduate students.',
            ],
        );

        $graduate = CompetitionCategory::query()->updateOrCreate(
            ['slug' => 'graduate'],
            [
                'name' => 'Graduate',
                'description' => 'For graduate students and researchers.',
            ],
        );
    }
}

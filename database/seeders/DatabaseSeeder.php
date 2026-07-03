<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ShieldRoleSeeder::class,
            UserSeeder::class,
            PostStatusSeeder::class,
            MenuSeeder::class,
            CompetitionStatusSeeder::class,
            // CompetitionSeeder::class,
        ]);
    }
}

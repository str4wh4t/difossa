<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Default password for local development: password
        $superAdmin = User::query()->updateOrCreate(
            ['email' => 'superadmin@local.com'],
            [
                'name' => 'superadmin',
                'full_name' => 'Super Admin',
                'password' => Hash::make('12341234'),
            ],
        );
        $superAdmin->syncRoles([User::ROLE_SUPER_ADMIN]);

        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@difossa.test'],
            [
                'name' => 'admin',
                'full_name' => 'Site Admin',
                'password' => Hash::make('password'),
            ],
        );
        $admin->syncRoles([User::ROLE_ADMIN]);

        $participant = User::query()->updateOrCreate(
            ['email' => 'participant@difossa.test'],
            [
                'name' => 'participant',
                'full_name' => 'Competition Participant',
                'affiliation' => 'Diponegoro University',
                'password' => Hash::make('password'),
            ],
        );
        $participant->syncRoles([User::ROLE_PARTICIPANT]);
    }
}

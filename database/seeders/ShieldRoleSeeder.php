<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class ShieldRoleSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $guard = 'web';

        Role::firstOrCreate(['name' => User::ROLE_SUPER_ADMIN, 'guard_name' => $guard]);
        $admin = Role::firstOrCreate(['name' => User::ROLE_ADMIN, 'guard_name' => $guard]);
        $participant = Role::firstOrCreate(['name' => User::ROLE_PARTICIPANT, 'guard_name' => $guard]);

        $adminPermissions = Permission::query()
            ->where('name', 'not like', '%:Role')
            ->where('name', 'not like', '%:User')
            ->pluck('name');

        $admin->syncPermissions($adminPermissions);

        $participant->syncPermissions([
            'ViewAny:Competition',
            'View:Competition',
            'ViewAny:CompetitionRegistration',
            'Create:CompetitionRegistration',
            'View:CompetitionRegistration',
            'Update:CompetitionRegistration',
            'Delete:CompetitionRegistration',
        ]);
    }
}

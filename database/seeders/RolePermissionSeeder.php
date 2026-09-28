<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User management
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            // Mini app management
            'mini-apps.view',
            'mini-apps.create',
            'mini-apps.edit',
            'mini-apps.delete',
            'mini-apps.manage-tokens',
            // Media management
            'media.upload',
            'media.delete',
            // Activity log
            'activities.view',
            // Settings
            'settings.view',
            'settings.edit',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions(Permission::all());

        $manager = Role::firstOrCreate(['name' => 'manager']);
        $manager->syncPermissions([
            'users.view',
            'users.edit',
            'mini-apps.view',
            'mini-apps.create',
            'mini-apps.edit',
            'mini-apps.manage-tokens',
            'media.upload',
            'media.delete',
            'activities.view',
        ]);

        $user = Role::firstOrCreate(['name' => 'user']);
        $user->syncPermissions([
            'mini-apps.view',
        ]);
    }
}

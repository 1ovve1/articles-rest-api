<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    function run(): void
    {
        /**
         * Flush permission cache like in:
         * https://spatie.be/docs/laravel-permission/v5/advanced-usage/seeding
         */
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'create articles']);
        Permission::create(['name' => 'edit articles']);
        Permission::create(['name' => 'delete articles']);
        Permission::create(['name' => 'publish articles']);
        Permission::create(['name' => 'hide articles']);

        // this can be done as separate statements
        $role = Role::create(['name' => 'writer']);
        $role->givePermissionTo(['create articles', 'edit articles']);

        $role = Role::create(['name' => 'moderator']);
        $role->givePermissionTo(['create articles', 'edit articles', 'hide articles', 'publish articles']);

        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo(Permission::all());

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}

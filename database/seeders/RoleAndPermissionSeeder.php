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
        Permission::create(['name' => 'view all users']);
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'edit users']);
        Permission::create(['name' => 'delete users']);
        Permission::create(['name' => 'disable users']);

        Permission::create(['name' => 'view all articles']);
        Permission::create(['name' => 'view articles']);
        Permission::create(['name' => 'create articles']);
        Permission::create(['name' => 'edit articles']);
        Permission::create(['name' => 'delete articles']);
        Permission::create(['name' => 'manage articles']);

        // this can be done as separate statements
        $role = Role::create(['name' => 'writer']);
        $role->givePermissionTo([
            'view users',
            'view articles',
            'create articles',
            'edit articles']);

        $role = Role::create(['name' => 'moderator']);
        $role->givePermissionTo([
            'view all users',
            'view users',
            'disable users',
            'view all articles',
            'view articles',
            'create articles',
            'edit articles',
            'manage articles']);

        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo(Permission::all());

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}

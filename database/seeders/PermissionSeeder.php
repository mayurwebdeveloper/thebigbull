<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::query()->delete();

        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'add user']);
        Permission::create(['name' => 'edit user']);
        Permission::create(['name' => 'delete user']);
        Permission::create(['name' => 'view permissions']);
        Permission::create(['name' => 'add permission']);
        Permission::create(['name' => 'edit permission']);
        Permission::create(['name' => 'delete permission']);
        Permission::create(['name' => 'view roles']);
        Permission::create(['name' => 'add role']);
        Permission::create(['name' => 'edit role']);
        Permission::create(['name' => 'delete role']);
        Permission::create(['name' => 'assign role-permissions']);
        Permission::create(['name' => 'view staff']);
        Permission::create(['name' => 'add staff']);
        Permission::create(['name' => 'edit staff']);
        Permission::create(['name' => 'delete staff']);
        Permission::create(['name' => 'view designations']);
        Permission::create(['name' => 'add designation']);
        Permission::create(['name' => 'edit designation']);
        Permission::create(['name' => 'delete designation']);

        Permission::create(['name' => 'view quizzes']);
        Permission::create(['name' => 'add quiz']);
        Permission::create(['name' => 'edit quiz']);
        Permission::create(['name' => 'delete quiz']);
        
    }
}

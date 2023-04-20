<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $role1 = Role::create(['name' => 'admin']);
        $role2 = Role::create(['name' => 'organizador']);
        $role3 = Role::create(['name' => 'estudio']);
        $role4 = Role::create(['name' => 'cliente']);

        Permission::create(['name' => 'admin.dashboard'])->syncRoles([$role1, $role2, $role3]);

        Permission::create(['name' => 'admin.events.index'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'admin.events.create'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'admin.events.edit'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'admin.events.destroy'])->syncRoles([$role1, $role2]);

        Permission::create(['name' => 'photographs.index'])->syncRoles([$role1, $role2, $role3]);


    }
}

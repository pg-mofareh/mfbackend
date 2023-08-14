<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class RolesAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $arrayOfPermissions = [
            'users create','users view','users edit','users delete',
            'products create','products view','products edit','products delete'
        ];
        $permissions = collect($arrayOfPermissions)->map(function($permission){
            return ['name'=>$permission,'guard_name'=>'web'];
        });
        Permission::insert($permissions->toArray());
        $role = Role::create(['name'=>'super admin'])->givePermissionTo($arrayOfPermissions);
    }
}

<?php

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use App\Enums\RolesEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $roles = [
            RolesEnum::Admin->value => [
                PermissionEnum::ReadDashboard,
                PermissionEnum::ReadUser,
                PermissionEnum::WriteUser,
                PermissionEnum::DeleteUser,
                PermissionEnum::RestoreUser,
            ],
            RolesEnum::User->value => [
                PermissionEnum::ReadDashboard,
                PermissionEnum::ReadUser,
            ]
        ];

        foreach ($roles as $roleName => $permissions) {
            $role = Role::firstOrCreate(['name' => $roleName], ['name' => $roleName]);

            foreach ($permissions as $permissionName) {
                $permission = Permission::firstOrCreate(['name' => $permissionName], ['name' => $permissionName]);

                $permission->assignRole($role);
            }
        }
    }
}

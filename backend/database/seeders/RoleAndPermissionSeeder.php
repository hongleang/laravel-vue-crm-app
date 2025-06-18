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

        foreach (PermissionEnum::cases() as $permissions) {
            $permission = Permission::firstOrCreate(['name' => $permissions->value], ['name' => $permissions->value]);
        }

        $roles = [
            RolesEnum::Admin->value => [
                PermissionEnum::ReadDashboard,
                PermissionEnum::ReadUser,
                PermissionEnum::WriteUser,
                PermissionEnum::DeleteUser,
                PermissionEnum::RestoreUser,
                PermissionEnum::ReadCompany,
                PermissionEnum::WriteCompany,
                PermissionEnum::DeleteCompany,
                PermissionEnum::RestoreCompany,
            ],
            RolesEnum::Manager->value => [
                PermissionEnum::ReadDashboard,
                PermissionEnum::ReadUser,
                PermissionEnum::WriteUser,
                PermissionEnum::DeleteUser,
                PermissionEnum::RestoreUser,
                PermissionEnum::ReadCompany,
                PermissionEnum::WriteCompany
            ],
            RolesEnum::Sales->value => [
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

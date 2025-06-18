<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Enums\RolesEnum;
use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Spatie\Permission\Contracts\Role;

class CompanyPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function list(User $user): bool
    {
        return $user->hasPermissionTo(PermissionEnum::ReadCompany);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Company $company): bool
    {
        return $user->hasPermissionTo(PermissionEnum::ReadCompany);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(PermissionEnum::WriteCompany);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Company $company): bool
    {
        return $user->hasPermissionTo(PermissionEnum::WriteCompany);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Company $company): bool
    {
        return $user->hasRole(RolesEnum::Admin) && $user->hasPermissionTo(PermissionEnum::WriteCompany);
    }
}

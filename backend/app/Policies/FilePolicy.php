<?php

namespace App\Policies;

use App\Models\File;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FilePolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, File $file): bool
    {
        return $user->can('view', $file->owner);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, File $file): bool
    {
        return $user->can('update', $file->owner);
    }
}

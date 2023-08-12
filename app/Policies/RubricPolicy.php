<?php

namespace App\Policies;

use App\Models\Rubric;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RubricPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Rubric $rubric): bool
    {
        return $rubric->active;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create rubrics');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Rubric $rubric): bool
    {
        return $user->hasPermissionTo('edit rubrics');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Rubric $rubric): bool
    {
        return $user->hasPermissionTo('delete rubrics');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Rubric $rubric): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Rubric $rubric): bool
    {
        return false;
    }
}

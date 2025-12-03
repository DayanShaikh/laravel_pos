<?php

namespace App\Policies;

use App\Models\User;

class ItemPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function viewAny(User $user): bool
    {
        return in_array('view-App\Models\Item', $user->given_permissions) || auth()->user()->hasRole('Admin');
    }

    /**
     * Determine whether the user can create models.
     */

    public function create(User $user): bool
    {
        return in_array('create-App\Models\Item', $user->given_permissions) || auth()->user()->hasRole('Admin');
    }

    /**
     * Determine whether the user can update the model.
     */

    public function update(User $user): bool
    {
        return in_array('update-App\Models\Item', $user->given_permissions) || auth()->user()->hasRole('Admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return in_array('delete-App\Models\Item', $user->given_permissions) || auth()->user()->hasRole('Admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user): bool
    {
        return false;
    }
}

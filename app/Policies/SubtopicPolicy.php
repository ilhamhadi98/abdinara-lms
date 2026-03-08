<?php

namespace App\Policies;

use App\Models\Subtopic;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SubtopicPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_subtopic');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_subtopic');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Subtopic $subtopic): bool
    {
        return $user->hasPermissionTo('update_subtopic');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Subtopic $subtopic): bool
    {
        return $user->hasPermissionTo('delete_subtopic');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->hasPermissionTo('delete_any_subtopic');
    }
}

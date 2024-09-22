<?php

namespace TomatoPHP\FilamentLogger\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use TomatoPHP\FilamentLogger\Models\Activity;

class ActivityPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return class_exists(\BezhanSalleh\FilamentShield\FilamentShield::class) ? $user->can('view_any_activity') : true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Activity $activity): bool
    {
        return class_exists(\BezhanSalleh\FilamentShield\FilamentShield::class) ? $user->can('view_activity') : true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return class_exists(\BezhanSalleh\FilamentShield\FilamentShield::class) ? $user->can('create_activity') : true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Activity $activity): bool
    {
        return class_exists(\BezhanSalleh\FilamentShield\FilamentShield::class) ? $user->can('update_activity') : true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Activity $activity): bool
    {
        return class_exists(\BezhanSalleh\FilamentShield\FilamentShield::class) ? $user->can('delete_activity') : true;
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return class_exists(\BezhanSalleh\FilamentShield\FilamentShield::class) ? $user->can('delete_any_activity') : true;
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Activity $activity): bool
    {
        return class_exists(\BezhanSalleh\FilamentShield\FilamentShield::class) ? $user->can('force_delete_activity') : true;
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return class_exists(\BezhanSalleh\FilamentShield\FilamentShield::class) ? $user->can('force_delete_any_activity') : true;
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Activity $activity): bool
    {
        return class_exists(\BezhanSalleh\FilamentShield\FilamentShield::class) ? $user->can('restore_activity') : true;
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return class_exists(\BezhanSalleh\FilamentShield\FilamentShield::class) ? $user->can('restore_any_activity') : true;
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Activity $activity): bool
    {
        return class_exists(\BezhanSalleh\FilamentShield\FilamentShield::class) ? $user->can('replicate_activity') : true;
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return class_exists(\BezhanSalleh\FilamentShield\FilamentShield::class) ? $user->can('reorder_activity') : true;
    }
}

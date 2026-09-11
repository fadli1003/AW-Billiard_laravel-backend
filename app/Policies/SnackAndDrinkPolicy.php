<?php

namespace App\Policies;

use App\Models\SnackAndDrink;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SnackAndDrinkPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SnackAndDrink $snackAndDrink): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SnackAndDrink $snackAndDrink): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SnackAndDrink $snackAndDrink): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SnackAndDrink $snackAndDrink): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SnackAndDrink $snackAndDrink): bool
    {
        return false;
    }
}

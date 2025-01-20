<?php

namespace App\Policies;

use App\Models\Apartment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ApartmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->type, ['Admin', 'Manager', 'Resident']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Apartment $apartment): bool
    {
        if ($user->type === 'Admin') {
            return true;
        }

        if ($user->type === 'Manager') {
            return $user->residences->flatMap->buildings->pluck('id')->contains($apartment->building_id);
        }

        if ($user->type === 'Resident') {
            return $user->apartments->pluck('id');
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->type, ['Admin', 'Manager', 'Resident']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Apartment $apartment): bool
    {
        if ($user->type === 'Admin') {
            return true;
        }

        if ($user->type === 'Manager') {
            return $user->residences->flatMap->buildings->pluck('id')->contains($apartment->building_id);
        }

        if ($user->type === 'Resident') {
            return $user->id === $apartment->user_id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Apartment $apartment): bool
    {
        if ($user->type === 'Admin') {
            return true; // Admin tiene acceso completo
        }

        if ($user->type === 'Manager') {
            return $user->residences->flatMap->buildings->pluck('id')->contains($apartment->building_id);
        }

        if ($user->type === 'Resident') {
            return $user->id === $apartment->user_id;
        }

        return false;
    }
}

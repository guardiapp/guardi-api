<?php

namespace App\Policies;

use App\Models\Residence;
use App\Models\User;

class ResidencePolicy
{
    public function viewAny(User $user)
    {
        return in_array($user->type, ['Admin', 'Manager']);
    }

    /**
     * Determina si el usuario puede ver apartamentos de una residencia específica.
     */
    public function viewByResidence(User $user, Residence $residence)
    {
        if ($user->type === 'Admin') {
            return true;
        }

        if ($user->type === 'Manager') {
            // Permitir solo si la residencia pertenece al manager
            return $user->residences->pluck('id')->contains($residence->id);
        }

        return false;
    }

    public function view(User $user, Residence $residence)
    {
        return $user->type === 'Admin' || ($user->type === 'Manager' && $residence->user_id === $user->id);
    }

    public function create(User $user)
    {
        //return in_array($user->type, ['Admin', 'Manager']);
        return $user->type === 'Admin';
    }

    public function update(User $user, Residence $residence)
    {
        //return $user->type === 'Admin' || ($user->type === 'Manager' && $residence->user_id === $user->id);
        return $user->type === 'Admin';
    }

    public function delete(User $user, Residence $residence)
    {
        //return in_array($user->type, ['Admin', 'Manager']);
        return $user->type === 'Admin';
    }
}

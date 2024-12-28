<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Guard;
use Illuminate\Auth\Access\HandlesAuthorization;

class GuardPolicy
{
    use HandlesAuthorization;

    /**
     * Determina si un usuario puede ver la lista de Guards.
     */
    public function viewAny(User $user)
    {
        // Admin tiene acceso completo
        if ($user->type === 'Admin') {
            return true;
        }

        // Manager puede acceder a la lista, pero el filtro ocurre en el controlador o repositorio
        if ($user->type === 'Manager') {
            return true;
        }

        return false;
    }

    /**
     * Determina si un usuario puede ver un Guard específico.
     */
    public function view(User $user, Guard $guard)
    {
        if ($user->type === 'Admin') {
            return true;
        }

        if ($user->type === 'Manager') {
            // Manager solo puede ver Guards asociados a sus Residences
            return $user->residences->contains('id', $guard->residence_id);
        }

        return false;
    }

    /**
     * Determina si un usuario puede crear Guards.
     */
    public function create(User $user)
    {
        // Solo Admin y Manager pueden crear Guards
        return in_array($user->type, ['Admin', 'Manager']);
    }

    /**
     * Determina si un usuario puede actualizar un Guard.
     */
    public function update(User $user, Guard $guard)
    {
        if ($user->type === 'Admin') {
            return true; // Admin tiene acceso completo
        }

        if ($user->type === 'Manager') {
            // Manager puede actualizar Guards solo en sus Residences
            return $user->residences->contains('id', $guard->residence_id);
        }

        return false;
    }

    /**
     * Determina si un usuario puede eliminar un Guard.
     */
    public function delete(User $user, Guard $guard)
    {
        if ($user->type === 'Admin') {
            return true; // Admin tiene acceso completo
        }

        if ($user->type === 'Manager') {
            // Manager puede eliminar Guards solo en sus Residences
            return $user->residences->contains('id', $guard->residence_id);
        }

        return false;
    }
}

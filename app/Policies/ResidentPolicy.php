<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Resident;
use Illuminate\Auth\Access\HandlesAuthorization;

class ResidentPolicy
{
    use HandlesAuthorization;

    /**
     * Determina si un usuario puede ver la lista de Residents.
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
     * Determina si un usuario puede ver un Resident específico.
     */
    public function view(User $user, Resident $resident)
    {
        if ($user->type === 'Admin') {
            return true;
        }

        if ($user->type === 'Manager') {
            // Verificar si el Manager puede acceder al Building asociado al Resident
            return $user->residences->pluck('id')
                ->intersect(
                    $resident->building()->pluck('residence_id')
                )->isNotEmpty();
        }

        return false;
    }

    /**
     * Determina si un usuario puede crear Residents.
     */
    public function create(User $user)
    {
        // Solo Admin y Manager pueden crear Residents
        return in_array($user->type, ['Admin', 'Manager']);
    }

    /**
     * Determina si un usuario puede actualizar un Resident.
     */
    public function update(User $user, Resident $resident)
    {
        if ($user->type === 'Admin') {
            return true; // Admin tiene acceso completo
        }

        if ($user->type === 'Manager') {
            // Verificar si el Manager puede acceder al Building asociado al Resident
            return $user->residences->pluck('id')
                ->intersect(
                    $resident->building()->pluck('residence_id')
                )->isNotEmpty();
        }

        return false;
    }

    /**
     * Determina si un usuario puede eliminar un Resident.
     */
    public function delete(User $user, Resident $resident)
    {
        if ($user->type === 'Admin') {
            return true; // Admin tiene acceso completo
        }

        if ($user->type === 'Manager') {
            // Verificar si el Manager puede acceder al Building asociado al Resident
            return $user->residences->pluck('id')
                ->intersect(
                    $resident->building()->pluck('residence_id')
                )->isNotEmpty();
        }

        return false;
    }
}

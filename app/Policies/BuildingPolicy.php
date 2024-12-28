<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Building;
use Illuminate\Auth\Access\HandlesAuthorization;

class BuildingPolicy
{
    use HandlesAuthorization;

    /**
     * Determina si un usuario puede crear Residents.
     */
    public function create(User $user)
    {
        // Solo Admin y Manager pueden crear Residents
        return in_array($user->type, ['Admin', 'Manager']);
    }

    /**
     * Determina si un usuario puede eliminar un Resident.
     */
    public function delete(User $user, Building $building)
    {
        if ($user->type === 'Admin') {
            return true; // Admin tiene acceso completo
        }

        if ($user->type === 'Manager') {
            // Verificar si el Manager puede acceder al Building asociado al Building
            return $user->residences->pluck('id')
                ->intersect(
                    $resident->building()->pluck('residence_id')
                )->isNotEmpty();
        }

        return false;
    }
}

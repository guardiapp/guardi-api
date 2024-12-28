<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Visit;
use Illuminate\Auth\Access\HandlesAuthorization;

class VisitPolicy
{
    use HandlesAuthorization;

    /**
     * Determina si un usuario puede ver la lista completa de visitas.
     */
    public function viewAny(User $user)
    {
        // Admin puede ver todas las visitas
        if ($user->type === 'Admin') {
            return true;
        }

        // Guard puede ver las visitas asociadas a su residencia
        if ($user->type === 'Guard') {
            return true;
        }

        // Manager puede ver visitas bajo su línea (residencias asociadas)
        if ($user->type === 'Manager') {
            return true;
        }

        // Resident puede ver solo sus visitas
        if ($user->type === 'Resident') {
            return true;
        }

        return false;
    }

    /**
     * Determina si un usuario puede ver una visita específica.
     */
    public function view(User $user, Visit $visit)
    {
        if ($user->type === 'Admin') {
            return true;
        }

        if ($user->type === 'Guard') {
            return $user->residences->contains('id', $visit->resident->building->residence->id);
        }

        if ($user->type === 'Manager') {
            return $user->residences->contains('id', $visit->resident->building->residence->id);
        }

        if ($user->type === 'Resident') {
            return $visit->resident->user_id === $user->id;
        }

        return false;
    }

    /**
     * Determina si un usuario puede crear visitas.
     */
    public function create(User $user)
    {
        // Solo Admin, Manager, o Resident pueden crear visitas
        return in_array($user->type, ['Admin', 'Manager', 'Resident']);
    }

    /**
     * Determina si un usuario puede actualizar una visita específica.
     */
    public function update(User $user, Visit $visit)
    {
        if ($user->type === 'Admin') {
            return true; // Admin tiene acceso a todas las visitas
        }

        //verifircar luego estas policies
        if ($user->type === 'Guard') {
            // Verifica si el guard está asociado a la residencia del visitante
            return $user->residences->contains('id', $visit->resident->building->residence->id);
        }

        if ($user->type === 'Manager') {
            // Manager puede actualizar solo visitas asociadas a sus residencias
            return $user->residences->contains('id', $visit->resident->building->residence->id);
        }

        if ($user->type === 'Resident') {
            // Resident puede actualizar solo sus propias visitas
            return $visit->resident->user_id === $user->id;
        }

        return false;
    }


    /**
     * Determina si un usuario puede eliminar una visita.
     */
    public function delete(User $user, Visit $visit)
    {
        if ($user->type === 'Admin') {
            return true;
        }

        if ($user->type === 'Resident') {
            return $visit->resident->user_id === $user->id;
        }

        return false;
    }
}

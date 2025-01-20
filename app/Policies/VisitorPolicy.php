<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Visitor;
use Illuminate\Auth\Access\HandlesAuthorization;

class VisitorPolicy
{
    use HandlesAuthorization;

    /**
     * Determina si un usuario puede ver la lista de Visitors.
     */
    public function viewAny(User $user)
    {
        if ($user->type === 'Admin') {
            return true;
        }

        if ($user->type === 'Manager') {
            // Manager tiene acceso a visitors de sus apartments
            return $user->residences()->with('buildings.apartments.visitors')->get()
                ->flatMap->buildings->flatMap->apartments->isNotEmpty();
        }

        if ($user->type === 'Resident') {
            return true;
        }

        return false;
    }

    /**
     * Determina si un usuario puede crear Visitors.
     */
    public function create(User $user)
    {
        // Admin, Manager y Resident pueden crear Visitors
        return in_array($user->type, ['Admin', 'Manager', 'Resident']);
    }

    /**
     * Determina si un usuario puede actualizar un Visitor específico.
     */
    public function update(User $user, Visitor $visitor)
    {
        if ($user->type === 'Admin') {
            return true; // Admin tiene acceso completo
        }

        if ($user->type === 'Manager') {
            // Manager puede actualizar Visitors relacionados con sus Residents
            return $user->residences->flatMap->residents->contains('id', $visitor->resident_id);
        }

        if ($user->type === 'Resident') {
            // Resident puede actualizar Visitors que le pertenezcan
            return $user->resident?->id === $visitor->resident_id;
        }

        return false;
    }

    /**
     * Determina si un usuario puede eliminar un Visitor específico.
     */
    public function delete(User $user, Visitor $visitor)
    {
        if ($user->type === 'Admin') {
            return true; // Admin tiene acceso completo
        }

        if ($user->type === 'Manager') {
            // Manager puede eliminar Visitors relacionados con sus Residents
            return $user->residences->flatMap->residents->contains('id', $visitor->resident_id);
        }

        if ($user->type === 'Resident') {
            // Resident puede eliminar Visitors que le pertenezcan
            return $user->resident?->id === $visitor->resident_id;
        }

        return false;
    }

    /**
     * Determina si un usuario puede ver un Visitor específico.
     */
    public function view(User $user, Visitor $visitor)
    {
        if ($user->type === 'Admin') {
            return true; // Admin tiene acceso completo
        }

        if ($user->type === 'Manager') {
            // Manager puede ver Visitors relacionados con sus Residents
            return $user->residences->flatMap->residents->contains('id', $visitor->resident_id);
        }

        if ($user->type === 'Resident') {
            // Resident puede ver Visitors que le pertenezcan
            return $user->resident?->id === $visitor->resident_id;
        }

        return false;
    }
}

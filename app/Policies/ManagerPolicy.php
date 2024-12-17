<?php

namespace App\Policies;

use App\Models\User;

class ManagerPolicy
{
    /**
     * Determinar si el usuario puede ver la lista de vigilantes.
     */
    public function viewAny(User $user)
    {
        return $user->type === 'Admin' || $user->type === 'Manager';
    }

















    /**
     * Determinar si el usuario puede ver un manager específico.
     */
    public function view(User $user, User $manager)
    {
        return $user->type === 'Admin' && $manager->type === 'Manager';
    }

    /**
     * Determinar si el usuario puede crear managers.
     */
    public function create(User $user)
    {
        return $user->type === 'Admin';
    }

    /**
     * Determinar si el usuario puede actualizar un manager.
     */
    public function update(User $user, User $manager)
    {
        return $user->type === 'Admin' && $manager->type === 'Manager';
    }

    /**
     * Determinar si el usuario puede eliminar un manager.
     */
    public function delete(User $user, User $manager)
    {
        return $user->type === 'Admin' && $manager->type === 'Manager';
    }
}


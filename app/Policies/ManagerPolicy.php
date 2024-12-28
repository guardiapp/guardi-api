<?php

namespace App\Policies;

use App\Models\User;

class ManagerPolicy
{
    /**
     * Determina si el usuario puede ver la lista de managers.
     * Solo permitido para administradores.
     */
    public function viewAny(User $user)
    {
        return $user->type === 'Admin';
    }

    /**
     * Determina si el usuario puede ver a un manager específico.
     * Los administradores pueden ver cualquier usuario.
     * Los managers solo pueden ver su propia información.
     */
    public function view(User $user, User $manager)
    {
        if ($user->type === 'Admin') {
            return true;
        }

        return $user->type === 'Manager' && $user->id === $manager->id;
    }

    /**
     * Determina si el usuario puede crear nuevos managers.
     * Solo permitido para administradores.
     */
    public function create(User $user)
    {
        return $user->type === 'Admin';
    }

    /**
     * Determina si el usuario puede actualizar la información de un manager.
     * Los administradores pueden editar cualquier usuario.
     * Los managers solo pueden editar su propia información.
     */
    public function update(User $user, User $manager)
    {
        if ($user->type === 'Admin') {
            return true;
        }

        return $user->type === 'Manager' && $user->id === $manager->id;
    }

    /**
     * Determina si el usuario puede eliminar un manager.
     * Los administradores pueden eliminar cualquier usuario.
     * Los managers no pueden eliminar otros usuarios, pero pueden eliminarse a sí mismos si eso se permite.
     */
    public function delete(User $user, User $manager)
    {
        if ($user->type === 'Admin') {
            return true;
        }

        return $user->type === 'Manager' && $user->id === $manager->id;
    }
}

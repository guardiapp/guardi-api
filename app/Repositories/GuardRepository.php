<?php

namespace App\Repositories;

use App\Models\Guard;

class GuardRepository
{
    /**
     * Obtener todos los managers con residencias.
     */
    public function getAll()
    {
        return Guard::with(['user', 'residence'])->get();
    }






















    /**
     * Obtener un manager específico con residencias.
     */
    public function findWithResidences($id)
    {
        return User::byType('Manager')
            ->with('residences')
            ->findOrFail($id);
    }

    /**
     * Crear un manager.
     */
    public function create(array $data)
    {
        $data['type'] = 'Manager';
        return User::create($data);
    }

    /**
     * Actualizar un manager.
     */
    public function update($id, array $data)
    {
        $manager = $this->findWithResidences($id);
        $manager->update($data);
        return $manager;
    }

    /**
     * Eliminar un manager.
     */
    public function delete($id)
    {
        $manager = $this->findWithResidences($id);
        return $manager->delete();
    }
}

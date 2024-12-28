<?php

namespace App\Repositories;

use App\Models\User;



use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Mail\ManagerCreated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;




class ManagerRepository
{
    /**
     * Obtener todos los managers con residencias.
     */
    public function getAllWithResidences($perPage = 10)
    {
        return User::byType('Manager')->with('residences')->paginate($perPage);

        abort(403, 'Unauthorized action.');
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
        $password = Str::random(8);
        $data['password'] = Hash::make($password);
        $data['type'] = 'Manager';

        try {

            // Manejo del avatar
            if (isset($data['avatar']) && $data['avatar']->isValid()) {
                $avatarPath = $data['avatar']->store('avatars', 'public');
                $data['avatar'] = $avatarPath;
            }

            // Crear usuario
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'type' => $data['type'],
                'avatar' => $data['avatar'] ?? null,
            ]);

            // Simular envío de correo en desarrollo
            Mail::to($user->email)->send(new \App\Mail\ManagerCreated($user, $password));

            return $guard;
        } catch (\Exception $e) {
            // Registrar errores en el log
            Log::error('Error al crear el administrador: ' . $e->getMessage());
            throw new \Exception('No se pudo crear el administrador');
        }
    }

    /**
     * Actualizar un manager.
     */
    public function update(User $manager, array $data, $avatarFile = null, $avatarDeleted = false)
    {
        try {
            // Manejo del avatar
            if ($avatarFile) {
                // Eliminar avatar anterior si existe
                if ($manager->avatar) {
                    Storage::disk('public')->delete($manager->avatar);
                }

                // Subir el nuevo avatar
                $avatarPath = $avatarFile->store('avatars', 'public');
                $manager->update(['avatar' => $avatarPath]);
            } elseif ($avatarDeleted) {
                // Eliminar avatar si fue marcado para eliminación
                if ($manager->avatar) {
                    Storage::disk('public')->delete($manager->avatar);
                }
                $manager->update(['avatar' => null]);
            }

            // Actualizar otros campos
            $manager->update([
                'email' => $data['email'],
                'name' => $data['name'],
            ]);

            return $guard;
        } catch (\Exception $e) {
            Log::error('Error al actualizar el administrador: ' . $e->getMessage());
            throw new \Exception('No se pudo actualizar el administrador');
        }
    }

    /**
     * Eliminar un manager.
     */
    public function delete($id)
    {
        try {
            $manager = $this->findWithResidences($id);

            // Manejo del avatar: eliminar si existe
            if ($manager->avatar) {
                Storage::disk('public')->delete($manager->avatar);
            }

            // Eliminar el manager
            return $manager->delete();
        } catch (\Exception $e) {
            Log::error('Error al eliminar el administrador: ' . $e->getMessage());
            throw new \Exception('No se pudo eliminar el administrador');
        }
    }
}

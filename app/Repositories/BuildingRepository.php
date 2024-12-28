<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Storage;
use App\Models\Building;
use App\Models\Residence;
use App\Models\User;
use Illuminate\Support\Str;

class BuildingRepository
{
    public function create(array $data)
    {
        try {
            $building = Building::create([
                'residence_id' => $data['residence_id'],
                'name' => $data['name'],
                'floors_number' => $data['floors_number'],
                'apartments_per_floor' => $data['apartments_per_floor'],
                'information' => $data['information'],
                'active' => true,
            ]);

            return $building;
        } catch (\Exception $e) {
            throw new \Exception('No se pudo crear el Edificio');
        }
    }

    public function findBuilding($id)
    {
        return Building::findOrFail($id);
    }

    public function delete($id)
    {
        try {
            $building = $this->findBuilding($id);
            return $building->delete();
        } catch (\Exception $e) {
            throw new \Exception('No se pudo eliminar el edificio');
        }
    }

    // public function update(Guard $guard, array $data, $avatarFile = null, $avatarDeleted = false)
    // {
    //     try {
    //         // Manejo del avatar
    //         if ($avatarFile) {
    //             // Eliminar avatar anterior si existe
    //             if ($guard->user->avatar) {
    //                 Storage::disk('public')->delete($guard->user->avatar);
    //             }

    //             // Subir el nuevo avatar
    //             $avatarPath = $avatarFile->store('avatars', 'public');
    //             $guard->user->update(['avatar' => $avatarPath]);
    //         } elseif ($avatarDeleted) {
    //             // Eliminar avatar si fue marcado para eliminación
    //             if ($guard->user->avatar) {
    //                 Storage::disk('public')->delete($guard->user->avatar);
    //             }
    //             $guard->user->update(['avatar' => null]);
    //         }

    //         // Actualizar otros campos
    //         $guard->update([
    //             'residence_id' => $data['residence_id'],
    //             'document' => $data['document'],
    //             'first_name' => $data['first_name'],
    //             'last_name' => $data['last_name'],
    //             'phone' => $data['phone'],
    //         ]);

    //         return $guard;
    //     } catch (\Exception $e) {
    //         Log::error('Error al actualizar el vigilante: ' . $e->getMessage());
    //         throw new \Exception('No se pudo actualizar el vigilante');
    //     }
    // }

    // public function delete($id)
    // {
    //     try {
    //         $guard = $this->findGuard($id);

    //         // Manejo del avatar: eliminar si existe
    //         if ($guard->user->avatar) {
    //             Storage::disk('public')->delete($guard->user->avatar);
    //         }

    //         // Eliminar al usuario asociado
    //         $guard->user->delete();

    //         // Eliminar el guard
    //         return $guard->delete();
    //     } catch (\Exception $e) {
    //         Log::error('Error al eliminar el vigilante: ' . $e->getMessage());
    //         throw new \Exception('No se pudo eliminar el vigilante');
    //     }
    // }
}

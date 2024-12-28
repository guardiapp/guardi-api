<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Storage;
use App\Models\Resident;
use App\Models\Residence;
use App\Models\Building;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Mail\ResidentCreated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ResidentRepository
{
    public function getAll($perPage, $page)
    {
        $user = Auth::user();

        if ($user->type === 'Admin') {
            // Admin obtiene todos los Residents
            return Resident::with(['building.residence', 'user'])
                ->paginate($perPage, ['*'], 'page', $page);
        }

        if ($user->type === 'Manager') {
            // Obtener las residencias asociadas al Manager
            $residenceIds = $user->residences()->pluck('id');

            // Obtener los buildings asociados a las residencias
            $buildingIds = Building::whereIn('residence_id', $residenceIds)->pluck('id');

            // Filtrar residents por los buildings asociados
            return Resident::with(['building.residence', 'user'])
                ->whereIn('building_id', $buildingIds)
                ->paginate($perPage, ['*'], 'page', $page);
        }

        abort(403, 'Unauthorized action.');
    }

    public function create(array $data)
    {
        $password = Str::random(8);
        $data['password'] = Hash::make($password);
        $data['type'] = 'Resident';

        try {

            // Manejo del avatar
            if (isset($data['avatar']) && $data['avatar']->isValid()) {
                $avatarPath = $data['avatar']->store('avatars', 'public');
                $data['avatar'] = $avatarPath;
            }

            // Crear usuario
            $user = User::create([
                'name' => $data['first_name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'type' => $data['type'],
                'avatar' => $data['avatar'] ?? null,
            ]);

            // Crear resident asociado al usuario
            $data['user_id'] = $user->id;
            $resident = Resident::create([
                'user_id' => $data['user_id'],
                'building_id' => $data['building_id'],
                'apartment' => $data['apartment'],
                'document' => $data['document'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'phone' => $data['phone'],
                'active' => true
            ]);

            // Simular envío de correo en desarrollo
            Mail::to($user->email)->send(new \App\Mail\ResidentCreated($user, $password));

            return $resident;
        } catch (\Exception $e) {
            // Registrar errores en el log
            Log::error('Error al crear el residente: ' . $e->getMessage());
            throw new \Exception('No se pudo crear el residente');
        }
    }

    public function findResident($id)
    {
        return Resident::with(['user', 'building', 'building.residence', 'building.residence.manager'])
            ->findOrFail($id);
    }

    public function update(Resident $resident, array $data, $avatarFile = null, $avatarDeleted = false)
    {
        try {
            // Manejo del avatar
            if ($avatarFile) {
                // Eliminar avatar anterior si existe
                if ($resident->user->avatar) {
                    Storage::disk('public')->delete($resident->user->avatar);
                }

                // Subir el nuevo avatar
                $avatarPath = $avatarFile->store('avatars', 'public');
                $resident->user->update(['avatar' => $avatarPath]);
            } elseif ($avatarDeleted) {
                // Eliminar avatar si fue marcado para eliminación
                if ($resident->user->avatar) {
                    Storage::disk('public')->delete($resident->user->avatar);
                }
                $resident->user->update(['avatar' => null]);
            }

            // Actualizar otros campos
            $resident->update([
                'building_id' => $data['building_id'],
                'apartment' => $data['apartment'],
                'document' => $data['document'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'phone' => $data['phone'],
                'active' => $data['active'],
            ]);

            return $resident;
        } catch (\Exception $e) {
            Log::error('Error al actualizar el vigilante: ' . $e->getMessage());
            throw new \Exception('No se pudo actualizar el vigilante');
        }
    }

    public function delete($id)
    {
        try {
            $resident = $this->findResident($id);

            // Manejo del avatar: eliminar si existe
            if ($resident->user->avatar) {
                Storage::disk('public')->delete($resident->user->avatar);
            }

            // Eliminar al usuario asociado
            $resident->user->delete();

            // Eliminar el resident
            return $resident->delete();
        } catch (\Exception $e) {
            Log::error('Error al eliminar el residente: ' . $e->getMessage());
            throw new \Exception('No se pudo eliminar el residente');
        }
    }

    public function getManagers()
    {
        return User::byType('Manager')->get();
    }

    public function getResidences()
    {
        $user = Auth::user();

        if ($user->type === 'Admin') {
            return Residence::get(); // Todas las residencias
        }

        if ($user->type === 'Manager') {
            return Residence::where('user_id', $user->id)->get();
        }

        abort(403, 'Unauthorized action.');
    }

    public function getBuildings()
    {
        $user = Auth::user();

        if ($user->type === 'Admin') {
            return Building::with('residence')->get();
        }

        if ($user->type === 'Manager') {
            return Building::whereHas('residence', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->get();
        }

        abort(403, 'Unauthorized action.');
    }
}

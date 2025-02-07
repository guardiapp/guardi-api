<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Storage;
use App\Models\Apartment;
use App\Models\Profile;
use App\Models\Residence;
use App\Models\Building;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Mail\ResidentCreated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ApartmentRepository
{
    public function getAll($perPage, $page, array $filters)
    {
        $user = Auth::user();

        if ($user->type === 'Admin') {
            $query = Apartment::with(['building.residence', 'resident', 'resident.profile']);
        } elseif ($user->type === 'Manager') {
            $residenceIds = $user->residences()->pluck('id');
            $buildingIds = Building::whereIn('residence_id', $residenceIds)->pluck('id');
            $query = Apartment::with(['building.residence', 'resident', 'resident.profile'])
                ->whereIn('building_id', $buildingIds);
        } else {
            abort(403, 'Unauthorized action.');
        }

        $this->applyFilters($query, $filters);

        return $query->paginate($perPage, ['*'], 'page', $page)->appends($filters);
    }

    /**
     * Obtener los apartamentos asociados a una residencia.
     */
    public function getByResidence(Residence $residence, $perPage, $page, $filters)
    {
        $query = $residence->apartments()->with(['resident.profile', 'building.residence']);

        $this->applyFilters($query, $filters);

        return $query->paginate($perPage, ['*'], 'page', $page)->appends($filters);
    }

    /**
     * Aplicar filtros dinámicos a la consulta de apartamentos.
     */
    protected function applyFilters($query, $filters)
    {
        if (!empty($filters['identifier'])) {
            $query->where('identifier', 'like', '%' . $filters['identifier'] . '%');
        }

        if (!empty($filters['resident_name'])) {
            $query->whereHas('resident.profile', function ($q) use ($filters) {
                $q->where('first_name', 'like', '%' . $filters['resident_name'] . '%')
                ->orWhere('last_name', 'like', '%' . $filters['resident_name'] . '%');
            });
        }

        if (!empty($filters['building_name'])) {
            $query->whereHas('building', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['building_name'] . '%');
            });
        }
    }

    public function create(array $data)
    {
        $password = Str::random(8);
        $data['password'] = Hash::make($password);
        $data['type'] = 'Resident';

        try {

            if (isset($data['avatar']) && $data['avatar']->isValid()) {
                $avatarPath = $data['avatar']->store('avatars', 'public');
                $data['avatar'] = $avatarPath;
            }

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'type' => $data['type'],
                'avatar' => $data['avatar'] ?? null,
            ]);
            $data['user_id'] = $user->id;

            $profile = Profile::create([
                'user_id' => $data['user_id'],
                'document' => $data['document'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'phone' => $data['phone'],
                'birthday' => $data['birthday'] ?? null,
            ]);

            $apartment = Apartment::create([
                'user_id' => $data['user_id'],
                'building_id' => $data['building_id'],
                'identifier' => $data['identifier'],
                'active' => true
            ]);

            // Simular envío de correo en desarrollo
            Mail::to($user->email)->send(new \App\Mail\ResidentCreated($user, $password));

            return $apartment;
        } catch (\Exception $e) {
            // Registrar errores en el log
            Log::error('Error al crear el apartamento: ' . $e->getMessage());
            throw new \Exception('No se pudo crear el apartamento');
        }
    }

    public function findApartment($id)
    {
        return Apartment::with([ 'resident.profile', 'building.residence.manager' ])
            ->findOrFail($id);
    }

    public function update(Apartment $apartment, array $data, $avatarFile = null, $avatarDeleted = false)
    {
        try {
            // Manejo del avatar
            if ($avatarFile) {
                // Eliminar avatar anterior si existe
                if ($apartment->user->avatar) {
                    Storage::disk('public')->delete($apartment->user->avatar);
                }

                // Subir el nuevo avatar
                $avatarPath = $avatarFile->store('avatars', 'public');
                $apartment->user->update(['avatar' => $avatarPath]);
            } elseif ($avatarDeleted) {
                // Eliminar avatar si fue marcado para eliminación
                if ($apartment->user->avatar) {
                    Storage::disk('public')->delete($apartment->user->avatar);
                }
                $apartment->user->update(['avatar' => null]);
            }

            $apartment->user->update([
                'name' => $data['first_name'],
                'email' => $data['email'],
            ]);

            $data['user_id'] = $user->id;

            $apartment->user->profile->update([
                'document' => $data['document'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'phone' => $data['phone'],
                'birthday' => $data['birthday'] ?? null,
            ]);

            // Actualizar otros campos
            $apartment->update([
                'identifier' => $data['identifier'],
                'active' => $data['active']
            ]);

            return $apartment;
        } catch (\Exception $e) {
            Log::error('Error al actualizar el vigilante: ' . $e->getMessage());
            throw new \Exception('No se pudo actualizar el vigilante');
        }
    }

    public function delete($id)
    {
        try {
            $apartment =  Apartment::findOrFail($id);

            // Manejo del avatar: eliminar si existe
            if ($apartment->resident->avatar) {
                Storage::disk('public')->delete($apartment->resident->avatar);
            }

            // Eliminar al usuario asociado
            $apartment->resident->delete();

            // Eliminar el resident
            return $apartment->delete();
        } catch (\Exception $e) {
            Log::error('Error al eliminar el apartamento: ' . $e->getMessage());
            throw new \Exception('No se pudo eliminar el apartamento');
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

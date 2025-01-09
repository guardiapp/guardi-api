<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Storage;
use App\Models\Apartment;
use App\Models\Residence;
use App\Models\Building;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
//use App\Mail\ResidentCreated;
// use Illuminate\Support\Facades\Mail;
// use Illuminate\Support\Facades\Log;

class ApartmentRepository
{
    public function getAll($perPage, $page)
    {
        $user = Auth::user();

        if ($user->type === 'Admin') {
            // Admin obtiene todos los apartamentos con relaciones necesarias
            return Apartment::with(['building.residence', 'resident', 'resident.profile'])
                ->paginate($perPage, ['*'], 'page', $page);
        }

        if ($user->type === 'Manager') {
            $residenceIds = $user->residences()->pluck('id');

            $buildingIds = Building::whereIn('residence_id', $residenceIds)->pluck('id');

            return Apartment::with(['building.residence', 'resident', 'resident.profile'])
                ->whereIn('building_id', $buildingIds)
                ->paginate($perPage, ['*'], 'page', $page);
        }

        if ($user->type === 'Resident') {
            return Apartment::with(['building.residence', 'resident', 'resident.profile'])
                ->where('user_id', $user->id)
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

            if (isset($data['avatar']) && $data['avatar']->isValid()) {
                $avatarPath = $data['avatar']->store('avatars', 'public');
                $data['avatar'] = $avatarPath;
            }

            $user = User::create([
                'name' => $data['first_name'],
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
                'birthday' => $data['birthday'],
            ]);

            $apartment = Apartment::create([
                'user_id' => $data['user_id'],
                'building_id' => $data['building_id'],
                'indentifier' => $data['identifier'],
                'active' => true
            ]);

            // Simular envío de correo en desarrollo
            Mail::to($user->email)->send(new \App\Mail\ResidentCreated($user, $password));

            return $resident;
        } catch (\Exception $e) {
            // Registrar errores en el log
            Log::error('Error al crear el apartamento: ' . $e->getMessage());
            throw new \Exception('No se pudo crear el apartamento');
        }
    }

    public function findApartment($id)
    {
        return Aparment::with(['user', 'user.profile', 'building', 'building.residence', 'building.residence.manager'])
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

            $avatar->user->profile->update([
                'document' => $data['document'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'phone' => $data['phone'],
                'birthday' => $data['birthday'],
            ]);

            // Actualizar otros campos
            $apartment->update([
                'building_id' => $data['building_id'],
                'active' => $data['active'],
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
            $apartment = $this->findResident($id);

            // Manejo del avatar: eliminar si existe
            if ($apartment->user->avatar) {
                Storage::disk('public')->delete($apartment->user->avatar);
            }

            // Eliminar al usuario asociado
            $apartment->user->delete();

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

<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Storage;
use App\Models\Visitor;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class VisitorRepository
{

    public function getAll($perPage, $page)
    {
        $user = auth()->user();

        if ($user->type === 'Admin') {
            $query = Visitor::with(['resident', 'resident.building.residence', 'resident.building.residence.manager']);
        } elseif ($user->type === 'Manager') {
            $residenceIds = $user->residences->pluck('id');
            $query = Visitor::whereHas('resident.building.residence', function ($q) use ($residenceIds) {
                $q->whereIn('id', $residenceIds);
            })->with(['resident', 'resident.building.residence', 'resident.building.residence.manager']);
        } elseif ($user->type === 'Resident') {
            $query = Visitor::where('resident_id', $user->resident->id)->with(['resident', 'resident.building.residence', 'resident.building.residence.manager']);
        } else {
            abort(403, 'Unauthorized action.');
        }

        return $query->paginate($perPage, ['*'], 'page', $page);
    }




















    // public function getResidences()
    // {
    //     $user = Auth::user();

    //     if ($user->type === 'Admin') {
    //         return Residence::get();
    //     }

    //     if ($user->type === 'Manager') {
    //         return Residence::where('user_id', $user->id)->get();
    //     }

    //     abort(403, 'Unauthorized action.');
    // }

    // public function getManagers()
    // {

    //     return User::byType('Manager')->get();

    //     abort(403, 'Unauthorized action.');
    // }

    // public function findGuard($id)
    // {
    //     return Guard::with(['user', 'residence'])
    //         ->findOrFail($id);
    // }

    // public function create(array $data)
    // {
    //     $password = Str::random(8);
    //     $data['password'] = Hash::make($password);
    //     $data['type'] = 'Guard';

    //     try {

    //         // Manejo del avatar
    //         if (isset($data['avatar']) && $data['avatar']->isValid()) {
    //             $avatarPath = $data['avatar']->store('avatars', 'public');
    //             $data['avatar'] = $avatarPath;
    //         }

    //         // Crear usuario
    //         $user = User::create([
    //             'name' => $data['first_name'],
    //             'email' => $data['email'],
    //             'password' => $data['password'],
    //             'type' => $data['type'],
    //             'avatar' => $data['avatar'] ?? null,
    //         ]);

    //         // Crear guard asociado al usuario
    //         $data['user_id'] = $user->id;
    //         $guard = Guard::create([
    //             'user_id' => $data['user_id'],
    //             'residence_id' => $data['residence_id'],
    //             'document' => $data['document'],
    //             'first_name' => $data['first_name'],
    //             'last_name' => $data['last_name'],
    //             'phone' => $data['phone'],
    //         ]);

    //         // Simular envío de correo en desarrollo
    //         Mail::to($user->email)->send(new \App\Mail\GuardCreated($user, $password));

    //         return $guard;
    //     } catch (\Exception $e) {
    //         // Registrar errores en el log
    //         Log::error('Error al crear el vigilante: ' . $e->getMessage());
    //         throw new \Exception('No se pudo crear el vigilante');
    //     }
    // }

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

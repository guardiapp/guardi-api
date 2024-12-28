<?php

namespace App\Repositories;

use App\Models\Residence;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ResidenceRepository
{
    public function getAll($perPage, $page)
    {
        $user = Auth::user();

        if ($user->type === 'Admin') {
            return Residence::with(['manager', 'buildings', 'buildings.residents'])->paginate($perPage, ['*'], 'page', $page);
        }

        if ($user->type === 'Manager') {
            return Residence::with(['manager', 'buildings', 'buildings.residents'])->where('user_id', $user->id)->paginate($perPage, ['*'], 'page', $page);
        }

        abort(403, 'Unauthorized action.');
    }

    public function create(array $data)
    {
        $user = User::findOrFail($data['user_id']);
        return Residence::create([
            'name' => $data['name'],
            'address' => $data['address'],
            'user_id' => $user->id,
        ]);
    }

    public function findByUser($id)
    {
        $user = Auth::user();

        if ($user->type === 'Admin') {
            return Residence::with(['manager', 'buildings', 'buildings.residents'])->findOrFail($id);
        }

        if ($user->type === 'Manager') {
            return Residence::with(['manager', 'buildings', 'buildings.residents'])->where('id', $id)->where('user_id', $user->id)->firstOrFail();
        }

        abort(403, 'Unauthorized action.');
    }

    public function update(Residence $residence, array $data)
    {
        $residence->update($data);
        return $residence;
    }

    public function delete(Residence $residence)
    {
        $residence->delete();
        return true;
    }

    public function getManagers()
    {
        return User::where('type', 'Manager')->get();
    }
}

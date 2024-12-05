<?php

namespace App\Repositories;

use App\Models\Residence;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ResidenceRepository
{
    public function getAll($perPage = 10)
    {
        $user = Auth::user();

        if ($user->type === 'Admin') {
            return Residence::with('user')->paginate($perPage);
        }

        if ($user->type === 'Manager') {
            return Residence::with('user')->where('user_id', $user->id)->paginate($perPage);
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
            return Residence::with('user')->findOrFail($id);
        }

        if ($user->type === 'Manager') {
            return Residence::with('user')->where('id', $id)->where('user_id', $user->id)->firstOrFail();
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

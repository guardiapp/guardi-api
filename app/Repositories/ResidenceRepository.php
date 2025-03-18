<?php

namespace App\Repositories;

use App\Models\Residence;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ResidenceRepository
{
    public function getAll($perPage, $page, $filters =[])
    {
        $user = Auth::user();

        if ($user->type === 'Admin') {
            return Residence::with(['manager', 'manager.profile', 'manager', 'buildings', 'apartments'])->paginate($perPage, ['*'], 'page', $page);
        }

        if ($user->type === 'Manager') {
            return Residence::with(['manager', 'manager.profile', 'manager', 'buildings', 'apartments'])->where('user_id', $user->id)->paginate($perPage, ['*'], 'page', $page);
        }

        abort(403, 'Unauthorized action.');
    }

    public function getFiltered($perPage, $page, $filters = [])
    {
        $user = Auth::user();

        $query = Residence::with(['manager', 'buildings', 'apartments']);

        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }
        if (!empty($filters['address'])) {
            $query->where('address', 'like', '%' . $filters['address'] . '%');
        }
        if (!empty($filters['manager'])) {
            $query->whereHas('manager', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['manager'] . '%');
            });
        }

        // Restricciones de tipo de usuario
        if ($user->type === 'Admin') {
            $paginator = $query->paginate($perPage, ['*'], 'page', $page);
        } elseif ($user->type === 'Manager') {
            $query->where('user_id', $user->id);
            $paginator = $query->paginate($perPage, ['*'], 'page', $page);
        } else {
            abort(403, 'Unauthorized action.');
        }

        // Agregar filtros a los links de la paginación
        return $paginator->appends($filters);
    }


    public function create(array $data)
    {
        //$user = User::findOrFail($data['user_id']);
        return Residence::create([
            'name' => $data['name'],
            'address' => $data['address'],
            //'user_id' => $user->id,
        ]);
    }

    public function find($id)
    {
        $user = Auth::user();

        if ($user->type === 'Admin') {
            return Residence::with(['manager', 'buildings', 'apartments.visits', 'apartments.visitors', 'guards'])->findOrFail($id);
        }

        if ($user->type === 'Manager') {
            return Residence::with(['manager', 'buildings', 'apartments.visits', 'apartments.visitors', 'guards'])->where('id', $id)->where('user_id', $user->id)->firstOrFail();
        }

        abort(403, 'Unauthorized action.');
    }

    public function findByUser($id)
    {
        $user = Auth::user();

        if ($user->type === 'Admin') {
            return Residence::with(['manager', 'buildings', 'apartments'])->findOrFail($id);
        }

        if ($user->type === 'Manager') {
            return Residence::with(['manager', 'buildings', 'apartments'])->where('id', $id)->where('user_id', $user->id)->firstOrFail();
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

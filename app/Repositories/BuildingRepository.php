<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Storage;
use App\Models\Building;
use App\Models\Residence;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;


class BuildingRepository
{
    public function getAll($perPage, $page, $filters)
    {
        $user = Auth::user();

        if ($user->type === 'Admin') {
            $query = Building::with(['residence.manager']);
        }  elseif ($user->type === 'Manager') {
            $residenceIds = $user->residences()->pluck('id');
            $query = Building::with(['residence.manager'])
                ->whereIn('residence_id', $residenceIds);
        } else {
            abort(403, 'Unauthorized action.');
        }

        $this->applyFilters($query, $filters);

        return $query->paginate($perPage, ['*'], 'page', $page)->appends($filters);
    }

    public function getByResidence(Residence $residence, $perPage, $page, array $filters)
    {
        $query = $residence->buildings()->with(['residence.manager']);

        $this->applyFilters($query, $filters);

        return $query->paginate($perPage, ['*'], 'page', $page)->appends($filters);
    }

    /**
     * Aplicar filtros dinámicos a la consulta de vigilantes.
     */
    protected function applyFilters($query, $filters)
    {
        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (isset($filters['active']) && $filters['active'] !== null) {
            $query->where('active', $filters['active']);
        }
    }

    public function getResidences()
    {
        $user = Auth::user();

        if ($user->type === 'Admin') {
            return Residence::get();
        }

        if ($user->type === 'Manager') {
            return Residence::where('user_id', $user->id)->get();
        }

        abort(403, 'Unauthorized action.');
    }

    public function getManagers()
    {

        return User::byType('Manager')->get();

        abort(403, 'Unauthorized action.');
    }

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

    public function update(Building $building, array $data)
    {
        try {

            $building->update([
                'name' => $data['name'],
                'floors_number' => $data['floors_number'],
                'apartments_per_floor' => $data['apartments_per_floor'],
                'information' => $data['information'],
                'active' => true,
            ]);

            return $building;

        } catch (\Exception $e) {
            Log::error('Error al actualizar el edificio: ' . $e->getMessage());
            throw new \Exception('No se pudo actualizar el edificio');
        }
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
}

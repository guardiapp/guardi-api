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

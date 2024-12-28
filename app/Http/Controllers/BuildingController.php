<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBuildingRequest;
use App\Http\Requests\UpdateBuildingRequest;
use Illuminate\Http\Request;
use App\Models\Residence;
use App\Models\Building;
use App\Repositories\BuildingRepository;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BuildingController extends Controller
{
    use AuthorizesRequests;

    protected $buildingRepository;

    public function __construct(BuildingRepository $buildingRepository)
    {
        $this->buildingRepository = $buildingRepository;
    }

    /**
     * Guardar un nuevo edificio en la base de datos.
     */
    public function store(StoreBuildingRequest $request)
    {
        $this->authorize('create', Building::class);

        $data = $request->validated();

        try {
            $building = $this->buildingRepository->create($data);

            return redirect()
                ->route('residences.edit', $data['residence_id'])
                ->with('success', 'Edificio creado exitosamente.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Error al crear el edificio.');
        }
    }

    public function update(UpdateBuildingRequest $request, string $id)
    {
        try {
            $building = $this->buildingRepository->findBuilding($id);

            $this->authorize('update', $building);

            $data = $request->validated();

            $this->buildingRepository->update($building, $data);

            return redirect()->route('residences.edit', $building->residence_id)->with('success', 'Edificio actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('residences.edit', $building->residence_id)->with('error', 'Error al actualizar el edificio: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar una residencia específica de la base de datos.
     */
    public function destroy(string $id)
    {
        $building = $this->buildingRepository->findBuilding($id);
        $this->authorize('delete', $building);

        try {
            $this->buildingRepository->delete($id);
            return redirect()->route('residences.edit', $building->residence_id)->with('success', 'Edificio eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al eliminar el vigilante: ' . $e->getMessage());
        }
    }
}

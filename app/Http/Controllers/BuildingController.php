<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBuildingRequest;
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


        // try {
        //     $building = $this->buildingRepository->create($data);

        //     // Retorna el nuevo building para ser usado en Vue
        //     return response()->json(['message' => 'Edificio creado exitosamente.', 'building' => $building], 201);

        // } catch (\Exception $e) {
        //     return response()->json(['message' => 'Error al crear el edificio.', 'error' => $e->getMessage()], 500);
        // }
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

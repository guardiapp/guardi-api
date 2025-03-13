<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\StoreBuildingRequest;
use App\Http\Requests\UpdateBuildingRequest;
use App\Repositories\BuildingRepository;
use App\Models\Residence;
use App\Models\Building;
use App\Models\User;
use Inertia\Inertia;

class BuildingController extends Controller
{
    use AuthorizesRequests;

    protected $buildingRepository;

    public function __construct(BuildingRepository $buildingRepository)
    {
        $this->buildingRepository = $buildingRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Building::class);

        $filters = $request->only(['name', 'active']);

        $perPage = $request->input('per_page', 5);

        $page = $request->input('page', 1);

        $buildings = $this->buildingRepository->getAll($perPage, $page, $filters);

        return Inertia::render('Buildings/Index', [
            'buildings' => $buildings,
            'filters' => $filters
        ]);
    }

    /**
     * Mostrar los apartamentos filtrados por residencia.
     */
    public function indexByResidence(Request $request, $residenceId)
    {
        $residence = Residence::with('buildings')->findOrFail($residenceId);

        $this->authorize('viewByResidence', $residence);

        $filters = $request->only(['name', 'active']);

        $perPage = $request->input('per_page', 5);

        $page = $request->input('page', 1);

        $buildings = $this->buildingRepository->getByResidence($residence, $perPage, $page, $filters);

        return Inertia::render('Buildings/Index', [
            'buildings' => $buildings,
            'filters' => $filters,
            'residence' => [
                'id' => $residence->id,
                'name' => $residence->name,
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Building::class);

        $user = Auth::user();

        $data = [];

        if ($user->type === 'Admin') {
            $data['managers'] = $this->buildingRepository->getManagers();
            $data['residences'] = [];
        } elseif ($user->type === 'Manager') {
            $data['residences'] = $this->buildingRepository->getResidences();
        }

        return Inertia::render('Buildings/Create', $data);
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
                ->route('buildings.create')
                ->with('success', 'Edificio creado exitosamente.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Error al crear el edificio.');
        }
    }

    public function edit(string $id)
    {
        $building = $this->buildingRepository->findBuilding($id);

        $this->authorize('update', $building);

        $user = Auth::user();

        $data = [
            'building' => $building,
        ];

        if ($user->type === 'Admin') {
            $data['managers'] = $this->buildingRepository->getManagers();
            $data['residences'] = $this->buildingRepository->getResidences();
        } elseif ($user->type === 'Manager') {
            $data['residences'] = $this->buildingRepository->getResidences();
        }

        return Inertia::render('Buildings/Edit', $data);
    }

    public function update(UpdateBuildingRequest $request, string $id)
    {
        try {
            $building = $this->buildingRepository->findBuilding($id);

            $this->authorize('update', $building);

            $data = $request->validated();

            $this->buildingRepository->update($building, $data);

            $user = Auth::user();

            if ($user->type === 'Admin') {
                return redirect()->route('buildings.index')->with('success', 'Edificio actualizado exitosamente.');
            } else {
                return redirect()->route('buildings.indexByResidence', ['residenceId' => $building->residence_id])->with('success', 'Edificio actualizado exitosamente.');
            }

        } catch (\Exception $e) {
            return redirect()->route('buildings.edit', $building->residence_id)->with('error', 'Error al actualizar el edificio: ' . $e->getMessage());
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
            return redirect()->route('buildings.index')->with('success', 'Edificio eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al eliminar el vigilante: ' . $e->getMessage());
        }
    }

    public function findAll(Request $request) {
        $filters = $request->only(['name', 'active']);
        return response()->json(
            ['buildings' => Building::get()]
        );
    }
}

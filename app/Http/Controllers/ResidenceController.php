<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResidenceRequest;
use App\Http\Requests\UpdateResidenceRequest;
use Illuminate\Http\Request;
use App\Models\Residence;
use App\Models\Building;
use App\Repositories\ResidenceRepository;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ResidenceController extends Controller
{
    use AuthorizesRequests;

    protected $residenceRepository;

    public function __construct(ResidenceRepository $residenceRepository)
    {
        $this->residenceRepository = $residenceRepository;
    }

    /**
     * Listar todas las residencias para el usuario autenticado.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Residence::class);

        $perPage = $request->input('per_page', 5);
        $page = $request->input('page', 1);
        $residences = $this->residenceRepository->getAll($perPage, $page);
        return Inertia::render('Residences/Index', $residences);
    }

    /**
     * Mostrar el formulario para crear una nueva residencia.
     */
    public function create()
    {
        $this->authorize('create', Residence::class);

        $user = Auth::user();

        // Datos para la vista
        $data = [];

        if ($user->type === 'Admin') {
            $data['managers'] = $this->residenceRepository->getManagers();
        }

        return Inertia::render('Residences/Create', $data);
    }

    /**
     * Guardar una nueva residencia en la base de datos.
     */
    public function store(StoreResidenceRequest $request)
    {
        $this->authorize('create', Residence::class);

        $this->residenceRepository->create($request->validated());

        //return redirect()->route('residences.create')->with('success', 'Residence deleted successfully.');
        return response()->noContent();
    }

    /**
     * Mostrar una residencia específica.
     */
    public function show($id)
    {
        $residence = $this->residenceRepository->find($id);
        $this->authorize('view', $residence);

        return Inertia::render('Residences/Show', [
            'residence' => $residence,
        ]);
    }

    /**
     * Mostrar el formulario para editar una residencia específica.
     */
    public function edit($id)
    {
        $residence = $this->residenceRepository->findByUser($id);
        $this->authorize('update', $residence);

        return Inertia::render('Residences/Edit', [
            'residence' => $residence,
            'managers' => $this->residenceRepository->getManagers(),
        ]);
    }

    /**
     * Actualizar una residencia específica en la base de datos.
     */
    public function update(UpdateResidenceRequest $request, $id)
    {
        $residence = $this->residenceRepository->findByUser($id);
        $this->authorize('update', $residence);

        $this->residenceRepository->update($residence, $request->validated());
        return redirect()->route('residences.index')->with('success', 'Residence updated successfully.');
    }

    /**
     * Eliminar una residencia específica de la base de datos.
     */
    public function destroy(Request $request, $id)
    {
        $residence = $this->residenceRepository->findByUser($id);
        $this->authorize('delete', $residence);

        $this->residenceRepository->delete($residence);

        // Obtener las residencias actualizadas con los mismos parámetros
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);

        $residences = $this->residenceRepository->getAll($perPage, $page);

        // Devolver las residencias actualizadas como respuesta
        return response()->json($residences);
    }

    /**
     * Obtener edificios asociados a una residencia.
     */
    public function getBuildingsByResidence($residenceId)
    {
        $user = Auth::user();
        //$this->authorize('viewAny', Building::class);

        // Verificar que el usuario tiene permiso para acceder a los edificios
        if ($user->type === 'Admin') {
            // Admin puede acceder a todos los edificios de la residencia
            $buildings = Building::where('residence_id', $residenceId)->get();
        } elseif ($user->type === 'Manager') {
            // Manager solo puede acceder a los edificios de sus residencias
            $residence = $user->residences()->find($residenceId);

            if (!$residence) {
                abort(403, 'Unauthorized action.');
            }

            $buildings = $residence->buildings()->get();
        } else {
            abort(403, 'Unauthorized action.');
        }

        return response()->json(['buildings' => $buildings]);
    }
}

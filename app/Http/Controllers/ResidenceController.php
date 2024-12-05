<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResidenceRequest;
use App\Http\Requests\UpdateResidenceRequest;
use App\Models\Residence;
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
    public function index()
    {
        $this->authorize('viewAny', Residence::class);

        $residences = $this->residenceRepository->getAll();
        return Inertia::render('Residences/Index', ['residences' => $residences]);
    }

    /**
     * Mostrar el formulario para crear una nueva residencia.
     */
    public function create()
    {
        $this->authorize('create', Residence::class);

        return Inertia::render('Residences/Create', [
            'managers' => $this->residenceRepository->getManagers(),
        ]);
    }

    /**
     * Guardar una nueva residencia en la base de datos.
     */
    public function store(StoreResidenceRequest $request)
    {
        $this->authorize('create', Residence::class);

        $this->residenceRepository->create($request->validated());
        return redirect()->route('residences.index')->with('success', 'Residence created successfully.');
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
    public function destroy($id)
    {
        $residence = $this->residenceRepository->findByUser($id);
        $this->authorize('delete', $residence);

        $this->residenceRepository->delete($residence);
        return redirect()->route('residences.index')->with('success', 'Residence deleted successfully.');
    }
}

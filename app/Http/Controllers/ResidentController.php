<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResidentRequest;
use App\Http\Requests\UpdateResidentRequest;
use Illuminate\Http\Request;
use App\Models\Resident;
use App\Models\User;
use App\Repositories\ResidentRepository;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ResidentController extends Controller
{
    use AuthorizesRequests;

    protected $residentRepository;

    public function __construct(ResidentRepository $residentRepository)
    {
        $this->residentRepository = $residentRepository;
    }

    /**
     * Listar todas los residentes para el usuario autenticado.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Resident::class);

        $perPage = $request->input('per_page', 5);

        $page = $request->input('page', 1);

        $residents = $this->residentRepository->getAll($perPage, $page);

        return Inertia::render('Residents/Index', $residents);
    }

    /**
     * Mostrar el formulario para crear una nueva residencia.
     */
    public function create()
    {
        $this->authorize('create', Resident::class);

        $user = Auth::user();

        $data = [];

        if ($user->type === 'Admin') {
            $data['managers'] = $this->residentRepository->getManagers();
            $data['residences'] = [];
            $data['buildings'] = [];
        } elseif ($user->type === 'Manager') {
            $data['residences'] = $this->residentRepository->getResidences();
            $data['buildings'] = $this->residentRepository->getBuildings();
        }

        return Inertia::render('Residents/Create', $data);
    }

    /**
     * Guardar un nuevo residente en la base de datos.
     */
    public function store(StoreResidentRequest $request)
    {
        $this->authorize('create', Resident::class);

        try {
            $data = $request->validated();

            $data['avatar'] = $request->file('avatar');

            $guard = $this->residentRepository->create($data);
            return redirect()->route('residents.create')->with('success', 'Residente creado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('residents.create')->with('error', 'Error al crear el residente: ' . $e->getMessage());
        }
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
    public function edit(string $id)
    {
        $resident = $this->residentRepository->findResident($id);

        $this->authorize('update', $resident);

        $user = Auth::user();

        $data = [];

        $data['resident'] = $resident;

        if ($user->type === 'Admin') {
            $data['managers'] = $this->residentRepository->getManagers();
            $data['residences'] = $this->residentRepository->getResidences();
            $data['buildings'] = $this->residentRepository->getBuildings();
        } elseif ($user->type === 'Manager') {
            $data['residences'] = $this->residentRepository->getResidences();
            $data['buildings'] = $this->residentRepository->getBuildings();
        }

        return Inertia::render('Residents/Edit', $data);
    }


    /**
     * Actualizar una residencia específica en la base de datos.
     */
    public function update(UpdateResidentRequest $request, string $id)
    {
        try {
            $resident = $this->residentRepository->findResident($id);

            $this->authorize('update', $resident);

            $data = $request->validated();
            $avatarFile = $request->file('avatar');
            $avatarDeleted = $request->input('avatar_deleted', false);

            $this->residentRepository->update($resident, $data, $avatarFile, $avatarDeleted);

            return redirect()->route('residents.index')->with('success', 'Residente actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('residents.index')->with('error', 'Error al actualizar el residente: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar un residente específic0 de la base de datos.
     */
    public function destroy(string $id)
    {
        // Encuentra el resident desde el repositorio y verifica autorización
        $resident = $this->residentRepository->findResident($id);
        $this->authorize('delete', $resident);

        try {
            $this->residentRepository->delete($id);
            return redirect()->route('residents.index')->with('success', 'Residente eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('residents.index')->with('error', 'Error al eliminar el residente: ' . $e->getMessage());
        }
    }
}

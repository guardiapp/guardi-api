<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\ManagerRepository;
use App\Http\Requests\ManagerStoreRequest;
use App\Http\Requests\ManagerUpdateRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class ManagerController extends Controller
{
    use AuthorizesRequests;

    protected $managerRepository;

    /**
     * Inyección del repositorio.
     */
    public function __construct(ManagerRepository $managerRepository)
    {
        $this->managerRepository = $managerRepository;
    }

    /**
     * Listar todos los managers con sus residencias.
     */
    public function index()
    {
        // Verificar autorización
        $this->authorize('viewAny', User::class);

        // Obtener managers con residencias
        $managers = $this->managerRepository->getAllWithResidences();
        return Inertia::render('Managers/Index', ['managers' => $managers]);
    }

    /**
     * Mostrar el formulario de creación.
     */
    public function create()
    {
        // Verificar autorización
        $this->authorize('create', 'App\Models\User');

        return Inertia::render('Managers/Create');
    }

    /**
     * Guardar un nuevo manager.
     */
    public function store(ManagerStoreRequest $request)
    {
        // Verificar autorización
        $this->authorize('create', 'App\Models\User');

        // Crear nuevo manager
        $this->managerRepository->create($request->validated());
        return redirect()
            ->route('managers.index')
            ->with('success', 'Manager created successfully.');
    }

    /**
     * Editar un manager existente.
     */
    public function edit($id)
    {
        // Verificar autorización

        // Obtener manager específico
        $manager = $this->managerRepository->findWithResidences($id);
        $this->authorize('update', $manager);
        return Inertia::render('Managers/Edit', [
            'manager' => $manager,
        ]);
    }

    /**
     * Actualizar un manager existente.
     */
    public function update(ManagerUpdateRequest $request, $id)
    {
        // Verificar autorización
        $this->authorize('update', 'App\Models\User');

        // Actualizar manager
        $this->managerRepository->update($id, $request->validated());
        return redirect()
            ->route('managers.index')
            ->with('success', 'Manager updated successfully.');
    }

    /**
     * Eliminar un manager existente.
     */
    public function destroy($id)
    {
        // Verificar autorización
        $this->authorize('delete', 'App\Models\User');

        // Eliminar manager
        $this->managerRepository->delete($id);
        return redirect()
            ->route('managers.index')
            ->with('success', 'Manager deleted successfully.');
    }
}

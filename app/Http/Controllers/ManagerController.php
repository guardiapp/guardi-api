<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Repositories\ManagerRepository;
use App\Http\Requests\StoreManagerRequest;
use App\Http\Requests\UpdateManagerRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Residence;


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
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $perPage = $request->input('per_page', 5);
        $page = $request->input('page', 1);
        $managers = $this->managerRepository->getAllWithResidences($perPage, $page);
        return Inertia::render('Managers/Index', $managers);
    }

    /**
     * Mostrar el formulario de creación.
     */
    public function create()
    {

        $this->authorize('create', User::class);

        return Inertia::render('Managers/Create');
    }

    /**
     * Guardar un nuevo manager.
     */
    public function store(StoreManagerRequest $request)
    {
        $this->authorize('create', User::class);

        try {
            $data = $request->validated();

            $data['avatar'] = $request->file('avatar');

            $manager = $this->managerRepository->create($data);
            return redirect()->route('managers.create')->with('success', 'Administrador creado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('managers.create')->with('error', 'Error al crear el administrador: ' . $e->getMessage());
        }
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
    public function update(UpdateManagerRequest $request, $id)
    {
        try {
            $manager = $this->managerRepository->findWithResidences($id);

            $this->authorize('update', $manager);

            $data = $request->validated();
            $avatarFile = $request->file('avatar');
            $avatarDeleted = $request->input('avatar_deleted', false);
            $this->managerRepository->update($manager, $data, $avatarFile, $avatarDeleted);

            return redirect()->route('managers.index')->with('success', 'Vigilante actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('managers.index')->with('error', 'Error al actualizar el vigilante: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar una residencia específica de la base de datos.
     */
    public function destroy(string $id)
    {
        // Encuentra el manager desde el repositorio y verifica autorización
        $manager = $this->managerRepository->findWithResidences($id);
        $this->authorize('delete', $manager);

        try {
            $this->managerRepository->delete($id);
            return redirect()->route('managers.index')->with('success', 'Vigilante eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('managers.index')->with('error', 'Error al eliminar el vigilante: ' . $e->getMessage());
        }
    }

    /**
     * Obtener residencias asociadas a un administrador.
     */
    public function getResidencesByManager($managerId)
    {
        $user = Auth::user();
        $this->authorize('viewAny', Residence::class);

        // Asegúrate de que el usuario autenticado tiene permiso para ver las residencias
        if ($user->type === 'Admin') {
            $residences = Residence::where('user_id', $managerId)->get();
        } elseif ($user->type === 'Manager') {
            $residences = $user->residences()->get();
        } else {
            abort(403, 'Unauthorized action.');
        }

        return response()->json(['residences' => $residences]);
    }
}

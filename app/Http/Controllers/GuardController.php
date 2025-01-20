<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\StoreGuardRequest;
use App\Http\Requests\UpdateGuardRequest;
use App\Repositories\GuardRepository;
use App\Models\Residence;
use App\Models\Guard;
use App\Models\User;
use Inertia\Inertia;

class GuardController extends Controller
{
    use AuthorizesRequests;

    protected $guardRepository;

    public function __construct(GuardRepository $guardRepository)
    {
        $this->guardRepository = $guardRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Guard::class);

        $filters = $request->only(['document', 'name', 'email', 'active']);

        $perPage = $request->input('per_page', 5);

        $page = $request->input('page', 1);

        $guards = $this->guardRepository->getAll($perPage, $page, $filters);

        return Inertia::render('Guards/Index', [
            'guards' => $guards,
            'filters' => $filters
        ]);
    }

    /**
     * Mostrar los vigilantes filtrados por residencia.
     */
    public function indexByResidence(Request $request, $residenceId)
    {
        $residence = Residence::with('guards')->findOrFail($residenceId);

        $this->authorize('viewByResidence', $residence);

        $filters = $request->only(['document', 'name', 'email', 'active']);

        $perPage = $request->input('per_page', 5);

        $page = $request->input('page', 1);

        $guards = $this->guardRepository->getByResidence($residence, $perPage, $page, $filters);

        return Inertia::render('Guards/Index', [
            'guards' => $guards,
            'residence' => $residence,
            'filters' => $filters
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Guard::class);

        $user = Auth::user();

        $data = [];

        if ($user->type === 'Admin') {
            $data['managers'] = $this->guardRepository->getManagers();
            $data['residences'] = [];
        } elseif ($user->type === 'Manager') {
            $data['residences'] = $this->guardRepository->getResidences();
        }

        return Inertia::render('Guards/Create', $data);
    }

    /**
     * Guardar un nuevo Guard en el sistema.
     */
    public function store(StoreGuardRequest $request)
    {
        $this->authorize('create', Guard::class);

        try {
            $data = $request->validated();

            $data['avatar'] = $request->file('avatar');

            $guard = $this->guardRepository->create($data);
            return redirect()->route('guards.create')->with('success', 'Vigilante creado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('guards.create')->with('error', 'Error al crear el vigilante: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $guard = $this->guardRepository->findGuard($id);

        $this->authorize('update', $guard);

        $user = Auth::user();

        $data = [];

        $data['guard'] = $guard;

        if ($user->type === 'Admin' || $user->type === 'Manager') {

            $data['residences'] = $this->guardRepository->getResidences();

            if ($user->type === 'Admin') {
                $data['managers'] = $this->guardRepository->getManagers();
            }
        }

        return Inertia::render('Guards/Edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGuardRequest $request, string $id)
    {
        try {
            $guard = $this->guardRepository->findGuard($id);

            $this->authorize('update', $guard);

            $data = $request->validated();
            $avatarFile = $request->file('avatar'); // Archivos cargados
            $avatarDeleted = $request->input('avatar_deleted', false); // Estado de eliminación del avatar

            $this->guardRepository->update($guard, $data, $avatarFile, $avatarDeleted);

            return redirect()->route('guards.index')->with('success', 'Vigilante actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('guards.index')->with('error', 'Error al actualizar el vigilante: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Encuentra el guard desde el repositorio y verifica autorización
        $guard = $this->guardRepository->findGuard($id);
        $this->authorize('delete', $guard);

        try {
            $this->guardRepository->delete($id);
            return redirect()->route('guards.index')->with('success', 'Vigilante eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('guards.index')->with('error', 'Error al eliminar el vigilante: ' . $e->getMessage());
        }
    }
}

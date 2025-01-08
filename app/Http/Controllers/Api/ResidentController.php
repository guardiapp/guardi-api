<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreResidentRequest;
use App\Http\Requests\UpdateResidentRequest;
use App\Repositories\ResidentRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Resident;

class ResidentController extends Controller
{
    use AuthorizesRequests;

    protected $residentRepository;

    public function __construct(ResidentRepository $residentRepository)
    {
        $this->residentRepository = $residentRepository;
    }

    public function index(Request $request): JsonResponse
    {

        $this->authorize('viewAny', Resident::class);

        $perPage = $request->input('per_page', 10);

        $page = $request->input('page', 1);

        $residents = $this->residentRepository->getAll($perPage, $page);

        return response()->json($residents);
    }

    public function store(StoreResidentRequest $request): JsonResponse
    {
        $this->authorize('create', Resident::class);

        try {
            $data = $request->validated();
            $data['avatar'] = $request->file('avatar'); // Obtiene archivo cargado

            // Lógica para crear residente
            $resident = $this->residentRepository->create($data);

            return response()->json([
                'message' => 'Residente creado exitosamente.',
                'data' => $resident,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear el residente.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        $residence = $this->residenceRepository->findByUser($id);
        $this->authorize('view', $residence);

        return response()->json($residence);
    }

    public function update(UpdateResidentRequest $request, $id): JsonResponse
    {
        try {
            $resident = $this->residentRepository->findResident($id);

            $this->authorize('update', $resident);

            $data = $request->validated();
            $avatarFile = $request->file('avatar');
            $avatarDeleted = $request->input('avatar_deleted', false);

            $this->residentRepository->update($resident, $data, $avatarFile, $avatarDeleted);

            return response()->json([
                'message' => 'Residente actualizado exitosamente.',
                'data' => $resident,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el residente.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        $residence = $this->residenceRepository->findByUser($id);
        $this->authorize('delete', $residence);
        $this->residenceRepository->delete($residence);
        return response()->json(['message' => 'Residence deleted successfully.']);
    }
}

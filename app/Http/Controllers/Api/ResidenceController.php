<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller; // Importar correctamente la clase base
use App\Http\Requests\StoreResidenceRequest;
use App\Http\Requests\UpdateResidenceRequest;
use App\Repositories\ResidenceRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ResidenceController extends Controller
{
    use AuthorizesRequests;

    protected $residenceRepository;

    public function __construct(ResidenceRepository $residenceRepository)
    {
        $this->residenceRepository = $residenceRepository;
    }

    public function index(): JsonResponse
    {
        $this->authorize('viewAny', Residence::class);

        $residences = $this->residenceRepository->getAll();
        return response()->json($residences);
    }

    public function store(StoreResidenceRequest $request): JsonResponse
    {
        $this->authorize('create', Residence::class);

        $residence = $this->residenceRepository->create($request->validated());
        return response()->json(['message' => 'Residence created successfully.', 'residence' => $residence]);
    }

    public function show($id): JsonResponse
    {
        $residence = $this->residenceRepository->findByUser($id);
        $this->authorize('view', $residence);

        return response()->json($residence);
    }

    public function update(UpdateResidenceRequest $request, $id): JsonResponse
    {
        $residence = $this->residenceRepository->findByUser($id);
        $this->authorize('update', $residence);
        $this->residenceRepository->update($residence, $request->validated());
        return response()->json(['message' => 'Residence updated successfully.', 'residence' => $residence]);
    }

    public function destroy($id): JsonResponse
    {
        $residence = $this->residenceRepository->findByUser($id);
        $this->authorize('delete', $residence);
        $this->residenceRepository->delete($residence);
        return response()->json(['message' => 'Residence deleted successfully.']);
    }
}

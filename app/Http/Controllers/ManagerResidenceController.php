<?php

namespace App\Http\Controllers;

use App\Repositories\ManagerResidenceRepository;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class ManagerResidenceController extends Controller
{
    use AuthorizesRequests;

    protected $managerResidenceRepository;

    public function __construct(ManagerResidenceRepository $managerResidenceRepository) {
        $this->managerResidenceRepository = $managerResidenceRepository;
    }

    public function findResidencesByManagerId(Request $request)
    {
        try {
            $managerId = $request->managerId;
            return response()->json(
                $this->managerResidenceRepository
                    ->findAllResidencesByManager($managerId)
            );
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ]);
        }
    }

    public function findManagersByResidenceId(Request $request) {
        try {
            $residenceId = $request->residenceId;
            return response() ->json(
                $this->managerResidenceRepository
                        ->findAllManagersByResidence($residenceId)
            );
        } catch (\Exception $e) {
            return response() ->json([
                "success" => false,
                "message" => $e->getMessage()
            ]);
        }
    }

    public function findManagersWhereResidenceNotIn(Request $request) {
        try {
            $residenceId = $request->residenceId;
            return response()->json(
                $this->managerResidenceRepository
                    ->findAllManagersWhereResidenceNotIn($residenceId)
            );
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ]);
        }
    }

    public function createBulk(Request $request) {
        try {
            $this->managerResidenceRepository->createBulk($request->payload);
            return response()->json([
                "success" => true,
                "message" => "relacion generada con exito."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ]);
        }
    }

    public function deleteByManagerId(Request $request) {
        try {
            $managerId = $request->managerId;
            $this->managerResidenceRepository->deleteByManagerId($managerId);
            return response()->json([
                "success" => true
            ]);
        } catch (\Exception $e) {
            return response() ->json([
                "success" => false,
                "message" => "Ocurrio un error al elminar las relaciones: ".$e->getMessage()
            ]);
        }
    }

    public function deleteByResidenceId(Request $request) {
        try {
            $residenceId = $request->residenceId;
            $this->managerResidenceRepository->deleteByResidenceId($residenceId);
            return response() ->json([
                "success" => true
            ]);
        } catch (\Exception $e) {
            return response() ->json([
                "success" => false,
                "message" => "Error al eliminar las relaciones: ".$e->getMessage()
            ]);
        }
    }
}

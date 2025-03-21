<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Repositories\VisitRepository;
use Illuminate\Http\Request;

class VisitController extends Controller
{
    protected $visitRepository;
    public function __construct(VisitRepository $visitRepository)
    {
        $this->visitRepository = $visitRepository;
    }

    public function index(Request $request) {
        $filters = $request->only([
            'visitor_name',
            'resident_name',
            'apartment',
            'visit_date',
            'expiration_date',
            'normalTab',
            'visitType',
            'entry_time',
        ]);

        return response()->json(
            $this->visitRepository->getAll(
                $request->input('per_page', 5),
                $request->input('page', 1),
                $filters
            )
        );
    }

    public function show(Request $request) {
        $id = $request->id;
        return response()->json(
            $this->visitRepository->findById($id)
        );
    }

    public function store(Request $request) {
        try {
            return response()->json(
                $this->visitRepository->saveOrUpdate($request->all())
            );
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ]);
        }
    }

    public function update(Request $request) {
        $id = $request->id;
        try {
            return response()->json(
                $this->visitRepository->saveOrUpdate(
                    $request->all(),
                    $id
                )
            );
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ]);
        }
    }

    public function destroy(Request $request) {
        $id = $request->id;
        if(
            $this->visitRepository->deleteById($id)
        ) return response()->json([ "success" => true ]);
        return response()->json([ "success" => false ]);
    }
}

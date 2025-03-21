<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\VisitorRepository;

class VisitorController extends Controller
{
    public function __construct(
        public VisitorRepository $visitorRepository
    ) {}

    public function index(Request $request) {
        $filters = $request->only([
            'name',
            'document',
            'apartment',
            'resident_name',
        ]);

        $perPage = $request->input('per_page', 5);
        $page = $request->input('page', 1);
        return response()->json(
            $this->visitorRepository->getAll(
                $perPage,
                $page,
                $filters
            )
        );
    }

    public function store(Request $request) {
        return response()->json(
            $this->visitorRepository->saveOrUpdate(
                $request->all()
            )
        );
    }

    public function update(Request $request) {
        return response()->json(
            $this->visitorRepository->saveOrUpdate(
                $request->all(),
                $request->id
            )
        );
    }

    public function destroy(Request $request) {
        return response()->json([
            "success" => $this->visitorRepository->deleteById(
                $request->id
            )
        ]);
    }

    public function show(Request $request) {
        return response()->json(
            $this->visitorRepository->findById(
                $request->id
            )
        );
    }
}
